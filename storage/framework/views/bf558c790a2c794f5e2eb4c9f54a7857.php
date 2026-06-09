

<?php echo $__env->make('wallet._card_styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startSection('content'); ?>
<section class="hero">
    <div>
        <h1>🏦 Withdraw Saldo</h1>
        <p>Tarik saldo wallet ke rekening bank tujuan (simulasi sukses instan).</p>
        <div class="wallet-actions">
            <a class="btn btn-soft" href="<?php echo e(route('dashboard.wallet.topup.form')); ?>">Ke Top Up</a>
            <a class="btn btn-soft" href="<?php echo e(route('dashboard.wallet.transfer.form')); ?>">Ke Transfer</a>
        </div>
    </div>
    <div class="balance">
        <span style="font-size:.78rem;color:#eaf2ff;">Saldo Tersedia</span>
        <strong>Rp <?php echo e(number_format((float) $wallet->balance, 0, ',', '.')); ?></strong>
    </div>
</section>

<section class="section-card panel">
    <form action="<?php echo e(route('dashboard.wallet.withdraw')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="row">
            <label class="label">Nominal Withdraw</label>
            <input type="number" name="amount" min="10000" step="1000" value="<?php echo e(old('amount')); ?>" placeholder="Minimal Rp 10.000" required>
        </div>

        <div class="row">
            <label class="label">Bank Tujuan</label>
            <select name="bank_name" required>
                <option value="">Pilih bank</option>
                <option value="BCA" <?php echo e(old('bank_name') === 'BCA' ? 'selected' : ''); ?>>BCA</option>
                <option value="BNI" <?php echo e(old('bank_name') === 'BNI' ? 'selected' : ''); ?>>BNI</option>
                <option value="BRI" <?php echo e(old('bank_name') === 'BRI' ? 'selected' : ''); ?>>BRI</option>
                <option value="MANDIRI" <?php echo e(old('bank_name') === 'MANDIRI' ? 'selected' : ''); ?>>MANDIRI</option>
                <option value="CIMB" <?php echo e(old('bank_name') === 'CIMB' ? 'selected' : ''); ?>>CIMB</option>
                <option value="LAINNYA" <?php echo e(old('bank_name') === 'LAINNYA' ? 'selected' : ''); ?>>Bank Lainnya</option>
            </select>
            <input type="text" name="bank_name_custom" value="<?php echo e(old('bank_name_custom')); ?>" placeholder="Isi nama bank jika pilih Bank Lainnya" style="margin-top:.5rem;">
        </div>

        <div class="row">
            <label class="label">Nomor Rekening</label>
            <input type="text" name="account_number" value="<?php echo e(old('account_number')); ?>" placeholder="Contoh: 1234567890" required>
        </div>

        <div class="row">
            <label class="label">Nama Pemilik Rekening</label>
            <input type="text" name="account_name" value="<?php echo e(old('account_name')); ?>" placeholder="Opsional">
        </div>

        <div class="row">
            <label class="label">Catatan</label>
            <textarea name="note" rows="3" placeholder="Catatan withdraw (opsional)"><?php echo e(old('note')); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">PROSES WITHDRAW</button>
        <div class="note">Proses ini adalah simulasi, status transaksi akan langsung sukses.</div>
    </form>

    <?php if(!empty($receipt)): ?>
        <div class="receipt">
            <h3>✅ Withdraw Berhasil!</h3>
            <p>Nominal: Rp <?php echo e(number_format((float) $receipt['amount'], 0, ',', '.')); ?></p>
            <p>Bank: <?php echo e($receipt['bank_name']); ?></p>
            <p>Rekening: <?php echo e($receipt['account_number']); ?> (<?php echo e($receipt['account_name']); ?>)</p>
            <p>Saldo: Rp <?php echo e(number_format((float) $receipt['before'], 0, ',', '.')); ?> → Rp <?php echo e(number_format((float) $receipt['after'], 0, ',', '.')); ?></p>
            <p>Ref: <?php echo e($receipt['reference']); ?></p>
        </div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/wallet/withdraw.blade.php ENDPATH**/ ?>