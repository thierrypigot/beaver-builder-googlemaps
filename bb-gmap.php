<?php
/**
 * Plugin Name: Beaver Builder - Google Maps
 * Plugin URI: http://www.thierry-pigot.fr
 * Description: Google Maps module for Beaver Builder.
 * Version: 1.0
 * Author: Thierry Pigot
 * Author URI: http://www.thierry-pigot.fr
 */
define('TP_BB_GMAP_DIR', plugin_dir_path(__FILE__));
define('TP_BB_GMAP_URL', plugins_url('/', __FILE__));

// Custom modules
function tp_bb_load_module_gmap() {
	if( class_exists('FLBuilder') ) {
		require_once 'includes/backend.php';
	}
}
add_action('init', 'tp_bb_load_module_gmap');