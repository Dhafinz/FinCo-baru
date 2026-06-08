# DOKUMENTASI FINCO — Analisis Database, Relasi JOIN, & User Flow

---

## A. RANGKUMAN CEK KETENTUAN UKL

### ✅ Terpenuhi

| Ketentuan | Status | Detail |
|-----------|--------|--------|
| Framework Laravel | ✅ | Laravel 12 |
| Migration Database | ✅ | 28 file migration |
| Relasi tabel (JOIN) | ✅ | 12+ foreign key, Eloquent relationships |
| Minimal 2 CRUD | ✅ | 7 CRUD: Users, Transactions, Categories, Budgets, Goals, Quests, Badges |
| Authentication | ✅ | Laravel Breeze + role admin/user |
| Minimal 2 API Endpoint | ✅ | 9 endpoint: register, login, categories, dashboard, transactions CRUD |
| Github | ✅ | Remote: https://github.com/Dhafinz/FinCo-baru.git |
| Histori commit | ✅ | 10+ commit dengan pesan relevan |

### ❌ Perlu Dilengkapi

| Output | Status | Keterangan |
|--------|--------|------------|
| ERD | ❌ | Perlu dibuat (lihat prompt di bawah) |
| Dokumentasi Postman | ❌ | Perlu export collection & uji endpoint |
| PPT Presentasi | ❌ | Output non-teknis |
| Video Demo | ❌ | Jika diminta penguji |

---

## B. STRUKTUR DATABASE & RELASI

### Group 1 — Auth / Core

| Tabel | Kolom Kunci | Foreign Key |
|-------|-------------|-------------|
| **users** | id, name, email, password, username (UNIQUE), full_name, phone, date_of_birth, role (user/admin) | — |
| **password_reset_tokens** | email (PK), token | — |
| **sessions** | id (PK), user_id, payload, last_activity | user_id → users.id |
| **personal_access_tokens** | id, tokenable_id, tokenable_type, token (UNIQUE), abilities | Polymorphic |

### Group 2 — Financial

| Tabel | Kolom Kunci | Foreign Key |
|-------|-------------|-------------|
| **categories** | id, name, type (income/expense), icon, color | — |
| **transactions** | id, user_id, category_id, budget_id, type, amount, description, transaction_date, xp_earned | user_id → users.id CASCADE, category_id → categories.id SET NULL, budget_id → budgets.id SET NULL |
| **budgets** | id, user_id, category, category_id, limit_amount, spent_amount, period, period_start, period_end, is_active, status | user_id → users.id CASCADE |
| **financial_goals** | id, user_id, name, target_amount, current_amount, target_date, status | user_id → users.id CASCADE |
| **transaction_goal_allocations** | id, transaction_id (UNIQUE), goal_id, allocated_amount | transaction_id → transactions.id CASCADE, goal_id → financial_goals.id CASCADE |

### Group 3 — Gamification

| Tabel | Kolom Kunci | Foreign Key |
|-------|-------------|-------------|
| **gamification_profiles** | id, user_id (UNIQUE), current_level, total_xp, current_streak, last_login_date | user_id → users.id CASCADE |
| **badges** | id, name (UNIQUE), description, icon, required_level, required_xp | — |
| **user_badges** | id, user_id, badge_id, earned_at | user_id → users.id CASCADE, badge_id → badges.id CASCADE |
| **challenges** (alias Quest) | id, user_id, name, description, difficulty, reward_xp, start_date, end_date, status, criteria | user_id → users.id CASCADE |

### Group 4 — Wallet

| Tabel | Kolom Kunci | Foreign Key |
|-------|-------------|-------------|
| **wallets** | id, user_id (UNIQUE), balance, currency, is_active | user_id → users.id CASCADE |
| **wallet_transactions** | id, user_id, wallet_id, type, amount, balance_before, balance_after, status, reference_number | user_id → users.id CASCADE, wallet_id → wallets.id CASCADE |
| **top_ups** | id, user_id, wallet_id, amount, payment_method, status, reference_number, xp_earned | user_id → users.id CASCADE, wallet_id → wallets.id CASCADE |

### Group 5 — Social

| Tabel | Kolom Kunci | Foreign Key |
|-------|-------------|-------------|
| **friendships** | id, user_id, friend_id, status (pending/accepted/rejected) | user_id → users.id CASCADE, friend_id → users.id CASCADE |
| **friend_blocks** | id, user_id, blocked_user_id | user_id → users.id CASCADE, blocked_user_id → users.id CASCADE |

