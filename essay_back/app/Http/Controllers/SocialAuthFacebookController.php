<?php
//Controller for facbook Login

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;

class SocialAuthFacebookController extends Controller
{
  /**
   * Create a redirect method to facebook api.
   *
   * @return void
   */
  //  public function redirect()
 //  {
   //     return Socialite::driver('facebook')->redirect();
//    }



      public function redirect()
      {
        return Socialite::driver('facebook')->redirect();
      }
    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
   // public function callback()
    // {
       // $user = $service->createOrGetUser(Socialite::driver('facebook')->user()); 
       // auth()->login($user);
      //  return redirect()->to('/homeContent');
    // }
     public function Callback()
     {
        $userSocial =   Socialite::driver('facebook')->stateless()->user();
        $users       =   User::where(['email' => $userSocial->getEmail()])->first();
        if($users){
            Auth::login($users);
            return redirect('/');
        }else{
            $user = User::create([
                'name'          => $userSocial->getName(),
                'email'         => $userSocial->getEmail(),
            //    'image'         => $userSocial->getAvatar(),
                'provider_id'   => $userSocial->getId(),
                'provider'      => $provider,
            ]);
         return redirect()->route('homeContent');
        }
     }
}
