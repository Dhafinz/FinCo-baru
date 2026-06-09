<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - <?php echo e(config('app.name', 'FinCo')); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>?v=<?php echo e(filemtime(public_path('css/admin.css'))); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<div class="admin-shell">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="admin-main">
        <header class="admin-header">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                    <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div class="header-breadcrumb">
                    <a href="<?php echo e(route('admin.dashboard')); ?>">FinCo</a>
                    <span class="header-breadcrumb-sep">/</span>
                    <span><?php echo $__env->yieldContent('breadcrumb', 'Dashboard'); ?></span>
                </div>
            </div>

            <div class="header-search" style="max-width:none;flex:0">
                <span style="font-size:13px;color:var(--text-secondary);font-weight:500;white-space:nowrap">
                    <?php (\Carbon\Carbon::setLocale('id')); ?>
                    <?php echo e(now()->translatedFormat('l, d F Y')); ?>

                </span>
            </div>

            <div class="header-right">
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline">
                    <?php echo csrf_field(); ?>
                    <button class="header-action-btn" title="Logout" type="submit">
                        <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    </button>
                </form>
                <div class="header-divider"></div>
                <div class="header-profile">
                    <div class="header-avatar">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </div>
                    <div class="header-profile-info">
                        <span class="header-profile-name"><?php echo e(auth()->user()->name); ?></span>
                        <span class="header-profile-role">Administrator</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="admin-content">
            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-error">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if (! empty(trim($__env->yieldContent('page_title')))): ?>
                <div class="page-header">
                    <h1><?php echo $__env->yieldContent('page_title'); ?></h1>
                    <?php if (! empty(trim($__env->yieldContent('page_subtitle')))): ?>
                        <p><?php echo $__env->yieldContent('page_subtitle'); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.admin-sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle = document.getElementById('sidebarToggle');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('show');
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }

    if (toggle) {
        toggle.addEventListener('click', openSidebar);
    }

    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
    });
});
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\finco\resources\views/admin/layouts/admin.blade.php ENDPATH**/ ?>