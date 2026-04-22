<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PointsModel;
use App\Models\PolylinesModel; // Tambahkan ini
use App\Models\PolygonsModel;  // Tambahkan ini

class Apicontroller extends Controller
{
    public function __construct()
    {
        //
    }

    // Fungsi untuk memanggil data titik kordinat
    public function geojson_points()
    {
        $model = new PointsModel();
        $data = $model->getGeoJsonPoints();
        return response()->json($data);
    }

    // Fungsi untuk memanggil data garis (polylines)
    public function geojson_polylines()
    {
        $model = new PolylinesModel();
        $data = $model->getGeoJsonPolylines();
        return response()->json($data);
    }

    // Fungsi untuk memanggil data area (polygons)
    public function geojson_polygons()
    {
        $model = new PolygonsModel();
        $data = $model->getGeoJsonPolygons();
        return response()->json($data);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
