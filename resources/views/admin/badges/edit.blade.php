@extends('admin.layouts.admin')

@section('title', 'Edit Badge')
@section('breadcrumb', 'Badges / Edit')
@section('page_title', 'Edit Badge')
@section('page_subtitle', 'Ubah data badge')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.badges.update', $badge) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" value="{{ $badge->name }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="3">{{ $badge->description }}</textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Icon</label>
        <input class="form-control" type="text" name="icon" value="{{ $badge->icon }}">
    </div>
    <div class="form-group">
        <label class="form-label">Required Level</label>
        <input class="form-control" type="number" name="required_level" value="{{ $badge->required_level }}" min="1">
    </div>
    <div class="form-group">
        <label class="form-label">Required XP</label>
        <input class="form-control" type="number" name="required_xp" value="{{ $badge->required_xp }}" min="0">
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
