<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-900">Budget Management</h2>
                <p class="text-gray-500 mt-1">Set and monitor your monthly budget to stay on track with your expenses.</p>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Budget Form -->
                <div class="bg-white rounded-2xl shadow-sm border p-6">
                    <h3 class="text-xl font-semibold mb-4">Monthly Budget</h3>

                    <form action="{{ route('budget.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Set Your Budget</label>
                            <input type="number" step="0.01" name="amount"
                                   value="{{ old('amount', $budget ? $budget->amount : '') }}"
                                   placeholder="RM 1200"
                                   class="w-full border rounded-xl px-4 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                            <select name="period" class="w-full border rounded-xl px-4 py-2">
                                <option value="monthly" {{ $budget && $budget->period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="weekly" {{ $budget && $budget->period == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            </select>
                        </div>

                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl">
                            Update Budget
                        </button>
                    </form>

                    <div class="mt-6 border-t pt-4">
                        <p class="text-sm text-gray-500">Current Month</p>
                        <p class="text-lg font-semibold">{{ now()->format('F Y') }}</p>
                    </div>
                </div>

                <!-- Budget Overview -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
                    <h3 class="text-xl font-semibold mb-6">Budget Overview</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Total Budget</p>
                            <p class="text-3xl font-bold text-gray-900">RM{{ number_format($budgetAmount, 2) }}</p>
                        </div>

                        <div class="text-center">
                            <p class="text-sm text-gray-500">Spent</p>
                            <p class="text-3xl font-bold text-red-500">RM{{ number_format($totalSpent, 2) }}</p>
                        </div>

                        <div class="text-center">
                            <p class="text-sm text-gray-500">Remaining</p>
                            <p class="text-3xl font-bold text-green-500">RM{{ number_format($remaining, 2) }}</p>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm text-gray-500 mb-2">
                            <span>Budget Usage</span>
                            <span>{{ number_format($usagePercent, 1) }}%</span>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 h-3 rounded-full" style="width: {{ $usagePercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="mt-6 bg-white rounded-2xl shadow-sm border p-6">
                <h3 class="text-xl font-semibold mb-4">Recent Activity</h3>

                <div class="space-y-4">
                    @forelse($recentExpenses as $expense)
                        <div class="flex items-center justify-between border-b pb-3">
                            <div>
                                <p class="font-medium text-gray-800">
                                    {{ $expense->category->category_name ?? 'Expense' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}
                                </p>
                            </div>

                            <p class="font-semibold text-red-500">
                                -RM{{ number_format($expense->amount, 2) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500">No recent expenses yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>