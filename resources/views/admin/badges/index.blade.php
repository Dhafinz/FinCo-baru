@extends('admin.layouts.admin')

@section('title', 'Badges')
@section('breadcrumb', 'Badges')

@section('content')
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Badge</h2>
            <p>Kelola badge gamifikasi</p>
        </div>
    </div>
    <a href="{{ route('admin.badges.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Badge
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Icon</th>
                <th>Required Level</th>
                <th>Required XP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($badges as $badge)
                <tr>
                    <td>#{{ $badge->id }}</td>
                    <td style="font-weight:600">{{ $badge->name }}</td>
                    <td>{{ $badge->icon }}</td>
                    <td>Level {{ $badge->required_level }}</td>
                    <td>{{ number_format($badge->required_xp) }} XP</td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.badges.edit', $badge) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.badges.destroy', $badge) }}" onsubmit="return confirm('Hapus badge ini?')">
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
    {{ $badges->links() }}
</div>
@endsection
