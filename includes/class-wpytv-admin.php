<?php
/**
 * Páxina de axustes: Axustes → YouTube Vídeos.
 *
 * @package WP_YouTube_Videos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPYTV_Admin {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'rexistrar' ) );
		add_action( 'update_option_' . WPYTV_OPTION, array( 'WPYTV_Fonte', 'baleirar_cache' ) );
	}

	public static function menu() {
		add_options_page(
			__( 'YouTube Vídeos', 'wp-youtube-videos' ),
			__( 'YouTube Vídeos', 'wp-youtube-videos' ),
			'manage_options',
			'wp-youtube-videos',
			array( __CLASS__, 'paxina' )
		);
	}

	public static function rexistrar() {
		register_setting(
			'wpytv',
			WPYTV_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'limpar' ),
			)
		);
	}

	public static function limpar( $entrada ) {
		return array(
			'api_key' => isset( $entrada['api_key'] ) ? sanitize_text_field( $entrada['api_key'] ) : '',
			'cache'   => isset( $entrada['cache'] ) ? max( 5, absint( $entrada['cache'] ) ) : 60,
		);
	}

	public static function paxina() {
		$ax = wpytv_axustes();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'YouTube Vídeos', 'wp-youtube-videos' ); ?></h1>

			<form method="post" action="options.php">
				<?php settings_fields( 'wpytv' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="wpytv-api"><?php esc_html_e( 'Clave da API de YouTube', 'wp-youtube-videos' ); ?></label></th>
						<td>
							<input type="password" id="wpytv-api" name="<?php echo esc_attr( WPYTV_OPTION ); ?>[api_key]" value="<?php echo esc_attr( $ax['api_key'] ); ?>" class="regular-text" autocomplete="off">
							<p class="description">
								<?php esc_html_e( 'Opcional. Sen clave só se mostran os 15 últimos vídeos de cada canle (feed RSS). Con clave móstranse todos.', 'wp-youtube-videos' ); ?><br>
								<?php
								printf(
									/* translators: %s: ligazón á consola de Google Cloud */
									esc_html__( 'Créase gratis en %s activando «YouTube Data API v3».', 'wp-youtube-videos' ),
									'<a href="https://console.cloud.google.com/apis/library/youtube.googleapis.com" target="_blank" rel="noopener">Google Cloud Console</a>'
								);
								?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="wpytv-cache"><?php esc_html_e( 'Actualizar cada', 'wp-youtube-videos' ); ?></label></th>
						<td>
							<input type="number" id="wpytv-cache" name="<?php echo esc_attr( WPYTV_OPTION ); ?>[cache]" value="<?php echo esc_attr( $ax['cache'] ); ?>" min="5" step="5" class="small-text">
							<?php esc_html_e( 'minutos', 'wp-youtube-videos' ); ?>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>

		</div>
		<?php
	}
}
