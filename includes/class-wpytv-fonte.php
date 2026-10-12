<?php
/**
 * Obtén os vídeos dunha canle de YouTube a partir do feed RSS público.
 *
 * @package WP_YouTube_Videos
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPYTV_Fonte {

	const CABEZAS  = array(
		'Accept-Language' => 'gl-ES,gl;q=0.9,es;q=0.8,en;q=0.7',
	);

	/**
	 * Devolve a lista de vídeos da canle.
	 *
	 * @param string $canle  Nome (@canle), URL ou ID (UC…) da canle.
	 * @param int    $maximo Número máximo de vídeos (0 = todos).
	 * @return array|WP_Error Lista de vídeos: id, titulo, data (timestamp), miniatura.
	 */
	public static function videos( $canle, $maximo = 0 ) {
		$canle = trim( (string) $canle );
		if ( '' === $canle ) {
			return new WP_Error( 'wpytv_sen_canle', __( 'Non se indicou ningunha canle.', 'wp-youtube-videos' ) );
		}

		return self::desde_rss( $canle, (int) $maximo );
	}

	/* --------------------------------------------------------------------
	 * Interpretar o que escribe o usuario
	 * ------------------------------------------------------------------ */

	/**
	 * Converte o texto do shortcode nun tipo e valor.
	 *
	 * @param string $canle Texto introducido.
	 * @return array [ tipo => id|handle|usuario, valor => … ]
	 */
	private static function interpretar( $canle ) {
		$canle = trim( $canle );

		// ID de canle (UC + 22 caracteres).
		if ( preg_match( '~^UC[\w-]{22}$~', $canle ) ) {
			return array( 'tipo' => 'id', 'valor' => $canle );
		}

		return array( 'tipo' => 'handle', 'valor' => ltrim( $canle, '@' ) );
	}

	/* --------------------------------------------------------------------
	 * Feed RSS → últimos 15 vídeos, sen clave
	 * ------------------------------------------------------------------ */

	private static function id_canle( $canle ) {
		$c = self::interpretar( $canle );
		if ( 'id' === $c['tipo'] ) {
			return $c['valor'];
		}

		/* translators: %s: nome da canle */
		return new WP_Error( 'wpytv_non_existe', sprintf( __( 'Non se atopou a canle «%s».', 'wp-youtube-videos' ), $canle ) );
	}

	private static function desde_rss( $canle, $maximo ) {
		$id = self::id_canle( $canle );
		if ( is_wp_error( $id ) ) {
			return $id;
		}

		$resp = wp_remote_get(
			'https://www.youtube.com/feeds/videos.xml?channel_id=' . rawurlencode( $id ),
			array(
				'timeout' => 15,
				'headers' => self::CABEZAS,
			)
		);
		if ( is_wp_error( $resp ) ) {
			return $resp;
		}
		if ( 200 !== wp_remote_retrieve_response_code( $resp ) ) {
			return new WP_Error( 'wpytv_rss', __( 'Non se puido ler o feed da canle.', 'wp-youtube-videos' ) );
		}

		$prev = libxml_use_internal_errors( true );
		$xml  = simplexml_load_string( wp_remote_retrieve_body( $resp ) );
		libxml_use_internal_errors( $prev );
		if ( ! $xml ) {
			return new WP_Error( 'wpytv_rss', __( 'O feed da canle non é válido.', 'wp-youtube-videos' ) );
		}

		$videos = array();
		foreach ( $xml->entry as $entrada ) {
			$yt  = $entrada->children( 'http://www.youtube.com/xml/schemas/2015' );
			$vid = (string) $yt->videoId;
			if ( ! $vid ) {
				continue;
			}
			$videos[] = array(
				'id'        => $vid,
				'titulo'    => (string) $entrada->title,
				'data'      => strtotime( (string) $entrada->published ),
				'miniatura' => 'https://i.ytimg.com/vi/' . rawurlencode( $vid ) . '/mqdefault.jpg',
			);
			if ( $maximo && count( $videos ) >= $maximo ) {
				break;
			}
		}

		return $videos;
	}
}
