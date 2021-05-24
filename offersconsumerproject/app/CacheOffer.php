<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CacheOffer extends Model
{
    //
    public function advertiser()
    {
        return $this->belongsTo('App\Advertiser');
    }
}
