<?php

use App\Advertiser;
use App\Offer;
use App\CacheOffer;

class Helpers {


        // --------------------------------------- Functions of get from api processing ------------------------------------------

        /**
          *  get data from from api and parce it to array
          *  return array
          */
        private static function getDataFromAPI( $url , $postParametres ){

            $params = "";
            foreach ( $postParametres as $k => $v ) {
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
            $xml= simplexml_load_string( $server_output , null ,LIBXML_NOCDATA);
            $json = json_encode($xml);
            $array =  json_decode($json,true);
            return  $array;
        }


    /**
     * get campaigns  from Api return array of offers
     * return list data of sids
     */

    public static function  getOffersFromAPI( $url , $apikey ){
        $postParametres=[   'apikey'     => $apikey,
                            'apifunc'    => 'getcampaigns'
                        ];

        return self::getDataFromAPI( $url , $postParametres )["data"];
    }

    /**
     * get Email list from api by URL APi and APIKey && sid
     * return list of creatives
     */

    public static function getEmailList( $url , $apikey , $sid ){
        $postParametres=[   'apikey'     => $apikey,
                            'apifunc'    => 'getemaillist',
                            'campaignid' => $sid
        ];

        return self::getDataFromAPI( $url , $postParametres)["data"];
    }

    /**
     * get Email content By SID && Email ID
     * return content creative
     */

    public static function getEmailRaw( $url , $apikey , $sid , $emailId ){
        $postParametres=[   'apikey'     => $apikey,
                            'apifunc'    => 'getemailraw',
                            'campaignid' => $sid,
                            'emailid'    => $emailId
            ];
        return self::getDataFromAPI( $url , $postParametres)["data"];
    }

    /**
     * get list images of creative email
     * return array of images
     */
    public static function getImagesList( $url , $apikey , $sid , $emailId ){
        $postParametres=[   'apikey'     => $apikey,
                            'apifunc'    => 'getemailraw',
                            'campaignid' => $sid,
                            'emailid'    => $emailId ];
        if(self::getDataFromAPI( $url , $postParametres )["data"]["images"] == null) return self::getDataFromAPI( $url , $postParametres )["data"]["images"];
        return self::getDataFromAPI( $url , $postParametres )["data"]["images"]["data"];
    }

    /**
     * get supprission link by sid
     * return link
     */

    public static function getSuppressionLink( $url , $apikey , $sid ){
        $postParametres=[   'apikey'     => $apikey,
                            'apifunc'    => 'getsuppression',
                            'campaignid' => $sid
            ];
        return self::getDataFromAPI( $url , $postParametres )["data"]["suppurl"];
    }


    /**
     * get list of images in array sid && emailid && name of image
     */
// --------------------------------------------
    public static function getImageListeAdapter( $url , $apikey , $sid ){

        $listEmailBySid = self::getEmailList($url,$apikey,$sid);

        foreach( $listEmailBySid as $email){
            $listImages[$email["id"]] = self::getImagesList($url,$apikey,$sid,$email["id"]);
        }

        foreach($listImages as $key =>$list){

            foreach($list as $img){
                if(is_array ($img)){
                    $images[] = [   "sid" => $sid , "emailID" => $key , "name" =>  $img["name"] ,
                                    "key" => $img["key"] ,"case" => "Noop!" ];

                }
                else {
                    $images[] = [   "sid" => $sid , "emailID" => $key , "name" =>  $list["name"],
                                    "key" => $list["key"],"case" => "Noop!" ]; break;
                }
            }

        }
        return $images;
        }


    public static function downloadImage( $url , $apikey , $sid , $emailId , $imageKey , $imageName ){
        $postParametres = [ 'apikey'     =>  $apikey,
                            'apifunc'    => 'getemailimage',
                            'campaignid' =>  $sid,
                            'emailid'    =>  $emailId,
                            'imagekey'   =>  $imageKey
                        ];
        return self::getImage( $url , $postParametres , $imageName );
    }

    public static function baseImage( $url , $apikey , $sid , $emailId , $imageKey ){
        $postParametres = [ 'apikey'     =>  $apikey,
                            'apifunc'    => 'getemailimage',
                            'campaignid' =>  $sid,
                            'emailid'    =>  $emailId,
                            'imagekey'   =>  $imageKey
                        ];
        return self::majImage( $url , $postParametres );
    }


    public static function majImage( $url , $postParametres ){

        $params = "";
		foreach ($postParametres as $k => $v) {
			$params = $params.$k."=".$v."&";
		}

		$params = rtrim($params,'&');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        set_time_limit(0);

        $raw = curl_exec($ch);
        if($raw == "INVALID IMAGEKEY") return "invalide";

        curl_close($ch);

        return $raw;
    }

    public static function binaryToBase64( $url , $apikey , $sid , $emailId , $imageKey ){
        $binary = self::baseImage( $url , $apikey , $sid , $emailId , $imageKey );
        $base = base64_encode( $binary );
        $mime = getimagesizefromstring( $binary )["mime"];
        $str="data:".$mime.";base64,";
        $img = $str.$base;
        return $img;
    }

    // -------------------------------- get content -------------------

    public static function getEmailInfo( $url , $apikey , $sid , Advertiser $adv){

        $emaillist = self::getEmailList($url,$apikey,$sid);
        if($emaillist!=null){
            if ( array_key_exists(0,$emaillist) ){
                foreach( $emaillist as $email ){
                    $emailRaw=Helpers::getEmailRaw($url,$apikey,$sid,$email["id"]);
                    preg_match_all("/(http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?/", $emailRaw['body'] ,$out, PREG_PATTERN_ORDER);
                        $linkss[] = $out[0];
                }

                foreach( $linkss as $ll){
                    if(count($ll) != 0 )
                    {
                        foreach($ll as $ln){
                            $out[0][] = $ln;
                        }
                    }
                }
            } else
        {
            $email=self::getEmailRaw($url,$apikey,$sid,$emaillist["id"]);
            preg_match_all("/(http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?/", $email['body'] ,$out, PREG_PATTERN_ORDER);
        }

        if(count($out[0])>2)
        {
            $linkss = array_unique($out[0]);
            foreach($linkss as $key => $link){
                if(strpos($link,"w3.org") == false){
                    $links[$key] = trim($link,'"');
                }
            }

        }else if(count($out[0]) == 0) $links = null;
        else  $links = trim($out[0][0],'"');


        $offer['links']     = $links;
        if($adv->name == "Sphere Digital"){
            if ( array_key_exists(0,$emaillist) ){
                $i = 0 ;
                do{
                    $email = self::getEmailRaw($url,$apikey,$sid,$emaillist[$i]["id"]);
                    if(!is_array ($email['subjects']))
                        $offer['subjects']  =  explode("\n",$email['subjects']);
                    if(!is_array ($email['fromlines']))
                        $offer['fromlines'] = explode("\n",$email['fromlines']);
                    $i++;
                }while( $i!=count($emaillist));


            }else{
                $email=self::getEmailRaw($url,$apikey,$sid,$emaillist["id"]);
                $offer['subjects']  = (!is_array ($email['subjects']))?explode("\n",$email['subjects']):null;
                $offer['fromlines'] = (!is_array ($email['fromlines']))?explode("\n",$email['fromlines']):null;
            }


        }else{
            $offer['subjects']  = (isset($email['subjects']) && !is_array ($email['subjects']))?explode("\n",$email['subjects']):null;
            $offer['fromlines'] = (isset($email['subjects']) && !is_array ($email['fromlines']))?explode("\n",$email['fromlines']):null;
        }

    }

    else
        $offer = null;

    return $offer;
}


    public static function DowlondAllImagesBySid( $url , $apikey , $sid ){

        $listImageBySid = self::getImageListeAdapter( $url , $apikey , $sid );
            foreach( $listImageBySid as  $val){
                $cases[] = ["sid"=>$sid , "emailID"=>$val["emailID"] , "name" => $val["name"],
                            "case"=>self::downloadImage( $url , $apikey , $sid , $val["emailID"] , $val["key"] , $val["name"])];
            }
        return $cases;

    }

    /**
     * gets banary code from API through Curl and save it with createImage function
     *
     */

    public static function getImage( $url , $postParametres , $imageName ){

        $params = "";
		foreach ($postParametres as $k => $v) {
			$params = $params.$k."=".$v."&";
		}

		$params = rtrim( $params , '&' );
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_GET , 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        set_time_limit(0);

        $raw = curl_exec($ch);
        if($raw == "INVALID IMAGEKEY") return "invalide";

        curl_close($ch);
        return  self::createImage( $raw , $postParametres['campaignid'] , $postParametres['emailid'] , $imageName );

    }


    /**
    * get data of image and save it in dir with the name
    * $imageCode is a binary code to convert to image
    */

    private static function createImage( $imageCode , $sid , $emailId , $imageName ) {
        $path  = './storage/offers/images/';

        $link = self::createImagePath( $path . $sid );
        $link = self::createImagePath($link.'/'.$emailId);

        $saveto = $link.'/'.$imageName;
        if (file_exists($saveto)) {
            unlink($saveto);
        }

        $fp = fopen($saveto, 'x');
        fwrite($fp, $imageCode);
        fclose($fp);
        return "added";
    }


    /**
     * create dir of images sid/emailid/imagename.extension
     */

    private static function createImagePath( $link ){
        if(!is_dir($link)){
            mkdir($link,0777);
        }
        return $link;
    }

// ---------------------------
    public static function imageTo64(){

        $img = file_get_contents("CropImage.png");
        $mime =  getimagesizefromstring ($img)["mime"];
        $img = base64_encode($img);
        $img = 'data:' . $mime. ';base64,' . $img ;
        return $img;
    }


    public static function findItem( $itemToFind , $arrayToSearch ){
        foreach( $arrayToSearch as $item )
            if( $itemToFind == $item->email_id ) return  $item->id ;
        return -1;
    }


    public static function getRealSuppressionLink( $supp ){
        if(!is_null( $supp ) ){
            preg_match("/optizmo.net\/(.*)/",$supp ,$out, PREG_OFFSET_CAPTURE);
            if(count($out) != 0){
            preg_match("/[a-zA-Z0-9]{1,2}-[a-zA-Z0-9]{1,4}-[a-zA-Z0-9]{1,3}-[a-zA-Z0-9]{1,32}/", $supp , $out , PREG_OFFSET_CAPTURE);
            $final_url = $out[0][0];

            //$offer = "https://mailer_api.optizmo.net/accesskey/download/".$final_url."?token=MIWHrKeLD64rpTzWwDHuBoa0jaOb0z5w";
            $offer = "https://mailer-api.optizmo.net/accesskey/download/".$final_url."?token=MIWHrKeLD64rpTzWwDHuBoa0jaOb0z5w";
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );
            $content = file_get_contents( $offer , false , stream_context_create($arrContextOptions) );
            $Offers = json_decode($content,true);
            return $Offers ["download_link"];
            }else return $supp;
        }
    }

