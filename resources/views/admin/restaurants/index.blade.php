<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Restaurant Management</h2>
    </x-slot>

    <div class="py-8 px-6 space-y-6">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($restaurants as $res)
            <div class="bg-white p-6 rounded shadow flex justify-between items-center">
                <div>
                    <h4 class="text-lg font-semibold text-gray-800">{{ $res->name }}</h4>
                    <p class="text-sm text-gray-600">Category: {{ $res->category->name }}</p>
                    <p class="text-sm text-gray-600">Status: <strong>{{ ucfirst($res->status) }}</strong></p>
                    <p class="text-sm text-gray-500">Owner: {{ $res->user->name ?? 'N/A' }}</p>
                </div>

                <div class="flex gap-2">
                    @if ($res->status === 'pending')
                        <form method="POST" action="{{ route('admin.restaurants.approve', $res) }}">
                            @csrf
                            <button class="bg-green-600 text-white px-4 py-2 rounded shadow">Approve</button>
                        </form>
                    @endif

                    @if ($res->status !== 'disabled')
                        <form method="POST" action="{{ route('admin.restaurants.disable', $res) }}">
                            @csrf
                            <button class="bg-red-600 text-white px-4 py-2 rounded shadow">Disable</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p>No restaurants available.</p>
        @endforelse
    </div>
</x-app-layout>
