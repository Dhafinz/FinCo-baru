@extends('admin.layouts.admin')

@section('title', 'Laporan')
@section('breadcrumb', 'Laporan')

@section('content')
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon blue">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
        </div>
        <div class="stat-card-label">Total Users</div>
        <div class="stat-card-value">{{ number_format($summary['users'] ?? 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon green">
                <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
        </div>
        <div class="stat-card-label">Total Transaksi</div>
        <div class="stat-card-value">{{ number_format($summary['transactions'] ?? 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon purple">
                <svg viewBox="0 0 24 24"><polyline points="7 17 17 7"/><polyline points="7 7 17 7 17 17"/></svg>
            </div>
        </div>
        <div class="stat-card-label">Total Pemasukan</div>
        <div class="stat-card-value">Rp {{ number_format($summary['income'] ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon red">
                <svg viewBox="0 0 24 24"><polyline points="17 7 7 17"/><polyline points="17 17 7 17 7 7"/></svg>
            </div>
        </div>
        <div class="stat-card-label">Total Pengeluaran</div>
        <div class="stat-card-value">Rp {{ number_format($summary['expense'] ?? 0, 0, ',', '.') }}</div>
    </div>
</div>
@endsection
