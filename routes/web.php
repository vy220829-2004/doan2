<?php

use App\Http\Controllers\Clients\AuthController;
use App\Http\Controllers\Clients\AccountController;
use App\Http\Controllers\Clients\ForgotPasswordController;
use App\Http\Controllers\Clients\HomeController;
use App\Http\Controllers\Clients\OrderController;
use App\Http\Controllers\Clients\ProductController;
use App\Http\Controllers\Clients\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Clients\ReviewController;
use App\Http\Controllers\Clients\WishlistController;
use App\Http\Controllers\Clients\SearchController;
use Illuminate\Support\Facades\Route;

Route::prefix('/')->group(function () {
    Route::redirect('/index.html', '/');
    Route::redirect('/about.html', '/about');
    Route::redirect('/service.html', '/services');
    Route::redirect('/service', '/services');
    Route::redirect('/team.html', '/team');
    Route::redirect('/faq.html', '/faq');
    Route::redirect('/shop.html', '/shop');
    Route::redirect('/contact.html', '/contact');
    Route::redirect('/cart.html', '/cart');
    Route::redirect('/wishlist.html', '/wishlist');
    Route::redirect('/login.html', '/login');
    Route::redirect('/register.html', '/register');
    Route::redirect('/account.html', '/account');
    Route::redirect('/checkout.html', '/checkout');

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/about', function () {
        return view('clients.admin.layouts.pages.about');
    })->name('about');

    Route::get('/services', function () {
        return view('clients.admin.layouts.pages.service');
    })->name('services');

    Route::get('/team', function () {
        return view('clients.admin.layouts.pages.team');
    })->name('team');

    Route::get('/faq', function () {
        return view('clients.admin.layouts.pages.faq');
    })->name('faq');

    Route::get('/shop', [ProductController::class, 'index'])->name('shop');

    Route::get('/contact', [App\Http\Controllers\Clients\ContactController::class, 'index'])->name('contact.index');
    Route::post('/contact', [App\Http\Controllers\Clients\ContactController::class, 'sendContact'])->name('contact.send');


    //SEARCH
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');


    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/add', [App\Http\Controllers\Clients\WishlistController::class, 'addToWishlist'])->name('wishlist.add');
    Route::delete('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/add-to-cart', [WishlistController::class, 'addToCartAndRedirect'])->name('wishlist.add_to_cart');

    Route::middleware('guest')->group(function () {
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.post');

        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');

        Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
        Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'edit'])->name('password.reset');
        Route::post('/reset-password', [ForgotPasswordController::class, 'update'])->name('password.update');
    });

    Route::get('/activate/{token}', [AuthController::class, 'activate'])->name('activate');

    Route::middleware([\App\Http\Middleware\RedirectIfNotAuthenticated::class])->group(function () {
        Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])
            ->middleware('auth')
            ->name('logout');

        Route::prefix('account')->middleware('auth')->group(function () {
            Route::get('/', [AccountController::class, 'index'])->name('account');
            Route::get('/addresses', [AccountController::class, 'index'])->name('account.addresses');
            Route::put('/update', [AccountController::class, 'update'])->name('account.update');
            Route::post('/change-password', [AccountController::class, 'changePassword'])->name('account.change_password');
            Route::post('/addresses', [AccountController::class, 'addAddress'])->name('account.add_address');
            Route::put('/addresses/{id}', [AccountController::class, 'updatePrimaryAddress'])->name('account.update_address'); // Re-added PUT route
            Route::delete('/addresses/{id}', [AccountController::class, 'deleteAddress'])->name('account.delete_address'); // Re-added DELETE route
        });

        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');


        Route::get('/order/{id}', [OrderController::class, 'showOrder'])->name('order.show');
        Route::get('/order/{id}/cancel', [OrderController::class, 'cancelOrder'])->name('order.cancel');


        Route::post('/review', [ReviewController::class, 'createReview'])->name('review.store');
        Route::get('/review/{product}', [ReviewController::class, 'index'])->name('review.index');
    });

    Route::get('/products', [\App\Http\Controllers\Clients\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/filter', [\App\Http\Controllers\Clients\ProductController::class, 'filter'])->name('products.filter');

    //Detail product
    Route::get('/products/{slug}', [\App\Http\Controllers\Clients\ProductController::class, 'detail'])->name('products.detail');

    Route::get('/mini-cart', [\App\Http\Controllers\Clients\CartController::class, 'miniCart'])->name('mini-cart');
    Route::match(['POST', 'DELETE'], '/cart/remove', [\App\Http\Controllers\Clients\CartController::class, 'removeFromCart'])->name('cart.remove.mini');

    //HANDLE CART 
    Route::post('/cart/add', [App\Http\Controllers\Clients\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove-cart', [CartController::class, 'removeCartItem'])->name('cart.remove');
});


require base_path('routes/admin.php');
require base_path('routes/staff.php');
