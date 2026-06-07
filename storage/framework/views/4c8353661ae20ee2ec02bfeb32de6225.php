<?php $__env->startSection('title', 'Goals'); ?>
<?php $__env->startSection('breadcrumb', 'Goals'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Goals</h2>
            <p>Kelola financial goals pengguna</p>
        </div>
    </div>
    <a href="<?php echo e(route('admin.goals.create')); ?>" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Goal
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Nama</th>
                <th>Target</th>
                <th>Terkumpul</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>#<?php echo e($goal->id); ?></td>
                    <td><?php echo e($goal->user_id); ?></td>
                    <td style="font-weight:600"><?php echo e($goal->name); ?></td>
                    <td>Rp <?php echo e(number_format($goal->target_amount, 0, ',', '.')); ?></td>
                    <td>Rp <?php echo e(number_format($goal->current_amount, 0, ',', '.')); ?></td>
                    <td>
                        <span class="status-pill status-<?php echo e($goal->status === 'completed' ? 'on_track' : ($goal->status === 'in_progress' ? 'warning' : 'exceeded')); ?>">
                            <?php echo e($goal->status); ?>

                        </span>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="<?php echo e(route('admin.goals.edit', $goal)); ?>">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.goals.destroy', $goal)); ?>" onsubmit="return confirm('Hapus goal ini?')">
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
    <?php echo e($goals->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/goals/index.blade.php ENDPATH**/ ?>