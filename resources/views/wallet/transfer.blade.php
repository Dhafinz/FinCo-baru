@extends('layouts.dashboard-panel')

@include('wallet._card_styles')

@section('content')
<section class="hero">
    <div>
        <h1>💸 Transfer ke Teman</h1>
        <p>Transfer saldo hanya ke teman yang statusnya accepted.</p>
        <div class="wallet-actions">
            <a class="btn btn-soft" href="{{ route('dashboard.wallet.topup.form') }}">Ke Top Up</a>
            <a class="btn btn-soft" href="{{ route('dashboard.wallet.withdraw.form') }}">Ke Withdraw</a>
        </div>
    </div>
    <div class="balance">
        <span style="font-size:.78rem;color:#eaf2ff;">Saldo Kamu</span>
        <strong>Rp {{ number_format((float) $wallet->balance, 0, ',', '.') }}</strong>
    </div>
</section>

<section class="section-card panel">
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

        <button type="submit" class="btn btn-primary">TRANSFER SEKARANG</button>
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
</section>
@endsection
