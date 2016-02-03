<?php

/**
 * @class TPgMapModule
 */
class TPgMapModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          	=> __('Google Map', 'bbgmap'),
			'description'   	=> __('Display a Google map.', 'bbgmap'),
			'category'          => __('WP in a day Modules', 'bbgmap'),
            'dir'               => TP_BB_GMAP_DIR .'tpbbgmap/',
            'url'               => TP_BB_GMAP_URL .'tpbbgmap/',
            'partial_refresh'	=> true
        ));

		$this->add_js( 'google-maps',       	'//maps.google.com/maps/api/js?sensor=false&#038;language=fr&amp;libraries=places', array('jquery'), null );
		$this->add_js( 'jquery-ui-map',     	$this->url .'assets/js/jquery.ui.map.min.js', array('jquery'), null, null );
		$this->add_js( 'markerclusterer',		$this->url .'assets/js/markerclusterer.min.js', array('jquery','jquery-ui-map'), null, null );
		$this->add_js( 'bb-gmaps-script',     	$this->url .'assets/js/script.js', array('markerclusterer'), null, null );

		add_filter('fl_builder_render_settings_field', array($this, 'extended_map_filters'), 10, 3);
	}

	public function update( $settings ) {

		if( !empty( $settings->content ) )
			$settings->content = do_shortcode( $settings->content );

		return $settings;
	}

	public function extended_map_filters( $field, $name, $settings ) {
		if( isset($field) && 'map_style' == $name && $settings->map_style ) {
			$settings->map_style = trim(stripslashes(json_encode($settings->map_style)), '"');
		}
		return $field;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('TPgMapModule', array(
	'general'       => array(
		'title'         => __('General', 'bbgmap'),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					'zoom'        => array(
						'type'          => 'select',
						'label'         => __('Zoom', 'bbgmap'),
						'default'       => '13',
						'options'       => array(
							'1'      => __( '1 (space)', 'bbgmap' ),
							'2'      => '2',
							'3'      => '3',
							'4'      => '4',
							'5'      => '5',
							'6'      => '6',
							'7'      => '7',
							'8'      => '8',
							'9'      => '9',
							'10'     => '10',
							'11'     => '11',
							'12'     => '12',
							'13'     => '13',
							'14'     => '14',
							'15'     => '15',
							'16'     => '16',
							'17'     => '17',
							'18'     => '18',
							'19'     => '19',
							'20'     => '20',
							'21'     => __( '21 (street)', 'bbgmap' ),
						),
						'preview'      => array(
							'type'         => 'refresh'
						)
					),
					'height'        => array(
						'type'          => 'text',
						'label'         => __('Height', 'bbgmap'),
						'default'       => '300',
						'size'          => '5',
						'description'   => 'px',
						'preview'      => array(
							'type'         => 'refresh'
						)
					),
				)
			)
		)
	),
	'markersSection'       => array(
		'title'         => __('Markers', 'bbgmap'),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					'markers'         => array(
						'type'          => 'form',
						'label'         => __('Marker', 'bbgmap'),
						'form'          => 'marker_group_form', // ID from registered form below
						'preview_text'  => 'title', // Name of a field to use for the preview text
						'multiple'      => true
					),
				)
			)
		)
	),
	'map_style'			=> array(
		'title'			=> __('Style', 'bbgmap'),
		'description'	=> __('Paste a style from <a href="http://snazzymaps.com" target="_blank">Snazzymaps</a> or <a href="http://www.mapstylr.com/" target="_blank">MapStylr</a>.', 'bbgmap'),
		'sections'	=> array(
			'map_style'			=> array(
				'title'			=> __('Style Code', 'bbgmap'),
				'fields'		=> array(
					'map_style'			=> array(
						'type'				=> 'textarea',
						'rows'				=> '15',
						'preview'      => array(
							'type'         => 'refresh'
						)
					)
				)
			)
		)
	)
));

/*
* Register a settings form to use in the "form" field type above.
 */
FLBuilder::register_settings_form('marker_group_form', array(
	'title' => __('Add Marker', 'bbgmap'),
	'tabs'  => array(
		'general'       => array( // Tab
			'title'         => __('General', 'bbgmap'), // Tab title
			'sections'      => array( // Tab Sections
				'general'       => array( // Section
					'title'         => '', // Section Title
					'fields'        => array( // Section Fields
						'title'       => array(
							'type'          => 'text',
							'label'         => __('Title', 'bbgmap'),
							'help'			=> __('Used to identify the markers in list', 'bbgmap'),
						),
						'map'		=> array(
							'type'	=> 'gmap',
						),
						'lat'       => array(
							'type'          => 'text',
							'label'         => __('Latitude', 'bbgmap'),
							'placeholder'   => __('48.8582807', 'bbgmap'),
							'class'			=> 'lat',
							'preview'         => array(
								'type'            => 'refresh'
							)
						),
						'lng'       => array(
							'type'          => 'text',
							'label'         => __('Longitude', 'bbgmap'),
							'placeholder'   => __('2.386331'),
							'class'			=> 'lng',
							'preview'         => array(
								'type'            => 'refresh'
							)
						),
						'marker'        => array(
							'type'          => 'photo',
							'label'         => __('Marker', 'bbgmap'),
							'preview'		=> array(
								'type'         => 'refresh'
							)
						),
						'content'        => array(
							'type'          => 'editor',
							'media_buttons' => true,
							'rows'          => 10
						),
					)
				)
			)
		)
	)
));