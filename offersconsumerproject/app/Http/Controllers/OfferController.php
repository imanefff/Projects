<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Helpers;
use CickHelpers;
use HasOfferHelpers;
use InhouseHelpers;
use ClickBoothHelpers;
use EverflowHelpers;


use App\CacheOffer;
use App\Offer;
use App\Creative;
use App\CreativeImage;
use App\Advertiser;
use App\Category;
use Auth;

use Illuminate\Support\Facades\File;
use Image;

use Illuminate\Database\QueryException;

class OfferController extends Controller
{



    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public function __construct()
     {
         $this->middleware('auth');
     }


    /**
     * show offer details
     * Route::get('offer/{id}','OfferController@showOffer');
     */
    public function showOffer($id){
        $offer = Offer::findOrFail($id);
        return view('offers.showOffer',compact('offer'));

    }
    /**
     *
     * redirect link image
     * get real path of image and return it like a link
     * Route::get('img/{entity}/{filename}', 'OfferController@routeImages');
     */


    public function routeImages ($entity,$filename)
    {
      //  return Image::make(storage_path('app\public\offers/images/'.$entity.'/' . $filename))->response();
	    return Image::make('storage/offers/images/'.$entity.'/' . $filename)->response();
    }


    /**
     * post ajax in /offer/create fill select Sid trought choise of Advertiser
     * Route::post( '/advertiser/accounts'  ,  'OfferController@getAccountsByAdvertiser');
     */

    public function getAccountsByAdvertiser(Request $request)
    {
        return Auth::user()->entity->advertisers()
                                    ->select('id','affiliate_id','api_type')
                                    ->where([
                                            ['name',$request->advertiser] ,
                                            ['status',1]
                                        ])
                                    ->get();
    }

    /**
     * ajax post return sids list
     * Route::post( '/advertiser/sids'      ,  'OfferController@getSidsByAdvertiserAccounts');
     */

    public function getSidsByAdvertiserAccounts(Request $request){
        return Advertiser::find($request->advertiser)->cacheoffres()->where('is_added',0)->get();
    }

    /**
     * ajax post -- this function if i choose cake api type
     * return offer id instead of Sid (عوضا عن)
     * Route::post( '/sid/cack'  ,  'OfferController@getSidByOfferId');
     */

    public function getSidByOfferId(Request $request){

        return Advertiser::find($request->advertiser)->cacheoffres()
                ->where([
                            ['is_added',0],
                            ['offer_id',$request->affiliet]
                        ])->get();
    }

    /**
     * post ajax after click button get offer fill all input of form throught data
     * return by this function
     * Route::post( '/advertiser/sid'       ,  'OfferController@getOfferBySid');
     */
    public function getOfferBySid ( Request $request ) {

        // $offers = Advertiser::find($request->advertiser)->cacheoffres()->select("sid")->where('is_added',0)->get();
        $advertiser = Advertiser::find( $request->advertiser );
        $offersCount = $advertiser->cacheoffres()->select("sid")->where([ [ 'is_added', 0 ] , [ 'sid' , $request->sid ] ])->count();

        // return $offers ;

        // foreach( $offers as $sid)
        //     $sids[] = $sid->sid;

        // if( !in_array($request->sid, $sids ) )
        //     return "This Sid not exist";

        if(  $offersCount == 0  ) return "This Sid not exist";



        // return $advertiser ;

        // if ( $advertiser->api_type == "hitpath" )
        //     {
        //         $arrayDet = Helpers::hitpathAdvNew( $advertiser , $request->sid );
        //         return Helpers::TraitString( $arrayDet , $advertiser );
        //     }
        // else if ( $advertiser->api_type == "cake"       )  return CickHelpers::cickpathAdv($advertiser,$request->sid);
        // else if ( $advertiser->api_type == "hasoffers"  )  return HasOfferHelpers::hasOffersResponce($advertiser,$request->sid);
        // else if ( $advertiser->api_type == "inhouse"    )  return InhouseHelpers::inHouseResponce($advertiser,$request->sid);
        // else if ( $advertiser->api_type == "clickbooth" )  return ClickBoothHelpers::clickBoothResponce($advertiser,$request->sid);

        switch ( $advertiser->api_type ){

            case "hitpath"    :
                $arrayDet = Helpers::hitpathAdvNew( $advertiser , $request->sid );
                return Helpers::TraitString( $arrayDet , $advertiser );
                break ;
            case "cake"       : return CickHelpers::cickpathAdv($advertiser,$request->sid); break ;
            case "hasoffers"  : return HasOfferHelpers::hasOffersResponce($advertiser,$request->sid); break ;
            case "inhouse"    : return InhouseHelpers::inHouseResponce($advertiser,$request->sid); break ;
            case "clickbooth" : return ClickBoothHelpers::clickBoothResponce($advertiser,$request->sid);break ;
            case "everflow"   : return EverflowHelpers::getOfferDetails( $advertiser,$request->sid ) ; break ;

        }




    }


