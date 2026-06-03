<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Semua points
Route::get('/points', [ApiController::class, 'geojson_points'])->name('geojson.points');
// Detail point berdasarkan ID
Route::get('/points/{id}', [ApiController::class, 'geojson_point'])->name('geojson.point');

// Semua Polyline
Route::get('/polylines', [ApiController::class, 'geojson_polylines'])->name('geojson.polylines');
// Detail polyline berdasarkan ID
Route::get('/polylines/{id}', [ApiController::class, 'geojson_polyline'])->name('geojson.polyline');

// Semua Polygon
Route::get('/polygons', [ApiController::class, 'geojson_polygons'])->name('geojson.polygons');
// TAMBAHAN BARU: Detail polygon berdasarkan ID
Route::get('/polygons/{id}', [ApiController::class, 'geojson_polygon'])->name('geojson.polygon');
