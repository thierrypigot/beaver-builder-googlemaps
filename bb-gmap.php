<?php
/**
 * Plugin Name: Beaver Builder - Google Maps
 * Plugin URI: http://www.thierry-pigot.fr
 * Description: Google Maps module for Beaver Builder.
 * Version: 1.2.1
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
	<input type="text" class="text text-full" id="address" value="55 Rue du Faubourg Saint-Honoré, 75008 Paris, France" placeholder="<?php _e('55 Rue du Faubourg Saint-Honoré, 75008 Paris, France', 'bbgmap'); ?>">
	<a href="#" id="get_coordinates"><?php _e('Get coordinates','bbgmap'); ?></a>
	<div id="tp-location">
		<div class="location_map-container">
			<div class="location_map" id="location_map"></div>
			<div class="instuction"><?php _e('Move the marker to reposition','bbgmap'); ?></div>
		</div>
	</div>

	<?php
}
// add_action('fl_builder_control_gmap', 'tp_bb_custom_field_gmap', 1, 3);