    public static function reloadSuprissionlink( Offer $ofr ){
        if( is_null($ofr->suppression_link ) ){
            $ofr->suppression_link =  self::getSuppressionLink($ofr->advertiser->url,$ofr->advertiser->api_key,$ofr->sid);

            $supp =  $ofr->suppression_link;
            preg_match("/optizmo.net\/(.*)/",$supp ,$out, PREG_OFFSET_CAPTURE);
            if(count($out) != 0){
                preg_match("/[a-zA-Z0-9]{1,2}-[a-zA-Z0-9]{1,4}-[a-zA-Z0-9]{1,3}-[a-zA-Z0-9]{1,32}/",$ofr->suppression_link,$out, PREG_OFFSET_CAPTURE);
                $final_url = $out[0][0];
                // $offer = "https://mailer_api.optizmo.net/accesskey/download/".$final_url."?token=MIWHrKeLD64rpTzWwDHuBoa0jaOb0z5w";
                $offer = "https://mailer-api.optizmo.net/accesskey/download/".$final_url."?token=MIWHrKeLD64rpTzWwDHuBoa0jaOb0z5w";
                $ofr->suppression_link_2 =$offer;
            }
            $ofr->save();
        }
    }

    public static function reloadSuprissionAuters( Offer $ofr ){

        $supp =  $ofr->suppression_link;
        preg_match("/optizmo.net\/(.*)/",$supp ,$out, PREG_OFFSET_CAPTURE);
        if(count($out) != 0){
            preg_match("/[a-zA-Z0-9]{1,2}-[a-zA-Z0-9]{1,4}-[a-zA-Z0-9]{1,3}-[a-zA-Z0-9]{1,32}/",$ofr->suppression_link,$out, PREG_OFFSET_CAPTURE);
            $final_url = $out[0][0];
            //$offer = "https://mailer_api.optizmo.net/accesskey/download/".$final_url."?token=MIWHrKeLD64rpTzWwDHuBoa0jaOb0z5w";
            $offer = "https://mailer-api.optizmo.net/accesskey/download/".$final_url."?token=MIWHrKeLD64rpTzWwDHuBoa0jaOb0z5w";
            $ofr->suppression_link_2 =$offer;
        }
        $ofr->save();

    }


