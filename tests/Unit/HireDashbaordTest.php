<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\ConfigHelper;
use App\JobApplication;
use Carbon\Carbon;

class HireDashboardTest extends ApiTester
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
    private $applicationExpiaryDuration = 60;

	function __construct()
    {
    	$this->encodeHelper = app(EncodeHelper::class);
    	$this->applicationStatuses = app(ConfigHelper::class)->getApplicationStatuses();
    }

    /** @test */
    public function it_tests_shortlisting_applicants_is_successful()
    {
    	// arrange
    	$token = $this->getToken('new_user', null);
        $headers = [
        	'Content-Type' => 'application/json',
        	'Authorization' => "Bearer $token"
        ];
        $jobApplication = $this->getJobApplication($this->applicationStatuses['apply']);
        $payload = [
			"application_id" => $this->encodeHelper->encodeData($jobApplication->id)
		];

    	// act
        $response = $this->postJsonResponse('api/short-list-applicant', $payload, $headers);

    	// assert
    	$response->assertStatus(201);
        $response->assertJson([
        	'message' => 'Short listed applicant successfully'
        ]);
    }

    /** @test */
    public function it_tests_interviewing_applicant_is_successful()
    {
        // arrange
        $token = $this->getToken('new_user', null);
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $token"
        ];
        $jobApplication = $this->getJobApplication($this->applicationStatuses['apply']);
        $payload = [
            "applications" => [
                $this->encodeHelper->encodeData($jobApplication->id)
            ]
        ];

        // act
        $response = $this->postJsonResponse('api/interview-applicant', $payload, $headers);

        // assert
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'application_id' => $this->encodeHelper->encodeData($jobApplication->id),
                'message' => 'Interviewed applicant successfully',
                'code' => '200'
            ]
        ]);
    }

    /** @test */
    public function it_tests_assigning_applicant_is_successful()
    {
        // arrange
        $token = $this->getToken('new_user', null);
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $token"
        ];
        $jobApplication = $this->getJobApplication($this->applicationStatuses['interview']);
        $payload = [
            "applications" => [
                $this->encodeHelper->encodeData($jobApplication->id)
            ]
        ];

        // act
        $response = $this->postJsonResponse('api/assign-applicant', $payload, $headers);

        // assert
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'application_id' => $this->encodeHelper->encodeData($jobApplication->id),
                'message' => 'Assigned applicant successfully',
                'code' => '200'
            ]
        ]);
    }

    /** @test */
    public function it_tests_declining_applicant_is_successful()
    {
        // arrange
        $token = $this->getToken('new_user', null);
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $token"
        ];
        $jobApplication = $this->getJobApplication($this->applicationStatuses['apply']);
        $payload = [
            "applications" => [
                $this->encodeHelper->encodeData($jobApplication->id)
            ]
        ];

        // act
        $response = $this->postJsonResponse('api/decline-applicant', $payload, $headers);

        // assert
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'application_id' => $this->encodeHelper->encodeData($jobApplication->id),
                'message' => 'Declined applicant successfully',
                'code' => '200'
            ]
        ]);
    }

    /** @test */
    public function it_tests_discontinue_job_is_successful()
    {
        // hire's discontinue action 

        // arrange
        $token = $this->getToken('new_user', null);
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $token"
        ];
        $jobApplication = $this->getJobApplication($this->applicationStatuses['assign']);
        $payload = [
            "applications" => [
                $this->encodeHelper->encodeData($jobApplication->id)
            ],
            "requester" => "hire"
        ];

        // act
        $response = $this->postJsonResponse('api/discontinue-job', $payload, $headers);

        // assert
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'application_id' => $this->encodeHelper->encodeData($jobApplication->id),
                'message' => 'Discontinued job successfully',
                'code' => '200'
            ]
        ]);

        // worker's discontinue action
        $payload = [
            "applications" => [
                $this->encodeHelper->encodeData($jobApplication->id)
            ],
            "requester" => "worker"
        ];
        // act
        $response = $this->postJsonResponse('api/discontinue-job', $payload, $headers);

        // assert
        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'application_id' => $this->encodeHelper->encodeData($jobApplication->id),
                'message' => 'Discontinued job successfully',
                'code' => '200'
            ]
        ]);
    }

    /** @test */
    public function it_tests_hire_getting_applicants_for_applied_section_is_successful()
    {
        // arrange
        $token = $this->getToken('new_user', null);
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $token"
        ];
        $payload = [];

        // act
        $response = $this->getJsonResponse('api/hire-jobs/applied-section', $payload, $headers);

        // assert
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->encodeHelper->encodeData($jobID),
            'application_or_job_status' => 'assign',
        ]);
        $response->assertJsonStructure([
            'data'=> [
                'jobs' => [
                    '*' => [
                        'id',
                        'employer_id',
                        'job_title',
                        'job_create_date',
                        'applicants'
                    ]
                ],
                'profiles' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'about',
                        'picture',
                        'dob',
                        'gender',
                        'industry',
                        'incorporation_date',
                        'location',
                        'user_type',
                        'qulaifications',
                        'experiences',
                        'proejcts',
                        'awards',
                        'skills'
                    ]
                ],
                'filters' => [
                    'application_statuses' =>[
                        'apply',
                        'short_list',
                        'interview'
                    ],
                    'job_posts',
                    'skills',
                    'qualifications',
                    'universities',
                    'locations'
                ]
            ]
        ]);
    }

    /** @test */
    public function it_tests_hire_getting_applicants_for_assigned_section_is_successful()
    {
        // arrange
        $token = $this->getToken('new_user', null);
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $token"
        ];
        $payload = [];

        // act
        $response = $this->getJsonResponse('api/hire-jobs/assigned-section', $payload, $headers);

        // assert
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $this->encodeHelper->encodeData($jobID),
            'application_or_job_status' => 'assign',
        ]);
        $response->assertJsonStructure([
            'data'=> [
                'jobs' => [
                    '*' => [
                        'id',
                        'employer_id',
                        'job_title',
                        'job_create_date',
                        'applicants'
                    ]
                ],
                'profiles' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'about',
                        'picture',
                        'dob',
                        'gender',
                        'industry',
                        'incorporation_date',
                        'location',
                        'user_type',
                        'qulaifications',
                        'experiences',
                        'proejcts',
                        'awards',
                        'skills'
                    ]
                ],
                'filters' => [
                    'application_statuses' =>[
                        'apply',
                        'short_list',
                        'interview'
                    ],
                    'job_posts',
                    'skills',
                    'qualifications',
                    'universities',
                    'locations'
                ]
            ]
        ]);
    }

    public function getJobApplication($status)
    {
        $hire = factory(\App\User::class)->create();
        $worker = factory(\App\User::class)->create();
        $job = factory(\App\WriteOffer::class)->create(['hire_id'=>$hire->id]);
        $jobApplication = factory(\App\JobApplication::class)->create([
            'id'=>$job->id, 
            'worker_id'=>$worker->id, 
            'application_status'=>$status
        ]);
        return $jobApplication;
    }
}