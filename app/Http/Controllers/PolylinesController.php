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
                'image.mimes' => 'File gambar harus berformat JPEG, PNG, atau JPG.',
                'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            ]
        );

        $directory = public_path('storage/images');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0777, true, true);
        }

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

        $this->polylines->create($data);

        return redirect()->route('peta')->with('success', 'Polylines created successfully.');
    }

    // --- TAMBAHAN BARU: FUNGSI EDIT ---
    public function edit(string $id)
    {
         $data = [
            'title' => 'Edit Polyline',
            'id' => $id
        ];

        return view('edit_polyline', $data);
    }

    // --- TAMBAHAN BARU: FUNGSI UPDATE ---
    public function update(Request $request, string $id)
    {
        $request->validate([
            'geometry_polylines' => 'required', // Sesuaikan nama name pada textarea blade
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $polyline = $this->polylines->findOrFail($id);

        $directory = public_path('storage/images');

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($polyline->image != null) {
                $image_path = public_path('storage/images/' . $polyline->image);
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }
            }

            // Upload baru
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move($directory, $name_image);
        } else {
            $name_image = $polyline->image;
        }

        $data = [
            'geom' => $request->geometry_polylines,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        $polyline->update($data);

        return redirect()->route('peta')->with('success', 'Data polylines berhasil diupdate.');
    }

    public function destroy(string $id)
    {
        $polyline = $this->polylines->findOrFail($id);
        $image = $polyline->image;

        if ($image != null) {
            $image_path = public_path('storage/images/' . $image);
            if (File::exists($image_path)) {
                File::delete($image_path);
            }
        }

        if (!$polyline->delete()) {
            return redirect()->route('peta')
                ->with('error', 'Gagal menghapus data polylines.');
        }

        return redirect()->route('peta')
            ->with('success', 'Data polylines berhasil dihapus.');
    }
}
