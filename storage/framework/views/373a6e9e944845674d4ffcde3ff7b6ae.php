<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | FinCo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --text: #0f172a;
            --muted: #64748b;
            --line: #dbe8f6;
            --blue: #2563eb;
            --bg: #eff6ff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            padding: 1rem;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at 90% 10%, rgba(59, 130, 246, 0.2), transparent 35%),
                radial-gradient(circle at 10% 90%, rgba(37, 99, 235, 0.22), transparent 33%),
                var(--bg);
            font-family: 'Outfit', sans-serif;
            color: var(--text);
        }

        .card {
            width: min(650px, 100%);
            background: #fff;
            border-radius: 24px;
            border: 1px solid var(--line);
            box-shadow: 0 24px 64px rgba(37, 99, 235, 0.16);
            padding: 1.9rem;
        }

        .brand {
            display: inline-block;
            margin-bottom: 1rem;
            text-decoration: none;
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            color: #0f172a;
            font-size: 1.2rem;
        }

        .brand span { color: inherit; }

        h1 {
            font-family: 'Sora', sans-serif;
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .sub {
            color: var(--muted);
            margin-bottom: 1.2rem;
            font-size: 0.95rem;
        }

        .error-box {
            margin-bottom: 1rem;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 0.7rem 0.85rem;
            font-size: 0.9rem;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }

        .field { margin-bottom: 0.9rem; }

        .field label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.88rem;
            font-weight: 600;
        }

        .field input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 0.72rem 0.82rem;
            background: #f8fbff;
            outline: none;
            font-size: 0.94rem;
        }

        .field input:focus {
            border-color: var(--blue);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .field-wrap { position: relative; }

        .toggle {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .full { grid-column: span 2; }

        .terms {
            display: flex;
            align-items: flex-start;
            gap: 0.45rem;
            color: var(--muted);
            font-size: 0.87rem;
            margin-bottom: 1rem;
        }

        .terms a {
            color: var(--blue);
            text-decoration: none;
            font-weight: 600;
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            padding: 0.8rem 1rem;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transform: translateY(-1px);
        }

        .switch {
            text-align: center;
            margin-top: 1rem;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .switch a {
            color: var(--blue);
            text-decoration: none;
            font-weight: 700;
        }

        .switch a:hover { text-decoration: underline; }

        @media (max-width: 700px) {
            .grid { grid-template-columns: 1fr; }
            .full { grid-column: span 1; }
        }
    </style>
</head>
<body>
    <main class="card">
        <a href="<?php echo e(url('/')); ?>" class="brand">FIN<span>CO</span></a>
        <h1>Buat Akun FinCo</h1>
        <p class="sub">Daftar dan mulai perjalanan finansial kamu dengan sistem level.</p>

        <?php if($errors->any()): ?>
            <div class="error-box"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>

            <div class="grid">
                <div class="field">
                    <label for="full_name">Nama Lengkap</label>
                    <input id="full_name" type="text" name="full_name" value="<?php echo e(old('full_name')); ?>" required autocomplete="name" placeholder="Nama lengkap kamu">
                </div>

                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="<?php echo e(old('username')); ?>" required placeholder="contoh: finco_user">
                </div>

                <div class="field full">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="username" placeholder="nama@email.com">
                </div>

                <div class="field">
                    <label for="phone">No. Telepon (opsional)</label>
                    <input id="phone" type="text" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="08xxxxxxxxxx">
                </div>

                <div class="field">
                    <label for="date_of_birth">Tanggal Lahir (opsional)</label>
                    <input id="date_of_birth" type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth')); ?>">
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="field-wrap">
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                        <button class="toggle" type="button" data-toggle="#password">Lihat</button>
                    </div>
                </div>

                <div class="field">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="field-wrap">
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
                        <button class="toggle" type="button" data-toggle="#password_confirmation">Lihat</button>
                    </div>
                </div>

                <label class="terms full">
                    <input id="terms" type="checkbox" name="terms" value="1" required>
                    <span>Saya setuju dengan <a href="#">Syarat dan Ketentuan</a> serta <a href="#">Kebijakan Privasi</a>.</span>
                </label>

                <div class="full">
                    <button class="btn" type="submit">Daftar Sekarang</button>
                </div>
            </div>
        </form>

        <p class="switch">Sudah punya akun? <a href="<?php echo e(route('login')); ?>">Masuk</a></p>
    </main>

    <script>
        document.querySelectorAll('[data-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.querySelector(button.getAttribute('data-toggle'));
                if (!input) return;
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                button.textContent = isPassword ? 'Sembunyi' : 'Lihat';
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\finco\resources\views/auth/register.blade.php ENDPATH**/ ?>