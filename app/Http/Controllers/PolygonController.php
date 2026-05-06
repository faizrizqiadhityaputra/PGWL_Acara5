<?php

namespace App\Http\Controllers;

use App\Models\PolygonModel;
use Illuminate\Http\Request;
use Illuminate\Support_Facades\File;

class PolygonController extends Controller
{
    protected $polygon;

    public function __construct(PolygonModel $polygons)
    {
        // Pastikan variabel ini konsisten digunakan di semua method
        $this->polygon = $polygons;
    }

    public function store(Request $request)
    {
        $request->validate([
            'geometry_polygon' => 'required',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Perbaikan: mimes, bukan mines
        ], [
            'geometry_polygon.required' => 'Field geometry polygon harus diisi.',
            'name.required' => 'Field name harus diisi.',
            'name.string' => 'Field name harus berupa string.',
            'name.max' => 'Field name tidak boleh lebih dari 255 karakter.',
            'image.mimes' => 'File gambar harus berformat JPEG, PNG, atau JPG.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
        ]);

        // Upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());

            // Simpan ke public/storage/images agar sesuai dengan helper asset() di blade
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

    public function destroy(string $id)
    {
        // Gunakan $this->polygon (sesuai constructor)
        $polygonData = $this->polygon->findOrFail($id);
        $image = $polygonData->image;

        // Menghapus file gambar jika ada di folder public/storage/images
        if ($image != null) {
            $path = public_path('storage/images/' . $image);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // Menghapus data dari database
        if (!$polygonData->delete()) {
            return redirect()->route('peta')
                ->with('error', 'Gagal menghapus data polygons.');
        }

        return redirect()->route('peta')
            ->with('success', 'Data polygons berhasil dihapus.');
    }
}
