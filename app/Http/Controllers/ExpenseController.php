<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    public function index()
    {
        $expenses = \App\Models\Expense::with('category')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Expense::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->route('expenses.create')->with('success', 'Expense added successfully.');
    }

    public function edit($id)
    {
        $expense = \App\Models\Expense::where('user_id', auth()->id())->findOrFail($id);
        $categories = \App\Models\Category::all();

        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $expense = \App\Models\Expense::where('user_id', auth()->id())->findOrFail($id);

        $expense->update([
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        $expense = \App\Models\Expense::where('user_id', auth()->id())->findOrFail($id);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}