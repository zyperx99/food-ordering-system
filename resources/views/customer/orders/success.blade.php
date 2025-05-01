<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Order Successful</h2>
    </x-slot>

    <div class="py-10 max-w-2xl mx-auto px-6">
        <div class="bg-white rounded shadow p-6 text-center">
            <h3 class="text-lg font-bold text-green-600 mb-4">Thank you for your order!</h3>
            <p class="text-gray-700 mb-2">Your order has been placed successfully.</p>
            <p class="text-sm text-gray-500">You’ve earned <strong>{{ session('points') }}</strong> loyalty point(s).</p>
            <br>

            <a href="{{ route('customer.restaurants') }}" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Back to Restaurants
            </a>
        </div>
    </div>
</x-app-layout>
