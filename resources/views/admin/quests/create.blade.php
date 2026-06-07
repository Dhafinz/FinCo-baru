@extends('admin.layouts.admin')

@section('title', 'Tambah Quest')
@section('breadcrumb', 'Quests / Tambah')
@section('page_title', 'Tambah Quest')
@section('page_subtitle', 'Buat quest baru')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.quests.store') }}">
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
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" required>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Difficulty</label>
        <select class="form-control" name="difficulty" required>
            <option value="easy">easy</option>
            <option value="medium">medium</option>
            <option value="hard">hard</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Reward XP</label>
        <input class="form-control" type="number" name="reward_xp" value="10" min="0">
    </div>
    <div class="form-group">
        <label class="form-label">Start Date</label>
        <input class="form-control" type="date" name="start_date" required>
    </div>
    <div class="form-group">
        <label class="form-label">End Date</label>
        <input class="form-control" type="date" name="end_date" required>
    </div>
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status" required>
            <option value="active">active</option>
            <option value="completed">completed</option>
            <option value="failed">failed</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Category</label>
        <input class="form-control" type="text" name="category">
    </div>
    <div class="form-group">
        <label class="form-label">Criteria (JSON / teks)</label>
        <textarea class="form-control" name="criteria" rows="3"></textarea>
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
