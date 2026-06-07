@extends('admin.layouts.admin')

@section('title', 'Goals')
@section('breadcrumb', 'Goals')

@section('content')
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Goals</h2>
            <p>Kelola financial goals pengguna</p>
        </div>
    </div>
    <a href="{{ route('admin.goals.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Goal
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Nama</th>
                <th>Target</th>
                <th>Terkumpul</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($goals as $goal)
                <tr>
                    <td>#{{ $goal->id }}</td>
                    <td>{{ $goal->user_id }}</td>
                    <td style="font-weight:600">{{ $goal->name }}</td>
                    <td>Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="status-pill status-{{ $goal->status === 'completed' ? 'on_track' : ($goal->status === 'in_progress' ? 'warning' : 'exceeded') }}">
                            {{ $goal->status }}
                        </span>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.goals.edit', $goal) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.goals.destroy', $goal) }}" onsubmit="return confirm('Hapus goal ini?')">
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
    {{ $goals->links() }}
</div>
@endsection
