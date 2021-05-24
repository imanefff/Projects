<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

use App\ServerModels\Server;
use App\ServerModels\History;
use App\ServerModels\Isp;

class ServerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index(){
        // $servers = Server::all();
        $servers = Auth::user()->servers;
        // return    $servers;
        return view( 'servers.commend' , compact('servers') );
    }


    public function timeline( ){
        // $servers = Auth::user()->entity->servers()->has('histories', '>', 0)->paginate(15);
        $servers = Auth::user()->servers()->paginate(15);
        return view( 'servers.timeline' , compact('servers') );
    }

    public function history( Request $request ){
        // Server::find(6)->histories()->latest()->get()
        // foreach ( Server::find( $request->id )->histories()->latest()->get()  as  $history ) {
        foreach ( Server::find( $request->id )->histories()->where( 'user_id' , Auth::user()->id )->latest()->get()  as  $history ) {
        // foreach ( Server::find($request->id)->histories  as  $history ) {
            $histories[] = [ "hestory" =>  $history , "user" =>  $history->user ];
        }

        return $histories;
        return Server::find($request->id)->histories;
    }
    public function getHestory(Request $request){

         return [ "hestory" => History::find( $request->id) , "user" => History::find( $request->id)->user ];

    }

    public function serverAction( Request $request ){


        $server = $request->name;
        $action = $request->action;

        // return $request ;
        $data = array('access_token' => 'KDSLZOLD54POKDREO5d565KDKS' , 'action' => $action);
        // http://nwd009.pimhost.com/pmc_pi.php
        $url = "http://$server.pimhost.com/pmc_pi.php";

        $options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
        )
        );

        $context  = stream_context_create($options);
        try{
            $result = file_get_contents($url, false, $context);
        }catch(\Exception $e){
            $result = "ErrServer";
        }


        $hest = new History;
        $hest->action     = $request->action;
        $hest->commend    = $request->action;
        $hest->resultat   = $result;
        $hest->reason     = $request->reason;
        $hest->server_id  = Server::where('name' , $request->name )->first()->id;
        $hest->user_id    = Auth::user()->id;
        $hest->save();

        return ["serverName" => $request->name , "CommendResult" => $result ];

    }

    public function serverExecute( Request $request ){
        // return $request;

        $server = $request->name;
        $commend = $request->cmdline;

        $url = "http://$server.pimhost.com/pmc_pi.php";

        $data = array( 'access_token' => 'KDSLZOLD54POKDREO5d565KDKS' , 'action' => 'command' , 'command' => $commend );
        $options = array(
                'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                )
        );

        $context  = stream_context_create($options);
        try{
            $result = file_get_contents($url, false, $context);
        }catch(\Exception $e){
            $result = "ErrServer";
        }

        $hest = new History;
        $hest->action     = "Commend";
        $hest->commend    = $request->cmdline;
        $hest->resultat   = $result;
        $hest->reason     = $request->reason;
        // $hest->server_id  = Server::where('name' , $request->name )->first()->id;
        $hest->server_id  = $request->id ;
        $hest->user_id    = Auth::user()->id;
        $hest->save();

        return ["serverName" => $request->name , "CommendResult" => $result ];

    }

    public function updateListServer(Request $req){

        $public_key = 'dhb_to_hawk';
        $private_key = 'ms0104@DHB!#2014AzErTy';
        $server = 'apicalls.pimhost.com:33080';

        $data = base64_encode(json_encode(array(
                        'key' => $public_key
                )));

        $signature = base64_encode(sha1($public_key.$data.$private_key));

        $login_url = "$server/servers.php?data=$data&signature=$signature";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $login_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        $data = json_decode(base64_decode($result), true);

        foreach($data as $dat)
        {
            switch($dat["entity_id"]){
                case 1 :
                    $entity = 1;
                    break;
                case 10 :
                    $entity = 2;
                    break;
                case 11 :
                    $entity = 3;
                    break;
                case 12:
                    $entity = 4;
                    break;
            }
            if( $dat["is_active"] == 1 )
                $servers [] =  [ "name" => $dat["name"] , "entity_id" => $entity , "count" => Server::where( [ ['name', $dat["name"] ] , ['entity_id' , $entity] ] )->count() ];

        }

        Server::whereRaw('1 = 1')->update(['update_cas' => 0]);

        foreach( $servers as $server ) {

            if( $server["count"] == 0)
            {
                $serve             = new Server;
                $serve->name       = $server['name'];
                $serve->entity_id  = $server['entity_id'];
                $serve->update_cas = 1;
                $serve->status     = 1;
                $serve->save();
            }
            else if( $server["count"] > 0)
            {
                $serve               = Server::where( [ ['name', $server["name"] ] , ['entity_id' , $server["entity_id"] ] ] )->first();
                $serve->update_cas   = 1 ;
                $serve->status       = 1 ;
                $serve->save();
            }

        }

        Server::where( 'update_cas' , '0' )->update(['status' => 0]);

        return $servers;
    }


    public function serverAdd( Request $req ){

        $serverName        = trim($req->serverName);
        $numberUser        = Auth::user()->servers()->where('name' , $serverName)->count();
        $nemberInServers   = Auth::user()->entity->servers()->where('name' , $serverName)->count();
        $nemberInServersUp = Auth::user()->entity->servers()->where([ [ 'name' , $serverName ] , [ 'status' , 1 ] ])->count();

        if (  $numberUser == 1 && $nemberInServers == 1 && $nemberInServersUp == 1 )
            return [ "msg" => " This server may be already in existe !!" , "erreur" => true , "data" => null ];
        else
        if( $numberUser > 0) {

            $server =  Auth::user()->servers()->where('name' , $serverName)->first();
            $server->status = 1;
            $server->save();
            $user    =  Auth::user();
            $user->servers()->attach(  $server->id );

        }else{

            if( $nemberInServers > 0 )
            {
                $nemberInServers  = Auth::user()->entity->servers()->where('name' , $serverName)->first();
                $nemberInServers->status = 1;
                $nemberInServers->save();

                $user = Auth::user();
                $user->servers()->attach(  $nemberInServers->id );
            }
            else
            {
                $server = new Server ;
                $server->name      = $serverName ;
                $server->entity_id = Auth::user()->entity->id ;
                $server->status    = 1 ;
                $server->save();

                $user = Auth::user();
                $user->servers()->attach( $server->id );

            }

        }

        return [ "msg" => " Added " , "erreur" => false , "data" => null ];

    }

    public function serverUserAll(Request $req ) {
            // return $servers = Auth::user()->servers;
            return Auth::user()->servers;
    }

    public function serverUserDesasosiete(Request $req){

        $user = Auth::user();
        return $user->servers()->detach($req->id);
    }

    public function addIsp(Request $req){

        $isp = new Isp;
        $isp->name    = rtrim ( $req->name , "/*" )."/*";
        $isp->user_id = Auth::user()->id;
        $p = $isp->save();
        if( !$p )
            return [ "msg" => 'failed Adding !!' , "erreur" => true , "data" => "" ];

        return [ "msg" => 'success Adding !!' , "erreur" => false , "data" => $isp ];
    }

    public function ispList(){
        // return Isp::all();
        return Auth::user()->isps;
    }

}
