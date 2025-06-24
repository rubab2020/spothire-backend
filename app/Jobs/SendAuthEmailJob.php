<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendAuthEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailTemplate = $this->data['emailTemplate'];
        $user = $this->data['user'];
        $verificationCode = $this->data['verificationCode'];
        $siteUrl = $this->data['siteUrl'];
        $subject = $this->data['subject'];

        Mail::send($emailTemplate, [
            'name' => $user->name,
            'email' => $user->email,
            'verification_code' => $verificationCode,
            'site_url'=>$siteUrl
        ],
        function($mail) use ($user, $subject){
            $mail->from('info@yayerk.com', "Yaywerk");
            $mail->to($user->email, $user->name);
            $mail->subject($subject);
        });

        if (Mail::failures()) return $this->respondInternalError("Failed sending mail");
    }
}