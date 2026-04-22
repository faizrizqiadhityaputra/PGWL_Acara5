<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\polylinesController;
use App\Http\Controllers\PageController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/peta', [PageController::class, 'peta'])->name('peta');
Route::get('/tabel', [PageController::class, 'tabel'])->name('tabel');

Route::post('/points', [PointsController::class, 'store'])
    ->name('points.store');

Route::post('/polylines', [polylinesController::class, 'store'])
    ->name('polyline.store');

Route::post('/polygons', [PolygonController::class, 'store'])
    ->name('polygons.store');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
