<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    protected $table = 'user_qualifications';

    protected $fillable = [
        "degree",
        "institution",
        "result_cgpa",
        "cgpa_scale",
        "completing_date",
        "enrolled"
    ];
}
