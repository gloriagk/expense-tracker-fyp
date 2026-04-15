<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $budget = Budget::where('user_id', auth()->id())->latest()->first();

        $totalSpent = Expense::where('user_id', auth()->id())->sum('amount');
        $recentExpenses = Expense::with('category')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $budgetAmount = $budget ? $budget->amount : 0;
        $remaining = max($budgetAmount - $totalSpent, 0);
        $usagePercent = $budgetAmount > 0 ? min(($totalSpent / $budgetAmount) * 100, 100) : 0;

        return view('budget.index', compact(
            'budget',
            'totalSpent',
            'budgetAmount',
            'remaining',
            'usagePercent',
            'recentExpenses'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'period' => 'required|string',
        ]);

        Budget::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'amount' => $request->amount,
                'period' => $request->period,
            ]
        );

        return redirect()->route('budget.index')->with('success', 'Budget updated successfully!');
    }
}