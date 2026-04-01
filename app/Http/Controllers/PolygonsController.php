<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Pastikan huruf besar/kecil "polygonsModel" sesuai dengan nama file aslinya di folder app/Models
use App\Models\polygonsModel;

class PolygonsController extends Controller
{
    // Deklarasikan variabel
    protected $polygons;

    // fungsi untuk mengkoneksikan
    public function __construct()
    {
        $this->polygons = new polygonsModel();
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate(
            [
                'geometry_polygons' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ],
            [
                'geometry_polygons.required' => 'Field geometry polygons harus diisi.',
                'name.required' => 'Field name harus diisi.',
                'name.string' => 'Field name harus berupa string.',
                'name.max' => 'Field name tidak boleh lebih dari 255 karakter.',
                'description.string' => 'Field description harus berupa string.',
            ]
        );

        // 2. Siapkan data
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_polygons,
            'image' => '', // 💡 Tambahan: Mencegah error "Not null violation" di database
        ];

        // 3. Simpan data ke database (Cukup 1 kali eksekusi)
        if (!$this->polygons->create($data)) {
            // Jika gagal, kembali ke form dengan pesan error
            return redirect()->back()->with('error', 'Gagal menyimpan data polygons.');
        }

        // 4. Jika sukses, kembali ke halaman peta dengan pesan sukses
        return redirect()->back()->with('success', 'Data polygons berhasil disimpan.');
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
