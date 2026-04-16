<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;

class AnalysisController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('category')
            ->where('user_id', auth()->id())
            ->orderBy('date', 'asc')
            ->get();

        $totalSpent = $expenses->sum('amount');
        $transactionCount = $expenses->count();
        $averageSpent = $transactionCount > 0 ? $totalSpent / $transactionCount : 0;

        $categoryGrouped = $expenses->groupBy(function ($expense) {
            return $expense->category ? $expense->category->category_name : 'Unknown';
        });

        $categoryLabels = [];
        $categoryData = [];

        foreach ($categoryGrouped as $label => $group) {
            $categoryLabels[] = $label;
            $categoryData[] = $group->sum('amount');
        }

        $topCategory = count($categoryData) > 0
            ? $categoryLabels[array_keys($categoryData, max($categoryData))[0]]
            : 'No data';

        $monthlyGrouped = $expenses->groupBy(function ($expense) {
            return Carbon::parse($expense->date)->format('M');
        });

        $monthLabels = [];
        $monthData = [];

        foreach ($monthlyGrouped as $label => $group) {
            $monthLabels[] = $label;
            $monthData[] = $group->sum('amount');
        }

        $highSpendingCategories = $categoryGrouped->map(function ($group, $label) use ($totalSpent) {
            $sum = $group->sum('amount');
            $count = $group->count();
            $percentage = $totalSpent > 0 ? ($sum / $totalSpent) * 100 : 0;

            return [
                'name' => $label,
                'amount' => $sum,
                'count' => $count,
                'percentage' => $percentage,
            ];
        })->sortByDesc('amount')->values();

        $insightText = count($categoryLabels) > 0
            ? "You are spending most on {$topCategory}. Review this category to manage your expenses better."
            : "No enough expense data yet. Add more expenses to generate spending insights.";

        return view('analysis.index', compact(
            'totalSpent',
            'transactionCount',
            'averageSpent',
            'topCategory',
            'categoryLabels',
            'categoryData',
            'monthLabels',
            'monthData',
            'highSpendingCategories',
            'insightText'
        ));
    }
}