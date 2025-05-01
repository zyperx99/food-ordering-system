<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\User;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create categories
        $asian = Category::create(['name' => 'Asian']);
        $western = Category::create(['name' => 'Western']);
        $dessert = Category::create(['name' => 'Dessert']);

        // Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Customer
        User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer User',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ]
        );

        // Create manager user
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Sample Manager',
                'password' => bcrypt('password'),
                'role' => 'manager',
            ]
        );

        // Create restaurant
        $restaurant = Restaurant::create([
            'user_id' => $manager->id,
            'category_id' => $asian->id,
            'name' => 'Nasi Lemak Heaven',
            'description' => 'Traditional Malaysian food',
            'address' => '123 Jalan Makan',
            'phone' => '012-3456789',
            'status' => 'approved',
        ]);

        // Add menu items
        Menu::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Nasi Lemak Ayam Goreng',
            'description' => 'Spicy fried chicken with rice & sambal',
            'price' => 12.90,
            'available_stock' => 100,
        ]);

        Menu::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Teh Tarik',
            'description' => 'Classic Malaysian pulled tea',
            'price' => 2.50,
            'available_stock' => 200,
        ]);
    }
}
