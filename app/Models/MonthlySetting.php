<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'daily_budget',
        'total_income',
        'total_saving'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}