    public static function hitpathAdv(Advertiser $advertiser , $sid ){

        $url =  $advertiser->url;
        $apikey = $advertiser->api_key;

        $offer = $advertiser->cacheoffres()->where('sid', $sid)->get();
        $SupLink   = self::getSuppressionLink($url,$apikey,$sid);

        $UnsubLink = self::getEmailInfo($url,$apikey,$sid,$advertiser);

        $offer['SupLink']   = ($SupLink!=null)?$SupLink:null;
        $offer['UnsubLink'] = ($UnsubLink!=null && isset($UnsubLink['UnsubLink']))?$UnsubLink['UnsubLink']:null;
        $offer['fromlines'] = ($UnsubLink!=null && isset($UnsubLink['fromlines']))?$UnsubLink['fromlines']:null;
        $offer['offlnk']    = ($UnsubLink!=null && isset($UnsubLink['offlnk']))?$UnsubLink['offlnk']:null;
        $offer['subjects']  = ($UnsubLink!=null && isset($UnsubLink['subjects']))?$UnsubLink['subjects']:null;
        $offer['urlToAdd']  = $advertiser->url_to_add;
        $offer['links']     = ($UnsubLink!=null && isset($UnsubLink['links']))?$UnsubLink['links']:null;
        return $offer;

    }


