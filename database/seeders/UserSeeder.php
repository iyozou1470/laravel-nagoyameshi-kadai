<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('users')->insert([
            //'name' =>'田中',
            //'kana' =>'タナカ',
            //'email' =>'otaka346+100@gmail.com',
            //'password' =>'aaaaaaaa',
            //'postal_code' =>'8889999',
            //'address' =>'福岡県遠賀郡',
            //'phone_number' =>'09099999999',
            //'birthday' =>'1999.09.09',
            //'occupation' =>'会社員',
        //]);
        // UserFactoryクラスで定義した内容にもとづいてダミーデータを5つ生成し、usersテーブルに追加する
        User::factory()->count(100)->create();
    }
}
