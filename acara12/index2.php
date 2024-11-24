<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Bootstrap CSS, styling elemen HTML dengan framework Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- leaflet css link, styling peta dari library Leaflet  -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <title>Web-GIS with geoserver and leaflet</title>

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        /* Membuat peta menggunakan seluruh lebar dan tinggi viewport */
        #map { 
            width: 100%;
            height: 100vh;
        }

        /* Mengatur tampilan tombol dan legenda peta */
        .legend-control {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: white;
            padding: 5px;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
        }

        .legend {
            position: absolute;
            bottom: 50px;
            /* Adjusted to be above the toggle button */
            left: 10px;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            display: none;
            z-index: 1000;
        }
    </style>
</head>

<body>


    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">KABUPATEN SLEMAN</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://geoportal.slemankab.go.id/">Sumber Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#InfoModal">
                            <i class="fa-solid fa-circle-info"></i> Info</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Div #map: Tempat peta Leaflet ditampilkan -->
    <div id="map"></div>
    <div class="legend-control" id="legendControl">Legenda</div>
    <div class="legend" id="legend">
        <!-- Legend content will be dynamically added here -->
    </div>


    <!-- Info Modal -->

    <!-- menampilkan detail pembuat peta seperti nama, NIM, kelas, dan tautan GitHub -->
    <div class="modal fade" id="InfoModal" tabindex="-1" aria-labelledby="InfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="InfoModalLabel">Info Pembuat</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>Nama</th>
                            <td>Arindra Najwa</td>
                        </tr>
                        <tr>
                            <th>NIM</th>
                            <td>23/515591/SV/22582</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>PG-WEB A</td>
                        </tr>
                        <tr>
                            <th>Github</th>
                            <td> <a href="https://github.com/arindranajwa" target="_blank" rel="noopener noreferrer">
                                    https://github.com/arindranajwa</a> </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Feature Modal -->

    <!-- menampilkan informasi fitur tertentu di peta, dapat dikustomisasi melalui ID featureModalTitle dan featureModalBody -->
    <div class="modal fade" id="featureModal" tabindex="-1" aria-labelledby="featureModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="featureModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="featureModalBody">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Menghubungkan dengan file JavaScript Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <!-- Menghubungkan dengan jQuery untuk memudahkan manipulasi DOM -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Menghubungkan dengan Leaflet.js untuk membuat peta interaktif -->
    <script src="plugin/leaflet-search-master/leaflet-search-master/dist/leaflet-search.min.js"></script>
    <!-- Menambahkan fitur pencarian ke peta -->
    <script
        src="plugin/Leaflet.defaultextent-master/Leaflet.defaultextent-master/dist/leaflet.defaultextent.js"></script>

    <!-- leaflet js link  -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- jquery link  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- leaflet geoserver request link  -->
    <script src="lib/L.Geoserver.js"></script>


    <!-- Membuat instance peta dengan ID map -->
    <script>
        var map = L.map("map").setView([-7.703214618269592, 110.38722055214195], 11);

        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });

        osm.addTo(map);

        // wms request. Menampilkan data spasial dari GeoServer melalui WMS (Web Map Service)
        var wmsLayer = L.Geoserver.wms("http://localhost:8080/geoserver/wms", {
            layers: "pgweb:SlemanKecamatan",
            transparent: true,

        });
        wmsLayer.addTo(map);

        // https://geoportal.slemankab.go.id/geoserver/geonode/jalan_ln/ows
        var wmsLayer2 = L.Geoserver.wms("https://geoportal.slemankab.go.id/geoserver/wms", {
            layers: "geonode:jalan_ln",
            transparent: true,

        });

        wmsLayer2.addTo(map);


        var wmsLayer3 = L.Geoserver.wms("http://localhost:8080/geoserver/wms", {
            layers: "pgweb:Jumlah Penduduk Sleman",
            transparent: true,

        });

        wmsLayer3.addTo(map);

        // Add legend content
        var legendContent = '<img src="http://localhost:8080/geoserver/pgweb/wms?REQUEST=GetLegendGraphic&VERSION=1.0.0&FORMAT=image/png&LAYER=pgweb:ADMINISTRASIDESA_AR_25K" alt="Legend">';
        document.getElementById('legend').innerHTML = legendContent;

        // Base maps and overlay maps
        var baseMaps = {
            "OpenStreetMap": osm
        };

        var overlayMaps = {
            "Batas Administrasi Desa": wmsLayer,
            "Jalan": wmsLayer2,
            "Penduduk": wmsLayer3
        };

        // Add layer control
        var layerControl = L.control.layers(baseMaps, overlayMaps).addTo(map);

        // Toggle legend visibility
        var legendVisible = false;
        document.getElementById('legendControl').onclick = function() {
            var legend = document.getElementById('legend');
            if (legendVisible) {
                legend.style.display = 'none';
            } else {
                legend.style.display = 'block';
            }
            legendVisible = !legendVisible;
        };
    </script>
</body>

</html>