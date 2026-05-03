<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bill_name',
        'amount',
        'due_day',
        'last_paid_year',
        'last_paid_month',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}