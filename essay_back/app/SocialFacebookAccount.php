<?php

// SocialFacebookAccount.php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialFacebookAccount extends Model
{
  protected $table = 'users';
  protected $fillable = ['id', 'provider_id', 'provider'];

  public function user()
  {
      return $this->belongsTo(User::class);
  }



}
