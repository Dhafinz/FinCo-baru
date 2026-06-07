<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quest;
use App\Models\User;
use Illuminate\Http\Request;

class QuestController extends Controller
{
    public function index()
    {
        $quests = Quest::query()->latest()->paginate(20);
        return view('admin.quests.index', compact('quests'));
    }

    public function create()
    {
        $users = User::query()->orderBy('name')->get();
        return view('admin.quests.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'reward_xp' => ['required', 'integer', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'status' => ['required', 'in:active,completed,failed'],
            'category' => ['nullable', 'string', 'max:255'],
            'criteria' => ['nullable', 'string'],
        ]);

        Quest::query()->create($data);
        return redirect()->route('admin.quests.index')->with('success', 'Quest berhasil dibuat.');
    }

    public function edit(Quest $quest)
    {
        $users = User::query()->orderBy('name')->get();
        return view('admin.quests.edit', compact('quest', 'users'));
    }

    public function update(Request $request, Quest $quest)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'reward_xp' => ['required', 'integer', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'status' => ['required', 'in:active,completed,failed'],
            'category' => ['nullable', 'string', 'max:255'],
            'criteria' => ['nullable', 'string'],
        ]);

        $quest->update($data);
        return redirect()->route('admin.quests.index')->with('success', 'Quest berhasil diperbarui.');
    }

    public function destroy(Quest $quest)
    {
        $quest->delete();
        return redirect()->route('admin.quests.index')->with('success', 'Quest berhasil dihapus.');
    }
}
