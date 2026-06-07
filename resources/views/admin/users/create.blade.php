@extends('admin.layouts.admin')

@section('title', 'Tambah User')
@section('breadcrumb', 'Users / Tambah')
@section('page_title', 'Tambah User')
@section('page_subtitle', 'Buat akun pengguna baru')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.users.store') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" required>
    </div>
    <div class="form-group">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" required>
    </div>
    <div class="form-group">
        <label class="form-label">Password</label>
        <input class="form-control" type="password" name="password" required>
    </div>
    <div class="form-group">
        <label class="form-label">Username</label>
        <input class="form-control" type="text" name="username">
    </div>
    <div class="form-group">
        <label class="form-label">Full Name</label>
        <input class="form-control" type="text" name="full_name">
    </div>
    <div class="form-group">
        <label class="form-label">Phone</label>
        <input class="form-control" type="text" name="phone">
    </div>
    <div class="form-group">
        <label class="form-label">Tanggal Lahir</label>
        <input class="form-control" type="date" name="date_of_birth">
    </div>
    <div class="form-group">
        <label class="form-label">Role</label>
        <select class="form-control" name="role" required>
            <option value="user">user</option>
            <option value="admin">admin</option>
        </select>
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
