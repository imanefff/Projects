<?php

use App\Advertiser;
use App\CacheOffer;
use App\Offer;
use Illuminate\Support\Facades\DB;

class EverflowHelpers {

    private static function CurlFunction ( $url , $apikey){

        $curl = curl_init();

        curl_setopt_array($curl, array(
	        CURLOPT_URL => $url ,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => "",
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 30,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => "GET",
	        CURLOPT_HTTPHEADER => array(
		        "x-eflow-api-key: $apikey",
		        "content-type: application/json"
	        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode( $response , true) ;
    }

    private static function getAllOffers( Advertiser $advertiser  ) {

        $offers = [];
        $listOffers = self::CurlFunction( "$advertiser->url/offersrunnable?page=1&page_size=500" , $advertiser->api_key );

        if( !isset( $listOffers ["offers"] ) ) return $offers;

        $modal = (  $listOffers ["paging"]["total_count"] % $listOffers ["paging"]["page_size"] == 0 ) ? 0 : 1 ;
        $totalPages = intval( $listOffers ["paging"]["total_count"]/$listOffers ["paging"]["page_size"] ) + $modal ;
        $offers = $listOffers ["offers"] ;

        sleep(1);

        for( $i = 2 ; $i <= $totalPages ; $i++){
            $listOffers = self::CurlFunction( "$advertiser->url/offersrunnable?page=$i&page_size=500" , $advertiser->api_key );
            if( $listOffers ["offers"]  ) $offers = array_merge( $offers , $listOffers["offers"] );
            sleep(2);
        }

        return $offers;
    }

    public static function getListOffer( Advertiser $advertiser){

        // $listOffers = self::CurlFunction( "$advertiser->url/offersrunnable" ,  $advertiser->api_key  );
        // $listOffers = self::CurlFunction( "$advertiser->url/alloffers" ,  $advertiser->api_key  );
        // return $listOffers ["paging"];
        // paging


        $listOffers = self::getAllOffers( $advertiser );

        // return count( $listOffers );
        // return $listOffers;



        $offers = [] ;

        foreach( $listOffers  as $offer) {
            // foreach( $listOffers ["offers"] as $offer) {

            $off ['sid']          = $offer [ "network_offer_id" ] ;
            $off ['name']         = $offer [ "name" ] ;
            $off ['description']  = $offer [ "html_description" ] ;
            $off ['payout']       = $offer [ "relationship" ] [ "payouts" ] [ "entries" ] [0] [ "payout_amount" ] ;
            $off ['unit']         = $offer [ "relationship" ] [ "payouts" ] [ "entries" ] [0] [ "payout_type" ] ;
            $off ['category']     = $offer [ "relationship" ] [ "category" ] [ "name" ];
            $off ['preview_url']  = $offer [ "preview_url" ];

            $geo = "";
            foreach (  $offer [ "relationship" ][ "ruleset" ]["countries"]  as $value ) {
                $geo .= " , ".$value["country_code"];
            }

            $geo = ltrim( $geo , " , " );

            $off ['geotargeting'] = $geo ;

            $offers [] =  $off;

        }

        // return $offers;
        $erreurs = [];
        $offs = [];
        foreach ( $offers as $key =>  $cacheoffer) {

            // file_put_contents("aaaa.txt" , json_encode( $cacheoffer ));
            try{
                $cach                = new CacheOffer;
                $cach->sid           = $cacheoffer [ "sid" ] ;
                $cach->name          = $cacheoffer [ "name" ] ;
                $cach->description   = $cacheoffer [ "description" ] ;
                $cach->payout        = $cacheoffer [ "payout" ] ;
                $cach->unit          = $cacheoffer [ "unit" ] ;
                $cach->category      = $cacheoffer [ "category" ] ;
                $cach->geotargeting  = ( strlen( $cacheoffer [ "geotargeting" ] ) > 245 ) ? "all" : $cacheoffer [ "geotargeting" ] ;
                $cach->api_type      = $advertiser->api_type ;
                $cach->advertiser_id = $advertiser->id ;
                $cach->save();
                $offs [] = $cach;
            }catch(Exception $e){
                $erreurs[] = $e->getMessage();
            }



        }
        return [ $offs , $erreurs ];
    }

    public static function getOfferDetails(  Advertiser $advertiser , $sid ){

        $cashOffer =  self::CurlFunction( "$advertiser->url/offers/$sid" , $advertiser->api_key );

        $off ['pppppp'] = $cashOffer ;
        // return  $cashOffer ;
        $off ['cache'] = $advertiser->cacheoffres()->where('sid', $sid )->first();
        // return  $off ['cache'] ;
        $off ['preview_link'] = $cashOffer["preview_url"];
        $off ['offerlnk'][]   = $cashOffer["tracking_url"];
        $off ['urlToAdd']     = $advertiser->url_to_add;

        if( $off ['cache']["category"] == "Dating" ){
            $arrayInfos =  explode( "\n\n\n" , $cashOffer ["html_description"] );

            foreach ( $arrayInfos as  $value) {
                // $preg [] = $value ;
                if( preg_match( "#Unsubscribe link#i" , $value) ||  preg_match( "#Opt-out link access#i" , $value) )
                    $preg  = explode( " -" , $value );
                else
                    $preg  = explode( ":" , $value );
                    $pregs[ $preg [0] ] = $preg [1] ;
            }

            $off ['subjects']     = explode( "\n" ,  trim( $pregs [ "Subject Lines" ] , "\n" ) );
            $off ['fromlines']    = explode( "\n" ,  trim( $pregs [ "From Lines" ] , "\n" ) );
            $off ['UnsubLink']    = $pregs [ "Unsubscribe link" ];
            $off ['SupLink']      = $pregs [ "Opt-out link access" ];

        }else{
            $off ['subjects']     = ( gettype( $cashOffer["relationship"]["email"]["subject_lines"]) == "array" ) ?
                                     $cashOffer["relationship"]["email"]["subject_lines"] : [ $cashOffer["relationship"]["email"]["subject_lines"] ];
            $off ['fromlines']    = ( gettype( $cashOffer["relationship"]["email"]["from_lines"]) == "array" ) ?
                                    $cashOffer["relationship"]["email"]["from_lines"] : [ $cashOffer["relationship"]["email"]["from_lines"] ];
            $off ['UnsubLink']    = (   $cashOffer["relationship"]["email_optout"]["unsub_link"] == null ||
                                        $cashOffer["relationship"]["email_optout"]["unsub_link"] == "" ) ? null :  $cashOffer["relationship"]["email_optout"]["unsub_link"] ;
            $off ['SupLink']      = $cashOffer["relationship"]["email_optout"]["suppression_file_link"]  ;
            // $off ['SupLink']      = ( $off ['SupLink'] == "" || $off ['SupLink'] == null ) ? $cashOffer["relationship"]["integrations"]["optizmo"]["mailer_access_key"] : null ;
            if( isset( $cashOffer["relationship"]["integrations"]["optizmo"]["mailer_access_key"]) &&
                !empty( $cashOffer["relationship"]["integrations"]["optizmo"]["mailer_access_key"] )
            ){
                $sup = $cashOffer["relationship"]["integrations"]["optizmo"]["mailer_access_key"];
                $id = explode( "/" , $sup )[ count( explode( "/" , $sup ) ) - 1 ];

                // $off ['SupLink'] = $id ;

                $off ['SupLink'] = "https://mailer-api.optizmo.net/accesskey/download/$id?token=MIWHrKeLD64rpTzWwDHuBoa0jaOb0z5w";

                // https://www.affiliateaccesskey.com/m-wckw-h92-4b42d7843151ae958b21e8b8252ee0dd


            }
        }

            foreach (  $cashOffer [ "relationship" ][ "creatives" ]["entries"]  as $value ){
                if ( $value["email_from"] != "" )
                    $off [ 'fromlines' ][] = $value["email_from"];
                if ( $value["email_subject"] != "" )
                    $off [ 'subjects' ][]  =  $value["email_subject "];
            }

        return $off;
    }

    public static function getCreativelist( Advertiser $advertiser , $sid , $offerId ){

        $cashOffer =  self::CurlFunction( "$advertiser->url/offers/$sid" , $advertiser->api_key );

        $creatives_list = $cashOffer[ "relationship" ][ "creatives" ][ "entries" ] ;

        $creatives_list_html = [];

        foreach( $creatives_list as $creative ){

            if( $creative["creative_type"] == "archive" ){

                $path      = './storage/offers/TempCreativesEverFlow';
                if(is_dir("$path/cretives")) system("rm -rf ".escapeshellarg("$path/cretives"));
                mkdir("$path/cretives");

                set_time_limit(0);
                $file     = file_get_contents( $creative["resource_url"]  );
                $fp       = fopen($path.'/Creative.zip', 'w');
                fwrite($fp, $file);
                fclose($fp);

                $zip = new ZipArchive() ;
                $zip->open($path.'/Creative.zip');
                $zip->extractTo("$path/cretives");
                $zip->close();

                if(is_dir("$path/cretives/__MACOSX")) system("rm -rf ".escapeshellarg("$path/cretives/__MACOSX"));
                $tree = self::dirToArray("$path/cretives");

                $files = static::arrayToPath( $tree );
                $creatives_html = [];
                $creatives_html = array_values ( array_filter( $files , function ( $item ) {
                    return preg_match( '/\.html|\.htm|\.HTML/m' , $item , $matches);
                } ));
                $creatives_list_html[ $creative ["network_offer_creative_id"] ]["name"] = $creative["name"];
                $creatives_list_html[ $creative ["network_offer_creative_id"] ]["creatives"] = $creatives_html;

            }else  {
                $creatives_list_html[ $creative ["network_offer_creative_id"] ]["name"] = $creative["name"];
                $creatives_list_html[ $creative ["network_offer_creative_id"] ]["creatives"] = $creative["html_code"];
            }


        }

        // return  $creatives_list_html ;
        $alreadyCreatives = [];
        $cas = [] ;

        // return Offer::find($offerId)->creatives ;

        if( count(Offer::find($offerId)->creatives) != 0 )
        foreach( Offer::find($offerId)->creatives()->select('email_id','id_sended')->where( 'is_deleted',0)->get() as $creative ){
            $alreadyCreatives [] = $creative['email_id'];
            $cas[$creative['email_id']] = $creative["id_sended"];
        }

        $creatives = [] ;

        foreach( $creatives_list_html as $key => $crtv ){

            if( is_array( $crtv["creatives"] ) ){

                foreach( $crtv["creatives"] as $k => $val ){
                    if( in_array( $key.str_pad($k, 2, '0', STR_PAD_LEFT) , $alreadyCreatives) ) {

                        if( $cas[ $key.$k ] == 0 )
                            $creatives[] =[ "sid"           => $sid ,
                                            // "creativeID"    => $key.str_pad($k, 2, '0', STR_PAD_LEFT) ,
                                            "creativeID_v"  => $key.str_pad($k, 2, '0', STR_PAD_LEFT) ,
                                            "creativeID"    => $key ,
                                            "path"          => $val ,
                                            "description"   => $crtv["name"].$k ,
                                            "inside"        => "in"
                            ];
                        else
                            $creatives[] =[ "sid"           => $sid ,
                                            "creativeID"    => $key ,
                                            "creativeID_v"    => $key.str_pad($k, 2, '0', STR_PAD_LEFT),
                                            "path"           => $val ,
                                            "description"   => $crtv["name"].$k,
                                            "inside"        => "send"
                            ];
                    }else
                        $creatives[] =[ "sid"           => $sid ,
                                        "creativeID"    => $key ,
                                        "creativeID_v"    => $key.str_pad($k, 2, '0', STR_PAD_LEFT) ,
                                        "path"          =>  $val ,
                                        "description"   => $crtv["name"].$k,
                                        "inside"        => "out"
                                    ];



                }



            }else{

                if( in_array( $key , $alreadyCreatives) ) {

                if( $cas[ $key ] == 0 )
                    $creatives[] =[ "sid"           => $sid ,
                                    "creativeID"    => $key,
                                    "description"   => $crtv["name"],
                                    "inside"        => "in"
                                ];
                else
                    $creatives[] =[ "sid"           => $sid ,
                                    "creativeID"    => $key,
                                    "description"   => $crtv["name"],
                                    "inside"        => "send"
                    ];
            }else
            $creatives[] =[ "sid"           => $sid ,
                            "creativeID"    => $key ,
                            "description"   => $crtv["name"],
                            "inside"        => "out"
                        ];

            }

        }

        return  $creatives;


        foreach( $creatives_list as $crtv ){

            if( in_array( $crtv["network_offer_creative_id"] , $alreadyCreatives) ) {

                if( $cas[ $crtv["network_offer_creative_id"]] == 0 )
                    $creatives[] =[ "sid"           => $sid ,
                                "creativeID"    => $crtv["network_offer_creative_id"],
                                "description"   => $crtv["name"],
                                "inside"        => "in"
                                ];
                else
                    $creatives[] =[ "sid"           => $sid ,
                        "creativeID"    => $crtv["network_offer_creative_id"],
                        "description"   => $crtv["name"],
                        "inside"        => "send"
                    ];
            }else
            $creatives[] =[ "sid"           => $sid ,
                            "creativeID"    => $crtv["network_offer_creative_id"],
                            "description"   => $crtv["name"],
                            "inside"        => "out"
                        ];
        }

        return  $creatives;

    }


    // public static function getCreativelist( Advertiser $advertiser , $sid , $offerId ){

    //     $cashOffer =  self::CurlFunction( "$advertiser->url/offers/$sid" , $advertiser->api_key );

    //     $creatives_list = $cashOffer[ "relationship" ][ "creatives" ][ "entries" ] ;

    //     return $cashOffer ;

    //     $alreadyCreatives = [];
    //     $cas = [] ;
    //     // return Offer::find($offerId)->creatives;
    //     if( count(Offer::find($offerId)->creatives) != 0 )
    //     foreach( Offer::find($offerId)->creatives()->select('email_id','id_sended')->where( 'is_deleted',0)->get() as $creative ){
    //         $alreadyCreatives [] = $creative['email_id'];
    //         $cas[$creative['email_id']] = $creative["id_sended"];
    //     }

    //     $creatives = [] ;
    //     foreach( $creatives_list as $crtv ){

    //         if( in_array( $crtv["network_offer_creative_id"] , $alreadyCreatives)) {

    //             if( $cas[ $crtv["network_offer_creative_id"]] == 0 )
    //                 $creatives[] =[ "sid"           => $sid ,
    //                             "creativeID"    => $crtv["network_offer_creative_id"],
    //                             "description"   => $crtv["name"],
    //                             "inside"        => "in"
    //                             ];
    //             else
    //                 $creatives[] =[ "sid"           => $sid ,
    //                     "creativeID"    => $crtv["network_offer_creative_id"],
    //                     "description"   => $crtv["name"],
    //                     "inside"        => "send"
    //                 ];
    //         }else
    //         $creatives[] =[ "sid"           => $sid ,
    //                         "creativeID"    => $crtv["network_offer_creative_id"],
    //                         "description"   => $crtv["name"],
    //                         "inside"        => "out"
    //                     ];
    //     }

    //     return  $creatives;

    // }

    public static function getCreative( Advertiser $advertiser , $sid , $emailID ){


        $cashOffer =  self::CurlFunction( "$advertiser->url/offers/$sid" , $advertiser->api_key );

        $creatives_list = $cashOffer[ "relationship" ][ "creatives" ][ "entries" ];

        $creative = array_filter( $creatives_list , function ( $item ) use ( $emailID ) { return $item["network_offer_creative_id"] == $emailID; } );

        $creative = ( count( $creative ) > 0 ) ? $creative[0] : null ;

        if( $creative["creative_type"] == "archive" ){

            $path      = './storage/offers/TempCreativesEverFlow';
            if(is_dir("$path/cretives")) system("rm -rf ".escapeshellarg("$path/cretives"));
            mkdir("$path/cretives");

            set_time_limit(0);
            $file     = file_get_contents( $creative["resource_url"]  );
            $fp       = fopen($path.'/Creative.zip', 'w');
            fwrite($fp, $file);
            fclose($fp);

            $zip = new ZipArchive() ;
            $zip->open($path.'/Creative.zip');
            $zip->extractTo("$path/cretives");
            $zip->close();

            if(is_dir("$path/cretives/__MACOSX")) system("rm -rf ".escapeshellarg("$path/cretives/__MACOSX"));
            $tree = self::dirToArray("$path/cretives");

            $files = static::arrayToPath( $tree );
            $creatives_html = [];
            $creatives_html = array_values ( array_filter( $files , function ( $item ) {
                return preg_match( '/\.html|\.htm|\.HTML/m' , $item , $matches);
            } ));

            return self::getHtml( $creatives_html );

        }else  return $creative["html_code"];


    }


    private static function arrayToPath( array $array )  {

        $files = static::getFilesRecurtion( $array ) ;
        return explode(",-," , $files);
        return $files;

    }



    private static function getFilesRecurtion( $node , string $path = "" ){

        if ( is_array($node)) {
            $ret = '';
            foreach($node as $key => $val){
                $newKey=(gettype( $key ) == "string" ) ? $key : "";
                $ret .= self::getFilesRecurtion( $val , $path.'/'.$newKey);
            }

            return $ret;
        }else return rtrim($path,"/").'/'.$node.",-,";
    }



    private static function dirToArray($dir) {

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

     public static function getHtml( $three ){

        $path      = './storage/offers/TempCreativesEverFlow/cretives/';

        $file_name = array_filter( $three , function ( $item ) {
                return  preg_match('/\.html|\.htm|\.txt|\.HTML/m' , $item , $matches) ; } );
        // $file_name;
        $file_name = ( count( $file_name ) > 0 ) ? $file_name[0] : null ;

        $html = file_get_contents($path.ltrim($file_name));
        return $html ;
        // $html = iconv('UTF-16', 'UTF-8', $html);
        // return $html ;
    }

    public static function getNameCreative(Advertiser $advertiser,$sid , $CeativeId ){

        $cashOffer =  self::CurlFunction( "$advertiser->url/offers/$sid" , $advertiser->api_key );

        $creatives_list = $cashOffer[ "relationship" ][ "creatives" ][ "entries" ];

        $creative = array_filter( $creatives_list , function ( $item ) use ( $CeativeId ) { return $item["network_offer_creative_id"] == $CeativeId; } );

        $creative = ( count( $creative ) > 0 ) ? $creative[0] : null ;

        return $creative["name"];


    }

}
