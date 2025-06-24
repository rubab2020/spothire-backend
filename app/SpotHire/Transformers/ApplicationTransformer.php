<?php
namespace App\SpotHire\Transformers;

use App\SpotHire\Transformers\Transformer;
use App\SpotHire\Helpers\EncodeHelper;
use App\User;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Transformers\WorkerProfileTransformer;
use App\SpotHire\Transformers\JobTransformer;

class ApplicationTransformer extends Transformer
{
	/**
	 * @var App\Helpers\EncodeHelper
	 **/
	private $encodeHelper;

	/**
	 * @var App\Helpers\ConfigHelper
	 **/
	private $configHelper;

	/**
	 * @var App\Helpers\CustomHelper
	 **/
	private $customHelper;

	/**
	* @var App\SpotHire\Transformers\WorkerProfileTransformer
	**/
	protected $workerProfileTransformer;

	/**
	* @var App\SpotHire\Transformers\JobTransformer
	**/
	protected $jobTransformer;

	/**
	* @var array
	**/
	private $applicationStatuses;

  private $nameOfuserTypes;

	function __construct(
		EncodeHelper $encodeHelper, 
		CustomHelper $customHelper, 
		WorkerProfileTransformer $workerProfileTransformer, 
		JobTransformer $jobTransformer,
		ConfigHelper $configHelper
	){
		$this->encodeHelper = $encodeHelper;
		$this->customHelper = $customHelper;
		$this->jobTransformer = $jobTransformer;
		$this->workerProfileTransformer = $workerProfileTransformer;
		$this->configHelper = $configHelper;
		$this->applicationStatuses = $configHelper->getApplicationStatuses();
    $this->nameOfuserTypes = $configHelper->getNameOfUserTypes();
	}

	/**
	 * transform a object for mapping between api parameters and database columns
	 *
	 * @param array of objects $jobs
	 * @return array
	 **/
	public function transform($application)
	{
		$ratingStatuses = $this->configHelper->getRatingStatuses();
		$photoPath = User::getPhotoPath($application['company_details']['id']);

		$employerPhoto = $application['company_details']['picture'] 
											? $photoPath.$application['company_details']['picture']
											: null;
		$employerCoverPhoto = $application['company_details']['cover_photo']
							   					? $photoPath.$application['company_details']['cover_photo']
							   					: null;

		return [
			'id'                  => $this->encodeHelper->encodeData($application['id']),
			'application_status'	=> $application['application_status'],
			'is_job_completed'    => $application['application_status'] == $this->applicationStatuses['completed'],
			'rating_status'       => $application['rating_status'] 
																? $ratingStatuses[$application['rating_status']] 
																: null, // todo: remove this for applied section
			'rating'              => $application['rating'], // todo: remove this for applied section
			'interview_date'      => $application['interview_date'],
			'interview_time'      => $application['interview_time'],
			'job' => [
				'id'            		=> $this->encodeHelper->encodeData($application['job_id']),
				'job_title'         => $application['job_details']['job_title'],
				'slug'              => $application['job_details']['slug'],
				'employment_type'   => $application['job_details']['employment_type'],
				'start_date'        => $application['job_details']['start_date'],
				'end_date'          => $application['job_details']['end_date'],
				'salary_from'       => $application['job_details']['salary_from'],
				'salary_to'         => $application['job_details']['salary_to'],
				'salary_type'       => $application['job_details']['salary_type'],
				'is_salary_negotiable' => $application['job_details']['is_salary_negotiable'] ? true :false,
				'address'           => $application['job_details']['address'],
				'location'              => $application['job_details']['location'],
				'department'        => $application['job_details']['department'],
				'min_experience'    => $application['job_details']['min_experience'],
				'min_qualification' => $application['job_details']['min_qualification'],
				'description'       => $application['job_details']['description'],
				'requirements'      => $application['job_details']['requirements'],
				'advert_duration'   => $application['job_details']['advert_duration'],
				'advert_deadline'   => $application['job_details']['advert_deadline'],
				'currency_type'     => $application['job_details']['currency_type'],
				'skills'            => $this->workerProfileTransformer->transformSkills($application['job_skills']),
				'employer'  => [
					'id' => $this->encodeHelper->encodeData($application['company_details']['id']),
					'account_type'		=> $this->nameOfuserTypes[$application['company_details']['user_type']],
					'name'          	=> $application['company_details']['name'],
					'profile_picture' => $employerPhoto,
					'cover_photo'   	=> $employerCoverPhoto,
					'phone'						=> $application['company_details']['phone'] 
																? base64_encode($application['company_details']['phone']) 
																: null
				]
			]
		];

		return $data;
	}
}