<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;

class ReportController extends Controller
{
    public function index()
    {
        $summary = [
            'users' => User::query()->count(),
            'transactions' => Transaction::query()->count(),
            'income' => (float) Transaction::query()->where('type', 'income')->sum('amount'),
            'expense' => (float) Transaction::query()->where('type', 'expense')->sum('amount'),
        ];

        return view('admin.reports.index', compact('summary'));
    }
}
