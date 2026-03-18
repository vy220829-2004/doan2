<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Middleware\RedirectIfAuthenticatedAdmin;
use App\Http\Middleware\RedirectIfNotAuthenticated;

Route::prefix('admin')->group(function () {

Route::middleware([RedirectIfAuthenticatedAdmin::class])->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    // Thêm Route xử lý submit:
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
});

 Route::middleware([RedirectIfNotAuthenticated::class])->group(function () {
        Route::get('/dashboard', function () {
            $activeGuard = 'admin';
            $authUser = \Illuminate\Support\Facades\Auth::guard($activeGuard)->user();

            return view('clients.admin.pages.dashboard', [
                'activeGuard' => $activeGuard,
                'authUser' => $authUser,
                'logoutRoute' => route('admin.logout'),
                'dashboardRoute' => route('admin.dashboard'),
            ]);
        })->name('admin.dashboard');

        Route::match(['GET', 'POST'], '/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    });

});