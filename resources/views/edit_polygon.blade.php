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

        <button class="btn btn-primary btn-floating-edit shadow" id="btnOpenForm">
            <i class="fa-solid fa-pen-to-square"></i> Buka Form Edit
        </button>
    </div>

    {{-- Modal Form Edit Polygon --}}
    <div class="modal fade" tabindex="-1" id="modalInputPolygon">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen-to-square text-warning me-2"></i>Edit Data Polygon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polygons.update', $id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                                <textarea class="form-control bg-light" id="geometry_polygon" name="geometry_polygon" rows="2" readonly required></textarea>
                            </div>
                            <small class="text-muted mt-1 d-block">Geser simpul polygon di peta (gunakan icon edit di sebelah kiri) untuk mengubah bentuk.</small>
                        </div>
                        <div class="mb-3">
                            <label for="image_polygon" class="form-label fw-semibold">Ubah Image (Opsional)</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="fa-regular fa-image"></i></span>
                                <input class="form-control" type="file" id="image_polygon" name="image"
                                    onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0]); document.getElementById('preview-image-polygon').style.display = 'block';">
                            </div>
                            <div class="text-center">
                                <img src="" alt="Preview" id="preview-image-polygon" class="img-thumbnail shadow-sm rounded mt-2" style="max-height: 200px; display: none;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-times me-1"></i> Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan</button>
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
        var activeLayer;

        function populateForm(layer) {
            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);
            var properties = drawnJSONObject.properties;

            $('#name_polygon').val(properties.name);
            $('#description_polygon').val(properties.description);
            $('#geometry_polygon').val(objectGeometry);

            if(properties.image) {
                $('#preview-image-polygon').attr('src', imagePath + properties.image).show();
            } else {
                $('#preview-image-polygon').hide();
            }
        }

        map.on('draw:edited', function(e) {
            e.layers.eachLayer(function(layer) {
                populateForm(layer);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalInputPolygon')).show();
            });
        });

        var polygons = L.geoJSON(null, {
            onEachFeature: function(feature, layer) {
                drawnItems.addLayer(layer);
                activeLayer = layer;

                layer.on('click', function() {
                    populateForm(layer);
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalInputPolygon')).show();
                });
            }
        });

        // Memanggil route JSON untuk polygon tunggal
        $.getJSON("{{ route('geojson.polygon', $id) }}", function(data) {
            polygons.addData(data);
            map.addLayer(polygons);

            if(polygons.getBounds().isValid()){
                map.fitBounds(polygons.getBounds(), { maxZoom: 16 });
            }
        });

        $('#btnOpenForm').on('click', function() {
            if(activeLayer) {
                populateForm(activeLayer);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('modalInputPolygon')).show();
            } else {
                alert('Data masih dimuat, mohon tunggu sebentar...');
            }
        });

    </script>
@endsection
