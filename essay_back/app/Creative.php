<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;

class Creative extends Model
{
       

    use SoftDeletes;
    use SearchableTrait;

    protected $dates = ['deleted_at'];
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'offers.name' => 10,
            'offers.description' => 5,

        ],
        'joins' => [
            'offers' => ['offers.id','creatives.offer_id'],
        ],
    ];

    public function favorite_to_users(){
        return $this->belongsToMany('App\User','creative_users');
    }

    public function offer()
    {
        return $this->belongsTo('App\Offer');
    }


}
