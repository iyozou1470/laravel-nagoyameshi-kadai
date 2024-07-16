<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Restaurant extends Model
{
    use HasFactory;

    // テーブル名を指定
    protected $table = 'restaurants';

    // フィルラブルな属性を指定
    protected $fillable = [
        'name', 'image', 'description', 'lowest_price', 
        'highest_price', 'postal_code', 'address', 
        'opening_time', 'closing_time', 'seating_capacity'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
    
    // リレーションの定義
    public function favorited_users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    // スコープを定義
    //public function scopeRatingSortable($query, $direction)
    //{
    //    return $query->withAvg('reviews', 'score')->orderBy('reviews_avg_score', $direction);
    //}

    //public function ratingSortable($query, $direction) {
    //    return $query->withAvg('reviews', 'score')->orderBy('reviews_avg_score', $direction);
    //}
}
