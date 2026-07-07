<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GalleryPageController;
use App\Http\Controllers\ShareholderReviewPageController;
use App\Http\Controllers\TermsPageController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ContentManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/gallery', GalleryPageController::class)->name('gallery.index');
Route::get('/shareholder-reviews', ShareholderReviewPageController::class)->name('reviews.index');
Route::get('/terms-and-conditions', TermsPageController::class)->name('terms.show');
Route::middleware('guest:admin')->group(function (): void {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', function () {
        return Auth::guard('admin')->check()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('login');
    });

    Route::middleware('guest:admin')->group(function (): void {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware('admin.auth')->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/content-management', [ContentManagementController::class, 'index'])->name('content.index');
        Route::patch('/content-management/notice', [ContentManagementController::class, 'updateNotice'])->name('content.notice.update');
        Route::patch('/content-management/about', [ContentManagementController::class, 'updateAbout'])->name('content.about.update');
        Route::patch('/content-management/why', [ContentManagementController::class, 'updateWhy'])->name('content.why.update');
        Route::patch('/content-management/projects', [ContentManagementController::class, 'updateProjects'])->name('content.projects.update');
        Route::patch('/content-management/gallery', [ContentManagementController::class, 'updateGallery'])->name('content.gallery.update');
        Route::patch('/content-management/reviews', [ContentManagementController::class, 'updateReviews'])->name('content.reviews.update');
        Route::patch('/content-management/leadership', [ContentManagementController::class, 'updateLeadership'])->name('content.leadership.update');
        Route::patch('/content-management/valued-shareholders', [ContentManagementController::class, 'updateValuedShareholders'])->name('content.valued-shareholders.update');
        Route::patch('/content-management/footer', [ContentManagementController::class, 'updateFooter'])->name('content.footer.update');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
        Route::post('/profile/logout-other-devices', [ProfileController::class, 'logoutOtherDevices'])->name('profile.logout-other-devices');
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});
