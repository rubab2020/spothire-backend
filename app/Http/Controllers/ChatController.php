<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Transformers\MessageTransformer;
use App\Message;
use App\User;
use App\Events\MessageSent;
use App\JobApplication;
use App\WriteOffer;

class ChatController extends ApiController
{
	/**
     * @var obj
     **/
    private $clientInfo;

    /**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    /**
    * @var App\SpotHire\Transformers\MessageTransformer
    **/
    protected $messageTransformer;

    function __construct(EncodeHelper $encodeHelper, MessageTransformer $messageTransformer, CustomHelper $customHelper)
	{
        $this->encodeHelper = $encodeHelper;
        $this->customHelper = $customHelper;
        $this->clientInfo = auth()->guard('api')->authenticate();
        $this->messageTransformer = $messageTransformer;
	}

	public function getThreadsInfo(Request $request) {
		$requests = $request->all();
		$data = [];
		foreach($requests as $request){
			$applicationId = $this->encodeHelper->decodeData($request['application_id']);
			$senderId = $this->encodeHelper->decodeData($request['sender_id']);

			$user = User::find($senderId);
			if($user){
				$application = JobApplication::with('jobDetails')->find($applicationId);
				
				if($application){
					$encodedId = $request['application_id'];
					$data[$encodedId] = [
						'id' => $this->encodeHelper->encodeData($user->id),
						'name'=> $user->name,
						'profile_pic' => ($user->picture != '' && $user->picture != null) 
		                          ? User::getPhotoPath($user->id).$user->picture 
		                          : null,
						'job_title'=>$application->jobDetails->job_title,
						'application_status'=>$application->application_status,
						'phone' => $user->phone ? base64_encode($user->phone) : null,
						'application_id'=> $encodedId
					];
				}
			}
		}
		return $this->respond([
			'data' => $data
		]);
	}

	public function getThreadInfo(Request $request) {
		$request = $request->all();
		$applicationId = $this->encodeHelper->decodeData($request['application_id']);
		$senderId = $this->encodeHelper->decodeData($request['sender_id']);

		$user = User::find($senderId);
		if(!$user)
			return $this->respondValidationError('Sender not found for given id');

		$application = JobApplication::with('jobDetails')->find($applicationId);
		if(!$application)
			return $this->respondValidationError('Application not found for given id');
		
		$encodedId = $request['application_id'];
		$data = [
			'id' => $this->encodeHelper->encodeData($user->id),
			'name'=> $user->name,
			'profile_pic' => ($user->picture != '' && $user->picture != null) 
                        ? User::getPhotoPath($user->id).$user->picture 
                        : null,
			'job_title'=>$application->jobDetails->job_title,
			'application_status'=>$application->application_status,
			'phone' => $user->phone ? base64_encode($user->phone) : null,
			'application_id'=> $encodedId
		];
		return $this->respond([
			'data' => $data
		]);
	}
}