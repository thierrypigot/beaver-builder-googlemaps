(function($){

	FLBuilder.registerModuleHelper('tpbbgmap', {

		rules: {
			lat: {
				required: true
			},
			lng: {
				required: true
			},
			zoom: {
				required: true,
				number: true
			},
			height: {
				required: true,
				number: true
			}
		},
		init: function()
		{
			function locateByCoordinates(coordinates)
			{
				latlngTemp = coordinates.split( ',', 2 );
				lat = parseFloat( latlngTemp[0] );
				lng = parseFloat( latlngTemp[1] );

				latlng = new google.maps.LatLng( lat, lng );

				geocoder.geocode({'latLng':latlng},function(results,status){
					if( status == google.maps.GeocoderStatus.OK )
					{
						address							= results[0].formatted_address;

						addMarker( latlng, address );

						//$('.lat').val() = lat;
						//$('.lng').val() = lng;
					}
					else{
						alert( "This place could not be found : " + status )
					}
				});
			}



			var map, lat, lng, coordinate, latlng, address;


			if( document.getElementsByClassName('lat')[0].length > 0 && document.getElementsByClassName('lng')[0].length > 0  )
			{
				lat = document.getElementsByClassName('lat')[0].value;
				lng = document.getElementsByClassName('lng')[0].value;
			}else{
				lat = 48.8582807;
				lng = 2.386331;
			}

			var geocoder = new google.maps.Geocoder();

			latlng = new google.maps.LatLng(lat, lng);

			// Enable the visual refresh
			google.maps.visualRefresh = true;

			var mapOptions = {
				zoom: 10,
				mapTypeControl: false,
				streetViewControl: false,
				center: latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: false,
				styles: [{
					featureType: "poi",
					elementType: "labels",
					stylers: [{
						visibility: 'on'
					}]
				}]
			};
			map = new google.maps.Map( document.getElementById('location_map'), mapOptions);

			var mapdiv = document.getElementById('location_map');
			mapdiv.style.height = '300px';

			addMarker( map.getCenter() );

			google.maps.event.addListener(map,'click',function(point){
				locateByCoordinates( point.latLng.lat() + ',' + point.latLng.lng() );
			});
			/*
			getCoordinates.addEventListener('click',function(event){
				location=locationInput.value;

				locateByAddress( location );

				event.stopPropagation();
				event.preventDefault();
				return false
			},false);
			*/
		}

	});

})(jQuery);