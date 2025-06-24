<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\CompanyBackground;
use Validator;
use Illuminate\Support\Facades\Hash;

class SettingsController extends ApiController
{
    /**
     * @var obj
     **/
    private $clientInfo;

    function __construct(){
        $this->clientInfo = auth()->guard('api')->authenticate();
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getIndividualSettings()
    {
        $user = User::find($this->clientInfo->id);
        
        return $this->respond(['data'=> [
            'name'=> $user->name,
            'email'=> $user->email,
            'phone'=> $user->phone,
        ]]);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getCompanySettings()
    {
        $user = User::find($this->clientInfo->id);
        
        return $this->respond(['data'=> [
            'name'=> $user->name,
            'email'=> $user->email,
            'phone'=> $user->phone,
        ]]);
    }

    /**
     * update individual dat
     *
     * @return Illuminate/Http/Response
     **/
    public function updateIndividualSettings(Request $request)
    {
        $user = User::find($this->clientInfo->id);
        if(!$user){
            return $this->respondInternalError('User not found');
        }

        $rules = [
            'name' => 'required|string|max:255',
        ];
        if($request->input('email') != $user->email)
            $rules['email'] = 'required|string|email|max:255|unique:users';
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return $this->respondValidationErrors($validator->messages());
        }

        // saves
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if($user->save()){
            return $this->respondUpdatingResource('Settings updated successfully');
        }
        else{
            return $this->respondInternalError('Failed updating settings');
        }
    }

    /**
     * update company data
     *
     * @return Illuminate/Http/Response
     **/
    public function updateCompanySettings(Request $request)
    {
        $user = User::find($this->clientInfo->id);
        if(!$user){
            return $this->respondInternalError('Company not found');
        }

        $rules = [
            'name' => 'required|string|max:255',
        ];
        if($request->input('email') != $user->email)
            $rules['email'] = 'required|string|email|max:255|unique:users';
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return $this->respondValidationErrors($validator->messages());
        }

        // saves
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if($user->save()){
            return $this->respondUpdatingResource('Settings updated successfully');
        }
        else{
            return $this->respondInternalError('Failed updating settings');
        }
    }
}