### Relasi One-to-Many
- **users** 1──M **transactions** (satu user punya banyak transaksi)
- **users** 1──M **budgets** (satu user punya banyak budget)
- **users** 1──M **financial_goals** (satu user punya banyak goal)
- **users** 1──M **challenges** (satu user punya banyak quest)
- **users** 1──M **wallet_transactions**
- **users** 1──M **top_ups**
- **users** 1──M **user_badges**
- **users** 1──M **friendships** (sebagai pengirim)
- **users** 1──M **friendships** (sebagai penerima)
- **users** 1──M **friend_blocks**
- **categories** 1──M **transactions**
- **budgets** 1──M **transactions**
- **transactions** 1──M **transaction_goal_allocations**
- **financial_goals** 1──M **transaction_goal_allocations**
- **badges** 1──M **user_badges**
- **wallets** 1──M **wallet_transactions**
- **wallets** 1──M **top_ups**

### Relasi One-to-One
- **users** 1──1 **gamification_profiles** (UNIQUE user_id)
- **users** 1──1 **wallets** (UNIQUE user_id)

### Relasi Many-to-Many (via Pivot)
- **transactions** M──M **financial_goals** (via *transaction_goal_allocations*)
- **users** M──M **badges** (via *user_badges*)

---

## C. CONTOH QUERY JOIN (via Eloquent)

### 1. Transactions JOIN Users (ambil transaksi dengan data user)
```php
Transaction::with('user')->latest()->take(8)->get();
// SQL: SELECT * FROM transactions 
//      LEFT JOIN users ON transactions.user_id = users.id 
//      ORDER BY created_at DESC LIMIT 8
```

### 2. Transactions JOIN Categories (transaksi dengan kategori)
```php
$user->transactions()->with(['category'])->get();
// SQL: SELECT * FROM transactions 
//      LEFT JOIN categories ON transactions.category_id = categories.id 
//      WHERE transactions.user_id = ?
```

### 3. Friendships JOIN Users (daftar permintaan pertemanan)
```php
Friendship::with('user:id,name,username')
    ->where('friend_id', $user->id)
    ->where('status', 'pending')
    ->get();
// SQL: SELECT * FROM friendships 
//      LEFT JOIN users ON friendships.user_id = users.id 
//      WHERE friendships.friend_id = ? AND friendships.status = 'pending'
```

### 4. UserBadges JOIN Badges (badge yang dimiliki user)
```php
UserBadge::with('badge')->where('user_id', $user->id)->get();
// SQL: SELECT * FROM user_badges 
//      LEFT JOIN badges ON user_badges.badge_id = badges.id 
//      WHERE user_badges.user_id = ?
```

### 5. Categories WITH COUNT Transactions (distribusi transaksi per kategori)
```php
Category::withCount('transactions')->get();
// SQL: SELECT categories.*, 
//      (SELECT COUNT(*) FROM transactions 
//       WHERE transactions.category_id = categories.id) AS transactions_count
//      FROM categories
```

### 6. Wallet Transfer — Update 2 User Sekaligus (atomic)
```php
DB::transaction(function () use ($sender, $receiver, $amount) {
    $sender->decrement('balance', $amount);      // UPDATE wallets SET balance = balance - ? WHERE id = ?
    $receiver->increment('balance', $amount);     // UPDATE wallets SET balance = balance + ? WHERE id = ?
    WalletTransaction::create([...]);             // INSERT ke wallet_transactions (pengirim)
    WalletTransaction::create([...]);             // INSERT ke wallet_transactions (penerima)
});
```

---

## D. INTERAKSI PENGGUNA — CRUD + Login + Submit Data

| Fitur | Create | Read | Update | Delete |
|-------|--------|------|--------|--------|
| **Registrasi** | User + Wallet + GamificationProfile | — | — | — |
| **Login** | — | Authentikasi | Update streak | — |
| **Transaksi** (4 mode) | General/Budget/Goal/Quest | Riwayat + filter | Edit transaksi | Hapus |
| **Budget** | Budget baru | Daftar + status | Edit budget | Hapus |
| **Financial Goals** | Goal baru | Progress bar | Complete otomatis | Hapus |
| **Quests** | Join quest | Progress dinamis | Complete/fail otomatis | Hapus |
| **Badge** | Award otomatis | Koleksi + katalog | — | — |
| **Wallet Top Up** | TopUp + Transaction | Saldo + riwayat | Update balance | — |
| **Wallet Transfer** | 2 Transaction | Riwayat | Update 2 balance | — |
| **Wallet Withdraw** | Withdraw | Riwayat | Update balance | — |
| **Friendship** | Send request | Daftar + cari | Accept/reject | Remove/block |
| **Profile** | — | Profil | Edit profile/password | Hapus akun |
| **API** | Register/login/transaksi | Dashboard + filter | Update transaksi | Delete transaksi |
| **Admin CRUD** | 7 entitas | Semua data | 7 entitas | 7 entitas |

