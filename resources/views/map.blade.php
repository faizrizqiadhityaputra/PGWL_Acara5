@extends('layouts.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        #map {
            height: calc(100vh - 56px);
            width: 100%;
        }

        .floating-info {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 1000;
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 320px;
        }

        /* Custom Icon Marker */
        .custom-div-icon {
            text-align: center;
            color: #0d6efd;
            font-size: 24px; /* Adjust icon size */
            line-height: 24px;
        }

    </style>
@endsection

@section('content')
    <div class="position-relative">
        <div id="map"></div>
        <div class="floating-info d-none d-md-block border-start border-primary border-4">
            <h6 class="fw-bold mb-1"><i class="fa-solid fa-map-location-dot text-primary me-2"></i>Peta Interaktif</h6>
            <p class="text-muted small mb-0">Gunakan toolbar di sebelah kiri untuk menggambar objek Geospasial, atau klik objek di peta untuk melihat detail.</p>
        </div>
    </div>

    {{-- Modal Form Input untuk Point --}}
    <div class="modal fade" tabindex="-1" id="modalInputPoint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-location-dot text-primary me-2"></i>Tambah Data Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('points.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name_point" class="form-label fw-semibold">Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                                <input type="text" class="form-control" id="name_point" name="name" placeholder="Enter name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description_point" class="form-label fw-semibold">Description</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-align-left"></i></span>
                                <textarea class="form-control" id="description_point" name="description" rows="3" placeholder="Enter description"></textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_point" class="form-label fw-semibold">Geometry</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-location-crosshairs"></i></span>
                                <textarea class="form-control bg-light" id="geometry_point" name="geometry_point" rows="2" readonly></textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="image_point" class="form-label fw-semibold">Image</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="fa-regular fa-image"></i></span>
                                <input class="form-control" type="file" id="image_point" name="image" onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                            </div>
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail shadow-sm rounded" style="max-height: 200px; display: none;" onload="this.style.display='block'">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-times me-1"></i> Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Input untuk Polylines --}}
    <div class="modal fade" tabindex="-1" id="modalInputpolylines">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="fa-solid fa-route text-success me-2"></i>Tambah Data Polyline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polyline.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                                <label for="name_polylines" class="form-label fw-semibold">Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                                    <input type="text" class="form-control" id="name_polylines" name="name" placeholder="Enter name" required>
                                </div>
                        </div>
                        <div class="mb-3">
                                <label for="description_polylines" class="form-label fw-semibold">Description</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-align-left"></i></span>
                                    <textarea class="form-control" id="description_polylines" name="description" rows="3" placeholder="Enter description"></textarea>
                                </div>
                        </div>
                        <div class="mb-3">
                                <label for="geometry_polylines" class="form-label fw-semibold">Geometry</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-location-crosshairs"></i></span>
                                    <textarea class="form-control bg-light" id="geometry_polylines" name="geometry_polylines" rows="2" readonly></textarea>
                                </div>
                        </div>
                        <div class="mb-3">
                                <label for="image_polyline" class="form-label fw-semibold">Image</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa-regular fa-image"></i></span>
                                    <input class="form-control" type="file" id="image_polyline" name="image" onchange="document.getElementById('preview-image-polyline').src = window.URL.createObjectURL(this.files[0])">
                                </div>
                                <img src="" alt="" id="preview-image-polyline" class="img-thumbnail shadow-sm rounded" style="max-height: 200px; display: none;" onload="this.style.display='block'">
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-times me-1"></i> Batal</button>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Input untuk Polygon --}}
    <div class="modal fade" tabindex="-1" id="modalInputPolygon">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="fa-solid fa-draw-polygon text-warning me-2"></i>Tambah Data Polygon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polygons.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                                <label for="name_polygon" class="form-label fw-semibold">Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                                    <input type="text" class="form-control" id="name_polygon" name="name" placeholder="Enter name" required>
                                </div>
                        </div>
                        <div class="mb-3">
                                <label for="description_polygon" class="form-label fw-semibold">Description</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-align-left"></i></span>
                                    <textarea class="form-control" id="description_polygon" name="description" rows="3" placeholder="Enter description"></textarea>
                                </div>
                        </div>
                        <div class="mb-3">
                                <label for="geometry_polygon" class="form-label fw-semibold">Geometry</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-location-crosshairs"></i></span>
                                    <textarea class="form-control bg-light" id="geometry_polygon" name="geometry_polygon" rows="2" readonly></textarea>
                                </div>
                        </div>
                        <div class="mb-3">
                                <label for="image_polygon" class="form-label fw-semibold">Image</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fa-regular fa-image"></i></span>
                                    <input class="form-control" type="file" id="image_polygon" name="image" onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                                </div>
                                <img src="" alt="" id="preview-image-polygon" class="img-thumbnail shadow-sm rounded" style="max-height: 200px; display: none;" onload="this.style.display='block'">
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-times me-1"></i> Batal</button>
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        var map = L.map('map').setView([-7.7956, 110.3695], 10);

        // SweetAlert2 Notification
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2500
            });
        @endif
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
            });
        @endif

        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            position: 'topleft',
            draw: {
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

        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            if (type === 'polyline') {
                $('#geometry_polylines').val(objectGeometry);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalInputpolylines')).show();
                $('#modalInputpolylines').off('hidden.bs.modal').one('hidden.bs.modal', function() {
                    drawnItems.removeLayer(layer);
                    $('#modalInputpolylines').find('form')[0].reset();
                });
            } else if (type === 'polygon' || type === 'rectangle') {
                $('#geometry_polygon').val(objectGeometry);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalInputPolygon')).show();
                $('#modalInputPolygon').off('hidden.bs.modal').one('hidden.bs.modal', function() {
                    drawnItems.removeLayer(layer);
                    $('#modalInputPolygon').find('form')[0].reset();
                });
            } else if (type === 'marker') {
                $('#geometry_point').val(objectGeometry);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalInputPoint')).show();
                $('#modalInputPoint').off('hidden.bs.modal').one('hidden.bs.modal', function() {
                    drawnItems.removeLayer(layer);
                    $('#modalInputPoint').find('form')[0].reset();
                });
            }
            drawnItems.addLayer(layer);
        });

        var satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
        );

        var baseMaps = {
            "OpenStreetMap": osm,
            "Satellite": satellite
        };

        L.control.layers(baseMaps).addTo(map);

        /* --- GeoJSON LOADERS --- */

        var csrf = "{{ csrf_token() }}";
        var imagePath = "{{ asset('storage/images') }}/";

        // 1. GeoJSON Points
        var points = L.geoJSON(null, {
            pointToLayer: function(feature, latlng) {
                // Custom marker with FontAwesome icon
                var customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: '<i class="fa-solid fa-location-dot"></i>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                });
                return L.marker(latlng, { icon: customIcon });
            },
            onEachFeature: function(feature, layer) {
                var pointId = feature.properties.id;

                var routedelete = "/delete-points/" + pointId;
                var routeedit = "/points/" + pointId;

                var popup_content = "<div class='text-center p-2'>" +
                    "<h6 class='fw-bold mb-1'>" + feature.properties.name + "</h6>" +
                    "<p class='text-muted small mb-2'>" + (feature.properties.description ? feature.properties.description : "Tanpa deskripsi") + "</p>" +
                    "<img src='" + imagePath + feature.properties.image + "' class='img-fluid rounded shadow-sm mb-3' style='max-height: 150px; width: 100%; object-fit: cover;'><br>" +
                    "<div class='d-flex justify-content-center gap-2'>" +
                        "<a href='" + routeedit + "' class='btn btn-warning btn-sm text-white px-3 rounded-pill' title='Edit data'>" +
                            "<i class='fa-solid fa-pen-to-square'></i> Edit" +
                        "</a>" +
                        "<form action='" + routedelete + "' method='POST' class='m-0'>" +
                            "<input type='hidden' name='_token' value='" + csrf + "'>" +
                            "<input type='hidden' name='_method' value='DELETE'>" +
                            "<button type='submit' class='btn btn-danger btn-sm px-3 rounded-pill' onclick='return confirm(\"Yakin hapus data?\")'>" +
                                "<i class='fa-solid fa-trash-can'></i> Hapus" +
                            "</button>" +
                        "</form>" +
                    "</div>" +
                "</div>";

                layer.bindPopup(popup_content);
            }
        });
        $.getJSON("{{ route('geojson.points') }}", function(data) {
            points.addData(data);
            map.addLayer(points);
        });

        // 2. GeoJSON Polylines
        var polylines = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                var polylineId = feature.properties.id;
                var routedelete = "/polylines/" + polylineId;
                var routeedit = "/polylines/" + polylineId; // <-- URL Edit Polyline

                var popup_content = "<div class='text-center p-2'>" +
                    "<h6 class='fw-bold mb-1'>" + feature.properties.name + "</h6>" +
                    "<p class='text-muted small mb-2'>" + (feature.properties.description ? feature.properties.description : "Tanpa deskripsi") + "</p>" +
                    "<img src='" + imagePath + feature.properties.image + "' class='img-fluid rounded shadow-sm mb-3' style='max-height: 150px; width: 100%; object-fit: cover;'><br>" +
                    "<div class='d-flex justify-content-center gap-2'>" +
                        "<a href='" + routeedit + "' class='btn btn-warning btn-sm text-white px-3 rounded-pill' title='Edit data'>" +
                            "<i class='fa-solid fa-pen-to-square'></i> Edit" +
                        "</a>" +
                        "<form action='" + routedelete + "' method='POST' class='m-0'>" +
                            "<input type='hidden' name='_token' value='" + csrf + "'>" +
                            "<input type='hidden' name='_method' value='DELETE'>" +
                            "<button type='submit' class='btn btn-danger btn-sm px-3 rounded-pill' onclick='return confirm(\"Yakin hapus data?\")'>" +
                                "<i class='fa-solid fa-trash-can'></i> Hapus" +
                            "</button>" +
                        "</form>" +
                    "</div>" +
                "</div>";

                layer.bindPopup(popup_content);
            }
        });

        // PASTIKAN BAGIAN INI ADA (Pemanggil Data Polylines)
        $.getJSON("{{ route('geojson.polylines') }}", function(data) {
            polylines.addData(data);
            map.addLayer(polylines);
        });

        // 3. GeoJSON Polygons
        var polygons = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                var polygonId = feature.properties.id;
                var routedelete = "/polygons/" + polygonId;
                var routeedit = "/polygons/" + polygonId; // <-- URL Edit Polygon

                var popup_content = "<div class='text-center p-2'>" +
                    "<h6 class='fw-bold mb-1'>" + feature.properties.name + "</h6>" +
                    "<p class='text-muted small mb-2'>" + (feature.properties.description ? feature.properties.description : "Tanpa deskripsi") + "</p>" +
                    "<img src='" + imagePath + feature.properties.image + "' class='img-fluid rounded shadow-sm mb-3' style='max-height: 150px; width: 100%; object-fit: cover;'><br>" +
                    "<div class='d-flex justify-content-center gap-2'>" +
                        "<a href='" + routeedit + "' class='btn btn-warning btn-sm text-white px-3 rounded-pill' title='Edit data'>" +
                            "<i class='fa-solid fa-pen-to-square'></i> Edit" +
                        "</a>" +
                        "<form action='" + routedelete + "' method='POST' class='m-0'>" +
                            "<input type='hidden' name='_token' value='" + csrf + "'>" +
                            "<input type='hidden' name='_method' value='DELETE'>" +
                            "<button type='submit' class='btn btn-danger btn-sm px-3 rounded-pill' onclick='return confirm(\"Yakin hapus data?\")'>" +
                                "<i class='fa-solid fa-trash-can'></i> Hapus" +
                            "</button>" +
                        "</form>" +
                    "</div>" +
                "</div>";
                layer.bindPopup(popup_content);
            }
        });

        // PASTIKAN BAGIAN INI ADA (Pemanggil Data Polygons)
        $.getJSON("{{ route('geojson.polygons') }}", function(data) {
            polygons.addData(data);
            map.addLayer(polygons);
        });
    </script>
@endsection
