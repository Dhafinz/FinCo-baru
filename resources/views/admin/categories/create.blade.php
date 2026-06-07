@extends('admin.layouts.admin')

@section('title', 'Tambah Kategori')
@section('breadcrumb', 'Categories / Tambah')
@section('page_title', 'Tambah Kategori')
@section('page_subtitle', 'Buat kategori transaksi baru')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" required>
    </div>
    <div class="form-group">
        <label class="form-label">Tipe</label>
        <select class="form-control" name="type" required>
            <option value="income">income</option>
            <option value="expense">expense</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Icon</label>
        <input class="form-control" type="text" name="icon">
    </div>
    <div class="form-group">
        <label class="form-label">Color</label>
        <input class="form-control" type="text" name="color">
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
