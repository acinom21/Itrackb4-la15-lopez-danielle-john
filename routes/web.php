<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

// Front page
Route::get('/', [HomeController::class, 'index'])->name('home');

// These must come BEFORE the resource route
Route::get('/books/feature', [BookController::class, 'feature'])
    ->name('books.feature');

Route::get('/books/filter/{genre?}', [BookController::class, 'redirectOldFilter'])
    ->name('books.filter');

// auth on five actions only; index and show stay public.
// whereNumber means {book} only matches digits, so /books/create can never be read as a book id.
Route::resource('books', BookController::class)
    ->whereNumber('book')
    ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'auth');

Route::get('/teachers/featured', [TeacherController::class, 'featured'])
    ->name('teachers.featured');

Route::resource('teachers', TeacherController::class)
    ->only(['index', 'show']);

// Register
Route::get('/register',  [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Login / Logout
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login',  [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');