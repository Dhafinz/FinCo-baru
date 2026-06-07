<?php $__env->startSection('title', 'Quests'); ?>
<?php $__env->startSection('breadcrumb', 'Quests'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Quest</h2>
            <p>Kelola quest gamifikasi</p>
        </div>
    </div>
    <a href="<?php echo e(route('admin.quests.create')); ?>" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Quest
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Nama</th>
                <th>Difficulty</th>
                <th>Reward XP</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $quests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>#<?php echo e($quest->id); ?></td>
                    <td><?php echo e($quest->user_id); ?></td>
                    <td style="font-weight:600"><?php echo e($quest->name); ?></td>
                    <td>
                        <span class="status-badge <?php echo e($quest->difficulty === 'easy' ? 'on_track' : ($quest->difficulty === 'medium' ? 'warning' : 'exceeded')); ?>">
                            <span class="dot"></span>
                            <?php echo e(ucfirst($quest->difficulty)); ?>

                        </span>
                    </td>
                    <td><?php echo e($quest->reward_xp); ?></td>
                    <td>
                        <span class="status-pill status-<?php echo e($quest->status === 'completed' ? 'on_track' : ($quest->status === 'in_progress' ? 'warning' : 'exceeded')); ?>">
                            <?php echo e($quest->status); ?>

                        </span>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="<?php echo e(route('admin.quests.edit', $quest)); ?>">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.quests.destroy', $quest)); ?>" onsubmit="return confirm('Hapus quest ini?')">
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
    <?php echo e($quests->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/quests/index.blade.php ENDPATH**/ ?>