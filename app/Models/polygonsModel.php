<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk fungsi DB::raw

class polygonsModel extends Model
{
    protected $table = 'polygons_tables';
    protected $guarded = ['id'];

    public function getGeoJsonPolygons()
    {
        // 1. Ambil data dan hitung luas area menggunakan fungsi PostGIS (ST_Area)
        $polygons = $this->select(
            'id',
            'name',
            'description',
            DB::raw('ST_AsGeoJSON(geom) as geojson'),
            // Menghitung luas dalam m2, lalu dikonversi ke Hektar (dibagi 10.000)
            DB::raw('ST_Area(geom::geography) / 10000 as area_hektar')
        )->get();

        // 2. Transformasi ke format Feature GeoJSON
        $features = $polygons->map(function ($polygon) {
            return [
                'type' => 'Feature',
                'geometry' => json_decode($polygon->geojson),
                'properties' => [
                    'id'          => $polygon->id,
                    'name'        => $polygon->name,
                    'description' => $polygon->description,
                    'area_ha'     => round($polygon->area_hektar, 2) . ' Hektar' // Pembulatan 2 desimal
                ]
            ];
        });

        // 3. Return sebagai FeatureCollection
        return [
            'type'     => 'FeatureCollection',
            'features' => $features
        ];
    }
}
