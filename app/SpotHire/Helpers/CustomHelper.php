<?php

namespace App\SpotHire\Helpers;

use App\SplashTrack;
// use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Jobs\SendNotificationEmailJob;
use Illuminate\Support\Facades\Mail;

class CustomHelper
{
	/**
	 * return column's unique data with count
	 *
	 * @param array $items
	 * @param string $columnName
	 * @param array $optionOrder
	 * @return array of objects
	 **/
	public function countColumnUniqueData($items, $columnName, $optionsOrder)
	{
		$grouped = $items->groupBy($columnName);
		$results = $grouped->map(function ($item, $key) {
			return ['name'=>$key, 'count'=>collect($item)->count()];
		});

		// get only values
		$results = $results->values();

		foreach($optionsOrder as $index => $option){
			$countFound = false;
			foreach($results as $result){
				if($result['name'] == $option['name']){
					$optionsOrder[$index]['count'] = $result['count'];
					$countFound = true;
				}
			}
			if(!$countFound){
				unset($optionsOrder[$index]);
			}
		}

		$optionsOrder = array_values($optionsOrder); // re-indexing
		
		return $optionsOrder;
	}

	/**
	 * return salary range filter options with coutn of each job's salary range
	 *
	 * @param array $jobs
	 * @param array $filterOptions
	 * @return array
	 **/
	public function countJobForEachSalaryFilter($jobs, $filterOptions)
	{
		foreach($filterOptions as $filterIndex => $filterOption){
			$salaryMin = $filterOption['range']['start'];
			$salaryMax = $filterOption['range']['end'];
			$count = 0;

			foreach($jobs as $job){
				if($job->salary_from === null && $job->salary_to === null && $salaryMin === null && $salaryMax === null){
					$count += 1;
					continue;
				}

				if($job->salary_from === null && $job->salary_to === null)
					continue;

				if( ($job->salary_from >= $salaryMin && $job->salary_to <= $salaryMax) 
					|| ($job->salary_from < $salaryMin && $job->salary_to >= $salaryMin && $job->salary_to <= $salaryMax)
					|| ($job->salary_from >= $salaryMin && $job->salary_from <= $salaryMax)
					|| ($job->salary_from < $salaryMin && $job->salary_to > $salaryMax)
				){
					$count += 1;
				}
			}
			
			$filterOptions[$filterIndex]['count'] = $count;

			if($count == 0){
				unset($filterOptions[$filterIndex]);
			}
		}

		$filterOptions = array_values($filterOptions); // re index

		return $filterOptions;
	}

	/**
	 * return dead line duration filter options with count of each job's expiary duration
	 *
	 *
	 * @param array $jobs
	 * @param array $filterOptions
	 * @return array
	 **/
	public function countJobforEachDeadLineFilter($jobs, $filterOptions)
	{
		foreach($filterOptions as $filterIndex => $filterOption){
			// advert date time to look for
			$momentNow = Carbon::Now();
			$advertDatetime = $momentNow->addDays($filterOption['days']);

			// count jobs for specific expirary duration
			$count = 0;
			foreach($jobs as $job){
				if($job->advert_due_datetime <= $advertDatetime){
					$count += 1;
				}
			}

			$filterOptions[$filterIndex]['count'] = $count;

			if($count == 0){
				unset($filterOptions[$filterIndex]);
			}
		}

		$filterOptions = array_values($filterOptions); // re index

		return $filterOptions;
	}

	/**
	 * add proeprty count with data null
	 *
	 * @param array $items
	 * @return array
	 **/
	public function addPropertyCountWithDataNull($items)
	{
		foreach($items as $key => $item){
			$items[$key]['count'] = null; 
		}
		return $items;
	}

	/**
	 * check wheteer data exist against an ID
	 *
	 * @return boolean
	 **/
	public function isDataExist($tableName, $id)
	{
		$obj = \DB::table($tableName)->find($id);

		return $obj ? true : false;
	}

	/**
	 * get splash status
	 * @param string $sectionName
	 * @param string $featureName
	 *
	 * @return boolean
	 **/
	public function getSplashStatus($sectionName, $featureName)
	{
		$clientInfo = auth()->guard('api')->authenticate();

		$splash = SplashTrack::where('user_id', $clientInfo->id)
					->where('section', $sectionName)
					->where('feature_name', $featureName)
					->select('is_visible')
					->first();
		if($splash == null){
			$splash = new SplashTrack;
			$splash->user_id = $clientInfo->id;
			$splash->section = $sectionName; 
		   $splash->feature_name = $featureName;
			$splash->is_visible = false;
			$splash->save(); 
			
			return true;
		}

		return $splash->is_visible ? true : false;
	}

	/**
	 * send mail to recepient
	 *
	 * @return boolean
	 **/
	public function sendMail(
		$receipientEmail, 
		$receipientName,
		$subject, 
		$msg, 
		$senderEmail, 
		$senderName, 
		$alertType
	){
		$siteUrl = ConfigHelper::getSiteUrl();

		$data = [];
		$data['receipientEmail'] = $receipientEmail;
		$data['receipientName'] = $receipientName;
		$data['subject'] = $subject;
		$data['msg'] = $msg;
		$data['senderEmail'] = $senderEmail;
		$data['senderName'] = $senderName;
		$data['alertType'] = $alertType;
		$data['siteUrl'] = $siteUrl;

		$queueJob = (new SendNotificationEmailJob($data))
						->delay(Carbon::now()->addSeconds(5));
		dispatch($queueJob);
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function sendPushNotification($subject, $msg, $deviceTokens, $alertType): string
	{
		$serverKey = env("FIREBASE_SERVER_KEY");
		$data = [
			// "to" => $user->device_token,
			"registration_ids" => $deviceTokens,
			"notification" =>
				[
					"title" => $subject,
					"body" => $msg,
					"icon" => 'assets/images/favicon.jpg'
				],
		];
		$dataString = json_encode($data);
  
		$headers = [
			'Authorization: key=' . $serverKey,
			'Content-Type: application/json',
		];
  
		$ch = curl_init();
  
		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
  
		curl_exec($ch);

		return 'Notification sent!';
	}

	public static function getUrlParams($queryString)
	{
		$query  = explode('&', $queryString);
		$params = array();

		foreach($query as $param)
		{
			list($name, $value) = explode('=', $param);
			// if($name != 'page'){
				$params[urldecode($name)][] = urldecode($value);
			// }
		}

		return $params;
	}

	// Function for basic field validation (present and neither empty nor only white space
	public static function IsNullOrEmptyString($str){
		return (!isset($str) || trim($str) === '');
	}

	public static function removeListedProperties($items, $excepts){
		foreach($excepts as $except){
			foreach($items as $key => $item){
				if($except == $key){
					unset($items[$key]);
					break;
				}
			}
		}

		return $items;
	}
}