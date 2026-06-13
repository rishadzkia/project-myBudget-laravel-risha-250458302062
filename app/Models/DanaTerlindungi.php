<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanaTerlindungi extends Model
{
protected $table = 'dana_terlindungi';
protected $fillable = [
    'user_id',
    'account_id',
    'nominal',
];

public function account()
{
    return $this->belongsTo(Account::class);
}
}
