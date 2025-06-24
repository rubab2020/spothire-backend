<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\ConfigHelper;
use App\JobApplication;
use Carbon\Carbon;

class WorkerDashboardTest extends ApiTester
{
	/**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    /**
     * @var array
     **/
    private $applicationStatuses;

	/**
     * @var integer
     **/
    private $applicaitnExpiaryDuration = 60;

	function __construct()
    {
    	$this->encodeHelper = app(EncodeHelper::class);
    	$this->applicationStatuses = app(ConfigHelper::class)->getApplicationStatuses();
    }
    
    /** @test */
    public function it_tests_a_worker_applying_to_a_job_is_successful()
    {
    	// arrange
    	$dataID = 2;
    	$token = $this->getToken('new_user', null);
        $headers = [
        	'Content-Type' => 'application/json',
        	'Authorization' => "Bearer $token"
        ];
        $payload = [
			"job_id" => $this->encodeHelper->encodeData($dataID)
		];

    	// act
        $response = $this->postJsonResponse('api/apply-job', $payload, $headers);

    	// assert
    	$response->assertStatus(201);
        $response->assertJson([
        	'message' => 'Applied job successfully'
        ]);
    }

    /** @test */
    public function it_tests_a_worker_withdrwaing_a_job_is_successful()
    {
    	// arrange
    	$dataID = 2;
        $userID = 62;
    	$token = $this->getToken('existing_user', $userID);
        $headers = [
        	'Content-Type' => 'application/json',
        	'Authorization' => "Bearer $token"
        ];
        $payload = [
			"job_id_list" => [
				$this->encodeHelper->encodeData($dataID)
			]
		];

    	// act
        $response = $this->postJsonResponse('api/withdraw-job', $payload, $headers);

    	// assert
    	$response->assertStatus(200);
        $response->assertJsonFragment([
        	'message' => 'Withdrew job successfully'
        ]);
        $response->assertJsonStructure([
            'data'=> [
                '*' => [
                    'job_id',
                    'message',
                    'code'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_tests_a_worker_deleting_a_job_is_successful()
    {
    	// arrange
    	$dataID = 2;
        $userID = 64;
    	$token = $this->getToken('existing_user', $userID);
        $headers = [
        	'Content-Type' => 'application/json',
        	'Authorization' => "Bearer $token"
        ];
        $payload = [
			"job_id" => $this->encodeHelper->encodeData($dataID)
		];

    	// act
        $response = $this->postJsonResponse('api/delete-job', $payload, $headers);

    	// assert
    	$response->assertStatus(200);
        $response->assertJson([
        	'message' => 'Deleted job successfully'
        ]);
    }

    /** @test */
    public function it_tests_a_worker_getting_jobs_for_applied_section_correctly()
    {
    	// arrange
        $userID = 67;
        $jobID = 9;
    	$token = $this->getToken('existing_user', $userID);
        $headers = [
        	'Content-Type' => 'application/json',
        	'Authorization' => "Bearer $token"
        ];
        $payload = [];
        $this->applyJob($jobID, $userID);
        // $this->InterviewJob();

    	// act
        $response = $this->getJsonResponse('api/worker-jobs/applied-section', $payload, $headers);

    	// assert
    	$response->assertStatus(200);
    	$response->assertJsonFragment([
    		'id' => $this->encodeHelper->encodeData($jobID),
    		'application_or_job_status' => 'apply',
    	]);
        $response->assertJsonStructure([
            'data'=> [
                '*' => [
                    'id',
                    'employer_id',
                    'employer_name',
                    'employer_location',
                    'job_title',
                    'department_name',
                    'location',
                    'city',
                    'experience',
                    'qualification',
                    'work_description',
                    'required_skills',
                    'other_requirements',
                    'salary_from',
                    'salary_to',
                    'salary_per_type',
                    'employment_type',
                    'period_from',
                    'period_to',
                    'time_from',
                    'time_to',
                    'timing_flexible',
                    'work_days',
                    'days_flexible',
                    'completing_date',
                    'completing_time',
                    'address',
                    'job_type'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_tests_a_worker_getting_jobs_for_assigned_section_correctly()
    {
    	// arrange
        $userID = 68;
        $jobID = 2;
    	$token = $this->getToken('existing_user', $userID);
        $headers = [
        	'Content-Type' => 'application/json',
        	'Authorization' => "Bearer $token"
        ];
        $payload = [];
        // $this->assignJob();

    	// act
        $response = $this->getJsonResponse('api/worker-jobs/assigned-section', $payload, $headers);

    	// assert
    	$response->assertStatus(200);
    	$response->assertJsonFragment([
    		'id' => $this->encodeHelper->encodeData($jobID),
    		'application_or_job_status' => 'assign',
    	]);
        $response->assertJsonStructure([
            'data'=> [
                '*' => [
                    'id',
                    'employer_id',
                    'employer_name',
                    'employer_location',
                    'job_title',
                    'department_name',
                    'location',
                    'city',
                    'experience',
                    'qualification',
                    'work_description',
                    'required_skills',
                    'other_requirements',
                    'salary_from',
                    'salary_to',
                    'salary_per_type',
                    'employment_type',
                    'period_from',
                    'period_to',
                    'time_from',
                    'time_to',
                    'timing_flexible',
                    'work_days',
                    'days_flexible',
                    'completing_date',
                    'completing_time',
                    'address',
                    'job_type'
                ]
            ]
        ]);
    }

    private function applyJob($jobID, $userID)
    {
    	$application = new JobApplication;
		$application->applicant_id = $userID;
		$application->job_id =  $jobID;
		$application->application_status = $this->applicationStatuses['apply'];
		$application->expiary_date_if_not_assigned =  $this->setApplicationExpiaryDate();
		$application->save();
    }

    private function setApplicationExpiaryDate()
	{
		$momentNow = Carbon::Now();
        $applicationExpiaryDatetime = $momentNow->addDays($this->applicaitnExpiaryDuration);

        return $applicationExpiaryDatetime->toDateTimeString();
	}
}