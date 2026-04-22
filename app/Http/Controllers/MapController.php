<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Point;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    // 1. Fungsi untuk menampilkan halaman (view) Peta
    public function map()
    {
        $data = [
            'title' => 'Peta Yogyakarta',
        ];

        // Karena kita akan me-load data lewat GeoJSON di JavaScript,
        // kamu tidak wajib melempar data query $points ke view map.
        return view('map', $data);
    }

    // 2. Fungsi untuk menampilkan halaman (view) Tabel
    public function table()
    {
        $data = [
            'title' => 'Tabel Data Lokasi',
        ];

        // Untuk halaman tabel, tidak perlu ST_X dan ST_Y jika ada data Polygon.
        // Cukup ambil kolom standar saja agar tidak error.
        $points = Point::select('id', 'name', 'description', 'image')->get();

        return view('table', $data, compact('points'));
    }

    // 3. TAMBAHKAN INI: Fungsi khusus API untuk dipanggil oleh Leaflet JavaScript
    public function getMapData()
    {
        // Menggunakan ST_AsGeoJSON agar Polyline & Polygon ikut terbaca
        $points = Point::select(
            'id', 'name', 'description', 'image',
            DB::raw('ST_AsGeoJSON(geom::geometry) as geojson')
        )->get();

        $features = $points->map(function($item) {
            return [
                'type' => 'Feature',
                'geometry' => json_decode($item->geojson),
                'properties' => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'image' => $item->image,
                ]
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }
}
