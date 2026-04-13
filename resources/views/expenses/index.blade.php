<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Expenses</h2>
    </x-slot>

    <div class="p-6">

        <!-- Add Button -->
        <a href="{{ route('expenses.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded">
            Add Expense
        </a>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mt-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table -->
        <table class="mt-6 w-full border rounded overflow-hidden">
            <thead>
                <tr class="border hover:bg-gray-100">
                    <th class="p-2">Amount</th>
                    <th class="p-2">Category</th>
                    <th class="p-2">Date</th>
                    <th class="p-2">Description</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($expenses as $expense)
                    <tr class="border hover:bg-gray-100">
                        <td class="p-2">RM {{ $expense->amount }}</td>
                        <td class="p-2">{{ $expense->category->category_name }}</td>
                        <td class="p-2">{{ $expense->date }}</td>
                        <td class="p-2">{{ $expense->description }}</td>
                        <td class="p-2">
                            <a href="{{ route('expenses.edit', $expense->id) }}" 
                               class="bg-blue-500 text-white px-2 py-1 rounded">
                                Edit
                            </a>

                            <form id="delete-form-{{ $expense->id }}" 
                                action="{{ route('expenses.destroy', $expense->id) }}" 
                                method="POST" 
                                style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button type="button" onclick="confirmDelete({{ $expense->id }})"
                                    class="bg-red-500 text-white px-2 py-1 rounded ml-2">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-4">
                            No expenses yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This expense will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>