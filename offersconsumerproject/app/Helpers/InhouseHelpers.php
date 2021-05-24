<?php

use App\Advertiser;
use App\CacheOffer;
use App\Offer;

class InhouseHelpers{

    public static function getCacheOffers(Advertiser $adv){

        $url = rtrim($adv->url,"/");
        $apiKey = $adv->api_key;

        $offset = 0;
        do{
            $ur = "$url/pubs_campaign_available/get/?key_id=$apiKey&limit=500&offset=$offset";
            $ap = file_get_contents($ur);
            $cache = json_decode($ap , true);

            $cachesArray [] =  $cache ["data"]["results"];
            $lenght = 0;
            foreach($cachesArray as $cach){
                $lenght += count($cach);
            }
            $count = $cache ["data"] ["count"];
            $offset+=500;
        }while($count != $lenght) ;

        foreach($cachesArray as $caches){
            foreach($caches as $offerCache ){
                if($offerCache ["approval_status"]=="approved"){
                    // if($offerCache ["approval_status"]!="request"){
                    $offer["sid"]           = $offerCache ["campaign_id"];
                    $offer["name"]          = $offerCache ["campaign_name"];
                    $offer["description"]   = $offerCache ["campaign_description"];
                    $offer["payout"]        = $offerCache ["payout"];
                    $offer["unit"]          = $offerCache ["pays_on"];
                    $offer["category"]      = self::arrayToString($offerCache ["categories"]);
                    $offer['geotargeting']  = self::arrayToString( json_decode( $offerCache ["country_ids"] , true ));
                    $offer['status']        = $offerCache ["approval_status"];

                    $offers [] = $offer;
                }
            }
        }

        return $offers;
    }

    public static function arrayToString($array){
        $str = "";
        foreach($array as $item){
            if( strlen($item) != 0) $str.=",".$item;
        }

        return ltrim($str,",");
    }

    public static function insertChachesToDB(Advertiser $adv){

        $caches = self::getCacheOffers($adv);

        foreach($caches as $cache){
            $off  = new CacheOffer;
            $off->sid            = $cache["sid"];
            $off->name           = $cache["name"];
            $off->description    = $cache["description"];
            $off->payout         = $cache["payout"];
            $off->unit           = $cache["unit"];
            $off->category       = $cache["category"];
            $off->geotargeting   = $cache["geotargeting"];
            $off->api_type       = "inhouse";
            $off->advertiser_id  = $adv->id;
            $off->save();
            $sids[] = $cache["sid"];
        }

        return $sids;
    }

    public static function inHouseResponce( Advertiser $adv , $sid ){

        $api_url = rtrim($adv->url,"/");
        $api_key = $adv->api_key;

        $off ['cache'] = $adv->cacheoffres()->where("sid",$sid)->get()[0];

        $url ="$api_url/pubs_campaign_suppression/get/?key_id=$api_key&campaign_id=$sid";
        $offs =  file_get_contents($url);
        $p= json_decode($offs,true);

        $off ['UnsubLink']     =  json_decode($p["data"],true)["optout_link"];
        $off ['SupLink']       =  json_decode($p["data"],true)["download_link"];

        $url = "$api_url/pubs_campaign_details/get/?key_id=$api_key&campaign_ids=[$sid]";
        $offs =  file_get_contents($url);
        $p= json_decode($offs,true);

        foreach(json_decode($p["data"],true)["results"][0]["landing_page_list"] as $links){
            $off ['offerlnk'][]= $links["url"];
        }

        $url ="$api_url/pubs_creative_email/get/?key_id=$api_key&campaign_id=$sid";
        $offs =  file_get_contents($url);
        $p= json_decode($offs,true);
        $n= json_decode($p["data"],true);

        $off ['subjects']  = [];
        $off ['fromlines'] = [];

        $froms = [] ;
        foreach( json_decode( $n["results"]["all_email_assets"] , true ) as $object ){
            $mapped = array_map( function( $item ) { return $item["content"] ; } ,  $object["all_from_lines"] );
            $froms  = array_merge( $froms , $mapped );
        }

        $off ['fromlines'] = array_unique( $froms );

        $subjects = [] ;
        foreach( json_decode( $n["results"]["all_email_assets"] , true ) as $object ){
            $mapped   = array_map( function( $item ) { return $item["content"] ; } ,  $object["all_subject_lines"] );
            $subjects = array_merge( $subjects , $mapped );
        }

        $subjects = array_unique( $subjects  );
        $off ['subjects']  = array_unique( $subjects  );

        $off ['urlToAdd']      =  $adv->url_to_add;

        return  $off;
    }

