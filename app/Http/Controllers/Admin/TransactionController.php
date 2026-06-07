<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Category;
use App\Models\Budget;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::query()->latest()->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $users = User::query()->orderBy('name')->get();
        $categories = Category::query()->orderBy('name')->get();
        $budgets = Budget::query()->orderBy('id')->get();
        return view('admin.transactions.create', compact('users', 'categories', 'budgets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'budget_id' => ['nullable', 'exists:budgets,id'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'xp_earned' => ['nullable', 'integer', 'min:0'],
        ]);

        Transaction::query()->create($data);
        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dibuat.');
    }

    public function edit(Transaction $transaction)
    {
        $users = User::query()->orderBy('name')->get();
        $categories = Category::query()->orderBy('name')->get();
        $budgets = Budget::query()->orderBy('id')->get();
        return view('admin.transactions.edit', compact('transaction', 'users', 'categories', 'budgets'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'budget_id' => ['nullable', 'exists:budgets,id'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'xp_earned' => ['nullable', 'integer', 'min:0'],
        ]);

        $transaction->update($data);
        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
