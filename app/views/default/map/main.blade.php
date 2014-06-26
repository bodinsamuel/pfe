<div id="mapbox-screen">
    <script src='https://api.tiles.mapbox.com/mapbox.js/v1.6.4/mapbox.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox.js/v1.6.4/mapbox.css' rel='stylesheet' />

    @if (isset($__map['locate']))
        <!-- Auto geolocation plugin -->
        <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.js'></script>
        <link href='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.css' rel='stylesheet' />
    @endif

    @if (isset($__map['cluster']))
        <script src='/assets/lib/map/markercluster/leaflet.markercluster.js'></script>
        <link href='/assets/lib/map/markercluster/default.css' rel='stylesheet' />
    @endif

    <div id='mapbox-screen-map'></div>
    <script>
        $(document).ready(function(){
            mm = new Map();
            mm.init();

            @if (isset($__map['markers']))
                var raw_markers = {{ json_encode($__map['markers']) }};

                @if (isset($__map['openOnLoad']))
                    mm.openOnLoad = true;
                @endif

                mm.parseMarkers(raw_markers);
            @endif

            @if (isset($__map['center']))
                mm.map.setView([{{{ $__map['center']['y'] }}}, {{{ $__map['center']['x'] }}}], 14);
            @endif

            @if (isset($__map['static']))
                mm.map.dragging.disable();
                mm.map.touchZoom.disable();
                mm.map.doubleClickZoom.disable();
                mm.map.scrollWheelZoom.disable();
                if (mm.map.tap)
                    mm.map.tap.disable();
            @endif
        });
    </script>
</div>
