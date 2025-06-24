<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Payment;
use App\Job;

class PaymentController extends Controller
{
    /**
     * undocumented function
     *
     * @return void
     **/
    public function getPayments()
    {
        $payments = Payment::get();
        
        $jobIds = [];
        foreach($payments as $payment){
            $jobIds[] = $payment->job_id;
        }
        $jobs = Job::whereIn('id', $jobIds)->pluck('job_title', 'id')->toArray();
        return view('admin/payments.paymentIndex', compact('payments', 'jobs'));
    }

    public function completePayment(Request $request)
    {
        $payment = Payment::find($request->input('id'));
        if($payment){
            $payment->status = 'complete';

            $job = Job::where('id', $payment->job_id)->first();
            if($job){
                if($payment->save()){
                    $job->is_payment_completed = true;
                    $job->save();
                    
                    return back()->with('success', 'Payment Complated');
                }
                else{
                    return back()->with('error', 'Failed completing payment');
                }
            }
            else{
                return back()->with('error', 'Job not found');
            }
        }
        else {
            return back()->with('error', 'Payment not found');
        }
    }
}
