@extends('admin.layouts.admin')

@section('title', 'Tambah Badge')
@section('breadcrumb', 'Badges / Tambah')
@section('page_title', 'Tambah Badge')
@section('page_subtitle', 'Buat badge baru')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.badges.store') }}">
    @csrf
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" required>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Icon</label>
        <input class="form-control" type="text" name="icon">
    </div>
    <div class="form-group">
        <label class="form-label">Required Level</label>
        <input class="form-control" type="number" name="required_level" value="1" min="1">
    </div>
    <div class="form-group">
        <label class="form-label">Required XP</label>
        <input class="form-control" type="number" name="required_xp" min="0">
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
