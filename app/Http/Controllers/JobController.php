<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Job;
use App\User;
use App\Skill;
use App\Experience;
use Carbon\Carbon;
use App\JobApplication;
use App\TrackUserJobFilter;
use App\TargetUniversity;
use App\SpotHire\Transformers\JobTransformer;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Helpers\CustomPaginateHelper;

class JobController extends ApiController
{
	/**
	 * @var App\SpotHire\Helpers\ConfigHelper
	 **/
	private $configHelper;

	/**
	* @var App\SpotHire\Transformers\JobTransformer
	**/
	protected $jobTransformer;

	/**
	* @var App\SpotHire\Helpers\CustomHelper
	**/
	protected $customHelper;

	/**
	 * @var array
	 **/
	private $applicationStatuses;

	/**
	 * @var App\Helpers\EncodeHelper
	 **/
	private $encodeHelper;

	/**
	* @var integer
	**/
	private $clientInfo;

	/**
	* @var App\SpotHire\Helpers\CustomHelper
	**/
	protected $customPaginateHelper;

	function __construct(
		ConfigHelper $configHelper, 
		JobTransformer $jobTransformer, 
		CustomHelper $customHelper, 
		EncodeHelper $encodeHelper, 
		CustomPaginateHelper $customPaginateHelper
	){
		$this->configHelper = $configHelper;
		$this->jobTransformer = $jobTransformer;
		$this->customHelper = $customHelper;
		$this->customPaginateHelper = $customPaginateHelper;
		$this->applicationStatuses = $configHelper->getApplicationStatuses();
		$this->encodeHelper = $encodeHelper;
		$this->user = auth()->guard('api')->authenticate();
	}


	/**
	 * return jobs circulars
	 *
	 * @return \Illuminate\Http\Response
	 **/
	public function getJobs(Request $request)
	{
		$filters = $this->getFiltersSelected($request);

		$jobs = $this->fetchJobsData($filters, null, true);
		$jobs = $jobs->toArray();


		$data = [
			'data' => $this->jobTransformer->transformCollection($jobs['data']),
			'pagination' => [
				'current_page' => $jobs['current_page'],
				'last_page' => $jobs['last_page'],
				'next_page_url' => $jobs['next_page_url'],
				'prev_page_url' => $jobs['prev_page_url'],
				'total' => $jobs['total'],
				'per_page' => $jobs['per_page']
			]
		];
		if($request->isMethod('post') && $filters == null){
			// Attach filter options for initial POST request API call. other times API calls are either filter applied calls which does not need any filters data via POST request or infinity paginated data scroll which is a GET request.

			$jobsWithoutFilters = $this->fetchJobsData(false, null, false);
			$data['filters'] = $this->getFilterOptions($jobsWithoutFilters);
		}

		return $this->respond($data); 
	}

	/**
	 * return filters selected
	 *
	 * @return mixed
	 **/
	public function getFiltersSelected($request)
	{
		$filters = $request->input('filters') ? $request->input('filters') : null;
		
		if($request->isMethod('post')){ // save filters selected
			if($filters){
				$userFilter = TrackUserJobFilter::where('user_id', $this->user->id)
												->first();
				if($userFilter == null){
					$userFilter = new TrackUserJobFilter;
				}
				$userFilter->user_id = $this->user->id;
				$userFilter->filters = serialize($filters);
				$userFilter->save();
			}
		}
		else{ // get saved filters
			$obj = TrackUserJobFilter::select('filters')
											->where('user_id', $this->user->id)
											->first();
			if($obj){
				$filters = unserialize($obj->filters);
			} 
		}

		return $filters;
	}

