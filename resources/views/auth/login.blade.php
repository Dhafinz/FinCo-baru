<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | FinCo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --text: #0f172a;
            --muted: #64748b;
            --line: #dbe8f6;
            --blue: #2563eb;
            --blue-dark: #1e40af;
            --bg: #eff6ff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1rem;
            background:
                radial-gradient(circle at 10% 10%, rgba(59, 130, 246, 0.25), transparent 35%),
                radial-gradient(circle at 90% 90%, rgba(37, 99, 235, 0.2), transparent 30%),
                var(--bg);
            font-family: 'Outfit', sans-serif;
            color: var(--text);
        }

        .card {
            width: min(460px, 100%);
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: 0 24px 64px rgba(37, 99, 235, 0.16);
            padding: 2rem;
        }

        .brand {
            display: inline-block;
            margin-bottom: 1rem;
            text-decoration: none;
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            color: var(--text);
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

        .field { margin-bottom: 0.95rem; }

        .field label {
            display: block;
            margin-bottom: 0.38rem;
            font-weight: 600;
            font-size: 0.88rem;
        }

        .field input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 0.72rem 0.82rem;
            background: #f8fbff;
            outline: none;
            font-size: 0.95rem;
            color: var(--text);
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

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            margin-bottom: 1.1rem;
            font-size: 0.88rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: var(--muted);
        }

        .forgot {
            color: var(--blue);
            text-decoration: none;
            font-weight: 600;
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            padding: 0.78rem 1rem;
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
            font-weight: 700;
            text-decoration: none;
        }

        .switch a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <main class="card">
        <a href="{{ url('/') }}" class="brand">FIN<span>CO</span></a>
        <h1>Selamat Datang</h1>
        <p class="sub">Masuk ke akun FinCo kamu.</p>

        @if (session('status'))
            <div class="error-box" style="background:#eff6ff;color:#1e40af;border-color:#bfdbfe;">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="error-box">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="field-wrap">
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
                    <button class="toggle" type="button" data-toggle="#password">Lihat</button>
                </div>
            </div>

            <div class="row">
                <label class="remember">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="forgot" href="{{ route('password.request') }}">Lupa password?</a>
                @endif
            </div>

            <button class="btn" type="submit">Masuk</button>
        </form>

        <p class="switch">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
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
