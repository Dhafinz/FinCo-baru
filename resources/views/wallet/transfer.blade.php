<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Wallet | FinCo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --line:#dbe8f6; --blue:#2563eb; --text:#122033; --muted:#55708d; }
        * { box-sizing:border-box; margin:0; padding:0; }
        body { font-family:'Outfit',sans-serif; color:var(--text); background:#f5f9ff; }
        .container { width:min(780px, 92%); margin:1.1rem auto 2rem; }
        .nav { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:.8rem; }
        .nav a { text-decoration:none; border:1px solid var(--line); border-radius:999px; padding:.42rem .75rem; color:#1f3b63; font-size:.82rem; font-weight:700; background:#fff; }
        .card { background:#fff; border:1px solid var(--line); border-radius:14px; box-shadow:0 10px 24px rgba(30,64,175,.05); }
        .hero { padding:1rem; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:#fff; }
        .hero h1 { font-family:'Sora',sans-serif; font-size:1.25rem; margin-bottom:.2rem; }
        .hero p { color:#eaf2ff; font-size:.86rem; }
        .body { padding:1rem; }
        .row { margin-bottom:.8rem; }
        .label { display:block; margin-bottom:.35rem; color:var(--muted); font-size:.78rem; font-weight:700; }
        select,input,textarea { width:100%; border:1px solid var(--line); border-radius:10px; padding:.55rem .65rem; font-family:inherit; font-size:.86rem; }
        .btn { border:1px solid var(--blue); border-radius:10px; background:var(--blue); color:#fff; font-weight:800; font-size:.84rem; padding:.58rem .8rem; cursor:pointer; }
        .alert { padding:.7rem .85rem; margin-bottom:.7rem; border-radius:12px; font-size:.84rem; }
        .ok { border:1px solid #86efac; color:#166534; background:#f0fdf4; }
        .err { border:1px solid #fecaca; color:#991b1b; background:#fff1f2; }
        .receipt { margin-top:1rem; padding:.9rem; border:1px solid #86efac; border-radius:12px; background:#f0fdf4; }
        .receipt h3 { color:#166534; margin-bottom:.4rem; font-size:.98rem; }
        .receipt p { font-size:.84rem; margin-bottom:.18rem; color:#14532d; }
    </style>
</head>
<body>
<div class="container">
    <nav class="nav">
        <a href="{{ route('dashboard') }}">← Dashboard</a>
        <a href="{{ route('dashboard.wallet') }}">Wallet</a>
        <a href="{{ route('dashboard.friends') }}">Teman</a>
    </nav>

    @if (session('success'))
        <div class="alert ok">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert err">{{ $errors->first() }}</div>
    @endif

    <section class="card">
        <div class="hero">
            <h1>💸 Transfer ke Teman</h1>
            <p>Saldo kamu: Rp {{ number_format((float) $wallet->balance, 0, ',', '.') }}</p>
        </div>
        <div class="body">
            <form action="{{ route('dashboard.wallet.transfer') }}" method="POST">
                @csrf
                <div class="row">
                    <label class="label">Pilih Teman</label>
                    <select name="friend_id" required>
                        <option value="">Pilih dari daftar teman</option>
                        @foreach ($friends as $friend)
                            <option value="{{ $friend['id'] }}" {{ (int) old('friend_id', $preselectedFriendId) === (int) $friend['id'] ? 'selected' : '' }}>
                                {{ $friend['name'] }} (@{{ $friend['username'] }}) • Level {{ $friend['level'] }} • XP {{ number_format($friend['xp'], 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <label class="label">Nominal</label>
                    <input type="number" name="amount" min="10000" step="1000" value="{{ old('amount') }}" placeholder="Rp 10.000 minimal" required>
                </div>

                <div class="row">
                    <label class="label">Catatan</label>
                    <textarea name="note" rows="3" placeholder="Catatan transfer (opsional)">{{ old('note') }}</textarea>
                </div>

                <button type="submit" class="btn">TRANSFER SEKARANG</button>
            </form>

            @if (!empty($receipt))
                <div class="receipt">
                    <h3>✅ Transfer Berhasil!</h3>
                    <p>Nominal: Rp {{ number_format((float) $receipt['amount'], 0, ',', '.') }}</p>
                    <p>Saldo: Rp {{ number_format((float) $receipt['before'], 0, ',', '.') }} → Rp {{ number_format((float) $receipt['after'], 0, ',', '.') }}</p>
                    <p>Ref: {{ $receipt['reference'] }}</p>
                    <p>XP: +{{ $receipt['xp'] }} 🎮</p>
                </div>
            @endif
        </div>
    </section>
</div>
</body>
</html>
