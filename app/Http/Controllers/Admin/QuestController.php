<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
        $categories = Category::query()->orderBy('name')->get();
        return view('admin.quests.create', compact('users', 'categories'));
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
            'tipe' => ['required', 'in:income,expense,both'],
            'tracking' => ['required', 'in:income_total,expense_category_total,expense_total,transaction_count,no_spend_days,login_streak'],
            'target' => ['nullable', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'category' => ['nullable', 'string', 'max:255'],
            'criteria' => ['nullable', 'string'],
        ]);

        $data['criteria'] = $this->buildCriteria($request);

        if ($request->input('tracking') === 'expense_category_total' && $request->input('category_id')) {
            $cat = Category::query()->find($request->input('category_id'));
            if ($cat) {
                $data['category'] = $cat->name;
            }
        }

        unset($data['tipe'], $data['tracking'], $data['target'], $data['category_id']);

        Quest::query()->create($data);
        return redirect()->route('admin.quests.index')->with('success', 'Quest berhasil dibuat.');
    }

    public function edit(Quest $quest)
    {
        $users = User::query()->orderBy('name')->get();
        $categories = Category::query()->orderBy('name')->get();
        return view('admin.quests.edit', compact('quest', 'users', 'categories'));
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
            'tipe' => ['required', 'in:income,expense,both'],
            'tracking' => ['required', 'in:income_total,expense_category_total,expense_total,transaction_count,no_spend_days,login_streak'],
            'target' => ['nullable', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'category' => ['nullable', 'string', 'max:255'],
            'criteria' => ['nullable', 'string'],
        ]);

        $data['criteria'] = $this->buildCriteria($request);

        if ($request->input('tracking') === 'expense_category_total' && $request->input('category_id')) {
            $cat = Category::query()->find($request->input('category_id'));
            if ($cat) {
                $data['category'] = $cat->name;
            }
        }

        unset($data['tipe'], $data['tracking'], $data['target'], $data['category_id']);

        $quest->update($data);
        return redirect()->route('admin.quests.index')->with('success', 'Quest berhasil diperbarui.');
    }

    public function destroy(Quest $quest)
    {
        $quest->delete();
        return redirect()->route('admin.quests.index')->with('success', 'Quest berhasil dihapus.');
    }

    private function buildCriteria(Request $request): array
    {
        $tracking = $request->input('tracking');
        $target = (int) ($request->input('target') ?? 0);

        $unit = in_array($tracking, ['income_total', 'expense_total', 'expense_category_total'])
            ? 'rupiah'
            : 'count';

        return [
            'tracking' => $tracking,
            'target' => $target,
            'unit' => $unit,
        ];
    }
}
