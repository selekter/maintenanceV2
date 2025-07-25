<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\MaintainController;
use App\Http\Controllers\maintenanceHistoryController;
use App\Http\Controllers\OilController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
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

Route::get('/maintain', [MaintainController::class, 'index'])->name('maintain.index');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('report')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('report.index');
        Route::get('/create', [ReportController::class, 'create'])->name('report.create');
        Route::post('/create', [ReportController::class, 'store'])->name('report.store');
        Route::get('/edit/{id}', [ReportController::class, 'edit'])->name('report.edit');
        Route::put('/edit/{id}', [ReportController::class, 'updateStat'])->name('report.updatestat');
        Route::delete('/edit/{id}', [ReportController::class, 'editDestroy'])->name('reportedit.destroy');
        Route::get('/drivers', [DriverController::class, 'index'])->name('driver.show');
        Route::delete('/delete/{id}', [ReportController::class, 'destroy'])->name('report.destroy');
    });

    Route::prefix('maintenance-history')->group(function() {
        Route::get('/', [maintenanceHistoryController::class, 'index'])->name('mainHistory.show');
        Route::get('/search', [maintenanceHistoryController::class, 'search'])->name('mainHistory.search');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
