<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{

    public function advertiser()
    {
        return $this->belongsTo('App\Advertiser');
    }

    public function creatives()
    {
        return $this->hasMany('App\Creative');
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    protected $fillable = ['sid','name','offer_url','preview_url','landing_page_url','unsubscribe_url',
    'categories','description','date','type','has_supp','countries','suppression_link'];

    //public $primaryKey =
}
