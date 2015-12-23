<?php
$mapsID = 'maps-'. uniqid();

$data = array(
	'markers'                   => $settings->markers,
	'zoom'                      => $settings->zoom,
	'height'                    => $settings->height .'px',
);

$controls = array(
	'overviewMapControl'        => 'false',
	'rotateControl'             => 'false',
	'scaleControl'              => 'false',
	'scrollwheel'               => 'false',
	'width'                     => '100%',
);

// Filter to change Google maps controls
if( has_filter('bbgmap_map_controls') ) {
	$controls = apply_filters( 'bbgmap_map_controls', $controls );
}

$atts = array_merge( $data, $controls );

// do_shortcode on all content field
foreach ( $atts['markers'] as $id => $marker) {
	$atts['markers'][$id]->content = do_shortcode( $atts['markers'][0]->content );
}


if( !isset( $atts['markers'] ) || ( empty(  $atts['markers'][0]->lat ) || empty(  $atts['markers'][0]->lng ) ) ) {
	?>
	<div class="alert alert-danger" role="alert"><?php _e('Add a marker to see the map', 'bbgmap'); ?></div>
	<?php
	return;
}
?>

<style>#<?php echo esc_html( $mapsID ); ?> img {max-width: initial;}</style>
<script>
	var $j = jQuery.noConflict();
	var gmarkers = [];

	$j( document ).ready(function($) {

		<?php
		$map_style = '[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]';

		// Filter to change Google maps style
		if( has_filter('bbgmap_map_style') ) {
			$map_style = apply_filters( 'bbgmap_map_style', $map_style );
		}
		?>
		var styles = <?php echo $map_style; ?>;

		var styledMap = new google.maps.StyledMapType( styles, {name: "Routier"} );

		var latlng = new google.maps.LatLng(<?php echo $atts['markers'][0]->lat; ?>,<?php echo $atts['markers'][0]->lng; ?>); // Paris

		var data = <?php echo json_encode( array( 'markers' => $atts['markers'] ) ); ?>;

		<?php if( count( $atts['markers'] ) > 1 ): ?>
			var bounds = true;
		<?php else: ?>
			var bounds = false;
		<?php endif; ?>

		// We need to bind the map with the "init" event otherwise bounds will be null
		$('#<?php echo esc_html( $mapsID ); ?>')
			.gmap({
				zoom:					<?php echo esc_js( $atts['zoom'] ); ?>,
				center:					latlng,
				overviewMapControl:		<?php echo esc_js( $atts['overviewMapControl'] ); ?>,
				rotateControl:			<?php echo esc_js( $atts['rotateControl'] ); ?>,
				scaleControl:			<?php echo esc_js( $atts['scaleControl'] ); ?>,
				scrollwheel:			<?php echo esc_js( $atts['scrollwheel'] ); ?>,
				streetViewControl:		false,
				mapTypeControlOptions:	{
					mapTypeIds: [google.maps.MapTypeId.SATELLITE, 'map_style']
				}
			})
			.bind('init', function(evt, map) {

				//Associate the styled map with the MapTypeId and set it to display.
				map.mapTypes.set('map_style', styledMap);
				map.setMapTypeId('map_style');

				$.each( data.markers, function(i, marker)
				{
					if( marker.marker ) {
						var icon = marker.marker_src;
					} else {
						var icon = '<?php echo TP_BB_GMAP_URL; ?>tpbbgmap/assets/images/marker.png';
					}


					var m = $('#<?php echo esc_js( $mapsID ); ?>').gmap('addMarker', {
						'position': new google.maps.LatLng( marker.lat, marker.lng ),
						'icon': icon,
						'bounds': bounds
					}).click(function() {
						if(marker.content)
						{
							$('#<?php echo esc_js( $mapsID ); ?>').gmap('openInfoWindow', { content : the_content( marker ) }, this);
						}
					})[0];
				});

				$('#<?php echo esc_js( $mapsID ); ?>').gmap('set', 'MarkerClusterer', new MarkerClusterer(map, $(this).gmap('get', 'markers')));

			});
	});
</script>
<div class="gmap" id="<?php echo esc_html( $mapsID ); ?>" style="width: <?php echo $atts['width']; ?>; height: <?php echo $atts['height']; ?>;"></div>