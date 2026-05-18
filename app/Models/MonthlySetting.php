<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'total_income',
        'total_saving',
        'daily_budget_limit'
    ];

    protected $casts = [
        'total_income' => 'decimal:2',
        'total_saving' => 'decimal:2',
        'daily_budget_limit' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}