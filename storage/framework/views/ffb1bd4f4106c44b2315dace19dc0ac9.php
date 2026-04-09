<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | FinCo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --text: #122033;
            --muted: #55708d;
            --line: #dbe8f6;
            --blue-600: #2563eb;
            --blue-500: #3b82f6;
            --panel: #ffffff;
            --bg: #f5f9ff;
            --shadow: 0 14px 30px rgba(37, 99, 235, 0.10);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 0% 0%, #eaf2ff 0%, transparent 30%),
                radial-gradient(circle at 100% 10%, #eaf2ff 0%, transparent 32%),
                var(--bg);
            min-height: 100vh;
        }

        .container {
            width: min(1160px, 93%);
            margin: 0 auto;
        }

        .app-shell {
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
            min-height: 100vh;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 35;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--line);
        }

        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.8rem 1rem;
        }

        .topbar-left {
            display: flex;
            flex-direction: column;
            gap: 0.12rem;
        }

        .location {
            font-size: 0.8rem;
            color: var(--muted);
            font-weight: 500;
        }

        .topbar-user {
            font-family: 'Sora', sans-serif;
            font-size: 0.96rem;
            color: #1f3b63;
            font-weight: 700;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .date-chip {
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 0.38rem 0.72rem;
            background: #fff;
            color: #355777;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .brand {
            font-family: 'Sora', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: 0.4px;
        }

        .brand span { color: inherit; }

        .brand-sub {
            color: var(--muted);
            font-size: 0.86rem;
            margin-top: 0.2rem;
        }

        .btn {
            border: 0;
            border-radius: 12px;
            padding: 0.66rem 0.95rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
        }

        .btn-logout {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            border: 1px solid #1d4ed8;
        }

        .btn-soft {
            color: #1d4ed8;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
        }

        .btn-danger {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #fecaca;
        }

        .content-area { min-width: 0; }

        main {
            padding: 1rem;
        }

        .dashboard-shell {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            align-items: start;
        }

        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            padding: 0.9rem;
            border-radius: 0;
            border-top: 0;
            border-bottom: 0;
            border-left: 0;
            box-shadow: none;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            border: 1px solid var(--line);
            border-radius: 12px;
            background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%);
            padding: 0.75rem 0.8rem;
            margin-bottom: 0.7rem;
        }

        .sidebar-brand .brand {
            font-size: 1.05rem;
        }

        .sidebar-brand .brand-sub {
            font-size: 0.78rem;
            margin-top: 0.18rem;
        }

        .sidebar h2 {
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
        }

        .sidebar p {
            font-size: 0.8rem;
            color: var(--muted);
            margin-bottom: 0.7rem;
        }

        .menu {
            list-style: none;
            display: grid;
            gap: 0.4rem;
        }

        .menu a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            color: #1f3b63;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0.55rem 0.65rem;
            background: #fff;
            font-size: 0.86rem;
            font-weight: 600;
            transition: 0.15s ease;
        }

        .menu a:hover {
            border-color: #bfdbfe;
            background: #f5f9ff;
            color: var(--blue-600);
        }

        .menu a.active {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-color: #93c5fd;
            color: #1d4ed8;
        }

        .menu small {
            color: var(--muted);
            font-weight: 500;
            font-size: 0.74rem;
        }

        .content-stack { min-width: 0; }

        .hero {
            background: linear-gradient(135deg, #0f2c56 0%, #1e56a0 58%, #3b82f6 100%);
            color: #fff;
            border-radius: 18px;
            padding: 1.2rem;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 1rem;
            align-items: center;
            box-shadow: var(--shadow);
        }

        .hero h1 {
            font-family: 'Sora', sans-serif;
            font-size: clamp(1.25rem, 2.6vw, 1.8rem);
            margin-bottom: 0.35rem;
        }

        .hero p {
            color: #eef5ff;
            max-width: 65ch;
            font-size: 0.93rem;
            line-height: 1.55;
        }

        .hero-pill {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.34);
            border-radius: 14px;
            padding: 0.9rem 1rem;
            min-width: 220px;
        }

        .hero-pill strong {
            display: block;
            font-family: 'Sora', sans-serif;
            font-size: 1.45rem;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .hero-pill span {
            font-size: 0.84rem;
            color: #dbeafe;
        }

        .stats {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: repeat(4, minmax(140px, 1fr));
            gap: 0.75rem;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(30, 64, 175, 0.05);
        }

        .stat {
            padding: 0.95rem;
        }

        .stat small {
            color: var(--muted);
            font-size: 0.76rem;
        }

        .stat strong {
            display: block;
            margin-top: 0.28rem;
            font-family: 'Sora', sans-serif;
            color: var(--blue-600);
            font-size: 1.35rem;
        }

        .layout {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 0.9rem;
        }

        .panel {
            padding: 1rem;
        }

        .panel h2 {
            font-size: 1rem;
            margin-bottom: 0.2rem;
        }

        .panel p {
            color: var(--muted);
            font-size: 0.84rem;
            margin-bottom: 0.75rem;
        }

        .transaction-list {
            display: grid;
            gap: 0.5rem;
        }

        .item {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0.6rem 0.7rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            background: #fff;
        }

        .item h3 {
            font-size: 0.92rem;
            margin-bottom: 0.2rem;
        }

        .item p {
            margin: 0;
            font-size: 0.77rem;
        }

        .badge {
            display: inline-flex;
            padding: 0.2rem 0.56rem;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 700;
        }

        .badge-income {
            color: #166534;
            background: #dcfce7;
        }

        .badge-expense {
            color: #991b1b;
            background: #fee2e2;
        }

        .placeholder {
            border: 1px dashed #bfdbfe;
            border-radius: 12px;
            background: #f8fbff;
            padding: 0.9rem;
            color: #3b5d85;
            font-size: 0.86rem;
            line-height: 1.5;
        }

        .alert {
            border-radius: 12px;
            padding: 0.72rem 0.84rem;
            margin-bottom: 0.8rem;
            font-size: 0.86rem;
            border: 1px solid;
        }

        .alert-success {
            background: #ecfdf5;
            border-color: #a7f3d0;
            color: #065f46;
        }

        .alert-error {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        .transaction-create {
            margin-top: 1rem;
            padding: 1rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(120px, 1fr));
            gap: 0.65rem;
        }

        .field {
            display: grid;
            gap: 0.3rem;
        }

        .field-2 {
            grid-column: span 2;
        }

        .field label {
            font-size: 0.78rem;
            color: var(--muted);
            font-weight: 600;
        }

        .field input,
        .field select {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0.52rem 0.62rem;
            font-family: inherit;
            font-size: 0.86rem;
            color: var(--text);
            background: #fff;
            width: 100%;
        }

        .row-actions {
            margin-top: 0.6rem;
            display: flex;
            gap: 0.45rem;
            flex-wrap: wrap;
        }

        .feature-grid {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(140px, 1fr));
            gap: 0.75rem;
        }

        .feature-item {
            padding: 0.95rem;
        }

        .feature-item h3 {
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            color: #1f3b63;
        }

        .feature-item p {
            margin: 0;
            color: var(--muted);
            font-size: 0.8rem;
            line-height: 1.45;
        }

        .leaderboard-wrap {
            overflow-x: auto;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
        }

        .leaderboard-table {
            width: 100%;
            min-width: 760px;
            border-collapse: collapse;
            font-size: 0.83rem;
        }

        .leaderboard-table thead th {
            text-align: left;
            color: var(--muted);
            font-weight: 700;
            border-bottom: 1px solid var(--line);
            padding: 0.72rem 0.7rem;
            white-space: nowrap;
        }

        .leaderboard-table tbody td {
            border-bottom: 1px solid #edf3fc;
            padding: 0.72rem 0.7rem;
            vertical-align: middle;
        }

        .leaderboard-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .leaderboard-me {
            background: #edf5ff;
        }

        .leaderboard-rank {
            font-family: 'Sora', sans-serif;
            color: #1e3a8a;
            font-size: 0.85rem;
        }

        .score-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 84px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1e3a8a;
            font-weight: 800;
            font-size: 0.78rem;
            padding: 0.26rem 0.56rem;
        }

        .badge-mini-list {
            margin-top: 0.2rem;
            font-size: 0.72rem;
            color: #49688b;
            white-space: nowrap;
        }

        .progress-line {
            margin-top: 0.55rem;
            height: 8px;
            border-radius: 999px;
            background: #e7eef8;
            overflow: hidden;
        }

        .progress-line span {
            display: block;
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        }

        .badge-help {
            margin-top: 0.45rem;
            font-size: 0.76rem;
            color: #406184;
            line-height: 1.45;
        }

        @media (max-width: 980px) {
            .app-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
                height: auto;
                border: 1px solid var(--line);
                border-radius: 14px;
                box-shadow: 0 10px 24px rgba(30, 64, 175, 0.05);
                margin: 0.75rem;
            }

            .topbar {
                margin: 0.75rem;
                border-radius: 14px;
                border: 1px solid var(--line);
            }

            .menu {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sidebar-brand {
                margin-bottom: 0.75rem;
            }

            .hero {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(140px, 1fr));
            }

            .layout {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: repeat(2, minmax(120px, 1fr));
            }

            .feature-grid {
                grid-template-columns: repeat(2, minmax(140px, 1fr));
            }

            .hero-pill { min-width: 0; }
        }

        @media (max-width: 560px) {
            .topbar-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .topbar-right {
                width: 100%;
                justify-content: space-between;
            }

            .menu {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .field-2 {
                grid-column: span 1;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <aside class="card sidebar">
            <div class="sidebar-brand">
                <div class="brand">FIN<span>CO</span></div>
                <div class="brand-sub"><?php echo e($user->name); ?> • User Panel</div>
            </div>
            <h2>Menu Fitur</h2>
            <p>Semua fitur nanti ditaruh di sidebar ini.</p>
            <ul class="menu">
                <li><a class="<?php echo e(($activeFeature ?? 'overview') === 'overview' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Overview <small>aktif</small></a></li>
                <li><a class="<?php echo e(($activeFeature ?? '') === 'transactions' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.transactions')); ?>">Transaksi <small>fitur</small></a></li>
                <li><a class="<?php echo e(($activeFeature ?? '') === 'budgets' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.budgets')); ?>">Budget <small>fitur</small></a></li>
                <li><a class="<?php echo e(($activeFeature ?? '') === 'goals' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.goals')); ?>">Goals <small>fitur</small></a></li>
                <li><a class="<?php echo e(($activeFeature ?? '') === 'challenges' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.challenges')); ?>">Challenges <small>fitur</small></a></li>
                <li><a class="<?php echo e(($activeFeature ?? '') === 'badges' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.badges')); ?>">Badges <small>fitur</small></a></li>
                <li><a class="<?php echo e(($activeFeature ?? '') === 'leaderboard' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.leaderboard')); ?>">Leaderboard <small>fitur</small></a></li>
                <li><a href="<?php echo e(route('dashboard.wallet')); ?>">Wallet <small>baru</small></a></li>
                <li><a href="<?php echo e(route('dashboard.friends')); ?>">Teman <small>baru</small></a></li>
                <li><a class="<?php echo e(($activeFeature ?? '') === 'reports' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.reports')); ?>">Laporan <small>fitur</small></a></li>
                <li><a class="<?php echo e(($activeFeature ?? '') === 'settings' ? 'active' : ''); ?>" href="<?php echo e(route('dashboard.settings')); ?>">Pengaturan <small>fitur</small></a></li>
            </ul>
        </aside>

        <div class="content-area">
            <header class="topbar">
                <div class="topbar-inner">
                    <div class="topbar-left">
                        <div class="location">Lokasi: Dashboard / <?php echo e($featureTitle ?? 'Overview'); ?></div>
                        <div class="topbar-user"><?php echo e($user->name); ?></div>
                    </div>
                    <div class="topbar-right">
                        <div class="date-chip"><?php echo e(now()->translatedFormat('d F Y')); ?></div>
                        <form action="<?php echo e(route('logout')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-logout">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <main>
                <div class="dashboard-shell">
                    <div class="content-stack">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="alert alert-error"><?php echo e(session('error')); ?></div>
                        <?php endif; ?>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-error"><?php echo e($errors->first()); ?></div>
                        <?php endif; ?>

                        <?php if(($activeFeature ?? 'overview') === 'overview'): ?>
                            <section class="hero card">
                                <div>
                                    <h1>Selamat datang kembali, <?php echo e($user->name); ?>.</h1>
                                    <p>Ringkasan utama keuangan dan progres gamification kamu hari ini.</p>
                                </div>
                                <div class="hero-pill">
                                    <strong>Level <?php echo e($currentLevel); ?></strong>
                                    <span>Total XP: <?php echo e(number_format($totalXp, 0, ',', '.')); ?></span>
                                </div>
                            </section>

                            <section class="stats">
                                <article class="card stat">
                                    <small>Total Income</small>
                                    <strong>Rp <?php echo e(number_format($totalIncome, 0, ',', '.')); ?></strong>
                                </article>
                                <article class="card stat">
                                    <small>Total Expense</small>
                                    <strong>Rp <?php echo e(number_format($totalExpense, 0, ',', '.')); ?></strong>
                                </article>
                                <article class="card stat">
                                    <small>Current Balance</small>
                                    <strong>Rp <?php echo e(number_format($balance, 0, ',', '.')); ?></strong>
                                </article>
                                <article class="card stat">
                                    <small>Jumlah Transaksi</small>
                                    <strong><?php echo e(number_format($transactionCount, 0, ',', '.')); ?></strong>
                                </article>
                            </section>

                            <section class="layout">
                                <article class="card panel">
                                    <h2>Transaksi Terbaru</h2>
                                    <p>Ringkasan aktivitas finansial terbaru akun kamu.</p>
                                    <div class="transaction-list">
                                        <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <div class="item">
                                                <div>
                                                    <h3><?php echo e($trx->description ?: 'Transaksi'); ?></h3>
                                                    <p><?php echo e(\Illuminate\Support\Carbon::parse($trx->transaction_date)->format('d M Y')); ?> • XP +<?php echo e($trx->xp_earned ?? 0); ?></p>
                                                </div>
                                                <div style="text-align:right;">
                                                    <span class="badge <?php echo e(($trx->type ?? 'expense') === 'income' ? 'badge-income' : 'badge-expense'); ?>"><?php echo e(strtoupper($trx->type ?? 'expense')); ?></span>
                                                    <div style="margin-top:0.25rem;font-weight:700;color:#1f3b63;">Rp <?php echo e(number_format((float) ($trx->amount ?? 0), 0, ',', '.')); ?></div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="placeholder">Belum ada transaksi. Kamu bisa lanjut dari menu Transaksi untuk mulai catat pemasukan dan pengeluaran.</div>
                                        <?php endif; ?>
                                    </div>
                                </article>

                                <article class="card panel">
                                    <h2>Akses Cepat Fitur</h2>
                                    <p>Pilih fitur di sidebar kiri untuk buka modul yang kamu butuhkan.</p>
                                    <div class="feature-grid">
                                        <article class="card feature-item">
                                            <h3>Transaksi</h3>
                                            <p>Lihat catatan pemasukan dan pengeluaran terbaru.</p>
                                        </article>
                                        <article class="card feature-item">
                                            <h3>Budget</h3>
                                            <p>Pantau batas pengeluaran agar tetap sesuai rencana.</p>
                                        </article>
                                        <article class="card feature-item">
                                            <h3>Goals</h3>
                                            <p>Kelola target tabungan dan progress pencapaiannya.</p>
                                        </article>
                                    </div>
                                </article>
                            </section>
                        <?php elseif($activeFeature === 'transactions'): ?>
                            <section class="hero card">
                                <div>
                                    <h1>Fitur Transaksi</h1>
                                    <p>Kelola transaksi: tambah, edit, dan hapus langsung dari halaman ini.</p>
                                </div>
                                <div class="hero-pill">
                                    <strong><?php echo e(number_format($transactionCount, 0, ',', '.')); ?></strong>
                                    <span>Total transaksi tercatat</span>
                                </div>
                            </section>

                            <section class="card transaction-create">
                                <h2>Tambah Transaksi</h2>
                                <p style="margin-top:0.2rem;color:var(--muted);font-size:0.84rem;">Catat pemasukan atau pengeluaran harian kamu.</p>
                                <form action="<?php echo e(route('dashboard.transactions.store')); ?>" method="POST" style="margin-top:0.75rem;">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-grid">
                                        <div class="field">
                                            <label for="trx_type">Tipe</label>
                                            <select id="trx_type" name="type" required>
                                                <option value="income">Income</option>
                                                <option value="expense">Expense</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label for="trx_amount">Amount</label>
                                            <input id="trx_amount" type="number" min="0" step="0.01" name="amount" value="<?php echo e(old('amount')); ?>" required>
                                        </div>
                                        <div class="field">
                                            <label for="trx_date">Tanggal</label>
                                            <input id="trx_date" type="date" name="transaction_date" value="<?php echo e(old('transaction_date', now()->toDateString())); ?>" required>
                                        </div>
                                        <div class="field">
                                            <label for="trx_category">Kategori</label>
                                            <select id="trx_category" name="category_id">
                                                <option value="">Pilih kategori</option>
                                                <?php $__currentLoopData = ($categoryOptions ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category->id); ?>"><?php echo e(ucfirst($category->type)); ?> - <?php echo e($category->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="field field-2">
                                            <label for="trx_desc">Deskripsi</label>
                                            <input id="trx_desc" type="text" name="description" maxlength="255" value="<?php echo e(old('description')); ?>" placeholder="Contoh: Gaji bulanan / Belanja mingguan">
                                        </div>
                                    </div>
                                    <div class="row-actions">
                                        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                                    </div>
                                </form>
                            </section>

                            <section class="layout">
                                <article class="card panel" style="grid-column: 1 / -1;">
                                    <h2>Daftar Transaksi</h2>
                                    <p>Pemasukan dan pengeluaran terbaru kamu (dengan aksi edit & hapus).</p>
                                    <div class="transaction-list">
                                        <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <div class="item">
                                                <div style="width:100%;">
                                                    <h3><?php echo e($trx->description ?: 'Transaksi'); ?></h3>
                                                    <p><?php echo e(\Illuminate\Support\Carbon::parse($trx->transaction_date)->format('d M Y')); ?> • XP +<?php echo e($trx->xp_earned ?? 0); ?></p>

                                                    <?php if(($editingTransactionId ?? 0) === $trx->id): ?>
                                                        <form action="<?php echo e(route('dashboard.transactions.update', $trx)); ?>" method="POST" style="margin-top:0.7rem;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PUT'); ?>
                                                            <div class="form-grid">
                                                                <div class="field">
                                                                    <label>Tipe</label>
                                                                    <select name="type" required>
                                                                        <option value="income" <?php echo e($trx->type === 'income' ? 'selected' : ''); ?>>Income</option>
                                                                        <option value="expense" <?php echo e($trx->type === 'expense' ? 'selected' : ''); ?>>Expense</option>
                                                                    </select>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Amount</label>
                                                                    <input type="number" min="0" step="0.01" name="amount" value="<?php echo e((float) $trx->amount); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Tanggal</label>
                                                                    <input type="date" name="transaction_date" value="<?php echo e(optional($trx->transaction_date)->format('Y-m-d')); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Kategori</label>
                                                                    <select name="category_id">
                                                                        <option value="">Pilih kategori</option>
                                                                        <?php $__currentLoopData = ($categoryOptions ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($category->id); ?>" <?php echo e((int) $trx->category_id === (int) $category->id ? 'selected' : ''); ?>><?php echo e(ucfirst($category->type)); ?> - <?php echo e($category->name); ?></option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                </div>
                                                                <div class="field field-2">
                                                                    <label>Deskripsi</label>
                                                                    <input type="text" name="description" maxlength="255" value="<?php echo e($trx->description); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="row-actions">
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                                <a href="<?php echo e(route('dashboard.transactions')); ?>" class="btn btn-soft" style="text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
                                                            </div>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                                <div style="text-align:right; min-width: 165px;">
                                                    <span class="badge <?php echo e(($trx->type ?? 'expense') === 'income' ? 'badge-income' : 'badge-expense'); ?>"><?php echo e(strtoupper($trx->type ?? 'expense')); ?></span>
                                                    <div style="margin-top:0.25rem;font-weight:700;color:#1f3b63;">Rp <?php echo e(number_format((float) ($trx->amount ?? 0), 0, ',', '.')); ?></div>
                                                    <div class="row-actions" style="justify-content:flex-end;">
                                                        <a href="<?php echo e(route('dashboard.transactions', ['edit' => $trx->id])); ?>" class="btn btn-soft" style="text-decoration:none;display:inline-flex;align-items:center;">Edit</a>
                                                        <form action="<?php echo e(route('dashboard.transactions.destroy', $trx)); ?>" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="placeholder">Belum ada data transaksi untuk ditampilkan.</div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            </section>
                        <?php elseif($activeFeature === 'budgets'): ?>
                            <section class="hero card">
                                <div>
                                    <h1>Fitur Budget</h1>
                                    <p>Kontrol pengeluaran dengan membandingkan income, expense, dan saldo berjalan.</p>
                                </div>
                                <div class="hero-pill">
                                    <strong>Rp <?php echo e(number_format($balance, 0, ',', '.')); ?></strong>
                                    <span>Sisa saldo saat ini</span>
                                </div>
                            </section>

                            <section class="stats">
                                <article class="card stat">
                                    <small>Income</small>
                                    <strong>Rp <?php echo e(number_format($totalIncome, 0, ',', '.')); ?></strong>
                                </article>
                                <article class="card stat">
                                    <small>Expense</small>
                                    <strong>Rp <?php echo e(number_format($totalExpense, 0, ',', '.')); ?></strong>
                                </article>
                                <article class="card stat">
                                    <small>Selisih</small>
                                    <strong>Rp <?php echo e(number_format($balance, 0, ',', '.')); ?></strong>
                                </article>
                                <article class="card stat">
                                    <small>Efisiensi</small>
                                    <strong><?php echo e($totalIncome > 0 ? number_format((($balance / $totalIncome) * 100), 1, ',', '.') : '0,0'); ?>%</strong>
                                </article>
                            </section>

                            <section class="card transaction-create">
                                <h2>Tambah Budget</h2>
                                <p style="margin-top:0.2rem;color:var(--muted);font-size:0.84rem;">Tetapkan batas pengeluaran untuk kategori tertentu.</p>
                                <form action="<?php echo e(route('dashboard.budgets.store')); ?>" method="POST" style="margin-top:0.75rem;">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-grid">
                                        <div class="field">
                                            <label for="bgt_category">Kategori</label>
                                            <select id="bgt_category" name="category" required>
                                                <option value="">Pilih kategori</option>
                                                <option value="food">Food & Grocery</option>
                                                <option value="transport">Transport</option>
                                                <option value="utilities">Utilities</option>
                                                <option value="entertainment">Entertainment</option>
                                                <option value="health">Health</option>
                                                <option value="education">Education</option>
                                                <option value="shopping">Shopping</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label for="bgt_limit">Batas (Rp)</label>
                                            <input id="bgt_limit" type="number" min="0" step="1000" name="limit_amount" value="<?php echo e(old('limit_amount')); ?>" required>
                                        </div>
                                        <div class="field">
                                            <label for="bgt_period">Periode</label>
                                            <select id="bgt_period" name="period" required>
                                                <option value="daily">Harian</option>
                                                <option value="weekly">Mingguan</option>
                                                <option value="monthly" selected>Bulanan</option>
                                                <option value="yearly">Tahunan</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label for="bgt_start">Tanggal Mulai</label>
                                            <input id="bgt_start" type="date" name="period_start" value="<?php echo e(old('period_start', now()->toDateString())); ?>" required>
                                        </div>
                                        <div class="field">
                                            <label for="bgt_end">Tanggal Akhir</label>
                                            <input id="bgt_end" type="date" name="period_end" value="<?php echo e(old('period_end', now()->addMonth()->toDateString())); ?>" required>
                                        </div>
                                        <div class="field">
                                            <label for="bgt_status">Status</label>
                                            <select id="bgt_status" name="is_active">
                                                <option value="1" selected>Aktif</option>
                                                <option value="0">Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row-actions">
                                        <button type="submit" class="btn btn-primary">Simpan Budget</button>
                                    </div>
                                </form>
                            </section>

                            <section class="layout">
                                <article class="card panel" style="grid-column: 1 / -1;">
                                    <h2>Daftar Budget</h2>
                                    <p>Budget yang sedang berjalan dan riwayat pengeluaran.</p>
                                    <div class="transaction-list">
                                        <?php $__empty_1 = true; $__currentLoopData = $budgets ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <div class="item">
                                                <div style="width:100%;">
                                                    <h3><?php echo e(ucfirst($budget->category)); ?> - Rp <?php echo e(number_format($budget->limit_amount, 0, ',', '.')); ?></h3>
                                                    <p>Periode: <?php echo e(\Illuminate\Support\Carbon::parse($budget->period_start)->format('d M')); ?> - <?php echo e(\Illuminate\Support\Carbon::parse($budget->period_end)->format('d M Y')); ?> • <?php echo e(ucfirst($budget->period)); ?></p>
                                                    <p style="margin-top:0.3rem;font-size:0.82rem;">Terpakai: Rp <?php echo e(number_format($budget->spent_amount ?? 0, 0, ',', '.')); ?> (<?php echo e($budget->percentageUsed() ?? 0); ?>%)</p>

                                                    <?php if(($editingBudgetId ?? 0) === $budget->id): ?>
                                                        <form action="<?php echo e(route('dashboard.budgets.update', $budget)); ?>" method="POST" style="margin-top:0.7rem;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PUT'); ?>
                                                            <div class="form-grid">
                                                                <div class="field">
                                                                    <label>Kategori</label>
                                                                    <select name="category" required>
                                                                        <option value="food" <?php echo e($budget->category === 'food' ? 'selected' : ''); ?>>Food & Grocery</option>
                                                                        <option value="transport" <?php echo e($budget->category === 'transport' ? 'selected' : ''); ?>>Transport</option>
                                                                        <option value="utilities" <?php echo e($budget->category === 'utilities' ? 'selected' : ''); ?>>Utilities</option>
                                                                        <option value="entertainment" <?php echo e($budget->category === 'entertainment' ? 'selected' : ''); ?>>Entertainment</option>
                                                                        <option value="health" <?php echo e($budget->category === 'health' ? 'selected' : ''); ?>>Health</option>
                                                                        <option value="education" <?php echo e($budget->category === 'education' ? 'selected' : ''); ?>>Education</option>
                                                                        <option value="shopping" <?php echo e($budget->category === 'shopping' ? 'selected' : ''); ?>>Shopping</option>
                                                                        <option value="other" <?php echo e($budget->category === 'other' ? 'selected' : ''); ?>>Other</option>
                                                                    </select>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Batas (Rp)</label>
                                                                    <input type="number" min="0" step="1000" name="limit_amount" value="<?php echo e((int) $budget->limit_amount); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Periode</label>
                                                                    <select name="period" required>
                                                                        <option value="daily" <?php echo e($budget->period === 'daily' ? 'selected' : ''); ?>>Harian</option>
                                                                        <option value="weekly" <?php echo e($budget->period === 'weekly' ? 'selected' : ''); ?>>Mingguan</option>
                                                                        <option value="monthly" <?php echo e($budget->period === 'monthly' ? 'selected' : ''); ?>>Bulanan</option>
                                                                        <option value="yearly" <?php echo e($budget->period === 'yearly' ? 'selected' : ''); ?>>Tahunan</option>
                                                                    </select>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Tanggal Mulai</label>
                                                                    <input type="date" name="period_start" value="<?php echo e(optional($budget->period_start)->format('Y-m-d')); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Tanggal Akhir</label>
                                                                    <input type="date" name="period_end" value="<?php echo e(optional($budget->period_end)->format('Y-m-d')); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Status</label>
                                                                    <select name="is_active">
                                                                        <option value="1" <?php echo e($budget->is_active ? 'selected' : ''); ?>>Aktif</option>
                                                                        <option value="0" <?php echo e(!$budget->is_active ? 'selected' : ''); ?>>Nonaktif</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row-actions">
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                                <a href="<?php echo e(route('dashboard.budgets')); ?>" class="btn btn-soft" style="text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
                                                            </div>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                                <div style="text-align:right; min-width: 165px;">
                                                    <span class="badge" style="background: <?php echo e($budget->percentageUsed() >= 90 ? '#fee2e2' : '#dcfce7'); ?>; color: <?php echo e($budget->percentageUsed() >= 90 ? '#991b1b' : '#166534'); ?>;"><?php echo e($budget->percentageUsed() ?? 0); ?>%</span>
                                                    <div style="margin-top:0.25rem;font-weight:700;color:#1f3b63;"><?php echo e($budget->is_active ? 'Aktif' : 'Nonaktif'); ?></div>
                                                    <div class="row-actions" style="justify-content:flex-end;">
                                                        <a href="<?php echo e(route('dashboard.budgets', ['edit' => $budget->id])); ?>" class="btn btn-soft" style="text-decoration:none;display:inline-flex;align-items:center;">Edit</a>
                                                        <form action="<?php echo e(route('dashboard.budgets.destroy', $budget)); ?>" method="POST" onsubmit="return confirm('Hapus budget ini?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="placeholder">Belum ada data budget. Mulai dengan membuat budget pertama untuk kontrol pengeluaran.</div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            </section>
                        <?php elseif($activeFeature === 'goals'): ?>
                            <section class="hero card">
                                <div>
                                    <h1>Fitur Goals</h1>
                                    <p>Targetkan progres finansial dan pantau pencapaian dari dashboard.</p>
                                </div>
                                <div class="hero-pill">
                                    <strong><?php echo e(number_format($totalXp, 0, ',', '.')); ?> XP</strong>
                                    <span>Modal progress gamification</span>
                                </div>
                            </section>

                            <section class="card transaction-create">
                                <h2>Tambah Goal</h2>
                                <p style="margin-top:0.2rem;color:var(--muted);font-size:0.84rem;">Tetapkan target finansial jangka menengah atau panjang.</p>
                                <form action="<?php echo e(route('dashboard.goals.store')); ?>" method="POST" style="margin-top:0.75rem;">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-grid">
                                        <div class="field field-2">
                                            <label for="goal_name">Nama Goal</label>
                                            <input id="goal_name" type="text" name="name" maxlength="100" value="<?php echo e(old('name')); ?>" placeholder="Contoh: Liburan ke Bali" required>
                                        </div>
                                        <div class="field">
                                            <label for="goal_target">Target (Rp)</label>
                                            <input id="goal_target" type="number" min="0" step="1000" name="target_amount" value="<?php echo e(old('target_amount')); ?>" required>
                                        </div>
                                        <div class="field">
                                            <label for="goal_date">Target Tanggal</label>
                                            <input id="goal_date" type="date" name="target_date" value="<?php echo e(old('target_date')); ?>" required>
                                        </div>
                                        <div class="field field-2">
                                            <label for="goal_desc">Deskripsi</label>
                                            <input id="goal_desc" type="text" name="description" maxlength="255" value="<?php echo e(old('description')); ?>" placeholder="Deskripsi singkat goal">
                                        </div>
                                    </div>
                                    <div class="row-actions">
                                        <button type="submit" class="btn btn-primary">Simpan Goal</button>
                                    </div>
                                </form>
                            </section>

                            <section class="layout">
                                <article class="card panel" style="grid-column: 1 / -1;">
                                    <h2>Daftar Goals</h2>
                                    <p>Target finansial yang sedang kamu kejar.</p>
                                    <div class="transaction-list">
                                        <?php $__empty_1 = true; $__currentLoopData = $goals ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <div class="item">
                                                <div style="width:100%;">
                                                    <h3><?php echo e($goal->name); ?></h3>
                                                    <p>Target: Rp <?php echo e(number_format($goal->target_amount, 0, ',', '.')); ?> • Saat ini: Rp <?php echo e(number_format($goal->current_amount ?? 0, 0, ',', '.')); ?> • Status: <?php echo e(ucfirst($goal->status)); ?></p>
                                                    <p style="margin-top:0.3rem;font-size:0.82rem;">Progress: <?php echo e($goal->progressPercentage() ?? 0); ?>% • Sisa <?php echo e($goal->daysRemaining() ?? 0); ?> hari</p>

                                                    <?php if(($editingGoalId ?? 0) === $goal->id): ?>
                                                        <form action="<?php echo e(route('dashboard.goals.update', $goal)); ?>" method="POST" style="margin-top:0.7rem;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PUT'); ?>
                                                            <div class="form-grid">
                                                                <div class="field field-2">
                                                                    <label>Nama</label>
                                                                    <input type="text" name="name" maxlength="100" value="<?php echo e($goal->name); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Target (Rp)</label>
                                                                    <input type="number" min="0" step="1000" name="target_amount" value="<?php echo e((int) $goal->target_amount); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Saat Ini (Rp)</label>
                                                                    <input type="number" min="0" step="1000" name="current_amount" value="<?php echo e((int) ($goal->current_amount ?? 0)); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Target Tanggal</label>
                                                                    <input type="date" name="target_date" value="<?php echo e(optional($goal->target_date)->format('Y-m-d')); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Status</label>
                                                                    <select name="status" required>
                                                                        <option value="active" <?php echo e($goal->status === 'active' ? 'selected' : ''); ?>>Active</option>
                                                                        <option value="completed" <?php echo e($goal->status === 'completed' ? 'selected' : ''); ?>>Completed</option>
                                                                        <option value="cancelled" <?php echo e($goal->status === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                                                    </select>
                                                                </div>
                                                                <div class="field field-2">
                                                                    <label>Deskripsi</label>
                                                                    <input type="text" name="description" maxlength="255" value="<?php echo e($goal->description); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="row-actions">
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                                <a href="<?php echo e(route('dashboard.goals')); ?>" class="btn btn-soft" style="text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
                                                            </div>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                                <div style="text-align:right; min-width: 165px;">
                                                    <span class="badge" style="background: <?php echo e($goal->status === 'completed' ? '#dcfce7' : ($goal->status === 'cancelled' ? '#fee2e2' : '#dbeafe')); ?>; color: <?php echo e($goal->status === 'completed' ? '#166534' : ($goal->status === 'cancelled' ? '#991b1b' : '#1e3a8a')); ?>;"><?php echo e(ucfirst($goal->status)); ?></span>
                                                    <div style="margin-top:0.25rem;font-weight:700;color:#1f3b63;"><?php echo e($goal->progressPercentage() ?? 0); ?>%</div>
                                                    <div class="row-actions" style="justify-content:flex-end;">
                                                        <a href="<?php echo e(route('dashboard.goals', ['edit' => $goal->id])); ?>" class="btn btn-soft" style="text-decoration:none;display:inline-flex;align-items:center;">Edit</a>
                                                        <form action="<?php echo e(route('dashboard.goals.destroy', $goal)); ?>" method="POST" onsubmit="return confirm('Hapus goal ini?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="placeholder">Belum ada goal. Buat goal pertama untuk mulai merencanakan target finansial kamu.</div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            </section>
                        <?php elseif($activeFeature === 'challenges'): ?>
                            <section class="hero card">
                                <div>
                                    <h1>Fitur Challenges</h1>
                                    <p>Challenge membantu membangun kebiasaan finansial sehat secara bertahap.</p>
                                </div>
                                <div class="hero-pill">
                                    <strong>Level <?php echo e($currentLevel); ?></strong>
                                    <span>Status tantangan user</span>
                                </div>
                            </section>

                            <section class="card transaction-create">
                                <h2>Tambah Challenge</h2>
                                <p style="margin-top:0.2rem;color:var(--muted);font-size:0.84rem;">Buat challenge baru untuk melatih disiplin finansial.</p>
                                <form action="<?php echo e(route('dashboard.challenges.store')); ?>" method="POST" style="margin-top:0.75rem;">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-grid">
                                        <div class="field field-2">
                                            <label for="chal_name">Nama Challenge</label>
                                            <input id="chal_name" type="text" name="name" maxlength="100" value="<?php echo e(old('name')); ?>" placeholder="Contoh: 30 Days No Spending" required>
                                        </div>
                                        <div class="field">
                                            <label for="chal_difficulty">Difficulty</label>
                                            <select id="chal_difficulty" name="difficulty" required>
                                                <option value="easy">Easy</option>
                                                <option value="medium" selected>Medium</option>
                                                <option value="hard">Hard</option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label for="chal_xp">XP Reward</label>
                                            <input id="chal_xp" type="number" min="10" step="10" name="reward_xp" value="<?php echo e(old('reward_xp', 100)); ?>" required>
                                        </div>
                                        <div class="field">
                                            <label for="chal_start">Tanggal Mulai</label>
                                            <input id="chal_start" type="date" name="start_date" value="<?php echo e(old('start_date', now()->toDateString())); ?>" required>
                                        </div>
                                        <div class="field">
                                            <label for="chal_end">Tanggal Akhir</label>
                                            <input id="chal_end" type="date" name="end_date" value="<?php echo e(old('end_date', now()->addDays(30)->toDateString())); ?>" required>
                                        </div>
                                        <div class="field field-2">
                                            <label for="chal_desc">Deskripsi</label>
                                            <input id="chal_desc" type="text" name="description" maxlength="255" value="<?php echo e(old('description')); ?>" placeholder="Deskripsi challenge & kriteria">
                                        </div>
                                    </div>
                                    <div class="row-actions">
                                        <button type="submit" class="btn btn-primary">Simpan Challenge</button>
                                    </div>
                                </form>
                            </section>

                            <section class="layout">
                                <article class="card panel" style="grid-column: 1 / -1;">
                                    <h2>Daftar Challenge</h2>
                                    <p>Challenge yang sedang berjalan atau sudah diselesaikan.</p>
                                    <div class="transaction-list">
                                        <?php $__empty_1 = true; $__currentLoopData = $challenges ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $challenge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <div class="item">
                                                <div style="width:100%;">
                                                    <h3><?php echo e($challenge->name); ?> <span style="font-size:0.85rem;color:var(--muted);font-weight:500;"><?php echo e(ucfirst($challenge->difficulty)); ?></span></h3>
                                                    <p>Periode: <?php echo e(\Illuminate\Support\Carbon::parse($challenge->start_date)->format('d M')); ?> - <?php echo e(\Illuminate\Support\Carbon::parse($challenge->end_date)->format('d M Y')); ?> • Reward: +<?php echo e($challenge->reward_xp); ?> XP</p>
                                                    <p style="margin-top:0.3rem;font-size:0.82rem;">Status: <?php echo e(ucfirst($challenge->status)); ?> • Sisa <?php echo e($challenge->daysRemaining() ?? 0); ?> hari</p>

                                                    <?php if(($editingChallengeId ?? 0) === $challenge->id): ?>
                                                        <form action="<?php echo e(route('dashboard.challenges.update', $challenge)); ?>" method="POST" style="margin-top:0.7rem;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PUT'); ?>
                                                            <div class="form-grid">
                                                                <div class="field field-2">
                                                                    <label>Nama</label>
                                                                    <input type="text" name="name" maxlength="100" value="<?php echo e($challenge->name); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Difficulty</label>
                                                                    <select name="difficulty" required>
                                                                        <option value="easy" <?php echo e($challenge->difficulty === 'easy' ? 'selected' : ''); ?>>Easy</option>
                                                                        <option value="medium" <?php echo e($challenge->difficulty === 'medium' ? 'selected' : ''); ?>>Medium</option>
                                                                        <option value="hard" <?php echo e($challenge->difficulty === 'hard' ? 'selected' : ''); ?>>Hard</option>
                                                                    </select>
                                                                </div>
                                                                <div class="field">
                                                                    <label>XP Reward</label>
                                                                    <input type="number" min="10" step="10" name="reward_xp" value="<?php echo e((int) $challenge->reward_xp); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Tanggal Mulai</label>
                                                                    <input type="date" name="start_date" value="<?php echo e(optional($challenge->start_date)->format('Y-m-d')); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Tanggal Akhir</label>
                                                                    <input type="date" name="end_date" value="<?php echo e(optional($challenge->end_date)->format('Y-m-d')); ?>" required>
                                                                </div>
                                                                <div class="field">
                                                                    <label>Status</label>
                                                                    <select name="status" required>
                                                                        <option value="active" <?php echo e($challenge->status === 'active' ? 'selected' : ''); ?>>Active</option>
                                                                        <option value="completed" <?php echo e($challenge->status === 'completed' ? 'selected' : ''); ?>>Completed</option>
                                                                        <option value="failed" <?php echo e($challenge->status === 'failed' ? 'selected' : ''); ?>>Failed</option>
                                                                    </select>
                                                                </div>
                                                                <div class="field field-2">
                                                                    <label>Deskripsi</label>
                                                                    <input type="text" name="description" maxlength="255" value="<?php echo e($challenge->description); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="row-actions">
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                                <a href="<?php echo e(route('dashboard.challenges')); ?>" class="btn btn-soft" style="text-decoration:none;display:inline-flex;align-items:center;">Batal</a>
                                                            </div>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                                <div style="text-align:right; min-width: 165px;">
                                                    <span class="badge" style="background: <?php echo e($challenge->difficulty === 'easy' ? '#dcfce7' : ($challenge->difficulty === 'hard' ? '#fee2e2' : '#dbeafe')); ?>; color: <?php echo e($challenge->difficulty === 'easy' ? '#166534' : ($challenge->difficulty === 'hard' ? '#991b1b' : '#1e3a8a')); ?>;"><?php echo e(ucfirst($challenge->difficulty)); ?></span>
                                                    <div style="margin-top:0.25rem;font-weight:700;color:#1f3b63;">+<?php echo e($challenge->reward_xp); ?> XP</div>
                                                    <div class="row-actions" style="justify-content:flex-end;">
                                                        <a href="<?php echo e(route('dashboard.challenges', ['edit' => $challenge->id])); ?>" class="btn btn-soft" style="text-decoration:none;display:inline-flex;align-items:center;">Edit</a>
                                                        <form action="<?php echo e(route('dashboard.challenges.destroy', $challenge)); ?>" method="POST" onsubmit="return confirm('Hapus challenge ini?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="placeholder">Belum ada challenge. Mulai dengan membuat challenge pertama untuk latih disiplin finansial.</div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            </section>
                        <?php elseif($activeFeature === 'badges'): ?>
                            <section class="hero card">
                                <div>
                                    <h1>Fitur Badges</h1>
                                    <p>Badge diberikan saat milestone finansial atau challenge tercapai, dan progres menuju badge berikutnya bisa kamu pantau di sini.</p>
                                </div>
                                <div class="hero-pill">
                                    <strong><?php echo e(number_format($totalXp, 0, ',', '.')); ?> XP</strong>
                                    <span>Progress menuju badge berikutnya</span>
                                </div>
                            </section>

                            <section class="layout">
                                <article class="card panel" style="grid-column: 1 / -1;">
                                    <h2>Badge yang Sudah Diraih</h2>
                                    <p>Badges diperoleh dari milestone dan challenge yang berhasil diselesaikan.</p>
                                    <div class="feature-grid">
                                        <?php $__empty_1 = true; $__currentLoopData = $userBadges ?? collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <article class="card feature-item" style="border: 2px solid #fbbf24; position:relative;">
                                                <span style="position:absolute;top:0.5rem;right:0.5rem;font-size:1.5rem;">⭐</span>
                                                <h3><?php echo e($ub->badge->name ?? 'Badge'); ?></h3>
                                                <p><?php echo e($ub->badge->description ?? 'Earned badge'); ?></p>
                                                <p style="font-size:0.75rem;margin-top:0.5rem;color:var(--muted);"><?php echo e($ub->earned_at ? \Illuminate\Support\Carbon::parse($ub->earned_at)->format('d M Y') : 'Acquired'); ?></p>
                                            </article>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="placeholder" style="grid-column: 1 / -1;">Belum ada badge yang diraih. Mulai dari transaction pertama atau challenge untuk unlock badge!</div>
                                        <?php endif; ?>
                                    </div>
                                </article>

                                <article class="card panel" style="grid-column: 1 / -1;">
                                    <h2>Badge yang Bisa Kamu Dapatkan</h2>
                                    <p>Setiap badge menampilkan syarat dan progress saat ini supaya kamu tahu langkah berikutnya.</p>
                                    <div class="feature-grid">
                                        <?php $__empty_1 = true; $__currentLoopData = ($badgeCatalog ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badgeTarget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <article class="card feature-item" style="border: 1px solid <?php echo e($badgeTarget['is_earned'] ? '#86efac' : '#bfdbfe'); ?>;">
                                                <h3><?php echo e($badgeTarget['icon'] ? $badgeTarget['icon'] . ' ' : ''); ?><?php echo e($badgeTarget['name']); ?></h3>
                                                <p><?php echo e($badgeTarget['description']); ?></p>
                                                <div class="badge-help">
                                                    <strong style="color:#1f3b63;">Cara dapat:</strong> <?php echo e($badgeTarget['how_to_get']); ?>

                                                </div>
                                                <div class="badge-help" style="margin-top:0.25rem;">
                                                    Progress: <?php echo e(number_format((int) ($badgeTarget['progress_percent'] ?? 0), 0, ',', '.')); ?>%
                                                    <?php if($badgeTarget['is_earned']): ?>
                                                        <span style="color:#166534;font-weight:700;"> • Sudah didapat</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="progress-line">
                                                    <span style="width: <?php echo e((int) ($badgeTarget['progress_percent'] ?? 0)); ?>%;"></span>
                                                </div>
                                            </article>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="placeholder" style="grid-column: 1 / -1;">Data badge belum tersedia.</div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            </section>
                        <?php elseif($activeFeature === 'leaderboard'): ?>
                            <section class="hero card">
                                <div>
                                    <h1>Fitur Leaderboard</h1>
                                    <p>Ranking pengguna paling aktif dan paling rajin berdasarkan aktivitas finansial, streak, progres goal, challenge, dan XP.</p>
                                </div>
                                <div class="hero-pill">
                                    <strong><?php echo e(($leaderboardRows ?? collect())->count()); ?></strong>
                                    <span>Total user dalam ranking</span>
                                </div>
                            </section>

                            <section class="layout">
                                <article class="card panel" style="grid-column: 1 / -1;">
                                    <h2>Ranking Aktivitas FinCo</h2>
                                    <p>Skor dihitung dari transaksi, hari aktif, streak, progres goals, challenge selesai, dan total XP.</p>

                                    <div class="leaderboard-wrap" style="margin-top:0.7rem;">
                                        <table class="leaderboard-table">
                                            <thead>
                                                <tr>
                                                    <th>Rank</th>
                                                    <th>Nama User</th>
                                                    <th style="text-align:center;">Transaksi</th>
                                                    <th style="text-align:center;">Hari Aktif</th>
                                                    <th style="text-align:center;">Streak</th>
                                                    <th style="text-align:center;">Progress Goal</th>
                                                    <th style="text-align:center;">Badges</th>
                                                    <th style="text-align:center;">XP</th>
                                                    <th style="text-align:right;">Skor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__empty_1 = true; $__currentLoopData = ($leaderboardRows ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                    <tr class="<?php echo e((int) ($row['id'] ?? 0) === (int) ($leaderboardViewerId ?? 0) ? 'leaderboard-me' : ''); ?>">
                                                        <td class="leaderboard-rank">#<?php echo e($index + 1); ?></td>
                                                        <td>
                                                            <div style="font-weight:700;color:#132b4a;white-space:nowrap;"><?php echo e($row['name']); ?></div>
                                                            <div style="font-size:0.74rem;color:var(--muted);white-space:nowrap;"><?php echo e('@' . ($row['username'] ?: 'user')); ?></div>
                                                        </td>
                                                        <td style="text-align:center;"><?php echo e(number_format((int) ($row['transactions'] ?? 0), 0, ',', '.')); ?></td>
                                                        <td style="text-align:center;"><?php echo e(number_format((int) ($row['active_days'] ?? 0), 0, ',', '.')); ?></td>
                                                        <td style="text-align:center;">🔥 <?php echo e(number_format((int) ($row['streak'] ?? 0), 0, ',', '.')); ?></td>
                                                        <td style="text-align:center;"><?php echo e(number_format((int) ($row['goal_progress'] ?? 0), 0, ',', '.')); ?>%</td>
                                                        <td style="text-align:center;">
                                                            <div style="font-weight:700;color:#1f3b63;"><?php echo e(number_format((int) ($row['badges_count'] ?? 0), 0, ',', '.')); ?></div>
                                                            <?php if(!empty($row['badges_preview'])): ?>
                                                                <div class="badge-mini-list"><?php echo e(implode(' • ', $row['badges_preview'])); ?></div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td style="text-align:center;"><?php echo e(number_format((int) ($row['xp'] ?? 0), 0, ',', '.')); ?></td>
                                                        <td style="text-align:right;"><span class="score-pill"><?php echo e(number_format((int) ($row['score'] ?? 0), 0, ',', '.')); ?></span></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                    <tr>
                                                        <td colspan="9">
                                                            <div class="placeholder" style="margin:0.25rem 0;">Belum ada data aktivitas user untuk disusun ke leaderboard.</div>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </article>
                            </section>
                        <?php elseif($activeFeature === 'reports'): ?>
                            <section class="hero card">
                                <div>
                                    <h1>Fitur Laporan</h1>
                                    <p>Ringkasan performa keuangan berdasarkan data transaksi user.</p>
                                </div>
                                <div class="hero-pill">
                                    <strong>Rp <?php echo e(number_format($balance, 0, ',', '.')); ?></strong>
                                    <span>Neraca bersih saat ini</span>
                                </div>
                            </section>

                            <section class="stats">
                                <article class="card stat">
                                    <small>Total Income</small>
                                    <strong>Rp <?php echo e(number_format($totalIncome, 0, ',', '.')); ?></strong>
                                </article>
                                <article class="card stat">
                                    <small>Total Expense</small>
                                    <strong>Rp <?php echo e(number_format($totalExpense, 0, ',', '.')); ?></strong>
                                </article>
                                <article class="card stat">
                                    <small>Neraca</small>
                                    <strong>Rp <?php echo e(number_format($balance, 0, ',', '.')); ?></strong>
                                </article>
                                <article class="card stat">
                                    <small>Jumlah Trx</small>
                                    <strong><?php echo e(number_format($transactionCount, 0, ',', '.')); ?></strong>
                                </article>
                            </section>
                        <?php elseif($activeFeature === 'settings'): ?>
                            <section class="hero card">
                                <div>
                                    <h1>Fitur Pengaturan</h1>
                                    <p>Data profil dasar akun pengguna FinCo.</p>
                                </div>
                                <div class="hero-pill">
                                    <strong><?php echo e($user->username ?: '-'); ?></strong>
                                    <span>Username akun</span>
                                </div>
                            </section>

                            <section class="layout">
                                <article class="card panel" style="grid-column: 1 / -1;">
                                    <h2>Informasi Akun</h2>
                                    <p>Ringkasan data pengguna yang tersimpan saat ini.</p>
                                    <div class="feature-grid">
                                        <article class="card feature-item">
                                            <h3>Nama</h3>
                                            <p><?php echo e($user->name); ?></p>
                                        </article>
                                        <article class="card feature-item">
                                            <h3>Email</h3>
                                            <p><?php echo e($user->email); ?></p>
                                        </article>
                                        <article class="card feature-item">
                                            <h3>Role</h3>
                                            <p><?php echo e(ucfirst($user->role ?? 'user')); ?></p>
                                        </article>
                                    </div>
                                </article>
                            </section>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\finco\resources\views/dashboard.blade.php ENDPATH**/ ?>