    public static function hitpathAdvNew(Advertiser $advertiser , $sid ){

        $apikey = $advertiser->api_key;
        $url = $advertiser->url;

        $thisOffer         = $advertiser->cacheoffres()->where('sid', $sid)->first();
        $offer ["cache"]   = $thisOffer ;
        $sup               = self::getSuppressionLink( $url , $apikey , $sid );
        if ( $sup != null ){
            $saveSup = CacheOffer::find($thisOffer->id);
            $saveSup->sup_link = $sup ;
            $saveSup->save();
        }
        $offer ["SuppressionLink"]    = ( $sup !=null )?$sup:null;

        $emaillist = self::getEmailList( $url , $apikey , $sid );
        if( $emaillist != null ){
            if ( array_key_exists( 0 , $emaillist ) ){
                    foreach( $emaillist as $email){
                        $creatives[$email['id']] = self::getEmailRaw( $url , $apikey , $sid , $email['id'] );
                    }
                }else{
                    $creatives[$emaillist['id']] = self::getEmailRaw( $url , $apikey , $sid , $emaillist['id'] );
                }
        }
        $offer ["creatives"]  = $creatives;
        return  $offer;
    }

    public static function hitpathResponce( Advertiser $advertiser , $sid ){
        $array = self::hitpathAdvNew( $advertiser , $sid );
        return   self::TraitString( $array , $advertiser );
    }



