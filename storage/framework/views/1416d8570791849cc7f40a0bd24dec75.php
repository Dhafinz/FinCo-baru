

<?php $__env->startSection('title', 'Tambah Quest'); ?>
<?php $__env->startSection('breadcrumb', 'Quests / Tambah'); ?>
<?php $__env->startSection('page_title', 'Tambah Quest'); ?>
<?php $__env->startSection('page_subtitle', 'Buat quest baru'); ?>

<?php $__env->startSection('content'); ?>
<form class="admin-form" method="POST" action="<?php echo e(route('admin.quests.store')); ?>">
    <?php echo csrf_field(); ?>
    <div class="form-group">
        <label class="form-label">User</label>
        <select class="form-control" name="user_id" required>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" required>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Difficulty</label>
        <select class="form-control" name="difficulty" required>
            <option value="easy">easy</option>
            <option value="medium">medium</option>
            <option value="hard">hard</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Reward XP</label>
        <input class="form-control" type="number" name="reward_xp" value="10" min="0">
    </div>
    <div class="form-group">
        <label class="form-label">Start Date</label>
        <input class="form-control" type="date" name="start_date" required>
    </div>
    <div class="form-group">
        <label class="form-label">End Date</label>
        <input class="form-control" type="date" name="end_date" required>
    </div>
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status" required>
            <option value="active">active</option>
            <option value="completed">completed</option>
            <option value="failed">failed</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Tipe</label>
        <select class="form-control" name="tipe" required>
            <option value="income">Income (Nabung)</option>
            <option value="expense">Expense (Hemat)</option>
            <option value="both">Both (Umum)</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Tracking</label>
        <select class="form-control" name="tracking" id="tracking" required>
            <option value="income_total">Total Income (Nabung)</option>
            <option value="expense_category_total">Per Kategori Expense (Hemat)</option>
            <option value="expense_total">Total Expense (Hemat)</option>
            <option value="transaction_count">Jumlah Transaksi</option>
            <option value="no_spend_days">Hari Tanpa Belanja</option>
            <option value="login_streak">Login Streak</option>
        </select>
    </div>
    <div class="form-group" id="kategori-group" style="display:none;">
        <label class="form-label">Kategori Expense</label>
        <select class="form-control" name="category_id">
            <option value="">— Pilih Kategori —</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Target</label>
        <input class="form-control" type="number" name="target" placeholder="100000" min="0">
    </div>
    <div class="form-group" style="display:none;">
        <label class="form-label">Category (legacy)</label>
        <input class="form-control" type="text" name="category">
    </div>
    <div class="form-group" style="display:none;">
        <label class="form-label">Criteria (JSON / teks)</label>
        <textarea class="form-control" name="criteria" rows="3"></textarea>
    </div>

    <script>
        document.getElementById('tracking').addEventListener('change', function() {
            document.getElementById('kategori-group').style.display = this.value === 'expense_category_total' ? '' : 'none';
        });
    </script>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/quests/create.blade.php ENDPATH**/ ?>