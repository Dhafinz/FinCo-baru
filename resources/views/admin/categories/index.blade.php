@extends('admin.layouts.admin')

@section('title', 'Categories')
@section('breadcrumb', 'Categories')

@section('content')
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Kategori</h2>
            <p>Kelola kategori transaksi</p>
        </div>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Kategori
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Tipe</th>
                <th>Icon</th>
                <th>Warna</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>#{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <span class="activity-type {{ $category->type }}">
                            {{ $category->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                    </td>
                    <td>{{ $category->icon }}</td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:6px">
                            <span style="width:14px;height:14px;border-radius:4px;background:{{ $category->color ?? '#2563eb' }};display:inline-block"></span>
                            {{ $category->color }}
                        </span>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
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
    {{ $categories->links() }}
</div>
@endsection