    /**
     * create offer and save it in database
     * if get return view page 1
     * if post save in database offer cames from page 1 and save creative (email body) and image of creative
     * and return page 2 with list of images
     * get  -- Route::get ( '/offer/create'         ,  'OfferController@createOffre');
     * post -- Route::post( '/offer/create/list'    ,  'OfferController@createOffre');
     */

    public function createOffre(Request $request){

        if($request->isMethod("post")){

            $ofr = Offer::where([
                ['advertiser_id',  $request->advertiser],
                ['sid',  $request->sid],
                ['is_deleted',  0]
            ])->get();

                if (count($ofr) > 0) {
                    session()->flash('failedAdd', 'Maybe this offers  added before! ..');
                    return back();
                }

                $advertiser = Advertiser::find($request->advertiser);
                $url        = $advertiser->url;
                $apikey     = $advertiser->api_key;

        try {

            $offr = new Offer;
            $offr->advertiser_id      = $request->advertiser;
            $offr->sid                = $request->sid;
            $offr->name               = $request->name;
            $offr->offer_url          = $request->offerURL;
            $offr->preview_url        = $request->previewURL;
            $offr->unsubscribe_url    = $request->unsubscribeURL;
            $offr->categories         = $request->categories;
            $offr->description        = $request->description;
            $offr->type               = $request->payoutType;
            $offr->default_payout     = $request->defaultPayout;
            $offr->from_lines         = $request->fromLines;
            $offr->subject_lines      = $request->subjectLines;
            $offr->level              = $request->level;
            $offr->countries          = $request->countries;
            $offr->suppression_link   = $request->suppressionLink;
            $offr->suppression_link_2 = Helpers::getRealSuppressionLink( $request->suppressionLink );
            $offr->entity_id          = Auth::user()->entity["id"];
            $offr->user_id            = Auth::user()["id"];
            $offr->is_deleted         = 0 ;
            $offr->save();

            $offerId = $offr->id;

            $cash = $advertiser->cacheoffres()->where("sid",$request->sid)->get()->last();
            $cash->is_added = 1;
            $cash->save();

            session()->flash('success', 'successful adding!');
            } catch (QueryException $e) {
                if($e->errorInfo[0]==23000 && $e->errorInfo[1] == 1062)
                    session()->flash('failed', 'adding failed! -- this Offer added before ');
                else
                    ession()->flash('failed', 'adding failed! '.$e->errorInfo[0]." ".$e->errorInfo[1]." ".$e->errorInfo[2]);
            }

            switch( $advertiser->api_type){
                case 'hitpath':
                    $creatives = Helpers::getCreativesList( $advertiser , $request->sid , $offr->id );
                    break;
                case  'cake':
                    $creatives = CickHelpers::getCreatives( $advertiser , $request->sid , $offr->id );
                    break;
                case 'hasoffers':
                    $creatives = HasOfferHelpers::getCreatives( $advertiser, $request->sid , $offr->id );
                    break;
                case 'inhouse' :
                    $creatives = InhouseHelpers::getCreatives( $advertiser , $request->sid , $offr->id );
                    break;
                case 'clickbooth':
                    $creatives = ClickBoothHelpers::getCreativeList( $advertiser, $request->sid , $offr->id );
                    break;

                case 'everflow' :
                    $creatives  = EverflowHelpers::getCreativelist( $advertiser, $request->sid , $offr->id );
                    break ;
            }

            if( count($creatives) ==0 ) return view('offers.NoCreative');
            else return view('offers.creatives',compact('creatives','offerId'));

        }




        // if get return view page 1 with list of advertiser

        $advertisers = Auth::user()->entity->advertisers()->select('name')->groupBy('name')->get();
        foreach (Category::select("name")->get() as $cat){
            $categories[] = $cat->name;
        }
        return view('offers.create' , compact('advertisers' , 'categories'));
        // return view('offers.create')->with('advertisers', $advertisers);
    }

