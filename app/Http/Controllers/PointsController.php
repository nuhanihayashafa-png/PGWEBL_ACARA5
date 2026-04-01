<?php

namespace App\Http\Controllers;

use App\Models\pointsmodel; // Pastikan huruf besar/kecilnya sama persis dengan nama file Model-mu
use Illuminate\Http\Request;

class PointsController extends Controller
{
    // 1. Deklarasikan variabel global di sini (Solusi agar tidak perlu mematikan alert Intelephense)
    protected $points;

    public function __construct()
    {
        // 2. Tambahkan titik koma (;) di akhir
        $this->points = new pointsmodel();
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
        // 1. Validasi input (Harus diletakkan di paling atas)
        $request->validate(
            [
                'geometry_point' => 'required',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
            ],
            [
                'geometry_point.required' => 'Field geometry point harus diisi.',
                'name.required' => 'Field name harus diisi.',
                'name.string' => 'Field name harus berupa string.',
                'name.max' => 'Field name tidak boleh lebih dari 255 karakter.',
                'description.string' => 'Field description harus berupa string.',
            ]
        );

        // 2. Siapkan data yang akan disimpan
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geometry_point,
        ];

        // 3. Simpan data ke database (Cukup 1 kali saja)
        // Jika gagal:
        // Jika gagal:
        if (!$this->points->create($data)) {
            return redirect()->back()->with('error', 'Gagal menyimpan data point.');
        }

        // Jika sukses: Kembali ke halaman peta
        return redirect()->back()->with('success', 'Data point berhasil disimpan.');
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
