<?php

namespace App\Services;

// use Log;

/**
 * send SMS
 */
class SmsService
{
	public function sendSMS($mobileNo, $message)
	{
		$url = "https://smsplus.sslwireless.com/api/v3/send-sms";

		$param =  "sid="
							. "YAYWERKNONAPI"
							. "&api_token="
							. "Yaywerk-c6a19700-2faa-4215-ae37-dd4bd390e461"
							. "&msisdn=" 
							. $mobileNo 
							. "&sms=" 
							. urlencode($message) 
							. "&csms_id=" 
							. uniqid();

		$crl = curl_init();
		curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($crl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($crl, CURLOPT_URL, $url);
		curl_setopt($crl, CURLOPT_HEADER, 0);
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($crl, CURLOPT_POST, 1);
		curl_setopt($crl, CURLOPT_POSTFIELDS, $param);
		$response = curl_exec($crl);
		curl_close($crl);

		// Log::info("SMS response: " . $response);

		return true;

		// return $response;
	}
}
