<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Restaurant;
use App\Models\Category;

class HomeController extends Controller
{
    public function index() {
        // guardを確認
        if (Auth::guard('admin')->check()) {
            Log::info(" class: " . get_class() . ", guard: admin ");
        } elseif (Auth::guard('web')->check()) {
            Log::info(" class: " . get_class() . ", guard: web");
        } else {
            Log::info(" class: " . get_class() . ", guard: それ以外");
        }

        //$highly_rated_restaurants = Restaurant::withAvg('reviews', 'score')->orderBy('reviews_avg_score', 'desc')->take(6)->get();
        $categories = Category::all();
        $new_restaurants = Restaurant::orderBy("created_at", "desc")->take(6)->get();

        return view('admin.home', compact('categories'));
    }
}
