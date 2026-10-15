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

	/**
	 * Textos das datas relativas.
	 */
	private static function textos_datas() {
		return array(
			'hoxe'    => __( 'Hoxe', 'wp-youtube-videos' ),
			'onte'    => __( 'Onte', 'wp-youtube-videos' ),
			/* translators: %d: número de días */
			'dias'    => __( 'hai %d días', 'wp-youtube-videos' ),
			'semana'  => __( 'hai 1 semana', 'wp-youtube-videos' ),
			/* translators: %d: número de semanas */
			'semanas' => __( 'hai %d semanas', 'wp-youtube-videos' ),
			'mes'     => __( 'hai 1 mes', 'wp-youtube-videos' ),
			/* translators: %d: número de meses */
			'meses'   => __( 'hai %d meses', 'wp-youtube-videos' ),
			'ano'     => __( 'hai 1 ano', 'wp-youtube-videos' ),
			/* translators: %d: número de anos */
			'anos'    => __( 'hai %d anos', 'wp-youtube-videos' ),
		);
	}

	/**
	 * Data relativa ("Hoxe", "Onte", "hai 3 días"…).
	 *
	 * @param int $ts Timestamp.
	 * @return string
	 */
	public static function data_relativa( $ts ) {
		$t    = self::textos_datas();
		$zona = wp_timezone();
		$hoxe = new DateTime( 'today', $zona );
		$dia  = ( new DateTime( '@' . (int) $ts ) )->setTimezone( $zona )->setTime( 0, 0 );
		$dias = (int) floor( ( $hoxe->getTimestamp() - $dia->getTimestamp() ) / DAY_IN_SECONDS );

		if ( $dias <= 0 ) {
			return $t['hoxe'];
		}
		if ( 1 === $dias ) {
			return $t['onte'];
		}
		if ( $dias < 7 ) {
			return sprintf( $t['dias'], $dias );
		}
		if ( $dias < 30 ) {
			$n = (int) floor( $dias / 7 );
			return 1 === $n ? $t['semana'] : sprintf( $t['semanas'], $n );
		}
		if ( $dias < 365 ) {
			$n = (int) floor( $dias / 30 );
			return 1 === $n ? $t['mes'] : sprintf( $t['meses'], $n );
		}
		$n = (int) floor( $dias / 365 );
		return 1 === $n ? $t['ano'] : sprintf( $t['anos'], $n );
	}

	public static function render( $atts ) {
		$a = shortcode_atts(
			array(
				'canle'       => '',
				'mostrar'     => 5,
				'datas'       => 'si',      // si | non.
			),
			$atts,
			'videos_youtube'
		);

		$mostrar  = max( 1, (int) $a['mostrar'] );
		$columnas = min( $mostrar, 8 );
		$datas    = ! in_array( strtolower( $a['datas'] ), array( 'non', 'no', '0', 'false' ), true );

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
						<?php if ( $datas ) : ?>
							<time class="wpytv-data" datetime="<?php echo esc_attr( gmdate( 'c', $v['data'] ) ); ?>" data-ts="<?php echo (int) $v['data']; ?>"><?php echo esc_html( self::data_relativa( $v['data'] ) ); ?></time>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		return trim( ob_get_clean() );
	}
}
