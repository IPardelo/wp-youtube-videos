<?php
/**
 * Plugin Name:       WP YouTube Vídeos
 * Description:       Mostra os vídeos dunha canle de YouTube nunha grella cun botón "máis". Shortcode: [videos_youtube canle="@nomedacanle"].
 * Version:           0.1.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Ismael Castiñeira
 * Author URI:        https://ipardelo.es
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-youtube-videos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPYTV_VERSION', '0.1.0' );
define( 'WPYTV_FILE', __FILE__ );
define( 'WPYTV_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPYTV_URL', plugin_dir_url( __FILE__ ) );
define( 'WPYTV_OPTION', 'wpytv_axustes' );

require_once WPYTV_DIR . 'includes/class-wpytv-fonte.php';
require_once WPYTV_DIR . 'includes/class-wpytv-shortcode.php';
require_once WPYTV_DIR . 'includes/class-wpytv-admin.php';

/**
 * Devolve os axustes do plugin cos valores por defecto.
 *
 * @return array
 */
function wpytv_axustes() {
	$defecto = array(
		'api_key' => '',
		'cache'   => 60, // minutos.
	);
	$gardados = get_option( WPYTV_OPTION, array() );
	return wp_parse_args( is_array( $gardados ) ? $gardados : array(), $defecto );
}

add_action(
	'plugins_loaded',
	function () {
		WPYTV_Shortcode::init();
		if ( is_admin() ) {
			WPYTV_Admin::init();
		}
	}
);
