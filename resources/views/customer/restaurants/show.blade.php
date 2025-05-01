<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            {{ $restaurant->name }}
        </h2>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded p-6">
            {{-- All form and content inside here --}}    
        {{-- Restaurant Info --}}
        <div class="mb-6 border-b pb-4">
            <p class="text-gray-700">{{ $restaurant->description }}</p>
            <p class="text-sm text-gray-500 mt-1">Category: <strong>{{ $restaurant->category->name }}</strong></p>
            <p class="text-sm text-gray-500">📍 {{ $restaurant->address }} | ☎ {{ $restaurant->phone }}</p>
        </div>

        {{-- Order Form --}}
        <form method="POST" action="{{ route('customer.order.store') }}">
            @csrf
            <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">

            {{-- Menu Items --}}
            <h3 class="text-lg font-bold mb-3">Menu</h3>
            <div class="space-y-4">
                @foreach($restaurant->menus as $menu)
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded border">
                        <div>
                            <h4 class="font-semibold text-gray-800">{{ $menu->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $menu->description }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600 mb-2">RM{{ number_format($menu->price, 2) }}</p>
                            <input
                                type="number"
                                name="items[{{ $menu->id }}]"
                                min="0"
                                max="{{ $menu->available_stock }}"
                                class="w-20 border rounded text-center py-1"
                                placeholder="0"
                            >
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Order Type --}}
            <div class="mt-8">
                <label for="order_type" class="block font-medium mb-1">Order Type</label>
                <select name="order_type" id="order_type" class="border px-3 py-2 rounded w-full max-w-xs">
                    <option value="pickup">Pickup</option>
                    <option value="delivery">Delivery</option>
                </select>
            </div>

            {{-- Submit --}}
            <div class="mt-6 text-right">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                    Place Order
                </button>
            </div>            
        </form>
        </div>
    </div>
</x-app-layout>
