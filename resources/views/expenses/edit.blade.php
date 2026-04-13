<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Edit Expense</h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto">

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Amount -->
            <div class="mb-4">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" 
                       value="{{ $expense->amount }}"
                       class="w-full border rounded p-2">
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label>Category</label>
                <select name="category_id" class="w-full border rounded p-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $expense->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date -->
            <div class="mb-4">
                <label>Date</label>
                <input type="date" name="date"
                       value="{{ $expense->date }}"
                       class="w-full border rounded p-2">
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label>Description</label>
                <textarea name="description" class="w-full border rounded p-2">
                    {{ $expense->description }}
                </textarea>
            </div>

            <!-- Button -->
            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Update Expense
            </button>

        </form>
    </div>
</x-app-layout>