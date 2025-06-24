<?php

namespace App\Http\Controllers;

use App\PasswordReset;
use App\User;
use App\UserToken;
use App\VerificationToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\SpotHire\Helpers\ConfigHelper;
use App\Jobs\SendAuthEmailJob;
use App\SpotHire\Helpers\EncodeHelper;
use App\NotificaionDeviceToken;
use App\OTP;

class AuthController extends ApiController
{
    /**
     * @var obj
     **/
    private $user;

    private $userTypes;

    /**
     * @var App\Helpers\EncodeHelper
    **/
    private $encodeHelper;

    private $otp;

    public function __construct(ConfigHelper $configHelper, EncodeHelper $encodeHelper, OTP $otp)
    {
        $this->encodeHelper = $encodeHelper;
        
        $this->middleware('auth:api', ['except' => [
            'login', 
            'register', 
            'recover', 
            'verifyUser', 
            'refreshToken', 
            'forgetPassword', 
            'forgetPasswordUpdate',
            'resendOTP' 
            ]
        ]);

        $this->userTypes = $configHelper->getUserTypes();

        $this->otp = $otp; 
    }

    /**
     * API Login, on success return JWT Auth token
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {
        $data = $request->json()->all();

        $rules = [
            'phone' => 'required',
            'password' => 'required'
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return $this->respondValidationErrors($validator->messages());
        }

        $credentials = [
            'phone' => $request->json()->get('phone'),
            'password' => $request->json()->get('password'),
            'valid' => true
        ];

        $user = User::where('phone',$request->json()->get('phone'))->first();

        if(!$user)
            return $this->respondValidationError('This Account is not registered');
        if(!$user->valid)
            return $this->respondValidationError('You have not verified your phone number');

        try {
            if (!$token = auth()->guard('api')->attempt($credentials)) 
                return $this->respondValidationError('Login failed. Phone number and password don\'t match.');
        } catch (JWTException $e) {
            return $this->respondInternalError();
        }

        $setToken = auth()->guard('api')->setToken($token);
        $user = auth()->guard('api')->authenticate();
        $saveToken = UserToken::create([
            'user_id' => $user->id,
            'jwt_token' => $token,
            'is_blacklisted' => false
        ]);

        $user = $this->userArray($user);
        return $this->respondWithToken($token, $user);
    }

    public function userArray($user)
    {
        return array(
            'id'                => $this->encodeHelper->encodeData($user['id']),
            'name'              => $user['name'],
            'email'             => $user['email'],
            'profile_picture'   => ($user['picture'] != '' && $user['picture'] != null) 
                                    ? User::getPhotoPath($user['id']).$user['picture'] 
                                    : null,
            'cover_photo'       => ($user['cover_photo'] != '' && $user['cover_photo'] != null) 
                                    ? User::getPhotoPath($user['id']).$user['cover_photo'] 
                                    : null,
            'user_type'         => !$user['user_type'] ? 'individual' : 'company',
            'show_complete_now' => $this->isCompleteNowPopupAllowed($user['id'], $user['user_type'])
        );
    }

    /**
     * check whether complete now popup allowed or now
     *
     * @param int $userId
     * @param int $userType
     * @return boolean
     **/
    public function isCompleteNowPopupAllowed($userId, $userType)
    {
        if($userType == $this->userTypes['individual']){
            $user = User::with('qualifications',
                'experiences',
                'awards',
                'skills')
                ->where('id',$userId)
                ->where('user_type',$userType)
                ->first();

            return $user->qualifications->isEmpty()
                    && $user->experiences->isEmpty()
                    && $user->awards->isEmpty()
                    && $user->skills->isEmpty()
                    && $user->about == null
                    ? true : false;
        }
        else{
            $user = User::with('companyBackground')
                ->where('id',$userId)
                ->where('user_type',$userType)
                ->first();

            return empty($user->companyBackground) && $user->about == null ? true : false;
        }
    }

