<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinCo | Financial Gamification</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f9fcff;
            --panel: #ffffff;
            --text: #122033;
            --muted: #55708d;
            --line: #dbe8f6;
            --blue-600: #2563eb;
            --blue-500: #3b82f6;
            --shadow: 0 16px 45px rgba(37, 99, 235, 0.12);
            --radius: 18px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 5% 0%, #f0f7ff 0%, transparent 40%),
                radial-gradient(circle at 90% 10%, #eaf2ff 0%, transparent 35%),
                linear-gradient(165deg, #ffffff 0%, #f8fbff 55%, #eef5ff 100%);
            min-height: 100vh;
        }

        .container {
            width: min(1140px, 92%);
            margin: 0 auto;
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.86);
            border-bottom: 1px solid rgba(219, 232, 246, 0.9);
        }

        .nav-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.9rem 0;
        }

        .brand {
            font-family: 'Sora', sans-serif;
            font-size: 1.28rem;
            font-weight: 800;
            letter-spacing: 0.4px;
            text-decoration: none;
            color: var(--text);
        }

        .brand span { color: inherit; }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--muted);
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 999px;
            transition: 0.2s ease;
        }

        .nav-links a:hover {
            color: var(--blue-600);
            background: #eef5ff;
        }

        .nav-auth {
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .btn {
            border: 1px solid transparent;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            padding: 0.72rem 1.08rem;
            font-weight: 600;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
            cursor: pointer;
        }

        .btn:hover { transform: translateY(-1px); }

        .btn-outline {
            border-color: #bfdbfe;
            color: var(--blue-600);
            background: linear-gradient(180deg, #ffffff 0%, #f0f7ff 100%);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--blue-500) 0%, var(--blue-600) 100%);
            color: #fff;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.28);
        }

        .hero {
            padding: 4.6rem 0 2.2rem;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 1.4rem;
            align-items: center;
        }

        .eyebrow {
            display: inline-flex;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 0.36rem 0.82rem;
            color: var(--blue-600);
            background: #f3f8ff;
            font-weight: 600;
            font-size: 0.86rem;
            margin-bottom: 1rem;
        }

        .hero h1 {
            font-family: 'Sora', sans-serif;
            line-height: 1.1;
            font-size: clamp(2rem, 5vw, 3.35rem);
            margin-bottom: 0.9rem;
        }

        .hero h1 .text-blue { color: var(--blue-600); }

        .hero p {
            color: var(--muted);
            font-size: 1.06rem;
            margin-bottom: 1.2rem;
            max-width: 58ch;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.72rem;
            margin-bottom: 1.35rem;
        }

        .hero-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.75rem;
        }

        .meta-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 0.85rem;
            box-shadow: 0 8px 24px rgba(30, 64, 175, 0.06);
        }

        .meta-value {
            font-family: 'Sora', sans-serif;
            color: var(--blue-600);
            font-size: 1.22rem;
            margin-bottom: 0.2rem;
        }

        .meta-label {
            color: var(--muted);
            font-size: 0.92rem;
        }

        .hero-showcase {
            background: linear-gradient(165deg, #ffffff 0%, #eff6ff 100%);
            border: 1px solid var(--line);
            border-radius: 24px;
            padding: 1.2rem;
            box-shadow: var(--shadow);
        }

        .showcase-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .dot-group {
            display: flex;
            gap: 0.36rem;
        }

        .dot {
            width: 0.52rem;
            height: 0.52rem;
            border-radius: 50%;
            background: #bfdbfe;
        }

        .showcase-title {
            font-weight: 700;
            color: #17407f;
        }

        .xp-box {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 0.9rem;
            margin-bottom: 0.9rem;
        }

        .xp-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.52rem;
            color: #264f86;
        }

        .xp-track {
            height: 10px;
            border-radius: 999px;
            background: #dbeafe;
            overflow: hidden;
        }

        .xp-fill {
            width: 72%;
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #facc15, #f59e0b);
        }

        .showcase-list {
            display: grid;
            gap: 0.58rem;
        }

        .showcase-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 0.55rem 0.72rem;
            color: #355b89;
            font-size: 0.92rem;
        }

        section { padding: 2.5rem 0; }

        .section-head {
            text-align: center;
            margin-bottom: 1.3rem;
        }

        .section-head h2 {
            font-family: 'Sora', sans-serif;
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            margin-bottom: 0.5rem;
        }

        .section-head p {
            color: var(--muted);
            max-width: 65ch;
            margin: 0 auto;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.9rem;
        }

        .feature-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 1rem;
            box-shadow: 0 10px 24px rgba(30, 64, 175, 0.06);
        }

        .feature-icon {
            width: 2.35rem;
            height: 2.35rem;
            border-radius: 12px;
            background: linear-gradient(140deg, #eff6ff 0%, #dbeafe 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.22rem;
            margin-bottom: 0.58rem;
        }

        .feature-card h3 {
            margin-bottom: 0.42rem;
            font-size: 1.02rem;
        }

        .feature-card p {
            color: var(--muted);
            font-size: 0.94rem;
            line-height: 1.45;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.85rem;
        }

        .step {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 1rem;
            box-shadow: 0 10px 24px rgba(30, 64, 175, 0.05);
        }

        .step-number {
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #dbeafe;
            color: #1d4ed8;
            font-weight: 700;
            margin-bottom: 0.65rem;
        }

        .cta {
            margin: 2.2rem 0 2rem;
            background: linear-gradient(135deg, #ffffff 0%, #eaf3ff 100%);
            border: 1px solid var(--line);
            border-radius: 24px;
            padding: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 0.9rem;
            align-items: center;
            box-shadow: var(--shadow);
        }

        .cta h3 {
            font-family: 'Sora', sans-serif;
            margin-bottom: 0.2rem;
        }

        .cta p { color: var(--muted); }

        footer {
            border-top: 1px solid var(--line);
            margin-top: 2rem;
            padding: 1.2rem 0 1.8rem;
            color: #6580a0;
            font-size: 0.94rem;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, #f3f8ff 100%);
        }

        .footer-wrap {
            display: flex;
            justify-content: space-between;
            gap: 0.7rem;
            flex-wrap: wrap;
        }

        .mobile-toggle {
            display: none;
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 10px;
            width: 42px;
            height: 42px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .mobile-toggle span {
            display: block;
            width: 18px;
            height: 2px;
            background: #32537d;
            margin: 2px 0;
        }

        @media (max-width: 1024px) {
            .hero-grid,
            .feature-grid,
            .steps {
                grid-template-columns: 1fr;
            }

            .hero { padding-top: 3.5rem; }
            .hero-meta { grid-template-columns: 1fr; }
        }

        @media (max-width: 860px) {
            .mobile-toggle { display: inline-flex; }

            .nav-links,
            .nav-auth { display: none; }

            .nav-wrap.open { flex-wrap: wrap; }

            .nav-wrap.open .nav-links {
                display: flex;
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.2rem;
                padding-top: 0.3rem;
            }

            .nav-wrap.open .nav-auth {
                display: flex;
                width: 100%;
                padding-top: 0.2rem;
            }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container nav-wrap" id="navWrap">
            <a href="#beranda" class="brand">FIN<span>CO</span></a>

            <button class="mobile-toggle" id="mobileToggle" type="button" aria-label="Buka menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>

            <ul class="nav-links">
                <li><a href="#beranda">Beranda</a></li>
                <li><a href="#fitur">Fitur</a></li>
                <li><a href="#cara-kerja">Cara Kerja</a></li>
            </ul>

            <div class="nav-auth">
                @auth
                    <a class="btn btn-outline" href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    @if (Route::has('login'))
                        <a class="btn btn-outline" href="{{ route('login') }}">Masuk</a>
                    @endif
                    @if (Route::has('register'))
                        <a class="btn btn-primary" href="{{ route('register') }}">Daftar</a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <main>
        <section class="hero" id="beranda">
            <div class="container hero-grid">
                <div>
                    <span class="eyebrow">Financial Gamification for Daily Growth</span>
                    <h1>Kelola uang lebih rapi, <span class="text-blue">naik level setiap hari.</span></h1>
                    <p>FinCo menggabungkan pencatatan finansial, challenge harian, dan sistem XP supaya kebiasaan keuangan terasa lebih ringan, konsisten, dan seru.</p>
                    <div class="hero-actions">
                        @if (Route::has('register'))
                            <a class="btn btn-primary" href="{{ route('register') }}">Mulai Gratis</a>
                        @endif
                        <a class="btn btn-outline" href="#fitur">Lihat Fitur</a>
                    </div>
                    <div class="hero-meta">
                        <div class="meta-card">
                            <div class="meta-value">+50 XP</div>
                            <div class="meta-label">Bonus registrasi</div>
                        </div>
                        <div class="meta-card">
                            <div class="meta-value">10 XP</div>
                            <div class="meta-label">Base XP per transaksi</div>
                        </div>
                        <div class="meta-card">
                            <div class="meta-value">Level 1-8</div>
                            <div class="meta-label">Progress jangka panjang</div>
                        </div>
                    </div>
                </div>

                <aside class="hero-showcase" aria-label="Preview dashboard FinCo">
                    <div class="showcase-top">
                        <div class="dot-group"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>
                        <span class="showcase-title">Preview Dashboard</span>
                    </div>
                    <div class="xp-box">
                        <div class="xp-row"><span>Level 8 - Menengah</span><strong>1,420 XP</strong></div>
                        <div class="xp-track"><div class="xp-fill"></div></div>
                    </div>
                    <div class="showcase-list">
                        <div class="showcase-item"><span>&#128188; Gaji Bulanan</span><strong>+35 XP</strong></div>
                        <div class="showcase-item"><span>&#128722; Belanja Mingguan</span><strong>+15 XP</strong></div>
                        <div class="showcase-item"><span>&#128293; Login Streak 5 Hari</span><strong>+5 XP</strong></div>
                        <div class="showcase-item"><span>&#127919; Goal Tercapai</span><strong>+100 XP</strong></div>
                    </div>
                </aside>
            </div>
        </section>

        <section id="fitur">
            <div class="container">
                <div class="section-head">
                    <h2>Fitur Utama FinCo</h2>
                    <p>Dirancang untuk bantu kamu mencatat keuangan sekaligus membangun kebiasaan finansial lewat sistem progres yang memotivasi.</p>
                </div>
                <div class="feature-grid">
                    <article class="feature-card">
                        <div class="feature-icon">&#128176;</div>
                        <h3>Transaction Tracker</h3>
                        <p>Catat income dan expense dengan cepat, lengkap dengan kategori dan histori yang mudah dipantau.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">&#127918;</div>
                        <h3>XP & Level System</h3>
                        <p>Setiap aksi finansial bernilai XP. Naik level bertahap dari Pemula sampai Expert.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">&#127942;</div>
                        <h3>Badge & Achievement</h3>
                        <p>Dapatkan lencana saat mencapai milestone seperti streak harian dan target transaksi.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">&#128202;</div>
                        <h3>Monthly Report</h3>
                        <p>Lihat ringkasan bulanan: total income, total expense, balance, dan pola pengeluaran.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">&#127919;</div>
                        <h3>Financial Goals</h3>
                        <p>Buat target keuangan dan pantau progres sampai goal tercapai dengan reward XP tambahan.</p>
                    </article>
                    <article class="feature-card">
                        <div class="feature-icon">&#128737;&#65039;</div>
                        <h3>Admin Control</h3>
                        <p>Admin bisa memonitor transaksi, kelola data user, dan melihat statistik keseluruhan.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="cara-kerja">
            <div class="container">
                <div class="section-head">
                    <h2>Cara Kerja Singkat</h2>
                    <p>Tiga langkah sederhana untuk memulai perjalanan finansial yang lebih sehat dan konsisten.</p>
                </div>
                <div class="steps">
                    <article class="step">
                        <span class="step-number">1</span>
                        <h3>Buat Akun</h3>
                        <p>Daftar gratis dan langsung dapat bonus registrasi +50 XP untuk kickstart progress kamu.</p>
                    </article>
                    <article class="step">
                        <span class="step-number">2</span>
                        <h3>Catat Aktivitas Finansial</h3>
                        <p>Tambah transaksi harian, pilih kategori, dan biarkan FinCo hitung XP secara otomatis.</p>
                    </article>
                    <article class="step">
                        <span class="step-number">3</span>
                        <h3>Naik Level</h3>
                        <p>Selesaikan challenge, jaga streak, dan naik level sambil membangun kebiasaan keuangan sehat.</p>
                    </article>
                </div>
            </div>
        </section>

        <div class="container">
            <section class="cta" aria-label="Call to action">
                <div>
                    <h3>Mulai bangun kebiasaan finansial yang lebih kuat hari ini.</h3>
                    <p>Gabungkan disiplin finansial dan motivasi gamification dalam satu dashboard yang ringan.</p>
                </div>
                <div class="hero-actions">
                    @if (Route::has('register'))
                        <a class="btn btn-primary" href="{{ route('register') }}">Buat Akun Sekarang</a>
                    @endif
                    @if (Route::has('login'))
                        <a class="btn btn-outline" href="{{ route('login') }}">Sudah Punya Akun</a>
                    @endif
                </div>
            </section>
        </div>
    </main>

    <footer>
        <div class="container footer-wrap">
            <p>&copy; {{ now()->year }} FinCo. Financial Gamification Web App.</p>
            <p>Dibangun untuk bantu kamu naik level dalam manajemen keuangan.</p>
        </div>
    </footer>

    <script>
        const mobileToggle = document.getElementById('mobileToggle');
        const navWrap = document.getElementById('navWrap');

        if (mobileToggle && navWrap) {
            mobileToggle.addEventListener('click', () => {
                const isOpen = navWrap.classList.toggle('open');
                mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        }
    </script>
</body>
</html>
