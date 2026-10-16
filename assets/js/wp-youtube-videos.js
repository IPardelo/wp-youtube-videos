/* WP YouTube Vídeos */
( function () {
	'use strict';

	var T = window.wpytvTextos || {};

	function txt( clave, n ) {
		return String( T[ clave ] || '' ).replace( '%d', n );
	}

	/* Datas relativas: recalcúlanse no navegador para que sigan ben
	   aínda que a páxina estea gardada en caché. */
	function dataRelativa( ts ) {
		var hoxe = new Date();
		hoxe.setHours( 0, 0, 0, 0 );
		var dia = new Date( ts * 1000 );
		dia.setHours( 0, 0, 0, 0 );
		var dias = Math.round( ( hoxe - dia ) / 86400000 );
		var n;

		if ( dias <= 0 ) { return txt( 'hoxe' ); }
		if ( dias === 1 ) { return txt( 'onte' ); }
		if ( dias < 7 ) { return txt( 'dias', dias ); }
		if ( dias < 30 ) { n = Math.floor( dias / 7 ); return n === 1 ? txt( 'semana' ) : txt( 'semanas', n ); }
		if ( dias < 365 ) { n = Math.floor( dias / 30 ); return n === 1 ? txt( 'mes' ) : txt( 'meses', n ); }
		n = Math.floor( dias / 365 );
		return n === 1 ? txt( 'ano' ) : txt( 'anos', n );
	}

	function iniciar( bloque ) {
		var mostrar = parseInt( bloque.getAttribute( 'data-mostrar' ), 10 ) || 5;
		var texto = bloque.getAttribute( 'data-texto' ) || '%d';
		var boton = bloque.querySelector( '.wpytv-mais' );

		bloque.querySelectorAll( '.wpytv-data[data-ts]' ).forEach( function ( el ) {
			var r = dataRelativa( parseInt( el.getAttribute( 'data-ts' ), 10 ) );
			if ( r ) { el.textContent = r; }
		} );

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
