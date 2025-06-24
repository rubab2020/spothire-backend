<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\SpotHire\Helpers\ConfigHelper;

class Job extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public static function boot()
    {
        // soft delete

        parent::boot();

        self::deleting(function($job) {
            $job->applications()->delete();
        });

    }

    /**
     * check jobs is approved
     *
     * @return query
     **/
    public function scopeIsApproved($query){
        $configHelper = new ConfigHelper;
        $reviewStatuses = $configHelper->getReviewStatuses();
        $query->where('review_status', $reviewStatuses['approved']);
    }

    /**
     * check jobs not expired
     *
     * @return query
     **/
    public function scopeNotExpired($query){
        $query->where('advert_deadline', '>=', Carbon::Now()->format('Y-m-d'));
    }

    /**
     * take jobs if not applied by worker
     *
     * @return query
     **/
    public function scopeNotApplied($query,  $clientId){
        $query->whereNotExists(function ($query) use($clientId) {
                $query->select(DB::raw(1))
                    ->from('job_applications')
                    ->where('job_applications.applicant_id', '=', $clientId)
                    ->whereRaw('job_applications.job_id = jobs.id');
            })
            ->orWhereExists(function ($query) use($clientId) { // with trashed
                $query->select(DB::raw(1))
                    ->from('job_applications')
                    ->where('job_applications.applicant_id', '=', $clientId)
                    ->whereRaw('job_applications.job_id = jobs.id')
                    ->where('job_applications.deleted_at', '!=', NULL);
            });
    }

    /**
     * filter jobs
     *
     * @return query
     **/
    public function scopeFilters($query, $filters = []){
        foreach($filters as $filterName => $filter){
            $params = $filter;
            
            // categories
            if($filterName == 'categories'){
                $query->whereIn('job_type', $params);
                continue;
            }

            // employment types
            if($filterName == 'employment_types'){
                $query->whereIn('employment_type', $params);
                continue;
            }

            // salary ranges
            if($filterName == 'salary_ranges'){
                foreach($params as $index => $param){
                    $minSalary =  $param['start'];
                    $maxSalary =  $param['end'];

                    // if negoyiable filter is given
                    if($minSalary === null && $maxSalary === null){
                        if($index < 1){
                            $query->where(function($query) use ($minSalary, $maxSalary){
                                $query->where('salary_from', $minSalary)
                                    ->where('salary_to', $maxSalary);
                            });
                        }
                        else{
                            $query->orWhere(function($query) use ($minSalary, $maxSalary){
                                $query->where('salary_from', $minSalary)
                                    ->where('salary_to', $maxSalary);
                            });
                        }
                    }

                    if($minSalary !== null && $maxSalary !== null){
                        if($index < 1){
                            $query->where(function($query) use ($minSalary, $maxSalary){
                                $query->where('salary_from', '>=', $minSalary)
                                    ->where('salary_to', '<=', $maxSalary);
                            })
                            ->orWhere(function($query) use ($minSalary, $maxSalary){
                                $query->where('salary_from', '<', $minSalary)
                                    ->where('salary_to', '>=', $minSalary)
                                    ->where('salary_to', '<=', $maxSalary);
                            })
                            ->orWhere(function($query) use ($minSalary, $maxSalary){
                                $query->where('salary_from', '>=', $minSalary)
                                    ->where('salary_from', '<=', $maxSalary);
                            })
                            ->orWhere(function($query) use ($minSalary, $maxSalary){
                                $query->where('salary_from', '<', $minSalary)
                                    ->where('salary_to', '>', $maxSalary);
                            });
                        }
                        else{
                            $query->orWhere(function($query) use ($minSalary, $maxSalary){
                                $query->where('salary_from', '>=', $minSalary)
                                    ->where('salary_to', '<=', $maxSalary);
                            })
                            ->orWhere(function($query) use ($minSalary, $maxSalary){
                                $query->where('salary_from', '<', $minSalary)
                                    ->where('salary_to', '>=', $minSalary)
                                    ->where('salary_to', '<=', $maxSalary);
                            })
                            ->orWhere(function($query) use ($minSalary, $maxSalary){
                                $query->where('salary_from', '>=', $minSalary)
                                    ->where('salary_from', '<=', $maxSalary);
                            })
                            ->orWhere(function($query) use ($minSalary, $maxSalary){
                                $query->where('salary_from', '<', $minSalary)
                                    ->where('salary_to', '>', $maxSalary);
                            });
                        }
                    }
                }

                continue;
            }

            // deadline durations
            if($filterName == 'deadline_durations'){
                if(count($params) > 0){
                    // max duration
                    $maxDuration = max($params);

                    // max advert date time to look for
                    $momentNow = Carbon::Now()->endOfDay();
                    $maxAdvertDatetime = $momentNow->addDays($maxDuration);

                    $query->where('advert_deadline', '<=', $maxAdvertDatetime);
                }
                continue;
            }

            // location names
            if($filterName == 'location_names'){
                // location in both user table and job table. 
                // so table name used as prefix for location column in order to remove ambiguous conflict
                $query->whereIn('jobs.location', $params); 
                continue;
            }

            // search only compnay name and job title. 
            if($filterName == 'search_job'){
                $search = $params;
               
                $query->where(function($query) use ($search){
                    $query->where('job_title', 'like', "%$search%")
                    ->orWhere('users.name', 'like', "%$search%");
                    
                });

                continue;
            }

            
        }
    }

    public function applications()
    {
        return $this->hasMany("App\JobApplication", "job_id")->latest();
    }

    public function appliedApplications()
    {
        $applicationStatuses = ConfigHelper::getApplicationStatusesStaic();
        return $this->hasMany("App\JobApplication", "job_id")
            ->where('application_status', $applicationStatuses['applied'])
            ->orWhere('application_status', $applicationStatuses['interviewed'])
            ->latest();
    }

    public function assignedApplications()
    {
        $applicationStatuses = ConfigHelper::getApplicationStatusesStaic();
        return $this->hasMany("App\JobApplication", "job_id")
            ->where('application_status', $applicationStatuses['assigned'])
            ->orWhere('application_status', $applicationStatuses['completed'])
            ->latest();
    }

    public function skills()
    {
        return $this->hasMany("App\JobSkill", "job_id");
    }

    public function user()
    {
        return $this->belongsTo("App\User", "user_id");
    }

    /**
     * return job owner's id
     *
     * @return integer
     **/
    public static function getJobOwnerId($jobId)
    {
        $job = self::withTrashed()->find($jobId);
        return $job ? $job->user_id : null;
    }


    /**
     * return job titles from jobs
     *
     * @return void
     **/
    public static function getjobTitles($jobs)
    {
        $jobTitles = [];
        foreach($jobs as $job){
            $jobTitles[] = $job->job_title;
        }

        $jobTitles = Job::uniqueJobTitlesWithCount($jobTitles);

        return $jobTitles;
    }

    /**
     * return unique job titles with count
     *
     * @return array
     **/
    public static function uniqueJobTitlesWithCount($items)
    {
        $results = [];
        $uniqueJobTitles = array_unique($items);
        $collection = collect($items);

        foreach($uniqueJobTitles as $jobTitle){
            $results[] = [
                'name' => $jobTitle,
                'count'=> Job::getCountJobTitles($collection, $jobTitle)
            ];
        }

        return $results;
    }


    /**
     * get count of job titles
     *
     * @return integer
     **/
    public static function getCountJobTitles($collection, $jobTitle)
    {
        $cnt = 0;
        foreach($collection as $item){
            if($item == $jobTitle){
                $cnt++;
            }
        }

        return $cnt;
    }

    /**
     * generate slug
     *
     * @return string
     **/
    public static function generateSlug($name)
    {
        $slug = str_slug($name, '-');
        $slugObj = self::where('slug' ,'like', '%'.$slug.'%')->latest()->first();
        if($slugObj){
            $pos = strrpos($slugObj->slug, '-');
            $number = substr($slugObj->slug, $pos+1);
            $number = (int) filter_var($number, FILTER_SANITIZE_NUMBER_INT);
            $slug .= '-'.($number+1);
        }

        return $slug;
    }

    /**
     * return deadline of a job
     *
     * @param integer $duration
     * @return string
     **/
    public static function advertDeadLine($duration)
    {
        $date        = date('y:m:d');
        $addDays     = strtotime("+" . ($duration) . " days", strtotime($date));
        $deadline    = date("Y-m-d", $addDays);

        return $deadline;
    }
}
