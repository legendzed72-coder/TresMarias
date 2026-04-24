<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">Monthly Revenue Report - {{ $monthName }} {{ $year }}</h2>
            <a href="{{ route('admin.revenue.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Month/Year Picker -->
        <div class="mb-8">
            <form method="GET" class="flex gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Month</label>
                    <select name="month" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected($m == $month)>
                                {{ \Carbon\Carbon::createFromDate($year, $m, 1)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Year</label>
                    <select name="year" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                        @for($y = 2024; $y <= now()->year; $y++)
                            <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </form>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">₱{{ number_format($overview['total_revenue'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-medium">Total Profit</p>
                <p class="text-2xl font-bold text-green-600 mt-2">₱{{ number_format($overview['total_profit'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-medium">Items Sold</p>
                <p class="text-2xl font-bold text-orange-600 mt-2">{{ $overview['total_items_sold'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-500 text-sm font-medium">Orders</p>
                <p class="text-2xl font-bold text-purple-600 mt-2">{{ $overview['total_orders'] }}</p>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600 border-b">
                <h2 class="text-lg font-bold text-white">Products Sold This Month</h2>
            </div>
            
            @if($products->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Product</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Avg Price</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Revenue</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 uppercase">Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($product->image_url)
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-gray-200 mr-3 flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-400"></i>
                                                </div>
                                            @endif
                                            <span class="font-medium text-gray-900">{{ $product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">{{ $product->quantity_sold }}</td>
                                    <td class="px-6 py-4 text-right">₱{{ number_format($product->avg_price, 2) }}</td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900">₱{{ number_format($product->revenue, 2) }}</td>
                                    <td class="px-6 py-4 text-right text-green-600 font-bold">₱{{ number_format($product->profit, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-4 block"></i>
                    <p>No sales this month</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
