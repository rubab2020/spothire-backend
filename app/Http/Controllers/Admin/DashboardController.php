<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Job;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * undocumented function
     *
     * @return void
     **/
    public function index()
    {
        // today
        $totalJobsToday = Job::whereDate('created_at', Carbon::today())->count();
        $approvedJobsToday = Job::whereDate('created_at', Carbon::today())->where('review_status', 'a')->count();
        $pendingJobsToday = Job::whereDate('created_at', Carbon::today())->where('review_status', 'p')->count();
        $rejectedJobsToday = Job::whereDate('created_at', Carbon::today())->where('review_status', 'r')->count();

        $totalUsersToday = User::whereDate('created_at', Carbon::today())->count();
        $verifiedUsersToday = User::whereDate('created_at', Carbon::today())->where('valid', true)->count();
        $unverifiedUsersToday = User::whereDate('created_at', Carbon::today())->where('valid', false)->count();


        // all
        $totalJobs = Job::count();
        $approvedJobs = Job::where('review_status', 'a')->count();
        $pendingJobs = Job::where('review_status', 'p')->count();
        $rejectedJobs = Job::where('review_status', 'r')->count();

        $totalUsers = User::count();
        $verifiedUsers = User::where('valid', true)->count();
        $unverifiedUsers = User::where('valid', false)->count();


        return view('admin.dashboard', compact(
            'totalJobsToday',
            'approvedJobsToday', 
            'pendingJobsToday', 
            'rejectedJobsToday', 
            'totalUsersToday',
            'verifiedUsersToday',
            'unverifiedUsersToday',
            'totalJobs',
            'approvedJobs', 
            'pendingJobs', 
            'rejectedJobs', 
            'totalUsers',
            'verifiedUsers',
            'unverifiedUsers'
        ));
    }  
}
