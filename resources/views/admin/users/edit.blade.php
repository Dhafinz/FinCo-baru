@extends('admin.layouts.admin')

@section('title', 'Edit User')
@section('breadcrumb', 'Users / Edit')
@section('page_title', 'Edit User')
@section('page_subtitle', 'Ubah data pengguna')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" value="{{ $user->name }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" value="{{ $user->email }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Password (kosongkan jika tidak diubah)</label>
        <input class="form-control" type="password" name="password">
    </div>
    <div class="form-group">
        <label class="form-label">Username</label>
        <input class="form-control" type="text" name="username" value="{{ $user->username }}">
    </div>
    <div class="form-group">
        <label class="form-label">Full Name</label>
        <input class="form-control" type="text" name="full_name" value="{{ $user->full_name }}">
    </div>
    <div class="form-group">
        <label class="form-label">Phone</label>
        <input class="form-control" type="text" name="phone" value="{{ $user->phone }}">
    </div>
    <div class="form-group">
        <label class="form-label">Tanggal Lahir</label>
        <input class="form-control" type="date" name="date_of_birth" value="{{ $user->date_of_birth }}">
    </div>
    <div class="form-group">
        <label class="form-label">Role</label>
        <select class="form-control" name="role" required>
            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>user</option>
            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>admin</option>
        </select>
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
