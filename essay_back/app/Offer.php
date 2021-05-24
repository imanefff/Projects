<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;


class Offer extends Model

{

     use SoftDeletes;
     use SearchableTrait;
    

     protected $dates = ['deleted_at'];
    

    public function creatives()
    {
        return $this->hasMany('App\Creative');
    }

      public function category()
    {
        return $this->belongsTo('App\Category');
    }



 }

  
