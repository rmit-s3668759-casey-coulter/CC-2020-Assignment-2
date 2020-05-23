<!-- This file is used to run the google map of your current IP address
This is written in PHP rather than Twig/HTML because we couldn't get it running -->

<!-- Below is simply copied from the base.html.twig file for same formatting -->
<!DOCTYPE html>
<html>
  <head>
    <title>Cloud Computing - Assignment 2</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <!-- Add styling for google map -->
    <style>
      #map {
        height: 100%;
      }
      html, body {
        height: 80%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>

  <body>
  <div class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <div class="navbar-brand">Toolbox</div>
        </div>
        <ul class="nav navbar-nav">
          <li><a href="/home">Home</a></li>
          <li><a href="/images">Image Gallery</a></li>
          <li><a href="/map">Map</a></li>
          <li><a href="/iplocations">IP Locations</a></li>
          <li><a href="/speech_to_text">Transcribe</a></li>
          <li><a href="/translation">Translate</a></li>
          <li><a href="/help">Help</a></li>
        </ul>
      </div>
    </div>
    
    <div class="container">
      <h3>Map</h3>
      <p>Below is where you are currently connected, scroll in and take a look</p>
    </div>
    <div id="map"></div>

    <!-- The below code wont run properly unless the user allows the consent to 
    location sharing when asked in the browser.  Error: "The Geolocation service
    failed" means the user did not give persmission -->
    <script>
      var map, infoWindow;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 6
        });
        infoWindow = new google.maps.InfoWindow;

        // Attempt HTML5 Geolocation
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('Location found.');
            infoWindow.open(map);
            map.setCenter(pos);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Show this error if the browser doesn't allow Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
      }

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
      }
    </script>
    
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyyrzXzHAv3zJqv1xCubqo9Ho6XZMzccg&callback=initMap">
    </script>

    <footer class="page-footer font-small black">
      <br>
      <div class="footer-copyright text-center py-3">© 2020 Copyright: Casey Coulter and Ido Yaron - Cloud Computing 2020</div>
    </footer>
  </body>
</html>