<?php

if(isset($argv[1]) )
$id = $argv[1];


// including database configuration file
require 'config.inc.php';



if(isset($id))
    $qry = mysqli_query($connection , "SELECT * FROM advertisers WHERE id=$id " );
else
    $qry = mysqli_query($connection , "SELECT * FROM advertisers WHERE `api_type` = 'hasoffers' AND `status` = '1' " );


while ($data =  mysqli_fetch_object($qry)){

    foreach (getCachOffers($data->api_key) as $offer){

        $sid          = mysqli_real_escape_string($connection , $offer['sid']);
        $name         = mysqli_real_escape_string($connection , $offer['name']);
        $description  = mysqli_real_escape_string($connection , $offer['description']);
        $payout       = mysqli_real_escape_string($connection , $offer['payout']);
        $unit         = mysqli_real_escape_string($connection , $offer['unit']);
        $category     = mysqli_real_escape_string($connection , $offer['category']);
        $preview_url  = mysqli_real_escape_string($connection , substr($offer['preview_url'],0,250) );
        $api_type     = "hasoffers";
        $advertiserId = mysqli_real_escape_string($connection , $data->id);
        $sql = "INSERT IGNORE INTO `cache_offers` (`id`, `sid`, `name`, `description`, `payout`, `unit`, `category`, `preview_url`, `api_type`, `advertiser_id`)
        VALUES (NULL,'$sid' , '$name' ,'$description' , '$payout','$unit','$category' ,'$preview_url' ,'$api_type','$advertiserId')";
        mysqli_query($connection,$sql);
    }
}

echo "Done !!";

    function getCachOffers($ApiKey){

        $url ="https://gwm2.api.hasoffers.com/Apiv3/json?api_key=$ApiKey&Target=Affiliate_Offer&Method=findMyApprovedOffers";

        set_time_limit(0);
        $file = file_get_contents($url);
        $cache = json_decode($file , true) ["response"]["data"];

        foreach ($cache as $k => $off){
            $sids[] = $k;
        }

        $categories = getCategory( $sids , $ApiKey);

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


    function getCategory($sids , $ApiKey){

        $url = "https://gwm2.api.hasoffers.com/Apiv3/json?api_key=$ApiKey&Target=Affiliate_Offer&Method=getCategories";

        foreach($sids as $sid){
          $url.="&ids[]=$sid";
        }

          $file = file_get_contents($url);
          $cache = json_decode($file , true)["response"]["data"];

        foreach( $cache as $categories){

            $cat = "";
            foreach( $categories["categories"]  as $category){
                $cat.=",".$category["name"];
            }
            $cats[$categories["offer_id"]] =  ltrim($cat,",");
          }

        return  $cats;
    }





