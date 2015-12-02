<?php
$mapsID = 'maps-'. uniqid();

$atts = array(
	'lat'                       => $settings->lat,
	'lng'                       => $settings->lng,
	'zoom'                      => $settings->zoom,
	'overviewMapControl'        => 'false',
	'rotateControl'             => 'false',
	'scaleControl'              => 'false',
	'scrollwheel'               => 'false',
	'width'                     => '100%',
	'height'                    => $settings->height .'px',
	'icon'                      => false,
	'content'                   => $settings->content,
);

if( !$atts['lat'] || !$atts['lng'] )
return;
?>
<style>#<?php echo esc_html( $mapsID ); ?> img {max-width: initial;}</style>
<script>
	var $j = jQuery.noConflict();

	$j( document ).ready(function($) {

		var latlng = new google.maps.LatLng( <?php echo esc_js( $atts['lat'] ); ?>, <?php echo esc_js( $atts['lng'] ); ?> ); // Paris

		<?php
		// Chargement des markers
		$markers['markers'][0]['lat'] = $atts['lat'];
		$markers['markers'][0]['lng'] = $atts['lng'];
		if( trim( $atts['content'] ) != '' )
			$markers['markers'][0]['content'] = $atts['content'];
		?>

		<?php if( $markers ): ?>
		var data = <?php echo json_encode( $markers ); ?>;
		<?php endif; ?>

		<?php if( $settings->marker ): ?>
			var icon = '<?php echo wp_get_attachment_image_src( $settings->marker )[0]; ?>';
		<?php else: ?>
			var icon = '<?php echo TP_BB_GMAP_URL; ?>assets/images/marker.png';
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
					var m = $('#<?php echo esc_js( $mapsID ); ?>').gmap('addMarker', {
						'position': new google.maps.LatLng( marker.lat, marker.lng ),
						'icon': icon,
						'bounds': false
					}).click(function() {
						if(marker.content)
						{
							$('#<?php echo esc_js( $mapsID ); ?>').gmap('openInfoWindow', { content : the_content( marker ) }, this);
						}
					})[0];
				});
			});
	});
</script>
<div class="gmap" id="<?php echo esc_html( $mapsID ); ?>" style="width: <?php echo $atts['width']; ?>; height: <?php echo $atts['height']; ?>;"></div>