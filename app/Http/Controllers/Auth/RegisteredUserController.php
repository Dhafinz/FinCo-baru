<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:'.User::class.',username'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

        $displayName = Str::of($request->full_name)->trim()->explode(' ')->first();

        $user = DB::transaction(function () use ($request, $displayName) {
            $createdUser = User::create([
                'name' => $displayName ?: $request->full_name,
                'username' => Str::lower($request->username),
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'role' => 'user',
                'password' => Hash::make($request->password),
            ]);

            Wallet::query()->firstOrCreate([
                'user_id' => $createdUser->id,
            ], [
                'balance' => 0,
                'currency' => 'IDR',
                'is_active' => true,
            ]);

            DB::table('gamification_profiles')->updateOrInsert(
                ['user_id' => $createdUser->id],
                [
                    'current_level' => 1,
                    'total_xp' => 0,
                    'current_streak' => 0,
                    'last_login_date' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return $createdUser;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
