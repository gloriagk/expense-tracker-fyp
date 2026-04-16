<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Page heading -->
            {{-- <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Add Expense</h1>
                <p class="text-gray-500 mt-1">Record a new expense and keep your spending data updated.</p>
            </div> --}}

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl border border-red-200">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-sm border p-8">
                <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">
                            Amount
                        </label>
                        <input type="number" step="0.01" name="amount" id="amount"
                               value="{{ old('amount') }}"
                               placeholder="e.g. 12.50"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-200"
                               required>
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Category
                        </label>
                        <select name="category_id" id="category_id"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date
                        </label>
                        <input type="text" name="date" id="expense_date"
                               value="{{ old('date') }}"
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-200"
                               required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="5"
                                  placeholder="Add a short note about this expense..."
                                  class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring focus:ring-blue-200">{{ old('description') }}</textarea>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('expenses.index') }}"
                           class="px-5 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
                            Cancel
                        </a>

                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition duration-200 ease-in-out transform hover:-translate-y-0.5 hover:shadow-md">
                            Save Expense
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script>
        flatpickr("#expense_date", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d/m/Y",
        });
    </script>
</x-app-layout>