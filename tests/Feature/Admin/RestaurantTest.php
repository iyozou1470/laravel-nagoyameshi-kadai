<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Admin;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\RegularHoliday;
use Illuminate\Support\Facades\Hash;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;


    // 未ログインのユーザーは管理者側の店舗一覧ページにアクセスできない
    public function test_unauthenticated_user_cannot_access_admin_restaurant_list_page(): void 
    {
        $response = $this->get(route('admin.restaurants.index'));

        $response->assertRedirect(route('admin.login'));
    }
    
    //未認証ユーザーがアクセスできない
    public function test_unauthenticated_user_cannot_access_member_store_page(): void
{
    $response = $this->get(route('restaurants.index'));

    $response->assertStatus(302); // 302 リダイレクトを確認
    $response->assertRedirect(route('login')); // ログインページへのリダイレクトを確認
}


    // ログイン済みの一般ユーザーは管理者側の店舗一覧ページにアクセスできない
    public function test_authenticated_regular_user_cannot_access_admin_restaurant_list_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.index'));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者は管理者側の店舗一覧ページにアクセスできる
    public function test_authenticated_admin_can_access_admin_restaurant_list_page(): void
    {
        $admin = Admin::factory()->create();

        $restaurant = Restaurant::factory()->create();
    
        // 管理者としてログイン
        $this->actingAs($admin, 'admin');
    
        // 店舗の一覧ページにアクセス
        $response = $this->get(route('admin.restaurants.index'));
    
        // 正常にアクセスできることを確認
        $response->assertStatus(200);
    }

    // 未ログインのユーザーは管理者側の店舗詳細ページにアクセスできない
    public function test_unauthenticated_user_cannot_access_admin_restaurant_detail_page(): void
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.show', ['restaurant' => $restaurant]));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは管理者側の店舗詳細ページにアクセスできない
    public function test_authenticated_regular_user_cannot_access_admin_restaurant_detail_page(): void
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.show', ['restaurant' => $restaurant]));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者は管理者側の店舗詳細ページにアクセスできる
    public function test_authenticated_admin_can_access_admin_restaurant_detail_page(): void
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();
    
        // 管理者としてログイン
        $this->actingAs($admin, 'admin');
    
        // 店舗の詳細ページにアクセス
        $response = $this->get(route('admin.restaurants.show', ['restaurant' => $restaurant]));
    
        // 正常にアクセスできることを確認
        $response->assertStatus(200);
    }

    // 未ログインのユーザーは管理者側の店舗登録ページにアクセスできない
    public function test_unauthenticated_user_cannot_access_admin_restaurant_register_page(): void 
    {
        $response = $this->get(route('admin.restaurants.create'));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは管理者側の店舗登録ページにアクセスできない
    public function test_authenticated_regular_user_cannot_access_admin_restaurant_register_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.create'));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者は管理者側の店舗登録ページにアクセスできる
    public function test_authenticated_admin_can_access_admin_restaurant_register_page(): void
    {
        $admin = Admin::factory()->create();

        // 管理者としてログイン
        $this->actingAs($admin, 'admin');
    
        // 店舗登録ページにアクセス
        $response = $this->get(route('admin.restaurants.create'));
    
        // 正常にアクセスできることを確認
        $response->assertStatus(200);
    }

    // 未ログインのユーザーは店舗を登録できない
    public function test_unauthenticated_user_cannot_register_restaurant(): void
    {
        $dayOff = RegularHoliday::factory()->create();

        $categoryIds = [];
        for ($i = 1; $i <= 3; $i++) {
            $category = Category::create([
                'name' => 'カテゴリ' . $i
            ]);
            array_push($categoryIds, $category->id);    
        }

        $restaurant = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'regular_holiday_ids' => [$dayOff->id],
            'seating_capacity' => 50,
            'category_ids' => $categoryIds,
        ];

        $response = $this->post(route('admin.restaurants.store'), $restaurant);

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは店舗を登録できない
    public function test_authenticated_regular_user_cannot_register_restaurant(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $dayOff = RegularHoliday::factory()->create();

        $categoryIds = [];
        for ($i = 1; $i <= 3; $i++) {
            $category = Category::create([
                'name' => 'カテゴリ' . $i
            ]);
            array_push($categoryIds, $category->id);    
        }

        $restaurant = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'regular_holiday_ids' => [$dayOff->id],
            'seating_capacity' => 50,
            'category_ids' => $categoryIds,
        ];

        $response = $this->post(route('admin.restaurants.store'), $restaurant);

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者は店舗を登録できる
    public function test_authenticated_admin_can_register_restaurant(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');

        $categoryIds = [];
        for ($i = 1; $i <= 3; $i++) {
            $category = Category::create([
                'name' => 'カテゴリ' . $i
            ]);
            array_push($categoryIds, $category->id);    
        }

        $dayOff = RegularHoliday::factory()->create();

        $restaurant = [
            'name' => 'テスト',
            'description' => 'テスト',
            'lowest_price' => 1000,
            'highest_price' => 5000,
            'postal_code' => '0000000',
            'address' => 'テスト',
            'opening_time' => '10:00:00',
            'closing_time' => '20:00:00',
            'regular_holiday_ids' => [$dayOff->id],
            'seating_capacity' => 50,
            'category_ids' => $categoryIds,
        ];

        $response = $this->post(route('admin.restaurants.store'), $restaurant);

        unset($restaurant['regular_holiday_ids'], $restaurant['category_ids']);
        $this->assertDatabaseHas('restaurants', $restaurant);
        
        $response->assertRedirect(route('admin.restaurants.index'));
    }

    // 未ログインのユーザーは管理者側の店舗編集ページにアクセスできない
    public function test_unauthenticated_user_cannot_access_admin_restaurant_edit_page(): void 
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->get(route('admin.restaurants.edit', ['restaurant' => $restaurant]));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは管理者側の店舗編集ページにアクセスできない
    public function test_authenticated_regular_user_cannot_access_admin_restaurant_edit_page(): void
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.restaurants.edit', ['restaurant' => $restaurant]));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者は管理者側の店舗編集ページにアクセスできる
    public function test_authenticated_admin_can_access_admin_restaurant_edit_page(): void
    {
        $admin = Admin::factory()->create();
        $restaurant = Restaurant::factory()->create();
    
        // 管理者としてログイン
        $this->actingAs($admin, 'admin');
    
        // 店舗編集ページにアクセス
        $response = $this->get(route('admin.restaurants.edit', ['restaurant' => $restaurant]));
    
        // 正常にアクセスできることを確認
        $response->assertStatus(200);
    }

    // 未ログインのユーザーは店舗を編集できない
    public function test_unauthenticated_user_cannot_edit_restaurant(): void
    {
        $restaurant = Restaurant::factory()->create();

        $data = [
            'name' => '更新テスト',
            'description' => '更新テスト',
            'lowest_price' => 2000,
            'highest_price' => 6000,
            'postal_code' => '1111111',
            'address' => '更新テスト',
            'opening_time' => '11:00:00',
            'closing_time' => '21:00:00',
            'seating_capacity' => 60,
        ];

        $response = $this->put(route('admin.restaurants.update', ['restaurant' => $restaurant]), $data);

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは店舗を編集できない
    public function test_authenticated_regular_user_cannot_edit_restaurant(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $restaurant = Restaurant::factory()->create();

        $data = [
            'name' => '更新テスト',
            'description' => '更新テスト',
            'lowest_price' => 2000,
            'highest_price' => 6000,
            'postal_code' => '1111111',
            'address' => '更新テスト',
            'opening_time' => '11:00:00',
            'closing_time' => '21:00:00',
            'seating_capacity' => 60,
        ];

        $response = $this->put(route('admin.restaurants.update', ['restaurant' => $restaurant]), $data);

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者は店舗を編集できる
    public function test_authenticated_admin_can_edit_restaurant(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin');
        $restaurant = Restaurant::factory()->create();

        $data = [
            'name' => '更新テスト',
            'description' => '更新テスト',
            'lowest_price' => 2000,
            'highest_price' => 6000,
            'postal_code' => '1111111',
            'address' => '更新テスト',
            'opening_time' => '11:00:00',
            'closing_time' => '21:00:00',
            'seating_capacity' => 60,
        ];

        $response = $this->put(route('admin.restaurants.update', ['restaurant' => $restaurant]), $data);

        unset($data['category_ids']);
        $this->assertDatabaseHas('restaurants', $data);
        
        $response->assertRedirect(route('admin.restaurants.show', ['restaurant' => $restaurant]));
    }

    // 未ログインのユーザーは店舗を削除できない
    public function test_unauthenticated_user_cannot_delete_restaurant(): void
    {
        $restaurant = Restaurant::factory()->create();

        $response = $this->delete(route('admin.restaurants.destroy', ['restaurant' => $restaurant]));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの一般ユーザーは店舗を削除できない
    public function test_authenticated_regular_user_cannot_delete_restaurant(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $restaurant = Restaurant::factory()->create();

        $response = $this->delete(route('admin.restaurants.destroy', ['restaurant' => $restaurant]));

        $response->assertRedirect(route('admin.login'));
    }

    // ログイン済みの管理者は店舗を削除できる
    public function test_authenticated_admin_can_delete_restaurant(): void
{
    $admin = Admin::factory()->create();
    $this->actingAs($admin, 'admin');
    $restaurant = Restaurant::factory()->create();

    $response = $this->delete(route('admin.restaurants.destroy', ['restaurant' => $restaurant]));

    // レストランがソフトデリートされたことを確認
    $this->assertSoftDeleted('restaurants', ['id' => $restaurant->id]);
    
    $response->assertRedirect(route('admin.restaurants.index'));
}
}
