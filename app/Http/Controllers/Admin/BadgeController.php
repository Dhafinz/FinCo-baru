<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::query()->latest()->paginate(20);
        return view('admin.badges.index', compact('badges'));
    }

    public function create()
    {
        return view('admin.badges.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'required_level' => ['required', 'integer', 'min:1'],
            'required_xp' => ['nullable', 'integer', 'min:0'],
        ]);

        Badge::query()->create($data);
        return redirect()->route('admin.badges.index')->with('success', 'Badge berhasil dibuat.');
    }

    public function edit(Badge $badge)
    {
        return view('admin.badges.edit', compact('badge'));
    }

    public function update(Request $request, Badge $badge)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'required_level' => ['required', 'integer', 'min:1'],
            'required_xp' => ['nullable', 'integer', 'min:0'],
        ]);

        $badge->update($data);
        return redirect()->route('admin.badges.index')->with('success', 'Badge berhasil diperbarui.');
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();
        return redirect()->route('admin.badges.index')->with('success', 'Badge berhasil dihapus.');
    }
}
