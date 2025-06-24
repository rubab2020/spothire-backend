<?php
namespace App\SpotHire\Transformers;

use App\SpotHire\Transformers\Transformer;
use App\SpotHire\Helpers\EncodeHelper;
use App\User;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Transformers\WorkerProfileTransformer;
use App\SpotHire\Transformers\HireProfileTransformer;

class HireAppliedTransformer extends Transformer
{
	/**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    /**
     * @var App\Helpers\ConfiHelper
     **/
    private $configHelper;

    /**
    * @var App\SpotHire\Transformers\WorkerProfileTransformer
    **/
    protected $workerProfileTransformer;

    /**
    * @var App\SpotHire\Transformers\WorkerProfileTransformer
    **/
    protected $hireProfileTransformer;

    /**
    * @var App\SpotHire\Transformers\JobTransformer
    **/
    protected $jobTransformer;

    private $nameOfuserTypes;
    
    private $userTypes;

    private $nameOfReviewSatuses;

    function __construct(
        EncodeHelper $encodeHelper, 
        ConfigHelper $configHelper,
        WorkerProfileTransformer $workerProfileTransformer, 
        HireProfileTransformer $hireProfileTransformer, 
        JobTransformer $jobTransformer
    ){
        $this->encodeHelper = $encodeHelper;
        $this->configHelper = $configHelper;
        $this->workerProfileTransformer = $workerProfileTransformer;
        $this->hireProfileTransformer = $hireProfileTransformer;
        $this->jobTransformer = $jobTransformer;
        $this->nameOfuserTypes = $configHelper->getNameOfUserTypes();
        $this->userTypes = $configHelper->getUserTypes();
        $this->nameOfReviewSatuses  = $configHelper->getNameOfReviewStatuses();
    }

	/**
     * transform a object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transform($job)
    {
        $data = [
            'id'                => $this->encodeHelper->encodeData($job['id']),
            'employer_id'       => $this->encodeHelper->encodeData($job['user_id']),
            'job_title'         => $job['job_title'],
            'slug'              => $job['slug'],
            'employment_type'   => $job['employment_type'],
            'start_date'        => $job['start_date'],
            'end_date'          => $job['end_date'],
            'salary_from'       => $job['salary_from'],
            'salary_to'         => $job['salary_to'],
            'salary_type'       => $job['salary_type'],
            'is_salary_negotiable' => $job['is_salary_negotiable'] ? true :false,
            'address'           => $job['address'],
            'location'          => $job['location'],
            'department'        => $job['department'],
            'min_experience'    => $job['min_experience'],
            'min_qualification' => $job['min_qualification'],
            'description'       => $job['description'],
            'requirements'      => $job['requirements'],
            'advert_duration'   => $job['advert_duration'],
            'advert_deadline'   => $job['advert_deadline'],
            'currency_type'     => $job['currency_type'],
            'skills'            => $this->workerProfileTransformer
                                        ->transformSkills($job['skills']),
            'review_status'        => $this->nameOfReviewSatuses[$job['review_status']],
            'is_payment_completed' => $job['is_payment_completed'] ? true : false,
            'is_temporary_unlocked'=> $job['is_temporary_unlocked'] ? true : false,
            'temp_unlock_due_till' => $job['temp_unlock_due_till'],
            'due_amount'    => $this->getDueAmount($job['advert_duration'], $job['currency_type']), 
        ];

        if(isset($job['applications']['data'])) { // paginated applicants
            $data['applicants'] = [
                'data' => $this->transformApplications($job['applications']['data']),
                'pagination' => $job['applications']['pagination']
            ];
        }
        else {
            $data['applicants'] = $this->transformApplications($job['applications']);
        }

        return $data;
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function getDueAmount($advertDuration, $currency)
    {
        $advertDays = $this->configHelper->getAdvertDays();
        foreach($advertDays as $advert){
            if($advertDuration == $advert['value']){
                return $advert['amount'];
            }
        }

        return null;
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function transformApplications($applications)
    {
        return array_map([$this, 'transformApplication'], $applications);
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function transformApplication($application)
    {
        $user = User::find($application['applicant_id']);
        return [
            'application_id'    => $this->encodeHelper->encodeData($application['id']),
            'account_type'      => $this->nameOfuserTypes[$user['user_type']],
            'application_status' => $application['application_status'],
            'is_short_listed'    => $application['is_short_listed'] == 1 
                                        ? true : false,
            'interview_date'     => $application['interview_date'],
            'interview_time'     => $application['interview_time'],
            'counts'             => $application['counts'],
            'details'            => $user['user_type'] == $this->userTypes['individual']
                                    ? $this->workerProfileTransformer
                                        ->transform($application['details'])
                                    : $this->hireProfileTransformer
                                        ->transform($application['details'])
        ];
    }
}