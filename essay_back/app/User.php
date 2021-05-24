<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;



class User extends \TCG\Voyager\Models\User implements MustVerifyEmail
{
    use Notifiable;
    use Authenticatable ;
    
    use Notifiable;
    use SoftDeletes;




    protected $dates = ['deleted_at'];

        protected $fillable = [
     
              'name', 'email', 'password', 'provider', 'provider_id','last_name','adresse','city','country','zip','gender','about','date_birth','education','marital_status',
        ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'provider_name', 'provider_id', 'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     public function isAdmin()
    {
    //this looks for an admin column in users table
       return $this->admin;
     }

     public function creatives()
    {
         return $this->hasMany('App\Creative','creative_users');
     }

     public function favorite_creatives()
     {
         return $this->belongsToMany('App\Creative','creative_users');
     }

     public function CreativeUser()
     {
         return $this->belongsToMany('App\Creative','creative_users');
     }

}
