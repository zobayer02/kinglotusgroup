<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\PasswordResetController;
use App\Http\Controllers\Admin\Auth\TwoFactorAuthController;
use App\Http\Controllers\Admin\Auth\TwoFactorProfileController;
use App\Http\Controllers\Admin\ContentManagementController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\CspReportController;
use App\Http\Controllers\FaqPageController;
use App\Http\Controllers\GalleryPageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShareholderReviewPageController;
use App\Http\Controllers\TermsPageController;
use App\Http\Controllers\ValuedShareholderPageController;
use App\Models\SiteNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/faq', FaqPageController::class)->name('faq.index');
Route::get('/faq/items', [FaqPageController::class, 'items'])->name('faq.items')->middleware('throttle:60,1');
Route::get('/gallery', GalleryPageController::class)->name('gallery.index');
Route::get('/shareholder-reviews', ShareholderReviewPageController::class)->name('reviews.index');
Route::get('/valued-shareholders', ValuedShareholderPageController::class)->name('shareholders.index');
Route::get('/valued-shareholders/items', [ValuedShareholderPageController::class, 'items'])->name('shareholders.items')->middleware('throttle:60,1');

Route::get('/terms-and-conditions', TermsPageController::class)->name('terms.show');
Route::get('/terms', fn () => redirect()->route('terms.show'))->name('terms');

