@extends('admin.layouts.admin')

@section('title', 'Edit Quest')
@section('breadcrumb', 'Quests / Edit')
@section('page_title', 'Edit Quest')
@section('page_subtitle', 'Ubah data quest')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.quests.update', $quest) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label">User</label>
        <select class="form-control" name="user_id" required>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $quest->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" value="{{ $quest->name }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="3">{{ $quest->description }}</textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Difficulty</label>
        <select class="form-control" name="difficulty" required>
            <option value="easy" {{ $quest->difficulty === 'easy' ? 'selected' : '' }}>easy</option>
            <option value="medium" {{ $quest->difficulty === 'medium' ? 'selected' : '' }}>medium</option>
            <option value="hard" {{ $quest->difficulty === 'hard' ? 'selected' : '' }}>hard</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Reward XP</label>
        <input class="form-control" type="number" name="reward_xp" value="{{ $quest->reward_xp }}" min="0">
    </div>
    <div class="form-group">
        <label class="form-label">Start Date</label>
        <input class="form-control" type="date" name="start_date" value="{{ $quest->start_date }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">End Date</label>
        <input class="form-control" type="date" name="end_date" value="{{ $quest->end_date }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status" required>
            <option value="active" {{ $quest->status === 'active' ? 'selected' : '' }}>active</option>
            <option value="completed" {{ $quest->status === 'completed' ? 'selected' : '' }}>completed</option>
            <option value="failed" {{ $quest->status === 'failed' ? 'selected' : '' }}>failed</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Category</label>
        <input class="form-control" type="text" name="category" value="{{ $quest->category }}">
    </div>
    <div class="form-group">
        <label class="form-label">Criteria (JSON / teks)</label>
        <textarea class="form-control" name="criteria" rows="3">{{ is_array($quest->criteria) ? json_encode($quest->criteria) : $quest->criteria }}</textarea>
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
