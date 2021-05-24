<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\User;
use Image;
use Carbon\Carbon;
use Artisan ;
class ProfileController extends Controller
{

  public function profile ()
  {
   $listCategories = DB::table('categories')->where('is_active', 1)->get();;

   return view('Profiles.profil',array('user'=> Auth::user(),'categories'=>$listCategories));
  }
  public function __construct()
{
   $this->middleware('auth');
}

public function newsletter_view ()
{
    $listCategories = DB::table('categories')->where('is_active', 1)->get();;

    return view('Profiles.manageNewsletter',array('user'=> Auth::user(),'categories'=>$listCategories));
 }


//public function newsletter_view()
//{
   // $listCategories = DB::table('categories')->where('is_active', 1)->get();;
   //$listCategories = DB::table('creatives')->where('is_active', 1)->get();;

  //  return view('Profiles.manageUpdate',array('user'=> Auth::user(),'categories'=>$listCategories));
// }



 public function newsletter_update(Request $request)
 {
$user = Auth::user();
        $user->selected_time = $request->input('selected_time');
        $user->recieve_news = $request->input('defaultExampleRadios');
        if (  $request->input('prefered_news') != null )
            $user->prefered_news = implode( "//" , $request->input('prefered_news') );
        else
            $user->prefered_news = $request->input('prefered_news') ;
            $user->save();
        return back();
        // $exitCode = Artisan::call('newsletter:day');
 }







  public function update_avatar(Request $request){
        // Handle the user upload of avatar

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        $avatarName = $user->id.'_avatar'.time().'.'.request()->photo->getClientOriginalExtension();

        $request->photo->storeAs('/users',$avatarName);

        $user->photo = $avatarName;
        $user->save();




        return back();

      }

    public function update(Request $request){


       $user = Auth::user();

              $user->last_name = $request->input('last_name');
              $user->name = $request->input('first_name');
              $user->adresse = $request->input('adresse');
              $user->country = $request->input('country');
              $user->city = $request->input('city');
              $user->zip = $request->input('zip');
              $user->gender = $request->input('gender');
              $user->date_birth = $request->input('date_birth');
              $user->education = $request->input('education');
              $user->marital_status = $request->input('marital_status');
              $user->about = $request->input('about');

              if (  $request->input('prefered_categories') != null )
                   $user->prefered_categories = implode( "//" , $request->input('prefered_categories') );
             else
                  $user->prefered_categories = $request->input('prefered_categories') ;



             $user->save();





              return back();
                  // ->with('success','You have successfully upload image.');


      }

    public function showChangePasswordForm(){
      $user = Auth::user();
        return view('auth.changepassword',compact( "user"));
    }
    public function changePassword(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        return redirect()->back()->with("success","Password changed successfully !");
    }


      public function deleteAccount(){
      $user = Auth::user();
        return view('auth.deleteaccount',compact( "user"));
    }

    public function userDestroy(Request $request) {

       if (!(Hash::check($request->get('password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your Password is not correct. Please try again.");
        }

      $user = Auth::user();


       Auth::logout();
       

       if ($user->deleted_at=Carbon::now()->toDateTimeString()) {

          $user->save();

          return redirect('/');
    }


       }



}