---

## E. PROMPT GENERATE ERD (untuk AI lain)

Copy-paste teks di bawah ini ke AI gambar/ERD generator (Draw.io, Eraser.io, Mermaid, atau AI seperti Claude):

```
# PERMINTAAN: Generate Entity Relationship Diagram (ERD)

Buatkan ERD untuk aplikasi FinCo (Finance Gamification) dengan detail berikut:

## TABEL-TABEL YANG ADA:

### 1. users (User)
Columns: id (PK), name, email (UNIQUE), password, username (UNIQUE), full_name, phone, date_of_birth, role (ENUM: user/admin), email_verified_at, remember_token, created_at, updated_at

### 2. transactions (Transaksi Keuangan)
Columns: id (PK), user_id (FK), category_id (FK nullable), budget_id (FK nullable), type (ENUM: income/expense), amount (DECIMAL), description, transaction_date, xp_earned, created_at, updated_at
Relasi: 
- user_id → users.id (ONE TO MANY: satu user punya banyak transaksi)
- category_id → categories.id (ONE TO MANY: satu kategori dipakai banyak transaksi)
- budget_id → budgets.id (ONE TO MANY: satu budget dipakai banyak transaksi)

### 3. categories (Kategori Transaksi)
Columns: id (PK), name, type (ENUM: income/expense), icon, color, created_at, updated_at
Relasi: 
- Satu kategori punya banyak transactions

### 4. budgets (Anggaran)
Columns: id (PK), user_id (FK), category, category_id, limit_amount (DECIMAL), spent_amount (DECIMAL), period (ENUM: daily/weekly/monthly/yearly), period_start, period_end, is_active (BOOLEAN), status (ENUM: on_track/warning/exceeded), created_at, updated_at
Relasi:
- user_id → users.id (ONE TO MANY: satu user punya banyak budget)

### 5. financial_goals (Target Keuangan)
Columns: id (PK), user_id (FK), name, description, target_amount (DECIMAL), current_amount (DECIMAL), target_date, status (ENUM: active/completed/cancelled), category, created_at, updated_at
Relasi:
- user_id → users.id (ONE TO MANY: satu user punya banyak goal)

### 6. transaction_goal_allocations (Alokasi Transaksi ke Goal)
Columns: id (PK), transaction_id (FK UNIQUE), goal_id (FK), allocated_amount (DECIMAL), created_at, updated_at
Relasi:
- transaction_id → transactions.id (ONE TO ONE: satu transaksi punya satu alokasi)
- goal_id → financial_goals.id (ONE TO MANY: satu goal punya banyak alokasi)

### 7. gamification_profiles (Profil Gamifikasi User)
Columns: id (PK), user_id (FK UNIQUE), current_level (INT), total_xp (INT), current_streak (INT), last_login_date, created_at, updated_at
Relasi:
- user_id → users.id (ONE TO ONE: satu user punya satu profil gamifikasi)

### 8. badges (Daftar Badge)
Columns: id (PK), name (UNIQUE), description, icon, required_level (INT), required_xp (INT), created_at, updated_at

### 9. user_badges (Badge yang Dimiliki User)
Columns: id (PK), user_id (FK), badge_id (FK), earned_at, created_at, updated_at
Relasi:
- user_id → users.id (ONE TO MANY: satu user punya banyak badge)
- badge_id → badges.id (ONE TO MANY: satu badge dimiliki banyak user)

### 10. challenges (alias Quest — Tantangan)
Columns: id (PK), user_id (FK), name, description, difficulty (ENUM: easy/medium/hard), reward_xp (INT), start_date, end_date, status (ENUM: active/completed/failed), category, criteria (TEXT/JSON), created_at, updated_at
Relasi:
- user_id → users.id (ONE TO MANY: satu user punya banyak quest)

### 11. wallets (Dompet Digital)
Columns: id (PK), user_id (FK UNIQUE), balance (DECIMAL), currency (default IDR), is_active (BOOLEAN), created_at, updated_at
Relasi:
- user_id → users.id (ONE TO ONE: satu user punya satu dompet)

### 12. wallet_transactions (Riwayat Transaksi Dompet)
Columns: id (PK), user_id (FK), wallet_id (FK), type (ENUM: top_up/payment/transfer_in/transfer_out), amount (DECIMAL), balance_before (DECIMAL), balance_after (DECIMAL), description, status (ENUM: success/failed), reference_number, created_at, updated_at
Relasi:
- user_id → users.id (ONE TO MANY)
- wallet_id → wallets.id (ONE TO MANY: satu dompet punya banyak transaksi)

### 13. top_ups (Riwayat Top Up)
Columns: id (PK), user_id (FK), wallet_id (FK), amount (DECIMAL), payment_method (ENUM: bank_transfer/virtual_account/qris), status (ENUM: pending/success/failed), reference_number, xp_earned (INT), created_at, updated_at
Relasi:
- user_id → users.id (ONE TO MANY)
- wallet_id → wallets.id (ONE TO MANY)

### 14. friendships (Pertemanan)
Columns: id (PK), user_id (FK), friend_id (FK), status (ENUM: pending/accepted/rejected), created_at, updated_at
Relasi:
- user_id → users.id (ONE TO MANY: satu user mengirim banyak request)
- friend_id → users.id (ONE TO MANY: satu user menerima banyak request)

### 15. friend_blocks (Blokir User)
Columns: id (PK), user_id (FK), blocked_user_id (FK), created_at, updated_at
Relasi:
- user_id → users.id (ONE TO MANY)
- blocked_user_id → users.id (ONE TO MANY)

## TOLONG GAMBARKAN ERD DENGAN:
- Notasi Crow's Foot atau Chen
- Tampilkan primary key (PK), foreign key (FK), unique constraint (UNIQUE)
- Label relasi: ONE TO ONE, ONE TO MANY, MANY TO MANY
- Grouping berdasarkan modul: Core/Auth, Financial, Gamification, Wallet, Social
```

