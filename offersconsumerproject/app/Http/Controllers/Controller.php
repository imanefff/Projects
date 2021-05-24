<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Helpers;
use CickHelpers;
use HasOfferHelpers;
use InhouseHelpers;
use ClickBoothHelpers;
use EverflowHelpers;

use App\Offer;
use App\CacheOffer;
use App\Advertiser;
use App\Category;
use App\Creative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleXMLElement;

use App\User;

use App\ServerModels\Server;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;


use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test(){

        // return EverflowHelpers::getCreative( Advertiser::find(118) , 2169 , 37 ) ;
        return EverflowHelpers::getCreativelist( Advertiser::find(118) , 2169 , 26 );
    }

    public function entitySwitch( $id ){

        Auth::user()->update(['entity_id' => $id ]);

        return back() ;
    }


    public function OffersUpdateList(Request $req){
        $id      =  $req->id;
        $apiType =  $req->apiType;

            // $dir = '/var/www/html/Projects/offersconsumerproject';
            // $dir = '/var/www/offersconsumerproject';
        $dir = base_path();


        switch($apiType){
            case "hasoffers":
                $p = system("/usr/bin/php $dir/insertCachOffersHasOffers.php $id");
                break;
            case "hitpath" :
                $p = system("/usr/bin/php $dir/insertHitPath.php $id");
                break;
            case "cake" :
                $p = system("/usr/bin/php $dir/insertCasheOffersCake.php $id");
                break;
            case "inhouse" :
                $p =  system("/usr/bin/php $dir/insertCacheInHouse.php $id");
                break;

            case "everflow" :
                $p =  EverflowHelpers::getListOffer( Advertiser::findOrFail($id) );
                break;
            default:
                break;
        }
        //throw new \Exception("$apiType =  $req->apiType;");
        return $p;

    }

    public function getAdvertiser(Request $req){
        if( $req->apiType ==  Advertiser::find($req->id)->api_type){
            $adver = Advertiser::find($req->id);
            if( $adver->api_type == "hitpath"){
                $adver->diffForHumans = Carbon::now()->diffForHumans( Advertiser::find($req->id)->updated_at);
                $adver->diffInMinutes = Carbon::now()->diffInMinutes( Advertiser::find($req->id)->updated_at);
            }
            return $adver;
        }
        return "some thing wrong";
    }


}

