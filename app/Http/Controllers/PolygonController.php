<?php

namespace App\Http\Controllers;

use App\Models\PolygonModel;
use Illuminate\Http\Request;

class PolygonController extends Controller
{
    // Menggunakan nama yang konsisten
    protected $polygon;

    public function __construct(PolygonModel $polygon)
    {
        $this->polygon = $polygon;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'geometry_polygon' => 'required',
            'name' => 'required|string|max:255',
        ], [
            'geometry_polygon.required' => 'Field geometry polygon harus diisi.',
            'name.required' => 'Field name harus diisi.',
            'name.string' => 'Field name harus berupa string.',
            'name.max' => 'Field name tidak boleh lebih dari 255 karakter.',
        ]);

        $data = [
            'geom' => $request->geometry_polygon,
            'name' => $request->name,
            'description' => $request->description ?? null,
        ];

        // PERBAIKAN: Gunakan $this->polygon (tanpa 's') sesuai deklarasi di atas
        $this->polygon->create($data);

        // Kembali ke halaman peta
        return redirect()->route('peta')->with('success', 'Polygon berhasil disimpan!');
    }

    // ... method lainnya tetap sama
}
