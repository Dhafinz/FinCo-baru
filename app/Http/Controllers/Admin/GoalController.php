<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialGoal;
use App\Models\User;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index()
    {
        $goals = FinancialGoal::query()->latest()->paginate(20);
        return view('admin.goals.index', compact('goals'));
    }

    public function create()
    {
        $users = User::query()->orderBy('name')->get();
        return view('admin.goals.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_amount' => ['required', 'numeric', 'min:0'],
            'current_amount' => ['nullable', 'numeric', 'min:0'],
            'target_date' => ['required', 'date'],
            'status' => ['required', 'in:active,completed,cancelled'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        FinancialGoal::query()->create($data);
        return redirect()->route('admin.goals.index')->with('success', 'Goal berhasil dibuat.');
    }

    public function edit(FinancialGoal $goal)
    {
        $users = User::query()->orderBy('name')->get();
        return view('admin.goals.edit', compact('goal', 'users'));
    }

    public function update(Request $request, FinancialGoal $goal)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_amount' => ['required', 'numeric', 'min:0'],
            'current_amount' => ['nullable', 'numeric', 'min:0'],
            'target_date' => ['required', 'date'],
            'status' => ['required', 'in:active,completed,cancelled'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        $goal->update($data);
        return redirect()->route('admin.goals.index')->with('success', 'Goal berhasil diperbarui.');
    }

    public function destroy(FinancialGoal $goal)
    {
        $goal->delete();
        return redirect()->route('admin.goals.index')->with('success', 'Goal berhasil dihapus.');
    }
}
