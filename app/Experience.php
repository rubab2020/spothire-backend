<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\SpotHire\Helpers\EncodeHelper;

class Experience extends Model
{
    protected $table = 'user_experiences';

    public function workImages()
    {
        return $this->hasMany('App\ExperienceImage', 'work_id', 'id');
    }

    /**
     * update company name in work experience if assigned. 
     * It is nessary because company can change its name and we should 
     * get a reflection in work exprience data
     *
     * @param array $expenses
     * @return array
     **/
    public static function updateCompanyNameIfAssgined($experiences)
    {
        foreach($experiences as $key => $experience){
            if($experience['application_id']){
                $user = User::whereHas('jobs.applications',  function($query) use($experience){
		                	$query->where('id', $experience['application_id']);
		                })
		                ->first();

		        if($user){
                    $experiences[$key]['employer'] = $user->name;
		        	$experiences[$key]['employer_photo'] = User::getPhoto($user->id);
		        }
            }
        }

        return $experiences;
    }

    /**
     * return image name
     *
     * @param int $id
     * @return string
     **/
    public static function getPhotoPath($id)
    {
        $encodeHelper = new EncodeHelper();
        $photoPath = public_path() . '/images/profile/' . $encodeHelper->encodeData($id) . '/experience_images/';
        return $photoPath;
    }
}
