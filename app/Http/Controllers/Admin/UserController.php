<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * undocumented function
     *
     * @return void
     **/
    public function index()
    {
        $users = User::get();
        return view('admin/users/list', compact('users'));
    }  
}
