<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Transformers\CustomTransformer;
use App\SpotHire\Transformers\WorkerProfileTransformer;
use App\Tag;
use App\Skill;

class UserController extends ApiController
{
    /**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    private $userTypes;

    /**
     * @var integer
     **/
    private $clientInfo;

    /**
    * @var App\SpotHire\Transformers\WorkerProfileTransformer
    **/
    protected $workerProfileTransformer;

    private $customTransformer;

    function __construct(
        ConfigHelper $configHelper, 
        EncodeHelper $encodeHelper, 
        CustomTransformer $customTransformer,  
        WorkerProfileTransformer $workerProfileTransformer
    ){
        $this->userTypes = $configHelper->getUserTypes();
        $this->clientInfo = auth()->guard('api')->authenticate();
        $this->encodeHelper = $encodeHelper;
        $this->customTransformer = $customTransformer;
        $this->workerProfileTransformer = $workerProfileTransformer;
    }

    public function getAboutYourself(Request $request){
        $user = $this->clientInfo;

        if($user->user_type == $this->userTypes['individual']){
            $user['skills'] = Skill::where('user_id',$user->id)->get()->toArray();
            $tags = Tag::all();
            
            return $this->respond([
                'data' => $this->userDataTransform($user),
                'tags' => $this->customTransformer->transformTags($tags)
            ]);
        }
        else{
            return $this->respond([
                'data' => $this->userDataTransform($user),
            ]);
        }


    }

    public function userProfileData(){
        $user = User::where('id',$this->clientInfo->id)->first();

        return $this->respond(['data'=> $this->transFormUserProfileData($user)]);
    }

    public function transFormUserProfileData($user){
        $data            = $user;
        $data['picture'] = ($user['picture'] != '' && $user['picture'] != null) 
                            ? User::getPhotoPath($user['id']).$user['picture'] 
                            : null;
        return $data;
    }

    public function SaveAboutYourself(Request $request)
    {
        // note: image upload size validaion not found
        $user = $this->clientInfo;
        $credentials = $request->all();

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');

            $imageName = $this->saveImage($image, null, 500);
            $imageNameSm = $this->saveImage($image, null, 250);

            $this->deleteOldImage($this->clientInfo->picture);
            $this->deleteOldImage($this->clientInfo->picture_sm);

            $this->clientInfo->picture = $imageName;
            $this->clientInfo->picture_sm = $imageNameSm;
        }

        if ($request->hasFile('cover_photo')) {
            $image = $request->file('cover_photo');

            $imageName = $this->saveImage($image, null, 900);
            $imageNameSm = $this->saveImage($image, null, 400);

            $this->deleteOldImage($this->clientInfo->cover_photo);
            $this->deleteOldImage($this->clientInfo->cover_photo_sm);

            $this->clientInfo->cover_photo = $imageName;
            $this->clientInfo->cover_photo_sm = $imageNameSm;
        }

        if($user->user_type == $this->userTypes['individual']){
            $this->clientInfo->about = !CustomHelper::IsNullOrEmptyString($credentials['about_yourself'])
                                            ? $credentials['about_yourself']
                                            : null;
            $this->clientInfo->dob = !CustomHelper::IsNullOrEmptyString($credentials['dob']) 
                                            ? date('Y-m-d',strtotime($credentials['dob']))
                                            : null;
            $this->clientInfo->gender = !CustomHelper::IsNullOrEmptyString($credentials['gender'])
                                            ? $credentials['gender']
                                            : null;
            $this->clientInfo->save();

            if(isset($credentials['skills']))
                $this->saveSkills(explode(',', $credentials['skills']));
            else
                $deleteSkills = Skill::where('user_id',$this->clientInfo->id)->delete();

            $this->clientInfo['skills'] = Skill::where('user_id',$this->clientInfo->id)->get()->toArray();
        }
        else{
            $this->clientInfo->about =  !CustomHelper::IsNullOrEmptyString($credentials['about'])
                                            ? $credentials['about']
                                            : null;

            $this->clientInfo->company_expertise =  !CustomHelper::IsNullOrEmptyString($credentials['expertise'])
                                                        ? $credentials['expertise']
                                                        : null;

            $this->clientInfo->save();
        }
        

