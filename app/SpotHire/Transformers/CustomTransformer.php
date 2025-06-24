<?php
namespace App\SpotHire\Transformers;

use App\SpotHire\Transformers\Transformer;
use App\SpotHire\Helpers\EncodeHelper;
use App\User;
use App\SpotHire\Helpers\CustomHelper;
use App\SpotHire\Transformers\WorkerProfileTransformer;

class CustomTransformer extends Transformer
{
	/**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

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
    public function transform($data)
    {
        return [];
    }

	/**
     * transform collection of employers
     *
     * @param $items
     * @return array
     **/
    public function transformEmployers($employers)
    {
        return array_map([$this, 'transformEmployer'], is_array($employers) ? $employers : $employers->toArray());
    }

    /**
     * transform employer object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transformEmployer($employer)
    {
        return [
            'id' => $this->encodeHelper->encodeData($employer['id']),
            'employer_name' => $employer['name'],
            'is_active' =>  $employer['is_active'] ? true : false
        ];
    }

    /**
     * transform collection of occcupation
     *
     * @param $items
     * @return array
     **/
    public function transformOccupations($occupations)
    {
        return array_map([$this, 'transformOccupation'], is_array($occupations) ? $occupations : $occupations->toArray());
    }

    /**
     * transform occupation object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transformOccupation($occupation)
    {
        return [
            'id' => $this->encodeHelper->encodeData($occupation['id']),
            'occupation_name' => $occupation['name'],
            'is_active' =>  $occupation['is_active'] ? true : false
        ];
    }

    /**
     * transform collection of occcupation
     *
     * @param $items
     * @return array
     **/
    public function transformDegrees($degrees)
    {
        return array_map([$this, 'transformDegree'], is_array($degrees) ? $degrees : $degrees->toArray());
    }

    /**
     * transform occupation object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transformDegree($degree)
    {
        return [
            'id' => $this->encodeHelper->encodeData($degree['id']),
            'degree' => $degree['name'],
            'is_active' =>  $degree['is_active'] ? true : false
        ];
    }

    /**
     * transform collection of occcupation
     *
     * @param $items
     * @return array
     **/
    public function transformInstitutions($institutions)
    {
        return array_map([$this, 'transformInstitution'], is_array($institutions) ? $institutions : $institutions->toArray());
    }

    /**
     * transform occupation object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transformInstitution($institution)
    {
        return [
            'id' => $this->encodeHelper->encodeData($institution['id']),
            'institution_name' => $institution['name'],
            'is_active' =>  $institution['is_active'] ? true : false
        ];
    }

    /**
     * transform collection of occcupation
     *
     * @param $items
     * @return array
     **/
    public function transformTags($tags)
    {
        return array_map([$this, 'transformTag'], is_array($tags) ? $tags : $tags->toArray());
    }

    /**
     * transform occupation object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transformTag($tag)
    {
        return [
            'id' => $this->encodeHelper->encodeData($tag['id']),
            'name' => $tag['name'],
            'is_active' =>  $tag['is_active'] ? true : false
        ];
    }


}