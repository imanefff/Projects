<?php


if(isset($argv[1]) )
$id = $argv[1];


// including database configuration file
require 'config.inc.php';



if(isset($id))
    $qry = mysqli_query($connection , "SELECT * FROM advertisers WHERE id=$id " );
else
    $qry = mysqli_query($connection , "SELECT * FROM `advertisers` WHERE `api_type` = 'hitpath' AND `entities` IS NULL ORDER BY `entity_id`" );


	while ($donne = mysqli_fetch_object($qry) ) {

	  $apikey   = $donne->api_key;
      $url      = $donne->url;
      $type_api = $donne->api_type;

      $postParametres=['apikey'     =>  $apikey ,
                       'apifunc'    => 'getcampaigns' ];

          // get offers from API as an array
      $offers = getFromApi($url , $postParametres)["data"];


      	 //check existing of data
      if(!is_null($offers)){


      				      // insert cache offers == offer by offer
                  foreach ($offers as $key => $value) {

            			     // filter string to insert in db
                    $name        = is_array($value['name'])?"":preg_replace("/'/i", "\'", $value['name']) ;
                    $description = is_array($value['description'])?"":preg_replace("/'/i", "\'", $value['description']) ;
                    $category    = is_array($value['category'])?"":preg_replace("/'/i", "\'", $value['category']) ;
                    $geotraget   = is_array($value['geotargeting'])?"":preg_replace("/'/i", "\'", $value['geotargeting']);

                    	// filter numbers to insert in db
                    $daysleft    = is_array($value['daysleft'])?0:$value['daysleft'] ;


                    	// the query of insert
                        $sqlInsert = "INSERT IGNORE INTO `cache_offers` (`id`, `sid`, `name`, `description`, `payout`, `unit`, `daysleft`, `category`, `geotargeting`, `api_type`, `advertiser_id`)  VALUES (NULL,'".$value['campaignid']."' , '".$name."' ,'".$description."' , '".preg_replace("/'/i", "\'", $value['payout'])."','".$value['unit']."', '". $daysleft ."','".$category."' ,'".$geotraget."' ,'".$type_api."','".$donne->id."')";

                        	//execute query
                        $sqlQuery = mysqli_query($connection,$sqlInsert);

                  }

        }
    }

  $qry = mysqli_query($connection , "UPDATE advertisers SET updated_at = CURRENT_TIMESTAMP WHERE id=$id " );


if(isset($id)) exit();

$qry = mysqli_query($connection , "SELECT * FROM `advertisers` WHERE `api_type` = 'hitpath' AND `entities` IS NOT NULL AND `entities` != '-1'" );

	while ($donne = mysqli_fetch_object($qry) ) {

		// var_dump($donne);

			$adv_id_Orginal = $donne->id;
			$adv_id_fake    = $donne->entities;

		    $apikey   = $donne->api_key;
      		$url      = $donne->url;
      		$type_api = $donne->api_type;

      $postParametres=['apikey'     =>  $apikey ,
                       'apifunc'    => 'getcampaigns' ];

          // get offers from API as an array
      $offers = getFromApi($url , $postParametres)["data"];


      	 //check existing of data
      if(!is_null($offers)){


      				      // insert cache offers == offer by offer
                  foreach ($offers as $key => $value) {

            			     // filter string to insert in db
                    $name        = is_array($value['name'])?"":preg_replace("/'/i", "\'", $value['name']) ;
                    $description = is_array($value['description'])?"":preg_replace("/'/i", "\'", $value['description']) ;
                    $category    = is_array($value['category'])?"":preg_replace("/'/i", "\'", $value['category']) ;
                    $geotraget   = is_array($value['geotargeting'])?"":preg_replace("/'/i", "\'", $value['geotargeting']);

                    	// filter numbers to insert in db
                    $daysleft    = is_array($value['daysleft'])?0:$value['daysleft'] ;


                    	// the query of insert
                        $sqlInsert = "INSERT IGNORE INTO `cache_offers` (`id`, `sid`, `name`, `description`, `payout`, `unit`, `daysleft`, `category`, `geotargeting`, `api_type`, `advertiser_id`)  VALUES (NULL,'".$value['campaignid']."' , '".$name."' ,'".$description."' , '".preg_replace("/'/i", "\'", $value['payout'])."','".$value['unit']."', '". $daysleft ."','".$category."' ,'".$geotraget."' ,'".$type_api."','".$adv_id_Orginal."')";

                        	//execute query
                        $sqlQuery = mysqli_query($connection,$sqlInsert);

                        // the query of insert
                        $sqlInsert = "INSERT IGNORE INTO `cache_offers` (`id`, `sid`, `name`, `description`, `payout`, `unit`, `daysleft`, `category`, `geotargeting`, `api_type`, `advertiser_id`)  VALUES (NULL,'".$value['campaignid']."' , '".$name."' ,'".$description."' , '".preg_replace("/'/i", "\'", $value['payout'])."','".$value['unit']."', '". $daysleft ."','".$category."' ,'".$geotraget."' ,'".$type_api."','".$adv_id_fake."')";
                        //execute query
                        $sqlQuery = mysqli_query($connection,$sqlInsert);

                  }
            $qry = mysqli_query($connection , "UPDATE advertisers SET updated_at = CURRENT_TIMESTAMP" );
        }
	}

	echo "added";



function getFromApi($url , $parametres)
{

      $params = "";
      foreach ($parametres as $k => $v) {
           $params = $params.$k."=".$v."&";
        }

      $params = rtrim($params,'&');

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      set_time_limit(0);
      $server_output = curl_exec($ch);

      curl_close($ch);

      if($server_output == "PLEASE WAIT A FEW MINUTES BEFORE NEXT API CALL" || $server_output == "INVALID CAMPAIGNID" || $server_output == null) return null;
      $xml= simplexml_load_string($server_output , null ,LIBXML_NOCDATA);
      $json = json_encode($xml);
      $array =  json_decode($json,true);
      return  $array;
}
