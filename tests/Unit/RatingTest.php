<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\SpotHire\Helpers\EncodeHelper;

class RatingTest extends ApiTester
{
    /**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    function __construct()
    {
        $this->encodeHelper = app(EncodeHelper::class);
    }

    /** @test */
    public function it_tests_a_worker_requesting_for_rating_to_a_job_is_successful()
    {
        // arrange
        $userID = 68;
        $dataID = 2;
        $token = $this->getToken('existing_user', $userID);
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $token"
        ];
        $payload = [
            "job_id" => $this->encodeHelper->encodeData($dataID)
        ];

        // act
        $response = $this->postJsonResponse('api/rating/request', $payload, $headers);

        // assert
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Requested for rating successfully'
        ]);
    }

    /** @test */
    public function it_tests_employer_rate_a_job_is_successful()
    {
        // arrange
        $token = $this->getToken('new_user', null);
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $token"
        ];
        $jobApplication = $this->getJobApplication($this->applicationStatuses['assign']);
        $jobApplication = $this->enableRateOption($jobApplication);
        $skill = $this->getJobSkill($jobApplication);
        $payload = [
            "application_id" => $this->encodeHelper->encodeData($jobApplication->id),
            "skills" => [
                "id" => $this->encodeHelper->encodeData($skill->id),
                "point" => 5
            ]
        ];

        // act
        $response = $this->postJsonResponse('api/rating/rate-job', $payload, $headers);

        // assert
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Short listed applicant successfully'
        ]);
    }

    public function enableRateOption($jobApplication)
    {
        $jobApplication->rating_status = $this->ratingStatuses['pending'];
        $jobApplication->save();

        return $jobApplication;
    }

    public function getJobSkill($jobApplication)
    {
        $workExperience = WorkExperience::where('reg_id', $jobApplication->worker_id)
                                        ->where('application_id', $jobApplication->id)
                                        ->first();

        $skill = Skill::where('work_table_id', $workExperience->id);

        return $skill;
    }
}