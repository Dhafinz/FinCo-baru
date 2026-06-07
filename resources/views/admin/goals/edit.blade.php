@extends('admin.layouts.admin')

@section('title', 'Edit Goal')
@section('breadcrumb', 'Goals / Edit')
@section('page_title', 'Edit Goal')
@section('page_subtitle', 'Ubah financial goal')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.goals.update', $goal) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label">User</label>
        <select class="form-control" name="user_id" required>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $goal->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" value="{{ $goal->name }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="3">{{ $goal->description }}</textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Target Amount</label>
        <input class="form-control" type="number" step="0.01" name="target_amount" value="{{ $goal->target_amount }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Current Amount</label>
        <input class="form-control" type="number" step="0.01" name="current_amount" value="{{ $goal->current_amount }}">
    </div>
    <div class="form-group">
        <label class="form-label">Target Date</label>
        <input class="form-control" type="date" name="target_date" value="{{ $goal->target_date }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status" required>
            <option value="active" {{ $goal->status === 'active' ? 'selected' : '' }}>active</option>
            <option value="completed" {{ $goal->status === 'completed' ? 'selected' : '' }}>completed</option>
            <option value="cancelled" {{ $goal->status === 'cancelled' ? 'selected' : '' }}>cancelled</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Category</label>
        <input class="form-control" type="text" name="category" value="{{ $goal->category }}">
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
