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
        <input class="form-control" type="date" name="start_date" value="{{ $quest->start_date instanceof \Carbon\Carbon ? $quest->start_date->format('Y-m-d') : $quest->start_date }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">End Date</label>
        <input class="form-control" type="date" name="end_date" value="{{ $quest->end_date instanceof \Carbon\Carbon ? $quest->end_date->format('Y-m-d') : $quest->end_date }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status" required>
            <option value="active" {{ $quest->status === 'active' ? 'selected' : '' }}>active</option>
            <option value="completed" {{ $quest->status === 'completed' ? 'selected' : '' }}>completed</option>
            <option value="failed" {{ $quest->status === 'failed' ? 'selected' : '' }}>failed</option>
        </select>
    </div>
    @php
        $raw = $quest->criteria;
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            $criteriaArr = is_array($decoded) ? $decoded : [];
        } else {
            $criteriaArr = is_array($raw) ? $raw : [];
        }
        $trackingVal = $criteriaArr['tracking'] ?? 'transaction_count';
        $targetVal = $criteriaArr['target'] ?? '';
        $inferTipe = in_array($trackingVal, ['income_total']) ? 'income' : (in_array($trackingVal, ['expense_category_total', 'expense_total', 'no_spend_days']) ? 'expense' : 'both');
    @endphp
    <div class="form-group">
        <label class="form-label">Tipe</label>
        <select class="form-control" name="tipe" required>
            <option value="income" {{ $inferTipe === 'income' ? 'selected' : '' }}>Income (Nabung)</option>
            <option value="expense" {{ $inferTipe === 'expense' ? 'selected' : '' }}>Expense (Hemat)</option>
            <option value="both" {{ $inferTipe === 'both' ? 'selected' : '' }}>Both (Umum)</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Tracking</label>
        <select class="form-control" name="tracking" id="tracking" required>
            <option value="income_total" {{ $trackingVal === 'income_total' ? 'selected' : '' }}>Total Income (Nabung)</option>
            <option value="expense_category_total" {{ $trackingVal === 'expense_category_total' ? 'selected' : '' }}>Per Kategori Expense (Hemat)</option>
            <option value="expense_total" {{ $trackingVal === 'expense_total' ? 'selected' : '' }}>Total Expense (Hemat)</option>
            <option value="transaction_count" {{ $trackingVal === 'transaction_count' ? 'selected' : '' }}>Jumlah Transaksi</option>
            <option value="no_spend_days" {{ $trackingVal === 'no_spend_days' ? 'selected' : '' }}>Hari Tanpa Belanja</option>
            <option value="login_streak" {{ $trackingVal === 'login_streak' ? 'selected' : '' }}>Login Streak</option>
        </select>
    </div>
    <div class="form-group" id="kategori-group" style="{{ $trackingVal === 'expense_category_total' ? '' : 'display:none;' }}">
        <label class="form-label">Kategori Expense</label>
        <select class="form-control" name="category_id">
            <option value="">— Pilih Kategori —</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ ($quest->category ?? '') === $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Target</label>
        <input class="form-control" type="number" name="target" value="{{ $targetVal }}" placeholder="100000" min="0">
    </div>
    <div class="form-group" style="display:none;">
        <label class="form-label">Category (legacy)</label>
        <input class="form-control" type="text" name="category" value="{{ $quest->category }}">
    </div>
    <div class="form-group" style="display:none;">
        <label class="form-label">Criteria (JSON / teks)</label>
        <textarea class="form-control" name="criteria" rows="3">{{ is_array($quest->criteria) ? json_encode($quest->criteria) : $quest->criteria }}</textarea>
    </div>

    <script>
        document.getElementById('tracking').addEventListener('change', function() {
            document.getElementById('kategori-group').style.display = this.value === 'expense_category_total' ? '' : 'none';
        });
    </script>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
