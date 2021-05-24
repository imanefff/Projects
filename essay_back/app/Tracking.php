<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tracking extends Model
{
     use SoftDeletes;

     protected $dates = ['deleted_at'];

     public function actions()
     {
         return $this->hasMany('App\Action');
     }
 }
