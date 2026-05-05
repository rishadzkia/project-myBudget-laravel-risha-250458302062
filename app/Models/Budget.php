<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'monthly_setting_id',
        'category_id',
        'budget_amount'
    ];

    protected $casts = [
        'budget_amount' => 'decimal:2',
    ];

    public function monthlySetting()
    {
        return $this->belongsTo(MonthlySetting::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}