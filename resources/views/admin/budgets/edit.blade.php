@extends('admin.layouts.admin')

@section('title', 'Edit Budget')
@section('breadcrumb', 'Budgets / Edit')
@section('page_title', 'Edit Budget')
@section('page_subtitle', 'Ubah data anggaran')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.budgets.update', $budget) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label">User</label>
        <select class="form-control" name="user_id" required>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $budget->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Category (Text)</label>
        <input class="form-control" type="text" name="category" value="{{ $budget->category }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Category ID (opsional)</label>
        <select class="form-control" name="category_id">
            <option value="">-</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $budget->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }} ({{ $category->type }})</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Limit Amount</label>
        <input class="form-control" type="number" step="0.01" name="limit_amount" value="{{ $budget->limit_amount }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Spent Amount</label>
        <input class="form-control" type="number" step="0.01" name="spent_amount" value="{{ $budget->spent_amount }}">
    </div>
    <div class="form-group">
        <label class="form-label">Period</label>
        <select class="form-control" name="period" required>
            <option value="daily" {{ $budget->period === 'daily' ? 'selected' : '' }}>daily</option>
            <option value="weekly" {{ $budget->period === 'weekly' ? 'selected' : '' }}>weekly</option>
            <option value="monthly" {{ $budget->period === 'monthly' ? 'selected' : '' }}>monthly</option>
            <option value="yearly" {{ $budget->period === 'yearly' ? 'selected' : '' }}>yearly</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Period Start</label>
        <input class="form-control" type="date" name="period_start" value="{{ $budget->period_start }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Period End</label>
        <input class="form-control" type="date" name="period_end" value="{{ $budget->period_end }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Is Active</label>
        <select class="form-control" name="is_active" required>
            <option value="1" {{ $budget->is_active ? 'selected' : '' }}>true</option>
            <option value="0" {{ ! $budget->is_active ? 'selected' : '' }}>false</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status" required>
            <option value="on_track" {{ $budget->status === 'on_track' ? 'selected' : '' }}>on_track</option>
            <option value="warning" {{ $budget->status === 'warning' ? 'selected' : '' }}>warning</option>
            <option value="exceeded" {{ $budget->status === 'exceeded' ? 'selected' : '' }}>exceeded</option>
        </select>
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
