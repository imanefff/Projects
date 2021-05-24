<?php

namespace App\ServerModels;

use Illuminate\Database\Eloquent\Model;

class Isp extends Model
{
    //

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
