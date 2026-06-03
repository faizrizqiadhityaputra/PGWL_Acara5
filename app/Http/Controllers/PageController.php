<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pointsModel;
use App\Models\polylinesModel;
use App\Models\polygonModel;
use App\Models\user;

class PageController extends Controller
{
    protected $points;
    protected $polylines;
    protected $polygons;
    protected $user;

    public function __construct()
    {
        $this->points = new pointsModel();
        $this->polylines = new polylinesModel();
        $this->polygons = new polygonModel();
        $this->user = new user();
    }

    public function landingpage()
    {
        $data = [
            'title' => 'PGWL',
            'points_count' => $this->points->count(),
            'polylines_count' => $this->polylines->count(),
            'polygons_count' => $this->polygons->count(),
            'user_count' => $this->user->count()
        ];

        return view('home', $data);
    }

    public function peta()
    {
        $data = [
            'title' => 'Peta'
        ];

        return view('map', $data);
    }

    public function tabel()
    {
        $data = [
            'title' => 'Tabel',                     // Sudah diperbaiki (tambah koma)
            'points' => $this->points->all(),
            'polylines' => $this->polylines->all(), // Siap digunakan di blade view tabel
            'polygons' => $this->polygons->all(),   // Siap digunakan di blade view tabel
        ];

        return view('tabel', $data);
    }

    // --- TAMBAHAN BARU: Dashboard Controller ---
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard WebGIS',
            'points_count' => $this->points->count(),
            'polylines_count' => $this->polylines->count(),
            'polygons_count' => $this->polygons->count(),
            'user_count' => $this->user->count()
        ];

        return view('dashboard', $data);
    }
}
