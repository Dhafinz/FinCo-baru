<?php $__env->startSection('title', 'Users'); ?>
<?php $__env->startSection('breadcrumb', 'Users'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Pengguna</h2>
            <p>Kelola akun pengguna FinCo</p>
        </div>
    </div>
    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah User
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>#<?php echo e($user->id); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="header-avatar" style="width:28px;height:28px;font-size:11px">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            </div>
                            <span style="font-weight:600"><?php echo e($user->name); ?></span>
                        </div>
                    </td>
                    <td><?php echo e($user->email); ?></td>
                    <td><?php echo e($user->username); ?></td>
                    <td>
                        <span class="status-pill status-<?php echo e($user->role === 'admin' ? 'on_track' : 'warning'); ?>">
                            <?php echo e($user->role); ?>

                        </span>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="<?php echo e(route('admin.users.edit', $user)); ?>">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" onsubmit="return confirm('Hapus user ini?')">
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
    <?php echo e($users->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/users/index.blade.php ENDPATH**/ ?>