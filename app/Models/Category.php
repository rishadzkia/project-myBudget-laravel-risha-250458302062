<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'icon'
    ];

    // relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}