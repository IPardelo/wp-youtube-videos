<?php
/**
 * Borra os axustes e a caché ao desinstalar o plugin.
 *
 * @package WP_YouTube_Videos
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

delete_option( 'wpytv_axustes' );
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'wpytv\_%' OR option_name LIKE '\_transient\_wpytv\_%' OR option_name LIKE '\_transient\_timeout\_wpytv\_%'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
