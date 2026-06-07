@extends('admin.layouts.admin')

@section('title', 'Transactions')
@section('breadcrumb', 'Transactions')

@section('content')
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Transaksi</h2>
            <p>Kelola transaksi pengguna</p>
        </div>
    </div>
    <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Transaksi
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>XP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>#{{ $transaction->id }}</td>
                    <td>{{ $transaction->user_id }}</td>
                    <td>
                        <span class="activity-type {{ $transaction->type }}">
                            {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                    </td>
                    <td>
                        <span class="activity-amount {{ $transaction->type }}">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </span>
                    </td>
                    <td>
                        <span class="activity-date">{{ $transaction->transaction_date ? $transaction->transaction_date->format('d M Y') : '-' }}</span>
                    </td>
                    <td>{{ $transaction->xp_earned }}</td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.transactions.edit', $transaction) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.transactions.destroy', $transaction) }}" onsubmit="return confirm('Hapus transaksi ini?')">
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
    {{ $transactions->links() }}
</div>
@endsection
