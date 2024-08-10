<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // guardを確認
        if (Auth::guard('admin')->check()) {
            Log::info(" class: " . get_class() . ", guard: admin ");
        } elseif (Auth::guard('web')->check()) {
            Log::info(" class: " . get_class() . ", guard: web");
        } else {
            Log::info(" class: " . get_class() . ", guard: それ以外");
        }

        // 最高評価のレストランを取得
        $highly_rated_restaurants = Restaurant::selectRaw('restaurants.*')
        ->groupBy('restaurants.id')
        ->orderBy('id', 'desc')
        ->take(6)
        ->get();


        // reviews削除前
        //$highly_rated_restaurants = Restaurant::with('[reviews]')
        //   ->selectRaw('restaurants.*, AVG(reviews.score) as reviews_avg_score')
        //    ->leftJoin('reviews', 'restaurants.id', '=', 'reviews.restaurant_id')
        //    ->groupBy('restaurants.id')
        //    ->orderBy('reviews_avg_score', 'desc')
        //    ->take(6)
        //    ->get();

        // カテゴリと新しいレストランを取得
        $categories = Category::all();
        $new_restaurants = Restaurant::orderBy("created_at", "desc")
            ->take(6)
            ->get();

        return view('home', compact('highly_rated_restaurants', 'new_restaurants', 'categories'));
    }
}
