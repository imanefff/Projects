<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Creative extends Model
{
    //
    public function offer()
    {
        return $this->belongsTo('App\Offer');
    }


    public function creativeimages()
    {
        return $this->hasMany('App\CreativeImage');
    }
}
