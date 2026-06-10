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

        // K-MEANS CLUSTERING (k = 3)

        $amounts = $expenses->pluck('amount')->toArray();

        $clusters = [
            'Low Spending' => [],
            'Medium Spending' => [],
            'High Spending' => [],
        ];

        $clusterInsight = "Not enough data for clustering.";

        $clusterCentroids = [
            'Low Spending' => 0,
            'Medium Spending' => 0,
            'High Spending' => 0,
        ];

        if (count($amounts) >= 3) {

            // Initial centroids
            $centroids = [
                min($amounts),
                array_sum($amounts) / count($amounts),
                max($amounts),
            ];

            // Run K-Means for 5 iterations
            for ($iteration = 0; $iteration < 5; $iteration++) {

                $tempClusters = [
                    0 => [],
                    1 => [],
                    2 => [],
                ];

                foreach ($amounts as $amount) {

                    $distances = [
                        abs($amount - $centroids[0]),
                        abs($amount - $centroids[1]),
                        abs($amount - $centroids[2]),
                    ];

                    $nearestCluster = array_search(min($distances), $distances);

                    $tempClusters[$nearestCluster][] = $amount;
                }

                foreach ($tempClusters as $index => $cluster) {
                    if (count($cluster) > 0) {
                        $centroids[$index] = array_sum($cluster) / count($cluster);
                    }
                }
            }

            // Sort centroids
            asort($centroids);

            $sortedIndexes = array_keys($centroids);

            $clusters['Low Spending'] = $tempClusters[$sortedIndexes[0]] ?? [];
            $clusters['Medium Spending'] = $tempClusters[$sortedIndexes[1]] ?? [];
            $clusters['High Spending'] = $tempClusters[$sortedIndexes[2]] ?? [];

            // Generate insight
            $largestClusterName = '';
            $largestClusterCount = 0;

            foreach ($clusters as $name => $cluster) {

                if (count($cluster) > $largestClusterCount) {
                    $largestClusterCount = count($cluster);
                    $largestClusterName = $name;
                }
            }

            $clusterInsight =
                "Most of your expenses belong to the {$largestClusterName} cluster. This indicates your dominant spending behaviour.";

                $clusterCentroids = [
                    'Low Spending' => round($centroids[$sortedIndexes[0]], 2),
                    'Medium Spending' => round($centroids[$sortedIndexes[1]], 2),
                    'High Spending' => round($centroids[$sortedIndexes[2]], 2),
                ];
        }

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
            'clusters',
            'clusterInsight',
            'clusterCentroids'
        ));
    }
}