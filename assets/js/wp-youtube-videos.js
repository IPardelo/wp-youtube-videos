/* WP YouTube Vídeos */
( function () {
	'use strict';

	function iniciar( bloque ) {
		var mostrar = parseInt( bloque.getAttribute( 'data-mostrar' ), 10 ) || 5;
		var texto = bloque.getAttribute( 'data-texto' ) || '%d';
		var boton = bloque.querySelector( '.wpytv-mais' );

		if ( boton ) {
			boton.addEventListener( 'click', function () {
				var ocultos = bloque.querySelectorAll( '.wpytv-video[hidden]' );
				for ( var i = 0; i < ocultos.length && i < mostrar; i++ ) {
					ocultos[ i ].hidden = false;
					ocultos[ i ].classList.add( 'wpytv-aparece' );
				}
				var restan = ocultos.length - Math.min( ocultos.length, mostrar );
				if ( restan > 0 ) {
					boton.textContent = texto.replace( '%d', restan );
				} else {
					boton.remove();
				}
			} );
		}
	}

	function arrancar() {
		document.querySelectorAll( '.wpytv' ).forEach( iniciar );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', arrancar );
	} else {
		arrancar();
	}
} )();
