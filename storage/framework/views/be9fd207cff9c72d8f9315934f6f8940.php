

<?php $__env->startSection('title', 'Edit Quest'); ?>
<?php $__env->startSection('breadcrumb', 'Quests / Edit'); ?>
<?php $__env->startSection('page_title', 'Edit Quest'); ?>
<?php $__env->startSection('page_subtitle', 'Ubah data quest'); ?>

<?php $__env->startSection('content'); ?>
<form class="admin-form" method="POST" action="<?php echo e(route('admin.quests.update', $quest)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <div class="form-group">
        <label class="form-label">User</label>
        <select class="form-control" name="user_id" required>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($user->id); ?>" <?php echo e($quest->user_id == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" type="text" name="name" value="<?php echo e($quest->name); ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="3"><?php echo e($quest->description); ?></textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Difficulty</label>
        <select class="form-control" name="difficulty" required>
            <option value="easy" <?php echo e($quest->difficulty === 'easy' ? 'selected' : ''); ?>>easy</option>
            <option value="medium" <?php echo e($quest->difficulty === 'medium' ? 'selected' : ''); ?>>medium</option>
            <option value="hard" <?php echo e($quest->difficulty === 'hard' ? 'selected' : ''); ?>>hard</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Reward XP</label>
        <input class="form-control" type="number" name="reward_xp" value="<?php echo e($quest->reward_xp); ?>" min="0">
    </div>
    <div class="form-group">
        <label class="form-label">Start Date</label>
        <input class="form-control" type="date" name="start_date" value="<?php echo e($quest->start_date); ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label">End Date</label>
        <input class="form-control" type="date" name="end_date" value="<?php echo e($quest->end_date); ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status" required>
            <option value="active" <?php echo e($quest->status === 'active' ? 'selected' : ''); ?>>active</option>
            <option value="completed" <?php echo e($quest->status === 'completed' ? 'selected' : ''); ?>>completed</option>
            <option value="failed" <?php echo e($quest->status === 'failed' ? 'selected' : ''); ?>>failed</option>
        </select>
    </div>
    <?php
        $criteriaArr = is_array($quest->criteria) ? $quest->criteria : [];
        $trackingVal = $criteriaArr['tracking'] ?? 'transaction_count';
        $targetVal = $criteriaArr['target'] ?? '';
        $inferTipe = in_array($trackingVal, ['income_total']) ? 'income' : (in_array($trackingVal, ['expense_category_total', 'expense_total', 'no_spend_days']) ? 'expense' : 'both');
    ?>
    <div class="form-group">
        <label class="form-label">Tipe</label>
        <select class="form-control" name="tipe" required>
            <option value="income" <?php echo e($inferTipe === 'income' ? 'selected' : ''); ?>>Income (Nabung)</option>
            <option value="expense" <?php echo e($inferTipe === 'expense' ? 'selected' : ''); ?>>Expense (Hemat)</option>
            <option value="both" <?php echo e($inferTipe === 'both' ? 'selected' : ''); ?>>Both (Umum)</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Tracking</label>
        <select class="form-control" name="tracking" id="tracking" required>
            <option value="income_total" <?php echo e($trackingVal === 'income_total' ? 'selected' : ''); ?>>Total Income (Nabung)</option>
            <option value="expense_category_total" <?php echo e($trackingVal === 'expense_category_total' ? 'selected' : ''); ?>>Per Kategori Expense (Hemat)</option>
            <option value="expense_total" <?php echo e($trackingVal === 'expense_total' ? 'selected' : ''); ?>>Total Expense (Hemat)</option>
            <option value="transaction_count" <?php echo e($trackingVal === 'transaction_count' ? 'selected' : ''); ?>>Jumlah Transaksi</option>
            <option value="no_spend_days" <?php echo e($trackingVal === 'no_spend_days' ? 'selected' : ''); ?>>Hari Tanpa Belanja</option>
            <option value="login_streak" <?php echo e($trackingVal === 'login_streak' ? 'selected' : ''); ?>>Login Streak</option>
        </select>
    </div>
    <div class="form-group" id="kategori-group" style="<?php echo e($trackingVal === 'expense_category_total' ? '' : 'display:none;'); ?>">
        <label class="form-label">Kategori Expense</label>
        <select class="form-control" name="category_id">
            <option value="">— Pilih Kategori —</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>" <?php echo e(($quest->category ?? '') === $cat->name ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Target</label>
        <input class="form-control" type="number" name="target" value="<?php echo e($targetVal); ?>" placeholder="100000" min="0">
    </div>
    <div class="form-group" style="display:none;">
        <label class="form-label">Category (legacy)</label>
        <input class="form-control" type="text" name="category" value="<?php echo e($quest->category); ?>">
    </div>
    <div class="form-group" style="display:none;">
        <label class="form-label">Criteria (JSON / teks)</label>
        <textarea class="form-control" name="criteria" rows="3"><?php echo e(is_array($quest->criteria) ? json_encode($quest->criteria) : $quest->criteria); ?></textarea>
    </div>

    <script>
        document.getElementById('tracking').addEventListener('change', function() {
            document.getElementById('kategori-group').style.display = this.value === 'expense_category_total' ? '' : 'none';
        });
    </script>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/quests/edit.blade.php ENDPATH**/ ?>