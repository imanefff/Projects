<?php

namespace App\ServerModels;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $connection = 'mysql2';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function server()
    {
        // return $this->hasMany('App\ServerModels\Server');
        return $this->belongsTo('App\ServerModels\Server');
    }

}
