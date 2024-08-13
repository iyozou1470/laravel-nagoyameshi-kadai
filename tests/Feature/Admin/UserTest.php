<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     */
    public function test_未ログインユーザー→管理者会員一覧NG(): void
    {
        $response = $this->get('admin/index');

        $response->assertStatus(404);
    }


    public function test_ログイン済みユーザー→管理者会員一覧NG(): void
    {
        $user = new User();
        $user->name = "testsan";
        $user->kana = "テストサン";
        $user->email = "user@example.com";
        $user->password = Hash::make('nagoyameshi');
        $user->postal_code = "123-4567";
        $user->address = "aaa";
        $user->phone_number = "090-1111-1111";
        $user->birthday = "1990-01-01";
        $user->occupation = "無職";
        $user->save();
     
        $response = $this->post('/admin/login', [
            'email' => $user->email,
            'password' => 'nagoyameshi',
        ]);

        $response->assertRedirect('/');
    }


    public function test_管理者ユーザー→管理者会員一覧OK(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();
     
        $response = $this->post('admin/login', [
            'email' => $admin->email,
            'password' => 'nagoyameshi',
        ]);
        $response->assertRedirect(route('admin.home'));
    }
}
