<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\GamificationProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        if ($user) {
            $profile = GamificationProfile::query()->firstOrCreate(
                ['user_id' => $user->id],
                ['current_level' => 1, 'total_xp' => 0, 'current_streak' => 0, 'last_login_date' => null]
            );

            $today = now()->toDateString();
            $lastLoginDate = $profile->last_login_date ? \Illuminate\Support\Carbon::parse($profile->last_login_date)->toDateString() : null;

            if ($lastLoginDate === $today) {
                $nextStreak = (int) $profile->current_streak;
            } elseif ($lastLoginDate === now()->subDay()->toDateString()) {
                $nextStreak = (int) $profile->current_streak + 1;
            } else {
                $nextStreak = 1;
            }

            DB::table('gamification_profiles')
                ->where('user_id', $user->id)
                ->update([
                    'current_streak' => $nextStreak,
                    'last_login_date' => $today,
                    'updated_at' => now(),
                ]);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
