<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

public function userDetails(User $user)
{
    return view('admin.user.detail',compact('user'));
}
}