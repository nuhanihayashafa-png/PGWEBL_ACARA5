@extends('layouts.template')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
    body {
        background-color: #f4f7f6; /* Warna background lebih lembut */
        font-family: 'Inter', sans-serif;
    }
    .container {
        margin-top: 50px;
    }
    .card {
        border: none;
        border-radius: 15px; /* Corner lebih membulat */
    }
    .card-header {
        background: linear-gradient(45deg, #4e73df, #224abe); /* Gradient halus */
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 20px;
    }
    .table {
        border-collapse: separate;
        border-spacing: 0 10px; /* Jarak antar baris */
    }
    .table thead th {
        border: none;
        color: #abb5be;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
        background-color: transparent !important;
        padding-bottom: 20px;
    }
    .table tbody tr {
        background-color: white;
        box-shadow: 0 5px 10px rgba(0,0,0,0.05); /* Shadow halus tiap baris */
        transition: transform 0.2s;
    }
    .table tbody tr:hover {
        transform: scale(1.01); /* Efek melayang saat hover */
        background-color: #f8f9fc;
    }
    .table td {
        vertical-align: middle;
        border: none;
        padding: 15px;
    }
    .table td:first-child { border-radius: 10px 0 0 10px; }
    .table td:last-child { border-radius: 0 10px 10px 0; }

    .img-thumbnail {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: 0.3s;
    }
    .img-thumbnail:hover {
        transform: scale(1.1);
    }
    .badge-lat { background-color: #e2e8f0; color: #475569; }
    .badge-long { background-color: #dcfce7; color: #166534; }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h4 class="mb-0"><i class="fas fa-map-marked-alt me-2"></i> Inventarisasi Titik Lokasi</h4>
            <span class="badge bg-light text-primary">{{ count($points) }} Total Data</span>
        </div>
        <div class="card-body bg-light">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Detail Lokasi</th>
                            <th>Koordinat</th>
                            <th class="text-center">Visual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($points as $p)
                        <tr>
                            <td class="text-center fw-bold text-muted">{{ $no++ }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->name }}</div>
                                <small class="text-muted">{{ Str::limit($p->description, 50) }}</small>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge badge-lat"><i class="fas fa-arrows-alt-v me-1"></i> {{ $p->latitude }}</span>
                                    <span class="badge badge-long"><i class="fas fa-arrows-alt-h me-1"></i> {{ $p->longitude }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                @if($p->image)
                                    <img src="{{ asset('storage/images/' . $p->image) }}"
                                         alt="{{ $p->name }}"
                                         width="80"
                                         class="img-thumbnail">
                                @else
                                    <i class="fas fa-image text-light fa-3x"></i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
