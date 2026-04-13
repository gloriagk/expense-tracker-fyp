<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function index()
    {
        $total = Expense::where('user_id', auth()->id())->sum('amount');
        $count = Expense::where('user_id', auth()->id())->count();
        $latest = Expense::where('user_id', auth()->id())->latest()->first();

        return view('dashboard', compact('total', 'count', 'latest'));
    }
}
