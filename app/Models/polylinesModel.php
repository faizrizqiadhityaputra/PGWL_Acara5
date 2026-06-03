<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class polylinesModel extends Model
{
    protected $table = 'polyline';
    protected $guarded = ['id'];

    public function geojson()
    {
        $polylines = $this->select(DB::raw('id, ST_AsGeoJSON(geom) as geojson, name, description, image, created_at, updated_at'))
            ->get();

        return $this->build_geojson($polylines);
    }

    // Fungsi baru untuk mengambil satu polyline berdasarkan ID
    public function geojson_polyline($id)
    {
        $polylines = $this->select(DB::raw('id, ST_AsGeoJSON(geom) as geojson, name, description, image, created_at, updated_at'))
            ->where('id', $id)
            ->get();

        return $this->build_geojson($polylines);
    }

    // Fungsi bantuan untuk menyusun struktur GeoJSON agar rapi
    private function build_geojson($polylines)
    {
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($polylines as $p) {
            $features = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geojson),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at
                ]
            ];

            array_push($geojson['features'], $features);
        }

        return $geojson;
    }
}
