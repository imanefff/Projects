<?php

use App\Advertiser;
use App\Offer;
// use Illuminate\Support\Facades\Storage;

// use PHPUnit\Framework\Constraint\Exception;


class CickHelpers {

public static function getCakeListOffers(Advertiser $adv){

    $url = $adv->url;
    $api_key = $adv->api_key;
    $affiliate_id = $adv->affiliate_id;

        $url = rtrim($url,"/");
        $us =  "$url/Feed?start_at_row=1&row_limit=1000&api_key=$api_key&affiliate_id=$affiliate_id";
         //   return $us;
        set_time_limit(0);
        $file = file_get_contents($us);

       // return $file ;
        $caches =  json_decode($file,true)["data"];
    //    return $caches;
        foreach( $caches as $cache ){
        if($cache['campaign_id'] != null){
        $off['sid']                =  $cache['campaign_id'];
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

        $offers[] = $off;
        }
        }

        return $offers;

    }


    public static function chackOffersActive(Advertiser $adv,$sid){
        $url = $adv->url;
        $api_key = $adv->api_key;
        $affiliate_id = $adv->affiliate_id;
        $url =  rtrim($url,"/");
        $ur  = "$url/Campaign?campaign_id=$sid&api_key=$api_key&affiliate_id=".trim($affiliate_id);
        try{
            set_time_limit(0);
            $file = file_get_contents($ur);
            return "active";
        }catch(Exception $e){
            return "paused";
        }
    }



    public static function ths2(Advertiser $adv,$sid){


        $url = $adv->url;
        $api_key = $adv->api_key;
        $affiliate_id = $adv->affiliate_id;

        $url =  rtrim($url,"/");
        $ur  = "$url/Campaign?campaign_id=$sid&api_key=$api_key&affiliate_id=".trim($affiliate_id);
        try{
            $file = file_get_contents($ur);
        }catch(Exception $e){
            $file = null;
        }

        if(!is_null($file)){
            $caches =  json_decode($file,true)["data"];
            $off ['preview_link'] =  $caches['preview_link'];
            $off ['UnsubLink']    =  $caches['unsubscribe_link'];
            $off ['SupLink']      =  $caches['suppression_link'];
            $off ['offerlnk']     =  $caches['creatives'][0]["unique_link"];
            $off ['fromlines']    =  $caches['from_lines'];
            $off ['subjects']     =  $caches['subject_lines'];
            $off ['links']        =  null;
            $off ['urlToAdd']     =  $advertiser->url_to_add;

            return $off;
        }else return null;
    }



    public static function cickpathAdv(Advertiser $advertiser,$sid){

        $url = $advertiser->url;
        $affiliate_id = $advertiser->affiliate_id;
        $key = $advertiser->api_key;

        $off ['cache'] = $advertiser->cacheoffres()->where('sid',$sid)->first();

        $file = file_get_contents($url."Campaign?campaign_id=$sid&api_key=$key&affiliate_id=$affiliate_id");

        $caches =  json_decode($file,true)["data"][0];
        $idOffer = $caches["offer_id"];

        $off ['offerlnk'] = [];
        foreach($caches['creatives'] as $crtv){
            $links[] =  rtrim($crtv["unique_link"],"s1=");
        }


        foreach( array_unique($links) as $link){
            $off ['offerlnk'][] =  $link;
        }


        $off ['preview_link'] =  $caches['preview_link'];
        $off ['UnsubLink']    =  $caches['unsubscribe_link'];
        $off ['fromlines']    =  $caches['from_lines'];
        $off ['subjects']     =  $caches['subject_lines'];
        $off ['links']        =  null;

        $off ['urlToAdd']  = $advertiser->url_to_add;

        try{
        $file = file_get_contents($url."SuppressionList?offer_id=$idOffer&api_key=$key&affiliate_id=$affiliate_id");
        $caches =  json_decode($file,true);

        $off ['SupLink'] = $caches["download_url"];
        } catch (Exception $e) {
            $off ['SupLink'] = null;
        }

        return $off;
    }

