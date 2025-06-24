<?php

namespace Tests\Unit;

use Faker\Factory as Faker;
use Tests\TestCase;

class ApiTester extends TestCase{
	protected $fake;

	function __construct()
	{
		// it's used by phpunit and any change to the signature etc can break things
		// if parent not called then it will throw issue(array_merge(): Argument #1 is not an array)
		parent::__construct();

		$this->fake = Faker::create();
	}

	protected function getJsonResponse($uri, $payload = [], $headers = [])
    {
    	return $this->json('GET', $uri, $payload, $headers);
    }

    protected function postJsonResponse($uri, $payload = [], $headers = [])
    {
    	return $this->json('POST', $uri, $payload, $headers);
    }

    protected function getToken($userType, $userID)
    {
        $user = $this->getUser($userType, $userID);
        
        $payload = [
            'email' => $user->email,
            'password' => 'secret'
        ];
        
        $headers = [
            'Content-Type' => 'application/json'
        ];

        $response = $this->postJsonResponse('api/login', $payload, $headers);
        $response = base64_decode(base64_decode($response->getData()->data)); // todo: remove decodeding after removing decoding methods from api service
        $response = json_decode($response);
        $token = $response->token;

        return $token;
    }

    private function getUser($userType, $userID = 1)
    {
        if($userType == 'new_user')
            return factory(\App\User::class)->create();

        // return exsiting user
        return \App\User::find($userID);
    }
}