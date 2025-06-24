<?php

namespace App\Http\Controllers;

use App\SpotHire\Transformers\CustomTransformer;
use App\SpotHire\Helpers\EncodeHelper;
use App\Job;
use App\JobLocation;
use App\JobQualification;
use App\JobSkills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Tag;
use App\User;
use App\JobSkill;
use App\JobWorkday;
use App\JobBenefit;
use App\Institution;
use App\TargetUniversity;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Transformers\WriteOfferTransformer;

class WriteOfferController extends ApiController
{
    private $encodeHelper;
    private $user;
    private $customTransformer;
    private $configHelper;
    private $writeOfferTransformer;

    /**
    * @var App\Helpers\CustomHelper
    **/
    private $customHelper;

    function __construct(EncodeHelper $encodeHelper, 
        CustomTransformer $customTransformer, 
        ConfigHelper $configHelper,
        CustomHelper $customHelper,
        WriteOfferTransformer $writeOfferTransformer
    ){
        $this->encodeHelper = $encodeHelper;
        $this->user = auth()->guard('api')->authenticate();
        $this->customTransformer = $customTransformer;
        $this->configHelper = $configHelper;
        $this->customHelper = $customHelper;
        $this->writeOfferTransformer = $writeOfferTransformer;
    }

    public function getJobOptions(Request $request){
        $tags = Tag::where('is_active', true)->get();

        return $this->respond([
            'tags'=> $this->customTransformer->transformTags($tags),
            'advert_days' => $this->configHelper->getAdvertDays(),
            'departments'=> $this->configHelper->getDepartments(),
            'employment_types'=> $this->configHelper->getEmploymentTypes(),
            'salary_types'=> $this->configHelper->getSalaryTypes(),
            'experiences' => $this->configHelper->getExperiences(),
            'qualifications' => $this->configHelper->getQualifications(),
            'locations' => $this->configHelper->getLocations()
        ]);
    }

    public function saveJob(Request $request) 
    {
        $reviewStatuses = $this->configHelper->getReviewStatuses();
        $data = $request->all();

        if(isset($data['id'])){
            $job = Job::find($this->encodeHelper->decodeData($data['id']));
            if(!$job) 
                return $this->respondValidationError('Job not found for given id'); 
        }
        else{
            $job = new Job();
        }
        $job->user_id         = $this->user->id;
        $job->job_title       = $data['job_title'];
        $job->slug            = Job::generateSlug($data['job_title']);
        $job->department      = $data['department'];
        $job->employment_type = $data['employment_type'];
        $job->start_date      = isset($data['start_date']) 
                                && $data['start_date'] != ''
                                && $data['start_date'] != null
                                ? date($data['start_date'])
                                : null;
        $job->end_date        = isset($data['end_date']) 
                                && $data['end_date'] != '' 
                                && $data['end_date'] != null
                                ? date($data['end_date'])
                                : null;
        $job->min_experience    = $data['min_experience'];
        $job->min_qualification = $data['min_qualification'];
        $job->salary_from       = isset($data['salary_from']) ? $data['salary_from'] : null;
        $job->salary_to         = isset($data['salary_to']) ? $data['salary_to'] : null;
        $job->salary_type       = isset($data['salary_type']) ? $data['salary_type'] : null;
        $job->is_salary_negotiable = $data['is_salary_negotiable'];
        $job->description       = $data['description'];
        $job->requirements      = $data['requirements'];
        $job->location          = $data['location'];
        $job->address           = $data['address'];
        $job->advert_duration   = $data['advert_duration'];
        $job->advert_deadline   = Job::advertDeadLine($data['advert_duration']);

        $job->is_payment_completed = true; // setting job posting automatially free 
        $job->review_status = $reviewStatuses['pending']; 

        if(!$job->save())
            return $this->respondInternalError('Failed to save job. Please try again.');

        // delete any existing for edit
        JobSkill::where('job_id', $job->id)->delete();        

        JobSkill::saveSkills($data['skills'], $job->id);
        
        // send email to admins
        $this->notifyAdmins($job->job_title);

        return $this->respondCreatingResource('Saved job successfully!');
    }

    public function editJob($id) 
    {
        $id = intval($this->encodeHelper->decodeData($id));
        if(!$id)
            return $this->respondValidationError('Id not found in the request');

        $job = Job::find($id);
        if(isset($job->user_id) && $job->user_id !=  $this->user->id)
            return $this->respondValidationError('No Data Found.');

        $job = Job::with('skills')->find($id);

        return $this->respond([
            'data' => $job
                    ? $this->writeOfferTransformer->transform($job->toArray())
                    : null
        ]);
    }

    public function deleteJob(Request $request)
    {
        $id = intval($this->encodeHelper->decodeData($request->input('id')));
        if(!$id)
            return $this->respondValidationError('Id not found in the request');

        $job = Job::find($id);
        if(isset($job->user_id) && $job->user_id !=  $this->user->id)
            return $this->respondValidationError('You do not have permission to delete this job.');

        if(!$job->delete())
            return $this->respondInternalError('Failed deleting job. Please try again.');

        return $this->respondDeleteingResource('Deleted job successfully!');
    }

    public function notifyAdmins($jobTitle) {
        $subject = 'New Job Posted!';
        
        //todo: add internal job link to message 
        $msg = 'A job for the postion of '
                .$jobTitle
                .' has been circulated by '
                .$this->user->name
                .'. Please login to your admin account to approve the job.';

        $recipients = [
            [
                'name'=>'admin rubab',
                'email'=>'rubab2020@gmail.com'
            ]
        ];

        foreach($recipients as $recipient){
            $this->customHelper->sendMail(
                $recipient['email'], 
                $recipient['name'],
                $subject, 
                $msg,
                $this->user->email,
                $this->user->name,
                'internal_job'
            );
        }
    }
}