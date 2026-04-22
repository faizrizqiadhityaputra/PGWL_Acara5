<?php

namespace App\Http\Controllers;

use App\Models\PolygonModel;
use Illuminate\Http\Request;

class PolygonController extends Controller
{
    protected $polygon;

    public function __construct(PolygonModel $polygons)
    {
        $this->polygon = $polygons;
    }

    public function store(Request $request)
    {
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

        $this->polygon->create($data);

        return redirect()->route('peta')->with('success', 'Polygon berhasil disimpan!');
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
