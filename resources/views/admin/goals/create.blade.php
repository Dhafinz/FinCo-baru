@extends('admin.layouts.admin')

@section('title', 'Tambah Goal')
@section('breadcrumb', 'Goals / Tambah')
@section('page_title', 'Tambah Goal')
@section('page_subtitle', 'Buat financial goal baru')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.goals.store') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">User</label>
        <select class="form-control" name="user_id" required>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" required>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Target Amount</label>
        <input class="form-control" type="number" step="0.01" name="target_amount" required>
    </div>
    <div class="form-group">
        <label class="form-label">Current Amount</label>
        <input class="form-control" type="number" step="0.01" name="current_amount" value="0">
    </div>
    <div class="form-group">
        <label class="form-label">Target Date</label>
        <input class="form-control" type="date" name="target_date" required>
    </div>
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status" required>
            <option value="active">active</option>
            <option value="completed">completed</option>
            <option value="cancelled">cancelled</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Category</label>
        <input class="form-control" type="text" name="category">
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
