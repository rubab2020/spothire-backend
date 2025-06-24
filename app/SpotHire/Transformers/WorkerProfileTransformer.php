<?php
namespace App\SpotHire\Transformers;

use App\SpotHire\Transformers\Transformer;
use App\SpotHire\Helpers\EncodeHelper;
use App\User;
use App\ExperienceImage;

class WorkerProfileTransformer extends Transformer
{
	/**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    /**
    * @var integer
    **/

    function __construct(EncodeHelper $encodeHelper)
    {
        $this->encodeHelper = $encodeHelper;
    }

	/**
     * transform a object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transform($profile)
    {
        return [
            'id' => $this->encodeHelper->encodeData($profile['id']),
            'name'              => $profile['name'],
            'phone'             => $profile['phone'] ? base64_encode($profile['phone']) : null,
            'about'             => $profile['about'],
            'profile_picture'   => ($profile['picture'] != '' && $profile['picture'] != null) ? User::getPhotoPath($profile['id']).$profile['picture'] : null,
            'cover_photo'       => ($profile['cover_photo'] != '' && $profile['cover_photo'] != null) ? User::getPhotoPath($profile['id']).$profile['cover_photo'] : null,
            'rating'            => isset($profile['rating']) ? $profile['rating'] : null,
            'qualifications'    => $this->transformQualifications($profile['qualifications']),
            'experiences'       => $this->transformExperiences($profile['experiences']),
            'awards'            => $this->transformAwards($profile['awards']),
            'skills'            => $this->transformSkills($profile['skills'])
        ];
    }

    /**
     * transform collection of qualification
     *
     * @param $items
     * @return array
     **/
    public function transformQualifications($qualifications)
    {
        return array_map([$this, 'transformQualification'], is_array($qualifications) ? $qualifications : $qualifications->toArray());
    }

    /**
     * transform qualification object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transformQualification($qualification)
    {
        return [
            'id'                => $this->encodeHelper->encodeData($qualification['id']),
            'degree'            => $qualification['degree'],
            'institution'       => $qualification['institution'],
            'result_cgpa'       => $qualification['result_cgpa'],
            'cgpa_scale'        => $qualification['cgpa_scale'],
            'completing_date'   => $qualification['completing_date'],
            'enrolled'          => $qualification['enrolled'] ? true : false,
        ];
    }

    /**
     * transform collection of experience
     *
     * @param $items
     * @return array
     **/
    public function transformExperiences($experiences)
    {
        return array_map([$this, 'transformExperience'], is_array($experiences) ? $experiences : $experiences->toArray());
    }

    /**
     * transform experience object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transformExperience($experience)
    {
        return [
            'id'                => $this->encodeHelper->encodeData($experience['id']),
            'occupation'        => $experience['occupation'],
            'employer'          => $experience['employer'],
            'employer_photo'    => isset($experience['employer_photo']) ? $experience['employer_photo'] : null,
            'application_id'    => $experience['application_id'] ? 
                                    $this->encodeHelper->encodeData($experience['application_id'])
                                    : null,
            'continuing'        => $experience['continuing'],
            'period_from'       => $experience['period_from'],
            'period_to'         => $experience['period_to'],
            'rating'            => isset($experience['rating']) ? $experience['rating'] : null,
            'rating_status'     => isset($experience['rating_status'])
                                    ? $experience['rating_status']
                                    :null,
            'work_images'       => $this->transformWorkImages($experience['work_images'], $experience['user_id'])
        ];
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function transformWorkImages($workImages, $userId)
    {
        $data = [];
        $workImages = is_array($workImages) ? $workImages : $workImages->toArray();
        
        foreach ($workImages as $workImage) {
           $data[] = $this->transformWorkImage($workImage, $userId);
        }

        return $data;
    }

    /**
     * undocumented function
     *
     * @return void
     **/
    public function transformWorkImage($workImage, $userId)
    {
        return [
            'id' => $this->encodeHelper->encodeData($workImage['id']),
            'image' => ExperienceImage::getPhotoPath($userId).$workImage['image'],
            'description' => $workImage['description'],
        ];
    }

    /**
     * transform collection of award
     *
     * @param $items
     * @return array
     **/
    public function transformAwards($awards)
    {
        return array_map([$this, 'transformAward'], is_array($awards) ? $awards : $awards->toArray());
    }

    /**
     * transform award object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transformAward($award)
    {
        return [
            'id'  => $this->encodeHelper->encodeData($award['id']),
            'title' => $award['title'],
            'institute' => $award['institute'],
            'date' => $award['date']
        ];
    }

    /**
     * transform collection of skill
     *
     * @param $items
     * @return array
     **/
    public function transformSkills($skills)
    {
        return array_map([$this, 'transformSkill'], is_array($skills) ? $skills : $skills->toArray());
    }

    /**
     * transform skill object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transformSkill($skill)
    {
        if(empty($skill)){
            return [];
        }

        return [
            'id' => $skill['id'] 
                    ? $this->encodeHelper->encodeData($skill['id']) 
                    : null,
            'name' => $skill['name']
        ];
    }
}