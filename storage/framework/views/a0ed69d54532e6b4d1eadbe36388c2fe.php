<?php $__env->startSection('title', 'Gamification'); ?>
<?php $__env->startSection('breadcrumb', 'Gamification'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Gamification</h2>
            <p>Ringkasan profil gamifikasi pengguna</p>
        </div>
    </div>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Level</th>
                <th>Total XP</th>
                <th>Streak</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $profiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $profile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>#<?php echo e($profile->id); ?></td>
                    <td><?php echo e($profile->user_id); ?></td>
                    <td>
                        <span class="status-badge on_track">
                            <span class="dot"></span>
                            Level <?php echo e($profile->current_level); ?>

                        </span>
                    </td>
                    <td><?php echo e(number_format($profile->total_xp)); ?> XP</td>
                    <td><?php echo e($profile->current_streak); ?> hari</td>
                    <td><span class="activity-date"><?php echo e($profile->last_login_date ?? '-'); ?></span></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<div class="pagination">
    <?php echo e($profiles->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/gamification/index.blade.php ENDPATH**/ ?>