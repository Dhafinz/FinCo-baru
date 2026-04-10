<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet | FinCo</title>
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
        .container { width: min(1080px, 92%); margin: 1.1rem auto 2rem; }
        .top-nav {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 0.9rem;
        }
        .top-nav a {
            text-decoration: none;
            color: #1f3b63;
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 999px;
            padding: 0.42rem 0.78rem;
            font-size: 0.82rem;
            font-weight: 600;
        }
        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(30, 64, 175, 0.05);
        }
        .hero {
            padding: 1.1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: #fff;
        }
        .hero h1 { font-family: 'Sora', sans-serif; font-size: 1.35rem; margin-bottom: 0.2rem; }
        .hero p { color: #eaf2ff; font-size: 0.88rem; }
        .balance {
            text-align: right;
            background: rgba(255,255,255,0.16);
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 12px;
            padding: 0.7rem 0.9rem;
            min-width: 180px;
        }
        .balance strong { display: block; font-family: 'Sora', sans-serif; font-size: 1.25rem; }
        .wallet-actions {
            margin-top: 0.85rem;
            display: flex;
            gap: 0.55rem;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0.5rem 0.78rem;
            font-size: 0.83rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-primary { background: var(--blue-600); color: #fff; border-color: var(--blue-600); }
        .btn-soft { background: #eef4ff; color: #224e8d; }
        .panel { margin-top: 1rem; padding: 1rem; }
        .panel h2 { font-size: 1rem; margin-bottom: 0.2rem; }
        .panel p { color: var(--muted); font-size: 0.84rem; margin-bottom: 0.7rem; }
        .list { display: grid; gap: 0.55rem; }
        .item {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0.6rem 0.7rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.8rem;
            background: #fff;
        }
        .item h3 { font-size: 0.9rem; margin-bottom: 0.15rem; }
        .item p { margin: 0; font-size: 0.76rem; color: var(--muted); }
        .amount-plus { color: #166534; font-weight: 700; }
        .amount-minus { color: #991b1b; font-weight: 700; }
        .tag {
            border-radius: 999px;
            padding: 0.2rem 0.56rem;
            font-size: 0.72rem;
            font-weight: 700;
            background: #dbeafe;
            color: #1e3a8a;
        }
        .empty {
            border: 1px dashed #bfdbfe;
            border-radius: 12px;
            background: #f8fbff;
            padding: 0.9rem;
            color: #3b5d85;
            font-size: 0.86rem;
            line-height: 1.5;
        }
        @media (max-width: 760px) {
            .hero { flex-direction: column; align-items: flex-start; }
            .balance { text-align: left; width: 100%; }
            .item { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="top-nav">
            <a href="{{ route('dashboard') }}">← Dashboard</a>
            <a href="{{ route('dashboard.wallet') }}">Wallet</a>
            <a href="{{ route('dashboard.wallet.topup.form') }}">Top Up</a>
            <a href="{{ route('dashboard.wallet.transfer.form') }}">Transfer</a>
            <a href="{{ route('dashboard.friends') }}">Teman</a>
        </nav>

        @if (session('success'))
            <div class="card" style="padding:0.7rem 0.85rem;margin-bottom:0.75rem;border-color:#86efac;color:#166534;background:#f0fdf4;">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="card" style="padding:0.7rem 0.85rem;margin-bottom:0.75rem;border-color:#fecaca;color:#991b1b;background:#fff1f2;">{{ $errors->first() }}</div>
        @endif

        <section class="card hero">
            <div>
                <h1>💰 Wallet Saya</h1>
                <p>Kelola saldo dompet digital kamu untuk top up, bayar, dan transfer ke teman.</p>
                <div class="wallet-actions">
                    <a class="btn btn-primary" href="{{ route('dashboard.wallet.topup.form') }}">TOP UP</a>
                    <a class="btn btn-soft" href="{{ route('dashboard.wallet.transfer.form') }}">TRANSFER</a>
                </div>
            </div>
            <div class="balance">
                <span style="font-size:0.78rem;color:#eaf2ff;">Saldo Saat Ini</span>
                <strong>Rp {{ number_format((float) $wallet->balance, 0, ',', '.') }}</strong>
            </div>
        </section>

        <section class="card panel">
            <h2>Riwayat Aktivitas Wallet</h2>
            <p>Semua aktivitas wallet seperti top up, pembayaran, dan transfer dicatat di sini.</p>
            <div class="list">
                @forelse ($activities as $activity)
                    @php
                        $isIncoming = in_array($activity->type, ['top_up', 'transfer_in'], true);
                        $icon = match ($activity->type) {
                            'top_up' => '✅',
                            'payment' => '🔴',
                            'transfer_in' => '🟢',
                            'transfer_out' => '🔵',
                            default => '•',
                        };
                    @endphp
                    <div class="item">
                        <div>
                            <h3>{{ $icon }} {{ ucfirst(str_replace('_', ' ', $activity->type)) }}</h3>
                            <p>{{ $activity->description ?: 'Aktivitas wallet' }} • {{ $activity->created_at?->format('d M Y H:i') }}</p>
                        </div>
                        <div style="text-align:right;">
                            <span class="tag">{{ strtoupper($activity->status) }}</span>
                            <div class="{{ $isIncoming ? 'amount-plus' : 'amount-minus' }}" style="margin-top:0.25rem;">
                                {{ $isIncoming ? '+' : '-' }}Rp {{ number_format((float) $activity->amount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty">Belum ada aktivitas wallet. Mulai dengan top up pertama kamu.</div>
                @endforelse
            </div>
        </section>
    </div>
</body>
</html>
