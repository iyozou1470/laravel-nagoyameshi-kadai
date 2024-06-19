<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // UserFactoryクラスで定義した内容にもとづいてダミーデータを100件生成し、usersテーブルに追加する
        User::factory()->count(100)->create();
    }
}
