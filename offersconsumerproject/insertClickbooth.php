<?php


if(isset($argv[1]) )
$id = $argv[1];


// including database configuration file
require 'config.inc.php';



if(isset($id))
    $qry = mysqli_query($connection , "SELECT * FROM advertisers WHERE id=$id " );
else
    $qry = mysqli_query($connection , "SELECT * FROM advertisers WHERE `api_type` = 'clickbooth' AND `status` = '1' " );



while($donne = mysqli_fetch_object($qry)){
    // var_dump(getOffersCompainList($donne));
    foreach(getOffersCompainList($donne) as $offer){
        $sid          = mysqli_real_escape_string($connection , $offer['sid']);
        $name         = mysqli_real_escape_string($connection , $offer['name']);
        $offerID      = mysqli_real_escape_string($connection , $offer['offer_id']);
        $payout       = mysqli_real_escape_string($connection , $offer['payout']);
        $unit         = mysqli_real_escape_string($connection , $offer['unit']);
        $category     = mysqli_real_escape_string($connection , $offer['category']);
        $geotargeting = mysqli_real_escape_string($connection , $offer['geo']);
        $api_type     = "clickbooth";
        $advertiserId = mysqli_real_escape_string($connection , $donne->id);

     $sql = "INSERT IGNORE INTO `cache_offers` (`id`, `sid`, `offer_id`, `name`, `payout`, `unit`, `category`, `geotargeting`, `api_type`, `advertiser_id`)
        VALUES (NULL,'$sid' , '$offerID' , '$name' , '$payout','$unit','$category' ,'$geotargeting' ,'$api_type','$advertiserId')";
    mysqli_query($connection,$sql);
    }
}

echo "insertion done !!";



 function getOffersCompainList( $adv){
    foreach (getOffers($adv) as $offer){

        foreach( $offer["campaign_ids"]["campaign_id"] as $offf)
        if(gettype( $offf) == "array"){
             foreach($offf as $opp){

                $offerCache["sid"]      = $opp;
                $offerCache["offer_id"] = $offer["offer_id"];
                $offerCache["name"]     = $offer["offer_name"];
                $offerCache["category"] = $offer["vertical_name"];
                $offerCache["payout"]   = round((float) $offer["payout"]);
                $offerCache["unit"]     = $offer["pricing_model"];

                $geo = "";
               if(array_key_exists("targeted_locations", $offer)){
                    foreach($offer["targeted_locations"]["targeted_location"] as $nn){
                        if( gettype( $nn) == "array" ){
                            $geo .= ",".$nn["code"];
                        }else{
                            $geo .= ",".$nn;
                            break;
                        }
                    }
                }else $geo = "all";

                $offerCache["geo"]  =  ltrim($geo,",") ;
                 $off[]= $offerCache;
                }
        }else{

            $offerCache["sid"]      = $offf;
            $offerCache["offer_id"] = $offer["offer_id"];
            $offerCache["name"]     = $offer["offer_name"];
            $offerCache["category"] = $offer["vertical_name"];
            $offerCache["payout"]   = round((float) $offer["payout"],2);
            $offerCache["unit"]     = $offer["pricing_model"];

            $geo = "";
            if(array_key_exists("targeted_locations", $offer)){
                foreach($offer["targeted_locations"]["targeted_location"] as $nn){
                    if( gettype( $nn) == "array" ){
                        $geo .= ",".$nn["code"];
                    }else{
                        $geo .= ",".$nn;
                        break;
                    }
                }
            }else $geo = "all";

            $offerCache["geo"]  =  ltrim($geo,",") ;

             $off[]=$offerCache;
        }
    }
    return $off;
 }



 function getOffers($adv){
    $adv_url      = rtrim($adv->url,"/");
    $api_key      = $adv->api_key;
    $affiliate_id = $adv->affiliate_id;

    $url = "$adv_url/v1/cb_api?get=offer_feed&api_key=$api_key&user_id=$affiliate_id";

    $p = file_get_contents($url);

    $obj = new SimpleXMLElement($p);
    $n = json_encode($obj);

    foreach(json_decode($n,true)["offers"]["offer"] as $offer){
        if(!empty($offer["campaign_ids"]))
            $offers [] = $offer;
    }

    return  $offers ;
}









