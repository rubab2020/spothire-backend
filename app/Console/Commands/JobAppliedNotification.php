<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SpotHire\Helpers\CustomHelper;
use Carbon\Carbon;
use App\JobApplication;
use App\WriteOffer;
use App\User;

class JobAppliedNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobApplied:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify Hires about all the applied candidates for respective job they posted in a day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CustomHelper $customHelper)
    {
        parent::__construct();

        $this->customHelper = $customHelper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $jobs = JobApplication::where('application_status', 'apply')
                                ->where('created_at', '>=', Carbon::yesterday()->startOfDay())
                                ->where('created_at', '<=', Carbon::yesterday()->endOfDay())
                                ->get()
                                ->groupBy('job_id');

        foreach($jobs as $jobId => $applications){
            $job = WriteOffer::find($jobId);
            if($job){
               $hire = User::find($job->reg_id);

               if($hire){
                    $applicants = [];
                    foreach($applications as $application){
                        // get applicant details
                        $applicant = User::find($application->applicant_id);
                        if($applicant){
                            $applicants[] = $applicant;
                        }
                    }

                    if(!empty($applicants)){
                        $applicantsName = '';
                        $cnt = 1;
                        foreach($applicants as $applicant){
                            $applicantsName .= $cnt++.'. '.$applicant->name."\r\n";
                        }

                        $resp = $this->customHelper->sendEmail(
                            $hire->email,
                            $hire->name, 
                            'List of new applied candidates on your Job "'.$job->looking_for.'"',
                            count($applicants).' applicant(s) applied Yesterday on your job "'.$job->looking_for.'". Here is the list of candidates,'."\r\n"
                            .$applicantsName, 
                            null, 
                            null,
                            null,
                            null
                        );
                    }
               }

            }
        }
    }
}
