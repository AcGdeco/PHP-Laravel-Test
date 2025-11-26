<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public route: anyone can view the contacts list
Route::get('/', [ContactController::class, 'index'])->name('contacts.index');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes: only authenticated users can access create/edit/delete/show
Route::middleware('auth')->group(function () {
	Route::get('/contacts/create', [ContactController::class, 'create'])->name('contacts.create');
	Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
	Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
	Route::get('/contacts/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
	Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
	Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');
});
