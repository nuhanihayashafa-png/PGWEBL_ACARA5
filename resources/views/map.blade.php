@extends('layouts.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }

        /* --- CUSTOM CSS UNTUK MODAL ELEGAN --- */
        .custom-modal {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
        }

        .custom-header {
            /* Gradien Biru Elegan */
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }

        .custom-header .modal-title {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Mengubah warna tombol X (close) menjadi putih */
        .custom-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
            opacity: 0.8;
        }

        .custom-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 1.5rem;
            background-color: #fcfcfc;
        }

        .form-control:focus {
            border-color: #2a5298;
            box-shadow: 0 0 0 0.25rem rgba(42, 82, 152, 0.15);
        }

        .modal-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #eaeaea;
            padding: 1rem 1.5rem;
        }

        .btn-elegant {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-elegant:hover {
            background: linear-gradient(135deg, #152a52 0%, #1e3c72 100%);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(42, 82, 152, 0.3);
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    {{-- Modal Form Input Point dengan Desain Elegan --}}
    <div class="modal fade" id="modalInputPoint" tabindex="-1" aria-labelledby="modalInputPointLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">
                <div class="modal-header custom-header">
                    <h5 class="modal-title" id="modalInputPointLabel">
                        <i class="fa-solid fa-location-dot me-2"></i> Input Point Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('store') }}" method="POST" id="formInputPoint">
                    @csrf

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-secondary">Point Name</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-tag text-primary"></i></span>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="E.g., Candi Prambanan" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold text-secondary">Description</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i
                                        class="fa-solid fa-align-left text-primary"></i></span>
                                <textarea class="form-control" id="description" name="description" placeholder="Add some details..." rows="3"></textarea>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="geometry_point" class="form-label fw-semibold text-secondary">Geometry (WKT
                                Coordinate)</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light"><i
                                        class="fa-solid fa-map text-secondary"></i></span>
                                <input type="text" class="form-control bg-light text-muted" id="geometry_point"
                                    name="geometry_point" readonly>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-white border shadow-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-elegant shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Modal Form Input Polylines dengan Desain Elegan --}}
    <div class="modal fade" id="modalInputPolylines" tabindex="-1" aria-labelledby="modalInputPolylinesLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">
                <div class="modal-header custom-header">
                    <h5 class="modal-title" id="modalInputPolylinesLabel">
                        <i class="fa-solid fa-location-dot me-2"></i> Input Polylines Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('polylines.store') }}" method="POST" id="formInputPolylines">
                    @csrf

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-secondary">Polylines Name</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-tag text-primary"></i></span>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="E.g., Candi Prambanan" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold text-secondary">Description</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i
                                        class="fa-solid fa-align-left text-primary"></i></span>
                                <textarea class="form-control" id="description" name="description" placeholder="Add some details..."
                                    rows="3"></textarea>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="geometry_polyline" class="form-label fw-semibold text-secondary">Geometry (WKT
                                Coordinate)</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light"><i
                                        class="fa-solid fa-map text-secondary"></i></span>
                                <input type="text" class="form-control bg-light text-muted" id="geometry_polyline"
                                    name="geometry_polyline" readonly>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-white border shadow-sm"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-elegant shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Modal Form Input Polygons dengan Desain Elegan --}}
    <div class="modal fade" id="modalInputPolygons" tabindex="-1" aria-labelledby="modalInputPolygonsLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">
                <div class="modal-header custom-header">
                    <h5 class="modal-title" id="modalInputPolygonsLabel">
                        <i class="fa-solid fa-location-dot me-2"></i> Input Polygons Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('polygons.store') }}" method="POST" id="formInputPolygons">
                    @csrf

                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-secondary">Polygons Name</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-tag text-primary"></i></span>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="E.g., Candi Prambanan" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold text-secondary">Description</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i
                                        class="fa-solid fa-align-left text-primary"></i></span>
                                <textarea class="form-control" id="description" name="description" placeholder="Add some details..."
                                    rows="3"></textarea>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="geometry_polygons" class="form-label fw-semibold text-secondary">Geometry (WKT
                                Coordinate)</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light"><i
                                        class="fa-solid fa-map text-secondary"></i></span>
                                <input type="text" class="form-control bg-light text-muted" id="geometry_polygons"
                                    name="geometry_polygons" readonly>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-white border shadow-sm"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-elegant shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>

    <script>
        // 1. Inisialisasi Peta
        var map = L.map('map').setView([-7.7829, 110.3671], 15);

        // 2. Base Map OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // 3. Menampilkan marker dari database
        @foreach ($points as $p)
            @if (isset($p->latitude) && isset($p->longitude))
                L.marker([{{ $p->latitude }}, {{ $p->longitude }}]).addTo(map)
                    .bindPopup("<b>{{ $p->name }}</b><br>{{ $p->description }}");
            @endif
        @endforeach

        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                position: 'topleft',
                polyline: true,
                polygon: true,
                rectangle: true,
                circle: false,
                marker: true,
                circlemarker: false
            },
            edit: false
        });

        map.addControl(drawControl);

        // Event saat fitur selesai digambar
        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            if (type === 'polyline') {

                // 1. Nilai koordinat WKT ke dalam input form hidden/readonly
                $('#geometry_polyline').val(objectGeometry);

                // 2. Tampilkan modal elegan (SUDAH DIPERBAIKI: PAKAI 'S')
                $('#modalInputPolylines').modal('show');

                // Modal dismiss reload page (SUDAH DIPERBAIKI: PAKAI 'S')
                $('#modalInputPolylines').on('hidden.bs.modal', function() {
                    location.reload();
                });

            } else if (type === 'polygon' || type === 'rectangle') {
                // 1. Nilai koordinat WKT ke dalam input form hidden/readonly
                $('#geometry_polygons').val(objectGeometry);

                // 2. Tampilkan modal elegan
                $('#modalInputPolygons').modal('show');

                // Modal dismiss reload page
                $('#modalInputPolygons').on('hidden.bs.modal', function() {
                    location.reload();
                });

            } else if (type === 'marker') {
                console.log("Create " + type);

                // 1. Nilai koordinat WKT ke dalam input form hidden/readonly
                $('#geometry_point').val(objectGeometry);

                // 2. Tampilkan modal elegan
                $('#modalInputPoint').modal('show');

                // Modal dismiss reload page
                $('#modalInputPoint').on('hidden.bs.modal', function() {
                    location.reload();
                });
            } else {
                console.log('__undefined__');
            }

            drawnItems.addLayer(layer);
        });
    </script>
@endsection
