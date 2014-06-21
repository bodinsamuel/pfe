<div id="mapbox-screen">
    <script src='https://api.tiles.mapbox.com/mapbox.js/v1.6.4/mapbox.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox.js/v1.6.4/mapbox.css' rel='stylesheet' />

    @if (isset($map_config['locate']))
        <!-- Auto geolocation plugin -->
        <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.js'></script>
        <link href='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.css' rel='stylesheet' />
    @endif

    @if (isset($map_config['cluster']))
        <script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/leaflet.markercluster.js'></script>
        <link href='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.css' rel='stylesheet' />
        <link href='https:////api.tiles.mapbox.com/mapbox.js/plugins/leaflet-markercluster/v0.4.0/MarkerCluster.Default.css' rel='stylesheet' />
        <script src="https://www.mapbox.com/mapbox.js/assets/data/realworld.388.js"></script>

    @endif

    <div id='mapbox-screen-map'></div>
    <script>
        mm = new Map();
        mm.init();

        @if (isset($__map_markers))
            var raw_markers = {{ json_encode($__map_markers) }};
            mm.parseMarkers(raw_markers);
        @endif

        @if (isset($__map_markers_center))
            mm.map.setView([{{{ $__map_markers_center['lat'] }}}, {{{ $__map_markers_center['lon'] }}}], 13);
            mm.map.fitBounds(featureLayer.getBounds());
        @else
            mm.map.fitBounds(featureLayer.getBounds());
        @endif

    </script>
</div>
