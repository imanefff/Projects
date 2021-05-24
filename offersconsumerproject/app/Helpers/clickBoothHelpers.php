<?php

use App\Advertiser;
use App\Offer;

class ClickBoothHelpers {

     public static function clickBoothResponce(Advertiser $adv,$sid){

        $adv_url   = rtrim($adv->url,"/");
        $api_key   = $adv->api_key;
        $affiliate = $adv->affiliate_id;

        $off ['cache'] = $adv->cacheoffres()->where("sid",$sid)->get()[0];
        $url = "$adv_url/v3/cb_api?get=get_campaign&api_key=$api_key&user_id=$affiliate&campaign_id=$sid";

        $p = file_get_contents($url);

        $obj = new SimpleXMLElement($p);
        $n = json_encode($obj);
        $data = json_decode($n,true)["campaign"];

        $off ['offerlnk'][]  =  $data ["campaign_url"];
        $off ['UnsubLink']   =  $data ["unsubscribe_link"];
        $off ['SupLink']     =  $data ["suppression_link"];
        $off ['fromlines']   =  explode("\n",$data ["from_lines"]["string"]);
        $off ['subjects']    =  explode("\n",$data ["subject_lines"]["string"]);
        $off ['urlToAdd']      =  $adv->url_to_add;
    return $off;
    }




    public static function getCreativeList(Advertiser $adv , $sid , $offerId  ){

        $adv_url   = rtrim($adv->url,"/");
        $api_key   = $adv->api_key;
        $affiliate = $adv->affiliate_id;

        $url = "$adv_url/v3/cb_api?get=get_campaign&api_key=$api_key&user_id=$affiliate&campaign_id=$sid";

        $p = file_get_contents($url);

        $obj = new SimpleXMLElement($p);
        $n = json_encode($obj);
        $creatives = json_decode($n,true)["campaign"]["creatives"]["creative"];

        if( count(Offer::find($offerId)->creatives) != 0 )
            foreach( Offer::find($offerId)->creatives()->select('email_id','id_sended')->where( 'is_deleted',0)->get() as $creative ){
            $alreadyCreatives [] = $creative['email_id'];
            $cas[$creative['email_id']] = $creative["id_sended"];
        }

        // return [ $alreadyCreatives , $cas , $creatives ];

        if(array_key_exists(0, $creatives)){
            foreach($creatives as $creative){
            //    if(self::findAlready($alreadyCreatives,$creative["creative_id"])) {

                if( isset($alreadyCreatives) && in_array( $creative["creative_id"] , $alreadyCreatives)) {
                    if( $cas[ $creative["creative_id"] ] == 0 )
                             $creativess[] =[
                                "sid"           => $sid ,
                                "creativeID"    => $creative["creative_id"],
                                "description"   => $creative["creative_name"],
                                "inside"        => "in"
                             ];
                    else
                        $creativess[] =[
                            "sid"           => $sid ,
                            "creativeID"    => $creative["creative_id"],
                            "description"   => $creative["creative_name"],
                            "inside"        => "send"
                        ];

                }else{
                    $creativess[] =[
                        "sid"           => $sid ,
                        "creativeID"    => $creative["creative_id"],
                        "description"   => $creative["creative_name"],
                        "inside"        => "out"
                     ];

                }
            }
        }else {
            // if(self::findAlready($alreadyCreatives,$creatives["creative_id"])){
            if( isset($alreadyCreatives) && in_array( $creatives["creative_id"] , $alreadyCreatives)) {
                if( $cas[ $creatives["creative_id"] ] == 0 )
                    $creativess[] =[
                        "sid"           => $sid ,
                        "creativeID"    => $creatives["creative_id"],
                        "description"   => $creatives["creative_name"],
                        "inside"        => "in"
                    ];
                else
                    $creativess[] =[
                        "sid"           => $sid ,
                        "creativeID"    => $creatives["creative_id"],
                        "description"   => $creatives["creative_name"],
                        "inside"        => "send"
                    ];
            }else{
                $creativess[] =[
                    "sid"           => $sid ,
                    "creativeID"    => $creatives["creative_id"],
                    "description"   => $creatives["creative_name"],
                    "inside"        => "out"
                ];

            }

        }

        return $creativess;
    }

    // private static function findAlready($already,$new){
    //     foreach($already as $crtv)
    //         if($crtv->email_id == $new) return true;
    //     return false;
    // }


    public static function getCreative(Advertiser $adv,$sid,$crt){

        $adv_url   = rtrim($adv->url,"/");
        $api_key   = $adv->api_key;
        $affiliate = $adv->affiliate_id;

        $url = "$adv_url/v1/cb_api?get=get_creative_code&api_key=$api_key&user_id=$affiliate&campaign_id=$sid&creative_id=$crt";

        $p = file_get_contents($url);

        $obj = new SimpleXMLElement($p);
        $n = json_encode($obj);
        // return  json_decode($n,true);
       return  json_decode($n,true)["creative_codes"]["creative_code"]["file_content"];

    }

    public static function getNameCreative(Advertiser $adv,$sid,$crt){

        $adv_url   = rtrim($adv->url,"/");
        $api_key   = $adv->api_key;
        $affiliate = $adv->affiliate_id;

        $url = "$adv_url/v1/cb_api?get=get_creative_code&api_key=$api_key&user_id=$affiliate&campaign_id=$sid&creative_id=$crt";

        $p = file_get_contents($url);

        $obj = new SimpleXMLElement($p);
        $n = json_encode($obj);
        return  json_decode($n,true)["creative_codes"]["creative_code"]["file_name"];

    }


    public static function getLinks(Advertiser $advertiser,$sid){
        $adv_url   = rtrim($advertiser->url,"/");
        $api_key   = $advertiser->api_key;
        $affiliate = $advertiser->affiliate_id;

        $url = "$adv_url/v3/cb_api?get=get_campaign&api_key=$api_key&user_id=$affiliate&campaign_id=$sid";



        $p = file_get_contents($url);

        $obj = new SimpleXMLElement($p);
        $n = json_encode($obj);
        $data = json_decode($n,true)["campaign"];

        $off[]  =  $data ["campaign_url"];

        return  $off;
    }


}
