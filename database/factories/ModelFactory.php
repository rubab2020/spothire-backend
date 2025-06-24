<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->email,
        'phone' => str_random(11),
        'password' => $password ?: $password = bcrypt('secret'),
        'valid' => 1,
        'user_type' => 0,
        'remember_token' => str_random(10),
    ];
});


/** Write offer */
$factory->define(App\WriteOffer::class, function (Faker\Generator $faker, $params){
    static $departments = ['Software', 'Graphics', 'Accounts', 'Hr Department'];
    static $locations = ['Dhaka', 'Barishal', 'Khulna', 'Rajshahi', 'Chittagong', 'Sylhet'];
    static $experiences = ['1 year', '2 years', '3 years', '4 years', '5 years', '6 years', '7 years', '8 years', '9 years', '10 years'];
    static $qulifications = ['BSC', 'MSC', 'HSC', 'SSC', 'MA', 'BA', 'MBA', 'EMBA'];
    static $salaryTypes = ['weekly', 'month', 'year'];
    static $employemntTypes = ['contractual', 'permanent'];
    static $workDays = ['Sunday,Tuesday,Monday,Thursday,Friday', 'Tuesday,Monday,Thursday,Friday', 'Sunday,Tuesday,Monday', 'Sunday,Tuesday'];
    static $avertDurations = [1, 3, 7, 14];
    static $jobTypes = ['part time', 'full time']; 

    return [
        'reg_id'        => $params['reg_id'], // hire_id
        'looking_for'   => $faker->sentence,
        'department'    => $faker->randomElement($departments),
        'location'      => $faker->randomElement($locations),
        'city'          => $faker->randomElement($locations),
        'experience'    => $faker->randomElement($experiences),
        'qualification'  => $faker->randomElement($qulifications),
        'work_description'  => $faker->paragraph,
        'required_skill'     => $faker->paragraph,
        'other_requirements' => $faker->paragraph,
        'salary_from'       => $faker->randomNumber,
        'salary_to'         => $faker->randomNumber,
        'salary_per'        => $faker->randomElement($salaryTypes),
        'employment_type'   => $faker->randomElement($employemntTypes),
        'period_from'       => $faker->date,
        'time_from'         => $faker->time,
        'time_to'           => $faker->time,
        'work_days'         => $faker->randomElement($workDays),
        'advert_duration'   => $faker->randomElement($avertDurations),
        'job_type'          => $faker->randomElement($jobTypes)
    ];
});


/** JobApplication */
$factory->define(App\JobApplication::class, function (Faker\Generator $faker, $params){
    return [
        'applicant_id' => $params['applicant_id'],
        'job_id' => $params['job_id'],
        'application_status' => $params['application_status']
    ];
});

// worker projects
$factory->define(App\Project::class, function (Faker\Generator $faker, $params){
    return [
        'reg_id' => $params['reg_id'], //worker_id
        'duties' => $faker->paragraph,
        'title_project' => $faker->sentence,
        'organization_name' => $faker->sentence,
        'skill_used' => $faker->sentence,
        'award_title' => $faker->sentence,
        'award-institute' => $faker->sentence,
        'award_date' => $faker->date,
        'period_from' => $faker->date,
        'period_to' => $faker->date,
    ];
});

// worker work experience
$factory->define(App\workExperience::class, function (Faker\Generator $faker, $params){
    return [
        'reg_id' => $params['reg_id'], //worker_id
        'occupation' => $faker->paragraph,
        'work_type' => $faker->sentence,
        'organization_name' => $faker->sentence,
        'duties' => $faker->paragraph
    ];
});

// worker skill
$factory->define(App\Skill::class, function (Faker\Generator $faker, $params){
    return [
        'reg_id' => $params['reg_id'], //worker_id
        'tags_name' => $faker->word,
    ];
});

// worker qualification
$factory->define(App\Qualification::class, function (Faker\Generator $faker, $params){
    return [
        'reg_id' => $params['reg_id'], //worker_id
        'degree' => $faker->sentence,
        'result_percentage' => $faker->randomElement([80,90,100]),
        'result_cgpa' => $faker->randomElement([3,4,5]),
        'cgpa_scale' => $faker->randomElement([3,4,5]),
        'institution' => $faker->sentence,
        'concentration' => $faker->sentence,
        'completing_date' => $faker->date
    ];
});

// worker awards
$factory->define(App\Awards::class, function (Faker\Generator $faker, $params){
    return [
        'reg_id' => $params['reg_id'], //worker_id
        'award_title' => $faker->sentence,
        'award_institute' => $faker->sentence,
        'award_date' => $faker->date
    ];
});