	/**
	 * return jobs
	 *
	 * @return objects
	 **/
	public function fetchJobsData($filters, $startFrom, $isPaginateable)
	{
		$jobs = [];

		// job info
		$jobs = Job::join('users', function($join){
			$join->on('jobs.user_id', 'users.id');
		});
		
		$jobs->leftJoin('favourites', function($join){
			$join->on('favourites.job_id', 'jobs.id')
				->where('favourites.user_id', $this->user->id);
		});
	
		$jobs->latest()
			->with('skills')
			->notApplied($this->user->id)
			->isApproved()
			->notExpired()
			->filters($filters ? $filters : [])
			->select('jobs.*', 
				'users.name as employer_name', 
				'users.picture as employer_picture',
				'users.cover_photo as employer_cover_photo',
				'users.user_type as employer_account_type',
				'favourites.id as favourite_id');

		$jobs = $jobs->get();


		$jobs = $this->orderJobsByRank($jobs);

		if($isPaginateable){
			$query = request()->query();
			$jobs = CustomPaginateHelper::getPaginate(
				$jobs, 
				$this->configHelper->getPaginatePerPage(), 
				'', 
				$query, 
				isset($query['page']) ? $query['page'] : null
			);
		}             

		return $jobs;
	}

	public function filterJobsIfTargetAudienceisSet($jobs)
	{ 
		$filteredJobs = [];
		$internalJobs = [];

		// candidate info
	    $candidate = User::find($this->user->id);
	    $candidateInstitutes =  [];
	    foreach ($candidate->qualifications as $qualification) {
	    	$candidateInstitutes[] = $qualification->institution;
	    }

    	// keeps jobs if matches target option or no target provided schenerio
		foreach($jobs as $job){
			if($job['target_audience'] == 'internal'){ // keep job if he is a hired already
				$res = $this->isCandidateAssigedToJob($job);
				if($res) 
					$internalJobs[] = $job;
			}
			else if($job['target_audience'] == 'university'){ // keeping job in matchet any of the targetget university
				$res = TargetUniversity::whereIn('name', $candidateInstitutes)->first();
				if($res) 
					$filteredJobs[] = $job;
			}
			else{ // job has not target applied, so keeping it
				$filteredJobs[] = $job;
			}
		}

		// adding internal jobs to the begging of data.
		for($i = count($internalJobs)-1; $i >= 0; $i--){
			array_unshift($filteredJobs, $internalJobs[$i]);
		}

		return $filteredJobs;
	}

	public function isCandidateAssigedToJob($job){
		$hasFound = false;

		$jobOnwer = User::select('id')->find($job->user_id);
		$ownerJobs = Job::with('applications')
          				->withTrashed()
						->where('user_id', $jobOnwer->id)
						->latest()
						->get();

		foreach($ownerJobs as $ownerJob){
			foreach($ownerJob->applications as $application){
				if(
					$application->applicant_id == $this->user->id 
					&& $application->application_status == $this->applicationStatuses['assigned']
				){
					$hasFound = true;
					break;
				}
			}

			if($hasFound) break;
		}

		return $hasFound;
	}


	// order jobs by points
	/**
	 * return filter options
	 *
	 * @param array $jobs
	 * @return array of objects
	 **/
	public function getFilterOptions($jobs)
	{
		$jobs = collect($jobs);

		$employmentTypes = $this->customHelper->countColumnUniqueData(
			$jobs, 
			'employment_type', 
			$this->configHelper->getFilterData('employment_types')
		);

		$salaryRanges = $this->configHelper->getFilterData('salary_ranges');
		$salaryRanges = $this->customHelper->countJobForEachSalaryFilter($jobs, $salaryRanges);

		$deadlineDurations = $this->configHelper->getFilterData('deadline_durations');
		$deadlineDurations = $this->customHelper->countJobforEachDeadLineFilter($jobs, $deadlineDurations);

		$locationNames = $this->customHelper
							->countColumnUniqueData($jobs, 'location', $this->configHelper->getFilterData('location_names'));

		$companyNames = User::getCompanyNames($jobs);
		$jobTitles = Job::getjobTitles($jobs);
		$jobSearchOptions = array_merge($companyNames, $jobTitles);

		return [
			'employment_types' => $employmentTypes,
			'salary_ranges' => $salaryRanges,
			'deadline_durations' => $deadlineDurations,
			'location_names' => $locationNames,
			'job_search_options' => $jobSearchOptions
		];
	}

