<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{
    protected $table = 'job_skills';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'job_id'
    ];

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public static function saveSkills($skills, $jobId)
    {
    	foreach ($skills as $skill) {
    		Self::create(['name'=>$skill, 'job_id'=> $jobId]);
    	}
    }
}
