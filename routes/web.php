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

//Points
Route::post('/points', [PointsController::class, 'store'])
    ->name('points.store');
Route::delete('/delete-points/{id}', [PointsController::class, 'destroy'])
    ->name('points.delete');

//Polylines
Route::post('/polylines', [polylinesController::class, 'store'])
    ->name('polyline.store');
Route::delete('/polylines/{id}', [polylinesController::class, 'destroy'])->name('polylines.delete');

    //Polygon
Route::post('/polygons', [PolygonController::class, 'store'])
    ->name('polygons.store');
Route::delete('/polygons/{id}', [PolygonController::class, 'destroy'])->name('polygons.delete');



Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
