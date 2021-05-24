<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Creative;
use App\Category;
use App\Offer;
use App\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Session;



class CreativeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
                return $_SERVER['REMOTE_ADDR'] ;
            }
        }
        $ip =get_ip();

      // Use an API to get visitor Location
        $query=@unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
        if($query && $query['status'] == 'success') {
            $country=$query['country'];
        }
        $listCreative = Creative::where([ ['is_active', 1],['Countries' , 'like' ,  '%'.$country.'%' ] ])->paginate(8);
        return view('shop', ['creatives' => $listCreative]);
    }

    public function shopSingle(request $request)
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
                return $_SERVER['REMOTE_ADDR'];
            }
        }
        $ip =get_ip();
        // Use an API to get visitor Location
        $query=@unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
        if($query && $query['status'] == 'success') {
            $country=$query['country'];
        }

        $creative = Creative::findorfail( $request->id);
        $listCreative = Creative::where([ ['is_active', 1], ['is_top_offer', 1],['Countries','like','%'.$country.'%' ] ])->get();
        return view('shopSingle')->with('creative', $creative)->with('creatives', $listCreative);
    }

    public function categorySingle( $id , request $request)
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
                return $_SERVER['REMOTE_ADDR'];
            }
        }
        $ip =get_ip();

      // Use an API to get visitor Location

        $query=@unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
        if( $query && $query['status'] == 'success') {
            $country=$query['country'];
        }
        $category = Category::findorfail( $request->id);
        $categories = Category::where('is_active', 1)->get();
        $offers = Offer::where([['Category' , $category->name],['Countries' , 'like' ,  '%'.$country.'%' ]])->get();
        return view('categorySingle' , compact('category' , 'categories' , 'offers') );
    }

    public function clickCreative(request $request )
    {
        $creative = Creative::findorfail( $request->id); // Find our creative by ID.
        $creative->increment('clicks'); // Increment the value in the clicks column.
        $creative->update(); // Save our updated creative.
     }   
 public function search(Request $request)
    {
        $query = $request->input('query');
        $action = new Action();
        $action->session_id=session()->getId();
        $action->subject="User have searched : ".$query;
         if($user = Auth::user())
         {
           $action->user_id=Auth::user()->id;
         }
        $action->save();
      // $creatives =Creative::search('mkf')->where('is_active', 1)->paginate(12);
        $creatives = Creative::search($query)->where('is_active', 1)->paginate(12);

     
      //  return $creatives;    
         return view('search-results')->with('creatives', $creatives);
    }

    public function UserActionClick(request $request )
    {
        $creative = Creative::findorfail( $request->id);
        $action = new Action();
        $action->session_id=session()->getId();
        $action->subject="User have clicked this Creative id : ".$creative->id;
        if($user = Auth::user())
        {
            $action->user_id=Auth::user()->id;
        }
        $action->save();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
