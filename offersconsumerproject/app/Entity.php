<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{

    protected $connection = 'mysql';

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function advertisers()
    {
        return $this->hasMany('App\Advertiser');
    }

    public function offers()
    {
        return $this->hasMany('App\Offer', 'entity_id');
    }

    public function servers()
    {
        return $this->hasMany('App\ServerModels\Server');
    }
}
