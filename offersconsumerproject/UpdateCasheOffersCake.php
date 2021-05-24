<?php

if(isset($argv[1]) )
$id = $argv[1];


// including database configuration file
require 'config.inc.php';



$connection = mysqli_connect($database_host , $database_user , $database_password , $database_name);

if(isset($id))
    $qry = mysqli_query($connection , "SELECT * FROM advertisers WHERE id=$id " );
else
    $qry = mysqli_query($connection , "SELECT * FROM advertisers WHERE `api_type` = 'cake' AND `status` = '1' " );



while( $donner = mysqli_fetch_object($qry) ){
    foreach( getCakeListOffers($donner) as $offer){
        $sid          = mysqli_real_escape_string($connection , $offer['sid']);
        $offerID      = mysqli_real_escape_string($connection , $offer['offer_id']);
        $advertiserId = mysqli_real_escape_string($connection , $offer['advertiser_id']);

        $sql = "UPDATE `cache_offers` SET `offer_id`='$offerID' WHERE `sid`=$sid and `advertiser_id`=$advertiserId ";
        mysqli_query($connection,$sql);
    }

}


 function getCakeListOffers($adv){

    $url = $adv->url;
    $api_key = $adv->api_key;
    $affiliate_id = $adv->affiliate_id;

        $url = rtrim($url,"/");
        $us =  "$url/Feed?start_at_row=1&row_limit=1000&api_key=$api_key&affiliate_id=$affiliate_id";
        set_time_limit(0);
        $file = file_get_contents($us);

        $caches =  json_decode($file,true)["data"];
        // return $caches;
        foreach( $caches as $cache ){
        if($cache['campaign_id'] != null){
        $off['sid']                =  $cache['campaign_id'];
        $off['offer_id']           =  $cache['offer_id'];
        $off['name']               =  $cache['offer_name'];
        $off['description']        =  $cache['description'];
        $off['payout']             =  $cache['price_converted'];
        $off['unit']               =  $cache['price_format'];
        $off['daysleft']           =  $cache['expiration_date'];
        $off['category']           =  $cache['vertical_name'];
        $off['case']               =  $cache['offer_status']["offer_status_name"];

        $contr = "";
        if(!empty($cache['allowed_countries'])){

            foreach( $cache['allowed_countries'] as $contry){
                $contr .= strtolower ($contry["country_code"])."," ;
            }
            $contr = rtrim($contr,",");
        }else $contr = "all";

        $off['geotargeting']  =  $contr ;

        $off['api_type']       = 'cake';
        $off['advertiser_id']  = $adv->id;

        if($off['case'] == 'Active' &&  !is_null($off['sid']) )
            $offers[] = $off;
        }
        }

        return $offers;

    }





?>
