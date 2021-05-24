<?php

use Illuminate\Database\Seeder;
use App\Advertiser;

class AdvertisersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $adv = new Advertiser;
        $adv->name       =  "idrive";
        $adv->adv_id     =  1;
        $adv->alias      =  "account 1";
        $adv->api_key    =  "682db938af9869ffa8c0098ecdedb0022e7238b1f63536a14207d80bbc03850f";
        $adv->url        =  "http://api.rembrow.com/pubapi.php";
        $adv->status     =  "0";
        $adv->api_type   =  "hitpath";
        $adv->url_to_add =  "{affiliate_id}/{source}/{aff_sub}";
        $adv->entity_id  =  1;
        $adv->save();

		
	$adv = new Advertiser;
        $adv->name       =  "madrivo";
        $adv->adv_id     =  2;
        $adv->alias      =  "account 1";
        $adv->api_key    =  "e74ed50ada7d93ff5192139189bdae537a3991e50e08a5cf82f338c67f9fcb58";
        $adv->url        =  "http://api.midenity.com/pubapi.php";
        $adv->status     =  "0";
        $adv->api_type   =  "hitpath";
        $adv->url_to_add =  "{affiliate_id}/{source}/{aff_sub}";
        $adv->entity_id  =  1;
        $adv->save();
		
		
	$adv = new Advertiser;
        $adv->name       =  "spheredigital";
        $adv->adv_id     =  3;
        $adv->alias      =  "account 1";
        $adv->api_key    =  "2d052b6fcb079f0dade5217d1a21b117948f9bf167964df518572fdb2fa22f93";
        $adv->url        =  "http://api.spheredigitalnetworks.com/pubapi.php";
        $adv->status     =  "0";
        $adv->api_type   =  "hitpath";
        $adv->url_to_add =  "{affiliate_id}/{source}/{aff_sub}";
        $adv->entity_id  =  1;
        $adv->save();
		
	$adv = new Advertiser;
        $adv->name       =  "Adgenics";
        $adv->adv_id     =  1;
        $adv->alias      =  "account 1";
        $adv->api_key    =  "a87fffdde867ec94d1f0919368340f3d64d549880b5f37c372186346d6d52766";
        $adv->url        =  "http://api.loadsmooth.com/pubapi.php";
        $adv->status     =  "0";
        $adv->api_type   =  "hitpath";
        $adv->url_to_add =  "{affiliate_id}/{source}/{aff_sub}";
        $adv->entity_id  =  1;
        $adv->save();

    }
}

