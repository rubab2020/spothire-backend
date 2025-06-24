<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $fillable = [
        'user_id', 'jwt_token', 'is_blacklisted'
    ];
}
