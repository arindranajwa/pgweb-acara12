<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- leaflet css link  -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <title>Web-GIS with geoserver and leaflet</title>

    <style>
        body {
            margin: 0;
            padding: 0;
        }

        #map {
            width: 100%;
            height: 100vh;
        }

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
    <div id="map"></div>
    <div class="legend-control" id="legendControl">LEGENDA</div>
    <div class="legend" id="legend">
        <!-- Legend content will be dynamically added here -->
    </div>

    <!-- leaflet js link  -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- jquery link  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- leaflet geoserver request link  -->
    <script src="lib/L.Geoserver.js"></script>


    <script>
        var map = L.map("map").setView([-7.174949729411567, 109.82031899676184], 8);

        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });

        osm.addTo(map);

        // wms request
        var wmsLayer = L.Geoserver.wms("http://localhost:8080/geoserver/wms", {
            layers: "pgweb:Kabupaten_Jateng_DIY",
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
            layers: "pgweb:Jumlah_Penduduk",
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