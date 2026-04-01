<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\polylinesModel; // Pastikan huruf besar/kecilnya sesuai dengan file aslinya

class PolylinesController extends Controller
{
    // 1. Deklarasikan variabel agar tidak error di Intelephense
    protected $polylines;

    public function __construct()
    {
        $this->polylines = new polylinesModel();
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
        // 2. Tambahkan validasi input
        $request->validate(
            [
                'geometry_polyline' => 'required', // Pastikan "name" di input form HTML-mu adalah "geometry_polyline"
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ],
            [
                'geometry_polyline.required' => 'Field geometry polyline harus diisi.',
                'name.required' => 'Field name harus diisi.',
                'name.string' => 'Field name harus berupa string.',
                'name.max' => 'Field name tidak boleh lebih dari 255 karakter.',
                'description.string' => 'Field description harus berupa string.',
            ]
        );

        // 3. Siapkan data yang akan disimpan
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_polyline,
            'image' => '', // Tambahkan baris ini untuk menghindari error Not Null
        ];

        // 4. Simpan data ke database & Redirect Back
        if (!$this->polylines->create($data)) {
            // Jika gagal:
            return redirect()->back()->with('error', 'Gagal menyimpan data polylines.');
        }

        // Jika sukses: Kembali ke halaman sebelumnya (peta)
        return redirect()->back()->with('success', 'Data polylines berhasil disimpan.');
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
