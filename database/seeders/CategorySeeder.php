<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['和食', '洋食', '中華', 'イタリアン', 'フレンチ'];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}