    protected function respondWithToken($token, $user)
    {
        return $this->respond([
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60, // seconds
                'user' => $user
            ]
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken()
    {
        $oldToken = auth()->guard('api')->getToken();
        $newToken = auth()->guard('api')->refresh($oldToken);
        $setToken = auth()->guard('api')->setToken($newToken);
        $user = auth()->guard('api')->authenticate();
        $user_token_save = UserToken::create([   
            'user_id' => $user->id,
            'jwt_token' => $newToken,
            'is_blacklisted' => false
        ]);
        return $this->respondWithToken($newToken, $user);
    }

    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $credentials = $request->json()->all();
        
        $rules = $this->registerValidation($credentials);
        $validator = Validator::make($credentials, $rules);
        if ($validator->fails()) {
            return $this->respondValidationErrors($validator->messages());
        }

        $user = $this->userCreate($credentials);
        if(!$user) 
            return $this->respondInternalError("Failed saving account");

        if(!$this->otp->sendOTP($credentials['phone']))
            return $this->respondInternalError('Failed Sending OTP');

        return $this->respondCreatingResource("Thanks for signing up! Please varify your mobile number using the code we have sent to your number.");
    }

    public function registerValidation($credentials)
    {
        if ($credentials['role'] == 'company') {
            $rules = [
                'phone' => 'required|string|min:11|unique:users',
            ];
        } else {
            $rules = [
                'phone' => 'required|string|min:11|unique:users',
            ];
        }
        return $rules;
    }

    /**
     * API Login, on success return JWT Auth token
     *
     */
    public function userCreate($credentials)
    {
        if ($credentials['role'] == 'individual') {
            $user = User::create([
                'phone' => $credentials['phone'],
                'user_type' => $this->userTypes['individual'],
            ]);
        } else {
            $user = User::create([
                'phone' => $credentials['phone'],
                'user_type' => $this->userTypes['company'],
            ]);
        }
        return $user;
    }

    /**
     * API Verify User Method
     *
     */
    public function verifyUser(Request $request)
    {
        $request = $request->json()->all();

        $user = User::where('phone', $request['phone'])->first();
        if(!$user)
            return $this->respondValidationError('User not found.');
        if($user->valid) 
            return $this->respondValidationError('This user is already verified.');

        if(!$this->otp->verifyOTP($request['phone'], $request['code']))
            return $this->respondValidationError('Invalid verificaion code or code expired.');

        $user->valid = true;
        $user->save();

        $token = auth()->guard('api')->fromUser($user);
        $user = $this->userArray($user);

        return $this->respond([
            'message' => 'Welcome',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 8760,
                'user'=> $user
            ]
        ]);
    }

