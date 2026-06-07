@extends('admin.layouts.admin')

@section('title', 'Users')
@section('breadcrumb', 'Users')

@section('content')
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Pengguna</h2>
            <p>Kelola akun pengguna FinCo</p>
        </div>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah User
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>#{{ $user->id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="header-avatar" style="width:28px;height:28px;font-size:11px">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:600">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->username }}</td>
                    <td>
                        <span class="status-pill status-{{ $user->role === 'admin' ? 'on_track' : 'warning' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
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
    {{ $users->links() }}
</div>
@endsection
