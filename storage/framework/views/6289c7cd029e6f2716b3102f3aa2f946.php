<?php $__env->startSection('title', 'Budgets'); ?>
<?php $__env->startSection('breadcrumb', 'Budgets'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Anggaran</h2>
            <p>Kelola anggaran pengguna</p>
        </div>
    </div>
    <a href="<?php echo e(route('admin.budgets.create')); ?>" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Budget
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Kategori</th>
                <th>Limit</th>
                <th>Terpakai</th>
                <th>Periode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>#<?php echo e($budget->id); ?></td>
                    <td><?php echo e($budget->user_id); ?></td>
                    <td><?php echo e($budget->category); ?></td>
                    <td>Rp <?php echo e(number_format($budget->limit_amount, 0, ',', '.')); ?></td>
                    <td>Rp <?php echo e(number_format($budget->spent_amount, 0, ',', '.')); ?></td>
                    <td><?php echo e($budget->period); ?></td>
                    <td>
                        <span class="status-badge <?php echo e($budget->status); ?>">
                            <span class="dot"></span>
                            <?php echo e($budget->status === 'on_track' ? 'On Track' : ($budget->status === 'warning' ? 'Warning' : 'Exceeded')); ?>

                        </span>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="<?php echo e(route('admin.budgets.edit', $budget)); ?>">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.budgets.destroy', $budget)); ?>" onsubmit="return confirm('Hapus budget ini?')">
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
    <?php echo e($budgets->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/budgets/index.blade.php ENDPATH**/ ?>