	/**
	 * return job detils
	 *
	 * @return Illuminate/Http/Response
	 **/
	public function getJobDetails(Request $request)
	{
		$slug = $request->input('slug');
	    if($slug === null){
	        return $this->respondValidationError('Slug not found in the request');
	    }

		// job info
		$job = Job::join('users', function($join){
				$join->on('jobs.user_id', 'users.id');
			})
			->leftJoin('job_applications', function($join){
				$join->on('jobs.id', 'job_applications.job_id');
				$join->where('job_applications.applicant_id', $this->user->id);
			})
			->leftJoin('favourites', function($join){
				$join->on('favourites.job_id', 'jobs.id')
						 ->where('favourites.user_id', $this->user->id);
			})
			->with('skills')
			->withTrashed()
		    ->notExpired()
		    ->select('jobs.*', 
		        'users.name as employer_name', 
		        'users.picture as employer_picture',
		        'users.cover_photo as employer_cover_photo',
		        'users.user_type as employer_account_type',
				'favourites.id as favourite_id',
				'job_applications.application_status as application_status',
				'job_applications.deleted_at as deleted_at' 
		    )
		    ->where('jobs.slug', $slug)
		    ->first();

	    if($job['application_status'] && $job['deleted_at'] == null){
	    	$job['is_job_appliable'] = false;
	    } 
	    else if($job['application_status'] && $job['deleted_at'] != null){
	    	$job['is_job_appliable'] = true;
	    }
	    else{
	    	$job['is_job_appliable'] = true;
	    }
	    unset($job['application_status']);

	    // check if job has target audience
	    $candidate = User::find($this->user->id);
	    $candidateInstitutes =  [];
	    foreach ($candidate->qualifications as $qualification) {
	    	$candidateInstitutes[] = $qualification->institution;
	    }
	    if($job['target_audience'] == 'internal'){
	    	$res = $this->isCandidateAssigedToJob($job);
				if(!$res) $job = null; 
	    }
	    else if($job['target_audience'] == 'university'){
	    	$res = TargetUniversity::whereIn('name', $candidateInstitutes)->first();
				if(!$res) $job = null; 
	    }

		return $this->respond([
			'data' => $job 
				? $this->jobTransformer->transform($job->toArray())
				: null
		]); 
	}

	/**
	 * return counts on filters
	 *
	 * @return void
	 **/
	public function getCountsOnFilters()
	{
		$jobsWithoutFilters = $this->fetchJobsData(false, null, false);

		return $this->respond([
			'filters' => $this->getFilterOptions($jobsWithoutFilters)
		]);
	}  

