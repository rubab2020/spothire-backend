<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
Use App\Payment;
Use App\JobApplication;
use App\Job;
use App\SpotHire\Helpers\EncodeHelper;

class PaymentController extends ApiController
{
    /**
     * @var obj
     **/
    private $clientInfo;

    /**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    function __construct(EncodeHelper $encodeHelper)
    {
        $this->clientInfo = auth()->guard('api')->authenticate();
        $this->encodeHelper = $encodeHelper;
    }

    /**
     * untitled
     *
     * @return Illuminate/Http/Response
     **/
    public function complete(Request $request)
    {
        $paymentType = $request->input('payment_type');

        if($paymentType == 'hand'){
            $jobId = $this->encodeHelper->decodeData($request->input('job_id'));
            $job = Job::find($jobId);
            if(!$job) return $this->respondValidationError('Job  not found');

            $payment = Payment::where('job_id', $jobId)->first();
            if(!$payment){
                $payment = new Payment;
            }

            $payment->job_id = $jobId;
            $payment->payment_type = $paymentType;
            $payment->status = 'pending';
            $payment->save();

            return $this->respondCreatingResource('Cash in hand payment requested successfully!');
        }

        return $this->respondValidationError('Not a valid request');
    }
}
