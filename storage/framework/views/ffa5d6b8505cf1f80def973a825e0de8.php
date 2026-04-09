

<?php echo $__env->make('wallet._card_styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startSection('content'); ?>
<section class="hero">
    <div>
        <h1>💳 Top Up Saldo</h1>
        <p>Simulasi top up langsung sukses tanpa payment gateway.</p>
        <div class="wallet-actions">
            <a class="btn btn-soft" href="<?php echo e(route('dashboard.wallet.withdraw.form')); ?>">Ke Withdraw</a>
            <a class="btn btn-soft" href="<?php echo e(route('dashboard.wallet.transfer.form')); ?>">Ke Transfer</a>
        </div>
    </div>
    <div class="balance">
        <span style="font-size:.78rem;color:#eaf2ff;">Saldo Sekarang</span>
        <strong>Rp <?php echo e(number_format((float) $wallet->balance, 0, ',', '.')); ?></strong>
    </div>
</section>

<section class="section-card panel">
    <form action="<?php echo e(route('dashboard.wallet.topup')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="row">
            <label class="label">Pilih Nominal</label>
            <div class="chips">
                <?php $__currentLoopData = $presetAmounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $preset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="chip">
                        <input type="radio" name="selected_amount" value="<?php echo e($preset); ?>" <?php echo e((int) old('selected_amount', 100000) === (int) $preset ? 'checked' : ''); ?>>
                        <span><?php echo e(number_format($preset / 1000, 0, ',', '.')); ?>k</span>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="row">
            <label class="label">Atau ketik nominal manual</label>
            <input type="number" name="amount" min="50000" step="1000" value="<?php echo e(old('amount')); ?>" placeholder="Contoh: 150000">
        </div>

        <div class="row">
            <label class="label">Metode Pembayaran</label>
            <div class="methods">
                <label class="method"><input type="radio" name="payment_method" value="bank_transfer" <?php echo e(old('payment_method', 'bank_transfer') === 'bank_transfer' ? 'checked' : ''); ?>> Bank Transfer BCA</label>
                <label class="method"><input type="radio" name="payment_method" value="virtual_account" <?php echo e(old('payment_method') === 'virtual_account' ? 'checked' : ''); ?>> Virtual Account</label>
                <label class="method"><input type="radio" name="payment_method" value="qris" <?php echo e(old('payment_method') === 'qris' ? 'checked' : ''); ?>> QRIS</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">KONFIRMASI TOP UP</button>
        <div class="note">Top up pertama akan mendapatkan bonus XP tambahan.</div>
    </form>

    <?php if(!empty($receipt)): ?>
        <div class="receipt">
            <h3>✅ Top Up Berhasil!</h3>
            <p>Nominal: Rp <?php echo e(number_format((float) $receipt['amount'], 0, ',', '.')); ?></p>
            <p>Metode: <?php echo e($receipt['method']); ?></p>
            <p>Saldo: Rp <?php echo e(number_format((float) $receipt['before'], 0, ',', '.')); ?> → Rp <?php echo e(number_format((float) $receipt['after'], 0, ',', '.')); ?></p>
            <p>Ref: <?php echo e($receipt['reference']); ?></p>
            <p>XP: +<?php echo e($receipt['xp']); ?> 🎮</p>
        </div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/wallet/topup.blade.php ENDPATH**/ ?>