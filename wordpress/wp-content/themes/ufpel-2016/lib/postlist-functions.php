<?php
function catch_that_image( $tag = true ) {
	// Retorna a primeira imagem inserida em um post
	global $post, $posts;
	$img = '';
	ob_start();
	ob_end_clean();
	preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
	// A linha abaixo pega o endereço da imagem original ao invés do thumbnail do texto
	// (desativada porque dá problema se a imagem não tiver link ou se não linkar para uma imagem)
	//preg_match_all( '/<a\s+href=[\'"]([^\'"]+)[\'"].*><img.*><\/a>/i', $post->post_content, $matches );
	$img = ( count( $matches[1] ) ) ? $matches[1][0] : '';

	if ( $tag && $img )
		$img = '<img src="'. $img .'">';

	return $img;
}

function wpe_excerptlength_search( $length ) {

	return 85;
}

function wpe_excerptlength_normal( $length ) {

	return 20;
}

function wpe_excerptlength_dest( $length ) {

	return 24;
}

function wpe_excerpt( $length_callback = '') {

	if ( function_exists( $length_callback ) )
		add_filter( 'excerpt_length', $length_callback );

	$output = get_the_excerpt();
	$output = apply_filters( 'wptexturize', $output );
	$output = apply_filters( 'convert_chars', $output );
	return $output;

}
?>
