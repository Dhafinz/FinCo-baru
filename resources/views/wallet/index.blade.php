@extends('layouts.dashboard-panel')

@include('wallet._card_styles')

@section('content')
<section class="hero">
    <div>
        <h1>💰 Wallet Saya</h1>
        <p>Kelola saldo dompet digital kamu untuk top up, bayar, dan transfer ke teman.</p>
        <div class="wallet-actions">
            <a class="btn btn-primary" href="{{ route('dashboard.wallet.topup.form') }}">TOP UP</a>
            <a class="btn btn-soft" href="{{ route('dashboard.wallet.withdraw.form') }}">WITHDRAW</a>
            <a class="btn btn-soft" href="{{ route('dashboard.wallet.transfer.form') }}">TRANSFER</a>
        </div>
    </div>
    <div class="balance">
        <span style="font-size:.78rem;color:#eaf2ff;">Saldo Saat Ini</span>
        <strong>Rp {{ number_format((float) $wallet->balance, 0, ',', '.') }}</strong>
    </div>
</section>

<section class="section-card panel">
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
                    <div class="{{ $isIncoming ? 'amount-plus' : 'amount-minus' }}" style="margin-top:.25rem;">
                        {{ $isIncoming ? '+' : '-' }}Rp {{ number_format((float) $activity->amount, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="empty">Belum ada aktivitas wallet. Mulai dengan top up pertama kamu.</div>
        @endforelse
    </div>
</section>
@endsection