	/**
	 * order jobs by rank 
	 *
		Algorthim for showing best matched jobs to the top of loaded data. 
		user profile            -between-       - job details
		-------------                           ------------
		1. match skills         -between-       - required skills
		2. match education      -between-       - with mininum qualification
		3. match designation    -between-       - department and job title [future work]
		4. count experience     -between-       - min experience

		point ranking: 
			.1 point for each matched skill, 
			.1 point for each education equal to min qualification
			.1 point for each experience equal to min experience.
	 *
	 * @param obj $jobs
	 * @return array
	 **/
	public function orderJobsByRank($jobs)
	{
		$profile = $this->getProfile();

		// calcuate rank
		$pointJobs = [];
		foreach($jobs as $job){
			$pointSkill = $this->calcSkillMatchPoint($job, $profile);
			$pointQualification = $this->calcEducationMatchPoint($job, $profile);
			$pointExpereince = $this->calcExperienceMatchPoint($job, $profile);

			$totalPoints = $pointSkill + $pointQualification + $pointExpereince;
			$pointJobs[] = ['id'=>$job->id, 'point'=>$totalPoints];
		}

		// highest points order for jobs
		$newOrderJobs = collect($pointJobs)->sortByDesc('point')->pluck('id');
		
		// order jobs by points
		$orderByRankJobs = [];
		foreach($newOrderJobs  as $newOrdJobId){
			foreach($jobs as $job){
				if($newOrdJobId == $job->id){
					$orderByRankJobs[] = $job;
					break;
				}
			}
		}

		return $orderByRankJobs;
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
		$jSkills = collect($job->skills)->pluck('name')->toArray(); 
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
		if($job->min_qualification == 'Bechelor'){
			foreach($profile->qualifications as $qualification){
		        $hasBachelor = $this->hasBachelorDegree($qualification->degree);
		        if($hasBachelor){
		          $pointQualification = 1;
		          break;
		        }
		     }
		}
		else if($job->min_qualification == 'Master'){
			foreach($profile->qualifications as $qualification){
		        $hasMaster = $this->hasMasterDegree($qualification->degree);
		        if($hasMaster){
		          $pointQualification = 1;
		          break;
		        }
		    }  
		}
		else if($job->min_qualification == 'Diploma'){
			foreach($profile->qualifications as $qualification){
		        $hasDiploma = $this->hasDiplomaDegree($qualification->degree);
		        if($hasDiploma){
		          $pointQualification = 1;
		          break;
		        }
	      	}
		}
		else if($job->min_qualification == 'Doctor'){
			foreach($profile->qualifications as $qualification){
		        $hasDoctorate = $this->hasDoctorateDegree($qualification->degree);
		        if($hasDoctorate){
		          $pointQualification = 1;
		        	break;
		        }
		    }
		}
		else if($job->min_qualification == 'Higher Secondary'){
			foreach($profile->qualifications as $qualification){
		        $hasHigherSecondary = $this->hasHigherSecondaryDegree($qualification->degree);
		        if($hasHigherSecondary){
		          $pointQualification = 1;
		        	break;
		        }
		    }   
		}
		else if($job->min_qualification == 'Secondary'){
			foreach($profile->qualifications as $qualification){
	        $hasSecondary = $this->hasSecondaryDegree($qualification->degree);
	        if($hasSecondary){
	          $pointQualification = 1;
	          break;
	        }
	      }
		}

		return $pointQualification;
	}

	/**
	 * calculate experience match point
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

		// issue: experiecne more than 3 years is getting same point, so user with greater than 3 years are seeing 1-3 years of expereince. need to fix this in future
		if($job->min_experience == "1-3 years" && $totalExperience >= 1){
			$pointExpereince = 1;
		}
		else if($job->min_experience == "3 - 5 years" && $totalExperience >= 3){
			$pointExpereince = 1;
		}
		else if($job->min_experience == "5 - 10 years" && $totalExperience >= 5){
			$pointExpereince = 1;
		}
		else if($job->min_experience == "> 10 years" && $totalExperience > 10){
			$pointExpereince = 1;
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
      if(strpos(strtolower($degree), strtolower('Bechelor')) !== false
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
      if(strpos(strtolower($degree), strtolower('Diploma')) !== false){
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
	 * undocumented function
	 *
	 * @return void
	 **/
	public function getProfile()
	{   
		$user = User::with(
					[
						'experiences.workImages', 
						'qualifications', 
						'awards', 
						'skills'
					])
					->find($this->user->id);
		$user->experiences = $this->addExperienceRating($user->experiences);
		$user->experiences = Experience::updateCompanyNameIfAssgined($user->experiences);
		$user->rating = $this->calculateProfileRating($user->experiences);

		return $user;
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
			$application = JobApplication::find($experience->application_id);
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
			$application = JobApplication::find($experience->application_id);
			if($application && $application->rating != null){
				$totalPoint += $application->rating;
				$totalRatedExperience++;
			}
		}
		return $totalRatedExperience > 0 ? number_format(($totalPoint / $totalRatedExperience), 1) : 0;
	}
}
