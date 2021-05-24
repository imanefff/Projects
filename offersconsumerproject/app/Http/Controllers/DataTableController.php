<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Offer;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DataTableController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view("offers.datatable");
    }

    public function getDataTable(Request $request){

        // return Auth::user();

        if(Auth::user()->permission == "admin"){
            $offers = Offer::all();
        }else{
            $offers = Auth::user()->entity->offers;
        }
        // return $offers ;
        $up = datatables()->of($offers)
            ->editColumn('created_at', function ($offers) {
                return $offers->created_at ? with(new Carbon($offers->created_at))->format('d/m/Y') : '';})
            ->addColumn('advertiser', function ($offers) {
                return $offers->advertiser->name ;})
            ->addColumn('affiliate', function ($offers) {
                return $offers->advertiser->affiliate_id;})
            ->addColumn('user', function ($offers) {
                return $offers->user->name ;})
            ->addColumn('entity', function ($offers) {
                return $offers->user->entity->name ;});


        if( Auth::user()->permission == "admin" || Auth::user()->permission == "manager" || Auth::user()->permission == "agent_offer"){

            $up = $up->addColumn('status', function ($offers) {
                if($offers->status == 0) return '<button type="button"  data-id='.$offers->id.' class="btn btn-secondary" onclick="func('.$offers->id.',this)"> &nbsp; Send &nbsp;</button>';
                else  return "Sent" ;})
            ->addColumn('edit', function ($offers) {
                if($offers->status == 0 && $offers->is_added == 0)  return '<a class="btn btn-warning" href="/offer/edit/'.$offers->id.'">edit</a>';
                else  return "----" ; })
            ->addColumn('update', function ($offers) {
                    return '<a class="btn btn-warning" href="/offer/update/'.$offers->id.'">Update</a>'; })
            ->addColumn('show', function ($offers) {
                return '<a class="btn btn-link" href="/offer/'.$offers->id.'">Show</a>';})
            ->addColumn('delete', function ($offers) {
                if(Auth::user()->permission == "admin" && Auth::user()->permission == "manager" || $offers->is_added == 0 )
                    return '<a class="btn btn-danger" href="/delete/offer/creatives/'.$offers->id.'" onclick="return confirm(\'Are you sure?\')">Delete</a>';
                else  return "---";
                })
            ->rawColumns(['user','status','edit', 'update','show','delete']);
            $up = $up->editColumn('name', '{!! str_limit($name, 40) !!}')->make();

        }else{

            $up = $up->addColumn('status', function ($offers) {
                if($offers->user->id == Auth::user()->id) {
                    if($offers->status == 0) return '<button type="button"  data-id='.$offers->id.' class="btn btn-secondary" onclick="func('.$offers->id.',this)"> &nbsp; Send &nbsp;</button>';
                    else  return "Sent" ;
                }else{
                    if($offers->status == 0) return 'not sent';
                    else  return "Sent" ;
                }
            })

            ->addColumn('edit', function ($offers) {
                if($offers->user->id == Auth::user()->id) {
                    if($offers->status == 0) return '<a class="btn btn-warning" href="/offer/edit/'.$offers->id.'">Edit</a>';
                    else  return "-----" ;
                }else{
                    return "-----" ;
                }
                ;})
            ->addColumn('update', function ($offers) {
                if($offers->user->id == Auth::user()->id) {
                    if($offers->status == 0) return '<a class="btn btn-warning" href="/offer/update/'.$offers->id.'">Update</a>';
                    else  return "-----" ;
                }else{
                    return "-----" ;
                }
                ;})
            ->addColumn('show', function ($offers) {
                return '<a class="btn btn-link" href="/offer/'.$offers->id.'">Show</a>';})
            ->addColumn('delete', function ($offers) {
                return '----';})
            ->rawColumns(['user','status', 'edit' , 'update','show','delete'])
            ->editColumn('name', '{!! str_limit($name, 40) !!}')->make();
        }

            return $up;
    }

    public function usersListes(){
        return view("offers.users");
    }

    public function getUsersDataTable(Request $request){
        if( Auth::user()->permission == 'manager' )
            $users = Auth::user()->entity->users()->with("entity");
        else if ( Auth::user()->permission == 'admin' )
            $users = User::with('entity')->select('users.*');

        return datatables()->of($users)
        ->addColumn('delete', function ($users) {
            return  '------' ;})
        ->addColumn('edit', function ($users) {
            return  '------';})
        ->rawColumns(['edit','delete'])
        ->make();
    }



}


// href="/offer/update/'.$users->id.'"
// onclick="deleteOffer(this)"
