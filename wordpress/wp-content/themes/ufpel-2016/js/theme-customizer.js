jQuery(document).ready(function($) {

	// Atualiza título do site em tempo real
	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '#titulo #nomesite' ).html( newval );
		} );
	} );

	// Atualiza descrição do site
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '.descricaosite' ).html( newval );
		} );
	} );

	// Atualiza unidade de origem no título do site
	wp.customize( 'ufpel_options[und_vinc]', function( value ) {
		value.bind( function( newval ) {
			newval = newval.trim();
			if (newval.length) {
				newval += '<br />';
			}
			$( '#titulo #und_vinc' ).html( newval );
		} );
	} );

});