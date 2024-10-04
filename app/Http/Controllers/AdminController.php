<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class AdminController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function categories()
    {
        $cats = Category::get();

        return view('cats', compact('cats'));
    }

    public function channels()
    {
        return view('channels');
    }
}