    /**
     * ajax post  , used to show creative in choose crative table and capture page editor ==> hitpath
     * Route::post('/showCreative'          , 'OfferController@getCreative') ;
     */

    public function getCreative(Request $req){

        $advertiser = Offer::find($req->offerId)->advertiser;
        $sid        = Offer::find($req->offerId)->sid;

        // return [ $advertiser , $sid , $req->all() ];

        switch( $advertiser->api_type) {

            case 'hitpath'   :
                return  Helpers::getCreative( $req->offerId , $req->creativeId );
                break;
            case 'cake'      :
                $name = $req->creativeId."_".CickHelpers::getNameCreative($req->creativeId);
                return  InhouseHelpers::imagesReplaceBase64(  CickHelpers::getHtml($name) );
                break;
            case 'hasoffers' :
                return  InhouseHelpers::imagesReplaceBase64( HasOfferHelpers::getCreative( $advertiser , $sid  ,$req->creativeId) );
                break;
            case 'inhouse'   :
                return  InhouseHelpers::imagesReplaceBase64( InhouseHelpers::getCreative( $advertiser , $sid ,$req->creativeId) );
                break;
            case 'clickbooth':
                return  InhouseHelpers::imagesReplaceBase64(  ClickBoothHelpers::getCreative( $advertiser , $sid ,$req->creativeId) );
                break;

            case "everflow" :
                return  InhouseHelpers::imagesReplaceBase64( EverflowHelpers::getCreative( $advertiser , $sid ,$req->creativeId) );
                break;

        }

    }



    /**
     * this function of crop page
     * Route::post('/creativesChoosen'      , 'OfferController@editorCretivesChoosen');
     */

    public function editorCretivesChoosen(Request $req){

        $disabledArray[] = null;

        $sid        = $req->sid;
        $offerId    = $req->offerId;
        $creatives  = $req->creativeIds;
        $offer      = Offer::find($req->offerId);
        $advertiser = $offer->advertiser;

        // return $advertiser ;

        if(isset($req->disabledArray)) $disabledArray = $req->disabledArray;

        else{
            foreach($creatives as $key => $creative){

                if(Helpers::findItem($creative,$offer->creatives->where('is_deleted',0)) != -1){

                    $crtv = Creative::find(Helpers::findItem($creative,$offer->creatives->where('is_deleted',0)));

                }else{

                    $crtv = new Creative;
                }

                $crtv->email_id = $creative;

                /*
                if($advertiser->api_type == "cake")
                $crtv->name = CickHelpers::getNameCreative($creative);
                else if ($advertiser->api_type == "hasoffers")
                $crtv->name = HasOfferHelpers::getNameCreative( $advertiser , $sid , $creative);
                else if ($advertiser->api_type == "inhouse")
                $crtv->name = 'CR'.strval ($key+1);
                else if ($advertiser->api_type == "clickbooth")
                $crtv->name = ClickBoothHelpers::getNameCreative( $advertiser , $sid , $creative);
                else if ( $advertiser->api_type == "everflow" )
                $crtv->name = EverflowHelpers::getNameCreative( $advertiser , $sid , $creative);
                else
                $crtv->name = Helpers::getEmailRaw( $advertiser->url , $advertiser->api_key , $sid , $creative )["description"];
                */

                switch( $advertiser->api_type ){

                    case "cake" :
                        $crtv->name = CickHelpers::getNameCreative($creative);
                        break;
                    case "hasoffers" :
                        $crtv->name = HasOfferHelpers::getNameCreative( $advertiser , $sid , $creative);
                        break;
                    case "inhouse" :
                        $crtv->name = 'CR'.strval ($key+1);
                        break;
                    case "clickbooth" :
                        $crtv->name = ClickBoothHelpers::getNameCreative( $advertiser , $sid , $creative);
                        break;
                    case "everflow" :
                        $crtv->name = EverflowHelpers::getNameCreative( $advertiser , $sid , $creative);
                        break;
                    default :
                        $crtv->name = Helpers::getEmailRaw( $advertiser->url , $advertiser->api_key , $sid , $creative )["description"];
                        break;

                }


                $crtv->offer_id = $offer->id;

                $crtv->save();
            }
        }

        if(isset($req->disabledArray)) if(count($req->disabledArray) ==  count($creatives)  ) return redirect("/offer/".$offer['id']);
        return view('layouts.editor2',compact( 'creatives' , 'offerId' , 'sid' , 'disabledArray'));
    }



