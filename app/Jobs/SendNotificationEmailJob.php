<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendNotificationEmailJob implements ShouldQueue
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
        $receipientEmail = $this->data['receipientEmail'];
        $receipientName = $this->data['receipientName'];
        $subject = $this->data['subject'];
        $msg = $this->data['msg'];
        $senderEmail = $this->data['senderEmail'];
        $senderName = $this->data['senderName'];
        $alertType = $this->data['alertType'];
        $siteUrl = $this->data['siteUrl'];

        Mail::send('email.notificationAlert', [
                'receipientEmail' => $receipientEmail,
                'receipientName' => $receipientName,
                'subject' => $subject,
                'msg' => $msg,
                'senderEmail' => $senderEmail,
                'senderName' => $senderName,
                'alertType'=> $alertType,
                'siteUrl'=> $siteUrl
            ],
            function($mail) use ($receipientEmail, $receipientName, $subject) {
                $mail->from('info@yaywerk.com', "Yaywerk");
                $mail->to($receipientEmail, $receipientName);
                $mail->subject($subject);
            }
        );
    }
}