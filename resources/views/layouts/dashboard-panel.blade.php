<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} | FinCo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --text:#122033;
            --muted:#55708d;
            --line:#dbe8f6;
            --blue-600:#2563eb;
            --panel:#ffffff;
            --bg:#f5f9ff;
            --shadow:0 14px 30px rgba(37,99,235,.10);
        }
        * { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family:'Outfit',sans-serif;
            color:var(--text);
            background:
                radial-gradient(circle at 0% 0%, #eaf2ff 0%, transparent 30%),
                radial-gradient(circle at 100% 10%, #eaf2ff 0%, transparent 32%),
                var(--bg);
            min-height:100vh;
        }
        .app-shell { display:grid; grid-template-columns:260px minmax(0,1fr); min-height:100vh; }
        .card { background:var(--panel); border:1px solid var(--line); border-radius:14px; box-shadow:0 10px 24px rgba(30,64,175,.05); }
        .sidebar {
            position:sticky;
            top:0;
            align-self:start;
            min-height:100vh;
            border-radius:0;
            border-left:0;
            border-top:0;
            border-bottom:0;
            border-right:1px solid var(--line);
            box-shadow:none;
            padding:1.1rem .95rem;
            background:#fff;
        }
        .sidebar-brand { margin-bottom:1rem; }
        .brand { font-family:'Sora',sans-serif; font-size:1.2rem; font-weight:800; letter-spacing:.4px; }
        .brand span { color:var(--blue-600); }
        .brand-sub { margin-top:.22rem; color:var(--muted); font-size:.79rem; }
        .sidebar h2 { font-size:.95rem; margin-bottom:.28rem; }
        .sidebar p { color:var(--muted); font-size:.8rem; margin-bottom:.7rem; }
        .menu { list-style:none; display:grid; gap:.48rem; }
        .menu a {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:.6rem;
            text-decoration:none;
            color:#1f3b63;
            border:1px solid var(--line);
            border-radius:10px;
            padding:.55rem .65rem;
            background:#fff;
            font-size:.83rem;
            font-weight:700;
            transition:all .16s ease;
        }
        .menu a:hover { border-color:#93c5fd; background:#f8fbff; }
        .menu a.active { background:#eff6ff; border-color:#93c5fd; color:#1e3a8a; }
        .menu a small { color:var(--muted); font-size:.7rem; font-weight:700; }
        .content-area { padding:.75rem; }
        .topbar {
            position:sticky;
            top:0;
            z-index:35;
            background:rgba(255,255,255,.9);
            backdrop-filter:blur(10px);
            border:1px solid var(--line);
            border-radius:14px;
        }
        .topbar-inner {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:1rem;
            padding:.8rem 1rem;
        }
        .location { font-size:.8rem; color:var(--muted); font-weight:500; }
        .topbar-user { font-family:'Sora',sans-serif; font-size:.96rem; color:#1f3b63; font-weight:700; }
        .topbar-right { display:flex; align-items:center; gap:.65rem; }
        .date-chip {
            border:1px solid var(--line);
            border-radius:999px;
            padding:.38rem .72rem;
            background:#fff;
            color:#355777;
            font-size:.8rem;
            font-weight:600;
            white-space:nowrap;
        }
        .btn-logout {
            border:1px solid #fecaca;
            color:#991b1b;
            background:#fff;
            border-radius:10px;
            padding:.42rem .65rem;
            font-size:.78rem;
            font-weight:700;
            cursor:pointer;
        }
        .main { margin-top:.85rem; }
        .alert { border-radius:12px; padding:.72rem .84rem; margin-bottom:.8rem; font-size:.86rem; border:1px solid; }
        .alert-success { background:#ecfdf5; border-color:#a7f3d0; color:#065f46; }
        .alert-error { background:#fef2f2; border-color:#fecaca; color:#991b1b; }
        @media (max-width:980px) {
            .app-shell { grid-template-columns:1fr; }
            .sidebar {
                position:static;
                min-height:auto;
                border:1px solid var(--line);
                border-radius:14px;
                box-shadow:0 10px 24px rgba(30,64,175,.05);
                margin:.75rem;
            }
            .content-area { padding:0 .75rem .75rem; }
            .menu { grid-template-columns:repeat(2,minmax(0,1fr)); }
        }
        @media (max-width:560px) {
            .menu { grid-template-columns:1fr; }
            .topbar-inner { flex-direction:column; align-items:flex-start; }
            .topbar-right { width:100%; justify-content:space-between; }
        }
    </style>
    @stack('pageStyles')
</head>
<body>
<div class="app-shell">
    <aside class="card sidebar">
        <div class="sidebar-brand">
            <div class="brand">FIN<span>CO</span></div>
            <div class="brand-sub">{{ $user->name }} • User Panel</div>
        </div>
        <h2>Menu Fitur</h2>
        <p>Akses cepat semua fitur FinCo.</p>
        <ul class="menu">
            <li><a class="{{ ($menuActive ?? '') === 'overview' ? 'active' : '' }}" href="{{ route('dashboard') }}">Overview <small>aktif</small></a></li>
            <li><a class="{{ ($menuActive ?? '') === 'transactions' ? 'active' : '' }}" href="{{ route('dashboard.transactions') }}">Transaksi <small>fitur</small></a></li>
            <li><a class="{{ ($menuActive ?? '') === 'budgets' ? 'active' : '' }}" href="{{ route('dashboard.budgets') }}">Budget <small>fitur</small></a></li>
            <li><a class="{{ ($menuActive ?? '') === 'goals' ? 'active' : '' }}" href="{{ route('dashboard.goals') }}">Goals <small>fitur</small></a></li>
            <li><a class="{{ ($menuActive ?? '') === 'challenges' ? 'active' : '' }}" href="{{ route('dashboard.challenges') }}">Challenges <small>fitur</small></a></li>
            <li><a class="{{ ($menuActive ?? '') === 'badges' ? 'active' : '' }}" href="{{ route('dashboard.badges') }}">Badges <small>fitur</small></a></li>
            <li><a class="{{ ($menuActive ?? '') === 'leaderboard' ? 'active' : '' }}" href="{{ route('dashboard.leaderboard') }}">Leaderboard <small>fitur</small></a></li>
            <li><a class="{{ ($menuActive ?? '') === 'wallet' ? 'active' : '' }}" href="{{ route('dashboard.wallet') }}">Wallet <small>baru</small></a></li>
            <li><a class="{{ ($menuActive ?? '') === 'friends' ? 'active' : '' }}" href="{{ route('dashboard.friends') }}">Teman <small>baru</small></a></li>
            <li><a class="{{ ($menuActive ?? '') === 'reports' ? 'active' : '' }}" href="{{ route('dashboard.reports') }}">Laporan <small>fitur</small></a></li>
            <li><a class="{{ ($menuActive ?? '') === 'settings' ? 'active' : '' }}" href="{{ route('dashboard.settings') }}">Pengaturan <small>fitur</small></a></li>
        </ul>
    </aside>

    <div class="content-area">
        <header class="topbar">
            <div class="topbar-inner">
                <div>
                    <div class="location">Lokasi: Dashboard / {{ $title ?? 'Halaman' }}</div>
                    <div class="topbar-user">{{ $user->name }}</div>
                </div>
                <div class="topbar-right">
                    <div class="date-chip">{{ now()->translatedFormat('d F Y') }}</div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="main">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
