<?php

namespace App\Http\Repositories;

use App;
use App\OTP;
use Carbon\Carbon;

class OTPRepository
{
	public function __construct()
	{
	}

	public function generateOTP($phoneNumber)
	{
		$newOTP = rand(100000, 999999);
		if (App::environment('local')) {
			$newOTP = 123456;
		}

		$OTP = OTP::where('phone_number', $phoneNumber)->where('otp', $newOTP)->first();
		if(!$OTP){
			$OTP = new OTP;
			$OTP->resend_count = 0; 
		}
		else if($OTP->resend_count >= 3) // send 1st time and resend 3 times in total 
			return null;
		else 
			$OTP->resend_count = $OTP->resend_count + 1;
		$OTP->phone_number = $phoneNumber;
		$OTP->otp = $newOTP;
		$OTP->save();

		return $OTP;
	}

	public function getOTP($phoneNumber, $otp)
	{
		$OTP = OTP::where('phone_number', $phoneNumber)
			->where('otp', $otp)
			->first();

		if ($OTP != null) {
			if($OTP->updated_at < Carbon::now()->subMinutes(5))
				return false;

			$OTP->delete();
			return true;
		}

		return false;
	}
}
