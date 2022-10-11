<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Registry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $id = Auth()->user()->id;

        $registries = Registry::where('user_id','=', $id);
        return view('dashboard/dashboard', compact('registries'));
    }

    public function categories()
    {
        $categories = Category::all();

        return view('dashboard/dashboard-categories', compact('categories'));
    }

    public function products()
    {
        $products = Product::all();

        return view('dashboard/dashboard-products', compact('products'));
    }
}