    public static function getCreatives( Advertiser $advertiser , $sid , $offerId ){

        $url           = $advertiser->url;
        $affiliate_id  = $advertiser->affiliate_id;
        $key           = $advertiser->api_key;


        $Alreadycreatives = [] ;
        $cas = [] ;
        if( count(Offer::find($offerId)->creatives) != 0 )
            foreach( Offer::find($offerId)->creatives()->select('email_id','id_sended')->where( 'is_deleted',0)->get() as $creative ){
                $Alreadycreatives [] = $creative['email_id'];
                $cas[$creative['email_id']] = $creative["id_sended"];
            }

        // return $cas;
        $url =  rtrim($url,"/");
        $ur  = "$url/Campaign?campaign_id=$sid&api_key=$key&affiliate_id=".trim($affiliate_id);

        $file          = file_get_contents($ur);
        $caches        = json_decode($file,true)["data"];



        $link          = $caches[0]['creatives_download_url'] ;

        set_time_limit(0);
        $file     = file_get_contents($link );

        $path     = './storage/offers/TempCreativesCake';
        // $path     = storage_path()."/app/public/offers/TempCreativesCake";
        $fp       = fopen($path.'/Creative.zip', 'w');
        fwrite($fp, $file);
        fclose($fp);

        if(is_dir("$path/cretives"))
            system("rm -rf ".escapeshellarg("$path/cretives"));

        if(!is_dir("$path/cretives")) mkdir("$path/cretives");

        $zip = new ZipArchive() ;
        $zip->open($path.'/Creative.zip');
        $zip->extractTo("$path/cretives");
        $zip->close();

        $tree = self::dirToArray("$path/cretives");
        $i = 1 ;
        $creatives=[];

            foreach($tree as $key => $dir){

                if( count( $dir ) != 0  ){
                    $emailID = explode("_",$key,2)[0] ;
                    if(isset($Alreadycreatives) && in_array( $emailID  , $Alreadycreatives)) {
                        if( $cas[ $emailID ] == 0 )
                            $creatives[] = ["sid" => $sid , "creativeID" => $emailID , "description" => explode("_",$key,2)[1], "inside" => "in" ];
                        else
                            $creatives[] = ["sid" => $sid , "creativeID" => $emailID , "description" => explode("_",$key,2)[1], "inside" => "send" ];
                    }else
                        $creatives[] = ["sid" => $sid , "creativeID" => $emailID , "description" => explode("_",$key,2)[1], "inside" => "out" ];

                }
            }
            return  $creatives;
    }


   public static function dirToArray($dir) {

        $result = array();

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value)
        {
           if (!in_array($value,array(".","..")))
           {
              if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
              {
                 $result[$value] = self::dirToArray($dir . DIRECTORY_SEPARATOR . $value);
              }
              else
              {
                 $result[] = $value;
              }
           }
        }

        return $result;
     }

    public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
         foreach ($files as $file) {
           (is_dir("$dir/$file") && !is_link($dir)) ? self::delTree("$dir/$file") : unlink("$dir/$file");
         }
        return true;
    }

    public static function getHtml($name){
        $path       = './storage/offers/TempCreativesCake/cretives/';

        $dirContent = self::dirToArray($path.$name) ;
        $index = -1;
        foreach($dirContent as $key => $element){
            if (preg_match('/.html/',$element))  $index = $key;
            else if(preg_match('/.htm/',$element))  $index = $key;
            else if(preg_match('/.txt/',$element))  $index = $key;
            else if(preg_match('/.HTML/',$element))  $index = $key;
        }

        //  return $dirContent;

         $html = file_get_contents($path.$name."/".$dirContent[$index]);
         $html = iconv('UTF-16', 'UTF-8', $html);
        //  return $html ;
        foreach($dirContent as $key => $img){

            // return $preg;


            // return $rem;
            if($key != $index)
            {
                $img2 = preg_replace("/\(/","\(",$img);
                $img2 = preg_replace("/\)/","\)",$img2);
                $preg = "/(<img.*)((\".*)$img2(\"))(.*)(>)/";
                $rem = "\"".url("/").ltrim($path ,'.').$name."/".$img."\"";
                $html = preg_replace($preg ,  '${1}'.$rem.'${6}' , $html);
            }
            // if($key != $index)  $html = str_replace($img,url("/").ltrim($path ,'.').$name."/".$img, $html);
        }
        // $html = str_replace($img,$path.$name."/".$img, $html);
        return $html ;
    }

    public static function getNameCreative($sid){

        $path       = './storage/offers/TempCreativesCake/cretives/';
        // return ltrim($path ,'.');
        $dirContent = self::dirToArray($path) ;

        foreach ($dirContent as $key => $value){
            if(!count($value) == 0) {
                if (preg_match('/'.$sid.'/',$key)) return str_replace($sid.'_','', $key) ;
            }
           // return $key;
        }
        return  $dirContent;
    }

    public static function getLinks(Advertiser $advertiser,$sid){

        $url = $advertiser->url;
        $affiliate_id = $advertiser->affiliate_id;
        $key = $advertiser->api_key;
        // $ur  = "$url/Campaign?campaign_id=$sid&api_key=$key&affiliate_id=".trim($affiliate_id);
        $file = file_get_contents($url."Campaign?campaign_id=$sid&api_key=$key&affiliate_id=$affiliate_id");


        $caches =  json_decode($file,true)["data"][0];
        // $off ['offerlnk'] = [];
        foreach($caches['creatives'] as $crtv){
            $links[] =  rtrim($crtv["unique_link"],"s1=");
        }

        return array_unique($links);
    }

}
