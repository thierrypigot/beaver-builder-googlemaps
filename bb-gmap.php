<?php
/**
 * Plugin Name: Beaver Builder - Google Maps
 * Plugin URI: http://www.thierry-pigot.fr
 * Description: Google Maps module for Beaver Builder.
 * Version: 1.1
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
		require_once 'includes/tp_bb_gmap_backend.php';
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