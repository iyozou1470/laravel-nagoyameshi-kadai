<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Category;

class HomeController extends Controller
{
    public function index() {
        // guardを確認
        if (Auth::guard('admin')->check()) {
            Log::info("class: " . get_class($this) . ", guard: admin ");
        } elseif (Auth::guard('web')->check()) {
            Log::info("class: " . get_class($this) . ", guard: web");
        } else {
            Log::info("class: " . get_class($this) . ", guard: それ以外");
        }

        // ユーザーと売上情報を取得
        $total_users = DB::table('users')->count();
        $total_premium_users = DB::table('subscriptions')
            ->where('stripe_status', 'active')
            ->count();
        $total_free_users = $total_users - $total_premium_users;
        $total_restaurants = DB::table('restaurants')->count();
        $total_reservations = DB::table('reservations')->count();
        $sales_for_this_month = 300 * $total_premium_users;

        // 最新の高評価の店舗を取得
        $highly_rated_restaurants = Restaurant::withAvg('reviews', 'score')
            ->orderBy('reviews_avg_score', 'desc')
            ->take(6)
            ->get();

        // カテゴリーを全て取得
        $categories = Category::all();

        // 最新の店舗を取得
        $new_restaurants = Restaurant::orderBy("created_at", "desc")
            ->take(6)
            ->get();

        return view('admin.home', compact(
            'categories',
            'highly_rated_restaurants',
            'new_restaurants',
            'total_users',
            'total_premium_users',
            'total_free_users',
            'total_restaurants',
            'total_reservations',
            'sales_for_this_month'
        ));
    }
}