<?php

namespace App\SpotHire\Helpers;

class ConfigHelper
{
    private static $salt1 = '0443652c6e5b0b3a';
    private static $salt2 = '339b166905a09d7f';

    private static $paginatePerPage = 20;

    /**
     * undocumented function
     *
     * @param string $saltName
     * @return mixed
     **/
    public function getSaltData($saltName){
        switch ($saltName) {
            case 'salt1':
                return self::$salt1;
                break;

            case 'salt2':
                return self::$salt2;
                break;
            
            default:
                return null;
                break;
        }
    }

    /**
     * get filter options based on filter name
     *
     * @param string $filterName
     * @return mixed
     **/
	public function getFilterData($filterName){
        switch ($filterName) {
            case 'categories': 
                return [
                    ['name' => 'Full Time'],  
                    ['name' => 'Part Time'],  
                    ['name' => 'Flexible'],  
                ];
                break;

            case 'employment_types':
                return [
                    ['name' => 'Permanent (Full-Time)'], 
                    ['name' => 'Permanent (Part-Time)'], 
                    ['name' => 'Contract (Full-Time)'], 
                    ['name' => 'Contract (Part-Time)'], 
                    ['name' => 'Single Work'], 
                    ['name' => 'Internship'], 
                    ['name' => 'Freelancing'], 
                ];
                break;

            case 'salary_ranges':
                return [
                    ['range' => ['start'=>null, 'end'=>null], 'text'=> 'Salary Negotiable'],
                    ['range' => ['start'=>0, 'end'=>9999], 'text'=> '< 10000'],
                    ['range' => ['start'=>10000, 'end'=>30000], 'text' => '10000 ~ 30000'],
                    ['range' => ['start'=>30000, 'end'=>50000], 'text' => '30000 ~ 50000'],
                    ['range' => ['start'=>50000, 'end'=>100000], 'text' => '50000 ~ 100000'],
                    ['range' => ['start'=>100001, 'end'=>10000000], 'text' => '100000+']
                ];
                break;

            case 'deadline_durations':
                return [
                    ['days' => 2, 'text' => 'In 3 Days'],
                    ['days' => 6, 'text' => 'In 7 Days'],
                    ['days' => 13, 'text' => 'In 14 Days']
                ];
                break;
            
            case 'location_names':
                return [
                    ['name' => 'Dhaka'],  
                    ['name' => 'Sylhet'],
                    ['name' => 'Chittagong'],
                    ['name' => 'Rajshahi'],
                    ['name' => 'Khulna'],
                    ['name' => 'Barishal'],
                    ['name' => 'Comilla'],
                    ['name' => 'Mymensingh'],
                    ['name' => 'Rangpur']
                ];

            default:
                return null;
                break;
        }
    }

    /**
    * return application statuses
    *
    * @return array
    **/
    public function getApplicationStatuses()
    {
        return [
           'applied' => 'applied',
           'interviewed' => 'interviewed',
           'assigned' => 'assigned',
           'completed' => 'completed',
           'shortlisted' => 'shortlisted'
        ];
    }

    public static function getApplicationStatusesStaic()
    {
        return [
           'applied' => 'applied',
           'interviewed' => 'interviewed',
           'assigned' => 'assigned',
           'completed' => 'completed',
           'shortlisted' => 'shortlisted'
        ];
    }

    /**
    * return rating statuses
    *
    * @return array
    **/
    public function getRatingStatuses()
    {
        return [
            'pending' => 'pending',
            'rated' => 'rated',
        ];
    }

    /**
     * get qualifications
     *
     * @return array
     **/
    public function getQualifications()
    {
        return [
            'Bachelors Degree',
            'Masters Degree',
            'Diploma',
            'Doctorate',
            'Secondary',
            'Higher Secondary',
            'Not Required'
        ];
    }

    public function getExperiences()
    {
        return [
            'Not required',
            '1-3 years',
            '3 - 5 years',
            '5 - 10 years',
            '> 10 years'
        ];
    }

    public function getSalaryTypes()
    {
        return [
            'Month',
            'Hour',
            'Year',
            'Project'
        ];
    }

