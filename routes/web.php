<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\polylinesController;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'landingpage'])->name('home');

Route::get('/peta', [PageController::class, 'peta'])
->middleware(['auth', 'verified'])->name('peta');
Route::get('/tabel', [PageController::class, 'tabel'])->name('tabel');

// --- Points Route ---
Route::post('/points', [PointsController::class, 'store'])->name('points.store');
Route::get('/points/{id}', [PointsController::class, 'edit'])->name('points.edit');
Route::put('/points/{id}', [PointsController::class, 'update'])->name('points.update');
Route::delete('/delete-points/{id}', [PointsController::class, 'destroy'])->name('points.delete');


// --- Polylines Route ---
Route::post('/polylines', [polylinesController::class, 'store'])->name('polyline.store');
Route::get('/polylines/{id}', [polylinesController::class, 'edit'])->name('polylines.edit');
Route::put('/polylines/{id}', [polylinesController::class, 'update'])->name('polylines.update');
Route::delete('/polylines/{id}', [polylinesController::class, 'destroy'])->name('polylines.delete');


// --- Polygon Route ---
Route::post('/polygons', [PolygonController::class, 'store'])->name('polygons.store');
// Route mengambil polygon (Untuk membuka halaman edit)
Route::get('/polygons/{id}', [PolygonController::class, 'edit'])->name('polygons.edit');
// Route update polygon (Untuk memproses form simpan)
Route::put('/polygons/{id}', [PolygonController::class, 'update'])->name('polygons.update');
// Route delete polygon
Route::delete('/polygons/{id}', [PolygonController::class, 'destroy'])->name('polygons.delete');


// --- Dashboard ---
// PERBAIKAN: Mengarahkan rute dashboard ke fungsi landingpage agar memuat home.blade.php
Route::get('/dashboard', [PageController::class, 'landingpage'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
