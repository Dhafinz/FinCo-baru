<?php $__env->startSection('title', 'Transactions'); ?>
<?php $__env->startSection('breadcrumb', 'Transactions'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Transaksi</h2>
            <p>Kelola transaksi pengguna</p>
        </div>
    </div>
    <a href="<?php echo e(route('admin.transactions.create')); ?>" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Transaksi
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Tipe</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>XP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>#<?php echo e($transaction->id); ?></td>
                    <td><?php echo e($transaction->user_id); ?></td>
                    <td>
                        <span class="activity-type <?php echo e($transaction->type); ?>">
                            <?php echo e($transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran'); ?>

                        </span>
                    </td>
                    <td>
                        <span class="activity-amount <?php echo e($transaction->type); ?>">
                            Rp <?php echo e(number_format($transaction->amount, 0, ',', '.')); ?>

                        </span>
                    </td>
                    <td>
                        <span class="activity-date"><?php echo e($transaction->transaction_date ? $transaction->transaction_date->format('d M Y') : '-'); ?></span>
                    </td>
                    <td><?php echo e($transaction->xp_earned); ?></td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="<?php echo e(route('admin.transactions.edit', $transaction)); ?>">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.transactions.destroy', $transaction)); ?>" onsubmit="return confirm('Hapus transaksi ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<div class="pagination">
    <?php echo e($transactions->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/transactions/index.blade.php ENDPATH**/ ?>