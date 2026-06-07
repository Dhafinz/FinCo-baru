@extends('admin.layouts.admin')

@section('title', 'Quests')
@section('breadcrumb', 'Quests')

@section('content')
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Quest</h2>
            <p>Kelola quest gamifikasi</p>
        </div>
    </div>
    <a href="{{ route('admin.quests.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Quest
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Nama</th>
                <th>Difficulty</th>
                <th>Reward XP</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quests as $quest)
                <tr>
                    <td>#{{ $quest->id }}</td>
                    <td>{{ $quest->user_id }}</td>
                    <td style="font-weight:600">{{ $quest->name }}</td>
                    <td>
                        <span class="status-badge {{ $quest->difficulty === 'easy' ? 'on_track' : ($quest->difficulty === 'medium' ? 'warning' : 'exceeded') }}">
                            <span class="dot"></span>
                            {{ ucfirst($quest->difficulty) }}
                        </span>
                    </td>
                    <td>{{ $quest->reward_xp }}</td>
                    <td>
                        <span class="status-pill status-{{ $quest->status === 'completed' ? 'on_track' : ($quest->status === 'in_progress' ? 'warning' : 'exceeded') }}">
                            {{ $quest->status }}
                        </span>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.quests.edit', $quest) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.quests.destroy', $quest) }}" onsubmit="return confirm('Hapus quest ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="pagination">
    {{ $quests->links() }}
</div>
@endsection
