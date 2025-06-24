<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\OTPRepository;
use App\Jobs\SendSMS;

class OTPController extends Controller
{
      private $OTP;

      public function __construct(OTPRepository $OTP)
      {
            $this->OTP = $OTP;
      }

      public function sendOTP(Request $request)
      {
            $OTP = $this->OTP->generateOTP($request->phone);
            if (!App::environment('local')) {
                  SendSMS::dispatch($OTP->phone_number, __('otp_message', ['otp' => $OTP->otp]));
            }
            return response()->json([
                  "status" => true,
                  "status_code" => 200,
                  "data" => [],
                  "message" => __("OTP has been sent.")
            ], 200);
      }

      public function resendOTP(Request $request)
      {
            $OTP = $this->OTP->generateOTP($request->phone);

            if (!App::environment('local')) {
                  SendSMS::dispatch($OTP->phone_number, __('otp_message', ['otp' => $OTP->otp]));
            }
            return response()->json([
                  "status" => true,
                  "status_code" => 200,
                  "data" => [],
                  "message" => __("OTP has been sent.")
            ], 200);
      }

      public function verifyOTP(Request $request)
      {
            if ($this->OTP->getOTP($request->phone, $request->otp)) {
                  return response()->json([
                        "status" => true,
                        "status_code" => 200,
                        "data" => [],
                        "message" => __("OTP has been verified successfully.")
                  ], 200);
            }
            return response()->json([
                  "status" => false,
                  "status_code" => 401,
                  "data" => [],
                  "message" => __("OTP didn't match. Request for another OTP.")
            ], 200);
      }
}
