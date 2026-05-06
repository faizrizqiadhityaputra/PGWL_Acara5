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
                'image.mines' => 'File gambar harus berformat JPEG, PNG, atau JPG.',
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
