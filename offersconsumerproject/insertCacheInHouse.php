<?php

if(isset($argv[1]) )
$id = $argv[1];


// including database configuration file
require 'config.inc.php';



if(isset($id))
    $qry = mysqli_query($connection , "SELECT * FROM advertisers WHERE id=$id " );
else
    $qry = mysqli_query($connection , "SELECT * FROM advertisers WHERE `api_type` = 'inhouse' AND `status` = '1' " );



while( $data = mysqli_fetch_object($qry) ){
   // var_dump(getCacheOffers($data));
    foreach( getCacheOffers($data) as $offer ){
        $sid          = mysqli_real_escape_string($connection , $offer['sid']);
        $name         = mysqli_real_escape_string($connection , $offer['name']);
        $description  = mysqli_real_escape_string($connection , $offer['description']);
        $payout       = mysqli_real_escape_string($connection , $offer['payout']);
        $unit         = mysqli_real_escape_string($connection , $offer['unit']);
        $category     = mysqli_real_escape_string($connection , $offer['category']);
        $geotargeting = mysqli_real_escape_string($connection , $offer['geotargeting']);
        $api_type     = "inhouse";
        $advertiserId = mysqli_real_escape_string($connection , $data->id);
        $sql = "INSERT IGNORE INTO `cache_offers` (`id`, `sid`, `name`, `description`, `payout`, `unit`, `category`, `geotargeting`, `api_type`, `advertiser_id`)
        VALUES (NULL,'$sid' , '$name' ,'$description' , '$payout','$unit','$category' ,'$geotargeting' ,'$api_type','$advertiserId')";
        mysqli_query($connection,$sql);
    }
}

echo "Done ! Caches added . \n";

function getCacheOffers($adv){

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
             $offer["category"]      = arrayToString($offerCache ["categories"]);
             $offer['geotargeting']  = arrayToString( json_decode( $offerCache ["country_ids"] , true ));
             $offer['status']        = $offerCache ["approval_status"];

             $offers [] = $offer;
            }
        }
    }

    return $offers;
}

 function arrayToString($array){
    $str = "";
    foreach($array as $item){
        if( strlen($item) != 0) $str.="/".$item;
    }

    return ltrim($str,"/");
}
