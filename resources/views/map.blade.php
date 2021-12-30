<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Locate the user</title>
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
<link href="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js"></script>
<style>
body { margin: 0; padding: 0; }
#map { position: absolute; top: 0; bottom: 0; width: 100%; }
</style>
</head>
<body>
<div id="map"></div>
    <script>
        var long = @php
            echo json_encode($longitude);
        @endphp;
        var lat = @php
            echo json_encode($latitude);
        @endphp;
        mapboxgl.accessToken = 'pk.eyJ1IjoidG9hbmIxNzA0NzgxIiwiYSI6ImNrcmF3NG9xMTFkaWQyb25paGxycHY0NTkifQ.eeIZM7f_dgHtLlKYyLmqGQ';
        var map = new mapboxgl.Map({
            container: 'map', // container id
            style: 'mapbox://styles/mapbox/streets-v11',
            // VNPT location  [106.4013015390428, 10.354338991528339]
            center: [long, lat],
            zoom: 12
        });
        // Latitude: 10.2025556, Longitude: 106.4995005
        // Add geolocate control to the map.
        map.addControl(
            new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
                trackUserLocation: true
            })
        );
        var marker1 = new mapboxgl.Marker()
        .setLngLat([106.4013015390428, 10.354338991528339])
        .addTo(map);
        
        // Create a default Marker, colored black, rotated 45 degrees.
        var marker2 = new mapboxgl.Marker({ color: 'red'})
        .setLngLat([long, lat])
        .addTo(map);
    </script>

</body>
</html>