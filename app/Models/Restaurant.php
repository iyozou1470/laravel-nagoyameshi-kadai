<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\RegularHoliday;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Review;
//use App\Models\Reservation;
use App\Models\User;

class Restaurant extends Model
{
    use HasFactory, Sortable;

    // ここにfillableプロパティを追加
    protected $fillable = [
        'name', 'description', 'lowest_price', 'highest_price',
        'postal_code', 'address', 'opening_time', 'closing_time',
        'seating_capacity', 'image'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function regular_holidays()
    {
        return $this->belongsToMany(RegularHoliday::class)->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function ratingSortable($query, $direction) {
        return $query->withAvg('reviews', 'score')->orderBy('reviews_avg_score', $direction);
    }

    //public function reservations()
    //{
        //return $this->hasMany(Reservation::class);
    //}

    //public function popularSortable($query, $direction) {
    //    return $query->withCount('reservations')->orderBy('reservations_count', $direction);
    //}

    public function favorited_users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

}
