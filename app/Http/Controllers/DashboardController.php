<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Expense;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('category')
            ->where('user_id', auth()->id())
            ->orderBy('date', 'asc')
            ->get();

        $total = $expenses->sum('amount');
        $count = $expenses->count();
        $latest = $expenses->sortByDesc('created_at')->first();

        $recentExpenses = Expense::with('category')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $categoryLabels = [];
        $categoryData = [];

        $categoryGrouped = $expenses->groupBy(function ($expense) {
            return $expense->category ? $expense->category->category_name : 'Unknown';
        });

        foreach ($categoryGrouped as $label => $group) {
            $categoryLabels[] = $label;
            $categoryData[] = $group->sum('amount');
        }

        $monthLabels = [];
        $monthData = [];

        $monthlyGrouped = $expenses->groupBy(function ($expense) {
            return Carbon::parse($expense->date)->format('M');
        });

        foreach ($monthlyGrouped as $label => $group) {
            $monthLabels[] = $label;
            $monthData[] = $group->sum('amount');
        }

        $budget = Budget::where('user_id', auth()->id())->latest()->first();
        $budgetTotal = $budget ? $budget->amount : 0;
        $budgetLeft = max($budgetTotal - $total, 0);
        $budgetUsedPercent = $budgetTotal > 0 ? min(($total / $budgetTotal) * 100, 100) : 0;

        $insights = [
            [
                'title' => 'Great job staying consistent',
                'desc' => 'Your recent spending pattern looks stable.',
                'bg' => 'bg-blue-50',
                'text' => 'text-blue-700',
            ],
            [
                'title' => 'Watch your highest category',
                'desc' => count($categoryLabels) ? 'Your top category is ' . $categoryLabels[array_keys($categoryData, max($categoryData))[0]] . '.' : 'No category data yet.',
                'bg' => 'bg-yellow-50',
                'text' => 'text-yellow-700',
            ],
            [
                'title' => 'Keep tracking regularly',
                'desc' => 'More data will make your analysis page stronger.',
                'bg' => 'bg-green-50',
                'text' => 'text-green-700',
            ],
        ];

        return view('dashboard', compact(
            'total',
            'count',
            'latest',
            'recentExpenses',
            'categoryLabels',
            'categoryData',
            'monthLabels',
            'monthData',
            'budgetTotal',
            'budgetLeft',
            'budgetUsedPercent',
            'insights'
        ));
    }
}