    /**
     * return image sreenshot with a GRAYSCALE filter (white/black filter)
     * form ==> post (from editor view )
     */

    public function cropImage(Request $req){

        // return $req;
        $offerId = $req->offerId;
        $disbled = $req->disabledArray;
        $sid = $req->sid;
        $creativeArray = $req->creativeArray;
        $emailId =$req->emailId;
        $img = $req->img;
        $filename = $req->img;
        $str="data:image/jpeg;base64,";
        $filename=str_replace($str,"",$filename);
        $str="data:image/png;base64,";
        $filename=str_replace($str,"",$filename);
        $filename =  base64_decode($filename);
        $im = imagecreatefromstring($filename);
        imagefilter($im, IMG_FILTER_GRAYSCALE);
        // imagepng($im, '.\plugins\Jcrop\image\back.png',9);
        imagepng($im, './storage/offers/imageTemp/JCrop/back.png',9);

        return view( 'offers.crop', compact('img' , 'emailId' , 'creativeArray' , 'sid' , 'offerId' , 'disbled'));
    }

    /**
     * crop image and save it in public dir  and return base64 of new image
     * ajax ==> post (from crop)
     * Route::post('/crops'                 ,  'OfferController@Caps');
     */
    public function Caps(Request $req)
    {
        // decode image file from base 64 to binary (string form)

        $filename = $req->img;
        $str="data:image/jpeg;base64,";
        $filename=str_replace($str,"",$filename);
        $str="data:image/png;base64,";
        $filename=str_replace($str,"",$filename);
        $filename =  base64_decode($filename);

         // create  temp image from string (binary code)
        $im = imagecreatefromstring($filename);

         // if the image open
        if ($im !== false){
            header('Content-Type: image/png');

        // resize image
            // create img temp vide with width and height passd in request
            $newImage = imagecreatetruecolor($req->w, $req->h);
            //copy content selectet by indises passed in req to $newImage
            imagecopyresampled($newImage, $im, 0, 0, $req->x, $req->y, $req->w , $req->h ,$req->w , $req->h );

            //save $newImage from temp to public dir
            imagepng($newImage, 'storage/offers/imageTemp/Creatives/CropImage.png',9);

            //convert image file to base64
            $file = file_get_contents('storage/offers/imageTemp/Creatives/CropImage.png');
            $file=  base64_encode($file);
            $file= $str.$file;
            unlink('storage/offers/imageTemp/Creatives/CropImage.png');
            return ["img" => $file , "cordone" => [ "x" => $req->x,"y" => $req->y, "width" => $req->w , "height" => $req->h]] ;
        }
        else
            return "false";
    }

    /**
     * post ajax : this function save image creative choosed .
     * Route::post('/creativeImage/save'    , 'OfferController@saveImages');
     */

    public function saveImages(Request $req){

        $offer = Offer::find($req->offerId);
        $creative = $offer->creatives()->where([ ['email_id',$req->creative] , ["is_deleted", 0] ])->first();

        $path =  $offer->advertiser->entity->path;;
        $etat=null;

        foreach($req->imgs as $key => $image){
            $filename = $image;
            $str="data:image/png;base64,";
            $filename=str_replace($str,"",$filename);
            $str="data:image/jpeg;base64,";
            $filename=str_replace($str,"",$filename);
            $filename =  base64_decode($filename);


            $im = imagecreatefromstring($filename);
            if ($im !== false){
                header('Content-Type: image/png');
                // imagepng( $im , 'storage/offers/images/'.$path.'/'.$creative->offer["id"].'_'.$creative["id"].'_'.($key+1).'.png',9);
                imagepng( $im , 'storage/offers/images/'.$path.'/'.$creative->offer["id"].'_'.$creative["id"].'_'.($key+1).'.png',9);
                // imagejpeg($im , 'storage/offers/images/'.$path.'/'.$creative->offer["id"].'_'.$creative["id"].'_'.($key+1).'.jpg', 100);

                imagedestroy($im);
                $savaImageDb = new CreativeImage;
                $savaImageDb->creative_path = 'storage/offers/images/'.$path.'/'.$creative->offer["id"].'_'.$creative["id"].'_'.($key+1).'.png';
                $savaImageDb->creative_url = 'img/'.$path.'/'.$creative->offer["id"].'_'.$creative["id"].'_'.($key+1).'.png';
                $savaImageDb->creative_id = $creative["id"];
                $savaImageDb->save();

                $etat[$key] = "added" ;

            }else $etat[$key] = "not added" ;

        }
        return $etat;
    }

