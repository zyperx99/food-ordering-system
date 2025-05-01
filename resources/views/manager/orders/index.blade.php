<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Incoming Orders</h2>
    </x-slot>

    <div class="py-8 max-w-6xl mx-auto space-y-6 px-4">
        {{-- Sales Summary --}}
        <div class="bg-white p-4 rounded shadow">
            <p class="text-gray-700">Today’s Sales: <strong>RM{{ number_format($todaySales, 2) }}</strong></p>
            <p class="text-gray-700">Total Sales: <strong>RM{{ number_format($totalSales, 2) }}</strong></p>
        </div>
        @if (session('success'))
            <div class="mb-4 p-4 rounded bg-green-100 text-green-800 border border-green-300">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 rounded bg-red-100 text-red-800 border border-red-300">
                {{ session('error') }}
            </div>
        @endif

        {{-- Order Cards --}}
        @forelse ($orders as $order)
            <div class="bg-white rounded shadow p-6 flex flex-col md:flex-row justify-between gap-4 items-start md:items-center">
                {{-- LEFT: Order Info --}}
                <div class="w-full md:w-3/4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-1">Order #{{ $order->id }}</h4>
                    <p class="text-sm text-gray-600">Customer: {{ $order->user->name }}</p>
                    <p class="text-sm">Order Type: {{ ucfirst($order->order_type) }}</p>
                    <p class="text-sm">Total: <strong class="text-blue-600">RM{{ number_format($order->total_price, 2) }}</strong></p>
                    <p class="text-sm mb-2">
                        Status:
                        <span class="inline-block px-2 py-1 text-xs font-bold rounded
                            {{ $order->status === 'accepted' ? 'bg-green-100 text-green-700' :
                               ($order->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    {{-- Menu Items --}}
                    <ul class="list-disc ml-5 text-sm text-gray-600">
                        @foreach ($order->items as $item)
                            <li>{{ $item->menu->name }} × {{ $item->quantity }}</li>
                        @endforeach
                    </ul>
                </div>

                {{-- RIGHT: Action Buttons --}}
                @if ($order->status === 'pending')
                    <div class="flex flex-col gap-2 w-full md:w-auto">
                        <form method="POST" action="{{ route('manager.orders.accept', $order) }}">
                            @csrf
                            <button class="w-full md:w-32 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm">
                                Accept
                            </button>
                        </form>
                        <form method="POST" action="{{ route('manager.orders.reject', $order) }}">
                            @csrf
                            <button class="w-full md:w-32 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow text-sm">
                                Reject
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-500">No orders available.</p>
        @endforelse
    </div>
</x-app-layout>