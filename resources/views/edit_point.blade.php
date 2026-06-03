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

        /* Custom class untuk tombol mengambang di atas peta */
        .btn-floating-edit {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 1000;
        }
    </style>
@endsection

@section('content')
    <div class="position-relative">
        <div id="map"></div>

        <!-- Tombol Floating untuk membuka form manual -->
        <button class="btn btn-primary btn-floating-edit shadow" id="btnOpenForm">
            <i class="fa-solid fa-pen-to-square"></i> Buka Form Edit
        </button>
    </div>

    {{-- Modal Form Edit --}}
    <div class="modal fade" tabindex="-1" id="modalInputPoint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('points.update', $id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name_point" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name_point" name="name" placeholder="Enter name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description_point" class="form-label">Description</label>
                            <textarea class="form-control" id="description_point" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry" name="geometry" rows="3" readonly required></textarea>
                            <small class="text-muted">Pindahkan titik di peta (gunakan icon edit di sebelah kiri) untuk mengubah koordinat.</small>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Ubah Image (Opsional)</label>
                            <input class="form-control" type="file" id="image" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0]); document.getElementById('preview-image-point').style.display = 'block';">

                            <img src="" alt="Preview" id="preview-image-point" class="img-thumbnail mt-2" width="400" style="display: none;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        var map = L.map('map').setView([-7.7956, 110.3695], 10);

        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: false,
            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: false
            }
        });

        map.addControl(drawControl);

        var imagePath = "{{ asset('storage/images') }}/";
        var activeLayer; // Variabel untuk menyimpan titik yang sedang aktif

        // Fungsi untuk mengisi data ke dalam form modal secara otomatis
        function populateForm(layer) {
            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);
            var properties = drawnJSONObject.properties;

            $('#name_point').val(properties.name);
            $('#description_point').val(properties.description);
            $('#geometry').val(objectGeometry);

            // Jika ada foto lama, tampilkan. Jika tidak, sembunyikan kotak fotonya.
            if(properties.image) {
                $('#preview-image-point').attr('src', imagePath + properties.image).show();
            } else {
                $('#preview-image-point').hide();
            }
        }

        // 1. Memicu form modal ketika titik digeser (diedit via Leaflet.Draw)
        map.on('draw:edited', function(e) {
            e.layers.eachLayer(function(layer) {
                populateForm(layer);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalInputPoint')).show();
            });
        });

        // Memuat data tunggal dari GeoJSON
        var points = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                drawnItems.addLayer(layer);
                activeLayer = layer; // Simpan ke memori

                // 2. Membuka form jika titik langsung di-klik oleh user
                layer.on('click', function() {
                    populateForm(layer);
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalInputPoint')).show();
                });
            }
        });

        $.getJSON("{{ route('geojson.point', $id) }}", function(data) {
            points.addData(data);
            map.addLayer(points);

            // Otomatis mengarahkan (zoom) ke lokasi titik
            if(points.getBounds().isValid()){
                map.fitBounds(points.getBounds(), { maxZoom: 16 });
            }
        });

        // 3. Action klik untuk tombol biru mengambang di peta
        $('#btnOpenForm').on('click', function() {
            if(activeLayer) {
                populateForm(activeLayer);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalInputPoint')).show();
            } else {
                alert('Data masih dimuat, mohon tunggu sebentar...');
            }
        });

    </script>
@endsection