    /**
     * this function delete offer from list
     * Route::post('offer/delete'  , 'OfferController@deleteOffer');
     */

    public function deleteOffer(Request $req){

        $offer =  Offer::find($req->id);
        $offer->is_deleted = 1;
        $offer->status = 0;
        $offer->save();

        $cashe =  CacheOffer::where("sid",$offer->sid)->first();
        $cashe->is_added = 0;
        $offer->save();

        return "added";
    }

    /**
     * this function update offers :: retrun data details of offer online
     * renewal(تجديد) data offer
     * Route::get('/offer/update/{id}'      , 'OfferController@updateOffer');
     * Route::post('/offer/Update/{id}'     , 'OfferController@updateOffer');
     */

    public function updateOffer($id,Request $req){

        if($req->isMethod("post")){

            $alreadyCreatives = null;
            $alredyCrtv = Offer::find($req->offerId)->creatives()->select('email_id')->where("is_deleted",0)->get();
            // Offer::find($req->offerId)->creatives()->select('email_id')->where("is_deleted",0)->get()
            foreach( $alredyCrtv as $creative ){
                $alreadyCreatives[] = $creative['email_id'];
            }

            try{

                $offer = Offer::find($req->offerId);
                $offer->name             = $req->name;
                $offer->offer_url        = $req->offerURL;
                $offer->preview_url      = $req->previewURL;
                $offer->unsubscribe_url  = $req->unsubscribeURL;
                $offer->categories       = $req->categories;
                $offer->description      = $req->description;
                $offer->type             = $req->payoutType;
                $offer->default_payout   = $req->defaultPayout;
                $offer->from_lines       = $offer->from_lines.'\n'.$req->fromLines;
                $offer->subject_lines    = $offer->subject_lines.'\n'.$req->subjectLines;
                $offer->level            = $req->level;
                $offer->countries        = $req->countries;
                $offer->suppression_link = $req->suppressionLink;
                $offer->save();

                session()->flash('success', 'successful updated!');

            } catch (QueryException $e) {
                if($e->errorInfo[0]==23000 && $e->errorInfo[1] == 1062)
                session()->flash('failed', 'adding failed! ');
            else
                session()->flash('failed', 'adding failed! '.$e->errorInfo[0]." ".$e->errorInfo[1]." ".$e->errorInfo[2]);
            }

            $advertiser = Offer::find($req->offerId)->advertiser;

        switch ($advertiser->api_type) {
            case 'hitpath':
                $creatives = Helpers::getCreativesList( $advertiser , $req->sid , $offer->id );
                break;
            case 'cake':
                $creatives = CickHelpers::getCreatives( $advertiser , $req->sid , $offer->id );
                break;
            case 'hasoffers':
                $creatives = HasOfferHelpers::getCreatives( $advertiser , $req->sid , $offer->id );
                break;
            case 'inhouse' :
                $creatives = InhouseHelpers::getCreatives( $advertiser , $req->sid  , $offer->id);
                break;
            case 'clickbooth' :
                $creatives = ClickBoothHelpers::getCreativeList( $advertiser , $req->sid ,  $offer->id);
                break;
        }

        // return $creatives;
            $offerId = $offer->id;

            if(count($creatives)==0)
            return view('offers.NoCreative');
            else
            return view('offers.creatives',compact('creatives','offerId'));

        }


        $offer = Offer::findOrFail($id);

        if( $offer->is_added == 1 && $offer->status == 1)
        return redirect('/offer/already/edit/'.$id);

        $ofrUrl = $offer ["offer_url"];

        $advertiser = $offer->advertiser;

        $url =  $advertiser->url;
        $apikey = $advertiser->api_key;
        $sid = $offer->sid;

        $cachOffer = $advertiser->cacheoffres()->where( 'sid' , $sid )->get();

        if($advertiser->api_type == "hitpath") {

            $responce =  Helpers::hitpathResponce( $advertiser ,  $sid );

            if( $responce == null )
                return view('offers.NoOfferDetails');

            $offer["offer"]        = $responce["cache"];
            $offer['SupLink']      = $responce["SuppressionLink"];
            $offer['fromlines']    = $responce["fromlines"];
            $offer['offlnk']       = null;
            $offer['subjects']     = $responce["subjects"] ;
            $offer['links']        = $responce["links"] ;
            $offer["preview_link"] = null;
        }
        else
        {
            switch ($advertiser->api_type) {
                case "hasoffers":
                    $responce =  HasOfferHelpers::hasOffersResponce( $advertiser ,  $sid);
                    break;
                case "cake":
                    $responce = CickHelpers::cickpathAdv( $advertiser ,  $sid );
                    break;
                case "inhouse":
                    $responce = InhouseHelpers::inHouseResponce( $advertiser ,  $sid);
                    break;
                case "clickbooth":
                    $responce = ClickBoothHelpers::clickBoothResponce( $advertiser ,  $sid);
                    break;
                }

            $offer["offer"]        = $responce["cache"];
            $offer['SupLink']      = $responce ["SupLink"];
            $offer['UnsubLink']    = $responce ["UnsubLink"];
            $offer['fromlines']    = $responce ["fromlines"];
            $offer['subjects']     = $responce ["subjects"];

            if( $advertiser->api_type == "clickbooth" || $advertiser->api_type == "inhouse" )
                $offer["preview_link"] = null;
            if( $advertiser->api_type == "cake" || $advertiser->api_type == "hasoffers" )
                $offer["preview_link"] = $responce ["preview_link"];

            if ( $advertiser->api_type == "hasoffers" ){
                $offer['offlnk']       = $responce ["offerlnk"];
                $offer['links']        = $responce ["links"];
            }else{
                $offer['offlnk']       = null;
                $offer['links']        = $responce ["offerlnk"];
            }
        }

        $offer["id"]           = $id;
        $offer['urlToAdd']     = $advertiser->url_to_add;
        $offer['apiType']      = $advertiser->api_type;
        $offer["advertiser"]   = $advertiser;



        foreach (Category::select("name")->get() as $cat){
            $categories[] = $cat->name;
         }

        // return $offer;
        return view('offers.UpdateOffer',compact('offer','categories'));
    }


