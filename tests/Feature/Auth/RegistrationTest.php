<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'kana' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'postal_code' => '1000001',
            'address' => '福岡県1-1',
            'phone_number' => '09010011001',
        ]);

        $this->assertAuthenticated();
        // メール認証が必要な場合のリダイレクト先を確認
        $response->assertRedirect('/verify-email');
    }
}
