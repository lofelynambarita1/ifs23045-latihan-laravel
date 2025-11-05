<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Income Categories
            ['name' => 'Gaji', 'type' => 'income', 'icon' => '💰'],
            ['name' => 'Bonus', 'type' => 'income', 'icon' => '🎁'],
            ['name' => 'Investasi', 'type' => 'income', 'icon' => '📈'],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => '💼'],
            ['name' => 'Lainnya', 'type' => 'income', 'icon' => '💵'],
            
            // Expense Categories
            ['name' => 'Makanan', 'type' => 'expense', 'icon' => '🍔'],
            ['name' => 'Transport', 'type' => 'expense', 'icon' => '🚗'],
            ['name' => 'Belanja', 'type' => 'expense', 'icon' => '🛒'],
            ['name' => 'Tagihan', 'type' => 'expense', 'icon' => '📱'],
            ['name' => 'Hiburan', 'type' => 'expense', 'icon' => '🎮'],
            ['name' => 'Kesehatan', 'type' => 'expense', 'icon' => '⚕️'],
            ['name' => 'Pendidikan', 'type' => 'expense', 'icon' => '📚'],
            ['name' => 'Lainnya', 'type' => 'expense', 'icon' => '💸'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}