# Dokumentasi Proyek FinCo

**FinCo (Fintech Gamification)** — Aplikasi manajemen keuangan pribadi berbasis Laravel dengan sistem gamification.

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Arsitektur](#arsitektur)
- [Struktur Database](#struktur-database)
- [Website (UI/UX)](#website-uiux)
- [RESTful API](#restful-api)
- [Gamification System](#gamification-system)
- [Cara Menjalankan](#cara-menjalankan)

---

## Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| **Manajemen Transaksi** | Catat pemasukan & pengeluaran dengan kategori, tanggal, dan deskripsi |
| **Budget Tracker** | Buat anggaran per kategori dengan periode (harian/mingguan/bulanan/tahunan), pantau pengeluaran real-time |
| **Financial Goals** | Target tabungan dengan progres otomatis saat mencatat pemasukan |
| **Quest / Challenges** | Tantangan finansial (misal: nabung Rp300rb, hemat belanja) dengan reward XP |
| **Badge & Achievement** | Badge otomatis saat mencapai milestone tertentu |
| **Leaderboard** | Peringkat antar pengguna berdasarkan skor aktivitas keuangan |
| **Wallet Digital** | Top up, transfer ke teman, withdraw, dan riwayat transaksi wallet |
| **Pertemanan** | Cari teman, kirim/terima permintaan, blokir, transfer antar teman |
| **Admin Panel** | CRUD penuh untuk user, kategori, budget, goal, quest, badge, transaksi |
| **Auth (Login/Register)** | Autentikasi web (Laravel Breeze) & API (Sanctum Token) |

---

## Arsitektur

```
finco/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # 10 controller CRUD admin
│   │   │   ├── Auth/           # Login, register, password, verifikasi email
│   │   │   ├── User/           # API: DashboardController, TransactionController
│   │   │   ├── GamificationController.php
│   │   │   ├── UserDashboardController.php   # Web dashboard utama
│   │   │   ├── WalletController.php
│   │   │   └── FriendController.php
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php
│   │   ├── Requests/           # Form request validation
│   │   └── Resources/          # API resource transformer
│   ├── Models/                 # 14+ Eloquent models
│   └── Services/
│       └── GamificationService.php
├── database/migrations/        # 27 file migrasi
├── resources/views/            # Blade template (dashboard, auth, wallet, friends, admin, etc.)
├── routes/
│   ├── web.php                 # 70+ route web
│   ├── api.php                 # 10+ endpoint REST API
│   └── auth.php                # Route autentikasi
└── composer.json               # Laravel 12, Sanctum
```

### Pola Arsitektur

- **Monolith with API**: Satu aplikasi Laravel melayani web (Blade) dan REST API (JSON)
- **Service Pattern**: Logika gamification dipisahkan ke `GamificationService`
- **Resource Pattern**: API responses menggunakan `JsonResource` (`TransactionResource`, `DashboardResource`)
- **Repository-less direct Eloquent**: Query dilakukan langsung di controller dengan Eloquent ORM
- **Middleware**: `AdminMiddleware` untuk proteksi route admin

### Penjelasan Folder

#### `app/Http/Controllers/`

| Controller | File | Fungsi |
|-----------|------|--------|
| `UserDashboardController` | `UserDashboardController.php` | **Controller terbesar**. Menangani semua fitur web user: overview dashboard, CRUD transaksi (3 mode: general/expense/income), CRUD budget, CRUD goal, CRUD quest, leaderboard, badges, reports, settings. Juga meng-handle auto-sync budget, auto-check quest progress, dan awarding badge. |
| `WalletController` | `WalletController.php` | Menangani wallet digital: top up (simulasi via bank transfer/VA/QRIS), transfer ke teman (dengan validasi pertemanan), withdraw ke rekening bank. Setiap transaksi wallet memberi XP dan badge. |
| `FriendController` | `FriendController.php` | Manajemen pertemanan: search user, kirim/terima/tolak permintaan, hapus teman, blokir/unblock. Memberi XP +30 untuk teman pertama dan badge "Social Saver" untuk 3+ teman. |
| `GamificationController` | `GamificationController.php` | Menampilkan halaman gamification: level, XP, ringkasan finansial, transaksi terbaru. |
| `ProfileController` | `ProfileController.php` | Edit profil user (built-in Laravel Breeze). |
| `User\DashboardController` | `User/DashboardController.php` | **API endpoint** untuk dashboard user. Mengembalikan data user, gamification, ringkasan finansial, dan 10 transaksi terbaru dalam format JSON. Menggunakan `GamificationService` untuk menghitung progres. |
| `User\TransactionController` | `User/TransactionController.php` | **API endpoint** untuk CRUD transaksi. Mendukung filtering (type, category_id, month), sorting, dan pagination. Setiap transaksi baru menghitung XP via `GamificationService` dan award ke user. |

#### `app/Http/Controllers/Admin/`

10 controller untuk admin panel dengan CRUD penuh:

| Controller | Fungsi |
|-----------|--------|
| `DashboardController` | Ringkasan admin (total users, transactions, quests, budgets) |
| `UserController` | CRUD users (name, email, username, role, password) |
| `TransactionController` | CRUD semua transaksi (filter by user, category, budget) |
| `CategoryController` | CRUD kategori (name, type income/expense, icon, color) |
| `BudgetController` | CRUD budget (by user, category, period, status) |
| `GoalController` | CRUD financial goals (by user, target, status) |
| `QuestController` | CRUD quest/challenge (difficulty, reward_xp, criteria JSON) |
| `BadgeController` | CRUD badge definitions (required_level, required_xp) |
| `GamificationController` | List semua gamification profiles (read-only) |
| `ReportController` | Laporan sederhana (total users, transactions, income/expense) |

### Model

| Model | Tabel | Fungsi |
|-------|-------|--------|
| `User` | `users` | Authenticatable user dengan role (user/admin), relasi ke transactions, wallet, friendships, gamification |
| `Transaction` | `transactions` | Catatan keuangan (income/expense), belongs to User/Category/Budget, many-to-many dengan FinancialGoal |
| `Budget` | `budgets` | Anggaran per kategori dengan limit dan period. Method `recalculateSpent()` menghitung ulang pengeluaran dari transaksi real. Status: on_track / warning / exceeded. |
| `FinancialGoal` | `financial_goals` | Target tabungan dengan target_amount, current_amount, target_date, status |
| `Quest` | `challenges` | Tantangan dengan difficulty, reward_xp, criteria JSON, date range |
| `Badge` | `badges` | Definisi badge dengan required_level dan required_xp |
| `UserBadge` | `user_badges` | Pivot user-badge dengan timestamp earned_at |
| `GamificationProfile` | `gamification_profiles` | Level, XP, total badges per user. Method `addXP()` auto level-up. |
| `Wallet` | `wallets` | Saldo digital user (balance, currency IDR) |
| `WalletTransaction` | `wallet_transactions` | Riwayat mutasi wallet (top_up, transfer_out, transfer_in, payment) dengan balance_before/after |
| `TopUp` | `top_ups` | Riwayat top up dengan payment_method, reference_number, xp_earned |
| `Friendship` | `friendships` | Relasi pertemanan bidirectional dengan status (pending/accepted/rejected) |
| `FriendBlock` | `friend_blocks` | Blokir antar user |
| `Category` | `categories` | Kategori transaksi (income/expense) dengan icon dan color untuk UI |
| `TransactionGoalAllocation` | `transaction_goal_allocations` | Pivot transaction ke financial goal dengan allocated_amount |

### Service

**`GamificationService`** — Digunakan oleh API controllers:

```php
class GamificationService
{
    // Award XP ke user, auto level-up jika mencukupi
    public function awardXP(User $user, int $xp, string $source): void

    // Hitung XP berdasarkan amount transaksi (base 10 + bonus 5 per 100k)
    public function calculateTransactionXP(float $amount): int

    // Ambil progres user (level, total_xp, xp_to_next_level, progress_percentage, badges)
    public function getUserProgress(User $user): array
}
```

### Middleware

**`AdminMiddleware`** (`app/Http/Middleware/AdminMiddleware.php`):
```php
// Cek apakah user punya role 'admin', return 403 jika bukan
if (! $user || ! $user->isAdmin()) {
    abort(403, 'Akses admin diperlukan.');
}
```

Terdaftar di `bootstrap/app.php` sebagai alias `'admin'` dan dipakai di `routes/web.php`:
```php
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () { ... });
```

### API Resources

**`TransactionResource`** — Transform transaksi ke JSON:
```php
// Output: id, user (id/name/username), category (id/name/icon/color), type,
// amount, amount_formatted ("Rp xxx"), description, transaction_date, xp_earned
```

**`DashboardResource`** — Transform dashboard ke JSON:
```php
// Output: user (id/name/username/email), gamification, financial_summary,
// recent_transactions (collection of TransactionResource)
```

---

## Struktur Database

27 tabel migrasi di `database/migrations/`:

| Tabel | Fungsi |
|-------|--------|
| `users` | Data user + field custom (username, role, full_name, phone, date_of_birth) |
| `categories` | Kategori transaksi (income/expense) dengan icon & color |
| `transactions` | Catatan transaksi, terhubung ke user, category, budget |
| `budgets` | Anggaran per kategori dengan limit_amount, period (daily/weekly/monthly/yearly), spent_amount, status |
| `financial_goals` | Target tabungan user (target_amount, current_amount, target_date, status) |
| `challenges` | Quest/challenge definisi (difficulty, reward_xp, criteria JSON) |
| `badges` | Definisi badge (name, icon, required_level, required_xp) |
| `user_badges` | Pivot user-badge (earned_at) |
| `gamification_profiles` | Level, XP, total badges per user |
| `wallets` | Saldo digital user (balance, currency IDR) |
| `wallet_transactions` | Riwayat mutasi wallet (top_up, transfer_out, transfer_in, payment) |
| `top_ups` | Riwayat top up dengan payment_method, status, reference_number |
| `friendships` | Relasi pertemanan (user_id, friend_id, status) |
| `friend_blocks` | Blokir user (user_id, blocked_user_id) |
| `transaction_goal_allocations` | Alokasi income ke financial goals |
| `personal_access_tokens` | Token Sanctum untuk API auth |
| `login_streaks`, `xp_history`, `achievements`, `user_achievements`, `user_challenges` | Sistem gamification pendukung |

### Relasi Utama (Entity Relationship)

```
User ──1── GamificationProfile
User ──1── Wallet
User ──*── Transaction
User ──*── Budget
User ──*── FinancialGoal
User ──*── Quest (challenges)
User ──*── Friendship ──*── User
User ──*── Badge ──*── User (via user_badges)

Transaction ──*──1 Category
Transaction ──*──1 Budget
Transaction ──*──* FinancialGoal (via transaction_goal_allocations)

Wallet ──*── WalletTransaction
Wallet ──*── TopUp
```

---

## Website (UI/UX)

Semua route web di `routes/web.php` (70+ route).

### Halaman User (auth required)

| Route | Method | Deskripsi |
|-------|--------|-----------|
| `/dashboard` | `overview` | Ringkasan keuangan (balance, income/expense, level, XP) + quick action + transaksi terbaru |
| `/dashboard/transactions` | CRUD | Transaksi dengan 3 mode: General, Expense (linked ke budget), Income (linked ke goal) |
| `/dashboard/budgets` | CRUD | Budget tracker dengan progress bar, status (on track/warning/exceeded), auto-sync dari transaksi |
| `/dashboard/goals` | CRUD | Financial goals dengan progress, auto-allocated dari income |
| `/dashboard/quests` | CRUD | Join quest template, buat quest sendiri, tracking progress |
| `/dashboard/badges` | List | Katalog badge + progres pencapaian (XP & level requirement) |
| `/dashboard/leaderboard` | List | Peringkat user berdasarkan score multi-faktor |
| `/dashboard/wallet` | View | Saldo, top up, transfer, withdraw + riwayat transaksi wallet |
| `/dashboard/friends` | Manage | Cari teman, kirim/terima/tolak permintaan, blokir/unblock |
| `/dashboard/reports` | View | Laporan keuangan |
| `/dashboard/settings` | View | Pengaturan akun |

### Halaman Admin (auth + admin middleware)

| Prefix | CRUD |
|--------|------|
| `/admin/users` | Create, Read, Update, Delete users |
| `/admin/transactions` | CRUD semua transaksi semua user |
| `/admin/categories` | CRUD kategori transaksi |
| `/admin/budgets` | CRUD budget |
| `/admin/goals` | CRUD financial goals |
| `/admin/quests` | CRUD quest/challenge |
| `/admin/badges` | CRUD badge definitions |

### Fitur Khusus Transaksi Web di `UserDashboardController`

**3 Mode Input Transaksi** (di method `storeTransaction`):

1. **General** — Pilih tipe (income/expense), kategori bebas
2. **Expense** — Pilih budget aktif terlebih dahulu → kategori otomatis terkunci sesuai budget. Setelah simpan, auto-sync ke budget (`syncBudgetWithTransaction`).
3. **Income** — Pilih financial goal → pemasukan auto-allocated ke goal. Jika goal tercapai, dapat badge "Goal Master" + bonus XP 100.

**Auto-sync Budget** (`syncBudgetWithTransaction`):
- Setiap transaksi expense menghitung ulang `spent_amount` budget terkait
- Budget warning di >= 80%, exceeded di > 100% (denda -20 XP)
- Notifikasi ditampilkan sebagai flash message

**Quest Progress Tracking** (`syncQuestProgressFromTransaction`):
- Setiap transaksi dicek terhadap quest aktif user
- Quest otomatis marked completed jika kriteria terpenuhi
- Reward XP diberikan otomatis

**Leaderboard Score Calculation**:
```
Score = (transactions × 4) + (active_days × 3) + (streak × 15) 
      + (goal_progress × 2) + (completed_quests × 20) + (XP × 0.3) + (badges × 30)
```

### View (Blade)

Halaman dashboard utama ada di `resources/views/dashboard.blade.php` — file blade tunggal yang menangani semua fitur user (overview, transactions, budgets, goals, quests, badges, leaderboard, reports, settings). Menggunakan CSS murni (tanpa framework CSS eksternal) dengan desain modern, gradient, responsive.

View terpisah untuk:
- `auth/*` — Login, register, forgot/reset password
- `wallet/*` — Wallet, top up, withdraw, transfer
- `friends/*` — Manajemen teman
- `profile/*` — Edit profil
- `admin/*` — Admin dashboard & CRUD forms

---

## RESTful API

Semua endpoint API di `routes/api.php` dengan prefix `/api`.

### Autentikasi

| Method | Endpoint | Controller | Deskripsi |
|--------|----------|-----------|-----------|
| POST | `/api/register` | Closure di `api.php` | Register user + buat GamificationProfile + return Sanctum token |
| POST | `/api/login` | Closure di `api.php` | Login dengan email & password, return token |

Detail register:
```php
// Validasi: name, email (unique), username (unique), password (min:8)
// Create user dengan role 'user'
// Create GamificationProfile
// Return: { success, token, user }
```

Detail login:
```php
// Cari user by email, cek password dengan Hash::check
// Return: { success, token, user } atau 401
```

### Endpoint Protected (auth:sanctum)

Semua route di bawah `/api/user` — membutuhkan header `Authorization: Bearer <token>`.

| Method | Endpoint | Controller | Deskripsi |
|--------|----------|-----------|-----------|
| GET | `/api/user/dashboard` | `DashboardController@index` | Dashboard lengkap |
| GET | `/api/user/transactions` | `TransactionController@index` | List transaksi (filterable, sortable, paginated) |
| POST | `/api/user/transactions` | `TransactionController@store` | Buat transaksi + hitung XP via GamificationService |
| GET | `/api/user/transactions/{id}` | `TransactionController@show` | Detail transaksi |
| PUT | `/api/user/transactions/{id}` | `TransactionController@update` | Update transaksi |
| DELETE | `/api/user/transactions/{id}` | `TransactionController@destroy` | Hapus transaksi |

### Endpoint Public

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/categories` | List semua kategori |

### Filter & Parameter API

**GET `/api/user/transactions`** mendukung parameter:

| Parameter | Contoh | Fungsi |
|-----------|--------|--------|
| `type` | `income` / `expense` | Filter by tipe |
| `category_id` | `3` | Filter by kategori |
| `month` | `2026-06` | Filter by bulan |
| `sort_by` | `amount` / `transaction_date` | Kolom sorting (default: transaction_date) |
| `sort_order` | `asc` / `desc` | Arah sorting (default: desc) |
| `per_page` | `25` | Item per halaman (default: 15) |

### Contoh Response API

**Success Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "user": { "id": 1, "name": "John Doe", "username": "john" },
    "category": { "id": 1, "name": "Gaji", "icon": "💰", "color": "#16a34a" },
    "type": "income",
    "amount": 5000000,
    "amount_formatted": "Rp 5.000.000",
    "description": "Gaji bulan Juni",
    "transaction_date": "2026-06-01",
    "xp_earned": 35,
    "created_at": "2026-06-01T12:00:00.000000Z"
  }
}
```

**Dashboard Response**:
```json
{
  "success": true,
  "data": {
    "user": { "id": 1, "name": "John", "username": "john", "email": "john@mail.com" },
    "gamification": {
      "level": 5,
      "total_xp": 450,
      "xp_to_next_level": 500,
      "xp_needed": 50,
      "progress_percentage": 90,
      "badges": 3,
      "achievements": 2
    },
    "financial_summary": {
      "total_income": 15000000,
      "total_expense": 8500000,
      "balance": 6500000,
      "monthly_income": 5000000,
      "monthly_expense": 2800000,
      "monthly_balance": 2200000
    },
    "recent_transactions": [ ... ]
  }
}
```

**Error Response**:
```json
{
  "success": false,
  "message": "Email atau password salah"
}
```

---

## Gamification System

### Level & XP

Level dihitung dengan rumus: `total_xp / 100 + 1`

**Sumber XP**:

| Aksi | XP | Di-handle di |
|------|----|-------------|
| Transaksi (via API) | `10 + floor(amount/100000) × 5` | `GamificationService@calculateTransactionXP` |
| Transaksi expense (Web) | `1` | `UserDashboardController@calculateXp` |
| Transaksi income (Web) | `floor(amount/10000)` | `UserDashboardController@calculateXp` |
| Top Up pertama | `70` (20 base + 50 bonus) | `WalletController@topup` |
| Top Up selanjutnya | `20` | `WalletController@topup` |
| Transfer ke teman | `15` | `WalletController@transfer` |
| Quest selesai | Sesuai reward (60-100) | `UserDashboardController@syncQuestProgressFromTransaction` |
| Budget berhasil | `100` | `UserDashboardController@checkAndAwardBudgetBadges` |
| Budget exceeded | `-20` (denda) | `UserDashboardController@syncBudgetWithTransaction` |
| Goal selesai | `100` (bonus) | `UserDashboardController@storeTransaction` |
| Teman pertama | `30` | `FriendController@accept` |

### Badge System

Badge didefinisikan di tabel `badges` dan diraih otomatis saat syarat terpenuhi:

| Badge | Syarat | Kode |
|-------|--------|------|
| First Top Up | Top up pertama kali | `WalletController@topup` |
| Top Up Master | 5+ top up | `WalletController@topup` |
| Generous | 3+ transfer | `WalletController@transfer` |
| Social Saver | 3+ teman accepted | `FriendController@accept` |
| Budget Master | Budget selesai tanpa exceeded | `UserDashboardController@checkAndAwardBudgetBadges` |
| Goal Master | Menyelesaikan financial goal | `UserDashboardController@storeTransaction` |

Badge lain (dengan required_level & required_xp) bisa dibuat melalui admin panel.

Method `awardBadge()` reusable di beberapa controller:
```php
private function awardBadge(int $userId, string $badgeName): void
{
    // Cari badge by name
    // Cek apakah user sudah punya badge ini (encegah duplikasi)
    // Jika belum, create UserBadge record
}
```

### Leaderboard

Leaderboard dihitung real-time di method `leaderboardRows()`:
```php
// Untuk setiap user:
Score = (transactions × 4) + (active_days × 3) + (streak × 15) 
      + (goal_progress × 2) + (completed_quests × 20) + (XP × 0.3) + (badges × 30)

// Di-sort descending by score
```

---

## Cara Menjalankan

### Prasyarat

- PHP 8.2+
- Composer
- Node.js & npm
- Database (SQLite / MySQL / PostgreSQL)

### Instalasi

```bash
# 1. Clone repositori
git clone <url-repo> finco
cd finco

# 2. Install dependencies PHP
composer install

# 3. Install dependencies frontend
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi database di .env
#    Default: SQLite (database/database.sqlite)
#    Untuk MySQL, ubah DB_CONNECTION=mysql, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 6. Jalankan migrasi & seeder
php artisan migrate --seed

# 7. Build frontend assets
npm run build

# 8. Jalankan development server
php artisan serve
#    Buka http://localhost:8000

# 9. (Terminal terpisah) Compile assets untuk development
npm run dev
```

### Testing

```bash
composer test
# atau
php artisan test
```
