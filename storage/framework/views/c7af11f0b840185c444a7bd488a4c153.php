<aside class="admin-sidebar fixed inset-y-0 left-0 z-40 hidden w-72 border-r border-slate-200 bg-slate-950 text-slate-100 lg:flex lg:flex-col">
    <div class="flex h-16 items-center gap-3 border-b border-white/10 px-6">
        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-400 text-lg font-black text-slate-950">F</div>
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">FinCo</p>
            <h2 class="text-base font-semibold text-white">Admin Panel</h2>
        </div>
    </div>

    <?php
        $items = [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => '◼'],
            ['label' => 'Transactions', 'route' => 'admin.transactions.index', 'icon' => '↔'],
            ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => '👤'],
            ['label' => 'Categories', 'route' => 'admin.categories.index', 'icon' => '▦'],
            ['label' => 'Budgets', 'route' => 'admin.budgets.index', 'icon' => '💰'],
            ['label' => 'Goals', 'route' => 'admin.goals.index', 'icon' => '🎯'],
            ['label' => 'Quests', 'route' => 'admin.quests.index', 'icon' => '⚡'],
            ['label' => 'Badges', 'route' => 'admin.badges.index', 'icon' => '🏅'],
            ['label' => 'Gamification', 'route' => 'admin.gamification.index', 'icon' => '📈'],
            ['label' => 'Reports', 'route' => 'admin.reports.index', 'icon' => '📊'],
        ];
    ?>

    <nav class="flex-1 space-y-2 px-4 py-5">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $isActive = request()->routeIs($item['route']); ?>
            <a href="<?php echo e(route($item['route'])); ?>" class="rounded-2xl px-2 py-1 text-sm font-medium transition <?php echo e($isActive ? 'active' : 'text-slate-300'); ?>">
                <span class="icon text-base"><?php echo e($item['icon']); ?></span>
                <span class="label"><?php echo e($item['label']); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>

    <div class="border-t border-white/10 px-6 py-4 text-xs text-slate-400">
        Signed in as <?php echo e(auth()->user()->email); ?>

    </div>
</aside>

<div class="fixed inset-0 z-30 bg-slate-950/60 lg:hidden" x-show="sidebarOpen" x-transition @click="sidebarOpen = false" style="display:none"></div>
<aside class="admin-sidebar fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-slate-200 bg-slate-950 text-slate-100 transition lg:hidden" :class="sidebarOpen ? 'translate-x-0' : ''">
    <div class="flex h-16 items-center gap-3 border-b border-white/10 px-6">
        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-400 text-lg font-black text-slate-950">F</div>
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">FinCo</p>
            <h2 class="text-base font-semibold text-white">Admin Panel</h2>
        </div>
    </div>
    <nav class="flex-1 space-y-2 px-4 py-5">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $isActive = request()->routeIs($item['route']); ?>
            <a href="<?php echo e(route($item['route'])); ?>" class="rounded-2xl px-2 py-1 text-sm font-medium transition <?php echo e($isActive ? 'active' : 'text-slate-300'); ?>">
                <span class="icon text-base"><?php echo e($item['icon']); ?></span>
                <span class="label"><?php echo e($item['label']); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>
</aside><?php /**PATH C:\finco\resources\views/admin/partials/sidebar.blade.php ENDPATH**/ ?>