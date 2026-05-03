<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascot extends Model
{
  protected $fillable = [
    'user_id',
    'level',
    'xp'
];
}
