<?php

namespace App\Http\Controllers;

// 検証中
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;

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
        //$highly_rated_restaurants = Restaurant::withAvg('reviews', 'score')
            //->orderBy('reviews_avg_score', 'desc')
            //->take(6)
            //->get();

        // カテゴリと新しいレストランを取得
        $categories = Category::all();
        $new_restaurants = Restaurant::orderBy("created_at", "desc")
            ->take(6)
            ->get();

        return view('home', compact('highly_rated_restaurants', 'categories', 'new_restaurants'));
    }
}
