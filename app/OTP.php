<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Repositories\OTPRepository;
use App\Jobs\SendSMS;
use App;

class OTP extends Model
{
      protected $guarded = ['id'];

      private $OTP;

	  public function __construct()
	  {
	  		$OTP = new OTPRepository; 
	        $this->OTP = $OTP;
	  }

	  public function sendOTP($phoneNumber)
	  {
	        $OTP = $this->OTP->generateOTP($phoneNumber);
	        if(!$OTP)
	              return false;

	        if (!App::environment('local')) {
	              SendSMS::dispatch($OTP->phone_number, 'Your OTP for Yaywerk is '. $OTP->otp);
	        }

	        return true;
	  }

	  public function resendOTP($phoneNumber)
	  {
	        $OTP = $this->OTP->generateOTP($phoneNumber);

	        if(!$OTP)
	              return false;

	        if (!App::environment('local')) {
	              SendSMS::dispatch($OTP->phone_number, 'Your OTP for Yaywerk is '. $OTP->otp);
	        }

	        return true;
	  }

	  public function verifyOTP($phoneNumber, $otp)
	  {
	        if ($this->OTP->getOTP($phoneNumber, $otp)) {
	              return true;
	        }
	        
	        return false;
	  }
}
