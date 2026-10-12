<?php
/**
 * Shortcode [videos_youtube].
 *
 * @package WP_YouTube_Videos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPYTV_Shortcode {

	public static function init() {
		add_shortcode( 'videos_youtube', array( __CLASS__, 'render' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'rexistrar_recursos' ) );
	}

	public static function rexistrar_recursos() {
		wp_register_style( 'wp-youtube-videos', WPYTV_URL . 'assets/css/wp-youtube-videos.css', array(), WPYTV_VERSION );
	}

	public static function render( $atts ) {
		$a = shortcode_atts(
			array(
				'canle'       => '',
				'mostrar'     => 5,
			),
			$atts,
			'videos_youtube'
		);

		$mostrar  = max( 1, (int) $a['mostrar'] );
		$columnas = min( $mostrar, 8 );

		$videos = WPYTV_Fonte::videos( $a['canle'] );

		if ( is_wp_error( $videos ) || empty( $videos ) ) {
			return '';
		}

		wp_enqueue_style( 'wp-youtube-videos' );


		ob_start();
		?>
		<div class="wpytv" style="--wpytv-columnas: <?php echo (int) $columnas; ?>;">
			<ul class="wpytv-grella">
				<?php foreach ( array_slice( $videos, 0, $mostrar ) as $v ) : ?>
					<?php
					$url = 'https://www.youtube.com/watch?v=' . rawurlencode( $v['id'] );
					?>
					<li class="wpytv-video">
						<a class="wpytv-ligazon" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" title="<?php echo esc_attr( $v['titulo'] ); ?>">
							<span class="wpytv-miniatura">
								<img src="<?php echo esc_url( $v['miniatura'] ); ?>" alt="" loading="lazy" width="320" height="180">
								<span class="wpytv-play" aria-hidden="true"></span>
							</span>
							<span class="wpytv-titulo"><?php echo esc_html( $v['titulo'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		return trim( ob_get_clean() );
	}
}