    /**
     * this function get data from daba base and change it
     * update daba of database
     * Route::get('/offer/edit/{id}'        , 'OfferController@editOffer');
     * Route::post('/offer/edit/{id}'       , 'OfferController@editOffer');
     */

   public function editOffer($id,Request $req){

    if($req->isMethod("post")){


        $alreadyCreatives = [];
        $alredyCrtv = Offer::find($req->offerId)->creatives()->select('email_id')->where("is_deleted",0)->get();
        foreach($alredyCrtv as $creative)
            $alreadyCreatives[] = $creative['email_id'];

        try{

            $offer = Offer::find($req->offerId);
            $offer->name             = $req->name;
            $offer->offer_url        = $req->offerURL;
            $offer->preview_url      = $req->previewURL;
            $offer->unsubscribe_url  = $req->unsubscribeURL;
            $offer->categories       = $req->categories;
            $offer->description      = $req->description;
            $offer->type             = $req->payoutType;
            $offer->default_payout   = $req->defaultPayout;
            $offer->from_lines       = $req->fromLines;
            $offer->subject_lines    = $req->subjectLines;
            $offer->countries        = $req->countries;
            $offer->suppression_link = $req->suppressionLink;

            $offer->save();

            session()->flash('success', 'successful update!');

        } catch (QueryException $e) {

            if($e->errorInfo[0]==23000 && $e->errorInfo[1] == 1062)
                session()->flash('failed', 'update failed! ');
            else
                session()->flash('failed', 'update failed! '.$e->errorInfo[0]." ".$e->errorInfo[1]." ".$e->errorInfo[2]);
       }

       $advertiser = Offer::find($req->offerId)->advertiser;

        switch($advertiser->api_type) {
            case "hitpath":
                $creatives = Helpers::getCreativesList( $advertiser , $offer->sid ,$offer->id );
                break;
            case 'cake':
                $creatives = CickHelpers::getCreatives( $advertiser , $offer->sid , $offer->id );
                break;
            case 'hasoffers' :
                $creatives = HasOfferHelpers::getCreatives( $advertiser , $offer->sid , $offer->id );
                break;
            case 'inhouse':
                $creatives = InhouseHelpers::getCreatives( $advertiser , $offer->sid , $offer->id );
                break;
            case 'clickbooth':
                $creatives = clickBoothHelpers::getCreativeList( $advertiser , $offer->sid , $offer->id );
            break;
        }


        $offerId = $offer->id ;
        if(count($creatives)==0)
        return view('offers.NoCreative');
        else
        return view('offers.creatives',compact('creatives','offerId'));
    }


        $offer = Offer::find($id);
        if($offer->advertiser->api_type == "hitpath")
            $links =  Helpers::getLinks($offer->advertiser,$offer->sid);
        else if($offer->advertiser->api_type == "inhouse")
            $links =  InhouseHelpers::getLinks($offer->advertiser,$offer->sid);
        else if($offer->advertiser->api_type == "hasoffers")
            $links =  HasOfferHelpers::getLinks($offer->advertiser,$offer->sid);
        else if($offer->advertiser->api_type == "cake")
            $links =  CickHelpers::getLinks($offer->advertiser,$offer->sid);
        else if($offer->advertiser->api_type == "clickbooth")
            $links =  ClickBoothHelpers::getLinks($offer->advertiser,$offer->sid);

        if(!is_array($links))$links=[$links];

           foreach (Category::select("name")->get() as $cat){
                $categories[] = $cat->name;
            }

       return view('offers.EditOffer',compact("links",'offer','categories'));
   }

