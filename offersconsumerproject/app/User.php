<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','entity_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function entity()
    {
        return $this->belongsTo('App\Entity');
    }

    public function offers()
    {
        return $this->hasMany('App\Offer','user_id');
    }


    public function histories()
    {
        return $this->hasMany('App\ServerModels\History');
    }

    public function servers()
    {
        // return $this->hasMany('App\ServerModels\History');*
        return $this->belongsToMany('App\ServerModels\Server' , 'server_user' , 'user_id' , 'server_id');
    }

    public function isps()
    {
        // return $this->hasMany('App\ServerModels\History');*
        return $this->hasMany('App\ServerModels\Isp');
    }


}
