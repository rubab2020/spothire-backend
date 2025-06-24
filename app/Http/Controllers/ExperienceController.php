<?php

namespace App\Http\Controllers;

use App\Employer;
use App\Occupation;
use App\Rating;
use App\Skill;
use App\Tag;
use App\User;
use App\Experience;
use App\ExperienceImage;
use Illuminate\Http\Request;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Transformers\WorkerProfileTransformer;
use App\SpotHire\Transformers\CustomTransformer;
use App\SpotHire\Helpers\CustomHelper;

class ExperienceController extends ApiController
{
    /**
     * @var integer
     **/
    private $clientInfo;

    /**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    /**
    * @var App\SpotHire\Transformers\WorkerProfileTransformer
    **/
    protected $workerProfileTransformer;

    function __construct(EncodeHelper $encodeHelper, WorkerProfileTransformer $workerProfileTransformer, CustomTransformer $customTransformer)
    {
        $this->clientInfo = auth()->guard('api')->authenticate();
        $this->encodeHelper = $encodeHelper;
        $this->workerProfileTransformer = $workerProfileTransformer;
        $this->customTransformer = $customTransformer;
    }

    public function aboutExperience(Request $request){
        return $this->respond($this->getExperienceData($this->clientInfo));
    }

        /**
     * undocumented function
     *
     * @return void
     **/
    public function getExperienceData($user){
        $tags        = Tag::where('is_active',1)->get();
        $employers   = Employer::where('is_active',1)->get();
        $occupations = Occupation::where('is_active',1)->get();
        $experiences  = Experience::with('workImages')
                                        ->where('user_id',$user->id)
                                        ->orderBy('period_from', 'desc')
                                        ->get();
        $experiences  = Experience::updateCompanyNameIfAssgined($experiences);

        return [
            'data' => $this->workerProfileTransformer->transformExperiences($experiences),
            'employers'=> $this->customTransformer->transformEmployers($employers),
            'occupations'=> $this->customTransformer->transformOccupations($occupations)
        ];
    }

    public function SaveAboutExperience(Request $request){
        $experiences = json_decode($request->input('data'), true);
        $alluploadImages = $request->file('images');

        foreach($experiences as $experience){
            $experienceObj = !CustomHelper::IsNullOrEmptyString($experience['id']) 
                                ? Experience::find($this->encodeHelper->decodeData($experience['id']))
                                : new Experience;

            if($experienceObj == null) continue;

            if( !CustomHelper::IsNullOrEmptyString($experience['occupation']) 
                || !CustomHelper::IsNullOrEmptyString($experience['employer']) 
                || !CustomHelper::IsNullOrEmptyString($experience['period_from']) 
                || !CustomHelper::IsNullOrEmptyString($experience['period_to'])
            ){
                // create employer and occupation
                if(!CustomHelper::IsNullOrEmptyString($experience['employer'])){
                    Employer::firstOrCreate(['name' => $experience['employer']]);
                }
                if(!CustomHelper::IsNullOrEmptyString($experience['occupation'])){
                    Occupation::firstOrCreate(['name' => $experience['occupation']]);
                }

                $experienceObj->user_id     = $this->clientInfo->id;
                $experienceObj->occupation  = $experience['occupation'];
                $experienceObj->employer    = $experience['employer'];
                $experienceObj->period_from = !CustomHelper::IsNullOrEmptyString($experience['period_from']) 
                                                        ? date('Y-m-d',strtotime($experience['period_from'])) 
                                                        : $experience['period_from'];
                $experienceObj->period_to   = !CustomHelper::IsNullOrEmptyString($experience['period_to'])
                                                ? date('Y-m-d',strtotime($experience['period_to'])) 
                                                : $experience['period_to'];


                $experienceObj->continuing  =   !CustomHelper::IsNullOrEmptyString($experience['period_from'] 
                                                    && CustomHelper::IsNullOrEmptyString($experience['period_to']))                                     
                                                    ? true : false;

                $experienceObj->save();
            }

            // save empty work experience
            if($experienceObj->id == null && $this->isImageUploaded($experience['work_images'])) {
                $experienceObj->user_id = $this->clientInfo->id;
                $experienceObj->save();
            }
            
            $alluploadImages = $this->saveWorkImages($experience['work_images'], $experienceObj->id, $alluploadImages);
        }
        return $this->respond($this->getExperienceData($this->clientInfo));
    }

