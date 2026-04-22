<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class polylinesModel extends Model
{
    protected $table = 'polylines_tables';
    protected $guarded = ['id'];

    public function getGeoJsonPolylines()
    {
        $polylines = $this->select(
            'id',
            'name',
            'description',
            DB::raw('ST_AsGeoJSON(geom) as geojson')
        )->get();

        // 2. Bentuk format Feature standar
        $features = $polylines->map(function ($polyline) {
            return [
                'type' => 'Feature',
                'geometry' => json_decode($polyline->geojson),
                'properties' => [
                    'id'          => $polyline->id,
                    'name'        => $polyline->name,
                    'description' => $polyline->description,
                ]
            ];
        });

        // 3. Kembalikan sebagai FeatureCollection
        return [
            'type'     => 'FeatureCollection',
            'features' => $features
        ];
    }
}
