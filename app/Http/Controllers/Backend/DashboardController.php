<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $blog = Blog::count();
        return view('backend.pages.dashboard.index', compact('blog'));
    }
}