    public static function TraitString( $offerDetaile , Advertiser $adv ){

        $array = $offerDetaile["creatives"] ;

        $offer["cache"]           = $offerDetaile["cache"];
        $offer["SuppressionLink"] = $offerDetaile["SuppressionLink"];
        $offer["urlToAdd"]        = $adv->url_to_add;

        foreach($array as $creative){
            preg_match_all("/(http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?/", $creative['body'] ,$out, PREG_PATTERN_ORDER);
            $linkArray[] = $out[0];
        }
        foreach( $linkArray as $ll){
            if(count($ll) != 0 )
            {
                foreach($ll as $ln){
                    $out[0][] = $ln;
                }
            }
        }

        if(count($out[0])>2)
        {
            foreach(array_unique($out[0]) as $key => $link){
                if(strpos($link,"w3.org") == true  || strpos($link,".png") == true  || strpos($link,".jpg") == true|| strpos($link,".gif") == true){
                }else $links[] = trim($link,'"');
            }

        }else if(count($out[0]) == 0) $links = null;
        else  $links = trim($out[0][0],'"');

        $offer['links']  =  $links ;

            $offer['fromlines' ] = null;
            $offer['subjects'  ] = null;

            foreach($array as $creative){

                if( !is_array ($creative['subjects']))
                    $offer['subjects']  =  explode("\n",$creative['subjects']);
                if( !is_array ($creative['fromlines']))
                    $offer['fromlines'] =  explode("\n",$creative['fromlines']);
                if( $creative['subjects'] != null && $creative['fromlines'] != null ) break;

            }

        return $offer;
    }



    public static function getLinks( Advertiser $advertiser , $sid ){
        $array = self::hitpathAdvNew( $advertiser , $sid );

        foreach($array["creatives"] as $creative){
            preg_match_all("/(http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?/", $creative['body'] ,$out, PREG_PATTERN_ORDER);
            if(count($out[0])>1)break;
        }

        if(count($out[0])>2)
        {
            foreach(array_unique($out[0]) as $key => $link){
                if(strpos($link,"w3.org") == true  || strpos($link,".png") == true  || strpos($link,".jpg") == true|| strpos($link,".gif") == true){
                }else $links[] = trim($link,'"').$advertiser->url_to_add;
            }

        }else if(count($out[0]) == 0) $links = null;
        else  $links = trim($out[0][0],'"');

        return $links ;

    }


/*
//-----------------------------------
    public static function getCreatives( Advertiser $advertiser , $sid ){

        foreach(Offer::where([ ['sid',$sid] , ['advertiser_id',$advertiser->id]])->first()->creatives()->select('email_id')->get() as $creative){
            $alreadyCreatives[] = $creative['email_id'];
        }

        $listCreative = self::getEmailList($advertiser->url,$advertiser->api_key,$sid);

        if ( array_key_exists(0,$listCreative) ) {
            foreach( $listCreative as $key => $creative ){
                if(isset($alreadyCreatives) && in_array( $creative["id"], $alreadyCreatives)) {
                    $creatives[] =["sid" => $sid , "creativeID" => $creative["id"] , "description" =>  Helpers::getEmailRaw($advertiser->url,$advertiser->api_key,$sid,$creative["id"])["description"] ,"inside"=> "in" ];
                }else{
                    $creatives[] =["sid" => $sid , "creativeID" => $creative["id"] , "description" =>  Helpers::getEmailRaw($advertiser->url,$advertiser->api_key,$sid,$creative["id"])["description"] ,"inside" => "out" ];
                }
            }
        }else{
            if(in_array( $creative["id"], $alreadyCreatives))
                $creatives[] =["sid" => $sid , "creativeID" => $listCreative["id"] , "description" => Helpers::getEmailRaw($advertiser->url,$advertiser->api_key,$sid, $listCreative["id"])["description"]  ,"inside"=> 'in' ];
            else $creatives[] =["sid" => $sid , "creativeID" => $listCreative["id"] , "description" => Helpers::getEmailRaw($advertiser->url,$advertiser->api_key,$sid, $listCreative["id"])["description"] ,"inside"=> 'out' ];
        }

        return $creatives;
    }

*/


