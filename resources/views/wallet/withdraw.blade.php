@extends('layouts.dashboard-panel')

@include('wallet._card_styles')

@section('content')
<section class="hero">
    <div>
        <h1>🏦 Withdraw Saldo</h1>
        <p>Tarik saldo wallet ke rekening bank tujuan (simulasi sukses instan).</p>
        <div class="wallet-actions">
            <a class="btn btn-soft" href="{{ route('dashboard.wallet.topup.form') }}">Ke Top Up</a>
            <a class="btn btn-soft" href="{{ route('dashboard.wallet.transfer.form') }}">Ke Transfer</a>
        </div>
    </div>
    <div class="balance">
        <span style="font-size:.78rem;color:#eaf2ff;">Saldo Tersedia</span>
        <strong>Rp {{ number_format((float) $wallet->balance, 0, ',', '.') }}</strong>
    </div>
</section>

<section class="section-card panel">
    <form action="{{ route('dashboard.wallet.withdraw') }}" method="POST">
        @csrf
        <div class="row">
            <label class="label">Nominal Withdraw</label>
            <input type="number" name="amount" min="10000" step="1000" value="{{ old('amount') }}" placeholder="Minimal Rp 10.000" required>
        </div>

        <div class="row">
            <label class="label">Bank Tujuan</label>
            <select name="bank_name" required>
                <option value="">Pilih bank</option>
                <option value="BCA" {{ old('bank_name') === 'BCA' ? 'selected' : '' }}>BCA</option>
                <option value="BNI" {{ old('bank_name') === 'BNI' ? 'selected' : '' }}>BNI</option>
                <option value="BRI" {{ old('bank_name') === 'BRI' ? 'selected' : '' }}>BRI</option>
                <option value="MANDIRI" {{ old('bank_name') === 'MANDIRI' ? 'selected' : '' }}>MANDIRI</option>
                <option value="CIMB" {{ old('bank_name') === 'CIMB' ? 'selected' : '' }}>CIMB</option>
                <option value="LAINNYA" {{ old('bank_name') === 'LAINNYA' ? 'selected' : '' }}>Bank Lainnya</option>
            </select>
            <input type="text" name="bank_name_custom" value="{{ old('bank_name_custom') }}" placeholder="Isi nama bank jika pilih Bank Lainnya" style="margin-top:.5rem;">
        </div>

        <div class="row">
            <label class="label">Nomor Rekening</label>
            <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="Contoh: 1234567890" required>
        </div>

        <div class="row">
            <label class="label">Nama Pemilik Rekening</label>
            <input type="text" name="account_name" value="{{ old('account_name') }}" placeholder="Opsional">
        </div>

        <div class="row">
            <label class="label">Catatan</label>
            <textarea name="note" rows="3" placeholder="Catatan withdraw (opsional)">{{ old('note') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">PROSES WITHDRAW</button>
        <div class="note">Proses ini adalah simulasi, status transaksi akan langsung sukses.</div>
    </form>

    @if (!empty($receipt))
        <div class="receipt">
            <h3>✅ Withdraw Berhasil!</h3>
            <p>Nominal: Rp {{ number_format((float) $receipt['amount'], 0, ',', '.') }}</p>
            <p>Bank: {{ $receipt['bank_name'] }}</p>
            <p>Rekening: {{ $receipt['account_number'] }} ({{ $receipt['account_name'] }})</p>
            <p>Saldo: Rp {{ number_format((float) $receipt['before'], 0, ',', '.') }} → Rp {{ number_format((float) $receipt['after'], 0, ',', '.') }}</p>
            <p>Ref: {{ $receipt['reference'] }}</p>
        </div>
    @endif
</section>
@endsection
