<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Dashboard</h2>
    </x-slot>

    <div class="p-6 space-y-6">

        <!-- Buttons -->
        <div class="flex gap-4">
            <a href="{{ route('expenses.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded">
                Add Expense
            </a>

            <a href="{{ route('expenses.index') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded">
                View Expenses
            </a>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-3 gap-4">

            <div class="bg-white p-4 shadow rounded">
                <h3 class="text-gray-500">Total Expenses</h3>
                <p class="text-2xl font-bold">RM {{ $total }}</p>
            </div>

            <div class="bg-white p-4 shadow rounded">
                <h3 class="text-gray-500">Transactions</h3>
                <p class="text-2xl font-bold">{{ $count }}</p>
            </div>

            <div class="bg-white p-4 shadow rounded">
                <h3 class="text-gray-500">Latest Expense</h3>
                <p class="text-xl">
                    {{ $latest ? 'RM '.$latest->amount : 'No data' }}
                </p>
            </div>

        </div>

    </div>
</x-app-layout>