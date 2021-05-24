<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertiser extends Model
{
    //

    public function entity()
    {
        return $this->belongsTo('App\Entity');
    }


    public function offers()
    {
        return $this->hasMany('App\Offer');
    }

    public function cacheoffres()
    {
        return $this->hasMany('App\CacheOffer');
    }
}
