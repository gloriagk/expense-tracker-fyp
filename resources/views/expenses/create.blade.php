<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Expense
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="amount" class="block font-medium text-sm text-gray-700">Amount</label>
                        <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}"
                               class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="category_id" class="block font-medium text-sm text-gray-700">Category</label>
                        <select name="category_id" id="category_id"
                                class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="date" class="block font-medium text-sm text-gray-700">Date</label>
                        <input type="date" name="date" id="date" value="{{ old('date') }}"
                               class="mt-1 block w-full rounded border-gray-300 shadow-sm" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="mt-1 block w-full rounded border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Save Expense
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>