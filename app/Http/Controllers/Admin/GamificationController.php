<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GamificationProfile;

class GamificationController extends Controller
{
    public function index()
    {
        $profiles = GamificationProfile::query()->latest()->paginate(20);
        return view('admin.gamification.index', compact('profiles'));
    }
}
