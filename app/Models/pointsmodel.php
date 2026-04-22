<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PointsModel extends Model
{
    // Nama tabel di database
    protected $table = 'points';

    // Kolom yang tidak boleh diisi (kosongkan agar semua bisa diisi)
    protected $guarded = [];

    // Matikan timestamps jika tabel tidak punya kolom created_at & updated_at
    public $timestamps = false;

    // Mengambil semua titik dan mengubah koordinat geom menjadi format GeoJSON
    public function getGeoJsonPoints()
    {
        // 1. Ambil data dari database dan simpan di variabel $points
        $points = $this->select(
            'id',
            'name',
            'description',
            // 'image_url', <--- Baris ini sudah dihapus
            DB::raw('ST_AsGeoJSON(geom) as geojson')
        )->get();

        // 2. Lakukan perulangan (map) untuk membentuk format Feature standar
        $features = $points->map(function ($point) {
            return [
                'type' => 'Feature',
                'geometry' => json_decode($point->geojson), // Decode string json ke object
                'properties' => [
                    'id'          => $point->id,
                    'name'        => $point->name,
                    'description' => $point->description
                    // 'image_url'   => $point->image_url <--- Baris ini juga dihapus
                ]
            ];
        });

        // 3. Gabungkan semua dan kembalikan sebagai FeatureCollection
        return [
            'type'     => 'FeatureCollection',
            'features' => $features
        ];
    }
}
