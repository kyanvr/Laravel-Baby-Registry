<?php

use App\Http\Controllers\Admin\ScrapeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistryController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Routes when logged in as user or admin
Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Routes for admin
Route::group(['middleware' => ['auth', 'role:admin']], function() {
    Route::get('/dashboard/categories', [DashboardController::class, 'categories'])->name('dashboard.categories');
    Route::get('/dashboard/products', [DashboardController::class, 'products'])->name('dashboard.products');

    Route::get('/scrape', [ScrapeController::class, 'show'])->name('scrape');
    Route::post('/scrape/categories', [ScrapeController::class, 'scrapeCategories'])->name('scrape.categories');
    Route::post('/scrape/articles', [ScrapeController::class, 'scrapeProducts'])->name('scrape.products');
    Route::post('/scrape/images', [ScrapeController::class, 'scrapeImages'])->name('scrape.images');
});

// Routes for users
Route::group(['middleware' => ['auth', 'role:user']], function(){
    Route::get('/registry/index', [RegistryController::class, 'index'])->name('registry.index');
    Route::post('/registry/create', [RegistryController::class, 'create'])->name('registry.create');
    Route::get('/registry/products', [RegistryController::class, 'productsList'])->name('registry.productsList');
    Route::post('/registry/products', [RegistryController::class, 'filter'])->name('registry.filter');
    Route::post('/registry/products/add', [RegistryController::class, 'addProducts'])->name('registry.add');
    Route::get('/registry/{url?}', [RegistryController::class, 'showSpecificRegistry'])->name('registry.showSpecific');
});

// Routes for guests
Route::get('/registry/{url?}/login', [RegistryController::class, 'guestLogin'])->name('registry.guestLogin');
Route::post('/registry/{url?}/login', [RegistryController::class, 'checkPassword'])->name('registry.checkPassword');
Route::post('/registry/{url?}/guest', [RegistryController::class, 'storeGuestProducts'])->name('registry.storeGuest');
Route::get('/registry/{url?}/guest', [RegistryController::class, 'guestIndex'])->name('registry.guestIndex');
Route::get('/checkout/overview', [CheckoutController::class, 'index'])->name('checkout.overview');
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/webhooks/mollie', [WebhookController::class, 'handle'])->name('webhooks.mollie');

require __DIR__.'/auth.php';
