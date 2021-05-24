<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use  App\Creative;


class FavoritesController extends Controller
{

  public function index()
   {
       $creatives = Auth::user()->favorite_creatives;
       $user = Auth::user();
       return view('Profiles.favorites',compact('creatives' , "user"));
   }
  public function add( Request $request )
    {

        $user = Auth::user();
        $isFavorite = $user->favorite_creatives()->where('creative_id', $request->id)->count();
        if ($isFavorite == 0)
        {
            $user->favorite_creatives()->attach( $request->id);
           // Toastr::success('Creative successfully added to your favorite list :)','Success');

            return [ "toggle" => true  , "message" => "added to favorites" ];
        } else {
            $user->favorite_creatives()->detach($request->id);
            //Toastr::success('Creative successfully removed form your favorite list :)','Success');
            return [ "toggle" => false  , "message" => "remove from favorites" ];
        }
    }

}