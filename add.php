<!DOCTYPE html>
<html>
<head>
	<title>Home Addresses</title>
	<meta charset="utf8">
	<link rel="stylesheet" href="css/app.css">
</head>
<body>
	<div id="map-search">
		<input id="search-txt" type="text" placeholder="306 Dien Bien Phu, district 3, Ho Chi Minh, Vietnam" maxlength="200">
		<input id="search-btn" type="button" value="Locate Address">
		<input id="detect-btn" type="button" value="Detect Location" disabled>
	</div>
	<div id="map-canvas"></div>
	<div id="map-output">
		<div>
			Latitude: <span id="lat"></span>
		</div>
		<div>
			Longitude: <span id="lng"></span>
		</div>
		<div>
			First name: <input type="text" id="firstName">
		</div>
		<div>
			Last name: <input type="text" id="lastName">
		</div>
		<input type="button" onclick="addAddress()" value="Add">
	</div>
	
	<script>
		/*
		 * Google Maps: Latitude-Longitude Finder Tool
		 * http://salman-w.blogspot.com/2009/03/latitude-longitude-finder-tool.html
		 */
		function loadmap() {
			// initialize map
			var map = new google.maps.Map(document.getElementById("map-canvas"), {
				center: new google.maps.LatLng(10.776481, 106.683232),
				zoom: 13,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			// initialize marker
			var marker = new google.maps.Marker({
				position: map.getCenter(),
				draggable: true,
				map: map
			});
			// intercept map and marker movements
			google.maps.event.addListener(map, "idle", function() {
				marker.setPosition(map.getCenter());
				/*document.getElementById("map-output").innerHTML = "Latitude:  " + map.getCenter().lat().toFixed(6) + "<br>Longitude: " + map.getCenter().lng().toFixed(6);*/

				document.getElementById("lat").innerHTML = map.getCenter().lat().toFixed(6);
				document.getElementById("lng").innerHTML = map.getCenter().lng().toFixed(6);
			});
			google.maps.event.addListener(marker, "dragend", function(mapEvent) {
				map.panTo(mapEvent.latLng);
			});
			// initialize geocoder
			var geocoder = new google.maps.Geocoder();
			google.maps.event.addDomListener(document.getElementById("search-btn"), "click", function() {
				geocoder.geocode({ address: document.getElementById("search-txt").value }, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						var result = results[0];
						document.getElementById("search-txt").value = result.formatted_address;
						if (result.geometry.viewport) {
							map.fitBounds(result.geometry.viewport);
						} else {
							map.setCenter(result.geometry.location);
						}
					} else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
						alert("Sorry, geocoder API failed to locate the address.");
					} else {
						alert("Sorry, geocoder API failed with an error.");
					}
				});
			});
			google.maps.event.addDomListener(document.getElementById("search-txt"), "keydown", function(domEvent) {
				if (domEvent.which === 13 || domEvent.keyCode === 13) {
					google.maps.event.trigger(document.getElementById("search-btn"), "click");
				}
			});
			// initialize geolocation
			if (navigator.geolocation) {
				google.maps.event.addDomListener(document.getElementById("detect-btn"), "click", function() {
					navigator.geolocation.getCurrentPosition(function(position) {
						map.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
					}, function() {
						alert("Sorry, geolocation API failed to detect your location.");
					});
				});
				document.getElementById("detect-btn").disabled = false;
			}			
		}
	</script>
	<script>
		function addAddress () 
		{
			var firstName = $("#firstName").val();
			var lastName = $("#lastName").val();
			var address = $("#search-txt").val();
			var lat = $("#lat").text();
			var lng = $("#lng").text();

			if(!address || !firstName || !lastName)
			{
				alert("Please fill in all fields!");
			}
			else
			{
				var data = {
				    firstName: firstName,
				    lastName: lastName,
				    address: address,
				    lat: lat,
				    lng: lng
				}

				$.post( "api/add.php", data).done(function( result ) {
					result = JSON.parse(result);
				    if(result.status == 1) {
				    	window.location.replace("index.php");
				    }
				    else
				    {
				    	alert(result.message);
				    }
			  	});
			}
		}
	</script>
	<script src="js/jquery-1.11.3.min.js"></script>
	<script src="//maps.googleapis.com/maps/api/js?v=3&amp;sensor=false&amp;key=AIzaSyD5qUxLbP6skeyLXRuzfQ6w_RQct74zyps&amp;callback=loadmap" defer></script>
</body>
</html>