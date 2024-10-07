<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;
use App\Models\Option;

class AdminController extends Controller
{
    public function index()
    {
        return view('index');
    }
}
