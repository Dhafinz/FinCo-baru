<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GamificationController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'overview'])->name('dashboard');
    Route::get('/dashboard/transactions', [UserDashboardController::class, 'transactions'])->name('dashboard.transactions');
    Route::post('/dashboard/transactions', [UserDashboardController::class, 'storeTransaction'])->name('dashboard.transactions.store');
    Route::put('/dashboard/transactions/{transaction}', [UserDashboardController::class, 'updateTransaction'])->name('dashboard.transactions.update');
    Route::delete('/dashboard/transactions/{transaction}', [UserDashboardController::class, 'destroyTransaction'])->name('dashboard.transactions.destroy');
    
    Route::get('/dashboard/budgets', [UserDashboardController::class, 'budgets'])->name('dashboard.budgets');
    Route::post('/dashboard/budgets', [UserDashboardController::class, 'storeBudget'])->name('dashboard.budgets.store');
    Route::put('/dashboard/budgets/{budget}', [UserDashboardController::class, 'updateBudget'])->name('dashboard.budgets.update');
    Route::delete('/dashboard/budgets/{budget}', [UserDashboardController::class, 'destroyBudget'])->name('dashboard.budgets.destroy');
    
    Route::get('/dashboard/goals', [UserDashboardController::class, 'goals'])->name('dashboard.goals');
    Route::post('/dashboard/goals', [UserDashboardController::class, 'storeGoal'])->name('dashboard.goals.store');
    Route::put('/dashboard/goals/{goal}', [UserDashboardController::class, 'updateGoal'])->name('dashboard.goals.update');
    Route::delete('/dashboard/goals/{goal}', [UserDashboardController::class, 'destroyGoal'])->name('dashboard.goals.destroy');
    
    Route::get('/dashboard/challenges', [UserDashboardController::class, 'challenges'])->name('dashboard.challenges');
    Route::post('/dashboard/challenges', [UserDashboardController::class, 'storeChallenge'])->name('dashboard.challenges.store');
    Route::put('/dashboard/challenges/{challenge}', [UserDashboardController::class, 'updateChallenge'])->name('dashboard.challenges.update');
    Route::delete('/dashboard/challenges/{challenge}', [UserDashboardController::class, 'destroyChallenge'])->name('dashboard.challenges.destroy');
    Route::get('/dashboard/quests', [UserDashboardController::class, 'quests'])->name('dashboard.quests');
    Route::post('/dashboard/quests/join', [UserDashboardController::class, 'joinQuest'])->name('dashboard.quests.join');
    
    Route::get('/dashboard/badges', [UserDashboardController::class, 'badges'])->name('dashboard.badges');
    Route::get('/dashboard/leaderboard', [UserDashboardController::class, 'leaderboard'])->name('dashboard.leaderboard');

    Route::get('/dashboard/wallet', [WalletController::class, 'index'])->name('dashboard.wallet');
    Route::get('/dashboard/wallet/topup', [WalletController::class, 'topupForm'])->name('dashboard.wallet.topup.form');
    Route::post('/dashboard/wallet/topup', [WalletController::class, 'topup'])->name('dashboard.wallet.topup');
    Route::get('/dashboard/wallet/withdraw', [WalletController::class, 'withdrawForm'])->name('dashboard.wallet.withdraw.form');
    Route::post('/dashboard/wallet/withdraw', [WalletController::class, 'withdraw'])->name('dashboard.wallet.withdraw');
    Route::get('/dashboard/wallet/transfer', [WalletController::class, 'transferForm'])->name('dashboard.wallet.transfer.form');
    Route::post('/dashboard/wallet/transfer', [WalletController::class, 'transfer'])->name('dashboard.wallet.transfer');

    Route::get('/dashboard/friends', [FriendController::class, 'index'])->name('dashboard.friends');
    Route::post('/dashboard/friends/search', [FriendController::class, 'search'])->name('dashboard.friends.search');
    Route::post('/dashboard/friends/add', [FriendController::class, 'sendRequest'])->name('dashboard.friends.add');
    Route::post('/dashboard/friends/remove/{id}', [FriendController::class, 'remove'])->name('dashboard.friends.remove');
    Route::post('/dashboard/friends/block/{id}', [FriendController::class, 'block'])->name('dashboard.friends.block');
    Route::post('/dashboard/friends/unblock/{id}', [FriendController::class, 'unblock'])->name('dashboard.friends.unblock');
    Route::post('/dashboard/friends/accept/{id}', [FriendController::class, 'accept'])->name('dashboard.friends.accept');
    Route::post('/dashboard/friends/reject/{id}', [FriendController::class, 'reject'])->name('dashboard.friends.reject');

    Route::get('/dashboard/reports', [UserDashboardController::class, 'reports'])->name('dashboard.reports');
    Route::get('/dashboard/settings', [UserDashboardController::class, 'settings'])->name('dashboard.settings');
});

Route::middleware('auth')->group(function () {
    Route::get('/gamification', [GamificationController::class, 'index'])->name('gamification');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('transactions', TransactionController::class);
    Route::get('/users', [UserController::class, 'index'])->name('users');
});

require __DIR__.'/auth.php';