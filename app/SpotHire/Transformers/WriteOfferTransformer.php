<?php
namespace App\SpotHire\Transformers;

use App\SpotHire\Transformers\Transformer;
use App\SpotHire\Helpers\EncodeHelper;
use App\User;
use App\SpotHire\Helpers\ConfigHelper;

class WriteOfferTransformer extends Transformer
{
    /**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    /**
    * @var integer
    **/
    private $clientInfo;

    private $nameOfuserTypes;

    function __construct(EncodeHelper $encodeHelper, ConfigHelper $configHelper)
    {
        $this->encodeHelper = $encodeHelper;

        $this->nameOfuserTypes = $configHelper->getNameOfUserTypes();

        $this->clientInfo == null;
        $url = url()->current();
        if (strpos($url, 'public') === false) {
            $this->clientInfo = auth()->guard('api')->authenticate();
        }
    }

    /**
     * transform a object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transform($job)
    {
        $data = [
            'id'                => $this->encodeHelper->encodeData($job['id']),
            'job_title'         => $job['job_title'],
            'slug'              => $job['slug'],
            'employment_type'   => $job['employment_type'],
            'start_date'        => $job['start_date'],
            'end_date'          => $job['end_date'],
            'salary_from'       => $job['salary_from'],
            'salary_to'         => $job['salary_to'],
            'salary_type'       => $job['salary_type'],
            'is_salary_negotiable' => $job['is_salary_negotiable'] ? true : false,
            'address'           => $job['address'],
            'location'          => $job['location'],
            'department'        => $job['department'],
            'min_experience'    => $job['min_experience'],
            'min_qualification' => $job['min_qualification'],
            'description'       => $job['description'],
            'requirements'      => $job['requirements'],
            'advert_duration'   => $job['advert_duration'],
            'advert_deadline'   => $job['advert_deadline'],
            'currency_type'     => $job['currency_type'],
            'skills'            => $this->transformSkills($job['skills']),
        ];

        return $data;
    }

    /**
     * transform collection of workDay
     *
     * @param $items
     * @return array
     **/
    public function transformSkills($workDays)
    {
        return array_map([$this, 'transformSkill'], is_array($workDays) ? $workDays : $workDays->toArray());
    }

    /**
     * transform workDay object for mapping between api parameters and database columns
     *
     * @param array of objects $jobs
     * @return array
     **/
    public function transformSkill($workDay)
    {
        return [
            'id'     => $this->encodeHelper->encodeData($workDay['id']),
            'job_id' => $this->encodeHelper->encodeData($workDay['job_id']),
            'name'   => $workDay['name'],
        ];
    }
}