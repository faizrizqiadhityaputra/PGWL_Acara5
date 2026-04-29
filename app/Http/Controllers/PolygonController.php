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
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'geometry_polygon.required' => 'Field geometry polygon harus diisi.',
            'name.required' => 'Field name harus diisi.',
            'name.string' => 'Field name harus berupa string.',
            'name.max' => 'Field name tidak boleh lebih dari 255 karakter.',
            'image.mines' => 'File gambar harus berformat JPEG, PNG, atau JPG.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
        ]);

         // Membuat direktori jika belum ada
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }
        //Uploaded image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => $request->geometry_polygon,
            'name' => $request->name,
            'description' => $request->description ?? null,
            'image' => $name_image,

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
