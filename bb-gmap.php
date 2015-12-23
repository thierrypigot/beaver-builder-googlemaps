<?php
/**
 * Plugin Name: Beaver Builder - Google Maps
 * Plugin URI: http://www.thierry-pigot.fr
 * Description: Google Maps module for Beaver Builder.
 * Version: 1.3
 * Author: Thierry Pigot
 * Author URI: http://www.thierry-pigot.fr
 */
 
 /*
 * todo[
 * Vérifier l'exécution des shortcodes dans le contenu
 * Ajouter un éditeur Wysiwyg pour générer les coordonnées, avec récupération par l'adresse ou le positionnement physique sur la carte
 * Faire les traductions (PoMo)
 * Ajouter des hooks :
 * - modifier le wrapper html de la popup du marqueur
 * - changer l'image du pins de base
 * ]
*/

define('TP_BB_GMAP_DIR', plugin_dir_path(__FILE__));
define('TP_BB_GMAP_URL', plugins_url('/', __FILE__));

// Custom modules
function tp_bb_load_module_gmap() {
	if( class_exists('FLBuilder') ) {
		require_once 'tpbbgmap/tpbbgmap.php';
	}
}
add_action('init', 'tp_bb_load_module_gmap');

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
add_action( 'plugins_loaded', 'tp_bb_load_textdomain_gmap' );
function tp_bb_load_textdomain_gmap()
{
	load_plugin_textdomain( 'bbgmap', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// Custom fields
function tp_bb_custom_field_gmap($name, $value, $field) {
	?>
	<script>
		jQuery(document).ready(function($){

			function addMarker( position, address ) {
				if( marker ){
					marker.setMap(null)
				}

				marker = new google.maps.Marker({
					map: map,
					position: position,
					title: address,
					draggable: true
				});

				map.setCenter(position);
				dragdropMarker()
			}

			function dragdropMarker(){
				google.maps.event.addListener( marker, 'dragend', function(mapEvent){
					coordinates = mapEvent.latLng.lat() + ',' + mapEvent.latLng.lng();
					locateByCoordinates( coordinates )
				})
			}

			function locateByAddress( address )
			{
				geocoder.geocode({'address':address},function(results,status){
					if(status == google.maps.GeocoderStatus.OK){
						addMarker(results[0].geometry.location,address);

						lat = results[0].geometry.location.lat();
						lng = results[0].geometry.location.lng()

						coordinates = lat + ',' + lng;

						getLat.val( lat );
						getLng.val( lng );
					}
					else{
						alert( "<?php _e('This address could not be found: ', 'bbgmap'); ?> (" + address +"): " + status )
					}
				})
			}

			function locateByCoordinates( coordinates )
			{
				latlngTemp = coordinates.split( ',', 2 );
				lat = parseFloat( latlngTemp[0] );
				lng = parseFloat( latlngTemp[1] );
				latlng = new google.maps.LatLng( lat, lng );
				geocoder.geocode({'latLng':latlng},function(results,status){
					if( status == google.maps.GeocoderStatus.OK )
					{
						address = results[0].formatted_address;

						addMarker( latlng, address );

						getLat.val( lat );
						getLng.val( lng );
					}
					else{
						alert( "<?php _e('This place could not be found: ','bbgmap');?>" + status )
					}
				})
			}

			var map, lat, lng, latlng, marker, address, coordinates;

			var geocoder = new google.maps.Geocoder();

			var getLat	= $('.lat');
			var getLng	= $('.lng');

			if( getLat.val().length > 0 && getLng.val().length > 0 ) {
				lat = getLat.val();
				lng = getLng.val();
			}else{
				lat = "48.8706011";
				lng = "2.3147646";
			}
			coordinates = lat + ',' + lng;

			latlng = new google.maps.LatLng( lat, lng );

			// Enable the visual refresh
			google.maps.visualRefresh = true;

			var mapOptions = {
				zoom:				10,
				mapTypeControl:		0,
				streetViewControl:	0,
				center:				latlng,
				mapTypeId:			google.maps.MapTypeId.ROADMAP,
				scrollwheel:		0,
				styles:[{
					featureType:"poi",
					elementType:"labels",
					stylers:[{
						visibility:"off"
					}]
				}]
			};
			map = new google.maps.Map(document.getElementById('location_map'),mapOptions);

			var mapdiv = document.getElementById('location_map');

			mapdiv.style.height = '300px';

			if( coordinates ) {
				addMarker( map.getCenter() )
			}
			google.maps.event.addListener(map, 'click', function( point ) {
				locateByCoordinates( point.latLng.lat() + ',' + point.latLng.lng() )
			});

			$("#get_coordinates").click(function(){
				var getAddress = $('#address').val();

				if( getAddress.length > 0 ) {
					locateByAddress( getAddress );
				}else{
					alert( "<?php _e('Please, i need a address to work ;)','bbgmap');?>" )
				}

			});
		});
	</script>
	<input type="text" class="text text-full" id="address" placeholder="<?php _e('55 Rue du Faubourg Saint-Honoré, 75008 Paris, France', 'bbgmap'); ?>">
	<div class="label label-primary get_coordinates"><a href="#" id="get_coordinates"><i class="fa fa-crosshairs"></i> <?php _e('Get coordinates','bbgmap'); ?></a></div>
	<div id="tp-location">
		<div class="location_map-container">
			<div class="location_map" id="location_map"></div>
			<div class="label label-info instruction"><i class="fa fa-info"></i> <?php _e('Move the marker to reposition','bbgmap'); ?></div>
		</div>
	</div>

	<?php
}
add_action('fl_builder_control_gmap', 'tp_bb_custom_field_gmap', 1, 3);
