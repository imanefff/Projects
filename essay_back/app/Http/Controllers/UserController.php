<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
     public function show(Request $request, $id)
    {
        $value = $request->session()->get('key', function () {

         return 'default';
         });
    }
}
