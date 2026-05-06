<?php

namespace App\Http\Controllers;

use App\Models\polylinesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class polylinesController extends Controller
{
    protected $polylines;

    public function __construct()
    {
        $this->polylines = new polylinesModel();
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'geometry_polylines' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ],
            [
                'geometry_polylines.required' => 'Geometry polylines is required.',
                'name.required' => 'Field name harus diisi.',
                'name.string' => 'Field name harus berupa string.',
                'name.max' => 'Field name tidak boleh lebih dari 255 karakter.',
                'image.mimes' => 'File gambar harus berformat JPEG, PNG, atau JPG.', // Perbaikan typo 'mines' -> 'mimes'
                'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            ]
        );

        // Path direktori ke public/storage/images agar bisa diakses helper asset()
        $directory = public_path('storage/images');

        // Membuat direktori jika belum ada
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0777, true, true);
        }

        // Uploaded image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move($directory, $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => $request->geometry_polylines,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Simpan data ke database
        $this->polylines->create($data);

        // Kembali ke halaman peta
        return redirect()->route('peta')->with('success', 'Polylines created successfully.');
    }

    public function destroy(string $id)
    {
        // Mencari data berdasarkan ID
        $polyline = $this->polylines->findOrFail($id);
        $image = $polyline->image;

        // Menghapus file gambar jika ada di direktori public
        if ($image != null) {
            $image_path = public_path('storage/images/' . $image);
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        // Menghapus data dari database
        if (!$polyline->delete()) {
            return redirect()->route('peta')
                ->with('error', 'Gagal menghapus data polylines.');
        }

        // Kembali ke halaman peta
        return redirect()->route('peta')
            ->with('success', 'Data polylines berhasil dihapus.');
    }
}
