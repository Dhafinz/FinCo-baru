@extends('admin.layouts.admin')

@section('title', 'Edit Kategori')
@section('breadcrumb', 'Categories / Edit')
@section('page_title', 'Edit Kategori')
@section('page_subtitle', 'Ubah kategori transaksi')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" value="{{ $category->name }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Tipe</label>
        <select class="form-control" name="type" required>
            <option value="income" {{ $category->type === 'income' ? 'selected' : '' }}>income</option>
            <option value="expense" {{ $category->type === 'expense' ? 'selected' : '' }}>expense</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Icon</label>
        <input class="form-control" type="text" name="icon" value="{{ $category->icon }}">
    </div>
    <div class="form-group">
        <label class="form-label">Color</label>
        <input class="form-control" type="text" name="color" value="{{ $category->color }}">
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
