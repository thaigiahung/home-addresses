<!DOCTYPE html>
<html>
	<head>
		<title>Home Addresses</title>
		<meta charset="utf8">
		<meta http-equiv="x-ua-compatible" content="ie=edge,chrome=1">
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

		<link rel="stylesheet" href="css/app.css">		
	</head>
	<body>
		<div id="map_wrapper">
		    <div id="map_canvas" class="mapping"></div>
		</div>

		<?php
			error_reporting(E_ALL);
			ini_set('display_errors', 1);

			require_once('libs/HomeAddressesHelper.php');

			$sql = "SELECT * FROM `address`";
	        $result = HomeAddressesHelper::ExecuteQuery($sql);
	        $arr = [];
	        while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
	        {
	        	array_push($arr, $row);
	        }
		?>

		<script src="js/jquery-1.11.3.min.js"></script>
		<script>
			jQuery(function($) {    
			    // Asynchronously Load the map API 
			    var script = document.createElement('script');
			    script.src = "http://maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
			    document.body.appendChild(script);
			});

			function initialize() {
			    var map;
			    var bounds = new google.maps.LatLngBounds();
			    var mapOptions = {
			        mapTypeId: 'roadmap',
			        center: new google.maps.LatLng(10.776481, 106.683232),
      				zoom: 12,
			    };
			                    
			    // Display a map on the page
			    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
			    map.setTilt(45);

			    var infos = '<?php echo json_encode($arr); ?>';
			    infos = JSON.parse(infos);
			        
			    // Display multiple markers on a map
			    var infoWindow = new google.maps.InfoWindow(), marker, i;
			    
			    // Loop through our array of markers & place each one on the map  
			    for( i = 0; i < infos.length; i++ ) {
			        var position = new google.maps.LatLng(infos[i]['lat'], infos[i]['lng']);
			        bounds.extend(position);
			        marker = new google.maps.Marker({
			            position: position,
			            map: map,
			            title: infos[i]['last_name'] + ' ' + infos[i]['first_name']
			        });
			        
			        // Allow each marker to have an info window    
			        google.maps.event.addListener(marker, 'click', (function(marker, i) {
			            return function() {
			                infoWindow.setContent(
			                	'<div class="info_content">' +
			                		'<h3>'+infos[i]['last_name'] + ' ' + infos[i]['first_name'] +'</h3>' +
			                		'<p>'+infos[i]['address']+'</p>'+
			                	'</div>'
		                	);
			                infoWindow.open(map, marker);
			            }
			        })(marker, i));

			        // Automatically center the map fitting all markers on the screen
			        map.fitBounds(bounds);
			    }

			    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
			    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
			        this.setZoom(14);
			        google.maps.event.removeListener(boundsListener);
			    });
			    
			}
		</script>
	</body>
</html>