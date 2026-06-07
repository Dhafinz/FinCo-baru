

<?php $__env->startSection('title', 'Edit User'); ?>
<?php $__env->startSection('breadcrumb', 'Users / Edit'); ?>
<?php $__env->startSection('page_title', 'Edit User'); ?>
<?php $__env->startSection('page_subtitle', 'Ubah data pengguna'); ?>

<?php $__env->startSection('content'); ?>
<form class="admin-form" method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" value="<?php echo e($user->name); ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" value="<?php echo e($user->email); ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label">Password (kosongkan jika tidak diubah)</label>
        <input class="form-control" type="password" name="password">
    </div>
    <div class="form-group">
        <label class="form-label">Username</label>
        <input class="form-control" type="text" name="username" value="<?php echo e($user->username); ?>">
    </div>
    <div class="form-group">
        <label class="form-label">Full Name</label>
        <input class="form-control" type="text" name="full_name" value="<?php echo e($user->full_name); ?>">
    </div>
    <div class="form-group">
        <label class="form-label">Phone</label>
        <input class="form-control" type="text" name="phone" value="<?php echo e($user->phone); ?>">
    </div>
    <div class="form-group">
        <label class="form-label">Tanggal Lahir</label>
        <input class="form-control" type="date" name="date_of_birth" value="<?php echo e($user->date_of_birth); ?>">
    </div>
    <div class="form-group">
        <label class="form-label">Role</label>
        <select class="form-control" name="role" required>
            <option value="user" <?php echo e($user->role === 'user' ? 'selected' : ''); ?>>user</option>
            <option value="admin" <?php echo e($user->role === 'admin' ? 'selected' : ''); ?>>admin</option>
        </select>
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>