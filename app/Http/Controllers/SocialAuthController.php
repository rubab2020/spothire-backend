<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use App\User;
use App\SpotHire\Helpers\ConfigHelper;
use App\SpotHire\Helpers\EncodeHelper;

class SocialAuthController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    protected $userTypes;

    /**
     * @var App\Helpers\EncodeHelper
     **/
    private $encodeHelper;

    function __construct(ConfigHelper $configHelper, EncodeHelper $encodeHelper)
    {
        $this->encodeHelper = $encodeHelper;
        $this->redirectTo = ConfigHelper::getSiteUrl();
        $this->userTypes = $configHelper->getUserTypes();
    }

    /**
     **_ Redirect the user to the OAuth Provider.
     _**
     **_ @return Response
     _**/
    public function redirectToProvider($provider, Request $request)
    {
        return Socialite::driver($provider)
                        ->with(['state' => serialize(['user_type'=>$request->input('user_type')])])
                        ->stateless()->redirect();
    }

    /**
     _ Obtain the user information from provider.  Check if the user already exists in our
     _ database by looking up their provider_id in the database.
     _ If the user exists, log them in. Otherwise, create a new user then log them in. After that 
     _ redirect them to the authenticated users homepage.
     _
     _ @return Response
     _**/
    public function handleProviderCallback($provider, Request $request)
    {
        $stateData = $request->input('state'); 
        if($stateData){
            $stateData = unserialize($request->input('state'));
        }

        if(! isset($stateData['user_type'])){
            return redirect($this->redirectTo.'?data=invalid');
        }

        $user = null;

        if($provider == 'google') $user = Socialite::driver($provider)->stateless()->user();
        else $user = Socialite::driver($provider)->stateless()->user(); //facebook

        if(!$user) return redirect($this->redirectTo.'?data=invalid');

        $authUser = $this->findOrCreateUser($user, $provider, $stateData);

        $token = auth()->guard('api')->fromUser($authUser);
        
        $data = [
            'data'=> [
                'token'      => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 8760,
                'user'       => [
                    'id'                => $this->encodeHelper->encodeData($authUser['id']),

                    'name'              => $authUser['name'],
                    'profile_picture'   => ($authUser['picture'] != '' && $authUser['picture'] != null) 
                                            ? User::getPhotoPath($authUser['id']).$authUser['picture'] 
                                            : null,
                    'cover_photo'       => ($authUser['cover_photo'] != '' && $authUser['cover_photo'] != null) 
                                                ? User::getPhotoPath($authUser['id']).$authUser['cover_photo'] 
                                                : null,
                    'user_type'         => $authUser['user_type'],
                    'show_complete_now' => User::isCompleteNowAllowed($authUser['id'], $authUser['user_type'])
                ] 
            ]
        ];

        return redirect($this->redirectTo.'?data='.base64_encode(json_encode($data)));
    }

    /**
     _ If a user has registered before using social auth, return the user
     _ else, create a new user object.
     _ @param  $user Socialite user object
     _ @param $provider Social auth provider
     _ @return  User
     */
    public function findOrCreateUser($user, $provider, $stateData)
    {
        $name = ($provider == 'google') 
                    ? $user->user['given_name'].' '.$user->user['family_name']
                    : $user->user['name'];

        list($firstname, $lastname) = ($provider == 'google')
                                        ?[
                                            $user->user['given_name'],
                                            $user->user['family_name']
                                         ]
                                        : explode(" ", $user->user['name']);

        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }

        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            $existingUser->name = $existingUser->name ? $existingUser->name : $name;
            $existingUser->picture = $existingUser->picture 
                                        ? $existingUser->picture 
                                        : $this->saveImage(
                                            $provider, 
                                            $user->avatar_original, 
                                            $existingUser->id, 
                                            null, 
                                            300);
            $existingUser->picture_sm = $existingUser->picture 
                                        ? $existingUser->picture 
                                        : $this->saveImage(
                                            $provider, 
                                            $user->avatar_original, 
                                            $existingUser->id, 
                                            null, 
                                            100); 
            $existingUser->provider = $provider;
            $existingUser->provider_id   = $user->id;
            $existingUser->valid  = true;
            $existingUser->save();

            return $existingUser;
        }

        $newUser = User::create([
            'name'          => $name,
            'email'         => $user->email,
            'provider'      => $provider,
            'provider_id'   => $user->id,
            'user_type'     => $this->userTypes[$stateData['user_type']],
            'valid'         => true
        ]);
        $newUser->picture = $this->saveImage(
                                $provider, 
                                $user->avatar_original, 
                                $newUser->id,
                                null,
                                300);
        $newUser->picture_sm = $this->saveImage(
                                $provider, 
                                $user->avatar_original, 
                                $newUser->id,
                                null,
                                100);
        $newUser->save();

        return $newUser;
    }


    /**
     * undocumented function
     *
     * @return void
     **/
    public function saveImage($provider, $imageUrl, $uId, $width, $height)
    {
        if (isset($imageUrl)) {
            if(($provider == 'google')){
                $imageUrl = str_replace("=s50","=s300", $imageUrl);
            }
            $name    = ($provider == 'google') ? 'google-pic.jpg' : 'facebook-pic.jpg';
            $imgName = date('mdYHis') . uniqid() . '.' . $name;
            $path    = public_path('/images/profile/' . $this->encodeHelper->encodeData($uId) . '/profile_images/');

            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }

            $imageLink = $path.$imgName;
            \Image::make($imageUrl)
                ->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($imageLink);
            
            return $imgName;
        }

        return null;
    }
}
