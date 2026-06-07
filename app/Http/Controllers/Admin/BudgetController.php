<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::query()->latest()->paginate(20);
        return view('admin.budgets.index', compact('budgets'));
    }

    public function create()
    {
        $users = User::query()->orderBy('name')->get();
        $categories = Category::query()->orderBy('name')->get();
        return view('admin.budgets.create', compact('users', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'category' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'limit_amount' => ['required', 'numeric', 'min:0'],
            'spent_amount' => ['nullable', 'numeric', 'min:0'],
            'period' => ['required', 'in:daily,weekly,monthly,yearly'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date'],
            'is_active' => ['required', 'boolean'],
            'status' => ['required', 'in:on_track,warning,exceeded'],
        ]);

        Budget::query()->create($data);
        return redirect()->route('admin.budgets.index')->with('success', 'Budget berhasil dibuat.');
    }

    public function edit(Budget $budget)
    {
        $users = User::query()->orderBy('name')->get();
        $categories = Category::query()->orderBy('name')->get();
        return view('admin.budgets.edit', compact('budget', 'users', 'categories'));
    }

    public function update(Request $request, Budget $budget)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'category' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'limit_amount' => ['required', 'numeric', 'min:0'],
            'spent_amount' => ['nullable', 'numeric', 'min:0'],
            'period' => ['required', 'in:daily,weekly,monthly,yearly'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date'],
            'is_active' => ['required', 'boolean'],
            'status' => ['required', 'in:on_track,warning,exceeded'],
        ]);

        $budget->update($data);
        return redirect()->route('admin.budgets.index')->with('success', 'Budget berhasil diperbarui.');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        return redirect()->route('admin.budgets.index')->with('success', 'Budget berhasil dihapus.');
    }
}
