<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <!-- Heading -->
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h2>
                <p class="text-gray-500 mt-1">Here's your spending overview for {{ now()->format('F Y') }}</p>
            </div>

            <!-- Summary cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500 mb-2">Total Expenses</p>
                    <h3 class="text-3xl font-bold text-gray-900">RM{{ number_format($total, 2) }}</h3>
                    <p class="text-sm text-red-500 mt-2">This month</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500 mb-2">Budget Left</p>
                    <h3 class="text-3xl font-bold text-gray-900">RM{{ number_format($budgetLeft, 2) }}</h3>
                    <p class="text-sm text-green-500 mt-2">
                        {{ $budgetTotal > 0 ? number_format(($budgetLeft / $budgetTotal) * 100, 1) : 0 }}% of budget
                    </p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <p class="text-sm text-gray-500 mb-2">Budget Used</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($budgetUsedPercent, 1) }}%</h3>
                    <div class="w-full bg-gray-200 rounded-full h-3 mt-4">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $budgetUsedPercent }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Charts row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Spending Pattern</h3>
                    <div class="h-[260px]">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Expense Categories</h3>
                    <div class="h-[260px]">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Insights + recent -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Financial Insights</h3>

                    <div class="space-y-3">
                        @foreach($insights as $insight)
                            <div class="rounded-xl p-4 {{ $insight['bg'] }}">
                                <p class="font-semibold {{ $insight['text'] }}">{{ $insight['title'] }}</p>
                                <p class="text-sm mt-1 {{ $insight['text'] }}">{{ $insight['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Expenses</h3>
                        <a href="{{ route('expenses.index') }}" class="text-blue-600 text-sm font-medium">View All</a>
                    </div>

                    <div class="space-y-4">
                        @forelse($recentExpenses as $expense)
                            <div class="flex items-center justify-between border-b pb-3">
                                <div>
                                    <p class="font-medium text-gray-800">
                                        {{ $expense->description ?: ($expense->category->category_name ?? 'Expense') }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}
                                    </p>
                                </div>
                                <p class="font-semibold text-gray-900">RM{{ number_format($expense->amount, 2) }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">No recent expenses yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const monthlyCanvas = document.getElementById('monthlyChart');
            const categoryCanvas = document.getElementById('categoryChart');

            if (monthlyCanvas) {
                new Chart(monthlyCanvas, {
                    type: 'line',
                    data: {
                        labels: @json($monthLabels),
                        datasets: [{
                            label: 'Monthly Spending',
                            data: @json($monthData),
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.10)',
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

            if (categoryCanvas) {
                new Chart(categoryCanvas, {
                    type: 'pie',
                    data: {
                        labels: @json($categoryLabels),
                        datasets: [{
                            data: @json($categoryData),
                            backgroundColor: [
                                '#f59e0b',
                                '#10b981',
                                '#8b5cf6',
                                '#3b82f6',
                                '#ef4444',
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