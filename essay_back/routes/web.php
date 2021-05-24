<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
   return view('welcome');
});
*/

Route::get('/','FrontController@index');

 // Les routes pour le login avec google et facebook

Route::get('/login/facebook/redirect', 'SocialAuthFacebookController@redirect');
Route::get('/login/facebook/callback', 'SocialAuthFacebookController@callback');
Route::get('/redirectGoogle', 'SocialAuthGoogleController@redirectGoogle');
Route::get('/callbackGoogle', 'SocialAuthGoogleController@callbackGoogle');


Route::get('login',function(){
	return view('passwords.login');
});
Route::get('about', function(){
	return view('about');
});


// Route::get('contact', function(){
// 	return view('contact');
// });
Route::get('/shop', 'CreativeController@index')->name('shop');
Route::post('/shop2', 'CreativeController@clickCreative');
Route::post('/userclick', 'CreativeController@UserActionClick');

 Route::post('newsletter', 'NewsletterController@store');
Route::get('contact', 'ContactController@index')->middleware('xss-protection');
Route::get('/shop/{id}', 'CreativeController@shopSingle');
Route::get('/shop/category/{id}', 'CreativeController@categorySingle');
Route::post('contact', 'ContactController@store');

Route::group(['middleware'=>'no-cache'],function(){
// route vert profile
        Route::get('profile','ProfileController@profile')->name('profile');
        Route::post('profile','ProfileController@update_avatar');
//Profile update
        Route::post('profile/update','ProfileController@update')->name('profileUpdate');
//password
        Route::get('/changePassword','ProfileController@showChangePasswordForm');
        Route::post('/changePassword','ProfileController@changePassword')->name('changePassword');
//deleteAccount
        Route::get('/deleteAccount','ProfileController@deleteAccount');
        Route::post('/deleteAccount','ProfileController@userDestroy')->name('userDestroy');
//ManageNewsletter
//      Route::post('profile/sendNews','ProfileController@newsletter_update')->name('sendNews');
      	Route::get('/manageNewsletter','ProfileController@newsletter_view')->name('manageNewsletter')->middleware('auth');
        Route::post('/newsletterUpdate','ProfileController@newsletter_update')->name('manageNewsletter');            
      //Route:get('/sendNews','ProfileController@sendNews_view')->name('sendNews')->middleware('auth');
//voyager
       // Route::group(['prefix'=> 'admin'], function () {
        //    Voyager::routes();
      //  });



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('assets/{path}', [
        'uses' => 'custom\CustomVoyagerController@assets',
        'as' => 'voyager.assets'
    ])->where('path', '(.*)');
});
  });
  
Route::group(['middleware'=>['verified','no-cache']],function(){
Route::get('profile','ProfileController@profile')->name('profile')->middleware('auth');
Route::get('/changePassword','ProfileController@showChangePasswordForm');
Route::get('/deleteAccount','ProfileController@deleteAccount');
Route::get('/favorite','FavoritesController@index')->name('favorite');
});
Route::post('favorite','FavoritesController@add')->name('creative');
Route::get('/search', 'CreativeController@search')->name('search');

Auth::routes(['verify' => true]);
   Route::post('newsletter', 'NewsletterController@store');
