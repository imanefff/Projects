<?php

if(isset($argv[1]) )
$id = $argv[1];


// including database configuration file
require 'config.inc.php';



if(isset($id))
    $qry = mysqli_query($connection , "SELECT * FROM advertisers WHERE id=$id " );
else
    $qry = mysqli_query($connection , "SELECT * FROM advertisers WHERE `api_type` = 'cake' AND `status` = '1' " );


while( $donner = mysqli_fetch_object($qry) ){
    // var_dump(getCakeListOffers($donner) );
    // break;
    foreach( getCakeListOffers($donner) as $offer){
        $sid          = mysqli_real_escape_string($connection , $offer['sid']);
        $offerID      = mysqli_real_escape_string($connection , $offer['offer_id']);
        $name         = mysqli_real_escape_string($connection , $offer['name']);
        $description  = mysqli_real_escape_string($connection , $offer['description']);
        $payout       = mysqli_real_escape_string($connection , $offer['payout']);
        $unit         = mysqli_real_escape_string($connection , $offer['unit']);
        $category     = mysqli_real_escape_string($connection , $offer['category']);
        $geotargeting = mysqli_real_escape_string($connection , $offer['geotargeting']);
        $api_type     = "cake";
        $advertiserId = mysqli_real_escape_string($connection , $offer['advertiser_id']);
        $sql = "INSERT IGNORE INTO `cache_offers` (`id`, `sid`, `offer_id`, `name`, `description`, `payout`, `unit`, `category`, `geotargeting`, `api_type`, `advertiser_id`)
         VALUES (NULL,'$sid' , '$offerID' , '$name' ,'$description' , '$payout','$unit','$category' ,'$geotargeting' ,'$api_type','$advertiserId')";
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
