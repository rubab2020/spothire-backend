<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Job;
use App\User;
use App\Skill;
use App\Experience;
use App\Tag;
use Carbon\Carbon;
use App\JobApplication;
use App\TrackUserJobFilter;
use App\Institution;
use App\TargetUniversity;
use App\SpotHire\Transformers\JobTransformer;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Helpers\CustomPaginateHelper;
use App\SpotHire\Transformers\CustomTransformer;

class JobPublicController extends ApiController
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
    // private $clientInfo;

    /**
    * @var App\SpotHire\Helpers\CustomHelper
    **/
    protected $customPaginateHelper;

    private $customTransformer;


    function __construct(
        ConfigHelper $configHelper, 
        JobTransformer $jobTransformer, 
        CustomHelper $customHelper, 
        EncodeHelper $encodeHelper, 
        CustomPaginateHelper $customPaginateHelper,
        CustomTransformer $customTransformer
    ){
        $this->configHelper = $configHelper;
        $this->jobTransformer = $jobTransformer;
        $this->customHelper = $customHelper;
        $this->customPaginateHelper = $customPaginateHelper;
        $this->applicationStatuses = $configHelper->getApplicationStatuses();
        $this->encodeHelper = $encodeHelper;
        $this->customTransformer = $customTransformer;
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
                'prev_page_url' => $jobs['prev_page_url']
            ]
        ];
        if($request->isMethod('post') && $filters == null){
            // Attach filter options for initial POST request API call. other times API calls are either filter applied calls which does not need any filters data via POST request or infinity paginated data scroll which is a GET request.

            $jobsWithoutFilters = $this->fetchJobsData(false, null, null, false);
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
        
    
        $jobs->latest()
            ->with('skills')
            ->isApproved()
            ->notExpired()
            // ->notTargeted()
            ->filters($filters ? $filters : [])
            ->select('jobs.*',
                'users.name as employer_name', 
                'users.picture as employer_picture',
                'users.cover_photo as employer_cover_photo',
                'users.user_type as employer_account_type');

        $jobs = $jobs->get();

        if($isPaginateable){
            $jobs = $jobs->toArray();
            $query = request()->query();
            $jobs = CustomPaginateHelper::getPaginate(
                        $jobs, 
                        $this->configHelper->getPaginatePerPage(), 
                        '', 
                        $query, 
                        isset($query['page']) ? $query['page'] : null);
        }             

        return $jobs;
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

        $employmentTypes = $this->customHelper
                            ->countColumnUniqueData($jobs, 'employment_type', $this->configHelper->getFilterData('employment_types'));

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

        $job = Job::join('users', function($join){
            $join->on('jobs.user_id', 'users.id');
        })
        ->with('skills')
        ->notExpired()
        ->select('jobs.*', 
            'users.name as employer_name', 
            'users.picture as employer_picture',
            'users.cover_photo as employer_cover_photo',
            'users.user_type as employer_account_type')
        ->where('jobs.slug', $slug)
        ->first();

        return $this->respond([
            'data' => $this->jobTransformer->transform($job->toArray()),
        ]); 
    }

    /**
     * return counts on filters
     *
     * @return void
     **/
    public function getCountsOnFilters()
    {
        $jobsWithoutFilters = $this->fetchJobsData(false, null, null, false);

        return $this->respond([
                'filters' => $this->getFilterOptions($jobsWithoutFilters)
        ]);
    }    
}
