

<?php echo $__env->make('wallet._card_styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startSection('content'); ?>
<section class="hero">
    <div>
        <h1>💰 Wallet Saya</h1>
        <p>Kelola saldo dompet digital kamu untuk top up, bayar, dan transfer ke teman.</p>
        <div class="wallet-actions">
            <a class="btn btn-primary" href="<?php echo e(route('dashboard.wallet.topup.form')); ?>">TOP UP</a>
            <a class="btn btn-soft" href="<?php echo e(route('dashboard.wallet.withdraw.form')); ?>">WITHDRAW</a>
            <a class="btn btn-soft" href="<?php echo e(route('dashboard.wallet.transfer.form')); ?>">TRANSFER</a>
        </div>
    </div>
    <div class="balance">
        <span style="font-size:.78rem;color:#eaf2ff;">Saldo Saat Ini</span>
        <strong>Rp <?php echo e(number_format((float) $wallet->balance, 0, ',', '.')); ?></strong>
    </div>
</section>

<section class="section-card panel">
    <h2>Riwayat Aktivitas Wallet</h2>
    <p>Semua aktivitas wallet seperti top up, pembayaran, dan transfer dicatat di sini.</p>
    <div class="list">
        <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $isIncoming = in_array($activity->type, ['top_up', 'transfer_in'], true);
                $icon = match ($activity->type) {
                    'top_up' => '✅',
                    'payment' => '🔴',
                    'transfer_in' => '🟢',
                    'transfer_out' => '🔵',
                    default => '•',
                };
            ?>
            <div class="item">
                <div>
                    <h3><?php echo e($icon); ?> <?php echo e(ucfirst(str_replace('_', ' ', $activity->type))); ?></h3>
                    <p><?php echo e($activity->description ?: 'Aktivitas wallet'); ?> • <?php echo e($activity->created_at?->format('d M Y H:i')); ?></p>
                </div>
                <div style="text-align:right;">
                    <span class="tag"><?php echo e(strtoupper($activity->status)); ?></span>
                    <div class="<?php echo e($isIncoming ? 'amount-plus' : 'amount-minus'); ?>" style="margin-top:.25rem;">
                        <?php echo e($isIncoming ? '+' : '-'); ?>Rp <?php echo e(number_format((float) $activity->amount, 0, ',', '.')); ?>

                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty">Belum ada aktivitas wallet. Mulai dengan top up pertama kamu.</div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/wallet/index.blade.php ENDPATH**/ ?>