    public function editAlreadyAddedOffer($id,Request $req){

        if($req->isMethod("post")){

            $offer = Offer::find($id);
            $offer->status = 0;
            $offer->hawk_id = $req->hawkId;
            $offer->save();
            return redirect('/offer/update/'.$id);
            }

         $offer = Offer::find($id);

         if($offer->hawk_id==null)
            return redirect('/form/hawk/'.$id);
         else{

            $offer = Offer::find($id);
            $offer->status = 0;
            $offer->save();

            return redirect('/offer/update/'.$id);
        }
    }


    public function hawkIdForm($id){
        return view('offers.hawkId')->with( 'offer' , Offer::find($id) );
    }

   /**
    * this function remove creative empty offers in list creatives
    * Route::post('/offer/creative/delete' ,  'OfferController@DeleteCreativeOffer' );
    */
    // TODO  change this monday images creative delete
   public function DeleteCreativeOffer(Request $req){

       $creative =  Offer::find($req->offerId)->creatives()->where([['email_id',$req->id] , ["is_deleted" , "0"]])->get()[0]->id;
       $crt = Creative::find($creative);
       $crt->is_deleted = 1;
       $crt->save();
      return "Deleted";
   }

   public function deleteCreatives(Request $req){

        $crtv =  Creative::find($req->id);
        $crtv->is_deleted = 1;
        $crtv->save();
    //     return count($crtv->creativeimages);
    //     if()
    //    // $crtv->creativeimages->each()->delete();
    //     $crtv->delete();

        return 'deleted' ;
   }

   /**
    * this function return base64 of image
    * Route::post("/get/code64","OfferController@getBase64");
    */
   public function getBase64(Request $req){

        $binary = file_get_contents($req->url);
        $base = base64_encode($binary);
        $mime = getimagesizefromstring($binary)["mime"];
        $str="data:".$mime.";base64,";
        $img = $str.$base;
        return $img;

   }

   public function DeleteOfferAdmin($id){

       foreach(Offer::find($id)->creatives as $creative){
           $creative->creativeimages()->delete();
           $arr[] = $creative->creativeimages;
       }

       Offer::find($id)->creatives()->delete();
       Offer::find($id)->creatives;
       Offer::find($id)->delete();
       return redirect()->back();
   }


}
