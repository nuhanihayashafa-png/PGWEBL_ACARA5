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
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }

        .custom-header .modal-title {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

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
    @include('toast')
    <div id="map"></div>

    {{-- Modal Form Input Point --}}
    <div class="modal fade" id="modalInputPoint" tabindex="-1" aria-labelledby="modalInputPointLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">
                <div class="modal-header custom-header">
                    <h5 class="modal-title" id="modalInputPointLabel">
                        <i class="fa-solid fa-location-dot me-2"></i> Input Point Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('store') }}" method="POST" id="formInputPoint" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-secondary">Point Name</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-tag text-primary"></i></span>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="E.g., Candi Prambanan">
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
                        <button type="submit" class="btn btn-elegant shadow-sm"><i
                                class="fa-solid fa-floppy-disk me-1"></i> Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Input Polylines --}}
    <div class="modal fade" id="modalInputPolylines" tabindex="-1" aria-labelledby="modalInputPolylinesLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">
                <div class="modal-header custom-header">
                    <h5 class="modal-title" id="modalInputPolylinesLabel">
                        <i class="fa-solid fa-route me-2"></i> Input Polylines Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('store') }}" method="POST" id="formInputPolylines" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-secondary">Polylines Name</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-tag text-primary"></i></span>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="E.g., Jalan Malioboro">
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
                        <button type="submit" class="btn btn-elegant shadow-sm"><i
                                class="fa-solid fa-floppy-disk me-1"></i> Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Input Polygons --}}
    <div class="modal fade" id="modalInputPolygons" tabindex="-1" aria-labelledby="modalInputPolygonsLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal">
                <div class="modal-header custom-header">
                    <h5 class="modal-title" id="modalInputPolygonsLabel">
                        <i class="fa-solid fa-draw-polygon me-2"></i> Input Polygons Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('store') }}" method="POST" id="formInputPolygons" novalidate>
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-secondary">Polygons Name</label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-white"><i
                                        class="fa-solid fa-tag text-primary"></i></span>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="E.g., Kawasan UGM">
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
                        <button type="submit" class="btn btn-elegant shadow-sm"><i
                                class="fa-solid fa-floppy-disk me-1"></i> Save Data</button>
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
        // ==========================================
        // 1. Inisialisasi Peta & Base Maps
        // ==========================================
        var map = L.map('map').setView([-7.7829, 110.3671], 15);

        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var Esri_WorldImagery = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri'
            });

        // ==========================================
        // 2. Layer Group
        // ==========================================
        var databaseItems = L.featureGroup().addTo(map); // Menggunakan featureGroup agar fitBounds mudah
        var drawnItems = new L.FeatureGroup().addTo(map);

        // ==========================================
        // 3. Setup Leaflet Draw Control
        // ==========================================
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

        // ==========================================
        // 4. Event saat fitur selesai digambar
        // ==========================================
        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            if (type === 'polyline') {
                $('#geometry_polyline').val(objectGeometry);
                $('#modalInputPolylines').modal('show');
                $('#modalInputPolylines').on('hidden.bs.modal', function() { map.removeLayer(layer); });
            } else if (type === 'polygon' || type === 'rectangle') {
                $('#geometry_polygons').val(objectGeometry);
                $('#modalInputPolygons').modal('show');
                $('#modalInputPolygons').on('hidden.bs.modal', function() { map.removeLayer(layer); });
            } else if (type === 'marker') {
                $('#geometry_point').val(objectGeometry);
                $('#modalInputPoint').modal('show');
                $('#modalInputPoint').on('hidden.bs.modal', function() { map.removeLayer(layer); });
            }

            drawnItems.addLayer(layer);
        });

        // ==========================================
        // 5. FUNGSI FETCH DATA KOMPLIT (Otomatis)
        // ==========================================
        function loadMapData(url) {
            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error("Gagal merespon dari: " + url);
                    return response.json();
                })
                .then(data => {
                    // Jika data kosong, hentikan proses agar tidak error
                    if (!data || !data.features || data.features.length === 0) return;

                    const geojsonLayer = L.geoJSON(data, {
                        onEachFeature: function(feature, layer) {
                            if (feature.properties) {
                                // Template Popup yang rapi tanpa image_url (karena di DB sudah dihapus)
                                let popupContent = `
                                    <div style="font-family: sans-serif; min-width: 180px; text-align: center;">
                                        <h4 style="margin: 0 0 5px 0; color: #1e3c72;">${feature.properties.name || 'Tanpa Nama'}</h4>
                                        <hr style="margin: 5px 0; border-top: 1px solid #ccc;">
                                        <p style="margin: 0; font-size: 14px; color: #555;">${feature.properties.description || 'Tidak ada deskripsi'}</p>
                                    </div>
                                `;
                                layer.bindPopup(popupContent);
                            }
                        },
                        style: function(feature) {
                            // Styling khusus agar Polygon dan Polyline terlihat elegan
                            return {
                                color: "#2980b9", // Warna garis tepi biru elegan
                                weight: 3,
                                opacity: 0.9,
                                fillColor: "#3498db", // Warna isian polygon
                                fillOpacity: 0.4
                            };
                        }
                    });

                    // Masukkan data ke layer databaseItems
                    databaseItems.addLayer(geojsonLayer);

                    // Paskan zoom layar agar semua titik/garis/polygon terlihat
                    map.fitBounds(databaseItems.getBounds(), { padding: [30, 30] });
                })
                .catch(error => console.log('Info Peta (Bisa diabaikan jika tabel masih kosong):', error));
        }

        // --- PANGGIL DATA DARI DATABASE DI SINI ---
        // Pastikan nama rute ini sama persis dengan yang ada di routes/web.php atau api.php
        loadMapData('/api/point');       // Memanggil data titik
        loadMapData('/api/polylines');   // Memanggil data garis (jika ada)
        loadMapData('/api/polygons');    // Memanggil data area (jika ada)


        // ==========================================
        // 6. Control Layer (Menu pojok kanan atas)
        // ==========================================
        var baseMapsOptions = {
            "OpenStreetMap": osm,
            "Esri World Imagery": Esri_WorldImagery
        };

        var overlayMapsOptions = {
            "Draw Tools (Gambar Baru)": drawnItems,
            "Data Database": databaseItems
        };

        L.control.layers(baseMapsOptions, overlayMapsOptions, { collapsed: false }).addTo(map);
    </script>
@endsection
