<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Feedback;
use App\FeedbackImage;
use Validator;

class FeedbackController extends ApiController
{
	/**
	 * store feedback
	 *
	 * @return Illuminate\Http\Response
	 **/
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'describe' => 'required|string|max:255',
            'requestType' => 'required',
    	]);

        if($validator->fails()){
            return $this->respondValidationErrors($validator->messages());
        }

		$feedback = new Feedback;
		$feedback->first_name = $request->input('firstName');
		$feedback->last_name = $request->input('lastName');
		$feedback->email = $request->input('email');
		$feedback->describe = $request->input('describe');
		$feedback->request_type = $request->input('requestType');
		
		if($feedback->save()){
			// save screenshot
			if ($request->hasFile('screenshots')) {
	            $images = $request->file('screenshots');

	            foreach($images as $image){
		            $imgName = date('mdYHis').uniqid().'.'.$image->getClientOriginalExtension();
		            $path = public_path('images/feedbacks/');
		            $imageLink = $path.$imgName;
		            \Image::make($image->getRealPath())
		            		->resize(null, 800, function ($constraint) {
							    $constraint->aspectRatio();
							})
		            		->save($imageLink);

		   			$feedbackImg = new FeedbackImage;
		   			$feedbackImg->feedback_id = $feedback->id;
		   			$feedbackImg->image = $imgName;
		   			$feedbackImg->save();
	            }
	        }

			return $this->respondCreatingResource();
		}

		return $this->respondInternalError();
	}
}