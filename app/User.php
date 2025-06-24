<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Helpers\EncodeHelper;
use App\Job;
use App\JobApplication;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','location','phone','dob','incorporation_date','industry','user_type','gender','firebase_id', 'picture', 'valid', 'provider', 'provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','created_at','updated_at'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        $configHelper = new ConfigHelper();
        $nameOfUserTypes = $configHelper->getNameOfUserTypes();

        return [
            "name" => $this->name,
            'phone' => $this->phone,
            'user_type' => $nameOfUserTypes[$this->user_type]
        ];
    }

    public function isCompany()
    {
        $configHelper = new ConfigHelper();
        $userTypes = $configHelper->getUserTypes();

        return Auth::user()->user_type == $this->userTypes['company'];
    }
    public function isIndividual()
    {
        $configHelper = new ConfigHelper();
        $userTypes = $configHelper->getUserTypes();

        return Auth::user()->user_type == $this->userTypes['individual'];
    }

    public function experiences()
    {
        return $this->hasMany('App\Experience', 'user_id', 'id')->orderBy('period_from', 'desc');
    }
    public function qualifications()
    {
        return $this->hasMany('App\Qualification', 'user_id', 'id')->orderBy('completing_date', 'desc');
    }
    public function awards()
    {
        return $this->hasMany('App\Award', 'user_id', 'id')->orderBy('date', 'desc');
    }
    public function skills()
    {
        return $this->hasMany('App\Skill', 'user_id', 'id')
                    ->orderBy('updated_at', 'desc');
    }

    public function companyBackground()
    {
        return $this->hasOne('App\CompanyBackground', 'user_id', 'id');
    }

    public function jobs()
    {
        return $this->hasMany('App\Job', 'user_id', 'id');
    }

    /**
     * return calcuated user age
     *
     * @return integer
     **/
    public function getAge()
    {
        $dob =  Carbon::parse($this->dob);
        $today = Carbon::now();
        $age = $today->diffInYears($dob);

        return $age;
    }


    /**
     * return user name
     *
     * @param int $id
     * @return string
     **/
    public static function getName($id)
    {
        $user = User::find($id);
        return $user->name;
    }

    /**
     * return user location
     *
     * @param int $id
     * @return string
     **/
    public static function getLocation($id)
    {
        $user = User::find($id);
        return $user->location;
    }

    /**
     * return user photo url
     *
     * @param int $id
     * @return string
     **/
    public static function getPhoto(
        $uid, 
        $picture = null, 
        $size='lg', 
        $urlType = 'url'
    ){
        if($picture == null){
            return null;
        }

        $base = ($urlType == 'path') ? public_path() : \URL::to('/');
        $encodeHelper = new EncodeHelper;

        return $base
            .'/images/profile/'
            .$encodeHelper->encodeData($uid)
            .'/profile_images/'
            .$picture;
    }

    /**
     * reuturn image url
     *
     * @param int $id
     **/
    public static function getPhotoPath($uid, $urlType = 'url')
    {
        $base = ($urlType == 'path') ? public_path() : \URL::to('/');
        $encodeHelper = new EncodeHelper();

        return $base 
            .'/images/profile/'
            .$encodeHelper->encodeData($uid) 
            .'/profile_images/';
    }

    /**
     * return companies' name
     *
     * @param array $jobs
     * @return array
     **/
    public static function getCompanyNames($jobs)
    {
        $companies = [];
        foreach($jobs as $job){
            $companies[] = User::getName($job->user_id);
        }

        $companies = User::uniqueNamesWithCount($companies);

        return $companies;
    }

    /**
     * return unique names with thier count
     *
     * @param array $items
     * @return array
     **/
    public static function uniqueNamesWithCount($items)
    {
        $results = [];
        $uniqueCompanies = array_unique($items);
        $collection = collect($items);

        foreach($uniqueCompanies as $companyName){
            $results[] = [
                'name' => $companyName,
                'count'=> User::getCountOfCompany($collection, $companyName)
            ];
        }

        return $results;
    }

    /**
     * return count of company
     *
     * @return integer
     **/
    public static function getCountOfCompany($collection, $companyName)
    {
        $cnt = 0;
        foreach($collection as $item){
            if($item == $companyName){
                $cnt++;
            }
        }

        return $cnt;
    }

    /**
     * A user can have many messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
      return $this->hasMany(Message::class);
    }


    /**
     * check whether complete now popup allowed or now
     *
     * @param int $userId
     * @param int $userType
     * @return boolean
     **/
    public static function isCompleteNowAllowed($userId, $userType)
    {
        $configHelper = new ConfigHelper();
        $userTypes = $configHelper->getUserTypes();

        if($userType == $userTypes['individual']){
            $user = User::with('qualifications',
                'experiences',
                'awards',
                'skills')
                ->where('id',$userId)
                ->where('user_type',$userType)
                ->first();

            return $user->qualifications->isEmpty()
                    && $user->experiences->isEmpty()
                    && $user->awards->isEmpty()
                    && $user->skills->isEmpty()
                    && $user->about == null
                    ? true : false;
        }
        else{
            $user = User::with('companyBackground')
                ->where('id',$userId)
                ->where('user_type',$userType)
                ->first();

            return empty($user->companyBackground) && $user->about == null ? true : false;
        }
    }

    public static function getAllEmployees($employerId, $columns)
    {
        $jobIds = Job::where('user_id', $employerId)->pluck('id');
        $applicantIds = JobApplication::whereIn('job_id', $jobIds)
                            ->pluck('applicant_id');
        $employees = self::select($columns)
                            ->whereIn('id', $applicantIds)->get();
        return $employees;
    }
}
