<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class Admin2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name' => 'Admin2',
            'email' => 'admin2@example.com',
            'password' => bcrypt('admin2'), // 適切なパスワードを設定
        ]);
    }
}
