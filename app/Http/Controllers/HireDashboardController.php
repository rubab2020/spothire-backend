<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobApplication;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Helpers\ConfigHelper;
use App\Job;
use App\User;
use App\SpotHire\Transformers\HireAppliedTransformer;
use App\SpotHire\Transformers\HireAssignedTransformer;
use App\Rating;
use App\Experience;
use Carbon\Carbon;
use App\Skill;
use App\Payment;
use App\Message;
use App\CompanyBackground;
use App\CompanyImage;
use App\SpotHire\Helpers\CustomPaginateHelper;

class HireDashboardController extends ApiController
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
	 * @var obj
	 **/
	private $user;

	/**
	* @var array
	**/
	private $hireAppliedTransformer;

	/**
	* @var array
	**/
	private $hireAssignedTransformer;

	/**
	 * @var array
	 **/
	private $ratingStatuses;

	private $userTypes;

	private $userId;


	function __construct(
		EncodeHelper $encodeHelper, 
		CustomHelper $customHelper, 
		ConfigHelper $configHelper, 
		HireAppliedTransformer $hireAppliedTransformer, 
		HireAssignedTransformer $hireAssignedTransformer 
	){
	    $this->user = auth()->guard('api')->authenticate();
	    $this->encodeHelper = $encodeHelper;
	    $this->customHelper = $customHelper;
	    $this->applicationStatuses = $configHelper->getApplicationStatuses();
	    $this->configHelper = $configHelper;
	    $this->hireAppliedTransformer = $hireAppliedTransformer;
	    $this->hireAssignedTransformer = $hireAssignedTransformer;
	    $this->ratingStatuses = $configHelper->getRatingStatuses();
	    $this->userTypes = $configHelper->getUserTypes();
	}

	/**
	 * get applied section's jobs
	 *
	 * @return Illuminate/Http/response
	 **/
	public function getAppliedJobs()
	{
		$jobs = Job::with('appliedApplications', 'skills')
			->where('user_id', $this->user->id)
			->latest()
			->get();

		$jobs = $this->changeRelationName(
			$jobs->toArray(),
			'applied_applications', 
			'applications'
		);

		//to view meessenger and phone number
		$jobs = $this->checkAndSetTemporaryUnlockOnJobs($jobs);
		
		$profiles = $this->getApplicationsDetails($jobs);
	  	$jobs = $this->orderApplicationsByRank($jobs, $profiles);
    	$jobs = $this->setInterviewAppicationsToTop($jobs, $profiles);
	  	$jobs = $this->setReqrmntMatchCountsToApplicants($jobs, $profiles);
	  	$jobs = $this->paginateApplicants($jobs);
	  	$jobs = $this->addApplicantsProfiles($jobs, $profiles);

		return $this->respond([
			'data' => [
				'jobs'	=> $this->hireAppliedTransformer->transformCollection($jobs),
				'filters' => $this->getFilters($jobs, $profiles),
			]
		]);
	}


	/**
	 *  set temporary unlock property to each job to view meessenger and phone number
	 *
	 * @param $jobs
	 * @return array
	 **/
	public function checkAndSetTemporaryUnlockOnJobs($jobs)
	{
		foreach($jobs as $key=>$job){
			$jobs[$key]['is_temporary_unlocked'] = false;
			$jobs[$key]['temp_unlock_due_till'] = null;

			$payment = Payment::where('job_id', $job['id'])->first();
			if($payment){
				$hoursLimit = 48;
				$fromDt = Carbon::parse($payment->updated_at);
				$toDt = Carbon::now();
				if($payment->status == 'pending'){
					if($toDt->diffInHours($fromDt) <= $hoursLimit){
						$jobs[$key]['is_temporary_unlocked'] = true;
					}
					$jobs[$key]['temp_unlock_due_till'] = $payment->updated_at
															->addDays(2)
															->format('Y-m-d H:i:s');
				}
			}
		}

		return $jobs;
	}



	public function getAppliedApplicants($jobId) 
	{
		$applicationStatuses = $this->applicationStatuses;
		$jobs = Job::with(['applications' => function($q) use ($applicationStatuses) {
			$q->where('application_status', $applicationStatuses['applied'])
        		->orWhere('application_status', $applicationStatuses['interviewed']);
		}])
		->where('user_id', $this->user->id)
		->where('id', $this->encodeHelper->decodeData($jobId))
		->get();

		if(empty($jobs))
			return $this->respondValidationError('Job not found for given id');

		$profiles = $this->getApplicationsDetails($jobs);
    	$jobs = $this->orderApplicationsByRank($jobs, $profiles);
    	$jobs = $this->setReqrmntMatchCountsToApplicants($jobs->toArray(), $profiles);
    	$jobs = $this->paginateApplicants($jobs);
    	$jobs = $this->addApplicantsProfiles($jobs, $profiles);
    	$job = $jobs[0];

		return $this->respond([
			'data' => $this->hireAppliedTransformer
						->transformApplications($job['applications']['data']),
			'pagination' => $job['applications']['pagination']
		]);
	}


	/**
	 * get assigned's jobs
	 *
	 * @return Illuminate/Http/response
	 **/
	public function getAssignedJobs()
	{
		$jobs = Job::with('assignedApplications')
            		->withTrashed()
								->where('user_id', $this->user->id)
								->latest()
								->get();

		$jobs = $this->changeRelationName(
			$jobs->toArray(), 
			'assigned_applications', 
			'applications'
		);

		$profiles = $this->getApplicationsDetails($jobs);

		$jobs = $this->addApplicantsProfiles($jobs, $profiles);
  		
		return $this->respond([
			'data' => $this->hireAssignedTransformer->transformCollection($jobs) 
		]);
	}

	/**
	 * return applications' details
	 * @param array $jobs
	 *
	 * @return array
	 **/
	public function getApplicationsDetails($jobs)
	{
		$profiles = [];
		foreach($jobs as $job){
			foreach($job['applications'] as $application){
				$applicationId = $application['applicant_id'];

				if(!array_key_exists($applicationId, $profiles)){ // get user profile and set it
					
					// profile
					$profile = $this->getWorkerProfileDataById($applicationId);
					if(!$job['is_payment_completed'])
						$profile['phone'] = null;

					$profiles[$applicationId] = $profile;
				}
			}
		}

		return $profiles;
	}


	/**
	 * return worker's profile data
	 * @param integer $userID
	 *
	 * @return mixed
	 **/
	public function getWorkerProfileDataById($userId)
	{
		$user = User::find($userId);
	    $this->userId = $userId;

		if($user->user_type == $this->userTypes['individual']){
    		$user = User::with([
    			'experiences.workImages', 
                'qualifications', 
                'awards', 
                'skills'
        	])
        	->find($user->id);
	        $user->experiences = $this->addExperienceRating($user->experiences);
	        $user->experiences = Experience::updateCompanyNameIfAssgined($user->experiences);
	        $user->rating = $this->calculateProfileRating($user->experiences);
	        return $user;
	    }
	    else if($user->user_type == $this->userTypes['company']){
	        $user->companyBackground;
	        $companyvalue = $this->getCompanyValue($user->id);
	        $user['images'] = $companyvalue['images'];
	        return $user;
	    }
	    else{
	       return null;
	    }
	}

  	// also in profilecontroller
	public function getCompanyValue($userId){
      	$companyBackground = CompanyBackground::where('user_id', $userId)->first();

      	$images = CompanyImage::where('user_id', $userId)->get();
      	$images = $this->transformImages($images);

      	return [
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
			$experience->rating = null;
			$experience->rating_status = null;

			$application = JobApplication::withTrashed()->find($experience->application_id);
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
		return $totalRatedExperience > 0 
			? number_format(($totalPoint / $totalRatedExperience), 1) 
			: 0;
	}

	/**
	 * check a job wheter belongs to user or not
	 *
	 * @return boolean
	 **/
	public function isClientJob($jobID)
	{
		$job = Job::find($jobID);
		return $job->user_id == $this->user->id ? true : false;
	}

	/**
	 * short list an applicant
	 *
	 * @return Illuminate\Http\Resonse
	 **/
	public function shortListApplicant(Request $request)
	{
		$applicationId = $this->encodeHelper->decodeData($request->input('application_id'));

		// return fail if job does not exist
		$jobId = JobApplication::where('id', $applicationId)->value('job_id');
		if(!$this->customHelper->isDataExist('jobs', $jobId)){
			return $this->respondValidationError('Job not found');
		}

		$application = JobApplication::find($applicationId);

		if(!$application){
			return $this->respondValidationError('Application not found');
		}

		// return fail if user does not have permission
		if(!$this->isJobOwner($application->job_id)){
			return $this->respondValidationError('Sorry, you don\'t have permission to shortlist applicant');
		}		

		// shortlisted already
		if($application->is_short_listed){
			$application->is_short_listed = false;

			if($application->save()){
				return $this->respondUpdatingResource('Removed applicant from shortlist');
			}
			else{
				return $this->respondInternalError('Failed removing shortlisted applicant');
			}
		}

		// short list application
		if(
			$application->application_status == $this->applicationStatuses['applied']
			|| $application->application_status == $this->applicationStatuses['interviewed']
		){
			$application->is_short_listed = true;

			if($application->save()){
				return $this->respondUpdatingResource('Shortlisted successfully');
			}
			else{
				return $this->respondInternalError('Failed shortlisting applicant.  Please try again later');
			}
		}
		else{
			return $this->respondValidationError('This job is not applied / interviewed to shortlist');
		}
	}


	/**
	 * interview an applicant
	 *
	 * @return Illuminate\Http\Resonse
	 **/
	public function interviewApplicant(Request $request)
	{
		$applicationId = $this->encodeHelper->decodeData($request->input('application_id'));
		$inaterviewDate = $request->input('interview_date');
		$inaterviewTime = $request->input('interview_time');

		// return fail if job does not exist
		$jobId = JobApplication::where('id', $applicationId)->value('job_id');
		if(!$this->customHelper->isDataExist('jobs', $jobId)){
			return $this->respondValidationError('Job not found');
		}

		$application = JobApplication::find($applicationId);

		if(!$application){
			return $this->respondValidationError('Application not found');
		}

		// return fail if user does not have permission
		if(!$this->isJobOwner($application->job_id)){
			return $this->respondValidationError('Sorry, you don\'t have permission to inverview applicant');
		}	

		// interview applicant
		if(
			$application->application_status == $this->applicationStatuses['applied']
			|| $application->application_status == $this->applicationStatuses['interviewed'] // re interview
		){
			$application->application_status = $this->applicationStatuses['interviewed'];
			$application->interview_date = isset($inaterviewDate) && $inaterviewDate !== ''
														          ? date($inaterviewDate)
														          : null;
			$application->interview_time = $inaterviewTime;

			if($application->save()){
				$notfication = $this->notifyApplicantAboutInterview($application);
				return $this->respondUpdatingResourceWithData('Interviewed successfully', $notfication);
			}
			else{
				return $this->respondInternalError('Failed interviewing applicant. Please try again later');
			}
		}
		else{
			return $this->respondValidationError('This job is not applied to interview');
		}
	}


	/**
	 * assign an applicant
	 *
	 * @return Illuminate\Http\Resonse
	 **/
	public function assignApplicant(Request $request)
	{
		$applicationId = $this->encodeHelper->decodeData($request->input('application_id'));

		// return fail if job does not exist
		$jobId = JobApplication::where('id', $applicationId)->value('job_id');
		if(!$this->customHelper->isDataExist('jobs', $jobId)){
			return $this->respondValidationError('Job not found');
		}

		$application = JobApplication::find($applicationId);

		if(!$application){
			return $this->respondValidationError('Application not found');
		}

		// return fail if user does not have permission
		if(!$this->isJobOwner($application->job_id)){
			return $this->respondValidationError('Sorry, you don\'t have permission to hire applicant');
		}

		// assigned already
		if($application->application_status == $this->applicationStatuses['assigned']){
			return $this->respondValidationError('Already hired');
		}

		// hire applicant
		if(
			$application->application_status == $this->applicationStatuses['applied']
			|| $application->application_status == $this->applicationStatuses['interviewed']
		){
			$application->application_status = $this->applicationStatuses['assigned'];

			if($application->save()){
				$job = Job::find($application->job_id);
				$this->saveAssignedJobToExperience($job, $application);
				$notfication = $this->notifyApplicantAboutHired($application);
				return $this->respondUpdatingResourceWithData('Hired successfully', $notfication);
			}
			else{
				return $this->respondInternalError('Failed hiring applicant. Please try again later');
			}
		}
		else{
			return $this->respondValidationError('This job is not applied / interviewed to hire');
		}
	}

	/**
	 * save assined job to experience
	 * @param obj $job, obj $application
	 * @return void
	 **/
	public function saveAssignedJobToExperience($job, $application)
	{
		$experience = new Experience;
	    $experience->user_id = $application->applicant_id;
	    $experience->occupation = $job->job_title;
	    $experience->employer = $this->user->name;
	    $experience->application_id = $application->id;
	    $experience->continuing = true;
	    $experience->period_from = $application->updated_at;
	    $experience->save();
	}

	/**
	 * decline an applicant
	 *
	 * @return Illuminate\Http\Resonse
	 **/
	public function declineApplicant(Request $request)
	{
		$applicationId = $this->encodeHelper->decodeData($request->input('application_id'));

		// return fail if job does not exist
		$jobId = JobApplication::where('id', $applicationId)->value('job_id');
		if(!$this->customHelper->isDataExist('jobs', $jobId)){
			return $this->respondValidationError('Job not found');
		}

		$application = JobApplication::find($applicationId);

		if(!$application){
			return $this->respondValidationError('Application not found');
		}

		// return fail if user does not have permission
		if(!$this->isJobOwner($application->job_id)){
			return $this->respondValidationError('Sorry, you don\'t have permission to decline applicant');
		}

		// decline applicant
		if(
			$application->application_status == $this->applicationStatuses['applied']
			|| $application->application_status == $this->applicationStatuses['interviewed']
		){
			if($application->delete()){
				return $this->respondUpdatingResource('Declined successfully');
			}
			else{
				return $this->respondInternalError('Failed declining application. Please try again later');
			}
		}
		else{
			return $this->respondValidationError('This job is not applied / interviewed to decline');
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
		$application = JobApplication::withTrashed()->find($applicationId);

		if(!$application){
			return $this->respondValidationError('Hired job not found');
		}

		// return fail if user does not have permission
		if(!$this->isJobOwner($application->job_id)){
			return $this->respondValidationError('Sorry, you don\'t have permission to discontinue job');
		}

		// discontinue job
		if($application->application_status == $this->applicationStatuses['assigned']){
			// remove form work experience
			$workExpr = Experience::where('user_id', $application->applicant_id)
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
			return $this->respondValidationError('You have to fire first to discontinue');
		}
	}

	/**
	 * complete job
	 *
	 * @return Illuminate\Http\Resonse
	 **/
	public function completeJob(Request $request)
	{
		$applicationId = $this->encodeHelper->decodeData($request->input('application_id'));
		$application = JobApplication::withTrashed()->find($applicationId);

		if(!$application){
			return $this->respondValidationError('Hired job not found');
		}

		// return fail if user does not have permission
		if(!$this->isJobOwner($application->job_id)){
			return $this->respondValidationError('Sorry, you don\'t have permission to complete job');
		}

		// completed already
		if($application->application_status == $this->applicationStatuses['completed']){
			return $this->respondValidationError('Already completed');
		}

		// complete job
		if($application->application_status == $this->applicationStatuses['assigned']){
			$application->application_status = $this->applicationStatuses['completed'];

			if($application->save()){
				// update work experience
				$workExpr = Experience::where('user_id', $application->applicant_id)
															->where('application_id', $application->id)
															->first();
				if($workExpr){
					$workExpr->continuing = false;
					$workExpr->period_to = Carbon::Now()->format('y-m-d');
					$workExpr->save();
				}

				return $this->respondUpdatingResource('Completed successfully');
			}
			else{
				return $this->respondInternalError('Failed completing job. Please try again later');
			}
		}
		else{
			return $this->respondValidationError('You have to hire first to complete');
		}
	}


	/**
	 * return fitlers name for for application statuses
	 * @param array $jobs
	 * @param array $profiles
	 *
	 * @return array
	 **/
	public function getFilters($jobs, $profiles)
	{
		return [
	      'application_statuses' => $this->getApplicationStatusesForFilter(),
	      'job_titles' => array_values($this->getJobTitleFilter($jobs)),
	      'skills' => array_values($this->getSkillFilter($profiles)),
	      'qualifications' => array_values($this->getQualificationFilter($profiles)),
	      'universities' => array_values($this->getUniverisityFilter($profiles)),
		];
	}

	/**
	 * get Application Statuses For Filter
	 *
	 * @return array
	 **/
	public function getApplicationStatusesForFilter()
	{
    return [
        $this->applicationStatuses['applied'],
        $this->applicationStatuses['shortlisted'],
        $this->applicationStatuses['interviewed']
    ];
	}
	
	/**
	 * return job title's filter
	 * @param array $jobs
	 *
	 * @return array
	 **/
	public function getJobTitleFilter($jobs)
	{
		$options = [];
		foreach ($jobs as $job) {
			if(!CustomHelper::IsNullOrEmptyString($job['job_title'])){
				$options[] = $job['job_title'];
			}
		}

		return array_unique($options);
	}

	/**
	 * return skill filter
	 * @param array $profiles
	 *
	 * @return array
	 **/
	public function getSkillFilter($profiles)
	{
		$options = [];
		foreach($profiles as $profile){
			// skills from global skill set
			foreach($profile['skills'] as $skill){
				if(!CustomHelper::IsNullOrEmptyString($skill['name'])){
					$options[] = $skill['name'];
				}
			}
		}

		return array_unique($options);
	}

	/**
	 * return qulification filter
	 * @param array $profiles
	 *
	 * @return array
	 **/
	public function getQualificationFilter($profiles)
	{
		$options = [];
		foreach($profiles as $profile){
			foreach($profile->qualifications as $qualification){
				if(!CustomHelper::IsNullOrEmptyString($qualification->degree)){
					$options[] = $qualification->degree;
				}
			}
		}

		return array_unique($options);
	}

	/**
	 * return university filter
	 * @param array $profiles
	 *
	 * @return array
	 **/
	public function getUniverisityFilter($profiles)
	{
		$uniDegrees = $this->configHelper->getQualifications();

		$options = [];
		foreach($profiles as $profile){
			foreach($profile->qualifications as $qualification){
				if($this->hasQualification($qualification->degree, $uniDegrees)){
					if(!CustomHelper::IsNullOrEmptyString($qualification->institution)){
						$options[] = $qualification->institution;
					}
				}
			}
		}

		return array_unique($options);
	}

	/**
	 * check if university degree exist in worker's degree
	 *
	 * @return boolean
	 **/
	public function hasQualification($degreeName, $uniDegrees)
	{
		foreach($uniDegrees as $uniDegree){
			if(strpos($degreeName, $uniDegree) !== false){
				return true;
			}
		}

		return false;
	}

	/**
	 * undocumented function
	 *
	 * @return array
	 **/
	public function setReqrmntMatchCountsToApplicants($jobs, $profiles){
		foreach($jobs as $jobKey => $job){
			foreach($job['applications'] as $applicationKey => $application){
				// $profile = $profiles[$this->encodeHelper->encodeData($application['applicant_id'])];
				$profile = $profiles[$application['applicant_id']];

				$totalMatchSkills = $this->calcSkillMatchPoint($job, $profile);
				$totalQualifications = count($profile->qualifications);
				$totalExperiences = count($profile->experiences);

				$jobs[$jobKey]['applications'][$applicationKey]['counts'] = [
						'matched_skills'=>$totalMatchSkills,
						'experiences' => $totalExperiences,
						'qualifications' => $totalQualifications
					];
			}
		}

		return $jobs;
	}

	/**
     * order applications by rank 
     *
        Algorthim for showing best matched applications to the top of loaded data. 
        user profile            -between-       - job details
        -------------                           ------------
        1. match skills         -between-       - required skills
        2. match education      -between-       - with mininum qualification
        3. match designation    -between-       - department and job title [future work]
        4. count experience     -between-       - min experience

        point ranking: 
            .1 point for each matched skill, 
            .1 point for each education equal to min qualification. bonus 1 point for each higher education than min qualification.
            .1 point for each experience equal to min experience. bonus 1 poin for each higher expereince than min experience.
     *
     * @param obj $jobs
     * @return array
     **/
	public function orderApplicationsByRank($jobs, $profiles)
	{
		foreach($jobs as $jobKey => $job){
			// calcuate rank
    		$pointApplications = [];	
			foreach($job['applications'] as $applicationKey => $application){
				$profile = $profiles[$application['applicant_id']];

				$pointSkill = $this->calcSkillMatchPoint($job, $profile);
		        $pointQualification = $this->calcEducationMatchPoint($job, $profile);
		        $pointExpereince = $this->calcExperienceMatchPoint($job, $profile);

		        $totalPoints = $pointSkill + $pointQualification + $pointExpereince;
		        $pointApplications[] = ['id'=>$application['id'], 'point'=>$totalPoints];
			}

				// highest points order for jobs
      	$newOrderApplications = collect($pointApplications)->sortByDesc('point')->pluck('id');

		    // order applications by points
		    $orderByRankApplications = [];
		    foreach($newOrderApplications  as $newOrdApplicationId){
		        foreach($job['applications'] as $application){
		            if($newOrdApplicationId == $application['id']){
		                $orderByRankApplications[] = $application;
		                break;
		            }
		        }
		    }

			// reset applications to job
			$jobs[$jobKey]['applications'] = $orderByRankApplications;
		}

		return $jobs;
	}

	public function setInterviewAppicationsToTop($jobs, $profiles)
	{
		foreach($jobs as $jobKey => $job){ 
			$newOrderApplications =  collect($job['applications'])
										->where('application_status', $this->applicationStatuses['interviewed'])
										->sortByDesc('updated_at')
										->pluck('id');

			$orderApplicationsByInterviewStatus = [];

			foreach($newOrderApplications  as $newOrdApplicationId){
		        foreach($job['applications'] as $application){
		            if($newOrdApplicationId == $application['id']){
		                $orderApplicationsByInterviewStatus[] = $application;
		                break;
		            }
		        }
		    }

		    // add remaining applications
		    $remainingApplications = [];
		    foreach($job['applications'] as $application){ 
		    	if($application['application_status'] != $this->applicationStatuses['interviewed']) {
		    		$remainingApplications[] = $application;
		    	}
		    }

		    // reset applications to job
			$jobs[$jobKey]['applications'] = array_merge($orderApplicationsByInterviewStatus, $remainingApplications);

		}

		return $jobs;
	}

	/**
	 * calcualte skill match point
	 *
	 * @param obj $job
	 * @param obj $profile
	 * @return int
	 **/
	public function calcSkillMatchPoint($job, $profile)
	{
	    $jSkills = collect($job['skills'])->pluck('name')->toArray(); 
	    $pSKills = collect($profile->skills)->pluck('name')->toArray(); 
	    $pointSkill = count(array_intersect($jSkills, $pSKills));
	    return $pointSkill;
	}

  /**
   * calculate education match point
   *
   * @param obj $job
   * @param obj $profile
   * @return int
   **/
  public function calcEducationMatchPoint($job, $profile)
  {
	  $pointQualification = 0;

	  if($job['min_qualification'] == 'Bechelor'){
	    foreach($profile->qualifications as $qualification){
	      $hasBachelor = $this->hasBachelorDegree($qualification->degree);
	      if($hasBachelor){
	        $pointQualification = 1;
	        break;
	      }
	    }
	  }
	  else if($job['min_qualification'] == 'Master'){
	    foreach($profile->qualifications as $qualification){
	      $hasMaster = $this->hasMasterDegree($qualification->degree);
	      if($hasMaster){
	        $pointQualification = 1;
	        break;
	      }
	    }
	  }
	  else if($job['min_qualification'] == 'Diploma'){
	    foreach($profile->qualifications as $qualification){
	      $hasDiploma = $this->hasDiplomaDegree($qualification->degree);
	      if($hasDiploma){
	        $pointQualification = 1;
	        break;
	      }
	    }
	  }
	  else if($job['min_qualification'] == 'Doctor'){
	    foreach($profile->qualifications as $qualification){
	      $hasDoctorate = $this->hasDoctorateDegree($qualification->degree);
	      if($hasDoctorate){
	        $pointQualification = 1;
	      	break;
	      }
	    }
	  }
	  else if($job['min_qualification'] == 'Higher Secondary'){
	    foreach($profile->qualifications as $qualification){
	      $hasHigherSecondary = $this->hasHigherSecondaryDegree($qualification->degree);
	      if($hasHigherSecondary){
	        $pointQualification = 1;
	      	break;
	      }
	    }
	  }
	  else if($job['min_qualification'] == 'Secondary'){
	    foreach($profile->qualifications as $qualification){
	      $hasSecondary = $this->hasSecondaryDegree($qualification->degree);
	      if($hasSecondary){
	        $pointQualification = 1;
	        break;
	      }
	    }
	  }
	  else if(
	  	$job['min_qualification'] == 'Not Required'  
	  	&& count($profile->qualifications) == 0
	  ){
	  	// add value it will keep those data top who has higher experience
		$pointQualification = 1; 
	  }

	  return $pointQualification;
  }

  /**
   	* calculate experience match point. first add count of exprience as point and after that add point if job requiremt is matched
   	*
   	* @param obj $job
   	* @param obj $profile
   	* @return int
   	**/
  	public function calcExperienceMatchPoint($job, $profile)
  	{
	    $pointExpereince = 0;
	    $totalExperience = 0;
	    $dates = [];

	    // add number of experience count as point
	    $pointExpereince += $profile->experiences->count();

	    // if requirement is matched add 1 point
	    foreach($profile->experiences as $experience){
	        if($experience->period_from){
	            $periodFrom = Carbon::parse($experience->period_from);
	            $periodTo = $experience->period_to 
	                        ? Carbon::parse($experience->period_to)
	                        : Carbon::now();

	            $dates[] = $periodFrom;
	            $dates[] = $periodTo;
	        }
	    }
		if(!empty($dates)){
		    $minDate = min($dates);
		    $maxDate = max($dates);
		    $diffYears = $maxDate->diffInYears($minDate);
		    $totalExperience += $diffYears;
	    }
	    if($job['min_experience'] == "1-3 years" && $totalExperience >= 1){
	        $pointExpereince += 1;
	    }
	    else if($job['min_experience'] == "3 - 5 years" && $totalExperience >= 3){
	        $pointExpereince += 1;
	    }
	    else if($job['min_experience'] == "5 - 10 years" && $totalExperience >= 5){
	        $pointExpereince += 1;

	    }
	    else if($job['min_experience'] == "> 10 years" && $totalExperience > 10){
	        $pointExpereince += 1;
	    }



	    return $pointExpereince;
  	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function hasBachelorDegree($degree)
	{
	    if(strpos(strtolower($degree), strtolower('Bachelor')) !== false
	        || strpos(strtolower($degree), strtolower('BSC')) !== false
	        || strpos(strtolower($degree), strtolower('B.Sc.')) !== false
	        || strpos(strtolower($degree), strtolower('BBA')) !== false
	        || strpos(strtolower($degree), strtolower('BA')) !== false
	        || strpos(strtolower($degree), strtolower('MBBS')) !== false
	    ){
	       return true;
	    }
	    else{
	        return false;
	    }
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function hasMasterDegree($degree)
	{
	    if(strpos(strtolower($degree), strtolower('Master')) !== false
	        || strpos(strtolower($degree), strtolower('MSC')) !== false
	        || strpos(strtolower($degree), strtolower('M.Sc.')) !== false
	        || strpos(strtolower($degree), strtolower('MA')) !== false
	        || strpos(strtolower($degree), strtolower('MD')) !== false // medical
	        || strpos(strtolower($degree), strtolower('MS')) !== false // medical
	        || strpos(strtolower($degree), strtolower('MHA')) !== false // medical
	    ){
	        return true;
	    }
	    else{
	        return false;
	    }
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function hasDiplomaDegree($degree)
	{
	    if(strpos(strtolower($qualification->degree), strtolower('Diploma')) !== false){
	       return true;
	    }
	    else{
	        return false;
	    }
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function hasDoctorateDegree($degree)
	{
	    if(
	    	strpos(strtolower($degree), strtolower('Doctor')) !== false
	        || strpos(strtolower($degree), strtolower('PhD')) !== false
	    ){
	        return true;
	    }
	    else{
	        return false;
	    }
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function hasHigherSecondaryDegree($degree)
	{
	    if(
	    	strpos(strtolower($degree), strtolower('Higher Secondary')) !== false
	        || strpos(strtolower($degree), strtolower('HSC')) !== false
	        || strpos(strtolower($degree), strtolower('A Level')) !== false
	        || strpos(strtolower($degree), strtolower('A-Level')) !== false
	        || strpos(strtolower($degree), strtolower('Alim')) !== false
	    ){
	        return true;
	    }
	    else{
	        return false;
	    }
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function hasSecondaryDegree($degree)
	{
	    if(
	    	strpos(strtolower($degree), strtolower('Secondary')) !== false
	        || strpos(strtolower($degree), strtolower('SSC')) !== false
	        || strpos(strtolower($degree), strtolower('O Level')) !== false
	        || strpos(strtolower($degree), strtolower('O-Level')) !== false
	        || strpos(strtolower($degree), strtolower('Dakhil')) !== false
	    ){
	        return true;
	    }
	    else{
	        return false;
	    }
	}

	/**
	 * notify applicant about interview
	 *
	 * @param object $application
	 * @return void
	 **/
	public function notifyApplicantAboutInterview($application)
	{
		$job = Job::find($application->job_id);
		if($job){
			$jobOwner = User::find($job->user_id);
			$worker = User::find($application->applicant_id);
			if($jobOwner && $worker){
				$subject = 'Interview Alert!';
				$msg = $jobOwner->name
								.' selected you for an interview for the '
								.$job->job_title.' position.';

				return [
					'recipient_id'	=>	$this->encodeHelper->encodeData($worker->id),
					'title'			=>	$subject,
					'body'			=>	$msg,
					'click_action'	=>	env("APP_URL")."/work/".$this->applicationStatuses['applied'],
					'application_id' =>  $this->encodeHelper->encodeData($application->id)
				];
			}
		}
	}

	/**
	 * notify applicant about assigned
	 *
	 * @param object $application
	 * @return void
	 **/
	public function notifyApplicantAboutHired($application)
	{
		$job = Job::find($application->job_id);

		if($job){
			$jobOwner = User::find($job->user_id);
			$worker = User::find($application->applicant_id);
			if($jobOwner && $worker){
				$subject = 'Hired Alert!';
				$msg = 'Congratulations! '.$jobOwner->name
						.' hired you for the '
						.$job->job_title.' position.';

				return [
					'recipient_id'	=>	$this->encodeHelper->encodeData($worker->id),
					'title'			=>	$subject,
					'body'			=>	$msg,
					'click_action'	=>	env("APP_URL")."/work/".$this->applicationStatuses['assigned'],
					'application_id' =>  $this->encodeHelper->encodeData($application->id)
				];
			}
		}
	}

	public function changeRelationName($jobs, $oldKey, $newKey)
	{
		foreach($jobs as $key => $job){
			$applications = $job[$oldKey];
			
			unset($jobs[$key][$oldKey]);
			$jobs[$key][$newKey] = $applications;
		}
		return $jobs;
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
	    return array_map(
	    	[$this, 'transformImage'], 
	    	is_array($images) ? $images : $images->toArray()
	    );
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


	private function isJobOwner($jobId)
	{
		$job = Job::find($jobId);
		return $job->user_id ==  $this->user->id ? true : false; 
	}

	private function paginateApplicants(Array $jobs): Array 
	{
		$query = request()->query();

		foreach($jobs as $key => $job) {
			$paginatedApplications = CustomPaginateHelper::getPaginate(
				$job['applications'],
				$this->configHelper->getPaginatePerPage(), 
				'',
				$query,
				isset($query['page']) ? $query['page'] : null
			);

			$paginatedApplications = $paginatedApplications->toArray();

			$jobs[$key]['applications'] = [];
			$jobs[$key]['applications']['data'] = $paginatedApplications['data'];
			$jobs[$key]['applications']['pagination'] = [
				'current_page' => $paginatedApplications['current_page'],
				'last_page' => $paginatedApplications['last_page'],
				'next_page_url' => $this->getApplicantPaginationUrl(
					$paginatedApplications['next_page_url'],
					$job['id']
				),
				'prev_page_url' => $this->getApplicantPaginationUrl(
					$paginatedApplications['prev_page_url'],
					$job['id']
				),
				'total' => $paginatedApplications['total'],
				'per_page' => $paginatedApplications['per_page']
			];
		}

		return $jobs;
	}

	private function addApplicantsProfiles($jobs, $profiles) 
	{
		$profileIds = array_keys($profiles);
		foreach($jobs as $jobKey => $job) {
			if(isset($job['applications']['data'])){ // for paginated data access
				foreach($job['applications']['data'] as $applicationKey => $application){
					if(in_array($application['applicant_id'], $profileIds)){
					 	$jobs[$jobKey]['applications']['data']
					 		[$applicationKey]['details'] = $profiles[$application['applicant_id']];
					}
				}
			}
			else{ // without paginated data
				foreach($job['applications'] as $applicationKey => $application){
					if(in_array($application['applicant_id'], $profileIds)){
					 	$jobs[$jobKey]['applications']
					 		[$applicationKey]['details'] = $profiles[$application['applicant_id']];
					}
				}
			}
		}

		return $jobs;
	}

	private function addApplicantsProfilesForNoPagination($jobs, $profiles) 
	{
		$profileIds = array_keys($profiles);
		foreach($jobs as $jobKey => $job) {
			foreach($job['applications'] as $applicationKey => $application){
				if(in_array($application['applicant_id'], $profileIds)){
				 	$jobs[$jobKey]['applications']
				 		[$applicationKey]['details'] = $profiles[$application['applicant_id']];
				}
			}
		}

		return $jobs;
	}

	private function getApplicantPaginationUrl($url, $jobId): string
	{
		if($url == null) 
			return 'null';

		$apipathEndPos = strpos($url, 'hire');
		$apiPath = substr($url, 0, $apipathEndPos); 

		$queryStartPos = strpos($url, '?');
		$query = substr($url, $queryStartPos);

		$paginationUrl = $apiPath
			. 'hire/jobs/'
			. $this->encodeHelper->encodeData($jobId)
			. '/applied-applicants'
			. $query;
		return $paginationUrl;
	}
}