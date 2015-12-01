<?php
/**
 * @class FLMapModule
 */
class TPgMapModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct()
	{
		parent::__construct(array(
			'name'          => __('Google Map', 'fl-builder'),
			'description'   => __('Display a Google map.', 'fl-builder'),
			'category'      => __('Advanced Modules', 'fl-builder'),
            'dir'           => TP_BB_GMAP_DIR,
            'url'           => TP_BB_GMAP_URL,
		));

		$this->add_js( 'google-maps',       '//maps.google.com/maps/api/js?sensor=false&#038;language=fr', array('jquery'), null );
		$this->add_js( 'markerclusterer',   $this->url .'assets/js/markerclusterer.min.js', array('jquery'), null, null );
		$this->add_js( 'jquery-ui-map',     $this->url .'assets/js/jquery.ui.map.min.js', array('jquery'), null, null );

	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('TPgMapModule', array(
	'general'       => array(
		'title'         => __('General', 'fl-builder'),
		'sections'      => array(
			'general'       => array(
				'title'         => '',
				'fields'        => array(
					'lat'       => array(
						'type'          => 'text',
						'label'         => __('Latitude', 'fl-builder'),
						'placeholder'   => __('48.8582807', 'fl-builder'),
						'preview'         => array(
							'type'            => 'refresh'
						)
					),
					'lng'       => array(
						'type'          => 'text',
						'label'         => __('Longitude', 'fl-builder'),
						'placeholder'   => __('2.386331'),
						'preview'         => array(
							'type'            => 'refresh'
						)
					),
					'zoom'        => array(
						'type'          => 'select',
						'label'         => __('Zoom', 'fl-builder'),
						'default'       => '13',
						'options'       => array(
							'1'      => __( '1 (space)', 'fl-builder' ),
							'2'      => '2',
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
							'21'     => __( '21 (street)', 'fl-builder' ),
						),
						'preview'      => array(
							'type'         => 'refresh'
						)
					),
					'height'        => array(
						'type'          => 'text',
						'label'         => __('Height', 'fl-builder'),
						'default'       => '300',
						'size'          => '5',
						'description'   => 'px',
						'preview'      => array(
							'type'         => 'refresh'
						)
					),
					'content'        => array(
						'type'          => 'editor',
						'media_buttons' => true,
						'rows'          => 10
					),
					'marker'        => array(
						'type'          => 'photo',
						'label'         => __('Marker', 'fl-builder'),
						'preview'      => array(
							'type'         => 'refresh'
						)
					)
				)
			)
		)
	)
));