    /*** kayn  */
    public static function getCreativesList( Advertiser $advertiser , $sid , $offerId ){
        $alreadyCreatives = [];
        if( count( Offer::find($offerId)->creatives) != 0){
            foreach( Offer::find($offerId)->creatives()->select('email_id','id_sended')->where( 'is_deleted' , 0 )->get() as $creative){
                $alreadyCreatives[] = $creative['email_id'];
                $cas[$creative['email_id']] = $creative["id_sended"];
            }
        }

        $listCreative = self::getEmailList( $advertiser->url , $advertiser->api_key , $sid );

        if ( array_key_exists(0,$listCreative) ) {
            foreach( $listCreative as $key => $creative ){
                if(isset($alreadyCreatives) && in_array( $creative["id"], $alreadyCreatives)) {
                    if( $cas[ $creative["id"] ] == 0 )
                        $creatives[] = [    "sid" => $sid ,
                                            "creativeID" => $creative["id"] ,
                                            "description" =>  self::getEmailRaw( $advertiser->url , $advertiser->api_key , $sid , $creative["id"] )["description"] ,
                                            "inside"=> "in" ];
                    else
                        $creatives[] = [ "sid" => $sid ,
                                        "creativeID" => $creative["id"] ,
                                        "description" =>  self::getEmailRaw( $advertiser->url , $advertiser->api_key , $sid , $creative["id"])["description"] ,
                                        "inside"=> "send" ];
                }else{
                    $creatives[] = [ "sid" => $sid ,
                                    "creativeID" => $creative["id"] ,
                                    "description" =>  self::getEmailRaw( $advertiser->url , $advertiser->api_key , $sid , $creative["id"])["description"] ,
                                    "inside" => "out" ];
                }
            }
        }else{
            if(in_array( $listCreative["id"], $alreadyCreatives)){
                if( $cas[ $creative["id"] ] == 0 )
                    $creatives[] = [    "sid" => $sid ,
                                        "creativeID" => $listCreative["id"] ,
                                        "description" => self::getEmailRaw( $advertiser->url , $advertiser->api_key , $sid , $listCreative["id"])["description"] ,
                                        "inside"=> 'in' ];
                else
                    $creatives[] = [    "sid" => $sid ,
                                        "creativeID" => $listCreative["id"] ,
                                        "description" => self::getEmailRaw( $advertiser->url , $advertiser->api_key , $sid , $listCreative["id"])["description"] ,
                                        "inside"=> 'send' ];
            } else $creatives[] = [ "sid" => $sid ,
                                    "creativeID" => $listCreative["id"] ,
                                    "description" => self::getEmailRaw( $advertiser->url , $advertiser->api_key , $sid , $listCreative["id"])["description"] ,
                                    "inside"=> 'out' ];
        }

        return $creatives;
    }





    public static function getCreative( $offerId , $creativeId ){

        $advertiser = Offer::find($offerId)->advertiser;
        $sid =  Offer::find($offerId)->sid;
        $url =  $advertiser->url;
        $apikey = $advertiser->api_key;

        $tempcreative = self::getEmailRaw($url,$apikey,$sid,$creativeId);

        if($tempcreative["images"] != null){
            $creativeImages =  $tempcreative["images"]["data"];

        foreach($creativeImages as $image){
            if(is_array($image)){
                $images[] = [   $image["name"] =>  self::binaryToBase64($url,$apikey,$sid,$creativeId,
                                $image["key"]) ];
            } else {
                $images[] = [ $creativeImages["name"]  => self::binaryToBase64($url,$apikey,$sid,$creativeId,
                            $creativeImages["key"])];break;
            }

        }
    } else{
        $images=null;
    }

    if(!is_null($images))
    foreach( $images as $arrayImage){
        $preg = "#".key( $arrayImage )."#i";
        $tempcreative["body"] = preg_replace($preg,  $arrayImage[key( $arrayImage )] , $tempcreative["body"]);
    }
    return $tempcreative["body"];

    }




}



