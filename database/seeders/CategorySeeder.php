<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // user_id = 1 (pastikan user ini ada)
        Category::create([
            'user_id' => 1,
            'category' => 'Makanan',
            'icon' => 'food'
        ]);

        Category::create([
            'user_id' => 2,
            'category' => 'Transportasi',
            'icon' => 'car'
        ]);

        Category::create([
            'user_id' => 3,
            'category' => 'Belanja',
            'icon' => 'shopping'
        ]);

        Category::create([
            'user_id' => 6,
            'category' => 'Gaji',
            'icon' => 'salary'
        ]);

        Category::create([
            'user_id' => 1,
            'category' => 'Hiburan',
            'icon' => null // karena nullable
        ]);
    }
}