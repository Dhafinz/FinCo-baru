@extends('admin.layouts.admin')

@section('title', 'Edit Transaksi')
@section('breadcrumb', 'Transactions / Edit')
@section('page_title', 'Edit Transaksi')
@section('page_subtitle', 'Ubah data transaksi')

@section('content')
<form class="admin-form" method="POST" action="{{ route('admin.transactions.update', $transaction) }}">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label class="form-label">User</label>
        <select class="form-control" name="user_id" required>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $transaction->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Category</label>
        <select class="form-control" name="category_id">
            <option value="">-</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $transaction->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }} ({{ $category->type }})</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Budget</label>
        <select class="form-control" name="budget_id">
            <option value="">-</option>
            @foreach ($budgets as $budget)
                <option value="{{ $budget->id }}" {{ $transaction->budget_id == $budget->id ? 'selected' : '' }}>#{{ $budget->id }} - {{ $budget->category }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Type</label>
        <select class="form-control" name="type" required>
            <option value="income" {{ $transaction->type === 'income' ? 'selected' : '' }}>income</option>
            <option value="expense" {{ $transaction->type === 'expense' ? 'selected' : '' }}>expense</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Amount</label>
        <input class="form-control" type="number" step="0.01" name="amount" value="{{ $transaction->amount }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <input class="form-control" type="text" name="description" value="{{ $transaction->description }}">
    </div>
    <div class="form-group">
        <label class="form-label">Tanggal</label>
        <input class="form-control" type="date" name="transaction_date" value="{{ $transaction->transaction_date }}" required>
    </div>
    <div class="form-group">
        <label class="form-label">XP Earned</label>
        <input class="form-control" type="number" name="xp_earned" value="{{ $transaction->xp_earned }}" min="0">
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
@endsection
