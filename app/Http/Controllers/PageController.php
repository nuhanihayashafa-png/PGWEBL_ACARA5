<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Point;
use Illuminate\Support\Facades\DB; // Menggunakan Facade agar lebih rapi

class MapController extends Controller
{
    public function map()
    {
        $data = [
            'title' => 'Peta Yogyakarta',
        ];

        $points = Point::select(
            '*',
            DB::raw('ST_X(geom::geometry) as longitude'),
            DB::raw('ST_Y(geom::geometry) as latitude')
        )->get();

        // Mengirim array $data dan $points
        return view('map', $data, compact('points'));
    }

    public function table()
    {
        $data = [
            'title' => 'Tabel Data Lokasi',
        ];

        $points = Point::select(
            'id', 'name', 'description', 'image',
            DB::raw('ST_X(geom::geometry) as longitude'),
            DB::raw('ST_Y(geom::geometry) as latitude')
        )->get();

        // Jangan lupa kirim $data ke view table juga
        return view('table', $data, compact('points'));
    }
}
