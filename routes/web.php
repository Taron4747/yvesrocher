<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\FiltersController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BannersController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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

// Auth

Route::get('/language/{language}', function ($language) {
    Session()->put('locale', $language);
    return redirect()->back();
})->name('language');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthenticatedSessionController::class, 'register'])->name('register');
    Route::post('/register', [AuthenticatedSessionController::class, 'postRegister']);
    Route::get('/forgot-password', [AuthenticatedSessionController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthenticatedSessionController::class, 'postForgotPassword'])->name('password.email');

    // форма сброса пароля
    Route::get('/reset-password/{token}', [AuthenticatedSessionController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthenticatedSessionController::class, 'postResetPasswordre'])->name('password.update');
});
Route::get('/email/verify', function () {
    return response()->json(['message' => 'Verify your email address.']);
})->middleware('auth')->name('verification.notice');

// Email verification handler
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Email verified successfully.']);
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend verification email
Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification email resent.']);
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');


Route::middleware(['setLocale'])->group(function () {
    
    Route::get('test', [CatalogController::class, 'test']);
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard
Route::get('/admin', [DashboardController::class, 'index'])
->name('dashboard')
->middleware('auth');
Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::prefix('admin')->group(function () {

        Route::post('/search', [HomeController::class, 'search'])->name('search');
        
        // Users

        Route::get('users', [UsersController::class, 'index'])
            ->name('users')
            ->middleware('auth');

        Route::get('users/create', [UsersController::class, 'create'])
            ->name('users.create')
            ->middleware('auth');

        Route::post('users', [UsersController::class, 'store'])
            ->name('users.store')
            ->middleware('auth');

        Route::get('users/{user}/edit', [UsersController::class, 'edit'])
            ->name('users.edit')
            ->middleware('auth');

        Route::put('users/{user}', [UsersController::class, 'update'])
            ->name('users.update')
            ->middleware('auth');

        Route::delete('users/{user}', [UsersController::class, 'destroy'])
            ->name('users.destroy')
            ->middleware('auth');

        Route::put('users/{user}/restore', [UsersController::class, 'restore'])
            ->name('users.restore')
            ->middleware('auth');

        // Organizations

        Route::get('organizations', [OrganizationsController::class, 'index'])
            ->name('organizations')
            ->middleware('auth');

        Route::get('organizations/create', [OrganizationsController::class, 'create'])
            ->name('organizations.create')
            ->middleware('auth');

        Route::post('organizations', [OrganizationsController::class, 'store'])
            ->name('organizations.store')
            ->middleware('auth');

        Route::get('organizations/{organization}/edit', [OrganizationsController::class, 'edit'])
            ->name('organizations.edit')
            ->middleware('auth');

        Route::put('organizations/{organization}', [OrganizationsController::class, 'update'])
            ->name('organizations.update')
            ->middleware('auth');

        Route::delete('organizations/{organization}', [OrganizationsController::class, 'destroy'])
            ->name('organizations.destroy')
            ->middleware('auth');

        Route::put('organizations/{organization}/restore', [OrganizationsController::class, 'restore'])
            ->name('organizations.restore')
            ->middleware('auth');

        // Contacts

        Route::get('contacts', [ContactsController::class, 'index'])
            ->name('contacts')
            ->middleware('auth');

        Route::get('contacts/create', [ContactsController::class, 'create'])
            ->name('contacts.create')
            ->middleware('auth');

        Route::post('contacts', [ContactsController::class, 'store'])
            ->name('contacts.store')
            ->middleware('auth');

        Route::get('contacts/{contact}/edit', [ContactsController::class, 'edit'])
            ->name('contacts.edit')
            ->middleware('auth');

        Route::put('contacts/{contact}', [ContactsController::class, 'update'])
            ->name('contacts.update')
            ->middleware('auth');

        Route::delete('contacts/{contact}', [ContactsController::class, 'destroy'])
            ->name('contacts.destroy')
            ->middleware('auth');

        Route::put('contacts/{contact}/restore', [ContactsController::class, 'restore'])
            ->name('contacts.restore')
            ->middleware('auth');

        // Reports

        Route::get('reports', [ReportsController::class, 'index'])
            ->name('reports')
            ->middleware('auth');

        // Images

        Route::get('/img/{path}', [ImagesController::class, 'show'])
            ->where('path', '.*')
            ->name('image');


            // Categories

        Route::get('categories', [CategoriesController::class, 'index'])
        ->name('categories')
        ->middleware('auth');

        Route::get('categories/create', [CategoriesController::class, 'create'])
        ->name('categories.create')
        ->middleware('auth');

        Route::post('categories', [CategoriesController::class, 'store'])
        ->name('categories.store')
        ->middleware('auth');

        Route::get('categories/{category}/edit', [CategoriesController::class, 'edit'])
        ->name('categories.edit')
        ->middleware('auth');

        Route::post('categories/{category}', [CategoriesController::class, 'update'])
        ->name('categories.update')
        ->middleware('auth');

        Route::delete('categories/{category}', [CategoriesController::class, 'destroy'])
        ->name('categories.destroy')
        ->middleware('auth');

        Route::put('categories/{category}/restore', [CategoriesController::class, 'restore'])
        ->name('categories.restore')
        ->middleware('auth');

        // Sub Categories
        Route::get('sub-categories', [CategoriesController::class, 'indexSub'])
        ->name('sub-categories')
        ->middleware('auth');

        Route::get('sub-categories/create', [CategoriesController::class, 'createSub'])
        ->name('sub-categories.create')
        ->middleware('auth');

        Route::post('sub-categories', [CategoriesController::class, 'storeSub'])
        ->name('sub-categories.store')
        ->middleware('auth');

        Route::get('sub-categories/{category}/edit', [CategoriesController::class, 'editSub'])
        ->name('sub-categories.edit')
        ->middleware('auth');

        Route::put('sub-categories/{category}', [CategoriesController::class, 'updateSub'])
        ->name('sub-categories.update')
        ->middleware('auth');

        Route::delete('sub-categories/{category}', [CategoriesController::class, 'destroySub'])
        ->name('sub-categories.destroy')
        ->middleware('auth');

        Route::put('sub-categories/{category}/restore', [CategoriesController::class, 'restoreSub'])
        ->name('sub-categories.restore')
        ->middleware('auth');

        // Sub Categories
        Route::get('sub-sub-categories', [CategoriesController::class, 'indexSubSub'])
        ->name('sub-sub-categories')
        ->middleware('auth');
        
        Route::get('sub-sub-categories/create', [CategoriesController::class, 'createSubSub'])
        ->name('sub-sub-categories.create')
        ->middleware('auth');
        
        Route::post('sub-sub-categories', [CategoriesController::class, 'storeSubSub'])
        ->name('sub-sub-categories.store')
        ->middleware('auth');
        
        Route::get('sub-sub-categories/{category}/edit', [CategoriesController::class, 'editSubSub'])
        ->name('sub-sub-categories.edit')
        ->middleware('auth');
        
        Route::put('sub-sub-categories/{category}', [CategoriesController::class, 'updateSubSub'])
        ->name('sub-sub-categories.update')
        ->middleware('auth');
        
        Route::delete('sub-sub-categories/{category}', [CategoriesController::class, 'destroySubSub'])
        ->name('sub-sub-categories.destroy')
        ->middleware('auth');
        
        Route::put('sub-sub-categories/{category}/restore', [CategoriesController::class, 'restoreSubSub'])
        ->name('sub-sub-categories.restore')
        ->middleware('auth');

            // Filters

            Route::get('filter', [FiltersController::class, 'index'])
            ->name('filter')
            ->middleware('auth');

            Route::get('filter/create', [FiltersController::class, 'create'])
            ->name('filter.create')
            ->middleware('auth');

            Route::post('filter', [FiltersController::class, 'store'])
            ->name('filter.store')
            ->middleware('auth');

            Route::get('filter/{filter}/edit', [FiltersController::class, 'edit'])
            ->name('filter.edit')
            ->middleware('auth');

            Route::put('filter/{filter}', [FiltersController::class, 'update'])
            ->name('filter.update')
            ->middleware('auth');

            Route::delete('filter/{filter}', [FiltersController::class, 'destroy'])
            ->name('filter.destroy')
            ->middleware('auth');

            Route::put('filter/{filter}/restore', [FiltersController::class, 'restore'])
            ->name('filter.restore')
            ->middleware('auth');



            // Products

            Route::get('product', [ProductController::class, 'index'])
            ->name('product')
            ->middleware('auth');

            Route::get('product/create', [ProductController::class, 'create'])
            ->name('product.create')
            ->middleware('auth');

            Route::post('product', [ProductController::class, 'store'])
            ->name('product.store')
            ->middleware('auth');

            Route::get('product/{product}/edit', [ProductController::class, 'edit'])
            ->name('product.edit')
            ->middleware('auth');

            Route::post('product/{product}', [ProductController::class, 'update'])
            ->name('product.update')
            ->middleware('auth');

            Route::delete('product/{product}', [ProductController::class, 'destroy'])
            ->name('product.destroy')
            ->middleware('auth');

            Route::put('product/{product}/restore', [ProductController::class, 'restore'])
            ->name('product.restore')
            ->middleware('auth');
        // Organizations

        Route::get('banners', [BannersController::class, 'index'])
        ->name('banners')
        ->middleware('auth');

        Route::get('banners/create', [BannersController::class, 'create'])
        ->name('banners.create')
        ->middleware('auth');

        Route::post('banners', [BannersController::class, 'store'])
        ->name('banners.store')
        ->middleware('auth');

        Route::get('banners/{banner}/edit', [BannersController::class, 'edit'])
        ->name('banners.edit')
        ->middleware('auth');

        Route::post('banners/{banner}', [BannersController::class, 'update'])
        ->name('banners.update')
        ->middleware('auth');

        Route::delete('banners/{banner}', [BannersController::class, 'destroy'])
        ->name('banners.destroy')
        ->middleware('auth');

        Route::put('banners/{banner}/restore', [BannersController::class, 'restore'])
        ->name('banners.restore')
        ->middleware('auth');
        Route::get('category/filters/{id}', [CategoriesController::class, 'categoryFilters'])
        ->name('category.filters')
        ->middleware('auth');
        Route::post('product-count-change', [ProductController::class, 'productCountChange'])
        ->name('product.count.change')
        ->middleware('auth');
        Route::post('change-banner', [BannersController::class, 'changeBanner'])
        ->name('banner.change')
        ->middleware('auth');
    });
    Route::get('category/{id}', [CatalogController::class, 'getByCategory'])
    ->name('category');

    Route::get('subcategory/{id}', [CatalogController::class, 'getBySubCategory'])
    ->name('subcategory');
    Route::get('subsubcategory/{id}', [CatalogController::class, 'getBySubSubCategory'])
    ->name('subsubcategory');

    Route::get('product/{id}', [CatalogController::class, 'product'])
    ->name('productbyid');

    Route::get('products', [CatalogController::class, 'products'])
    ->name('products');
    Route::get('random-categories', [CatalogController::class, 'randomCategories']);

});
   
   