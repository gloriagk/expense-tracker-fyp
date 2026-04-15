<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Success message -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Expense table card -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Category</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Description</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-900 font-medium whitespace-nowrap">
                                    RM {{ number_format($expense->amount, 2) }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-block bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full">
                                        {{ $expense->category->category_name }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4 text-gray-700">
                                    {{ $expense->description ?: '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2 whitespace-nowrap">
                                        <a href="{{ route('expenses.edit', $expense->id) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
                                            Edit
                                        </a>

                                        <form id="delete-form-{{ $expense->id }}"
                                            action="{{ route('expenses.destroy', $expense->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                    onclick="confirmDelete({{ $expense->id }})"
                                                    class="bg-red-600 text-white px-4 py-2 rounded-lg">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    No expenses recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This expense will be deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>