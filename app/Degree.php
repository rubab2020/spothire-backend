<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'degree'
    ];
    protected $hidden = [
        'created_at','updated_at'
    ];
}
