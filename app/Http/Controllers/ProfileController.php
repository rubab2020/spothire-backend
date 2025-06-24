<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\CompanyImage;
use App\CompanyBackground;
use App\Experience;
use App\JobApplication;
use App\Job;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Transformers\HireProfileTransformer;
use App\SpotHire\Transformers\WorkerProfileTransformer;

class ProfileController extends ApiController
{
    private $userTypes;
    private $encodeHelper;
    private $userId;
    private $clientInfo;
    private $hireProfileTransformer;
    private $workerProfileTransformer;

    function __construct(
        ConfigHelper $configHelper, 
        EncodeHelper $encodeHelper, 
        HireProfileTransformer $hireProfileTransformer,  
        WorkerProfileTransformer $workerProfileTransformer
    ){
        $this->userTypes = $configHelper->getUserTypes();
        $this->encodeHelper = $encodeHelper;
        $this->hireProfileTransformer = $hireProfileTransformer;
        $this->workerProfileTransformer = $workerProfileTransformer; 
        
        $this->clientInfo == null;
        $url = url()->current();
        if (strpos($url, 'public') === false) {
            $this->clientInfo = auth()->guard('api')->authenticate();
        }
    }


    /**
     * undocumented function
     *
     * @return void
     **/
    public function getProfile()
    {   
        $this->userId = $this->clientInfo->id;

        if($this->clientInfo->user_type == $this->userTypes['individual']){
            $user = User::with(
                    ['experiences.workImages', 
                    'qualifications', 
                    'awards', 
                    'skills'])
                    ->find($this->clientInfo->id);
            $user->experiences = $this->addExperienceRating($user->experiences);
            $user->experiences = Experience::updateCompanyNameIfAssgined($user->experiences);
            $user->rating = $this->calculateProfileRating($user->experiences);

            return $this->respond([
                'data'=> $this->workerProfileTransformer->transform($user->toArray())
            ]);
        }
        else if($this->clientInfo->user_type == $this->userTypes['company']){
            $user = User::find($this->clientInfo->id);

            $user->companyBackground;
            $companyvalue = $this->getCompanyValue($user->id);
            $user['images'] = $companyvalue['images'];

            return $this->respond([
                'data'=> $this->hireProfileTransformer->transform($user->toArray())
            ]);
        }
        else{
            return $this->respondNotFound('User Not Found.');
        }
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function getProfileById(Request $request, $userId)
    {   
        $excepts = explode(',',$request->input('excepts'));
        $userId = $this->encodeHelper->decodeData($userId);
        $this->userId = $userId;
        $user = User::find($userId);

        if($user->user_type == $this->userTypes['individual']){
        	$user = User::with(
                    ['experiences.workImages', 
                    'qualifications', 
                    'awards', 
                    'skills'])
                    ->find($user->id);
            $user->experiences = $this->addExperienceRating($user->experiences);
            $user->experiences = Experience::updateCompanyNameIfAssgined($user->experiences);
            $user->rating = $this->calculateProfileRating($user->experiences);

            $data = $this->workerProfileTransformer->transform($user->toArray());
            if(!empty($excepts))
                $data = CustomHelper::removeListedProperties($data, $excepts);
            
            return $this->respond(['data'=> $data]);
        }
        else if($user->user_type == $this->userTypes['company']){
            $user->companyBackground;
            $companyvalue = $this->getCompanyValue($user->id);
            $user['images'] = $companyvalue['images'];

            $data = $this->hireProfileTransformer->transform($user->toArray());
            if(!empty($excepts))
                $data = CustomHelper::removeListedProperties($data, $excepts);

            return $this->respond(['data'=> $data]);
        }
        else{
            return $this->respondNotFound('User Not Found.');
        }
    }

    // also in hiredashboardcontroller
    public function getCompanyValue($userId){
        $totalEmployees = null;

        $companyBackground = CompanyBackground::where('user_id', $userId)->first();
        if($companyBackground) 
            $totalEmployees = $companyBackground->total_employees;

        $images = CompanyImage::where('user_id', $userId)->get();
        $images = $this->transformImages($images);

        return [
            'total_employees' => $totalEmployees,
            'images' => $images
        ];
    }

    /**
        * note: same function in hiredashboardcontroller
     * return user experience rating
     * @param array $experiences
     *
     * @return array
     **/
    public function addExperienceRating($experiences)
    {
        foreach($experiences as $experience){
            $application = JobApplication::withTrashed()->find($experience->application_id);
            $experience->rating = null;
            $experience->rating_status = null;

            if($application){
                $experience->rating = $application->rating;
                $experience->rating_status = $application->rating_status;
            }
        }
        return $experiences;
    }

    /**
        * note: same function in hiredashboardcontroller
     * calcualte profile rating 
     *
     * @return void
     * @author 
     **/
    public function calculateProfileRating($experiences)
    {
        $totalPoint = 0;
        $totalRatedExperience = 0;
        foreach($experiences as $experience){
            $application = JobApplication::withTrashed()->find($experience->application_id);
            if($application && $application->rating != null){
                $totalPoint += $application->rating;
                $totalRatedExperience++;
            }
        }
        return $totalRatedExperience > 0 ? number_format(($totalPoint / $totalRatedExperience), 1) : 0;
    }


    // --------- transfer below codes to transform class -------------------
    // note: code duplicay in compnay background
    // also in hiredashboardcontroller

    /**
     * undocumented function
     *
     * @return void
     **/
    public function transformImages($images)
    {
        return array_map([$this, 'transformImage'], is_array($images) ? $images : $images->toArray());
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function transformImage($image)
    {
        return [
            'id' => $this->encodeHelper->encodeData($image['id']),
            'image' => CompanyImage::getPhotoPath($this->userId).$image['image'],
            'description' => $image['description'],
        ];
    }


    public function getStatusOfWorkAndHireApplications(Request $request)
    {
        $data = [];
        $userId = $this->clientInfo->id;

        $applicaionIds = [];
        $encodedApplicationIds = $request->input('application_ids');
        foreach($encodedApplicationIds as $applicationId)
            $applicaionIds[] = $this->encodeHelper->decodeData($applicationId);

        $jobIds = Job::where('user_id', $userId)->pluck('id');
        $applications = JobApplication::whereIn('id', $applicaionIds)
                            ->where(function($query) use ($jobIds, $userId){
                                $query->whereIn('job_id', $jobIds) // check applicants of user's jobs
                                    ->orWhere('applicant_id', $userId); // check applied jobs of user
                            })
                            ->select('id', 'applicant_id', 'job_id', 'application_status')
                            ->get();

        foreach($applications as $application) {
            $encodedApplicationId = $this->encodeHelper->encodeData($application->id);
            $data[$encodedApplicationId] = [
                'id' => $encodedApplicationId,
                'job_id' => $this->encodeHelper->encodeData($application->job_id),
                'applicant_id' => $this->encodeHelper->encodeData($application->applicant_id),
                'application_status' => $application->application_status
            ];
        }

        return $this->respond([
            'data'=> ['applications'=> $data]
        ]);
    }
}
