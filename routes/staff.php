<?php

use App\Http\Controllers\Staff\StaffAuthController;
use App\Http\Middleware\RedirectIfAuthenticatedStaff;
use App\Http\Middleware\RedirectIfNotAuthenticatedStaff;
use Illuminate\Support\Facades\Route;

Route::prefix('staff')->group(function () {
    Route::middleware([RedirectIfAuthenticatedStaff::class])->group(function () {
        Route::get('/login', [StaffAuthController::class, 'showLoginForm'])->name('staff.login');
        Route::post('/login', [StaffAuthController::class, 'login'])->name('staff.login.post');
    });

    Route::middleware([RedirectIfNotAuthenticatedStaff::class])->group(function () {
        Route::get('/dashboard', function () {
            $activeGuard = 'staff';
            $authUser = \Illuminate\Support\Facades\Auth::guard($activeGuard)->user();

            // Dùng chung dashboard layout, sidebar sẽ tự giới hạn menu theo role/permission
            return view('clients.admin.pages.dashboard', [
                'activeGuard' => $activeGuard,
                'authUser' => $authUser,
                'logoutRoute' => route('staff.logout'),
                'dashboardRoute' => route('staff.dashboard'),
            ]);
        })->name('staff.dashboard');

        Route::match(['GET', 'POST'], '/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');
    });
});