    public function resendOTP(Request $request)
    {
        $phone = $request->json()->all()['phone'];

        if(!$this->otp->resendOTP($phone))
            return $this->respondInternalError('Failed Sending OTP or resend OTP limit reached.');

        return $this->respondCreatingResource("Sent OTP Successfully.");
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to re-login to get a new token
     */
    public function logout(Request $request)
    {
        $oldToken = auth()->guard('api')->getToken();

        try {
            auth()->guard('api')->invalidate($oldToken);
            $tokenDelete = UserToken::where('jwt_token', '=', $oldToken)->delete();
            return $this->respond(['message' => 'You have successfully logged out.']);
        } catch (JWTException $e) {
            return $this->respondInternalError();
        }
    }

    /**
     * API forget Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(Request $request)
    {
        $user = User::where('phone', $request->input('phone'))->first();

        if(!$user)
            return $this->respondValidationError('This Account is not registered');
        if(!$user->valid)
            return $this->respondValidationError('You have not verified your phone number');

        if(!$this->otp->sendOTP($request->input('phone')))
            return $this->respondInternalError('Failed Sending OTP');

        return $this->respond([
            'success' =>[
                'message' => 'Sent OTP successfully',
            ],
            'data' => [
                'phone' => $request->input('phone')
            ]
        ]);
    }

    /**
     * API Recover Password
     *
     */
    public function forgetPasswordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'code' => 'required',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password|min:8',
        ]);

        if ($validator->fails()) 
            return $this->respondValidationErrors($validator->messages());

        $user = User::where('phone', $request->input('phone'))->first();
        
        if (!$user) 
            return $this->respondValidationError('User Not found');

        if(!$this->otp->verifyOTP($request->input('phone'), $request->input('code')))
            return $this->respondValidationError('Invalid verificaion code. Please send request again.');

        $user->password = bcrypt($request->input('password'));
        
        if(!$user->save()) return $this->respondInternalError('Failed Resetting Password');

        return $this->respondUpdatingResource('Password changed successfully.');
    }


    /**
     * passwor reset
     *
     * @return Illuminate\Http\Response
     **/
    public function passwordReset(Request $request)
    {
        $this->user = auth()->guard('api')->authenticate();

        $user = User::find($this->user->id);
        
        if (!$user) 
            return $this->respondValidationError('User Not found');

        Validator::extend('old_password', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, current($parameters));
        },'Current Password is not correct');

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|old_password:' . $user->password,
            'new_password' => 'required|string|min:8',
            'confirm_password' => 'required|same:new_password|min:8',
        ]);

        if ($validator->fails()) {
            return $this->respondValidationErrors($validator->messages());
        }

        $user->password = bcrypt($request->input('new_password'));
        
        if (!$user->save()) return $this->respondInternalError('Failed Resetting Password');
       
        return $this->respondUpdatingResource('Password changed successfully');
    }


    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getNotificaionDeviceTokens()
    {
        $this->user = auth()->guard('api')->authenticate();
        $user = User::find($this->user->id);
        if(!$user) return $this->respondInternalError('User Not found');

        $tokens = NotificaionDeviceToken::where('user_id', $user->id)->pluck('token');
        return $this->respond([
            'data' => $tokens
        ]);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function getReceiverNotificaionDeviceTokens($uid)
    {
        $uid = $this->encodeHelper->decodeData($uid);
        $user = User::find($uid);
        if(!$user) return $this->respondInternalError('User Not found');

        $tokens = NotificaionDeviceToken::where('user_id', $uid)->pluck('token');
        return $this->respond([
            'data' => $tokens
        ]);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author 
     **/
    public function storeNotificaionDeviceToken(Request $request)
    {
        $token = $request->input('token');

        $this->user = auth()->guard('api')->authenticate();
        $user = User::find($this->user->id);
        if(!$user) return $this->respondInternalError('User Not found');


        $tokenObj = NotificaionDeviceToken::where('token', $token)->first();
        if(!$tokenObj){
            $tokenObj = new NotificaionDeviceToken;
        }
        else if($tokenObj->user_id ==  $this->user->id) {
            return $this->respondValidationError('Token already exist');
        }
        $tokenObj->user_id = $user->id;
        $tokenObj->token = $token;
        if(!$tokenObj->save()){
            return $this->respondInternalError('Failed saving token');
        }

        return $this->respondCreatingResource('Token stored successfully');
    }

    public function saveBasicDetails(Request $request) {
        $request = $request->json()->all();

        $user = User::where('phone', $request['phone'])->first();
        if(!$user)
            return $this->respondValidationError('User not found.');

        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',              // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[0-9]/',      // must contain at least one digit
            ]
        ];
        if($request['email'] != $user->email)
            $rules['email'] = 'string|email|max:255|unique:users';

        $validator = Validator::make($request, $rules);
        if ($validator->fails()) {
            return $this->respondValidationErrors($validator->messages());
        }

        $user->name = $request['name'];
        $user->email = $request['email'] == '' ? null : $request['email'];
        $user->password = bcrypt($request['password']);

        if (!$user->save()) return $this->respondInternalError('Failed saving basic info');

        return $this->respondUpdatingResourceWithData('Updated info Successfully', ['name' => $user->name, 'email'=>$user->email]);
    }
}