---

## F. PROMPT USER FLOW (SINGKAT — untuk AI lain)

Copy-paste teks ini untuk user flow diagram yang simpel:

```
Buat user flow diagram aplikasi FinCo (Financial Gamification), dengan maksimal 12 kotak utama.

ALUR:
1. Landing Page → pilih Login atau Register
2. Register: isi form (nama, username, email, password) → sistem buat akun + wallet + profil game → masuk Dashboard
3. Login: cek email/password → update streak login → masuk Dashboard
4. Dashboard: lihat ringkasan (total income/expense/saldo), level & XP, 20 transaksi terbaru
5. Transaksi: 4 mode — General (kategori bebas), Expense (terikat budget, update otomatis spent), Income (terikat goal, auto alokasi), Quest (syarat quest tertentu)
6. Budget: CRUD budget per kategori + periode → progress bar (hijau/kuning/merah) → otomatis cek exceeded
7. Financial Goals: CRUD goal → progress dari income → +100 XP + badge saat selesai
8. Quests: 6 quest template → join → progress otomatis dari transaksi/login → reward XP + badge
9. Wallet: Top Up (50k/100k/200k/500k via bank/VA/QRIS) → Withdraw (isi bank + nominal) → Transfer (cari teman → kirim) → +XP tiap transaksi
10. Teman: cari user → kirim/tolak/terima request → blokir/unblok → +30 XP teman pertama
11. Leaderboard: skor = (transaksi×4)+(hari aktif×3)+(streak×15)+(goal×2)+(quest×20)+(XP×0.3)+(badge×30)
12. Admin Panel: dashboard statistik + Chart.js + CRUD 7 entitas (Users, Transactions, Categories, Budgets, Goals, Quests, Badges)

GAMBARKAN:
- Flowchart kotak-panah sederhana (12 kotak)
- Decision: "Sudah punya akun?" (Ya → Login, Tidak → Register)
- Warna: Biru (auth), Hijau (financial), Ungu (gamifikasi), Oranye (sosial/wallet)
```

---

## G. REKOMENDASI PERBAIKAN

| No | Temuan | Rekomendasi |
|----|--------|-------------|
| 1 | `budgets.category_id` tidak punya FK constraint | Tambah migration: `$table->foreign('category_id')->references('id')->on('categories')->onDelete('set null')` |
| 2 | 5 tabel stub tidak terpakai (achievements, user_achievements, user_challenges, xp_history, login_streaks) | Hapus atau isi dengan implementasi |
| 3 | FinancialGoal tidak punya inverse relasi ke Transaction | Tambah `belongsToMany('transactions')` di model FinancialGoal |
| 4 | GamificationProfile diakses pakai `DB::table()` raw di banyak tempat | Migrasi ke Eloquent model |
| 5 | Tidak ada soft delete | Tambah SoftDeletes untuk data sensitif |
