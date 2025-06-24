<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'cors', 'middleware' => 'throttle:60,1'], function() { // Allow up to 60 requests in 1 minute
    /*Auth*/
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('forgetPassword','AuthController@forgetPassword');
    Route::post('verify', 'AuthController@verifyUser');
    Route::post('otp/resend', 'AuthController@resendOTP');
    Route::post('forgetPasswordUpdate', 'AuthController@forgetPasswordUpdate');
    // Route::post('recover', 'AuthController@recover');
    Route::post('refresh-token', 'AuthController@refreshToken');
    Route::post('save/basic-details', 'AuthController@saveBasicDetails');

    // otp
    // Route::post('/otp/send', 'OTPController@sendOTP')->name('otp.send');
    // Route::post('/otp/resend', 'OTPController@resendOTP')->name('otp.resend');
    // Route::post('/otp/verify', 'OTPController@verifyOTP')->name('otp.verify');

    Route::get('notification/device-tokens', 'AuthController@getNotificaionDeviceTokens');
    Route::get('notification/device-tokens/{uid}', 'AuthController@getReceiverNotificaionDeviceTokens');
    Route::post('notification/device-tokens/save', 'AuthController@storeNotificaionDeviceToken');

    /*social auth*/
    Route::get('auth/{provider}', 'SocialAuthController@redirectToProvider');
    Route::get('auth/{provider}/callback', 'SocialAuthController@handleProviderCallback');

    /*feedback*/
    Route::post('feedback/store', 'FeedbackController@store');

    /*public job controller*/
    Route::get('public/job/write/options', 'JobPublicController@getJobOptions');
    Route::get('public/jobs', 'JobPublicController@getJobs'); // for infinity data scroll
    Route::post('public/jobs', 'JobPublicController@getJobs');
    Route::post('public/job-details', 'JobPublicController@getJobDetails');
    /*public profile controller*/
    Route::get('public/profile/{id}', 'ProfileController@getProfileById');


    /*only loggined in user can access*/
    Route::group(['middleware' => ['api']], function () {
        /*quick access to encoed and decode data*/
        Route::get('encode-data/{id}',
            function($id, App\SpotHire\Helpers\EncodeHelper $encodeHelper){ 
                return $encodeHelper->encodeData($id);
            }
        );
        Route::get('decode-data/{hashid}',
            function($hashid, App\SpotHire\Helpers\EncodeHelper $encodeHelper){
                return $encodeHelper->decodeData($hashid);
            }
        );

        /*auth*/
        Route::post('password-reset','AuthController@passwordReset');
        Route::post('logout', 'AuthController@logout');

        /*UserController*/
        Route::get('about-yourself', 'UserController@getAboutYourself');
        Route::post('about-yourself/save', 'UserController@SaveAboutYourself');
        // Route::get('user-settings', 'UserController@userProfileData');
        Route::post('user/update/email_phone_privacy', 'UserController@updateEmailPhonePrivacy');

        /*QualificationController*/
        Route::get('qualifications', 'QualificationController@getQualifications');
        Route::post('qualifications/save', 'QualificationController@saveQualifications');
        Route::POST('qualification/delete', 'QualificationController@deleteQualification');

        /*ExperienceController*/
        Route::get('experiences', 'ExperienceController@aboutExperience');
        Route::POST('experiences/save', 'ExperienceController@SaveAboutExperience');
        Route::POST('experience/delete', 'ExperienceController@deleteExperience');

        /*AwardController*/
        Route::get('awards', 'AwardController@getAwards');
        Route::POST('awards/save', 'AwardController@saveAward');
        Route::POST('award/delete', 'AwardController@deleteAward');

        /*CompanyBackgroundController*/
        Route::get('company-background', 'CompanyBackgroundController@getCompanyBackground');
        Route::POST('company-background/save', 'CompanyBackgroundController@saveCompanyBackground');
        Route::get('company-value', 'CompanyBackgroundController@getCompanyValue');
        Route::POST('company-value/save', 'CompanyBackgroundController@saveCompanyValue');
        // Route::get('company-profile', 'CompanyBackgroundController@companyProfileData');
        // Route::get('company-profile/{id}', 'CompanyBackgroundController@getCompanyProfile');
        // needs to be deprecated while working in profile page
        // Route::POST('about-yourself/update', 'CompanyBackgroundController@updateAboutUs');
        // Route::POST('company-background/update', 'CompanyBackgroundController@updateCompanyBackGround');

        /*SkillController*/
        Route::get('skills', 'SkillController@getSkills');
        Route::POST('skills/save', 'SkillController@saveSkills');

        /*ProfileController*/
        // Route::get('individualProfile', 'ProfileController@individualProfile');
        Route::get('profile/{id}', 'ProfileController@getProfileById');
        Route::get('profile', 'ProfileController@getProfile');
        Route::post('profile/work-and-hire/get-status-of-applications', 'ProfileController@getStatusOfWorkAndHireApplications');

        /*WriteOfferController*/
        Route::get('job/write/options', 'WriteOfferController@getJobOptions');
        Route::POST('job/save', 'WriteOfferController@saveJob');
        Route::get('job/edit/{job_id}', 'WriteOfferController@editJob');
        Route::post('job/delete', 'WriteOfferController@deleteJob');

        /*job circular controller*/
        Route::get('jobs', 'JobController@getJobs'); // for infinity data scroll
        Route::post('jobs', 'JobController@getJobs');
        Route::post('job-details', 'JobController@getJobDetails');
        Route::get('jobs/get-counts-on-filters', 'JobController@getCountsOnFilters');

        /*work dashbaord controller*/
        // Route::post('job/delete', 'WorkDashboardController@deleteJob');
        Route::get('work/jobs/applied', 'WorkDashboardController@getAppliedJobs');
        Route::get('work/jobs/assigned', 'WorkDashboardController@getAssignedJobs');
        Route::post('work/job/apply', 'WorkDashboardController@applyJob');
        Route::post('work/job/withdraw', 'WorkDashboardController@withdrawJob');
        Route::post('work/job/discontinue', 'WorkDashboardController@discontinueJob');

        /* favourite controller*/
        Route::post('favourite/job/add', 'FavouriteController@addJobToFavourite');
        Route::post('favourite/job/remove', 'FavouriteController@removeJobFromFavourite');

        /*Rating Controller*/ 
        Route::post('rating/request', 'RatingController@request');
        Route::post('rating/rate', 'RatingController@rate');
        // Route::post('rating/withdraw', 'RatingController@withdraw');

        /*hire dashboard controller*/
        Route::post('hire/jobs/applied', 'HireDashboardController@getAppliedJobs');
        Route::get('hire/jobs/{job_id}/applied-applicants', 'HireDashboardController@getAppliedApplicants');
        // Route::post('hire/jobs/applied/new-applicants', 'HireDashboardController@getAppliedJobsNewApplicants');
        Route::post('hire/jobs/assigned', 'HireDashboardController@getAssignedJobs');
        // Route::post('hire/jobs/applied/profiles', 'HireDashboardController@getAppliedProfiles');
        // Route::post('hire/jobs/assigned/profiles', 'HireDashboardController@getAssignedProfiles');
        // Route::post('worker-profile', 'HireDashboardController@getWorkerProfileData');
        Route::post('short-list-applicant', 'HireDashboardController@shortListApplicant');
        Route::post('interview-applicant', 'HireDashboardController@interviewApplicant');
        Route::post('assign-applicant', 'HireDashboardController@assignApplicant');
        Route::post('decline-applicant', 'HireDashboardController@declineApplicant');
        Route::post('hire/job/discontinue', 'HireDashboardController@discontinueJob');
        Route::post('hire/job/complete', 'HireDashboardController@completeJob');

        /*settings*/
        Route::get('settings/individual','SettingsController@getIndividualSettings');
        Route::post('settings/individual/update','SettingsController@updateIndividualSettings');
        Route::get('settings/company','SettingsController@getCompanySettings');
        Route::post('settings/company/update','SettingsController@updateCompanySettings');

        /*chat*/
        // Route::get('messages/{applicaion_id}', 'ChatController@fetchMessages');
        // Route::post('messages', 'ChatController@sendMessage');
        // Route::post('messages/save-seen', 'ChatController@saveMessagesSeen');
        Route::post('chat/threads/senders-info', 'ChatController@getThreadsInfo');
        Route::post('chat/thread/sender-info', 'ChatController@getThreadInfo');

        /*payment*/
        Route::post('payment/complete', 'PaymentController@complete');
    });
});