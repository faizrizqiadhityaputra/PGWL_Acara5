<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pointsModel;
use App\Models\polylinesModel;
use App\Models\polygonModel;

class ApiController extends Controller
{
    protected $points;
    protected $polylines;
    protected $polygon;

    public function __construct()
    {
        $this->points = new pointsModel();
        $this->polylines = new polylinesModel();
        $this->polygon = new polygonModel();
    }

    public function geojson_points()
    {
        $data = $this->points->geojson();
        return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
    }

    public function geojson_polylines()
    {
        $data = $this->polylines->geojson();
        return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
    }

    public function geojson_polygons()
    {
        $data = $this->polygon->geojson();
        return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
    }
}
