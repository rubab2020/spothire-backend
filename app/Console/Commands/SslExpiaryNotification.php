<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SpotHire\Helpers\CustomHelper;

class SslExpiaryNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sslExpiary:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email for ssl certificate expiary alert';

    /**
     * @var App\Helpers\CustomHelper
     **/
    private $customHelper;

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
        $createDate = date('2019-03-10');
        $expiaryDate = date('Y-m-d', strtotime($createDate. ' + 90 days')); 
        $notifyFromDate = date('Y-m-d', strtotime($createDate. ' + 84 days')); 
        $today = date('Y-m-d');


        if($today >= $notifyFromDate && $today <= $expiaryDate){ // notify for 7 days
            // send email
            $resp = $this->customHelper->sendEmail(
                    'rubab2020@gmail.com',
                    'Rubab', 
                    'SSL certificate will expire soon!', 
                    'SSL certifacte will get expired on '.$expiaryDate.'. Please update both frontend and backend\'s SSL certificate to enable HTTPS again.', 
                    null, 
                    null,
                    null
                );
            $resp = $this->customHelper->sendEmail(
                    'musawirahmed07@gmail.com',
                    'Rubab', 
                    'SSL certificate will expire soon!', 
                    'SSL certifacte will get expired on '.$expiaryDate.'. Please update both frontend and backend\'s SSL certificate to enable HTTPS again.', 
                    null, 
                    null,
                    null
                );
        }
    }
}
