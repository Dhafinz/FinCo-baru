@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@push('styles')
<style>
.chart-container canvas { max-height: 260px; }
</style>
@endpush

@section('content')
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon blue">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="stat-card-trend {{ $stats['users_trend'] >= 0 ? 'up' : 'down' }}">
                <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
                {{ $stats['users_trend'] }}
            </span>
        </div>
        <div class="stat-card-label">Total Pengguna</div>
        <div class="stat-card-value">{{ number_format($stats['users']) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon green">
                <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <span class="stat-card-trend {{ $stats['transactions_trend'] >= 0 ? 'up' : 'down' }}">
                <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
                {{ $stats['transactions_trend'] }}
            </span>
        </div>
        <div class="stat-card-label">Total Transaksi</div>
        <div class="stat-card-value">{{ number_format($stats['transactions']) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon orange">
                <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
            </div>
            <span class="stat-card-trend {{ $stats['budgets_trend'] >= 0 ? 'up' : 'down' }}">
                <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
                {{ $stats['budgets_trend'] }}
            </span>
        </div>
        <div class="stat-card-label">Total Anggaran</div>
        <div class="stat-card-value">{{ number_format($stats['budgets']) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div class="stat-card-icon purple">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
            </div>
            <span class="stat-card-trend up">
                <svg viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
                +12%
            </span>
        </div>
        <div class="stat-card-label">Total Kategori</div>
        <div class="stat-card-value">{{ number_format($stats['categories']) }}</div>
    </div>
</div>

<div class="dashboard-grid-2">
    <div class="chart-card">
        <div class="chart-card-header">
            <div>
                <div class="chart-card-title">Grafik Keuangan</div>
                <div class="chart-card-subtitle">Perbandingan pemasukan & pengeluaran 6 bulan terakhir</div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="financeChart"></canvas>
        </div>
    </div>

    <div class="activity-card">
        <div class="activity-card-header">
            <h3>Aktivitas Terbaru</h3>
            <a href="{{ route('admin.transactions.index') }}">Lihat Semua</a>
        </div>
        <table class="activity-table">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentTransactions as $t)
                <tr>
                    <td>
                        <div class="activity-user">
                            <div class="activity-user-avatar" style="background: {{ ['#2563eb','#10b981','#f59e0b','#6366f1','#ef4444','#0d9488'][rand(0,5)] }}">
                                {{ strtoupper(substr($t['user_name'], 0, 1)) }}
                            </div>
                            <span class="activity-user-name">{{ $t['user_name'] }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="activity-type {{ $t['type'] }}">
                            @if ($t['type'] === 'income')
                            <svg viewBox="0 0 24 24"><polyline points="7 17 17 7"/><polyline points="7 7 17 7 17 17"/></svg>
                            @else
                            <svg viewBox="0 0 24 24"><polyline points="17 7 7 17"/><polyline points="17 17 7 17 7 7"/></svg>
                            @endif
                            {{ $t['type'] === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                    </td>
                    <td>
                        <span class="activity-amount {{ $t['type'] }}">
                            Rp {{ number_format($t['amount'], 0, ',', '.') }}
                        </span>
                    </td>
                    <td>
                        <span class="activity-date">{{ $t['date'] }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;padding:32px;color:var(--text-muted)">
                        Belum ada transaksi terbaru
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="dashboard-grid-2">
    <div class="chart-card">
        <div class="chart-card-header">
            <div>
                <div class="chart-card-title">Distribusi Kategori</div>
                <div class="chart-card-subtitle">Jumlah transaksi per kategori</div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-card-header">
            <div>
                <div class="chart-card-title">Menu Cepat</div>
                <div class="chart-card-subtitle">Akses fitur utama admin</div>
            </div>
        </div>
        <div class="quick-grid">
            <a class="quick-card" href="{{ route('admin.users.index') }}">
                <div class="quick-card-icon blue">
                    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <span class="quick-card-label">Users</span>
                <span class="quick-card-count">{{ number_format($stats['users']) }} pengguna</span>
            </a>
            <a class="quick-card" href="{{ route('admin.transactions.index') }}">
                <div class="quick-card-icon green">
                    <svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <span class="quick-card-label">Transaksi</span>
                <span class="quick-card-count">{{ number_format($stats['transactions']) }} transaksi</span>
            </a>
            <a class="quick-card" href="{{ route('admin.categories.index') }}">
                <div class="quick-card-icon purple">
                    <svg viewBox="0 0 24 24"><path d="M4 4h5l2 2h9v2H4V4z"/><path d="M4 10h16v10H4V10z"/></svg>
                </div>
                <span class="quick-card-label">Kategori</span>
                <span class="quick-card-count">{{ number_format($stats['categories']) }} kategori</span>
            </a>
            <a class="quick-card" href="{{ route('admin.budgets.index') }}">
                <div class="quick-card-icon orange">
                    <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                </div>
                <span class="quick-card-label">Anggaran</span>
                <span class="quick-card-count">{{ number_format($stats['budgets']) }} anggaran</span>
            </a>
            <a class="quick-card" href="{{ route('admin.goals.index') }}">
                <div class="quick-card-icon teal">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                </div>
                <span class="quick-card-label">Goals</span>
                <span class="quick-card-count">{{ number_format($stats['goals']) }} goals</span>
            </a>
            <a class="quick-card" href="{{ route('admin.quests.index') }}">
                <div class="quick-card-icon red">
                    <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <span class="quick-card-label">Quests</span>
                <span class="quick-card-count">{{ number_format($stats['quests']) }} quests</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const textColor = '#475569';
    const gridColor = '#e2e8f0';

    new Chart(document.getElementById('financeChart'), {
        type: 'bar',
        data: {
            labels: @json($monthlyLabels),
            datasets: [
                {
                    label: 'Pemasukan',
                    data: @json($monthlyIncome),
                    backgroundColor: 'rgba(37, 99, 235, 0.8)',
                    borderColor: '#2563eb',
                    borderWidth: 0,
                    borderRadius: 6,
                    barPercentage: 0.6,
                },
                {
                    label: 'Pengeluaran',
                    data: @json($monthlyExpense),
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderColor: '#ef4444',
                    borderWidth: 0,
                    borderRadius: 6,
                    barPercentage: 0.6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 12,
                        boxHeight: 12,
                        borderRadius: 3,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 16,
                        font: { size: 12, weight: '500' },
                        color: textColor,
                    }
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#0f172a',
                    bodyColor: '#475569',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': Rp ' + Number(ctx.raw).toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor, drawBorder: false },
                    border: { display: false },
                    ticks: {
                        color: textColor,
                        font: { size: 11 },
                        padding: 8,
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000).toFixed(1) + 'jt';
                            if (value >= 1000) return (value / 1000).toFixed(0) + 'rb';
                            return value;
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: {
                        color: textColor,
                        font: { size: 11 },
                        padding: 8,
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: @json($categoryDistribution->pluck('name')),
            datasets: [{
                data: @json($categoryDistribution->pluck('count')),
                backgroundColor: [
                    '#2563eb', '#10b981', '#f59e0b', '#6366f1',
                    '#ef4444', '#0d9488', '#8b5cf6', '#ec4899',
                    '#14b8a6', '#f97316', '#06b6d4', '#84cc16'
                ],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        boxHeight: 10,
                        borderRadius: 2,
                        padding: 12,
                        font: { size: 11, weight: '500' },
                        color: textColor,
                    }
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#0f172a',
                    bodyColor: '#475569',
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 8,
                }
            }
        }
    });
});
</script>
@endpush
