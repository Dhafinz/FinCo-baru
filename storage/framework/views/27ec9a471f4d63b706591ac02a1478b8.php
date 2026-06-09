

<?php $__env->startSection('title', 'Edit Transaksi'); ?>
<?php $__env->startSection('breadcrumb', 'Transactions / Edit'); ?>
<?php $__env->startSection('page_title', 'Edit Transaksi'); ?>
<?php $__env->startSection('page_subtitle', 'Ubah data transaksi'); ?>

<?php $__env->startSection('content'); ?>
<form class="admin-form" method="POST" action="<?php echo e(route('admin.transactions.update', $transaction)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <div class="form-group">
        <label class="form-label">User</label>
        <select class="form-control" name="user_id" required>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($user->id); ?>" <?php echo e($transaction->user_id == $user->id ? 'selected' : ''); ?>><?php echo e($user->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Category</label>
        <select class="form-control" name="category_id">
            <option value="">-</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php echo e($transaction->category_id == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?> (<?php echo e($category->type); ?>)</option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Budget</label>
        <select class="form-control" name="budget_id">
            <option value="">-</option>
            <?php $__currentLoopData = $budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($budget->id); ?>" <?php echo e($transaction->budget_id == $budget->id ? 'selected' : ''); ?>>#<?php echo e($budget->id); ?> - <?php echo e($budget->category); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Type</label>
        <select class="form-control" name="type" required>
            <option value="income" <?php echo e($transaction->type === 'income' ? 'selected' : ''); ?>>income</option>
            <option value="expense" <?php echo e($transaction->type === 'expense' ? 'selected' : ''); ?>>expense</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Amount</label>
        <input class="form-control" type="number" step="0.01" name="amount" value="<?php echo e($transaction->amount); ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <input class="form-control" type="text" name="description" value="<?php echo e($transaction->description); ?>">
    </div>
    <div class="form-group">
        <label class="form-label">Tanggal</label>
        <input class="form-control" type="date" name="transaction_date" value="<?php echo e($transaction->transaction_date); ?>" required>
    </div>
    <div class="form-group">
        <label class="form-label">XP Earned</label>
        <input class="form-control" type="number" name="xp_earned" value="<?php echo e($transaction->xp_earned); ?>" min="0">
    </div>
    <button class="btn btn-primary" type="submit">Simpan</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/transactions/edit.blade.php ENDPATH**/ ?>