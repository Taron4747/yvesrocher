<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\AttributesController;
use App\Http\Controllers\HomeController;
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

// Auth
Route::middleware(['setLocale'])->group(function () {

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [HomeController::class, 'index'])
    ->name('home');
    Route::get('/admin', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');
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

Route::put('categories/{category}', [CategoriesController::class, 'update'])
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
->name('categories.update')
->middleware('auth');

Route::delete('sub-categories/{category}', [CategoriesController::class, 'destroySub'])
->name('categories.destroy')
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
 ->name('categories.update')
 ->middleware('auth');
 
 Route::delete('sub-sub-categories/{category}', [CategoriesController::class, 'destroySubSub'])
 ->name('categories.destroy')
 ->middleware('auth');
 
 Route::put('sub-sub-categories/{category}/restore', [CategoriesController::class, 'restoreSubSub'])
 ->name('sub-sub-categories.restore')
 ->middleware('auth');

    // Attributes

    Route::get('attributes', [AttributesController::class, 'index'])
    ->name('attributes')
    ->middleware('auth');

    Route::get('attributes/create', [AttributesController::class, 'create'])
    ->name('attributes.create')
    ->middleware('auth');

    Route::post('attributes', [AttributesController::class, 'store'])
    ->name('attributes.store')
    ->middleware('auth');

    Route::get('attributes/{category}/edit', [AttributesController::class, 'edit'])
    ->name('attributes.edit')
    ->middleware('auth');

    Route::put('attributes/{category}', [AttributesController::class, 'update'])
    ->name('attributes.update')
    ->middleware('auth');

    Route::delete('attributes/{category}', [AttributesController::class, 'destroy'])
    ->name('attributes.destroy')
    ->middleware('auth');

    Route::put('attributes/{category}/restore', [AttributesController::class, 'restore'])
    ->name('attributes.restore')
    ->middleware('auth');

});

 Route::get('/language/{language}', function ($language) {
    Session()->put('locale', $language);
 
    return redirect()->back();
})->name('language');