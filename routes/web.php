<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
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

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/data', [DashboardController::class, 'getData'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.data');

Route::get('/test-broadcast', function() {
    event(new App\Events\TestEvent());
    return 'Event has been sent!';
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('/items', ItemController::class);
    Route::post('items/change-status', [ItemController::class, 'changeStatus'])->name('item.change-status');
    Route::post('items/change-weight', [ItemController::class, 'changeWeight'])->name('item.change-weight');
    Route::post('items/change-statuses', [ItemController::class, 'batchChangeStatus'])->name('item.batch-change-status');
    Route::post('items/change-weights', [ItemController::class, 'batchChangeWeight'])->name('item.batch-change-weight');

    Route::resource('/attributes', AttributeController::class)->middleware('role:admin');
    Route::post('/attributes/delete', [AttributeController::class, 'delete'])->name('attributes.delete');

    Route::resource('/config', ConfigController::class)->middleware('role:admin');
});

require __DIR__ . '/auth.php';
