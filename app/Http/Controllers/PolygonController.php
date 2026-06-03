<?php

namespace App\Http\Controllers;

use App\Models\PolygonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Perbaikan typo sebelumnya (Support_Facades jadi Support\Facades)

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
            'image.mimes' => 'File gambar harus berformat JPEG, PNG, atau JPG.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move(public_path('storage/images'), $name_image);
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

    // --- TAMBAHAN BARU: FUNGSI EDIT ---
    public function edit(string $id)
    {
         $data = [
            'title' => 'Edit Polygon',
            'id' => $id
        ];

        return view('edit_polygon', $data);
    }

    // --- TAMBAHAN BARU: FUNGSI UPDATE ---
    public function update(Request $request, string $id)
    {
        $request->validate([
            'geometry_polygon' => 'required',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $polygonData = $this->polygon->findOrFail($id);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($polygonData->image != null) {
                $path = public_path('storage/images/' . $polygonData->image);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            // Upload gambar baru
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move(public_path('storage/images'), $name_image);
        } else {
            // Tetap gunakan gambar lama
            $name_image = $polygonData->image;
        }

        $data = [
            'geom' => $request->geometry_polygon,
            'name' => $request->name,
            'description' => $request->description ?? null,
            'image' => $name_image,
        ];

        $polygonData->update($data);

        return redirect()->route('peta')->with('success', 'Data polygon berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        $polygonData = $this->polygon->findOrFail($id);
        $image = $polygonData->image;

        if ($image != null) {
            $path = public_path('storage/images/' . $image);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        if (!$polygonData->delete()) {
            return redirect()->route('peta')
                ->with('error', 'Gagal menghapus data polygons.');
        }

        return redirect()->route('peta')
            ->with('success', 'Data polygons berhasil dihapus.');
    }
}
