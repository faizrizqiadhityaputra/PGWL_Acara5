<?php

namespace App\Http\Controllers;

use App\Models\pointsModel;
use Illuminate\Http\Request;

class PointsController extends Controller
{
    protected $points;

    public function __construct()
    {
        $this->points = new pointsModel();
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'geometry_point' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ],
            [
                'geometry_point.required' => 'Geometry point is required.',
                'name.required' => 'Field name harus diisi.',
                'name.string' => 'Field name harus berupa string.',
                'name.max' => 'Field name tidak boleh lebih dari 255 karakter.',
                'image.mimes' => 'File gambar harus berformat JPEG, PNG, atau JPG.',
                'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB',
            ]
        );

        // Membuat direktori jika belum ada
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        //Uploaded image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => $request->geometry_point,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Simpan data ke database
        $this->points->create($data);

        // Kembali ke halaman peta
        return redirect()->route('peta')->with('success', 'Point created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $data = [
            'title' => 'Edit Point',
            'id' => $id
        ];

        return view('edit_point', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi data
        $request->validate([
            'geometry' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Cari data lama berdasarkan ID
        $point = $this->points->find($id);

        // Cek apakah user mengupload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage jika ada
            if ($point->image != null && file_exists('./storage/images/' . $point->image)) {
                unlink('./storage/images/' . $point->image);
            }

            // Upload gambar baru
            $image = $request->file('image');
            $name_image = time() . "_point." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            // Jika tidak ada gambar baru, tetap gunakan nama gambar lama
            $name_image = $point->image;
        }

        // Data yang akan diupdate
        $data = [
            'geom' => $request->geometry,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Simpan pembaruan ke database
        $point->update($data);

        // Kembali ke halaman peta utama
        return redirect()->route('peta')->with('success', 'Data point berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //mencari nama file gambar
        $image = $this->points->find($id)->image;

        //menghapus file gambar jika ada
        if ($image != null) {
            if (file_exists('./storage/images/' . $image)) {
                unlink('./storage/images/' . $image);
            }
        }

        //menghapus data dari database
        if (!$this->points->destroy($id)) {
            return redirect()->route('peta')
                ->with('error', 'Gagal menghapus data point.');
        }

        //kembali ke halaman peta
        return redirect()->route('peta')
            ->with('success', 'Data point berhasil dihapus.');
    }
}
