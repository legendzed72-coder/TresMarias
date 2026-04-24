<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-bark-600 leading-tight">Revenue Reports & Analytics</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">₱{{ number_format($overview['total_revenue'], 2) }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-chart-line text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Profit</p>
                        <p class="text-2xl font-bold text-green-600 mt-2">₱{{ number_format($overview['total_profit'], 2) }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Profit Margin</p>
                        <p class="text-2xl font-bold text-purple-600 mt-2">{{ number_format($overview['profit_margin'], 1) }}%</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <i class="fas fa-percentage text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Orders Placed</p>
                        <p class="text-2xl font-bold text-orange-600 mt-2">{{ $overview['total_orders'] }}</p>
                    </div>
                    <div class="bg-orange-100 rounded-full p-3">
                        <i class="fas fa-receipt text-orange-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Avg Order Value</p>
                        <p class="text-2xl font-bold text-pink-600 mt-2\">₱{{ number_format($overview['avg_order_value'], 2) }}</p>
                    </div>
                    <div class="bg-pink-100 rounded-full p-3">
                        <i class="fas fa-shopping-cart text-pink-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Daily Revenue -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                    <h2 class="text-lg font-bold text-white">Today's Top Products</h2>
                </div>
                <div class="p-6">
                    @if($dailyProducts->count() > 0)
                        <div class="space-y-4">
                            @foreach($dailyProducts as $product)
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div class="flex items-center">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-200 mr-3 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $product->quantity_sold }} sold</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                    <p class="font-bold text-gray-900">₱{{ number_format($product->revenue, 2) }}</p>
                                    <p class="text-sm text-green-600">+₱{{ number_format($product->profit, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('admin.revenue.daily') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-700 text-sm font-medium">View Daily Report →</a>
                    @else
                        <p class="text-gray-500 text-center py-8">No sales today</p>
                    @endif
                </div>
            </div>

            <!-- Weekly Revenue -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-green-600">
                    <h2 class="text-lg font-bold text-white">This Week's Top Products</h2>
                </div>
                <div class="p-6">
                    @if($weeklyProducts->count() > 0)
                        <div class="space-y-4">
                            @foreach($weeklyProducts as $product)
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div class="flex items-center">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-200 mr-3 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $product->quantity_sold }} sold</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">₱{{ number_format($product->revenue, 2) }}</p>
                                        <p class="text-sm text-green-600">+₱{{ number_format($product->profit, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('admin.revenue.weekly') }}" class="mt-4 inline-block text-green-600 hover:text-green-700 text-sm font-medium">View Weekly Report →</a>
                    @else
                        <p class="text-gray-500 text-center py-8">No sales this week</p>
                    @endif
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600">
                    <h2 class="text-lg font-bold text-white">This Month's Top Products</h2>
                </div>
                <div class="p-6">
                    @if($monthlyProducts->count() > 0)
                        <div class="space-y-4">
                            @foreach($monthlyProducts as $product)
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div class="flex items-center">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-200 mr-3 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $product->quantity_sold }} sold</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">₱{{ number_format($product->revenue, 2) }}</p>
                                        <p class="text-sm text-green-600">+₱{{ number_format($product->profit, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('admin.revenue.monthly') }}" class="mt-4 inline-block text-orange-600 hover:text-orange-700 text-sm font-medium">View Monthly Report →</a>
                    @else
                        <p class="text-gray-500 text-center py-8">No sales this month</p>
                    @endif
                </div>
            </div>

            <!-- Yearly Revenue -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600">
                    <h2 class="text-lg font-bold text-white">This Year's Top Products</h2>
                </div>
                <div class="p-6">
                    @if($yearlyProducts->count() > 0)
                        <div class="space-y-4">
                            @foreach($yearlyProducts as $product)
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div class="flex items-center">
                                        @if($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-200 mr-3 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $product->quantity_sold }} sold</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">₱{{ number_format($product->revenue, 2) }}</p>
                                        <p class="text-sm text-green-600">+₱{{ number_format($product->profit, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('admin.revenue.yearly') }}" class="mt-4 inline-block text-purple-600 hover:text-purple-700 text-sm font-medium">View Yearly Report →</a>
                    @else
                        <p class="text-gray-500 text-center py-8">No sales this year</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Report Links -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Detailed Reports</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.revenue.daily') }}" class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                    <div class="text-center">
                        <i class="fas fa-calendar text-2xl text-blue-600 mb-2"></i>
                        <p class="font-medium text-gray-900">Daily Revenue</p>
                    </div>
                </a>
                <a href="{{ route('admin.revenue.weekly') }}" class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition">
                    <div class="text-center">
                        <i class="fas fa-calendar-week text-2xl text-green-600 mb-2"></i>
                        <p class="font-medium text-gray-900">Weekly Revenue</p>
                    </div>
                </a>
                <a href="{{ route('admin.revenue.monthly') }}" class="flex items-center justify-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition">
                    <div class="text-center">
                        <i class="fas fa-calendar-alt text-2xl text-orange-600 mb-2"></i>
                        <p class="font-medium text-gray-900">Monthly Revenue</p>
                    </div>
                </a>
                <a href="{{ route('admin.revenue.yearly') }}" class="flex items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
                    <div class="text-center">
                        <i class="fas fa-chart-bar text-2xl text-purple-600 mb-2"></i>
                        <p class="font-medium text-gray-900">Yearly Revenue</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
