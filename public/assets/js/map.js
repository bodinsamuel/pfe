Map = (function() {

    function Map()
    {
        this.map = {};
        this.featureLayer = {};
    }


    Map.prototype.init = function()
    {
        this.map = L.mapbox.map('mapbox-screen-map', 'bodinsamuel.hj2ocb3b', { 
            maxZoom: 17,
            zoom: 12,
            center: [48.855, 2.32]
        })

        this.featureLayer = L.mapbox.featureLayer().addTo(this.map);

        L.control.locate().addTo(this.map).setPosition('topright');
        this.map.zoomControl.setPosition('topright');

        this.icons = Map.prototype.customIcon();
    };

    Map.prototype.parseMarkers = function(json)
    {
        var markers = new L.MarkerClusterGroup({
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: true,
            zoomToBoundsOnClick: true,
            maxClusterRadius: 50,
            spiderfyDistanceMultiplier: 1.5
        });

        for (var i in json)
        {
            marker = json[i];
            var marker = L.marker(new L.LatLng(json[i].y, json[i].x), {
                title: json[i].title
            });
            marker.bindPopup(json[i].title);
            markers.addLayer(marker);
        };
        this.map.addLayer(markers);
    };

    Map.prototype.customIcon = function()
    {
        return {
            test1: L.icon({
                "iconUrl": "/assets/img/map/pin.png",
                "iconSize": [25, 25], // size of the icon
                "iconAnchor": [25, 25], // point of the icon which will correspond to marker's location
                "popupAnchor": [0, -25], // point from which the popup should open relative to the iconAnchor
            })
        };
    };

    return Map;
})();
