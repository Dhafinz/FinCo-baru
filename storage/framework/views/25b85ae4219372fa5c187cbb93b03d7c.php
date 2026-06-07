

<?php $__env->startSection('title', 'Tambah User'); ?>
<?php $__env->startSection('breadcrumb', 'Users / Tambah'); ?>
<?php $__env->startSection('page_title', 'Tambah User'); ?>
<?php $__env->startSection('page_subtitle', 'Buat akun pengguna baru'); ?>

<?php $__env->startSection('content'); ?>
<form class="admin-form" method="POST" action="<?php echo e(route('admin.users.store')); ?>">
    <?php echo csrf_field(); ?>
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" required>
    </div>
    <div class="form-group">
        <label class="form-label">Email</label>
        <input class="form-control" type="email" name="email" required>
    </div>
    <div class="form-group">
        <label class="form-label">Password</label>
        <input class="form-control" type="password" name="password" required>
    </div>
    <div class="form-group">
        <label class="form-label">Username</label>
        <input class="form-control" type="text" name="username">
    </div>
    <div class="form-group">
        <label class="form-label">Full Name</label>
        <input class="form-control" type="text" name="full_name">
    </div>
    <div class="form-group">
        <label class="form-label">Phone</label>
        <input class="form-control" type="text" name="phone">
    </div>
    <div class="form-group">
        <label class="form-label">Tanggal Lahir</label>
        <input class="form-control" type="date" name="date_of_birth">
    </div>
    <div class="form-group">
        <label class="form-label">Role</label>
        <select class="form-control" name="role" required>
            <option value="user">user</option>
            <option value="admin">admin</option>
        </select>
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/users/create.blade.php ENDPATH**/ ?>