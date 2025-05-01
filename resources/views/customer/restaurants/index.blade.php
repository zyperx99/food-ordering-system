<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">Browse Restaurants</h2>
    </x-slot>

    <div class="py-6 px-6">
        {{-- Filter by category --}}
        <form method="GET" class="mb-6">
            <label for="category" class="mr-2 font-medium">Filter by Category:</label>
            <select name="category" id="category" onchange="this.form.submit()" class="border rounded px-3 py-1">
                <option value="">All</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Restaurant list --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse ($restaurants as $restaurant)
                <div class="bg-white p-4 rounded shadow">
                    <h3 class="text-lg font-semibold">
                        <a href="{{ route('customer.restaurant.show', $restaurant->id) }}" class="text-blue-600 hover:underline">
                            {{ $restaurant->name }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500">{{ $restaurant->category->name }} | 📍 {{ $restaurant->address }}</p>
                </div>
            @empty
                <p>No restaurants found.</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $restaurants->links() }}
        </div>
    </div>
</x-app-layout>
