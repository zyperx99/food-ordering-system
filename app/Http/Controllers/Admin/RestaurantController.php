<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    //
    public function index()
    {
        $restaurants = Restaurant::with('category', 'user')->latest()->get();
        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function approve(Restaurant $restaurant)
    {
        $restaurant->update(['status' => 'approved']);
        return back()->with('success', 'Restaurant approved.');
    }

    public function disable(Restaurant $restaurant)
    {
        $restaurant->update(['status' => 'disabled']);
        return back()->with('success', 'Restaurant disabled.');
    }
}
