<?php

namespace App\Http\Controllers;

use App\Models\FriendBlock;
use App\Models\Friendship;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $friends = $this->acceptedFriendsWithStats($user->id);
        $incomingRequests = Friendship::query()
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->with('user:id,name,username,email')
            ->latest()
            ->get()
            ->map(function (Friendship $friendship): array {
                $profile = DB::table('gamification_profiles')->where('user_id', $friendship->user_id)->first();

                return [
                    'id' => $friendship->id,
                    'user_id' => $friendship->user_id,
                    'name' => $friendship->user->name,
                    'username' => $friendship->user->username,
                    'initials' => $this->initials($friendship->user->name),
                    'level' => (int) ($profile->current_level ?? 1),
                    'xp' => (int) ($profile->total_xp ?? 0),
                ];
            });

        $blockedUsers = FriendBlock::query()
            ->where('user_id', $user->id)
            ->with('blockedUser:id,name,username,email')
            ->latest()
            ->get()
            ->map(function (FriendBlock $block): array {
                return [
                    'id' => $block->id,
                    'user_id' => $block->blocked_user_id,
                    'name' => $block->blockedUser?->name,
                    'username' => $block->blockedUser?->username,
                    'email' => $block->blockedUser?->email,
                ];
            });

        return view('friends.index', [
            'user' => $user,
            'friends' => $friends,
            'incomingRequests' => $incomingRequests,
            'searchResults' => collect(session('friend_search_results', [])),
            'blockedUsers' => $blockedUsers,
            'title' => 'Teman',
            'menuActive' => 'friends',
        ]);
    }

    public function search(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'keyword' => ['required', 'string', 'min:2', 'max:100'],
        ]);

        $user = $request->user();
        $keyword = trim($validated['keyword']);

        $results = User::query()
            ->where('id', '!=', $user->id)
            ->where(function ($query) use ($keyword) {
                $query->where('username', 'like', '%'.$keyword.'%')
                    ->orWhere('email', 'like', '%'.$keyword.'%')
                    ->orWhere('name', 'like', '%'.$keyword.'%');
            })
            ->limit(10)
            ->get(['id', 'name', 'username', 'email'])
            ->map(function (User $candidate) use ($user): array {
                $existing = Friendship::query()
                    ->where(function ($query) use ($user, $candidate) {
                        $query->where('user_id', $user->id)->where('friend_id', $candidate->id);
                    })
                    ->orWhere(function ($query) use ($user, $candidate) {
                        $query->where('user_id', $candidate->id)->where('friend_id', $user->id);
                    })
                    ->first();

                return [
                    'id' => $candidate->id,
                    'name' => $candidate->name,
                    'username' => $candidate->username,
                    'email' => $candidate->email,
                    'status' => $existing?->status,
                    'is_blocked' => $this->isBlockedBetween($user->id, $candidate->id),
                ];
            })
            ->values()
            ->all();

        return redirect()->route('dashboard.friends')
            ->with('friend_search_results', $results)
            ->with('success', 'Hasil pencarian teman diperbarui.');
    }

    public function sendRequest(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'target_user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $user = $request->user();
        $targetUserId = (int) $validated['target_user_id'];

        if ($targetUserId === $user->id) {
            return back()->withErrors(['target_user_id' => 'Tidak bisa menambahkan diri sendiri.']);
        }

        if ($this->isBlockedBetween($user->id, $targetUserId)) {
            return back()->withErrors(['target_user_id' => 'Tidak bisa kirim request karena salah satu akun dalam status blokir.']);
        }

        $existing = Friendship::query()
            ->where(function ($query) use ($user, $targetUserId) {
                $query->where('user_id', $user->id)->where('friend_id', $targetUserId);
            })
            ->orWhere(function ($query) use ($user, $targetUserId) {
                $query->where('user_id', $targetUserId)->where('friend_id', $user->id);
            })
            ->first();

        if ($existing && $existing->status !== 'rejected') {
            return back()->withErrors(['target_user_id' => 'Permintaan sudah ada atau kalian sudah berteman.']);
        }

        if ($existing && $existing->status === 'rejected') {
            $existing->update([
                'user_id' => $user->id,
                'friend_id' => $targetUserId,
                'status' => 'pending',
            ]);

            return redirect()->route('dashboard.friends')->with('success', 'Permintaan pertemanan berhasil dikirim ulang.');
        }

        Friendship::query()->create([
            'user_id' => $user->id,
            'friend_id' => $targetUserId,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard.friends')->with('success', 'Permintaan pertemanan berhasil dikirim.');
    }

    public function accept(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();

        $friendship = Friendship::query()
            ->where('id', $id)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        DB::transaction(function () use ($friendship, $user) {
            $friendship->update(['status' => 'accepted']);

            Friendship::query()->firstOrCreate([
                'user_id' => $user->id,
                'friend_id' => $friendship->user_id,
            ], [
                'status' => 'accepted',
            ]);

            Friendship::query()
                ->where('user_id', $user->id)
                ->where('friend_id', $friendship->user_id)
                ->update(['status' => 'accepted']);

            $this->grantFirstFriendXp($user->id);
            $this->grantFirstFriendXp($friendship->user_id);

            if ($this->countAcceptedFriends($user->id) >= 3) {
                $this->awardBadge($user->id, 'Social Saver');
            }
            if ($this->countAcceptedFriends($friendship->user_id) >= 3) {
                $this->awardBadge($friendship->user_id, 'Social Saver');
            }
        });

        return redirect()->route('dashboard.friends')->with('success', 'Permintaan teman diterima.');
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();

        $friendship = Friendship::query()
            ->where('id', $id)
            ->where('friend_id', $user->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $friendship->update(['status' => 'rejected']);

        return redirect()->route('dashboard.friends')->with('success', 'Permintaan teman ditolak.');
    }

    public function remove(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();

        Friendship::query()
            ->where(function ($query) use ($user, $id) {
                $query->where('user_id', $user->id)->where('friend_id', $id);
            })
            ->orWhere(function ($query) use ($user, $id) {
                $query->where('user_id', $id)->where('friend_id', $user->id);
            })
            ->delete();

        return redirect()->route('dashboard.friends')->with('success', 'Teman berhasil dihapus.');
    }

    public function block(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();

        if ($id === $user->id) {
            return back()->withErrors(['target_user_id' => 'Tidak bisa blokir akun sendiri.']);
        }

        DB::transaction(function () use ($user, $id) {
            Friendship::query()
                ->where(function ($query) use ($user, $id) {
                    $query->where('user_id', $user->id)->where('friend_id', $id);
                })
                ->orWhere(function ($query) use ($user, $id) {
                    $query->where('user_id', $id)->where('friend_id', $user->id);
                })
                ->delete();

            FriendBlock::query()->firstOrCreate([
                'user_id' => $user->id,
                'blocked_user_id' => $id,
            ]);
        });

        return redirect()->route('dashboard.friends')->with('success', 'User berhasil diblokir.');
    }

    public function unblock(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();

        DB::transaction(function () use ($user, $id) {
            // Ambil blocked_user_id sebelum delete
            $blockedUserId = FriendBlock::query()
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->value('blocked_user_id');

            if (!$blockedUserId) {
                return;
            }

            // Hapus block record
            FriendBlock::query()
                ->where('id', $id)
                ->where('user_id', $user->id)
                ->delete();

            // Restore friendship sebagai teman accepted (dua arah)
            Friendship::firstOrCreate(
                ['user_id' => $user->id, 'friend_id' => $blockedUserId],
                ['status' => 'accepted']
            );

            Friendship::firstOrCreate(
                ['user_id' => $blockedUserId, 'friend_id' => $user->id],
                ['status' => 'accepted']
            );
        });

        return redirect()->route('dashboard.friends')->with('success', 'Blokir berhasil dibuka dan ditambahkan kembali sebagai teman.');
    }

    private function acceptedFriendsWithStats(int $userId): Collection
    {
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
            ->filter(fn (int $friendId) => ! $this->isBlockedBetween($userId, $friendId))
            ->values();

        if ($friendIds->isEmpty()) {
            return collect();
        }

        return User::query()
            ->whereIn('id', $friendIds)
            ->get(['id', 'name', 'username', 'email'])
            ->map(function (User $friend): array {
                $profile = DB::table('gamification_profiles')->where('user_id', $friend->id)->first();

                return [
                    'id' => $friend->id,
                    'name' => $friend->name,
                    'username' => $friend->username,
                    'email' => $friend->email,
                    'initials' => $this->initials($friend->name),
                    'level' => (int) ($profile->current_level ?? 1),
                    'xp' => (int) ($profile->total_xp ?? 0),
                ];
            })
            ->values();
    }

    private function initials(string $name): string
    {
        $parts = collect(explode(' ', trim($name)))->filter()->take(2);

        if ($parts->isEmpty()) {
            return 'U';
        }

        return $parts->map(fn (string $part) => strtoupper(substr($part, 0, 1)))->implode('');
    }

    private function countAcceptedFriends(int $userId): int
    {
        return (int) Friendship::query()
            ->where('status', 'accepted')
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)->orWhere('friend_id', $userId);
            })
            ->get(['user_id', 'friend_id'])
            ->map(function (Friendship $friendship) use ($userId): int {
                return (int) ($friendship->user_id === $userId ? $friendship->friend_id : $friendship->user_id);
            })
            ->unique()
            ->count();
    }

    private function grantFirstFriendXp(int $userId): void
    {
        if ($this->countAcceptedFriends($userId) !== 1) {
            return;
        }

        $profile = DB::table('gamification_profiles')->where('user_id', $userId)->first();
        if (! $profile) {
            DB::table('gamification_profiles')->insert([
                'user_id' => $userId,
                'current_level' => 1,
                'total_xp' => 30,
                'current_streak' => 0,
                'last_login_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return;
        }

        $totalXp = max(0, (int) ($profile->total_xp ?? 0) + 30);
        DB::table('gamification_profiles')
            ->where('user_id', $userId)
            ->update([
                'total_xp' => $totalXp,
                'current_level' => intdiv($totalXp, 100) + 1,
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

    private function isBlockedBetween(int $userId, int $targetUserId): bool
    {
        return FriendBlock::query()
            ->where(function ($query) use ($userId, $targetUserId) {
                $query->where('user_id', $userId)->where('blocked_user_id', $targetUserId);
            })
            ->orWhere(function ($query) use ($userId, $targetUserId) {
                $query->where('user_id', $targetUserId)->where('blocked_user_id', $userId);
            })
            ->exists();
    }
}