    /**
     * save work images
     *
     * @return array
     **/
    private function saveWorkImages($images, $experienceID, $alluploadImages)
    {
        $uploadImgIndex = 0;
        
        foreach($images as $image) {
            if($image['id']){ // exsiting data
                $obj = ExperienceImage::find($this->encodeHelper->decodeData($image['id']));
                if($obj == null) continue;
                
                if($image['image'] == 'delete'){
                    $this->deleteOldImage($obj->image);
                    $this->deleteOldImage($obj->image_sm);

                    $obj->delete();
                }
                else{
                    if($image['image'] == null) continue;

                    $imgName = $this->saveImage($alluploadImages[$uploadImgIndex], null, 300);
                    if($imgName == null) continue;
                    
                    $this->deleteOldImage($obj->image);
                    $this->deleteOldImage($obj->image_sm);

                    $obj->image = $imgName;
                    $obj->image_sm = $this->saveImage($alluploadImages[$uploadImgIndex], null, 180);
                    $obj->description = $image['description'];
                    $obj->save();

                    $uploadImgIndex++;
                }
                
            }
            else{ // new data
                if($image['image'] == null) continue;

                $obj = new ExperienceImage();

                $imgName = $this->saveImage($alluploadImages[$uploadImgIndex], null, 300);
                if($imgName == null) continue;

                $this->deleteOldImage($obj->image);
                $this->deleteOldImage($obj->image_sm);

                $obj->image = $imgName;
                $obj->image_sm = $this->saveImage($alluploadImages[$uploadImgIndex], null, 180);
                $obj->description = $image['description'];
                $obj->work_id = $experienceID;
                $obj->save();

                $uploadImgIndex++;
            }
        }

        // remove uploaded images from all upload images
        for($i = 0; $i < $uploadImgIndex; $i++) {
            unset($alluploadImages[$i]);
        }
        if(sizeof($alluploadImages) > 0)
            $alluploadImages = array_values($alluploadImages);
        
        return $alluploadImages;
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    private function saveImage($image, $width, $height)
    {  
        $imgName = date('mdYHis').uniqid();
        $path = public_path('/images/profile/' 
                . $this->encodeHelper->encodeData($this->clientInfo->id) . '/experience_images/');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $imageLink = $path.$imgName.'.jpg';
        $upload = \Image::make($image->getRealPath());
        $upload->orientate();
        $upload->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($imageLink);

        return $upload ? $imgName.'.jpg' : null;
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function isImageUploaded($images)
    {
        foreach($images as $image) {
            if($image['image']) {
                return true;
            }
        }

        return false;
    }

    public function deleteExperience(Request $request){
        $credentials = $request->all();
        
        $workId = $this->encodeHelper->decodeData($credentials['work_id']);

        $workImages = ExperienceImage::where('work_id', $workId)->get();

        $delete = Experience::where('user_id',$this->clientInfo->id)
                                    ->where('id',$workId)
                                    ->delete();
        
        
        if($delete) $this->deleteOldImages($workImages);

        if(!$delete) return $this->respondInternalError('Failed Deleting experience.');
        
        return  $this->respondDeleteingResource('Experience deleted successfully.');
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    private function deleteOldImages($workImages)
    {
        foreach($workImages as $item){
            $this->deleteOldImage($item->image);
            $this->deleteOldImage($item->image_sm);
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

        $fileName = Experience::getPhotoPath($this->clientInfo->id).$image;
        if(file_exists($fileName))  \File::delete($fileName);
    }
}