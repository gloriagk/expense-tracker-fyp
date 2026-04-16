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

        $alertMessage = null;
        $alertType = null;

        if ($budgetAmount > 0) {
            if ($usagePercent >= 100) {
                $alertType = 'danger';
                $alertMessage = 'You have exceeded your budget limit.';
            } elseif ($usagePercent >= 80) {
                $alertType = 'warning';
                $alertMessage = 'Warning: You have used more than 80% of your budget.';
            } else {
                $alertType = 'safe';
                $alertMessage = 'You are still within your budget.';
            }
        }

        return view('budget.index', compact(
            'budget',
            'totalSpent',
            'budgetAmount',
            'remaining',
            'usagePercent',
            'recentExpenses',
            'alertMessage',
            'alertType'
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