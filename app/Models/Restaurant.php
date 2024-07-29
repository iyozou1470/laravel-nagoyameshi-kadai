<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\RegularHoliday;
use App\Models\User;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'lowest_price', 'highest_price', 
        'postal_code', 'address', 'opening_time', 'closing_time', 
        'seating_capacity', 'image','description'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function regular_holidays()
    {
        return $this->belongsToMany(RegularHoliday::class)->withTimestamps();
    }

    public function favorited_users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
