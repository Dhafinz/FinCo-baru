<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teman | FinCo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --line:#dbe8f6; --blue:#2563eb; --text:#122033; --muted:#55708d; --green:#16a34a; --red:#dc2626; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family:'Outfit',sans-serif; color:var(--text); background:#f5f9ff; }
        .container { width:min(1120px, 92%); margin:1.1rem auto 2rem; }
        .top-nav { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:.8rem; }
        .top-nav a { text-decoration:none; border:1px solid var(--line); border-radius:999px; padding:.42rem .75rem; color:#1f3b63; font-size:.82rem; font-weight:700; background:#fff; }
        .card { background:#fff; border:1px solid var(--line); border-radius:14px; box-shadow:0 10px 24px rgba(30,64,175,.05); }
        .header {
            padding:1rem;
            background:linear-gradient(140deg, #1d4ed8, #60a5fa);
            color:#fff;
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:1rem;
        }
        .header h1 { font-family:'Sora',sans-serif; font-size:1.3rem; margin-bottom:.2rem; }
        .header p { color:#eaf2ff; font-size:.86rem; }
        .chips { display:flex; gap:.45rem; flex-wrap:wrap; }
        .chip { background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.35); border-radius:999px; padding:.3rem .62rem; font-size:.76rem; font-weight:700; }
        .search { margin-top:1rem; padding:1rem; }
        .search form { display:grid; grid-template-columns: 1fr auto; gap:.5rem; }
        input[type='text'] { border:1px solid var(--line); border-radius:10px; padding:.58rem .68rem; font-family:inherit; font-size:.86rem; }
        .btn { border-radius:10px; padding:.52rem .72rem; border:1px solid; font-size:.8rem; font-weight:700; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; }
        .btn-blue { background:#2563eb; color:#fff; border-color:#2563eb; }
        .btn-red-soft { background:#fff; color:#be123c; border-color:#fecdd3; }
        .btn-green { background:#16a34a; color:#fff; border-color:#16a34a; }
        .btn-red { background:#dc2626; color:#fff; border-color:#dc2626; }
        .layout { margin-top:1rem; display:grid; grid-template-columns: 2fr 1fr; gap:.9rem; }
        .panel { padding:1rem; }
        .panel h2 { font-size:.98rem; margin-bottom:.2rem; }
        .panel p { color:var(--muted); font-size:.82rem; margin-bottom:.7rem; }
        .list { display:grid; gap:.6rem; }
        .friend-card {
            border:1px solid var(--line);
            border-radius:12px;
            padding:.7rem;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:.7rem;
            transition:transform .16s ease, box-shadow .16s ease;
            background:#fff;
        }
        .friend-card:hover { transform:translateY(-2px); box-shadow:0 10px 20px rgba(30,64,175,.08); }
        .info { display:flex; gap:.6rem; align-items:center; }
        .avatar {
            width:42px;
            height:42px;
            border-radius:50%;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            font-weight:800;
            background:linear-gradient(135deg,#3b82f6,#1d4ed8);
        }
        .name { font-weight:700; color:#163354; font-size:.9rem; }
        .meta { font-size:.76rem; color:var(--muted); }
        .level {
            display:inline-flex;
            padding:.16rem .46rem;
            border-radius:999px;
            font-size:.7rem;
            font-weight:700;
            margin-top:.22rem;
        }
        .level-high { background:#dcfce7; color:#166534; }
        .level-mid { background:#dbeafe; color:#1e3a8a; }
        .level-low { background:#eef2ff; color:#3730a3; }
        .actions { display:flex; gap:.35rem; flex-wrap:wrap; justify-content:flex-end; }
        .empty {
            border:1px dashed #bfdbfe;
            border-radius:12px;
            background:#f8fbff;
            padding:.95rem;
            color:#3b5d85;
            font-size:.85rem;
            text-align:center;
            line-height:1.5;
        }
        .search-results { margin-top:.8rem; display:grid; gap:.5rem; }
        .alert { padding:.7rem .85rem; margin-bottom:.7rem; border-radius:12px; font-size:.84rem; }
        .ok { border:1px solid #86efac; color:#166534; background:#f0fdf4; }
        .err { border:1px solid #fecaca; color:#991b1b; background:#fff1f2; }
        @media (max-width: 980px) { .layout { grid-template-columns: 1fr; } }
        @media (max-width: 640px) {
            .header { flex-direction:column; }
            .search form { grid-template-columns: 1fr; }
            .friend-card { flex-direction:column; align-items:flex-start; }
            .actions { width:100%; justify-content:flex-start; }
        }
    </style>
</head>
<body>
<div class="container">
    <nav class="top-nav">
        <a href="{{ route('dashboard') }}">← Dashboard</a>
        <a href="{{ route('dashboard.wallet') }}">Wallet</a>
        <a href="{{ route('dashboard.wallet.transfer.form') }}">Transfer</a>
    </nav>

    @if (session('success'))
        <div class="alert ok">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert err">{{ $errors->first() }}</div>
    @endif

    <section class="card header">
        <div>
            <h1>👥 Teman</h1>
            <p>Kelola pertemanan dan transfer saldo antar pengguna FinCo.</p>
        </div>
        <div class="chips">
            <span class="chip">{{ $friends->count() }} Teman</span>
            <span class="chip">{{ $incomingRequests->count() }} Request</span>
            <span class="chip">+ Tambah Teman</span>
        </div>
    </section>

    <section class="card search">
        <form action="{{ route('dashboard.friends.search') }}" method="POST">
            @csrf
            <input type="text" name="keyword" value="{{ old('keyword') }}" placeholder="🔍 Cari nama atau email teman..." required>
            <button type="submit" class="btn btn-blue">Cari</button>
        </form>

        @if ($searchResults->isNotEmpty())
            <div class="search-results">
                @foreach ($searchResults as $result)
                    <div class="friend-card">
                        <div class="info">
                            <div class="avatar">{{ strtoupper(substr($result['name'] ?? 'U', 0, 1)) }}</div>
                            <div>
                                <div class="name">{{ $result['name'] }}</div>
                                <div class="meta">@{{ $result['username'] }} • {{ $result['email'] }}</div>
                            </div>
                        </div>
                        <div class="actions">
                            @if (in_array($result['status'], ['pending', 'accepted'], true))
                                <span class="btn" style="border-color:#dbeafe;background:#eff6ff;color:#1e3a8a;cursor:default;">{{ strtoupper($result['status']) }}</span>
                            @else
                                <form action="{{ route('dashboard.friends.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="target_user_id" value="{{ $result['id'] }}">
                                    <button type="submit" class="btn btn-blue">+ Tambah Teman</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section class="layout">
        <article class="card panel">
            <h2>👥 Daftar Teman ({{ $friends->count() }})</h2>
            <p>Teman yang sudah accepted dan bisa jadi tujuan transfer saldo.</p>

            <div class="list">
                @forelse ($friends as $friend)
                    @php
                        $levelClass = $friend['level'] >= 5 ? 'level-high' : ($friend['level'] >= 3 ? 'level-mid' : 'level-low');
                    @endphp
                    <div class="friend-card">
                        <div class="info">
                            <div class="avatar">{{ $friend['initials'] }}</div>
                            <div>
                                <div class="name">{{ $friend['name'] }}</div>
                                <div class="meta">@{{ $friend['username'] }}</div>
                                <span class="level {{ $levelClass }}">Level {{ $friend['level'] }}</span>
                                <div class="meta" style="margin-top:.18rem;">XP: {{ number_format($friend['xp'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="actions">
                            <a class="btn btn-blue" href="{{ route('dashboard.wallet.transfer.form', ['friend_id' => $friend['id']]) }}">💸 Transfer</a>
                        </div>
                    </div>
                @empty
                    <div class="empty">
                        <div style="font-size:1.2rem; margin-bottom:.2rem;">👥</div>
                        Belum ada teman.<br>
                        Cari teman dan mulai bertransaksi bersama!
                    </div>
                @endforelse
            </div>
        </article>

        <article class="card panel">
            <h2>🔔 Request Masuk ({{ $incomingRequests->count() }})</h2>
            <p>Permintaan pertemanan yang menunggu respons.</p>

            <div class="list">
                @forelse ($incomingRequests as $req)
                    <div class="friend-card" style="padding:.65rem;">
                        <div class="info">
                            <div class="avatar">{{ $req['initials'] }}</div>
                            <div>
                                <div class="name">{{ $req['name'] }}</div>
                                <div class="meta">@{{ $req['username'] }}</div>
                                <div class="meta">Level {{ $req['level'] }} • XP {{ number_format($req['xp'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="actions">
                            <form action="{{ route('dashboard.friends.accept', $req['id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-green">✅ Terima</button>
                            </form>
                            <form action="{{ route('dashboard.friends.reject', $req['id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-red">❌ Tolak</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty">Belum ada request masuk.</div>
                @endforelse
            </div>
        </article>
    </section>
</div>
</body>
</html>
