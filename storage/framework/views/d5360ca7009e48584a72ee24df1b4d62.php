<?php $__env->startSection('title', 'Badges'); ?>
<?php $__env->startSection('breadcrumb', 'Badges'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Badge</h2>
            <p>Kelola badge gamifikasi</p>
        </div>
    </div>
    <a href="<?php echo e(route('admin.badges.create')); ?>" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Badge
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Icon</th>
                <th>Required Level</th>
                <th>Required XP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>#<?php echo e($badge->id); ?></td>
                    <td style="font-weight:600"><?php echo e($badge->name); ?></td>
                    <td><?php echo e($badge->icon); ?></td>
                    <td>Level <?php echo e($badge->required_level); ?></td>
                    <td><?php echo e(number_format($badge->required_xp)); ?> XP</td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="<?php echo e(route('admin.badges.edit', $badge)); ?>">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.badges.destroy', $badge)); ?>" onsubmit="return confirm('Hapus badge ini?')">
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
    <?php echo e($badges->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/badges/index.blade.php ENDPATH**/ ?>