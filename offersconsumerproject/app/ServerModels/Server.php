<?php

namespace App\ServerModels;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $connection = 'mysql2';

    public function entity()
    {
        return $this->belongsTo('App\Entity');
    }

    public function histories()
    {
        return $this->hasMany('App\ServerModels\History');
    }

    public function users(){
        return $this->belongsToMany( 'App\User' , 'server_user' , 'server_id' , 'user_id' );
    }

}
