<?php $this->set('title_for_layout', __('Venues | %s', APP_NAME)); ?>
<section>
<h2>Venues</h2>
<p></p>

	<div id="brazil-map" style="width: 640px; height: 640px; margin: 1rem auto;"></div>

	<script type="text/javascript" 
		src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADVGUeoLZJz5Fc0sCdDGWKPk4bFPjc9Ao&sensor=false">
	</script>
	<script type="text/javascript"
		src="/js/infobox.js">
	</script>

	<script>

		var marker, coords, map;
		var inwin = new google.maps.InfoWindow({
			content: ''
		});

		function init_map() {
		  var mapOptions = {
		    center: new google.maps.LatLng(-11.59305, -55.8545),
		    zoom: 4,
		    disableDefaultUI: true
		  };
		  map = new google.maps.Map(document.getElementById('brazil-map'), mapOptions);

		  $.ajax({
		  	dataType: 'json',
		  	url: '/api/venues/index',
		  	type: 'GET',
		  	success: function(r) {
		  		$.each(r, function(k,v) {
		  			console.log(v.Venue);
		  			marker = new google.maps.Marker({
		  				position: new google.maps.LatLng(v.Venue.lat, v.Venue.lng),
		  				map: map,
		  				title: v.Venue.stadium,
		  				icon: '/img/stadium.png'
		  			})

		  			bindInfoWindow(marker, inwin, v.Venue);
		  		})
		  	}
		  })

		}

		function bindInfoWindow(marker, inwin, venue) {

			var content;

			content  = '<div class="infobox"><h4>' + venue.city;
			content += '</h4><p><strong>' + venue.stadium;
			content += '</strong></p><p><a href="/venues/' + venue.id;
			content += '">view</a></p>';

			google.maps.event.addListener(marker, 'click', function() {
				inwin.setContent(content);
				inwin.open(map, marker);
			})
		};

		google.maps.event.addDomListener(window, 'load', init_map);



	</script>

</section>
