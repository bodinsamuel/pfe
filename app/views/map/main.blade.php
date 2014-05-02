
<style type="text/css">
    #mapbox-screen {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 50px;
        left: 0;
        /*z-index: 1;*/
    }
/*    #mapbox-screen:before {
        content: " ";
        background: rgba(0,0,0,0.2);
        width: 100%;
        height: 100%;
        display: block;
        position: absolute;
        z-index: 2;
    }*/
    #mapbox-screen-map {
        width: 100%;
        height: 100%;
        position: relative;
    }
</style>
<div id="mapbox-screen">
    <script src='https://api.tiles.mapbox.com/mapbox.js/v1.6.2/mapbox.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox.js/v1.6.2/mapbox.css' rel='stylesheet' />

    @if (isset($map_config['locate']))
        <!-- Auto geolocation plugin -->
        <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.js'></script>
        <link href='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.24.0/L.Control.Locate.css' rel='stylesheet' />
    @endif

    <div id='mapbox-screen-map'></div>
    <script>
        var map = L.mapbox.map('mapbox-screen-map', 'bodinsamuel.hj2ocb3b');
        map.setView([48.855, 2.32], 13);
        L.control.locate().addTo(map).setPosition('topright');
        map.zoomControl.setPosition('topright');
    </script>
</div>
