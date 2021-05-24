<?php

use App\CacheOffer;
use App\Advertiser;
use App\Offer;



class HasOfferHelpers{

    public static function getCachOffers(){


        $url ="https://gwm2.api.hasoffers.com/Apiv3/json?api_key=a08d1218e62e4737876ceef1e5701f962f9b35fac083919bc459e967789f73fe&Target=Affiliate_Offer&Method=findMyApprovedOffers";
        set_time_limit(0);
        $file = file_get_contents($url);
        $cache = json_decode($file , true) ["response"]["data"];


        foreach ($cache as $k => $off){
            $sids[] = $k;
        }

        $categories = self::getCategory( $sids );


        foreach ($cache as $k => $off){

           $offerCache =  $off["Offer"];

                $offer["sid"]           = $offerCache ["id"];
                $offer["name"]          = $offerCache ["name"];
                $offer["description"]   = $offerCache ["description"];
                $offer["payout"]        = $offerCache ["default_payout"];
                $offer["unit"]          = $offerCache ["payout_type"];
                $offer["category"]      = $categories[ $offer["sid"]];
                $offer["preview_url"]   = $offerCache ["preview_url"];

                $offers [] = $offer;
        }

        return $offers ;

    }


    private static function getCategory($sids){

      //  return $sids;

        $url = "https://gwm2.api.hasoffers.com/Apiv3/json?api_key=a08d1218e62e4737876ceef1e5701f962f9b35fac083919bc459e967789f73fe&Target=Affiliate_Offer&Method=getCategories";
      // &ids[]=$sid";

      foreach($sids as $sid){
        $url.="&ids[]=$sid";
      }
        $file = file_get_contents($url);
        $cache = json_decode($file , true)["response"]["data"];
       // $cats[] = null;
       foreach( $cache as $categories){

            $cat = "";
            foreach( $categories["categories"]  as $category){
                $cat.=",".$category["name"];
            }
           $cats[$categories["offer_id"]] =  ltrim($cat,",");
        }

       return  $cats;
    }

    private static function getGeoTargeting($sid){

    $url = "https://gwm2.api.hasoffers.com/Apiv3/json?api_key=a08d1218e62e4737876ceef1e5701f962f9b35fac083919bc459e967789f73fe&Target=Affiliate_Offer&Method=getGeoTargeting&id=$sid";

    $file = file_get_contents($url);

    $cachs = json_decode($file,true)["response"]["data"]["Countries"];
    $geos = "";
    foreach($cachs as $cache){
        $geos.=",".strtolower($cache["code"]);
    }

    return ltrim($geos,",");
}

    public static function hasOffersResponce(Advertiser $advertiser,$sid){
        $api_url = $advertiser->url;
        $api_key = $advertiser->api_key;

        $url = $api_url."$sid?apiKey=$api_key";

        $off ['cache'] = $advertiser->cacheoffres()->where('sid',$sid)->get()[0];

        $file  = file_get_contents($url);
        $offer = json_decode($file , true);

        $off ['cache']['geotargeting']  = self::getGeoTargeting($sid);

        $off ['preview_link']  =  $off ['cache']["preview_url"];
        $off ['UnsubLink']     =  $offer["optOutLink"];
        $off ['SupLink']       =  $offer["suppressionFile"];
        $off ['offerlnk']      =  $offer["trackingLink"];
        $off ['fromlines']     =  explode("\n",$offer["emailInstructionsFrom"]);
        $off ['subjects']      =  explode("\n",$offer["emailInstructionsSubject"]);
        $off ['links']         =  null;
        $off ['urlToAdd']      =  $advertiser->url_to_add;

        return $off;


    }
    // explode("\n",$creative['subjects']);

    public static function getCreatives(Advertiser $advertiser , $sid ,  $offerId ){

        $api_url = $advertiser->url;
        $api_key = $advertiser->api_key;
        $url = $api_url."$sid?apiKey=$api_key";

        $already = Offer::find($offerId)->creatives()
                                                ->select('email_id','id_sended')
                                                ->where( 'is_deleted',0)
                                                ->get();

        foreach ( $already as $creative ){
            $alreadyCreatives [] = $creative['email_id'];
            $cas[$creative['email_id']] = $creative["id_sended"];
        }
        // $alreadyCreatives = $advertiser->offers()->where("sid",$sid)->first()->creatives;
        // return  [ $alreadyCreatives , $cas ];

        $file  = file_get_contents($url);
        $offers = json_decode($file , true)["emailCreatives"];

        // return [ $alreadyCreatives , $cas , $offers ];
        foreach($offers as $offer){
            // if( self::findCretive($alreadyCreatives,$offer["id"]) )
            // if( self::findCretive($alreadyCreatives,$offer["id"]) )
            if(isset($alreadyCreatives) && in_array( $offer["id"]  , $alreadyCreatives)){
                if( $cas[ $offer["id"] ] == 0 )
                    $creatives[] =[ "sid"           => $sid ,
                        "creativeID"    => $offer["id"],
                        "description"   => $offer["display"],
                        "inside"        => "in"
                    ];
                else
                    $creatives[] =[ "sid"           => $sid ,
                        "creativeID"    => $offer["id"],
                        "description"   => $offer["display"],
                        "inside"        => "send"
                    ];
            }else
            $creatives[] =[ "sid"           => $sid ,
                            "creativeID"    => $offer["id"],
                            "description"   => $offer["display"],
                            "inside"        => "out"
                          ];

        }

        return $creatives;


    }

    // private static function findCretive($already , $new){
    //     foreach($already as $crtv)
    //         if($crtv->email_id == $new) return true;

    //     return false;
    // }


    public static function getCreative(Advertiser $advertiser,$sid ,$CeativeId){

        $api_url = $advertiser->url;
        $api_key = $advertiser->api_key;
        $url = $api_url."$sid?apiKey=$api_key";

        $file  = file_get_contents($url);
        $offers = json_decode($file , true)["emailCreatives"];

      //  return  $offers;
        foreach($offers as $offer){
            if(  $offer['id'] == $CeativeId)
                return $offer["code"];
        }



    }

    public static function getNameCreative(Advertiser $advertiser,$sid ,$CeativeId){

        $api_url = $advertiser->url;
        $api_key = $advertiser->api_key;
        $url = $api_url."$sid?apiKey=$api_key";

        $file  = file_get_contents($url);
        $offers = json_decode($file , true)["emailCreatives"];

        foreach($offers as $offer){
            if(  $offer['id'] == $CeativeId)
                return $offer["display"];
        }



    }


    public static function  getLinks(Advertiser $advertiser,$sid){
        $api_url = $advertiser->url;
        $api_key = $advertiser->api_key;

        $url = $api_url."$sid?apiKey=$api_key";

        $file  = file_get_contents($url);
        $offer = json_decode($file , true);
        return [$offer["trackingLink"]];
    }

}

