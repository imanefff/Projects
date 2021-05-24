<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;
use App\Creative;
use Illuminate\Support\Facades\Storage;


class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
       public function index()
    {

 // function to get visitor IP adress
      function get_ip(){
          if(isset($_SERVER['HTTP_CLIENT_IP'])) {

              return $_SERVER['HTTP_CLIENT_IP'];
          }
          elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {

              return $_SERVER['HTTP_X_FORWARDED_FOR'];
          }

          else{
              return  $_SERVER['REMOTE_ADDR'];
    
            
          }
           }

      $ip =get_ip();

      $query=@unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
      if($query && $query['status'] == 'success') {
          $country=$query['country'];
           }
       
      if($country!=null){
        $creatives = Creative::where([[ 'is_active', 1],['Countries' , 'like' ,  $country ]] )->paginate(9);
        $listHotOffer = Creative::where([ ['is_active', 1], ['is_hot_offer', 1],['Countries' , 'like' ,  $country ] ])->get();
        $listTopOffer = Creative::where([ ['is_active', 1], ['is_top_offer', 1],['Countries' , 'like' ,  $country] ])->get();

         return view('homeContent' , compact('creatives' , 'listHotOffer' , 'listTopOffer') );

     }else{
       
        $creatives = Creative::where([[ 'is_active', 1]] )->paginate(9);
        $listHotOffer = Creative::where([ ['is_active', 1], ['is_hot_offer', 1] ])->get();
        $listTopOffer = Creative::where([ ['is_active', 1], ['is_top_offer', 1] ])->get();

         return view('homeContent' , compact('creatives' , 'listHotOffer' , 'listTopOffer') );

        
     }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
