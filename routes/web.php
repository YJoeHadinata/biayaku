<?php

use App\Http\Controllers\Admin\AdminSubscriptionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MiscellaneousCostController;
use App\Http\Controllers\OperationalCostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\ProductionBatchController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Redirect admin users to admin dashboard
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('materials', MaterialController::class);
    Route::resource('products', ProductController::class);
    Route::resource('products.recipes', RecipeController::class)->shallow();
    Route::resource('products.variations', ProductVariationController::class)->shallow();
    Route::resource('production-batches', ProductionBatchController::class);
    Route::resource('costs/operational', OperationalCostController::class);
    Route::resource('costs/miscellaneous', MiscellaneousCostController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->middleware('subscription:export_excel')->name('reports.export.excel');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->middleware('subscription:export_pdf')->name('reports.export.pdf');

    // Subscription routes
    Route::get('/subscription/plans', [SubscriptionController::class, 'plans'])->name('subscriptions.plans');
    Route::get('/subscription/status', [SubscriptionController::class, 'status'])->name('subscriptions.status');
    Route::post('/subscription/subscribe/{planSlug}', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('/subscription/upgrade/{planSlug}', [SubscriptionController::class, 'upgrade'])->name('subscriptions.upgrade');
    Route::get('/subscription/upgrade-required', [SubscriptionController::class, 'upgradeRequired'])->name('subscriptions.upgrade-required');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes - only for super_admin and admin
Route::middleware(['auth', 'role:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Branch management
    Route::resource('branches', BranchController::class);

    // Subscription management
    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/all', [AdminSubscriptionController::class, 'all'])->name('subscriptions.all');
    Route::patch('/subscriptions/{subscription}/approve', [AdminSubscriptionController::class, 'approve'])->name('subscriptions.approve');
    Route::patch('/subscriptions/{subscription}/reject', [AdminSubscriptionController::class, 'reject'])->name('subscriptions.reject');

    // User management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
});

require __DIR__ . '/auth.php';
