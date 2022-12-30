<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::group(['prefix' => 'app', 'middleware' => ['auth', 'verified']],function() {
  Route::get('/{folder?}', [AppController::class, 'index'])->name('app.dashboard');

  Route::post('/folders', [FolderController::class, 'store'])->name('app.folders.store');
  Route::put('/folders', [FolderController::class, 'update'])->name('app.folders.update');
  Route::delete('/folders', [FolderController::class, 'destroy'])->name('app.folders.destroy');

  Route::post('/{folder?}/files', [FileController::class, 'store'])->name('app.files.store');
  Route::put('/files/{file}', [FileController::class, 'update'])->name('app.files.update');
  Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('app.files.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
