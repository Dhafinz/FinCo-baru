@extends('admin.layouts.admin')

@section('title', 'Tambah Transaksi')
@section('breadcrumb', 'Transactions / Tambah')
@section('page_title', 'Tambah Transaksi')
@section('page_subtitle', 'Buat transaksi baru')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.transactions.store') }}">
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
        <label class="form-label">Category</label>
        <select class="form-control" name="category_id">
            <option value="">-</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->type }})</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Budget</label>
        <select class="form-control" name="budget_id">
            <option value="">-</option>
            @foreach ($budgets as $budget)
                <option value="{{ $budget->id }}">#{{ $budget->id }} - {{ $budget->category }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Type</label>
        <select class="form-control" name="type" required>
            <option value="income">income</option>
            <option value="expense">expense</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Amount</label>
        <input class="form-control" type="number" step="0.01" name="amount" required>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <input class="form-control" type="text" name="description">
    </div>
    <div class="form-group">
        <label class="form-label">Tanggal</label>
        <input class="form-control" type="date" name="transaction_date" required>
    </div>
    <div class="form-group">
        <label class="form-label">XP Earned</label>
        <input class="form-control" type="number" name="xp_earned" value="0" min="0">
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
