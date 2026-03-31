@extends('layouts.template')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet Draw CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Map height = full screen - navbar */
        #map {
            height: calc(100vh - 60px);
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    {{-- Modal Form Input untuk Point --}}
    <div class="modal" tabindex="-1" id="modalInputPoint">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('points.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name_point" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name_point" name="name"
                                placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="description_point" class="form-label">Description</label>
                            <textarea class="form-control" id="description_point" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_point" name="geometry_point" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Input untuk Polylines --}}
    <div class="modal" tabindex="-1" id="modalInputPolyline">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Polyline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polylines.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name_polyline" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name_polyline" name="name"
                                placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="description_polyline" class="form-label">Description</label>
                            <textarea class="form-control" id="description_polyline" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_polyline" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_polyline" name="geometry_polyline" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Form Input untuk Poligon --}}
    <div class="modal" tabindex="-1" id="modalInputPolygon">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Polygon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('polygon.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name_polygon" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name_polygon" name="name"
                                placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="description_polygon" class="form-label">Description</label>
                            <textarea class="form-control" id="description_polygon" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="geometry_polygon" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geometry_polygon" name="geometry_polygon" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Leaflet Draw JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <!-- Terraformer -->
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-7.7956, 110.3695], 10);

        // Basemap OSM
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        /* Digitize Function */
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

            console.log(type);

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            console.log(drawnJSONObject);
            console.log(objectGeometry);

            if (type === 'polyline') {

                // Set Value geometry to geometry_point textarea
                $('#geometry_polyline').val(objectGeometry);

                //Show Modal Input
                console.log("Create " + type);

                //Show Modal Input Polyline
                $('#modalInputPolyline').modal('show');

                // Hapus titik dari peta jika modal ditutup (cancel/dismiss)
                $('#modalInputPolyline').off('hidden.bs.modal').one('hidden.bs.modal', function() {
                    drawnItems.removeLayer(layer);
                    $('#modalInputPolyline').find('form')[0].reset();
                });
            } else if (type === 'polygon' || type === 'rectangle') {
                // Set Value geometry to geometry_polygon textarea
                $('#geometry_polygon').val(objectGeometry);

                //Show Modal Input
                console.log("Create " + type);

                //Show Modal Input Point
                $('#modalInputPolygon').modal('show');

                // Hapus titik dari peta jika modal ditutup (cancel/dismiss)
                $('#modalInputPolygon').off('hidden.bs.modal').one('hidden.bs.modal', function() {
                    drawnItems.removeLayer(layer);
                    $('#modalInputPolygon').find('form')[0].reset();
                });
            } else if (type === 'marker') {
                // Set Value geometry to geometry_point textarea
                $('#geometry_point').val(objectGeometry);

                //Show Modal Input
                console.log("Create " + type);

                //Show Modal Input Point
                $('#modalInputPoint').modal('show');

                // Hapus titik dari peta jika modal ditutup (cancel/dismiss)
                $('#modalInputPoint').off('hidden.bs.modal').one('hidden.bs.modal', function() {
                    drawnItems.removeLayer(layer);
                    $('#modalInputPoint').find('form')[0].reset();
                });
            } else {
                console.log('undefined');
            }

            drawnItems.addLayer(layer);
        });


        // Layer control contoh
        var satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
        );

        var baseMaps = {
            "OpenStreetMap": osm,
            "Satellite": satellite
        };

        L.control.layers(baseMaps).addTo(map);
    </script>
@endsection
