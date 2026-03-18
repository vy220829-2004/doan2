<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderByRaw("CASE WHEN slug = 'thuc-pham-khac' THEN 1 ELSE 0 END")
            ->orderBy('name')
            ->get();

        return view('clients.admin.layouts.pages.home', compact('categories'));
    }
}