    // getCreatives
    public static function getCreatives(Advertiser $adv , $sid , $offerId ){

        $api_url = rtrim($adv->url,"/");
        $api_key = $adv->api_key;
        $url ="$api_url/pubs_creative_email/get/?key_id=$api_key&campaign_id=$sid";
        $creatives = [];
        $offs =  file_get_contents($url);
        $p= json_decode($offs,true);
        $n= json_decode($p["data"],true);

        //return $n ;
        $alreadyCreatives = [];
        $cas = [] ;

        if( count(Offer::find($offerId)->creatives) != 0 )
        foreach( Offer::find($offerId)->creatives()->select('email_id','id_sended')->where( 'is_deleted',0)->get() as $creative ){
            $alreadyCreatives [] = $creative['email_id'];
            $cas[$creative['email_id']] = $creative["id_sended"];
        }

        // return [ $alreadyCreatives ] ;
       // return json_decode( $n["results"]["all_email_assets"] , true ) ;

        foreach( json_decode( $n["results"]["all_email_assets"] , true ) as $crtv ){

            if( in_array( $crtv["id"]  , $alreadyCreatives)) {
                if( $cas[ $crtv["id"]] == 0 )
                    $creatives[] =[ "sid"           => $sid ,
                                "creativeID"    => $crtv["id"],
                                "description"   => $crtv["format"],
                                "inside"        => "in"
                                ];
                else
                    $creatives[] =[ "sid"           => $sid ,
                        "creativeID"    => $crtv["id"],
                        "description"   => $crtv["format"],
                        "inside"        => "send"
                    ];
            }else
            $creatives[] =[ "sid"           => $sid ,
                            "creativeID"    => $crtv["id"],
                            "description"   => $crtv["format"],
                            "inside"        => "out"
                        ];
        }

        return  $creatives;

    }

    private static function findAlready($already,$new){
        foreach($already as $crtv)
            if($crtv->email_id == $new) return true;
        return false;
    }


    public static function getCreative( Advertiser $adv,$sid , $EmailID){
        $api_url = rtrim($adv->url,"/");
        $api_key = $adv->api_key;
        $url ="$api_url/pubs_creative_email/get/?key_id=$api_key&campaign_id=$sid";

        $offs =  file_get_contents($url);
        $p= json_decode($offs,true);

        $n= json_decode($p["data"],true);

        foreach( json_decode( $n["results"]["all_email_assets"] , true ) as $crtv )
            if( $crtv["id"]  == $EmailID) return $crtv["content"];

    }


    public static function imagesReplaceBase64( $html ){
         //   $html = self::getCreative($adv,$sid , $EmailID);

        //    $html = preg_replace('/<img%20/' , "<img " ,  $html );

            preg_match_all('/<img[^>]+>/i',$html,$out,PREG_PATTERN_ORDER);
           // return $out;
            if(count($out[0]) != 0 ){
                foreach($out[0] as $img){
                    $begen = strpos($img, "http");
                    $rest  = substr($img,$begen);
                    $LinksOfImages[] = str_replace(strstr($rest, '"'),"",$rest);
                }
            } else {
                return $html ;
            }

            foreach($LinksOfImages as $link){
                $LinksOfImagess[$link] = str_replace(" ","%20",$link);
            }
            //return $LinksOfImagess;

            foreach($LinksOfImagess as $org => $link){
                try{

                    $binary = file_get_contents($link);
                    $base = base64_encode($binary);
                    $mime = getimagesizefromstring($binary)["mime"];
                    $str="data:".$mime.";base64,";
                    $img = $str.$base;
                    $arr[$org] = $img ;
                }catch (Exception $e) {
                    $arr[$org] = $link;
                }
            }

          //return $arr;
            $htmsl =  $html ;


            foreach($arr as $link => $base){
                $htmsl = str_replace($link,$base,$htmsl);
            }
            return  $htmsl;
    }

    public static function getLinks(Advertiser $advertiser,$sid){

        $api_url = rtrim($advertiser->url,"/");
        $api_key = $advertiser->api_key;

        $url = "$api_url/pubs_campaign_details/get/?key_id=$api_key&campaign_ids=[$sid]";
        $offs =  file_get_contents($url);
        $p= json_decode($offs,true);

        foreach(json_decode($p["data"],true)["results"][0]["landing_page_list"] as $links){
            $off[]= $links["url"];
        }
        return  $off ;
    }


    public static function reloadSuprissionlink(Offer $offer){
        if(preg_match('/optizmo/',$offer->suppression_link)){
            $url =  str_replace(
                "https://mailer_api.optizmo.net/accesskey/getfile/",
                // "https://mailer_api.optizmo.net/accesskey/download/",
                "https://mailer-api.optizmo.net/accesskey/download/",
                "$offer->suppression_link");

            $offer->suppression_link_2 =  $url;
            $offer->save();
            return $offer;
        }else  return  $offer->suppression_link;;
    }

}