    public function getEmploymentTypes()
    {
        return [
            'Permanent (Full-Time)',
            'Permanent (Part-Time)',
            'Contract (Full-Time)',
            'Contract (Part-Time)',
            'Single Work',
            'Internship',
            'Freelancing'
        ];
    }

    public function getAdvertDays()
    {
        return [
            ['name'=>'7 days', 'value'=>7, 'amount'=>250], 
            ['name'=>'14 days', 'value'=>14, 'amount'=>500], 
            ['name'=>'30 days', 'value'=>30 , 'amount'=>1000]
        ];
    }

    public function getDepartments()
    {
        return [
            'Accounting/Auditing',
            'Agriculture & Food',
            'Architecture & Construction',
            'Banking/Financial Services',
            'Beauty/Health Care',
            'Commercial & Supply Chain',
            'Consultancy',
            'Customer Service/Call Centre',
            'Charities(Non profit)',
            'Design(Creative)',
            'Data Entry Operator',
            'Driving/Motor Technician',
            'Engineering',
            'Education/Training',
            'Electrician/Repair',
            'Food Processing',
            'Garments/Textile',
            'General management/Admin',
            'Human Resources',
            'Hospitality/Travel/Tourism',
            'IT & Telecommunication',
            'Law/Legal',
            'Marketing/Sales',
            'Management-Executive',
            'Media/Advertising/Event',
            'Medical/Pharma',
            'Manufacturing/Maintenance',
            'NGO/Development',
            'Public Sector',
            'Production/Operation',
            'Publishing/Printing',
            'Research/Consultancy',
            'Secretary/Receptionist',
            'Security/Support Service',
            'Sports/Recreation',
            'Tourism',
            'Transport/Distrib./Logistics',
            'Others'
        ];
    }

    public function getUserTypes()
    {
        return [
            'individual' => 0,
            'company' => 1
        ];
    }

    public function getNameOfUserTypes()
    {
        return [
            0 => 'individual',
            1 => 'company'
        ];
    }

    public function getReviewStatuses()
    {
        return [
            'pending' => 'p',
            'approved' => 'a',
            'rejected' => 'r',
        ];
    }

    public function getNameOfReviewStatuses()
    {
        return [
            'p' => 'pending',
            'a' => 'approved',
            'r' => 'rejected'
        ];
    }

    public function getLocations()
    {
        return [
            "Barguna",
            "Barisal",
            "Bhola",
            "Jhalokati",
            "Patuakhali",
            "Pirojpur",
            "Bandarban",
            "Brahmanbaria",
            "Chandpur",
            "Chittagong",
            "Comilla",
            "Cox's Bazar",
            "Feni",
            "Khagrachhari",
            "Lakshmipur",
            "Noakhali",
            "Rangamati",
            "Dhaka",
            "Faridpur",
            "Gazipur",
            "Gopalganj",
            "Kishoreganj",
            "Madaripur",
            "Manikganj",
            "Munshiganj",
            "Narayanganj",
            "Narsingdi",
            "Rajbari",
            "Shariatpur",
            "Tangail",
            "Bagerhat",
            "Chuadanga",
            "Jessore",
            "Jhenaidah",
            "Khulna",
            "Kushtia",
            "Magura",
            "Meherpur",
            "Narail",
            "Satkhira",
            "Jamalpur",
            "Mymensingh",
            "Netrokona",
            "Sherpur",
            "Bogra",
            "Joypurhat",
            "Naogaon",
            "Natore",
            "Chapai Nawabganj",
            "Pabna",
            "Rajshahi",
            "Sirajganj",
            "Dinajpur",
            "Gaibandha",
            "Kurigram",
            "Lalmonirhat",
            "Nilphamari",
            "Panchagarh",
            "Rangpur",
            "Thakurgaon",
            "Habiganj",
            "Moulvibazar",
            "Sunamganj",
            "Sylhet",
        ];
    }

    public function getPaginatePerPage()
    {
        return self::$paginatePerPage;
    }

    public static function getSiteUrl()
    {
        return env("APP_URL");
    }
}