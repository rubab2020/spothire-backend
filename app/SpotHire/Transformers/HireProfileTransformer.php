<?php
namespace App\SpotHire\Transformers;

use App\SpotHire\Transformers\Transformer;
use App\SpotHire\Helpers\EncodeHelper;
use App\User;

class HireProfileTransformer extends Transformer
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
     * @param array of objects $jobscd
     * @return array
     **/
    public function transform($profile)
    {
        $companyBackground = $profile['company_background'] ? $profile['company_background'] :  $profile->companyBackground;
        return [
            'id' => $this->encodeHelper->encodeData($profile['id']),
            'name'              => $profile['name'],
            'phone'             => $profile['phone'] ? base64_encode($profile['phone']) : null,
            'profile_picture'   => ($profile['picture'] != '' && $profile['picture'] != null) 
                                    ? User::getPhotoPath($profile['id']).$profile['picture'] 
                                    : null,
            'cover_photo'       => ($profile['cover_photo'] != '' && $profile['cover_photo'] != null) 
                                    ? User::getPhotoPath($profile['id']).$profile['cover_photo'] 
                                    : null,
            'about'             => $profile['about'],
            'company_expertise' => $profile['company_expertise'],
            'industry'          => $companyBackground['industry'],
            'location'          => $companyBackground['location'],
            'inception_date'    => $companyBackground['inception_date'],
            'annual_turnover'   => $companyBackground['annual_turnover'],
            'total_employees'   => $companyBackground['total_employees'],
            'images'            => $profile['images']
        ];
    }
}