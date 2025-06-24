<?php

namespace App\Http\Controllers;

use App\Rating;
use App\Job;
use Illuminate\Http\Request;
use App\JobApplication;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Helpers\ConfigHelper;
use Carbon\Carbon;
use App\SpotHire\Transformers\ApplicationTransformer;
use App\workExperience;
use App\Tag;
use App\SpotHire\Transformers\WorkerProfileTransformer;
use App\User;
use App\JobSkill;
use App\Experience;
use App\NotificaionDeviceToken;

class WorkDashboardController extends ApiController
{
	/**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    /**
     * @var App\Helpers\CustomHelper
     **/
    private $customHelper;

    /**
     * @var App\Helpers\ConfigHelper
     **/
    private $configHelper;

    /**
     * @var array
     **/
    private $applicationStatuses;

    /**
    * @var integer
    **/
    private $user;

    /**
    * @var App\SpotHire\Transformers\ApplicationTransformer
    **/
    protected $applicationTransformer;

    /**
     * @var array
     **/
    private $ratingStatuses;

    /**
    * @var App\SpotHire\Transformers\WorkerProfileTransformer
    **/
    protected $workerProfileTransformer;

	function __construct(
		EncodeHelper $encodeHelper, 
		ConfigHelper $configHelper, 
		CustomHelper $customHelper, 
		ApplicationTransformer $applicationTransformer, 
		WorkerProfileTransformer $workerProfileTransformer
	){
	    $this->user = auth()->guard('api')->authenticate();
	    $this->encodeHelper = $encodeHelper;
	    $this->applicationStatuses = $configHelper->getApplicationStatuses();
	    $this->customHelper = $customHelper;
	    $this->applicationTransformer = $applicationTransformer;
	    $this->ratingStatuses = $configHelper->getRatingStatuses();
	    $this->workerProfileTransformer = $workerProfileTransformer;
	}

	/**
	 * return worker's applied section jobs
	 *
	 * @return Illuminate\Http\Response
	 **/
	public function getAppliedJobs()
	{
		$applications = JobApplication::latest()
			->where('applicant_id', $this->user->id)
			->where(function ($query){
				$query->where('application_status', $this->applicationStatuses['applied'])
					->orWhere('application_status', $this->applicationStatuses['interviewed']);
			})
			->latest()
			->get();

		// get job detils, skills
		foreach ($applications as $application) {
			$application->jobDetails;
			$application->job_skills = JobSkill::where('job_id', $application->job_id)->get();
		}


		// get company details
		$applications = JobApplication::companyDetailsOfApplications($applications);

		$applications = $this->orderApplicationsByInterviewStatus($applications->toArray());

		return $this->respond([
			'data' => $this->applicationTransformer->transformCollection($applications)
		]);
	}

	public function orderApplicationsByInterviewStatus($applications)
	{
		$newOrderApplications = collect($applications)
							->where('application_status', $this->applicationStatuses['interviewed'])
							->sortByDesc('updated_at');


	    $remainingApplications = [];
	    foreach($applications as $application) {
	    	if($application['application_status'] != $this->applicationStatuses['interviewed']) {
	    		$remainingApplications[] = $application;
	    	}
	    }

	    return array_merge($newOrderApplications->toArray(), $remainingApplications);
	}


	/**
	 * return worker's assigned section jobs
	 *
	 * @return Illuminate\Http\response
	 **/
	public function getAssignedJobs()
	{
		$applications = JobApplication::latest()
			->where('applicant_id', $this->user->id)
			->where(function($query){
				$query->where('application_status', $this->applicationStatuses['assigned'])
					->orWhere('application_status', $this->applicationStatuses['completed']);
			})
			->latest()
			->get();

		// get job detils, skills
		foreach ($applications as $application) {
			$application->jobDetails;
			$application->job_skills = JobSkill::where('job_id', $application->job_id)->get();
		}

		// get company details
		$applications = JobApplication::companyDetailsOfApplications($applications);

		return $this->respond([
			'data' => $this->applicationTransformer->transformCollection($applications->toArray()),
		]);
	}

	/**
	 * notify job owner about job application
	 *
	 * @param object $application
	 * @return void
	 **/
	public function notifyOwnerAboutApplication($application) : array
	{
		$job = Job::find($application->job_id);

		if($job){
			$jobOwner = User::find($job->user_id);
			$worker = User::find($application->applicant_id);
			if($jobOwner && $worker){
				$subject = 'Application Alert';
				$msg = $worker->name.' applied to '.$job->job_title;
				$deviceTokens = NotificaionDeviceToken::where('user_id', $jobOwner->id)->pluck('token');

				$resp = $this->customHelper->sendPushNotification(
			        $subject, 
			        $msg,
			        $deviceTokens,
			        'applied'
				);

				return [
					'recipient_id'	=>	$this->encodeHelper->encodeData($jobOwner->id),
					'title'			=>	$subject,
					'body'			=>	$msg,
					'click_action'	=>	env("APP_URL")."/hire/".$this->applicationStatuses['applied'],
					'application_id' =>  $this->encodeHelper->encodeData($application->id)
				];
			}
		}
	}


