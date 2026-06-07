@extends('admin.layouts.admin')

@section('title', 'Gamification')
@section('breadcrumb', 'Gamification')

@section('content')
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Gamification</h2>
            <p>Ringkasan profil gamifikasi pengguna</p>
        </div>
    </div>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Level</th>
                <th>Total XP</th>
                <th>Streak</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($profiles as $profile)
                <tr>
                    <td>#{{ $profile->id }}</td>
                    <td>{{ $profile->user_id }}</td>
                    <td>
                        <span class="status-badge on_track">
                            <span class="dot"></span>
                            Level {{ $profile->current_level }}
                        </span>
                    </td>
                    <td>{{ number_format($profile->total_xp) }} XP</td>
                    <td>{{ $profile->current_streak }} hari</td>
                    <td><span class="activity-date">{{ $profile->last_login_date ?? '-' }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination">
    {{ $profiles->links() }}
</div>
@endsection
