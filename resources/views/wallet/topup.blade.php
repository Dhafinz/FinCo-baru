@extends('layouts.dashboard-panel')

@include('wallet._card_styles')

@section('content')
<section class="hero">
    <div>
        <h1>💳 Top Up Saldo</h1>
        <p>Simulasi top up langsung sukses tanpa payment gateway.</p>
        <div class="wallet-actions">
            <a class="btn btn-soft" href="{{ route('dashboard.wallet.withdraw.form') }}">Ke Withdraw</a>
            <a class="btn btn-soft" href="{{ route('dashboard.wallet.transfer.form') }}">Ke Transfer</a>
        </div>
    </div>
    <div class="balance">
        <span style="font-size:.78rem;color:#eaf2ff;">Saldo Sekarang</span>
        <strong>Rp {{ number_format((float) $wallet->balance, 0, ',', '.') }}</strong>
    </div>
</section>

<section class="section-card panel">
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

        <button type="submit" class="btn btn-primary">KONFIRMASI TOP UP</button>
        <div class="note">Top up pertama akan mendapatkan bonus XP tambahan.</div>
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
</section>
@endsection