	/**
	 * check if a job is hire job or not
	 *
	 * @return boolean
	 **/
	public function isOwnerJob($jobId)
	{
		$job = Job::where('id', $jobId)->where('user_id', $this->user->id)->first();
		return $job ? true : false;
	}

	/**
	 * store a job application
	 *
	 * @param \Illuminate\Http\Request
	 * @return \Illuminate\Http\Response
	 **/
	public function applyJob(Request $request)
	{
		$jobId = $this->encodeHelper->decodeData($request->input('id'));

		// return fail if job does not exist
		if( ! $this->customHelper->isDataExist('jobs', $jobId)){
			return $this->respondValidationError('Job not found for given id');
		}


		if($this->isOwnerJob($jobId)){
			return $this->respondValidationError('You can not apply your own job');
		}
		
		$application = JobApplication::withTrashed()
						->where('applicant_id', $this->user->id)
						->where('job_id', $jobId)
						->first();
		
		// already applied
		if($application && $application->deleted_at == NULL){
			return $this->respondValidationError('Already Applied');
		}

		$application = $application ? $application : new JobApplication;
		$application->applicant_id = $this->user->id;
		$application->job_id =  $jobId;
		$application->application_status = $this->applicationStatuses['applied'];
		$application->interview_date = NULL;
		$application->interview_time = NULL;
		$application->is_short_listed = false;
		$application->rating_status = NULL;
		$application->rating = NULL;
		$application->deleted_at = NULL;

		if($application->save()){
			$notfication = $this->notifyOwnerAboutApplication($application);
			return $this->respondCreatingResourceWithData('Applied successfully', $notfication);
		}
		else{
			return $this->respondInternalError('Failed saving application');
		}
	}

	/**
	 * withdraw a job
	 *
	 * @param \Illuminate\Http\Request
	 * @return \Illuminate\Http\Response
	 **/
	public function withdrawJob(Request $request)
	{
		$applicationId = $this->encodeHelper->decodeData($request->input('application_id'));

		// return fail if job does not exist
		$jobId = JobApplication::where('id', $applicationId)->value('job_id');
		if(!$this->customHelper->isDataExist('jobs', $jobId)){
			return $this->respondValidationError('Job not found');
		}

		$application = JobApplication::where('applicant_id', $this->user->id)->find($applicationId);

		if(!$application){
			return $this->respondValidationError('Application not found');
		}

		// return fail if user does not have permission
		if($application->applicant_id !=  $this->user->id){
			return $this->respondValidationError('Sorry, you don\'t have permission to widraw this job');
		}		

		if(
			$application->application_status == $this->applicationStatuses['applied']
			|| $application->application_status == $this->applicationStatuses['interviewed']
		) {
			
			if($application->delete()){
				return $this->respondUpdatingResource('Withdrew successfully');
			}
			else{
				return $this->respondInternalError('Failed withdrawing job. Please try again later');
			}

		}
		else{
			return $this->respondValidationError('You have to apply or interviewed first to withdraw');
		}
	}


	/**
	 * discontinue job
	 *
	 * @return Illuminate\Http\Resonse
	 **/
	public function discontinueJob(Request $request)
	{
		$applicationId = $this->encodeHelper->decodeData($request->input('application_id'));
		$application = JobApplication::find($applicationId);

		if(!$application){
			return $this->respondValidationError('Hired job not found');
		}

		// return fail if user does not have permission
		if($application->applicant_id !=  $this->user->id){
			return $this->respondValidationError('Sorry, you don\'t have permission to discontinue this job');
		}

		// discontinue job
		if($application->application_status == $this->applicationStatuses['assigned']){
			// remove from work experience
			$workExpr = Experience::where('user_id', $this->user->id)
														->where('application_id', $application->id)
														->first();
			if($workExpr){
				$workExpr->delete();
			}

			if($application->delete()){
				return $this->respondUpdatingResource('Discontinued successfully');
			}
			else{
				return $this->respondInternalError('Failed discontinuing job. Please try again later');
			}
		}
		else{
			return $this->respondValidationError('You have to be hired first to discontinue');
		}
	}
}