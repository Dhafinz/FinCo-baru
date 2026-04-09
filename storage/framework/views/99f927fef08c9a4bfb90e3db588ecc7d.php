<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Profile')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <?php echo $__env->make('profile.partials.update-profile-information-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <?php echo $__env->make('profile.partials.update-password-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-4xl">
                    <h3 class="text-lg font-semibold text-gray-900">Badge Yang Sudah Didapat</h3>
                    <p class="mt-1 text-sm text-gray-600">Semua badge hasil progres finansial kamu ditampilkan di sini.</p>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <?php $__empty_1 = true; $__currentLoopData = $userBadges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userBadge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <article class="rounded-lg border border-blue-100 bg-blue-50/50 p-4">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="font-medium text-gray-900"><?php echo e($userBadge->badge->name ?? 'Badge'); ?></h4>
                                    <span class="text-xl"><?php echo e($userBadge->badge->icon ?? '🏅'); ?></span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600"><?php echo e($userBadge->badge->description ?? 'Badge tercapai.'); ?></p>
                                <p class="mt-2 text-xs text-blue-700">Didapat: <?php echo e(optional($userBadge->earned_at)->format('d M Y H:i') ?? '-'); ?></p>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="rounded-lg border border-dashed border-gray-300 p-4 text-sm text-gray-600 sm:col-span-2 lg:col-span-3">
                                Belum ada badge yang didapat. Mulai dari transaksi, top up, dan challenge untuk unlock badge pertama.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <?php echo $__env->make('profile.partials.delete-user-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\finco\resources\views/profile/edit.blade.php ENDPATH**/ ?>