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

	/* Reprodutor emerxente */
	var modal = null;
	var ultimoFoco = null;

	function pecharModal() {
		if ( ! modal ) { return; }
		modal.remove();
		modal = null;
		document.body.classList.remove( 'wpytv-sen-scroll' );
		document.removeEventListener( 'keydown', teclaModal );
		if ( ultimoFoco ) { ultimoFoco.focus(); }
	}

	function teclaModal( e ) {
		if ( e.key === 'Escape' ) { pecharModal(); }
	}

	function abrirModal( id, titulo ) {
		pecharModal();
		ultimoFoco = document.activeElement;

		modal = document.createElement( 'div' );
		modal.className = 'wpytv-modal';
		modal.setAttribute( 'role', 'dialog' );
		modal.setAttribute( 'aria-modal', 'true' );
		modal.setAttribute( 'aria-label', titulo );

		var caixa = document.createElement( 'div' );
		caixa.className = 'wpytv-modal-caixa';

		var iframe = document.createElement( 'iframe' );
		iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent( id ) + '?autoplay=1&rel=0';
		iframe.title = titulo;
		iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
		iframe.allowFullscreen = true;

		var pechar = document.createElement( 'button' );
		pechar.type = 'button';
		pechar.className = 'wpytv-modal-pechar';
		pechar.setAttribute( 'aria-label', txt( 'pechar' ) || 'Pechar' );
		pechar.innerHTML = '&times;';
		pechar.addEventListener( 'click', pecharModal );

		caixa.appendChild( pechar );
		caixa.appendChild( iframe );
		modal.appendChild( caixa );
		modal.addEventListener( 'click', function ( e ) {
			if ( e.target === modal ) { pecharModal(); }
		} );

		document.body.appendChild( modal );
		document.body.classList.add( 'wpytv-sen-scroll' );
		document.addEventListener( 'keydown', teclaModal );
		pechar.focus();
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

		if ( bloque.getAttribute( 'data-modal' ) === '1' ) {
			bloque.addEventListener( 'click', function ( e ) {
				var a = e.target.closest( '.wpytv-ligazon' );
				if ( ! a || e.ctrlKey || e.metaKey || e.shiftKey || e.button === 1 ) { return; }
				e.preventDefault();
				abrirModal( a.getAttribute( 'data-video' ), a.getAttribute( 'title' ) || '' );
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
