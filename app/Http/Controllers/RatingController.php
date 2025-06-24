<?php
namespace App\Http\Controllers;

use App\Skill;
use App\Tag;
use App\workExperience;
use Illuminate\Http\Request;
use App\JobApplication;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Helpers\CustomHelper;
use App\Rating;
use App\Job;
use App\User;

class RatingController extends ApiController
{
  /**
   * @var App\Helpers\CustomHelper
  **/
  private $customHelper;

	/**
   * @var App\Helpers\EncodeHelper
  **/
  private $encodeHelper;

	/**
   * @var array
  **/
  private $ratingStatuses;

	/**
  * @var integer
  **/
  private $clientInfo;

	function __construct(EncodeHelper $encodeHelper, ConfigHelper $configHelper, CustomHelper $customHelper)
	{
    $this->customHelper = $customHelper;
    $this->encodeHelper = $encodeHelper;
    $this->ratingStatuses = $configHelper->getRatingStatuses();
    $this->clientInfo = auth()->guard('api')->authenticate();
    $this->applicationStatuses = $configHelper->getApplicationStatuses();
	}

	/**
	 * request for rating
	 *
	 * @return Illuminate\Http\Request
	 **/
	public function request(Request $request)
	{
		$applicationId = $request->input('application_id');

		$application = JobApplication::where('application_status', $this->applicationStatuses['assigned'])
											->withTrashed()
											->find($this->encodeHelper->decodeData($applicationId));

		if($application == null){
			return $this->respondNotFound('No jobs found for rating request or not hired');
		}

		if($application->rating_status == $this->ratingStatuses['pending']){
			return $this->respondValidationError('Already requested for rating');	
		}

		$application->rating_status = $this->ratingStatuses['pending'];

		if($application->save()){
			return $this->respondCreatingResource('Requested for rating successfully');
		}
		else{
			return $this->respondInternalError('Failed saving rating request');
		}
	}

	/**
	 * rate a employees skills for a job
	 *
	 * @return Illuminate\Http\Request
	 **/
	public function rate(Request $request)
	{
		$applicationId = $this->encodeHelper->decodeData($request->input('application_id'));

		$application = JobApplication::withTrashed()
										->where('application_status', $this->applicationStatuses['assigned'])
										->find($applicationId);

		if(!$application){
			return $this->respondValidationError('Job not found as hired for rating');
		}
		
		if($application->rating_status != $this->ratingStatuses['pending']){
			return $this->respondValidationError('Already rated or no request made my employee previously');
		}

		if($this->clientInfo->id != Job::getJobOwnerId($application->job_id)){
			return $this->respondValidationError('Job does not belong to requeted user');
		}

		// update rating status
		$application->rating_status = $this->ratingStatuses['rated'];
		$application->rating = $request->input('rating_point');

		if(!$application->save()){
				return $this->respondInternalError('Failed performing Rate action');
		}
		
		$this->notifyEmployeeAboutRated($application);

		return $this->respondUpdatingResource('Rate action done successfully');
	}


	/**
	 * notify employer about rating request
	 *
	 * @param object $application
	 * @return void
	 **/
	public function notifyEmployerAboutRatingRequest($application)
	{
		$job = Job::find($application->job_id);

		if($job){
			$jobOwner = User::find($job->reg_id);
			$worker = User::find($application->applicant_id);
			if($jobOwner && $worker){
				$subject = 'Rating Request alert from Spothire!';
				$msg = '"'.$worker->name
					.'" requested rating for "'
					.$job->job_title.'" position';

				$resp = $this->customHelper->sendEmail(
					$jobOwner->email,
		        $jobOwner->name, 
		        $subject,
		        $msg, 
		        $worker->email, 
		        $worker->name,
		        'apply'
				);
			}
		}
	}

	/**
	 * notify employee about rated
	 *
	 * @param object $application
	 * @return void
	 **/
	public function notifyEmployeeAboutRated($application)
	{
		$job = Job::find($application->job_id);

		if($job){
			$jobOwner = User::find($job->reg_id);
			$worker = User::find($application->applicant_id);
			if($jobOwner && $worker){
				$subject = 'Rated alert from Spothire!';
				$msg = '"'.$jobOwner->name
							.'" rated you for "'
							.$job->job_title.'" position';

				$resp = $this->customHelper->sendEmail(
			        $worker->email, 
			        $worker->name,
			        $subject, 
			        $msg, 
					$jobOwner->email,
			        $jobOwner->name, 
			        'apply'
				);
			}
		}
	}
}