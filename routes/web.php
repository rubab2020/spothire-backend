<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// use AWS;
// Route::get('/', function() {
// 	$sms = AWS::createClient('sns');
    
//         $sms->publish([
//                 'Message' => 'Hello, this is a test msg',
//                 'PhoneNumber' => '+8801676104165',    
//                 'MessageAttributes' => [
//                     'AWS.SNS.SMS.SMSType'  => [
//                         'DataType'    => 'String',
//                         'StringValue' => 'Transactional',
//                      ]
//                  ],
//               ]);
// });


// faker data create
// Route::get('faker-data-create-user-job-apply', function(App\SpotHire\Helpers\ConfigHelper $configHelper){
// 	$hire = factory(\App\User::class)->create();
// 	echo "hire user successfully created"."<br>";
	
// 	// create thousand jobs
// 	for($i = 1; $i <= 1000; $i++){
//     	$job = factory(\App\WriteOffer::class)->create(['reg_id'=>$hire->id]);
// 	}

// 	echo "jobs successfully created"."<br>";

// 	// apply the last job by thousand workers
// 	for($k = 1; $k <= 1000; $k++){
// 	    $worker = factory(\App\User::class)->create();

// 		echo "worker {{ $k }} successfully created"."<br>";

//     // add infos to worker
//     for($j = 1; $j <= 10; $j++){
// 	    factory(\App\Project::class)->create(['reg_id'=>$worker->id]);
// 	    factory(\App\workExperience::class)->create(['reg_id'=>$worker->id]);
// 	    factory(\App\Skill::class)->create(['reg_id'=>$worker->id]);
// 	    factory(\App\Qualification::class)->create(['reg_id'=>$worker->id]);
// 	    factory(\App\Qualification::class)->create(['reg_id'=>$worker->id]);
// 	    factory(\App\Awards::class)->create(['reg_id'=>$worker->id]);
//     }

// 		echo "worker details successfully created"."<br>";

//     // apply job
//     $this->applicationStatuses = $configHelper->getApplicationStatuses();
//     $jobApplication = factory(\App\JobApplication::class)->create([
//         'job_id'=>$job->id, 
//         'applicant_id'=>$worker->id, 
//         'application_status'=>$this->applicationStatuses['apply']
//     ]);

// 		echo "worker applied to job successfully created"."<br>";
// 	}

// 	echo "successfully completed"."<br>";
// });

// Route::get('faker-data-create-user-job-apply', function(App\SpotHire\Helpers\ConfigHelper $configHelper){
// 	$hire = factory(\App\User::class)->create();
// 	echo "hire user successfully created"."<br>";
	
// 	// create thousand jobs
// 	for($i = 1; $i <= 1000; $i++){
//     	$job = factory(\App\WriteOffer::class)->create(['reg_id'=>$hire->id]);
// 	}

// 	echo "jobs successfully created"."<br>";

// 	// apply the last job by thousand workers
// 	for($k = 1; $k <= 1000; $k++){
// 	    $worker = factory(\App\User::class)->create();

// 		echo "worker {{ $k }} successfully created"."<br>";


// 	    // add infos to worker
// 	    for($j = 1; $j <= 10; $j++){
// 		    factory(\App\Project::class)->create(['reg_id'=>$worker->id]);
// 		    factory(\App\workExperience::class)->create(['reg_id'=>$worker->id]);
// 		    factory(\App\Skill::class)->create(['reg_id'=>$worker->id]);
// 		    factory(\App\Qualification::class)->create(['reg_id'=>$worker->id]);
// 		    factory(\App\Qualification::class)->create(['reg_id'=>$worker->id]);
// 		    factory(\App\Awards::class)->create(['reg_id'=>$worker->id]);
// 	    }

// 		echo "worker details successfully created"."<br>";


// 	    // apply job
// 	    $this->applicationStatuses = $configHelper->getApplicationStatuses();
// 	    $jobApplication = factory(\App\JobApplication::class)->create([
// 	        'job_id'=>$job->id, 
// 	        'applicant_id'=>$worker->id, 
// 	        'application_status'=>$this->applicationStatuses['apply']
// 	    ]);

// 		echo "worker applied to job successfully created"."<br>";
// 	}

// 	echo "successfully completed"."<br>";
// });


// Route::get('faker-data-create-user-job', function(App\SpotHire\Helpers\ConfigHelper $configHelper){
// 	// create thousand jobs
// 	for($i = 1; $i <= 1000; $i++){
// 		$hire = factory(\App\User::class)->create();
//     	$job = factory(\App\WriteOffer::class)->create(['reg_id'=>$hire->id]);
// 	}
// 	echo "jobs successfully created"."<br>";
// });


// social auth
// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('user/resendVerify/{verification_code}', 'AuthController@resendVerifyUser');
// Route::get('user/resendForgetPasswordVerify/{verification_code}', 'AuthController@resendForgetPasswordVerify');

// dashboard
// Route::get('admin/users', 'Admin\AdminUserController@users');


// mail view
// Route::get('mailtest', function(){
//     $siteUrl = App\SpotHire\Helpers\ConfigHelper::getSiteUrl();
// 	return view('email.verify', ['name' => 'name', 'verification_code' => 'verification_code', 'site_url'=>$siteUrl]);
// });

Route::group(['prefix' => 'admin'], function() {
	Auth::routes();
});

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function() {
	Route::get('/dashboard', 'Admin\DashboardController@index');

	Route::get('/users', 'Admin\UserController@index');

	// jobs
	Route::get('/jobs', 'Admin\JobController@getJobs');
	Route::post('/job/change-review-status', 'Admin\JobController@changeReviewStatus');

	// payment
	Route::get('/payments', 'Admin\PaymentController@getPayments');
	Route::post('/payment/complete', 'Admin\PaymentController@completePayment');
});

