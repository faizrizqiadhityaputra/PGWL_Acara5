<?php

namespace App\Http\Controllers;

use App\Models\polylinesModel;
use Illuminate\Http\Request;

class polylinesController extends Controller
{
    protected $polylines;
     public function __construct()
    {
        $this->polylines=new polylinesModel();
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
        $request->validate([
            'geometry_polylines' => 'required',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ],
        [
            'geometry_polylines.required' => 'Geometry polylines is required.',
            'name.required' => 'Field name harus diisi.',
            'name.string' => 'Field name harus berupa string.',
            'name.max' => 'Field name tidak boleh lebih dari 255 karakter.',
        ]);

       $data = [
            'geom' => $request->geometry_polylines,
            'name' => $request->name,
            'description' => $request->description,
        ];

        // Simpan data ke database
        $this->polylines->create($data);

        // Kembali ke halaman peta
        return redirect()->route('peta')->with('success', 'polylines created successfully.');
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
        //
    }
}
