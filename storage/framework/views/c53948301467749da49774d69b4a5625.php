

<?php $__env->startPush('pageStyles'); ?>
<style>
    .section-card { background:#fff; border:1px solid #dbe8f6; border-radius:14px; box-shadow:0 10px 24px rgba(30,64,175,.05); }
    .header {
        padding:1rem;
        background:linear-gradient(140deg,#1d4ed8,#60a5fa);
        color:#fff;
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:1rem;
        border-radius:14px;
        border:1px solid rgba(147,197,253,.35);
    }
    .header h1 { font-family:'Sora',sans-serif; font-size:1.3rem; margin-bottom:.2rem; }
    .header p { color:#eaf2ff; font-size:.86rem; }
    .chips { display:flex; gap:.45rem; flex-wrap:wrap; }
    .chip { background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.35); border-radius:999px; padding:.3rem .62rem; font-size:.76rem; font-weight:700; }
    .search { margin-top:1rem; padding:1rem; }
    .search form { display:grid; grid-template-columns:1fr auto; gap:.5rem; }
    input[type='text'] { border:1px solid #dbe8f6; border-radius:10px; padding:.58rem .68rem; font-family:inherit; font-size:.86rem; }
    .btn { border-radius:10px; padding:.52rem .72rem; border:1px solid; font-size:.8rem; font-weight:700; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; font-family:inherit; }
    .btn-blue { background:#2563eb; color:#fff; border-color:#2563eb; }
    .btn-red-soft { background:#fff; color:#be123c; border-color:#fecdd3; }
    .btn-green { background:#16a34a; color:#fff; border-color:#16a34a; }
    .btn-red { background:#dc2626; color:#fff; border-color:#dc2626; }
    .layout { margin-top:1rem; display:grid; grid-template-columns:2fr 1fr; gap:.9rem; }
    .panel { padding:1rem; }
    .panel h2 { font-size:.98rem; margin-bottom:.2rem; }
    .panel p { color:#55708d; font-size:.82rem; margin-bottom:.7rem; }
    .list { display:grid; gap:.6rem; }
    .friend-card {
        border:1px solid #dbe8f6;
        border-radius:12px;
        padding:.7rem;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:.7rem;
        transition:transform .16s ease, box-shadow .16s ease;
        background:#fff;
    }
    .friend-card:hover { transform:translateY(-2px); box-shadow:0 10px 20px rgba(30,64,175,.08); }
    .info { display:flex; gap:.6rem; align-items:center; }
    .avatar {
        width:42px; height:42px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center;
        color:#fff; font-weight:800; background:linear-gradient(135deg,#3b82f6,#1d4ed8);
    }
    .name { font-weight:700; color:#163354; font-size:.9rem; }
    .meta { font-size:.76rem; color:#55708d; }
    .level { display:inline-flex; padding:.16rem .46rem; border-radius:999px; font-size:.7rem; font-weight:700; margin-top:.22rem; }
    .level-high { background:#dcfce7; color:#166534; }
    .level-mid { background:#dbeafe; color:#1e3a8a; }
    .level-low { background:#eef2ff; color:#3730a3; }
    .actions { display:flex; gap:.35rem; flex-wrap:wrap; justify-content:flex-end; }
    .empty { border:1px dashed #bfdbfe; border-radius:12px; background:#f8fbff; padding:.95rem; color:#3b5d85; font-size:.85rem; text-align:center; line-height:1.5; }
    .search-results { margin-top:.8rem; display:grid; gap:.5rem; }
    @media (max-width:980px) { .layout { grid-template-columns:1fr; } }
    @media (max-width:640px) {
        .header { flex-direction:column; }
        .search form { grid-template-columns:1fr; }
        .friend-card { flex-direction:column; align-items:flex-start; }
        .actions { width:100%; justify-content:flex-start; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<section class="header">
    <div>
        <h1>👥 Teman</h1>
        <p>Kelola pertemanan, blokir akun, dan transfer saldo antar pengguna FinCo.</p>
    </div>
    <div class="chips">
        <span class="chip"><?php echo e($friends->count()); ?> Teman</span>
        <span class="chip"><?php echo e($incomingRequests->count()); ?> Request</span>
        <span class="chip"><?php echo e(($blockedUsers ?? collect())->count()); ?> Diblokir</span>
    </div>
</section>

<section class="section-card search">
    <form action="<?php echo e(route('dashboard.friends.search')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="text" name="keyword" value="<?php echo e(old('keyword')); ?>" placeholder="🔍 Cari nama atau email teman..." required>
        <button type="submit" class="btn btn-blue">Cari</button>
    </form>

    <?php if($searchResults->isNotEmpty()): ?>
        <div class="search-results">
            <?php $__currentLoopData = $searchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="friend-card">
                    <div class="info">
                        <div class="avatar"><?php echo e(strtoupper(substr($result['name'] ?? 'U', 0, 1))); ?></div>
                        <div>
                            <div class="name"><?php echo e($result['name']); ?></div>
                            <div class="meta">{{ $result['username'] }} • <?php echo e($result['email']); ?></div>
                        </div>
                    </div>
                    <div class="actions">
                        <?php if(!empty($result['is_blocked'])): ?>
                            <span class="btn" style="border-color:#fecdd3;background:#fff1f2;color:#9f1239;cursor:default;">BLOCKED</span>
                        <?php elseif(in_array($result['status'], ['pending', 'accepted'], true)): ?>
                            <span class="btn" style="border-color:#dbeafe;background:#eff6ff;color:#1e3a8a;cursor:default;"><?php echo e(strtoupper($result['status'])); ?></span>
                        <?php else: ?>
                            <form action="<?php echo e(route('dashboard.friends.add')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="target_user_id" value="<?php echo e($result['id']); ?>">
                                <button type="submit" class="btn btn-blue">+ Tambah Teman</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</section>

<section class="layout">
    <article class="section-card panel">
        <h2>👥 Daftar Teman (<?php echo e($friends->count()); ?>)</h2>
        <p>Teman yang sudah accepted dan bisa jadi tujuan transfer saldo.</p>

        <div class="list">
            <?php $__empty_1 = true; $__currentLoopData = $friends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $friend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $levelClass = $friend['level'] >= 5 ? 'level-high' : ($friend['level'] >= 3 ? 'level-mid' : 'level-low');
                ?>
                <div class="friend-card">
                    <div class="info">
                        <div class="avatar"><?php echo e($friend['initials']); ?></div>
                        <div>
                            <div class="name"><?php echo e($friend['name']); ?></div>
                            <div class="meta"><?php echo e($friend['username']); ?></div>
                            <span class="level <?php echo e($levelClass); ?>">Level <?php echo e($friend['level']); ?></span>
                            <div class="meta" style="margin-top:.18rem;">XP: <?php echo e(number_format($friend['xp'], 0, ',', '.')); ?></div>
                        </div>
                    </div>
                    <div class="actions">
                        <a class="btn btn-blue" href="<?php echo e(route('dashboard.wallet.transfer.form', ['friend_id' => $friend['id']])); ?>">💸 Transfer</a>
                        <form action="<?php echo e(route('dashboard.friends.remove', $friend['id'])); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-red-soft">❌ Hapus</button>
                        </form>
                        <form action="<?php echo e(route('dashboard.friends.block', $friend['id'])); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-red" onclick="return confirm('Blokir user ini?')">⛔ Blokir</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty">
                    <div style="font-size:1.2rem; margin-bottom:.2rem;">👥</div>
                    Belum ada teman.<br>
                    Cari teman dan mulai bertransaksi bersama!
                </div>
            <?php endif; ?>
        </div>
    </article>

    <article class="section-card panel">
        <h2>🔔 Request Masuk (<?php echo e($incomingRequests->count()); ?>)</h2>
        <p>Permintaan pertemanan yang menunggu respons.</p>
        <div class="list">
            <?php $__empty_1 = true; $__currentLoopData = $incomingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="friend-card" style="padding:.65rem;">
                    <div class="info">
                        <div class="avatar"><?php echo e($req['initials']); ?></div>
                        <div>
                            <div class="name"><?php echo e($req['name']); ?></div>
                            <div class="meta"><?php echo e($req['username']); ?></div>
                            <div class="meta">Level <?php echo e($req['level']); ?> • XP <?php echo e(number_format($req['xp'], 0, ',', '.')); ?></div>
                        </div>
                    </div>
                    <div class="actions">
                        <form action="<?php echo e(route('dashboard.friends.accept', $req['id'])); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-green">✅ Terima</button>
                        </form>
                        <form action="<?php echo e(route('dashboard.friends.reject', $req['id'])); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-red">❌ Tolak</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty">Belum ada request masuk.</div>
            <?php endif; ?>
        </div>

        <h2 style="margin-top:1rem;">🚫 Daftar Blokir</h2>
        <p>User dibawah ini tidak bisa kirim/terima request pertemanan.</p>
        <div class="list">
            <?php $__empty_1 = true; $__currentLoopData = ($blockedUsers ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blocked): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="friend-card" style="padding:.65rem;">
                    <div class="info">
                        <div class="avatar"><?php echo e(strtoupper(substr($blocked['name'] ?? 'U', 0, 1))); ?></div>
                        <div>
                            <div class="name"><?php echo e($blocked['name']); ?></div>
                            <div class="meta"><?php echo e($blocked['username']); ?> • <?php echo e($blocked['email']); ?></div>
                        </div>
                    </div>
                    <div class="actions">
                        <form action="<?php echo e(route('dashboard.friends.unblock', $blocked['id'])); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-blue">🔓 Buka Blokir</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty">Belum ada user yang diblokir.</div>
            <?php endif; ?>
        </div>
    </article>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\finco\resources\views/friends/index.blade.php ENDPATH**/ ?>