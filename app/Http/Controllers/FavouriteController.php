<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SpotHire\Helpers\EncodeHelper;
use App\SpotHire\Helpers\CustomHelper;
use App\Favourite;
use App\JobApplication;

class FavouriteController extends ApiController
{
	/**
   * @var App\Helpers\EncodeHelper
   **/
  private $encodeHelper;

  /**
   * @var App\Helpers\CustomHelper
   **/
  private $customHelper;

  /**
  * @var integer
  **/
  private $clientInfo;

	function __construct(EncodeHelper $encodeHelper, CustomHelper $customHelper)
	{
    $this->encodeHelper = $encodeHelper;
    $this->customHelper = $customHelper;
    $this->clientInfo = auth()->guard('api')->authenticate();
	}

	/**
	 * add job to favourite
	 *
	 * @param \Illuminate\Http\Request
	 * @return \Illuminate\Http\Response 
	 **/
	public function addJobToFavourite(Request $request)
	{
		$jobId = $this->encodeHelper->decodeData($request->input('id'));

		// return fail if job offer does not exist
		if(!$this->customHelper->isDataExist('jobs', $jobId)){
			return $this->respondValidationError('This job isn\'t available anymore!');
		}

		$application = JobApplication::where('job_id', $jobId)
																	->where('applicant_id', $this->clientInfo->id)
																	->first();
		if($application){
			return $this->respondValidationError('Applied job can\'t be favourited!');
		}

		$favourite = Favourite::where('job_id', $jobId)
							->where('user_id', $this->clientInfo->id)
							->first();

		if($favourite) {
			return $this->respondValidationError('Job favourited already!');
		}

		$favourite = new Favourite;
		$favourite->job_id = $jobId;
		$favourite->user_id = $this->clientInfo->id;

		if(!$favourite->save()){
			return $this->respondInternalError('Failed favouriting job!');
		}

		return $this->respond([
			'message' => 'Job added to favourite successfully.'
		]);
	}

	/**
	 * remove job from favourite
	 *
	 * @param \Illuminate\Http\Request
	 * @return \Illuminate\Http\Response 
	 **/
	public function removeJobFromFavourite(Request $request)
	{
		$jobId = $this->encodeHelper->decodeData($request->input('id'));

		// return fail if job offer does not exist
		if(!$this->customHelper->isDataExist('jobs', $jobId)){
			return $this->respondValidationError('This job isn\'t available anymore!');
		}

		$favourite = Favourite::where('job_id', $jobId)
							->where('user_id', $this->clientInfo->id)
							->first();

		if(!$favourite) {
			return $this->respondValidationError('Job wasn\'t added to favourite previously!');
		}


		if(!$favourite->delete()){
			return $this->respondInternalError('Failed removing job from favourite!');
		}

		return $this->respond([
			'message' => 'Job removed from favourite successfully.'
		]);
	}
}