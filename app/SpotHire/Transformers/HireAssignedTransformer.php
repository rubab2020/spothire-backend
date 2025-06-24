<?php
namespace App\SpotHire\Transformers;

use App\SpotHire\Transformers\Transformer;
use App\SpotHire\Helpers\EncodeHelper;
use App\User;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Transformers\WorkerProfileTransformer;
use App\SpotHire\Transformers\HireProfileTransformer;

class HireAssignedTransformer extends Transformer
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
     * @var
     **/
    private $ratingStatuses;

    /**
    * @var array
    **/
    private $applicationStatuses;

    private $nameOfuserTypes;

    private $userTypes;

    /**
    * @var App\SpotHire\Transformers\WorkerProfileTransformer
    **/
    protected $workerProfileTransformer;

    /**
    * @var App\SpotHire\Transformers\WorkerProfileTransformer
    **/
    protected $hireProfileTransformer;

    function __construct(
        EncodeHelper $encodeHelper, 
        ConfigHelper $configHelper,
        WorkerProfileTransformer $workerProfileTransformer,
        HireProfileTransformer $hireProfileTransformer
    ){
        $this->encodeHelper = $encodeHelper;
        $this->configHelper = $configHelper;
        $this->ratingStatuses = $this->configHelper->getRatingStatuses();
        $this->applicationStatuses = $configHelper->getApplicationStatuses();
        $this->nameOfuserTypes = $configHelper->getNameOfUserTypes();
        $this->workerProfileTransformer = $workerProfileTransformer;
        $this->hireProfileTransformer = $hireProfileTransformer;
        $this->userTypes = $configHelper->getUserTypes();

    }

	/**
     * transform a object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transform($job)
    {
        return [
            'id'           => $this->encodeHelper->encodeData($job['id']),
            'employer_id'  => $this->encodeHelper->encodeData($job['user_id']),
            'job_title'    => $job['job_title'],
            'employees'    => array_values($this->transformEmployees($job['applications']))
        ];
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function transformEmployees($employees)
    {
        return array_map([$this, 'transformEmployee'], $employees);
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function transformEmployee($employee)
    {
        $user = User::find($employee['applicant_id']);
        return [
            'id'                            => $this->encodeHelper->encodeData($employee['applicant_id']),
            'application_id'                => $this->encodeHelper->encodeData($employee['id']),
            'account_type'                  => $this->nameOfuserTypes[$user['user_type']],
            'name'                          => $user->name,
            'picture'                       => ($user['picture'] != '' && $user['picture'] != null) 
                                                ? User::getPhotoPath($user['id']).$user['picture'] 
                                                : null,
            'is_job_completed'              => $employee['application_status'] == $this->applicationStatuses['completed']
                                                ? true : false, 
            'has_rating_request'            => $employee['rating_status'] == $this->ratingStatuses['pending'] ? true : false,
            'rating'                        => $employee['rating'],
            'details'            => $user['user_type'] == $this->userTypes['individual']
                                    ? $this->workerProfileTransformer
                                        ->transform($employee['details'])
                                    : $this->hireProfileTransformer
                                        ->transform($employee['details'])
        ];
    }
}