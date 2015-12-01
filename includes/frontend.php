<?php
$mapsID = 'maps-'. md5( time() );

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
<style>#<?php echo $mapsID; ?> img {max-width: initial;}</style>
<script>
	var $j = jQuery.noConflict();

	var styles = [
		{
			stylers: [
				{hue: ""},
				{saturation: "-50"},
				{lightness: "-3"},
			]
		},
		{
			featureType: "landscape", stylers: [
			{visibility: "on"},
			{hue: ""},
			{saturation: ""},
			{lightness: ""},
		]
		},
		{
			featureType: "administrative", stylers: [
			{visibility: "on"},
			{hue: ""},
			{saturation: ""},
			{lightness: ""},
		]
		},
		{
			featureType: "road", stylers: [
			{visibility: "on"},
			{hue: ""},
			{saturation: ""},
			{lightness: ""},
		]
		},
		{
			featureType: "water", stylers: [
			{visibility: "on"},
			{hue: ""},
			{saturation: ""},
			{lightness: ""},
		]
		},
		{
			featureType: "poi", stylers: [
			{visibility: "on"},
			{hue: ""},
			{saturation: ""},
			{lightness: ""},
		]
		},
	];

	var styledMap = new google.maps.StyledMapType( styles, {name: "Routier"} );
	var latlng = new google.maps.LatLng( <?php echo $atts['lat']; ?>, <?php echo $atts['lng']; ?> ); // Paris

	// This function picks up the click and opens the corresponding info window
	function myClick(i)
	{
		google.maps.event.trigger(gmarkers[i], "click");
	}

	function the_content( marker )
	{
		if( marker.content )
		{
			return '<div class="infoBox"><div class="item-data">' + marker.content + '</div></div>';
		}
	}

	var gmarkers = [];

	$j( document ).ready(function($) {

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
		$('#<?php echo $mapsID; ?>')
			.gmap({
				zoom:					<?php echo $atts['zoom']; ?>,
				center:					latlng,
				overviewMapControl:		<?php echo $atts['overviewMapControl']; ?>,
				rotateControl:			<?php echo $atts['rotateControl']; ?>,
				scaleControl:			<?php echo $atts['scaleControl']; ?>,
				scrollwheel:			<?php echo $atts['scrollwheel']; ?>,
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
					var m = $('#<?php echo $mapsID; ?>').gmap('addMarker', {
						'position': new google.maps.LatLng( marker.lat, marker.lng ),
						'icon': icon,
						'bounds': false
					}).click(function() {
						if(marker.content)
						{
							$('#<?php echo $mapsID; ?>').gmap('openInfoWindow', { content : the_content( marker ) }, this);
						}
					})[0];
				});

				$('#<?php echo $mapsID; ?>').gmap('set', 'MarkerClusterer', new MarkerClusterer(map, $(this).gmap('get', 'markers')));

			});
	});
</script>
<div class="gmap" id="<?php echo $mapsID; ?>" style="width: <?php echo $atts['width']; ?>; height: <?php echo $atts['height']; ?>;"></div>