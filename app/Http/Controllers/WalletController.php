<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\FriendBlock;
use App\Models\TopUp;
use App\Models\User;
use App\Models\UserBadge;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $wallet = Wallet::query()->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'balance' => 0,
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        $activities = WalletTransaction::query()
            ->where('user_id', $user->id)
            ->latest()
            ->take(25)
            ->get();

        return view('wallet.index', [
            'user' => $user,
            'wallet' => $wallet,
            'activities' => $activities,
            'title' => 'Wallet',
            'menuActive' => 'wallet',
        ]);
    }

    public function topupForm(Request $request)
    {
        $user = $request->user();
        $wallet = Wallet::query()->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'balance' => 0,
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        return view('wallet.topup', [
            'user' => $user,
            'wallet' => $wallet,
            'presetAmounts' => [50000, 100000, 200000, 500000],
            'receipt' => session('topup_receipt'),
            'title' => 'Top Up Wallet',
            'menuActive' => 'wallet',
        ]);
    }

    public function topup(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_amount' => ['nullable', 'numeric', 'min:50000'],
            'amount' => ['nullable', 'numeric', 'min:50000'],
            'payment_method' => ['required', 'in:bank_transfer,virtual_account,qris'],
        ]);

        $amount = (float) ($validated['amount'] ?? 0);
        if ($amount <= 0) {
            $amount = (float) ($validated['selected_amount'] ?? 0);
        }

        if ($amount < 50000) {
            return back()->withErrors(['amount' => 'Nominal minimal top up adalah Rp 50.000.'])->withInput();
        }

        $user = $request->user();

        $receipt = DB::transaction(function () use ($user, $amount, $validated): array {
            $wallet = Wallet::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (! $wallet) {
                $wallet = Wallet::query()->create([
                    'user_id' => $user->id,
                    'balance' => 0,
                    'currency' => 'IDR',
                    'is_active' => true,
                ]);
            }

            $balanceBefore = (float) $wallet->balance;
            $balanceAfter = $balanceBefore + $amount;
            $topUpCountBefore = (int) TopUp::query()->where('user_id', $user->id)->count();
            $xpEarned = 20 + ($topUpCountBefore === 0 ? 50 : 0);
            $reference = $this->generateReference('TPU');

            TopUp::query()->create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'payment_method' => $validated['payment_method'],
                'status' => 'success',
                'reference_number' => $reference,
                'xp_earned' => $xpEarned,
            ]);

            WalletTransaction::query()->create([
                'user_id' => $user->id,
                'wallet_id' => $wallet->id,
                'type' => 'top_up',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => 'Top up via '.$this->paymentMethodLabel($validated['payment_method']),
                'status' => 'success',
                'reference_number' => $reference,
            ]);

            $wallet->update(['balance' => $balanceAfter]);

            $this->applyXpDelta($user->id, $xpEarned);
            if ($topUpCountBefore === 0) {
                $this->awardBadge($user->id, 'First Top Up');
            }

            $totalTopUps = $topUpCountBefore + 1;
            if ($totalTopUps >= 5) {
                $this->awardBadge($user->id, 'Top Up Master');
            }

            return [
                'amount' => $amount,
                'method' => $this->paymentMethodLabel($validated['payment_method']),
                'before' => $balanceBefore,
                'after' => $balanceAfter,
                'reference' => $reference,
                'xp' => $xpEarned,
            ];
        });

        return redirect()->route('dashboard.wallet.topup.form')
            ->with('success', 'Top up berhasil disimulasikan.')
            ->with('topup_receipt', $receipt);
    }

    public function transferForm(Request $request)
    {
        $user = $request->user();
        $wallet = Wallet::query()->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'balance' => 0,
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        $friendOptions = $this->acceptedFriends($user->id)
            ->map(function (User $friend): array {
                $profile = DB::table('gamification_profiles')->where('user_id', $friend->id)->first();

                return [
                    'id' => $friend->id,
                    'name' => $friend->name,
                    'username' => $friend->username,
                    'level' => (int) ($profile->current_level ?? 1),
                    'xp' => (int) ($profile->total_xp ?? 0),
                ];
            })
            ->values();

        return view('wallet.transfer', [
            'user' => $user,
            'wallet' => $wallet,
            'friends' => $friendOptions,
            'receipt' => session('transfer_receipt'),
            'preselectedFriendId' => (int) $request->query('friend_id', 0),
            'title' => 'Transfer Wallet',
            'menuActive' => 'wallet',
        ]);
    }

    public function withdrawForm(Request $request)
    {
        $user = $request->user();
        $wallet = Wallet::query()->firstOrCreate([
            'user_id' => $user->id,
        ], [
            'balance' => 0,
            'currency' => 'IDR',
            'is_active' => true,
        ]);

        return view('wallet.withdraw', [
            'user' => $user,
            'wallet' => $wallet,
            'receipt' => session('withdraw_receipt'),
            'title' => 'Withdraw Wallet',
            'menuActive' => 'wallet',
        ]);
    }

    public function withdraw(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'],
            'bank_name' => ['required', 'string', 'max:50'],
            'bank_name_custom' => ['nullable', 'string', 'max:50', 'required_if:bank_name,LAINNYA'],
            'account_number' => ['required', 'string', 'max:30'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $amount = (float) $validated['amount'];

        try {
            $receipt = DB::transaction(function () use ($user, $validated, $amount): array {
                $wallet = Wallet::query()->where('user_id', $user->id)->lockForUpdate()->first();

                if (! $wallet) {
                    $wallet = Wallet::query()->create([
                        'user_id' => $user->id,
                        'balance' => 0,
                        'currency' => 'IDR',
                        'is_active' => true,
                    ]);
                }

                $balanceBefore = (float) $wallet->balance;
                if ($balanceBefore < $amount) {
                    throw new \RuntimeException('Saldo tidak mencukupi untuk withdraw.');
                }

                $balanceAfter = $balanceBefore - $amount;
                $reference = $this->generateReference('WDR');

                $resolvedBankName = strtoupper($validated['bank_name']) === 'LAINNYA'
                    ? strtoupper((string) $validated['bank_name_custom'])
                    : strtoupper((string) $validated['bank_name']);

                $description = sprintf(
                    'Withdraw ke %s (%s)%s',
                    $resolvedBankName,
                    $validated['account_number'],
                    ! empty($validated['note']) ? ' - '.$validated['note'] : ''
                );

                WalletTransaction::query()->create([
                    'user_id' => $user->id,
                    'wallet_id' => $wallet->id,
                    'type' => 'payment',
                    'amount' => $amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'description' => $description,
                    'status' => 'success',
                    'reference_number' => $reference,
                ]);

                $wallet->update(['balance' => $balanceAfter]);

                return [
                    'amount' => $amount,
                    'bank_name' => $resolvedBankName,
                    'account_number' => $validated['account_number'],
                    'account_name' => $validated['account_name'] ?? '-',
                    'before' => $balanceBefore,
                    'after' => $balanceAfter,
                    'reference' => $reference,
                ];
            });
        } catch (\RuntimeException $exception) {
            return back()->withErrors(['amount' => $exception->getMessage()])->withInput();
        }

        return redirect()->route('dashboard.wallet.withdraw.form')
            ->with('success', 'Withdraw berhasil diproses.')
            ->with('withdraw_receipt', $receipt);
    }

    public function transfer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'friend_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => ['required', 'numeric', 'min:10000'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $sender = $request->user();
        $receiverId = (int) $validated['friend_id'];
        $amount = (float) $validated['amount'];

        if ($receiverId === $sender->id) {
            return back()->withErrors(['friend_id' => 'Tidak bisa transfer ke akun sendiri.'])->withInput();
        }

        $isFriend = Friendship::query()
            ->where('status', 'accepted')
            ->where(function ($query) use ($sender, $receiverId) {
                $query->where(function ($q) use ($sender, $receiverId) {
                    $q->where('user_id', $sender->id)->where('friend_id', $receiverId);
                })->orWhere(function ($q) use ($sender, $receiverId) {
                    $q->where('user_id', $receiverId)->where('friend_id', $sender->id);
                });
            })
            ->exists();

        if (! $isFriend) {
            return back()->withErrors(['friend_id' => 'Transfer hanya bisa ke teman yang sudah accepted.'])->withInput();
        }

        try {
            $receipt = DB::transaction(function () use ($sender, $receiverId, $amount, $validated): array {
            $senderWallet = Wallet::query()->where('user_id', $sender->id)->lockForUpdate()->first();
            $receiverWallet = Wallet::query()->where('user_id', $receiverId)->lockForUpdate()->first();

            if (! $senderWallet) {
                $senderWallet = Wallet::query()->create([
                    'user_id' => $sender->id,
                    'balance' => 0,
                    'currency' => 'IDR',
                    'is_active' => true,
                ]);
            }

            if (! $receiverWallet) {
                $receiverWallet = Wallet::query()->create([
                    'user_id' => $receiverId,
                    'balance' => 0,
                    'currency' => 'IDR',
                    'is_active' => true,
                ]);
            }

            $senderBefore = (float) $senderWallet->balance;
            if ($senderBefore < $amount) {
                throw new \RuntimeException('Saldo tidak mencukupi untuk transfer ini.');
            }

            $senderAfter = $senderBefore - $amount;
            $receiverBefore = (float) $receiverWallet->balance;
            $receiverAfter = $receiverBefore + $amount;

            $reference = $this->generateReference('TRF');
            $desc = $validated['note'] ?? 'Transfer antar teman FinCo';

            $senderWallet->update(['balance' => $senderAfter]);
            $receiverWallet->update(['balance' => $receiverAfter]);

            WalletTransaction::query()->create([
                'user_id' => $sender->id,
                'wallet_id' => $senderWallet->id,
                'type' => 'transfer_out',
                'amount' => $amount,
                'balance_before' => $senderBefore,
                'balance_after' => $senderAfter,
                'description' => $desc,
                'status' => 'success',
                'reference_number' => $reference,
            ]);

            WalletTransaction::query()->create([
                'user_id' => $receiverId,
                'wallet_id' => $receiverWallet->id,
                'type' => 'transfer_in',
                'amount' => $amount,
                'balance_before' => $receiverBefore,
                'balance_after' => $receiverAfter,
                'description' => 'Transfer masuk dari @'.$sender->username,
                'status' => 'success',
                'reference_number' => $reference,
            ]);

            $this->applyXpDelta($sender->id, 15);

            $transferCount = (int) WalletTransaction::query()
                ->where('user_id', $sender->id)
                ->where('type', 'transfer_out')
                ->where('status', 'success')
                ->count();

            if ($transferCount >= 3) {
                $this->awardBadge($sender->id, 'Generous');
            }

                return [
                    'amount' => $amount,
                    'before' => $senderBefore,
                    'after' => $senderAfter,
                    'reference' => $reference,
                    'xp' => 15,
                ];
            });
        } catch (\RuntimeException $exception) {
            return back()->withErrors(['amount' => $exception->getMessage()])->withInput();
        }

        return redirect()->route('dashboard.wallet.transfer.form')
            ->with('success', 'Transfer berhasil.')
            ->with('transfer_receipt', $receipt);
    }

    private function acceptedFriends(int $userId): Collection
    {
        $blockedIds = FriendBlock::query()
            ->where('user_id', $userId)
            ->pluck('blocked_user_id')
            ->all();

        $blockedByIds = FriendBlock::query()
            ->where('blocked_user_id', $userId)
            ->pluck('user_id')
            ->all();

        $disallowed = array_unique(array_merge($blockedIds, $blockedByIds));

        $friendIds = Friendship::query()
            ->where('status', 'accepted')
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)->orWhere('friend_id', $userId);
            })
            ->get(['user_id', 'friend_id'])
            ->map(function (Friendship $friendship) use ($userId): int {
                return (int) ($friendship->user_id === $userId ? $friendship->friend_id : $friendship->user_id);
            })
            ->unique()
            ->filter(fn (int $friendId): bool => ! in_array($friendId, $disallowed, true))
            ->values();

        if ($friendIds->isEmpty()) {
            return collect();
        }

        return User::query()->whereIn('id', $friendIds)->get(['id', 'name', 'username']);
    }

    private function paymentMethodLabel(string $method): string
    {
        return match ($method) {
            'bank_transfer' => 'Bank Transfer BCA',
            'virtual_account' => 'Virtual Account',
            'qris' => 'QRIS',
            default => 'Pembayaran',
        };
    }

    private function generateReference(string $prefix): string
    {
        return sprintf('%s-%s-%03d', $prefix, now()->format('YmdHis'), random_int(100, 999));
    }

    private function applyXpDelta(int $userId, int $delta): void
    {
        $profile = DB::table('gamification_profiles')->where('user_id', $userId)->first();

        if (! $profile) {
            DB::table('gamification_profiles')->insert([
                'user_id' => $userId,
                'current_level' => 1,
                'total_xp' => max(0, $delta),
                'current_streak' => 0,
                'last_login_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        $totalXp = max(0, (int) ($profile->total_xp ?? 0) + $delta);
        $level = intdiv($totalXp, 100) + 1;

        DB::table('gamification_profiles')
            ->where('user_id', $userId)
            ->update([
                'total_xp' => $totalXp,
                'current_level' => $level,
                'updated_at' => now(),
            ]);
    }

    private function awardBadge(int $userId, string $badgeName): void
    {
        $badge = DB::table('badges')->where('name', $badgeName)->first();
        if (! $badge) {
            return;
        }

        $exists = UserBadge::query()
            ->where('user_id', $userId)
            ->where('badge_id', $badge->id)
            ->exists();

        if (! $exists) {
            UserBadge::query()->create([
                'user_id' => $userId,
                'badge_id' => $badge->id,
                'earned_at' => now(),
            ]);
        }
    }
}
