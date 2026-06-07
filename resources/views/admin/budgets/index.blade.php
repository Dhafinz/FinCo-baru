@extends('admin.layouts.admin')

@section('title', 'Budgets')
@section('breadcrumb', 'Budgets')

@section('content')
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Anggaran</h2>
            <p>Kelola anggaran pengguna</p>
        </div>
    </div>
    <a href="{{ route('admin.budgets.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Budget
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Kategori</th>
                <th>Limit</th>
                <th>Terpakai</th>
                <th>Periode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($budgets as $budget)
                <tr>
                    <td>#{{ $budget->id }}</td>
                    <td>{{ $budget->user_id }}</td>
                    <td>{{ $budget->category }}</td>
                    <td>Rp {{ number_format($budget->limit_amount, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($budget->spent_amount, 0, ',', '.') }}</td>
                    <td>{{ $budget->period }}</td>
                    <td>
                        <span class="status-badge {{ $budget->status }}">
                            <span class="dot"></span>
                            {{ $budget->status === 'on_track' ? 'On Track' : ($budget->status === 'warning' ? 'Warning' : 'Exceeded') }}
                        </span>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.budgets.edit', $budget) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.budgets.destroy', $budget) }}" onsubmit="return confirm('Hapus budget ini?')">
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
    {{ $budgets->links() }}
</div>
@endsection
