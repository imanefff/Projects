<?php

namespace App\Http\Controllers;

use CickHelpers;
use App\CacheOffer;
// use App\Helpers\CickHelpers;
use Illuminate\Http\Request;
use App\Advertiser;

class CakeOffers extends Controller
{
    public function getCackOffer(){
         $advertisers =  Advertiser::where("api_type","cake")->get();

        // foreach($advertisers as $advertiser ){
        //     $offersOfAdvertiser[] = CickHelpers::getCakeListOffers($advertiser);
        // }




        // foreach($offersOfAdvertiser as $offers){
        //     foreach($offers as $offer ){
        //         if($offer["case"] == "Active"){
        //             $off = new CacheOffer;
        //             $off->sid            = $offer["sid"];
        //             $off->name           = $offer["name"];
        //             $off->description    = $offer["description"];
        //             $off->payout         = $offer["payout"];
        //             $off->unit           = $offer["unit"];
        //             $off->daysleft       = $offer["daysleft"];
        //             $off->category       = $offer["category"];
        //             $off->geotargeting   = (strlen($offer["geotargeting"])>60)?"all":$offer["geotargeting"] ;
        //             $off->advertiser_id  = $offer["advertiser_id"];
        //             $off->save();

        //         }
        //     }
        // }
            foreach($advertisers as $advertiser){
                foreach($advertiser->cacheoffres as $cache){
                    if( CickHelpers::chackOffersActive($advertiser,$cache["sid"]) == "paused"){ $cache->delete(); $caches[]=$cache["sid"];}
                }
            }
        return $caches;
        // chackOffersActive(Advertiser $adv,$sid)

        // $advertisers[0]->cacheoffres;

    }





}
