jQuery(document).ready(function($) {

	/* Ao carregar a página, exibe imagem externa selecionada (se houver) */

	if ( $( "#imagemdecapa_externa" ).val() ) {
		$( '<img src="' + $( "#imagemdecapa_externa" ).val() + '" id="imagemdecapa-sugestao-preview">' ).insertAfter( "#postimagediv .inside" );
	}

	/* Imagem externa selecionada */

	$( "#imagemdecapa-sugestoes" ).on( "click", ".imagemdecapa-sugestao", function() {
		$( "#remove-post-thumbnail" ).click();	// dispara remoção da imagem destacada
		$( ".imagemdecapa-sugestao-selecionada" ).removeClass( "imagemdecapa-sugestao-selecionada" );
		$(this).addClass( "imagemdecapa-sugestao-selecionada" );
		$( "#imagemdecapa_externa" ).val( $(this).attr('src') );
		$( "#imagemdecapa-sugestao-preview" ).remove();
		$( '<img src="' + $(this).attr('src') + '" id="imagemdecapa-sugestao-preview">' ).insertAfter( "#postimagediv .inside" );
	});

	/* Detecta imagem destacada (padrão WP) selecionada e remove externa */

    var featuredImage = wp.media.featuredImage.frame();
    featuredImage.on( 'select', function() {
//		var attachment = featuredImage.state().get('selection').first().toJSON();
//		console.log(attachment);
		$( "#imagemdecapa-sugestao-preview" ).remove();
		$( "#imagemdecapa_externa" ).val('');	// remove imagem externa quando for selecionada uma imagem destacada
		$( ".imagemdecapa-sugestao-selecionada" ).removeClass( "imagemdecapa-sugestao-selecionada" );
    });

});