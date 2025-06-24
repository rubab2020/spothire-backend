<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SpotHire\Helpers\EncodeHelper;

class CompanyImage extends Model
{
    
	/**
     * return image name
     *
     * @param int $id
     * @return string
     **/
    public static function getPhotoPath($uid, $urlType = 'url')
    {
        $base = ($urlType == 'path') ? public_path() : \URL::to('/');
        $encodeHelper = new EncodeHelper();

        return $base 
            .'/images/profile/'
            .$encodeHelper->encodeData($uid) 
            .'/company_value_images/';
    }
}