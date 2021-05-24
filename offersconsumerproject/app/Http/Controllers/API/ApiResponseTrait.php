<?php


namespace App\Http\Controllers\API;

trait ApiResponseTrait {

    /**
    *[
    *   'data'   => ''
    *   'status' => true , false
    *   'error'  => ''
    *]

    */

    public function apiResponse( $data = null , $error = null, $code = 200){
        $array = [
            'data'   => $data ,
            'status' => $code==200 ? true : false ,
            'error'  => $error

        ];

        return response($array,$code);
    }




}
