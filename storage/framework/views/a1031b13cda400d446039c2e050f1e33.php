

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page_title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <section class="rounded-3xl bg-gradient-to-r from-slate-950 via-slate-900 to-cyan-950 px-6 py-8 text-white shadow-xl">
            <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr] lg:items-end">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-cyan-200">Global overview</p>
                    <h2 class="mt-2 text-3xl font-semibold">Ringkasan FinCo Admin</h2>
                    <p class="mt-3 max-w-2xl text-sm text-slate-300">Pantau semua aktivitas user, transaksi, budget, goals, quest, badges, dan performa gamification dari satu pusat kontrol.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-slate-300">Users</p>
                        <p class="mt-1 text-2xl font-semibold"><?php echo e(number_format($stats['users'])); ?></p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <p class="text-slate-300">Transactions</p>
                        <p class="mt-1 text-2xl font-semibold"><?php echo e(number_format($stats['transactions'])); ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <?php $__currentLoopData = [
                ['label' => 'Total Income', 'value' => $stats['income'], 'tone' => 'emerald'],
                ['label' => 'Total Expense', 'value' => $stats['expense'], 'tone' => 'rose'],
                ['label' => 'Categories', 'value' => $stats['categories'], 'tone' => 'sky'],
                ['label' => 'Gamification Profiles', 'value' => $stats['profiles'], 'tone' => 'amber'],
                ['label' => 'Budgets', 'value' => $stats['budgets'], 'tone' => 'indigo'],
                ['label' => 'Goals', 'value' => $stats['goals'], 'tone' => 'violet'],
                ['label' => 'Quests', 'value' => $stats['quests'], 'tone' => 'cyan'],
                ['label' => 'Badges', 'value' => $stats['badges'], 'tone' => 'slate'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500"><?php echo e($card['label']); ?></p>
                    <p class="mt-3 text-2xl font-semibold text-slate-900"><?php echo e(is_numeric($card['value']) ? (str_contains($card['label'], 'Income') || str_contains($card['label'], 'Expense') ? 'Rp ' . number_format((float) $card['value'], 0, ',', '.') : number_format((int) $card['value'])) : $card['value']); ?></p>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Income vs Expense</h3>
                    <span class="text-xs font-medium uppercase tracking-[0.25em] text-slate-400">6 months</span>
                </div>
                <div class="mt-6 space-y-4">
                    <?php $__currentLoopData = $monthlySeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <div class="mb-2 flex items-center justify-between text-xs text-slate-500">
                                <span><?php echo e($row['label']); ?></span>
                                <span>Rp <?php echo e(number_format($row['income'], 0, ',', '.')); ?> / Rp <?php echo e(number_format($row['expense'], 0, ',', '.')); ?></span>
                            </div>
                            <div class="grid gap-2">
                                <div class="h-3 rounded-full bg-slate-100 overflow-hidden"><div class="h-full rounded-full bg-emerald-500" style="width: <?php echo e(min(100, ($stats['income'] > 0 ? ($row['income'] / max($stats['income'], 1)) * 100 : 0))); ?>%"></div></div>
                                <div class="h-3 rounded-full bg-slate-100 overflow-hidden"><div class="h-full rounded-full bg-rose-500" style="width: <?php echo e(min(100, ($stats['expense'] > 0 ? ($row['expense'] / max($stats['expense'], 1)) * 100 : 0))); ?>%"></div></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </article>

            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Global Status</h3>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <?php $__currentLoopData = $reportRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400"><?php echo e(strtoupper($row->type)); ?></p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">Rp <?php echo e(number_format((float) $row->total_amount, 0, ',', '.')); ?></p>
                            <p class="text-sm text-slate-500"><?php echo e(number_format((int) $row->total_count)); ?> transaksi</p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Recent Transactions</h3>
                <div class="mt-5 overflow-hidden rounded-2xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-slate-500">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium">User</th>
                                <th class="px-4 py-3 text-left font-medium">Type</th>
                                <th class="px-4 py-3 text-left font-medium">Amount</th>
                                <th class="px-4 py-3 text-left font-medium">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-4 py-3"><?php echo e($trx->user?->name ?? '-'); ?><div class="text-xs text-slate-400"><?php echo e($trx->category?->name ?? 'No category'); ?></div></td>
                                    <td class="px-4 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-medium <?php echo e($trx->type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'); ?>"><?php echo e(strtoupper($trx->type)); ?></span></td>
                                    <td class="px-4 py-3 font-medium">Rp <?php echo e(number_format((float) $trx->amount, 0, ',', '.')); ?></td>
                                    <td class="px-4 py-3 text-slate-500"><?php echo e(optional($trx->transaction_date)->format('d M Y')); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="4" class="px-4 py-6 text-center text-slate-500">Belum ada transaksi.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Top Users by XP</h3>
                <div class="mt-5 space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $topUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <div>
                                <p class="font-medium text-slate-900"><?php echo e($user->name); ?></p>
                                <p class="text-xs text-slate-500"><?php echo e($user->email); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-slate-900"><?php echo e(number_format((int) ($user->gamificationProfile?->total_xp ?? 0))); ?> XP</p>
                                <p class="text-xs text-slate-500">Level <?php echo e((int) ($user->gamificationProfile?->current_level ?? 1)); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-slate-500">Belum ada data user.</p>
                    <?php endif; ?>
                </div>
            </article>
        </section>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>