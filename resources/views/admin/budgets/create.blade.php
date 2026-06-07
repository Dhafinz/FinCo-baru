@extends('admin.layouts.admin')

@section('title', 'Tambah Budget')
@section('breadcrumb', 'Budgets / Tambah')
@section('page_title', 'Tambah Budget')
@section('page_subtitle', 'Buat anggaran baru')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.budgets.store') }}">
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
        <label class="form-label">Category (Text)</label>
        <input class="form-control" type="text" name="category" required>
    </div>
    <div class="form-group">
        <label class="form-label">Category ID (opsional)</label>
        <select class="form-control" name="category_id">
            <option value="">-</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->type }})</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Limit Amount</label>
        <input class="form-control" type="number" step="0.01" name="limit_amount" required>
    </div>
    <div class="form-group">
        <label class="form-label">Spent Amount</label>
        <input class="form-control" type="number" step="0.01" name="spent_amount" value="0">
    </div>
    <div class="form-group">
        <label class="form-label">Period</label>
        <select class="form-control" name="period" required>
            <option value="daily">daily</option>
            <option value="weekly">weekly</option>
            <option value="monthly" selected>monthly</option>
            <option value="yearly">yearly</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Period Start</label>
        <input class="form-control" type="date" name="period_start" required>
    </div>
    <div class="form-group">
        <label class="form-label">Period End</label>
        <input class="form-control" type="date" name="period_end" required>
    </div>
    <div class="form-group">
        <label class="form-label">Is Active</label>
        <select class="form-control" name="is_active" required>
            <option value="1">true</option>
            <option value="0">false</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status" required>
            <option value="on_track">on_track</option>
            <option value="warning">warning</option>
            <option value="exceeded">exceeded</option>
        </select>
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
