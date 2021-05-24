<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function tracking()
    {
        return $this->belongsTo('App\Tracking');
    }

}
