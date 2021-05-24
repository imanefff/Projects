<?php

header ("Access-Control-Allow-Origin: *");
header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
header ("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
header ("Access-Control-Allow-Headers: *");

/******************* Configuration Connection  ***********************/

    $hostname = "localhost" ;
    $username = "root" ;
    $password = "root" ;
    $database = "hawktest" ;

/*********************************************************************/

    // concetion database
    $connection = mysqli_connect($hostname,$username,$password,$database);
    mysqli_set_charset( $connection ,"utf8" );
    $isAdded = false;

    // get offer id and url from Post sended
if(isset($_POST['offer_id']) && !empty($_POST['offer_id'])){
    $idOffer = $_POST['offer_id'];

    if(isset($_POST['url']) && !empty($_POST['url']))
            $urls = $_POST['url'];


    // check thas offer id sended is a number
   if(is_numeric($idOffer)) {

    // get data offer from api
    $url = $urls."/api/offers/".$idOffer;
    $homepage = file_get_contents($url);
    $Offer = json_decode($homepage,true);
    $dataOffer = $Offer['data'];
    // print_r($Offer['data']);
   }

    // check if category is exist in database
    $sql = "select * from categories where name = '".$dataOffer['categories']."'";
    $qry = mysqli_query($connection , $sql);

    // if already exist put her id in $categoryID variable
    // else create new category and put her id in $categoryID variable
    if($qry->num_rows != 0){
        $donne = mysqli_fetch_object($qry);
        $categoryID = $donne->id;
    }else{

        $sql = " INSERT INTO `categories` (`name`)  VALUES ( '" .$dataOffer['categories']."') ";

        if($qry = mysqli_query($connection,$sql)){
        //    $sql = "SELECT LAST_INSERT_ID()";
           $sql = "SELECT `id` FROM categories WHERE `name` = '".$dataOffer['categories']."' ORDER BY id DESC LIMIT 1;";
           $qry = mysqli_query($connection,$sql);
           $donne = mysqli_fetch_row($qry);
           $categoryID = $donne[0];

        }

    }


    $advertiserId    = mysqli_real_escape_string($connection , $dataOffer['advertiser_id']);
    $sid             = mysqli_real_escape_string($connection , $dataOffer['sid'] );
    $name            = mysqli_real_escape_string($connection , $dataOffer['name']);
    $offerUrl        = mysqli_real_escape_string($connection , $dataOffer['offer_url']);
    $previewUrl      = mysqli_real_escape_string($connection , $dataOffer['preview_url']);
    $unsubscribeUrl  = mysqli_real_escape_string($connection , $dataOffer['unsubscribe_url']);
    $categoryID      = mysqli_real_escape_string($connection , $categoryID);
    $description     = mysqli_real_escape_string($connection , str_replace("'","\'",$dataOffer['description']));
    $type            = mysqli_real_escape_string($connection , $dataOffer['type'] );
    $amount          = mysqli_real_escape_string($connection , ltrim($dataOffer['amount'],'$') );
    $countries       = mysqli_real_escape_string($connection , $dataOffer['countries'] );
    $entityId        = mysqli_real_escape_string($connection , $dataOffer['entity_id']);
    $suppressionLink = mysqli_real_escape_string($connection , $dataOffer['suppression_link']);

    $oid             = 0 ;
    $isActive        = 1 ;
    $hasSupp         = 0 ;
    $level           = 1 ;
    $isMobile        = 0 ;




    if($Offer['data']["case"] == "Add"){
    // now prepare  query of inserte offer in database
        $sql = "INSERT INTO `offers` (`advertiser_id`, `sid`, `oid`, `name`, `offer_url`, `preview_url`,
        `landing_page_url`, `unsubscribe_url`, `is_active`, `categories`, `description`,  `type`,
        `amount`, `has_supp`, `level`, `countries`, `is_mobile`,
        `entity_id`, `suppression_link`) VALUES
        ( $advertiserId , $sid , $oid , '$name','$offerUrl', '$previewUrl', '', '$unsubscribeUrl', $isActive , '$categoryID',
        '$description' , '$type', '$amount', $hasSupp , $level , '$countries', $isMobile, $entityId,'$suppressionLink')";


//     }
// //    echo  $sql;
// //     exit();
// }


      if($qry = mysqli_query($connection,$sql)) {
        $sql = "SELECT `id` FROM `offers` WHERE `advertiser_id`=".$dataOffer['advertiser_id']." AND `sid`= ".$dataOffer['sid'] ." ORDER BY id DESC LIMIT 1;";
        $qry = mysqli_query($connection,$sql);
        $donne = mysqli_fetch_row($qry);
        $offerId = $donne[0];
        $isAdded = true;
         }
        }
     // get id offer inserted


     if($Offer['data']["case"] != "Add")
        $offerId = $Offer['data']["hawkId"];

    if($Offer['data']["case"] == "Add"){

        $fromLines = $dataOffer['from_lines'];
        foreach($fromLines as $from){
            $sql = "INSERT INTO `email_assets` (`offer_id`, `content`, `type`) VALUES ('".$offerId."', '".rtrim($from, "\r")."', 2)";
            $qry = mysqli_query($connection,$sql);
        }
        $subjectLines = $dataOffer['subject_lines'];
        foreach($subjectLines as $subject)
        {
            $sql = "INSERT INTO `email_assets` (`offer_id`, `content`, `type`) VALUES ('".$offerId."', '".rtrim($subject, "\r")."', 1)";
            $qry = mysqli_query($connection,$sql);
        }

    }
    else{
        $sql = " select * from email_assets where offer_id=$offerId and type='from'";
        $qry = mysqli_query($connection,$sql);
        while($donne = mysqli_fetch_object( $qry )){
            $fromsAdded[] =  $donne->content;
        }
        foreach($dataOffer['from_lines'] as $from){
           if(!in_array(rtrim($from, "\r") , $fromsAdded ))
                $toAddFroms[] = $from;
        }

        $fromLines = $toAddFroms;
        foreach($fromLines as $from){
            $sql = "INSERT INTO `email_assets` (`offer_id`, `content`, `type`) VALUES ('".$offerId."', '".rtrim($from, "\r")."', 2)";
            $qry = mysqli_query($connection,$sql);
        }

        $sql = " select * from email_assets where offer_id=$offerId and type='subject'";
        $qry = mysqli_query($connection,$sql);
        while($donne = mysqli_fetch_object( $qry )){
            $fromsAdded[] =  $donne->content;
        }
        foreach($dataOffer['subject_lines'] as $from){
           if(!in_array(rtrim($from, "\r") , $fromsAdded ))
                $toAddsubject[] = $from;
        }

        $subjectLines = $toAddsubject;
        foreach($subjectLines as $subject)
        {
            $sql = "INSERT IGNORE INTO `email_assets` (`offer_id`, `content`, `type`) VALUES ('".$offerId."', '".rtrim($subject, "\r")."', 1)";
            $qry = mysqli_query($connection,$sql);
        }

    }


    $imgUrl = 'http://rackcdn.data-feeds.com/assets/offersconsumer/images/';

    $creatives = $dataOffer['Creatives'];

   foreach($creatives as $creative){
        $image1 = null;
        $image2 = null;

        // check if image creative existe and insert it in DB
      if(array_key_exists (0,$creative['creative_url'])){


            $sql = " INSERT INTO `images` (`url`) VALUES ('".$imgUrl.ltrim($creative['creative_url'][0],"img/")."');";
            if($qry = mysqli_query($connection,$sql)){
                $sql = "SELECT `id` FROM images WHERE `url` = '".$imgUrl.ltrim($creative['creative_url'][0],"img/")."' ORDER BY id DESC LIMIT 1;";
                $qry = mysqli_query($connection,$sql);
                $donne = mysqli_fetch_row($qry);
                $image1 = $donne[0];
             }
        }

        // check if image unsubscribe  existe and insert it in DB
       if(array_key_exists (1,$creative['creative_url']))
            {$sql = " INSERT INTO `images` (`url`) VALUES ('".$imgUrl.ltrim($creative['creative_url'][1],"img/")."');";
                if($qry = mysqli_query($connection,$sql)){
                    $sql = "SELECT `id` FROM images WHERE `url` = '".$imgUrl.ltrim($creative['creative_url'][1],"img/")."' ORDER BY id DESC LIMIT 1;";
                    $qry = mysqli_query($connection,$sql);
                    $donne = mysqli_fetch_row($qry);
                    $image2 = $donne[0];
                 }
            }

            $part2 ="VALUES
            ( $offerId , '".$creative["name"]."',
            '<html>\n<body>\n<center>\n<font face=\"Calibri\"><a href=\"http://[RedirectUrl]\">View\nas Web page</a> |
            <a href=\"http://[OfferUnsubUrl]\">Unsubscribe\n</a></font><br>\n<br>\n<a href=\"http://[RedirectUrl]\">
            <img alt=\"\"\nsrc=\"http://[CreativeImage]\" height=\"\" width=\"\"></a> <br>\n<br>\n
            <a href=\"http://[OfferUnsubUrl]\"><img\nsrc=\"http://[OfferUnsubImage]\"></a>
            <br>\n<br>\n<a href=\"http://[ListUnsubUrl]&email=[RcptEmail]\">
            <img src=\"http://[ListUnsubImage]\" height=\"142\"width=\"722\"></a>\n<br>\n<br>\n</center>\n</body>\n</html>',";


            if(  is_null ($image1) &&  is_null($image2) ) $case = 1;
            if( !is_null ($image1) &&  is_null($image2) ) $case = 2;
            if( !is_null ($image1) && !is_null($image2) ) $case = 3;

            switch($case){
                case 1 :  $part1 = "" ; $part2 = "" ; $part3 = ""; break;
                case 2 :
               $part1 = "INSERT INTO `creatives` ( `offer_id`, `name`, `content`, `creative_image_id`, `is_active`)";
                    $part3 = " $image1, 1)";
                    break;
                case 3 :
                     $part1 = "INSERT INTO `creatives` ( `offer_id`, `name`, `content`, `creative_image_id`,
                     `unsubscribe_image_id`, `is_active`)";
                    $part3 = "$image1, $image2, 1)";
                    break;
            }
                // query of inser creative
            $sql = $part1.$part2.$part3;
            $qry = mysqli_query($connection,$sql);

     }



  //get update link api to update offer status
  $post = $urls."/api/offers/update/".$idOffer;
  //curl for updating offer status
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $post);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "hawk_id=$offerId");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $server_output = curl_exec($ch);
  print_r( $server_output);
  curl_close($ch);

}


