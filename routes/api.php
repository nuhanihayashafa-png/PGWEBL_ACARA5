<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// 1. Pastikan Controller di-import di sini
use App\Http\Controllers\Apicontroller;

/*
|--------------------------------------------------------------------------
| API Routes - Proyek RAGA (Responsive Asisten Gawat Darurat)
|--------------------------------------------------------------------------
*/

// Rute default Laravel
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


 //* Menghubungkan URL /api/point ke fungsi 'index' di Apicontroller
Route::get('/point', [Apicontroller::class, 'geojson_points']);
// Rute untuk Polyline
Route::get('/polylines', [Apicontroller::class, 'geojson_polylines']);

// Rute untuk Polygon
Route::get('/polygons', [Apicontroller::class, 'geojson_polygons']);

// Jika kamu ingin menambah data (misal dari Smartwatch IoT)
// Route::post('/point', [Apicontroller::class, 'store']);
