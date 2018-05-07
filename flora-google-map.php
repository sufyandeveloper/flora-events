<?php echo get_post_type_archive_link('floraevents'); ?>
<style>
    #flora-map{
        width:100%;
        height:400px;
    }
</style>

<h2>Location</h2>
<input type="search" id="flora-map-search" size="50" />
<div id="flora-map"></div>

<script>
    
    function floramap(){
        
        var lat = <?php echo !empty($eventLocationLat) ? $eventLocationLat : '24.974144892412934'; ?>;
        var lng = <?php echo !empty($eventLocationLng) ? $eventLocationLng : '67.13708442382813'; ?>;
        
        var options = {
            zoom: 10,
            center: {lat: lat,lng: lng}
        }
        
        var map = new google.maps.Map( document.getElementById('flora-map'), options);
        
        //marker
        var marker = new google.maps.Marker({
           position: {lat:lat,lng:lng},
           map: map,
           draggable: true
        });
        
        //Search Box
        var searchBox = new google.maps.places.SearchBox(document.getElementById('flora-map-search'));
        
        google.maps.event.addListener(searchBox, 'places_changed', function(){
            
            var places = searchBox.getPlaces();
            var bounds = new google.maps.LatLngBounds();
            
            var i, place;
            
            for(i=0; place = places[i]; i++){
                console.log(place.geometry.location);
                bounds.extend(place.geometry.location);
                marker.setPosition(place.geometry.location);
                
                updateMarkerPosition(place.geometry.location.lat(), place.geometry.location.lng());
            }
            
            map.fitBounds(bounds);
            map.setZoom(10);
        });
        
        
        //marker dragend event
        google.maps.event.addListener(marker, 'dragend', function(){
            
            updateMarkerPosition(marker.getPosition().lat(), marker.getPosition().lng());
            
        });
        
        function updateMarkerPosition(lat, lng){
            
            document.getElementById('flora-lat').value = lat;
            document.getElementById('flora-lng').value = lng;
        }
        
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7xARpb99M-tbyfkhy1OQrjWky7FO1qJw&libraries=places&callback=floramap"></script>











