<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Term;

class TermController extends Controller
{
    // -----
    // index アクション
    // ------
    public function index() {
        $term = Term::first();
        return view('terms.index', compact('term'));
}
}