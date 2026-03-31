<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::insert([
            ['category_name' => 'Food', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Transport', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Entertainment', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Shopping', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Education', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Bills', 'created_at' => now(), 'updated_at' => now()],
            ['category_name' => 'Other', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
