<?php

use Illuminate\Http\Request;

// use Illuminate\Http\Request;

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

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get ( '/data/get'             ,  'OfferController@dataGet'); // to get data from Api function getcampaigns

Route::get ( '/offer/create'         ,  'OfferController@createOffre'); // return page off create offer page 1 of data
// Route::post( '/offer/create'         ,  'OfferController@createOffre'); // return page off create offer page 2 of images
Route::post( '/offer/create/list'    ,  'OfferController@createOffre'); // return page off create offer page 2 of images
// Route::post( '/offer/create'         ,  'Controller@createTest'); // return page off create offer page 2 of images
// Route::get( '/list/Creative'        ,  'OfferController@ListCreatives')->name("listCretives"); // return page off create offer page 2 of images

Route::post( '/advertiser/sid'       ,  'OfferController@getOfferBySid'); // in page 1 comes from ajax return data of sid and fill inputs
Route::post( '/advertiser/accounts'  ,  'OfferController@getAccountsByAdvertiser'); // in page 1 comes from ajax return data of sid fill select od SID
Route::post( '/advertiser/sids'      ,  'OfferController@getSidsByAdvertiserAccounts'); // in page 1 comes from ajax return data of sid fill select od SID
Route::post( '/sid/cack'             ,  'OfferController@getSidByOfferId');


Route::post('/offer/creative/delete' ,  'OfferController@DeleteCreativeOffer' );

Route::post('/CropImage'             ,  'OfferController@cropImage'); // post form cames from editor to crop image screenshot return view crop
Route::post('/crops'                 ,  'OfferController@Caps'); // ajax post for crop image offer and return base64 of new image && save image in public dir with name CropImage.png



Route::get('/xmlTest'                , 'Controller@test'); // this route just for testing functions



Route::get('/imagesTest'             , 'Controller@ImagesTest');


// -------------------------------------

Route::post('/showCreative'          , 'OfferController@getCreative') ;

// -------------------------------------


// Route::post('/showCreativeCake'      , 'OfferController@getCreativeCake') ;
// Route::post('/showCreativeHasOffers' , 'OfferController@getCreativeHasOffers') ;
// Route::post('/showCreativeInHouse'   , 'OfferController@getCreativeInHouse') ;
// Route::post('/showCreativeClickbooth', 'OfferController@getCreativeClickbooth') ;

/* ----
    /showCreativeCake
    /showCreativeHasOffers
    /showCreativeInHouse
    /showCreativeClickbooth
*/

Route::post('/creativesChoosen'      , 'OfferController@editorCretivesChoosen');


Route::post('/creativeImage/save'    , 'OfferController@saveImages');

Auth::routes();

Route::get('/home'                   , 'HomeController@index')->name('home');

Route::post('saved/Images'           , 'OfferController@editorCretivesChoosen');


// detele offer
Route::post('offer/delete'           , 'OfferController@deleteOffer');


Route::get('/offer/{id}'             , 'OfferController@showOffer');

Route::get('img/{entity}/{filename}' , 'OfferController@routeImages');


Route::get( '/offer/update/{id}'     , 'OfferController@updateOffer');
Route::post('/offer/Update/{id}'     , 'OfferController@updateOffer');

Route::get('/offer/edit/{id}'        , 'OfferController@editOffer');
Route::post('/offer/edit/{id}'       , 'OfferController@editOffer');

Route::get('/offer/already/edit/{id}' , 'OfferController@editAlreadyAddedOffer');
Route::post('/offer/already/edit/{id}' , 'OfferController@editAlreadyAddedOffer');
Route::get('/form/hawk/{id}'          , 'OfferController@hawkIdForm');





// Route::get('/tt'                     , 'Controller@test')->middleware('adminPermession');
Route::get('/tt'                     , 'Controller@test');



Route::get('/entity/switch/{id}'                    , 'Controller@entitySwitch');


Route::get('/'                       , 'DataTableController@index');
// Route::get('/', 'DataTableController@index')->middleware('adminPermession');
Route::get('offers/getdataTable'     , 'DataTableController@getDataTable');

// Route::post('/offerTohawk','Controller@ps');


// Route::get('/users/list'             , 'DataTableController@usersListes')->middleware('adminPermession');
Route::get('/users/list'             , 'DataTableController@usersListes')->middleware('managerPermession');
// Route::get('/', 'DataTableController@index')->middleware('adminPermession');
// Route::get('/users/list/data'        , 'DataTableController@getUsersDataTable')->middleware('adminPermession');
Route::get('/users/list/data'        , 'DataTableController@getUsersDataTable')->middleware('managerPermession');


Route::get('/tttt','CakeOffers@getCackOffer');
Route::get('/t2','CakeOffers@ppp');
Route::get('/t3','CakeOffers@ppps');


route::get('/noCreative',function(){
    return view('offers.NoCreative');
});


// Route::get('/thisss',function(){
//     return "aaa";
// })->name('thhs');

Route::post('/creative/delete','OfferController@deleteCreatives');


Route::post("/offers/listUpdate","Controller@OffersUpdateList");
Route::post("/Advertiser/listUpdate/data","Controller@getAdvertiser");


Route::post("/get/code64","OfferController@getBase64");

// Route::get('/delete/offer/creatives/{id}','OfferController@DeleteOfferAdmin')->middleware('adminPermession');
Route::get('/delete/offer/creatives/{id}','OfferController@DeleteOfferAdmin')->middleware('managerPermession');



// --------------------------------- Servers -----------------------------------


Route::get( '/server/commend'           , 'ServerController@index' );
Route::get( '/server/timeline'          , 'ServerController@timeline' );
Route::post('/server/history'           , 'ServerController@history' );
Route::post('/server/getHestory'        , 'ServerController@getHestory' );

Route::post('/server/action'            , 'ServerController@serverAction' );
Route::post('/server/execute'           , 'ServerController@serverExecute' );
Route::post('/update/list/server'       , 'ServerController@updateListServer' );

Route::post('/server/add'               , 'ServerController@serverAdd' );
Route::post('/server/user/all'          , 'ServerController@serverUserAll');
Route::post('/server/user/desasosiete'  , 'ServerController@serverUserDesasosiete');

Route::post("/isp/add"                  , 'ServerController@addIsp' );
Route::post("/isp/list"                 , 'ServerController@ispList' );


// -------------------------------- Auth Routes ---------------------------------

Route::get('register'                , 'Auth\RegisterController@showRegistrationForm')->name('register');

Route::get('/auth/success', [
    'as'   => 'auth.success',
    'uses' => 'Auth\RegisterController@success'
]);

// Route::get("Enity" , "")

