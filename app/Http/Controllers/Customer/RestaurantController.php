<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Category;

class RestaurantController extends Controller
{
    //
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Restaurant::where('status', 'approved');

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        $restaurants = $query->with('category')->paginate(10);

        return view('customer.restaurants.index', compact('restaurants', 'categories'));
    }

    public function show($id)
    {
        $restaurant = Restaurant::with(['menus', 'category'])->findOrFail($id);
        return view('customer.restaurants.show', compact('restaurant'));
    }
    
}

