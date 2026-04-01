<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pointsmodel extends Model
{
    protected $table = 'points';

    // Kosongkan saja untuk testing agar semua data diizinkan masuk
    protected $guarded = [];

    // WAJIB DITAMBAHKAN: Mematikan pencarian kolom waktu
    public $timestamps = false;
}
