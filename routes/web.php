<?php

use App\Http\Controllers\Admin\AdminCaseStudyController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminReviewQueueController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Billing\BillingController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\PhotoController;
use App\Http\Controllers\Dashboard\VehicleController;
use App\Http\Controllers\Marketing\CaseStudyController;
use App\Http\Controllers\Marketing\ContactController;
use App\Http\Controllers\Marketing\HomeController;
use App\Http\Controllers\Marketing\PricingController;
use App\Http\Controllers\Marketing\ProductController;
use App\Http\Controllers\Marketing\SolutionsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/* ---------- Public marketing ---------- */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/studio', [ProductController::class, 'studio'])->name('products.studio');
Route::get('/social', [ProductController::class, 'social'])->name('products.social');
Route::get('/engage', [ProductController::class, 'engage'])->name('products.engage');
Route::get('/solutions', [SolutionsController::class, 'index'])->name('solutions');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
Route::get('/case-studies', [CaseStudyController::class, 'index'])->name('case-studies.index');
Route::get('/case-studies/{case_study:slug}', [CaseStudyController::class, 'show'])->name('case-studies.show');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/* ---------- Authenticated dashboard ---------- */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('vehicles', VehicleController::class);
    Route::post('vehicles/{vehicle}/photos', [PhotoController::class, 'store'])->name('vehicles.photos.store');
    Route::post('photos/{photo}/revisit', [PhotoController::class, 'revisit'])->name('photos.revisit');
    Route::delete('photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/subscribe/{plan}', [BillingController::class, 'subscribe'])->name('billing.subscribe');
    Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/* ---------- Admin ---------- */
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('case-studies', AdminCaseStudyController::class);
    Route::resource('plans', AdminPlanController::class)->only(['index', 'edit', 'update']);
    Route::get('/review-queue', [AdminReviewQueueController::class, 'index'])->name('review-queue.index');
    Route::post('/review-queue/{photo}/approve', [AdminReviewQueueController::class, 'approve'])->name('review-queue.approve');
    Route::post('/review-queue/{photo}/reject', [AdminReviewQueueController::class, 'reject'])->name('review-queue.reject');
});

require __DIR__.'/auth.php';