Route::post('/api/csp-report', [CspReportController::class, 'report'])->name('api.csp.report');
Route::middleware('guest:admin')->group(function (): void {
    Route::get('/login', function () {
        $notice = SiteNotice::query()->active()->latest('updated_at')->first();

        return view('auth.login', ['notice' => $notice]);
    })->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('/login/challenge', [TwoFactorAuthController::class, 'challenge'])->name('login.challenge');
    Route::post('/login/challenge', [TwoFactorAuthController::class, 'verifyChallenge'])->name('login.challenge.verify')->middleware('throttle:10,1');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email')->middleware('throttle:10,1');
    Route::get('/verify-otp', [PasswordResetController::class, 'verifyOtpForm'])->name('password.verify-otp');
    Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp'])->name('password.verify-otp.submit')->middleware('throttle:10,1');
    Route::post('/resend-otp', [PasswordResetController::class, 'resendOtp'])->name('password.resend-otp')->middleware('throttle:5,1');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update')->middleware('throttle:10,1');
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', function () {
        return Auth::guard('admin')->check()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('login');
    });

    Route::match(['get', 'post'], '/system/migrate', function (Request $request) {
        $expectedKey = (string) config('app.key');
        $expectedToken = (string) env('MIGRATION_SECRET', '');
        $providedKey = (string) ($request->query('key') ?? $request->input('key') ?? '');
        $providedToken = (string) ($request->query('token') ?? $request->input('token') ?? '');

        $isAuthorized = false;
        if (! empty($expectedKey) && ! empty($providedKey) && hash_equals($expectedKey, $providedKey)) {
            $isAuthorized = true;
        } elseif (! empty($expectedToken) && ! empty($providedToken) && hash_equals($expectedToken, $providedToken)) {
            $isAuthorized = true;
        } elseif (Auth::guard('admin')->check() && Auth::guard('admin')->user()?->role === 'super_admin') {
            $isAuthorized = true;
        }

        if (! $isAuthorized) {
            abort(403, 'Unauthorized migration trigger.');
        }

        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();

        // Run ContentSyncSeeder to sync all image paths and section content
        if ($request->boolean('sync_content', true)) {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ContentSyncSeeder',
                '--force' => true,
            ]);
            $output .= "\n".Artisan::output();
        }

        // Clear site cache so newly migrated data immediately shows up
        Cache::flush();

        return response()->json([
            'status' => 'success',
            'message' => 'Database migrations and content sync executed successfully.',
            'output' => $output,
        ]);
    })->middleware('throttle:10,1')->name('system.migrate');

    Route::middleware('guest:admin')->group(function (): void {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware('admin.auth')->group(function (): void {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        // Content management: accessible by super_admin and admin
        Route::middleware('admin.role:super_admin,admin')->group(function (): void {
            Route::get('/content-management', [ContentManagementController::class, 'index'])->name('content.index');
            Route::patch('/content-management/notice', [ContentManagementController::class, 'updateNotice'])->name('content.notice.update');
            Route::patch('/content-management/about', [ContentManagementController::class, 'updateAbout'])->name('content.about.update');
            Route::patch('/content-management/why', [ContentManagementController::class, 'updateWhy'])->name('content.why.update');
            Route::patch('/content-management/projects', [ContentManagementController::class, 'updateProjects'])->name('content.projects.update');
            Route::patch('/content-management/prospectus', [ContentManagementController::class, 'updateProspectus'])->name('content.prospectus.update');
            Route::patch('/content-management/gallery', [ContentManagementController::class, 'updateGallery'])->name('content.gallery.update');
            Route::patch('/content-management/reviews', [ContentManagementController::class, 'updateReviews'])->name('content.reviews.update');
            Route::patch('/content-management/leadership', [ContentManagementController::class, 'updateLeadership'])->name('content.leadership.update');
            Route::patch('/content-management/leadership/toggle-visibility', [ContentManagementController::class, 'toggleLeadershipVisibility'])->name('content.leadership.toggle-visibility');
            Route::patch('/content-management/leadership/toggle-board-members', [ContentManagementController::class, 'toggleBoardMembersVisibility'])->name('content.leadership.toggle-board-members');
            Route::patch('/content-management/valued-shareholders', [ContentManagementController::class, 'updateValuedShareholders'])->name('content.valued-shareholders.update');
            Route::get('/content-management/valued-shareholders/items', [ContentManagementController::class, 'getShareholders'])->name('content.valued-shareholders.items');
            Route::post('/content-management/valued-shareholders/items', [ContentManagementController::class, 'storeShareholder'])->name('content.valued-shareholders.store');
            Route::post('/content-management/valued-shareholders/items/{shareholder}', [ContentManagementController::class, 'updateShareholder'])->name('content.valued-shareholders.item.update');
            Route::delete('/content-management/valued-shareholders/items/{shareholder}', [ContentManagementController::class, 'destroyShareholder'])->name('content.valued-shareholders.destroy');
            Route::patch('/content-management/footer', [ContentManagementController::class, 'updateFooter'])->name('content.footer.update');
            Route::get('/content-management/faqs/items', [ContentManagementController::class, 'getFaqs'])->name('content.faqs.items');
            Route::post('/content-management/faqs', [ContentManagementController::class, 'storeFaq'])->name('content.faqs.store');
            Route::patch('/content-management/faqs/{faq}', [ContentManagementController::class, 'updateFaq'])->name('content.faqs.update');
            Route::delete('/content-management/faqs/{faq}', [ContentManagementController::class, 'destroyFaq'])->name('content.faqs.destroy');
        });

        // Profile & Security & Password management: privileged, super_admin only
        Route::middleware('admin.role:super_admin')->group(function (): void {
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
            Route::post('/profile/logout-other-devices', [ProfileController::class, 'logoutOtherDevices'])->name('profile.logout-other-devices');

            // Two-Factor Authentication (MFA) Management
            Route::post('/profile/two-factor/enable', [TwoFactorProfileController::class, 'enable'])->name('profile.two-factor.enable');
            Route::post('/profile/two-factor/confirm', [TwoFactorProfileController::class, 'confirm'])->name('profile.two-factor.confirm');
            Route::post('/profile/two-factor/disable', [TwoFactorProfileController::class, 'disable'])->name('profile.two-factor.disable');
            Route::post('/profile/two-factor/recovery-codes', [TwoFactorProfileController::class, 'regenerateRecoveryCodes'])->name('profile.two-factor.recovery-codes');
        });
    });
});
