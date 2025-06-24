<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table = 'user_skills';
    
    protected $hidden = [
        'created_at','updated_at','user_id'
    ];
}
