<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Offer;
use App\Creative;

use Helpers;
use InhouseHelpers;


class OffersController extends Controller
{

    use ApiResponseTrait;

    public function offer($id){
        $offer = Offer::find($id);

        if  ($offer->is_added == 0 )
            $case = "Add";
        else if ($offer->is_added == 1 )
            $case = "Edit";

        if($offer->advertiser->name == "hitpath")
            Helpers::reloadSuprissionlink($offer);
        else if($offer->advertiser->name == "W4"	)
            InhouseHelpers::reloadSuprissionlink($offer);
        else
            Helpers::reloadSuprissionAuters($offer);

        if($case!="Add" && $offer->hawk_id != null)
            $offerSend['hawkId']           =  $offer->hawk_id;
        else
            $offerSend['hawkId']           = null;

            $offerSend['case']             = $case;
            $offerSend['advertiser_id']    = $offer->advertiser->adv_id;
            $offerSend['sid']              = ($offer->advertiser->api_type != "cake")?$offer->sid:$offer->advertiser->cacheoffres()->where('sid',$offer->sid)->first()->offer_id;
            $offerSend['name']             = $offer->name;
            $offerSend['offer_url']        = $offer->offer_url;
            $offerSend['preview_url']      = $offer->preview_url;
            $offerSend['unsubscribe_url']  = $offer->unsubscribe_url;
            $offerSend['categories']       = $offer->categories;
            $offerSend['description']      = $offer->description;
            $offerSend['type']             = $offer->type;
            $offerSend['amount']           = $offer->default_payout;
            $offerSend['entity_id']        = $offer->user->entity->id_entity;

            $offerSend['suppression_link'] = (!is_null($offer->suppression_link_2))?$offer->suppression_link_2:$offer->suppression_link;

        foreach($offer->creatives()->where([["is_deleted",0],["id_sended",0]])->get() as $key => $creative){
            $offerSend['Creatives'][$key]['name'] = $creative->name;
            foreach($creative->creativeimages as $content){
                $offerSend['Creatives'][$key]['creative_url'][] = $content->creative_url;
            }
        }
        $pregFroms = preg_replace("/\\\\n/", "\n", $offer->from_lines);
        $froms  =  explode("\n",$pregFroms );
        foreach($froms as $from)
            if (strlen($from) != 0) if($from != "\r"  ) $offerSend['from_lines'][] =  $from ;

        $pregSubjct = preg_replace("/\\\\n/", "\n", $offer->subject_lines);
        $subjct =  explode( "\n" , $pregSubjct );
        foreach($subjct as $sub)
            if( strlen($sub) != 0 ) if($sub != "\r" ) $offerSend['subject_lines'][] = $sub ;

        // $countries  = 'US Only';
        $countries = $offer->countries ;
        $offerSend['countries'] = $this->filterCountres( $countries );
        // if(strtolower($offer->countries) == "all traffic accepted" )  $offerSend['countries'] = "all";
        // else  $offerSend['countries'] = strtoupper($offer->countries);

        // $offerSend['countries'] = str_replace("/",",", $offerSend['countries'] );

        return  $this->apiResponse($offerSend);
    }


    public function update($id,Request $request){

        if(isset($request->hawk_id))
            $hawkId = $request->hawk_id;
        else
            $hawkId = null;


            if(empty(Offer::find($id)))
                return $this->apiResponse(null,"this offer is not found",404);

            $offer = Offer::find($id);
            $offer->status   = 1 ;
            $offer->hawk_id  = $hawkId ;
            $offer->is_added = 1 ;
            $offer->save();

            $creatives = $offer->creatives()->where([ ["is_deleted" , 0] , ["id_sended" , 0] ])->get();

            foreach ( $creatives as $key => $creative) {
                $creatieObject = Creative::find($creative->id);
                $creatieObject->id_sended = 1;
                $creatieObject->save();
            }

            if($offer)
                return $this->apiResponse("succes",null,201);
            else
                return $this->apiResponse("fail",'same thig passed wrong',520);

    }

    private function filterCountres( $countries ){
        $varCountries = ( strtolower( $countries ) == "all traffic accepted" ) ? "all" : strtoupper( $countries );
        $varCountries = preg_replace('/(\/|&)/',",", $varCountries );
        $varCountries = preg_replace( '/(\[|\]|ONLY)/' , "" , $varCountries );
        $varCountries = trim( $varCountries );
        return $varCountries;
    }
}
