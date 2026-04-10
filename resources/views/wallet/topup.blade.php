<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up Wallet | FinCo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --line:#dbe8f6; --blue:#2563eb; --text:#122033; --muted:#55708d; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Outfit', sans-serif; color: var(--text); background: #f5f9ff; }
        .container { width: min(780px, 92%); margin: 1.1rem auto 2rem; }
        .nav { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.8rem; }
        .nav a { text-decoration: none; border:1px solid var(--line); border-radius:999px; padding:0.42rem 0.75rem; color:#1f3b63; font-size:0.82rem; font-weight:700; background:#fff; }
        .card { background:#fff; border:1px solid var(--line); border-radius:14px; box-shadow:0 10px 24px rgba(30,64,175,.05); }
        .hero { padding:1rem; background:linear-gradient(135deg,#1d4ed8,#3b82f6); color:#fff; }
        .hero h1 { font-family:'Sora',sans-serif; font-size:1.25rem; margin-bottom:0.2rem; }
        .hero p { color:#eaf2ff; font-size:0.86rem; }
        .body { padding:1rem; }
        .row { margin-bottom:0.8rem; }
        .label { display:block; margin-bottom:0.35rem; color:var(--muted); font-size:0.78rem; font-weight:700; }
        .chips { display:grid; grid-template-columns: repeat(4, minmax(80px, 1fr)); gap:0.5rem; }
        .chip input { display:none; }
        .chip span { display:block; border:1px solid var(--line); border-radius:10px; text-align:center; padding:0.5rem 0.2rem; font-size:0.82rem; font-weight:700; color:#1f3b63; background:#fff; cursor:pointer; }
        .chip input:checked + span { border-color: var(--blue); color: var(--blue); background:#eef4ff; }
        input[type='number'], select, textarea { width:100%; border:1px solid var(--line); border-radius:10px; padding:0.55rem 0.65rem; font-family:inherit; font-size:0.86rem; }
        .methods { display:grid; gap:0.5rem; }
        .method { border:1px solid var(--line); border-radius:10px; padding:0.55rem 0.65rem; display:flex; align-items:center; gap:0.5rem; }
        .btn { border:1px solid var(--blue); border-radius:10px; background:var(--blue); color:#fff; font-weight:800; font-size:0.84rem; padding:0.58rem 0.8rem; cursor:pointer; }
        .note { margin-top:0.7rem; font-size:0.78rem; color:var(--muted); }
        .receipt { margin-top:1rem; padding:0.9rem; border:1px solid #86efac; border-radius:12px; background:#f0fdf4; }
        .receipt h3 { color:#166534; margin-bottom:0.4rem; font-size:0.98rem; }
        .receipt p { font-size:0.84rem; margin-bottom:0.18rem; color:#14532d; }
        .alert { padding:0.7rem 0.85rem; margin-bottom:0.7rem; border-radius:12px; font-size:0.84rem; }
        .ok { border:1px solid #86efac; color:#166534; background:#f0fdf4; }
        .err { border:1px solid #fecaca; color:#991b1b; background:#fff1f2; }
        @media (max-width: 640px) { .chips { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    </style>
</head>
<body>
<div class="container">
    <nav class="nav">
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

    <section class="card">
        <div class="hero">
            <h1>💳 Top Up Saldo</h1>
            <p>Saldo sekarang: Rp {{ number_format((float) $wallet->balance, 0, ',', '.') }}</p>
        </div>
        <div class="body">
            <form action="{{ route('dashboard.wallet.topup') }}" method="POST">
                @csrf
                <div class="row">
                    <label class="label">Pilih Nominal</label>
                    <div class="chips">
                        @foreach ($presetAmounts as $preset)
                            <label class="chip">
                                <input type="radio" name="selected_amount" value="{{ $preset }}" {{ (int) old('selected_amount', 100000) === (int) $preset ? 'checked' : '' }}>
                                <span>{{ number_format($preset / 1000, 0, ',', '.') }}k</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <label class="label">Atau ketik nominal manual</label>
                    <input type="number" name="amount" min="50000" step="1000" value="{{ old('amount') }}" placeholder="Contoh: 150000">
                </div>

                <div class="row">
                    <label class="label">Metode Pembayaran</label>
                    <div class="methods">
                        <label class="method"><input type="radio" name="payment_method" value="bank_transfer" {{ old('payment_method', 'bank_transfer') === 'bank_transfer' ? 'checked' : '' }}> Bank Transfer BCA</label>
                        <label class="method"><input type="radio" name="payment_method" value="virtual_account" {{ old('payment_method') === 'virtual_account' ? 'checked' : '' }}> Virtual Account</label>
                        <label class="method"><input type="radio" name="payment_method" value="qris" {{ old('payment_method') === 'qris' ? 'checked' : '' }}> QRIS</label>
                    </div>
                </div>

                <button type="submit" class="btn">KONFIRMASI TOP UP</button>
                <div class="note">Simulasi: transaksi langsung sukses tanpa payment gateway.</div>
            </form>

            @if (!empty($receipt))
                <div class="receipt">
                    <h3>✅ Top Up Berhasil!</h3>
                    <p>Nominal: Rp {{ number_format((float) $receipt['amount'], 0, ',', '.') }}</p>
                    <p>Metode: {{ $receipt['method'] }}</p>
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
