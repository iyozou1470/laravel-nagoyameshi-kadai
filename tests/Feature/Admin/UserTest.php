<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    //未ログインのユーザーは管理者側の会員一覧ページにアクセスできない
    public function test_unauthenticated_user_cannnot_access_admin_list_page(): void 
    {
        $response = $this->get('admin.users.index');

        $response->assertStatus(404);
    }

    //ログイン済みの一般ユーザーは管理者側の会員一覧ページにアクセスできない
    public function test_authenticated_regular_user_cannot_access_admin_member_list_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('admin.users.index');

        $response->assertStatus(404);
    }

    //ログイン済みの管理者は管理者側の会員一覧ページにアクセスできる
    public function test_authenticated_admin_can_access_admin_member_list_page(): void
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
    //未ログインのユーザーは管理者側の会員詳細ページにアクセスできない
    public function test_unauthenticated_user_cannnot_access_admin_detail_page(): void
    {
        $response = $this->get('admin.users.show');

        $response->assertStatus(404);
    }

    //ログイン済みの一般ユーザーは管理者側の会員詳細ページにアクセスできない
    public function test_authenticated_regular_user_cannot_access_admin_member_detail_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('admin.users.show');

        $response->assertStatus(404);
    }

    //ログイン済みの管理者は管理者側の会員詳細ページにアクセスできる
    public function test_authenticated_admin_can_access_admin_member_detail_page(): void
    {
        $admin = new Admin();
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('nagoyameshi');
        $admin->save();

        $user = User::factory()->create();
    
        // 管理者としてログイン
        $this->actingAs($admin);
    
        // 会員の詳細ページにアクセス
        $response = $this->get(route('admin.users.show', ['user' => $user->id]));
    
        // リダイレクトされることを確認
        $response->assertStatus(200);
    }
}