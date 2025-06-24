<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Helpers\CustomHelper;
use App\Job;
use App\User;

class JobController extends Controller
{
    private $userTypes;
    private $nameReviewStatuses;
    private $reviewStatuses;
    /**
    * @var App\Helpers\CustomHelper
    **/
    private $customHelper;

    public function __construct(
        ConfigHelper $configHelper, 
        CustomHelper $customHelper
    ){
        $this->customHelper = $customHelper;
        $this->userTypes = $configHelper->getUserTypes();
        $this->nameReviewStatuses = $configHelper->getNameOfReviewStatuses();
        $this->reviewStatuses = $configHelper->getReviewStatuses();
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function getJobs()
    {
        $userTypes = $this->userTypes;
        $reviewStatuses = $this->nameReviewStatuses;
        $jobs = Job::with('user')->withTrashed()->get();
        return view('admin.jobs.jobIndex', 
                    compact('jobs', 'userTypes', 'reviewStatuses'));
    }

    public function changeReviewStatus(Request $request)
    {
        if($request->ajax()) {
            $job = Job::find($request->input('job_id'));
            if(!$job){
                return response()->json([
                    'status'=>'error', 
                    'message'=>'Job not found',
                ]);
            }
            $job->review_status = $request->input('review_status');
            $job->advert_deadline   = Job::advertDeadLine($job->advert_duration);

            if($job->save()){
                $this->notifyEmployerAboutReviewStatus($job);

                return response()->json([
                    'status'=>'success', 
                    'message'=>'Updated Status successfully',
                ]);
            }
            else{
                return response()->json([
                    'status'=>'error', 
                    'message'=>'Failed updating status',
                ]);
            }
        }
        else{
            return "Bad request. Please try again";
        }
    }

    /**
     * notify employer about review status
     *
     * @param object $job
     * @return void
     **/
    public function notifyEmployerAboutReviewStatus($job)
    {

        if($job){
            $jobOwner = User::find($job->user_id);
            if($jobOwner){
                $subject = '';
                $msg = '';
                if($job->review_status == $this->reviewStatuses['pending']){
                    $subject = 'Job approval pending';
                    $msg = 'Your job post for '
                            .$job->job_title
                            .' position is now pending for approval. Need help? Contact Support.';
                }
                else if($job->review_status == $this->reviewStatuses['approved']){
                    $subject = 'Job approved';
                    $msg = 'Your job post for '
                            .$job->job_title
                            .' position has been approved. Need help? Contact Support.';
                }
                else if($job->review_status == $this->reviewStatuses['rejected']){
                    $subject = 'Job rejected';
                    $msg = 'Your job post for '
                            .$job->job_title
                            .' position has been rejected. Need help? Contact Support.';
                }

                $this->customHelper->sendMail(
                    $jobOwner->email, 
                    $jobOwner->name,
                    $subject,
                    $msg,
                    null,
                    null,
                    'review_status'
                );
            }
        }
    }
}