        return $this->respond(['data' => $this->userDataTransform($this->clientInfo)]);
    }

    private function saveSkills($skills) {
        $userSkills = Skill::where('user_id',$this->clientInfo->id)
                    ->groupBy('name')->get()->pluck('name')->toArray();

        $deletedSkills = array_diff($userSkills, $skills);
        $addedSkills = array_diff($skills, $userSkills);

        foreach($deletedSkills as $deleted){
            Skill::where('user_id',$this->clientInfo->id)
                    ->where('name',$deleted)
                    ->delete();
        }
        foreach($addedSkills as $skillName){
            $totalTags = Tag::where('name',$skillName)->count();

            if($totalTags == 0){
                $Tag = new Tag();
                $Tag->name = $skillName;
                $Tag->save();
            }

            $skill  = new Skill();
            $skill->name = $skillName;
            $skill->user_id = $this->clientInfo->id;
            $skill->save();
        }
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function saveImage($image, $width, $height)
    {
        $imgName = date('mdYHis') . uniqid() . '.' . $image->getClientOriginalExtension();
        $path = public_path('/images/profile/' . $this->encodeHelper->encodeData($this->clientInfo->id) . '/profile_images/');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $imageLink = $path.$imgName;
        $upload = \Image::make($image->getRealPath());
        $upload->orientate();
        $upload->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($imageLink);

        return $upload ? $imgName : null;
    }

    public function userDataTransform($user){
        if($user->user_type == $this->userTypes['individual']){
            return array(
                'name'              => $user['name'],
                'email'             => $user['email'],
                'about'             => $user['about'] ? $user['about'] : '',
                'dob'               => $user['dob'] ? $user['dob']  : '',
                'gender'            => $user['gender'] ? $user['gender'] : '',
                'profile_picture'   => ($user['picture'] != '' && $user['picture'] != null) 
                                        ? User::getPhotoPath($user['id']).$user['picture'] 
                                        : null,
                'cover_photo'       => ($user['cover_photo'] != '' && $user['cover_photo'] != null) 
                                        ? User::getPhotoPath($user['id']).$user['cover_photo'] 
                                        : null,
                'skills'            => $this->workerProfileTransformer->transformSkills($user['skills'])
            );
        }
        else{
            return array(
                'name'              => $user['name'],
                'email'             => $user['email'],
                'about'             => $user['about'] ? $user['about'] : '',
                'expertise'         => $user['company_expertise'] ? $user['company_expertise'] : '',
                'profile_picture'   => ($user['picture'] != '' && $user['picture'] != null) 
                                        ? User::getPhotoPath($user['id']).$user['picture'] 
                                        : null,
                'cover_photo'       => ($user['cover_photo'] != '' && $user['cover_photo'] != null) 
                                        ? User::getPhotoPath($user['id']).$user['cover_photo'] 
                                        : null,
            );
        }
    }

    /**
     * check whether complete now popup allowed or now
     *
     * @param int $userId
     * @param int $userType
     * @return boolean
     **/
    public function isCompleteNowPopupAllowed($userId, $userType)
    {
        if($userType == $this->userTypes['individual']){
            $user = User::with('qualifications',
                'experiences',
                'awards',
                'skills')
                ->where('id',$userId)
                ->where('user_type',$userType)
                ->first();

            return ($user->qualifications->isEmpty()
                && $user->experiences->isEmpty()
                && $user->awards->isEmpty()
                && $user->skills->isEmpty()
                && $user->about == null) 
                ? true : false;
        }
        else{
            $user = User::with('companyBackground')
                ->where('id',$userId)
                ->where('user_type',$userType)
                ->first();

            return (empty($user->companyBackground) 
                && $user->about == null
                && $user->compnay_expertise == null) 
                ? true : false;
        }
    }


    /**
     * email phone privacy control in order to hide/unhide those data
     *
     * @return respoonse
     **/
    public function updateEmailPhonePrivacy(Request $request)
    {
        $user = User::find($this->clientInfo->id);
        if(!$user){
            return $this->respondValidationError('User Not found');
        }

        $user->is_email_visible = $request->input('is_email_visible');
        $user->is_phone_visible = $request->input('is_phone_visible');

        if($user->save()){
            return $this->respondUpdatingResource('Email/phone privacy updated successfully');
        }
        else{
            return $this->respondInternalError('Failed updating email/phone privacy update');
        }
    }

    /**
     * delete old image
     *
     * @param string $iamge
     * @return void
     **/
    public function deleteOldImage($image)
    {   
        if($image == null) return;

        $fileName = User::getPhotoPath($this->clientInfo->id).$image;
        if(file_exists($fileName))  \File::delete($fileName);
    }
}