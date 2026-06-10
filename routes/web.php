<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AffiliateEarningController;
use App\Http\Controllers\SaleController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/produto/{product:slug}', [ProductPageController::class, 'show'])
    ->name('products.public.show');

Route::get('/a/{affiliation}/{affiliateCode}', [ProductPageController::class, 'affiliateRedirect'])
    ->whereNumber('affiliation')
    ->name('affiliates.redirect');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/produtos', [ProductController::class, 'index'])->name('products.index');
    Route::get('/produtos/criar', [ProductController::class, 'create'])->name('products.create');
    Route::post('/produtos', [ProductController::class, 'store'])->name('products.store');
    Route::get('/produtos/{product}/editar', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/produtos/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/produtos/{product}', [ProductController::class, 'destroy'])->name('products.destroy');


    Route::get('/afiliados/ganhos', [AffiliateEarningController::class, 'index'])
    ->name('affiliate.earnings.index');


    Route::get('/minhas-vendas', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/minhas-vendas/{order}', [SaleController::class, 'show'])->name('sales.show');


    Route::delete('/produtos/imagens/{image}', [ProductController::class, 'destroyImage'])
        ->name('products.images.destroy');
    Route::get('/minhas-compras', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/minhas-compras/{order}', [PurchaseController::class, 'show'])->name('purchases.show');
        
    Route::get('/afiliados/produtos', [AffiliateController::class, 'index'])->name('affiliates.index');
    Route::post('/afiliados/produtos/{product}', [AffiliateController::class, 'store'])->name('affiliates.store');

    Route::get('/afiliados/minhas-afiliacoes', [AffiliateController::class, 'myAffiliations'])->name('affiliates.my');
    Route::patch('/afiliados/minhas-afiliacoes/{affiliation}/cancelar', [AffiliateController::class, 'cancel'])
        ->name('affiliates.cancel');

    Route::get('/checkout/{product:slug}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{product:slug}', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/pedido/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

/*
|--------------------------------------------------------------------------
| Área administrativa
|--------------------------------------------------------------------------
| URLs separadas e não exibidas no menu público.
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/usuarios', [AdminController::class, 'users'])->name('users');
        


        
        Route::get('/produtos', [AdminController::class, 'products'])->name('products');
        Route::patch('/produtos/{product}/aprovacao', [AdminController::class, 'updateProductApproval'])->name('products.approval');
        Route::patch('/produtos/{product}/desativar', [AdminController::class, 'disableProduct'])->name('products.disable');
        Route::patch('/produtos/{product}/restaurar', [AdminController::class, 'restoreProduct'])->name('products.restore');

        Route::get('/pedidos', [AdminController::class, 'orders'])->name('orders');
        Route::patch('/pedidos/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');

        Route::get('/pedidos/{order}/rastreio', [AdminController::class, 'tracking'])->name('orders.tracking');
        Route::post('/pedidos/{order}/rastreio', [AdminController::class, 'updateTracking'])->name('orders.tracking.update');

        Route::get('/configuracoes', [AdminController::class, 'settings'])->name('settings');
        Route::post('/configuracoes', [AdminController::class, 'updateSettings'])->name('settings.update');


        Route::get('/afiliados/ganhos', [AdminController::class, 'affiliateEarnings'])
            ->name('affiliate.earnings');

        Route::patch('/afiliados/ganhos/{order}/validar', [AdminController::class, 'updateAffiliateCommission'])
            ->name('affiliate.earnings.update');

        Route::patch('/usuarios/{user}/status', [AdminController::class, 'updateUserStatus'])
            ->name('users.status');

            
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});