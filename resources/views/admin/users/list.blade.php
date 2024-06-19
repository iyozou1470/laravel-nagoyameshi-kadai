<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function userList(Request $request)
    {
    $keyword = $request->input('keyword');

    //データの取得(ページネーション適用済み)
    $usersQuery = User::query();
    if($keyword){
        $usersQuery->where(function ($query) use ($keyword){
            $query->where('name','like',"%$keyword%")
                  ->orWhere('kana','like',"%$keyword%");
        });
    }
    $users = $usersQuery->paginate(10);

    // データの総数
    $total = $users->total();

    return view('admin.user.list',compact('users','keyword','total'));
}