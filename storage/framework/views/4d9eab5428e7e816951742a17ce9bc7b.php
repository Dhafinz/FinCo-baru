

<?php echo $__env->make('wallet._card_styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startSection('content'); ?>
<section class="hero">
    <div>
        <h1>💸 Transfer ke Teman</h1>
        <p>Transfer saldo hanya ke teman yang statusnya accepted.</p>
        <div class="wallet-actions">
            <a class="btn btn-soft" href="<?php echo e(route('dashboard.wallet.topup.form')); ?>">Ke Top Up</a>
            <a class="btn btn-soft" href="<?php echo e(route('dashboard.wallet.withdraw.form')); ?>">Ke Withdraw</a>
        </div>
    </div>
    <div class="balance">
        <span style="font-size:.78rem;color:#eaf2ff;">Saldo Kamu</span>
        <strong>Rp <?php echo e(number_format((float) $wallet->balance, 0, ',', '.')); ?></strong>
    </div>
</section>

<section class="section-card panel">
    <form action="<?php echo e(route('dashboard.wallet.transfer')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="row">
            <label class="label">Pilih Teman</label>
            <select name="friend_id" required>
                <option value="">Pilih dari daftar teman</option>
                <?php $__currentLoopData = $friends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $friend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($friend['id']); ?>" <?php echo e((int) old('friend_id', $preselectedFriendId) === (int) $friend['id'] ? 'selected' : ''); ?>>
                        <?php echo e($friend['name']); ?> ({{ $friend['username'] }}) • Level <?php echo e($friend['level']); ?> • XP <?php echo e(number_format($friend['xp'], 0, ',', '.')); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="row">
            <label class="label">Nominal</label>
            <input type="number" name="amount" min="10000" step="1000" value="<?php echo e(old('amount')); ?>" placeholder="Rp 10.000 minimal" required>
        </div>

        <div class="row">
            <label class="label">Catatan</label>
            <textarea name="note" rows="3" placeholder="Catatan transfer (opsional)"><?php echo e(old('note')); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">TRANSFER SEKARANG</button>
    </form>

    <?php if(!empty($receipt)): ?>
        <div class="receipt">
            <h3>✅ Transfer Berhasil!</h3>
            <p>Nominal: Rp <?php echo e(number_format((float) $receipt['amount'], 0, ',', '.')); ?></p>
            <p>Saldo: Rp <?php echo e(number_format((float) $receipt['before'], 0, ',', '.')); ?> → Rp <?php echo e(number_format((float) $receipt['after'], 0, ',', '.')); ?></p>
            <p>Ref: <?php echo e($receipt['reference']); ?></p>
            <p>XP: +<?php echo e($receipt['xp']); ?> 🎮</p>
        </div>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/wallet/transfer.blade.php ENDPATH**/ ?>