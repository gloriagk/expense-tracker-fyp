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

        $alertMessage = null;
        $alertType = null;

        if ($budgetTotal > 0) {
            if ($budgetUsedPercent >= 100) {
                $alertType = 'danger';
                $alertMessage = 'You have exceeded your budget limit.';
            } elseif ($budgetUsedPercent >= 80) {
                $alertType = 'warning';
                $alertMessage = 'Warning: You have used more than 80% of your budget.';
            } else {
                $alertType = 'safe';
                $alertMessage = 'You are still within your budget.';
            }
        }

        $insights = [];

        // Highest spending category
        $topCategory = count($categoryLabels)
            ? $categoryLabels[array_keys($categoryData, max($categoryData))[0]]
            : 'Unknown';

        // Budget insight
        if ($budgetUsedPercent >= 100) {

            $insights[] = [
                'title' => 'Budget Exceeded',
                'desc' => 'You have exceeded your monthly budget. Consider reducing spending in ' . $topCategory . '.',
                'bg' => 'bg-red-50',
                'text' => 'text-red-700',
            ];

        } elseif ($budgetUsedPercent >= 80) {

            $insights[] = [
                'title' => 'Budget Warning',
                'desc' => 'You have used more than 80% of your budget. Monitor your spending carefully.',
                'bg' => 'bg-yellow-50',
                'text' => 'text-yellow-700',
            ];

        } else {

            $insights[] = [
                'title' => 'Budget On Track',
                'desc' => 'Your spending is currently within budget.',
                'bg' => 'bg-green-50',
                'text' => 'text-green-700',
            ];
        }

        // Top category insight
        $insights[] = [
            'title' => 'Highest Spending Category',
            'desc' => 'Most of your expenses are in ' . $topCategory . '.',
            'bg' => 'bg-blue-50',
            'text' => 'text-blue-700',
        ];

        // Data quality insight
        if ($count < 10) {

            $insights[] = [
                'title' => 'More Data Needed',
                'desc' => 'Add more expenses to improve analysis accuracy and K-Means clustering results.',
                'bg' => 'bg-purple-50',
                'text' => 'text-purple-700',
            ];

        } else {

            $insights[] = [
                'title' => 'Good Data Coverage',
                'desc' => 'You have sufficient transaction data for spending analysis.',
                'bg' => 'bg-green-50',
                'text' => 'text-green-700',
            ];
        }

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
            'insights',
            'alertMessage',
            'alertType'
        ));
    }
}