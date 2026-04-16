<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Page heading -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Spending Analysis</h1>
                <p class="text-gray-500 mt-1">Understand your spending patterns and make smarter financial decisions.</p>
            </div>

            <!-- Summary cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500">Total Spending</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">RM{{ number_format($totalSpent, 2) }}</h3>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500">Average Spending</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">RM{{ number_format($averageSpent, 2) }}</h3>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500">Top Category</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $topCategory }}</h3>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500">Transactions</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2">{{ $transactionCount }}</h3>
                </div>
            </div>

            <!-- Insight banner -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white 
                        rounded-2xl shadow-lg p-6 mb-6">

                <h3 class="text-lg font-semibold mb-2">
                    💡 Your Spending Insight
                </h3>

                <p class="text-sm leading-relaxed">
                    {{ $insightText }}
                </p>

            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Spending Trend</h3>
                    <div class="h-[280px]">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Category Breakdown</h3>
                    <div class="h-[280px]">
                        <canvas id="categoryBreakdownChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- High-spending categories -->
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">High-Spending Categories</h3>

                <div class="space-y-6">
                    @forelse($highSpendingCategories as $category)
                        <div class="border rounded-xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-semibold text-gray-900">{{ $category['name'] }}</p>
                                <p class="font-semibold text-gray-900">RM{{ number_format($category['amount'], 2) }}</p>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                <div class="bg-orange-500 h-3 rounded-full" style="width: {{ $category['percentage'] }}%"></div>
                            </div>

                            <div class="flex justify-between text-sm text-gray-500">
                                <span>{{ number_format($category['percentage'], 1) }}% of total spending</span>
                                <span>{{ $category['count'] }} transactions</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No category analysis available yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const monthlyTrendCanvas = document.getElementById('monthlyTrendChart');
            const categoryBreakdownCanvas = document.getElementById('categoryBreakdownChart');

            if (monthlyTrendCanvas) {
                new Chart(monthlyTrendCanvas, {
                    type: 'line',
                    data: {
                        labels: @json($monthLabels),
                        datasets: [{
                            label: 'Monthly Spending',
                            data: @json($monthData),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.10)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            if (categoryBreakdownCanvas) {
                new Chart(categoryBreakdownCanvas, {
                    type: 'pie',
                    data: {
                        labels: @json($categoryLabels),
                        datasets: [{
                            data: @json($categoryData),
                            backgroundColor: [
                                '#ef4444',
                                '#f59e0b',
                                '#3b82f6',
                                '#8b5cf6',
                                '#10b981',
                                '#f97316',
                                '#6b7280'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        });
    </script>
</x-app-layout>