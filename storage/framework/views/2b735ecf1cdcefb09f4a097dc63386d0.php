<?php $__env->startSection('title', 'Categories'); ?>
<?php $__env->startSection('breadcrumb', 'Categories'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-toolbar">
    <div class="page-toolbar-left">
        <div>
            <h2>Data Kategori</h2>
            <p>Kelola kategori transaksi</p>
        </div>
    </div>
    <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Kategori
    </a>
</div>

<div class="admin-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Tipe</th>
                <th>Icon</th>
                <th>Warna</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>#<?php echo e($category->id); ?></td>
                    <td><?php echo e($category->name); ?></td>
                    <td>
                        <span class="activity-type <?php echo e($category->type); ?>">
                            <?php echo e($category->type === 'income' ? 'Pemasukan' : 'Pengeluaran'); ?>

                        </span>
                    </td>
                    <td><?php echo e($category->icon); ?></td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:6px">
                            <span style="width:14px;height:14px;border-radius:4px;background:<?php echo e($category->color ?? '#2563eb'); ?>;display:inline-block"></span>
                            <?php echo e($category->color); ?>

                        </span>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <a class="btn btn-secondary btn-sm" href="<?php echo e(route('admin.categories.edit', $category)); ?>">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.categories.destroy', $category)); ?>" onsubmit="return confirm('Hapus kategori ini?')">
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
    <?php echo e($categories->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>