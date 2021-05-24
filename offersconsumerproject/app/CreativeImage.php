<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreativeImage extends Model
{
    //
    public function creative()
    {
        return $this->belongsTo('App\Creative');
    }
}
