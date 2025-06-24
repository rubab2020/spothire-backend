<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function scopeNotExpired($query){
        $query->where('expiary_date_if_not_assigned', '>=', Carbon::Now()->endOfDay());
    }

    public function jobDetails() {
	    return $this->belongsTo('App\Job', 'job_id')->withTrashed();
	}

	/**
	 * return company detials of job applications
	 *
	 * @param array $applications
	 * @return array
	 **/
	public static function companyDetailsOfApplications($applications){
		foreach($applications as  $applicaionKey => $application){
			$companyId = $application->jobDetails->user_id;
			$applications[$applicaionKey]['company_details'] = User::find($companyId);
		}

		return $applications;
	}

}
