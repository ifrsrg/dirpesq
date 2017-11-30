<?php
function carrega_scripts_frontend() {

	global $wp_styles;

	if ( is_customize_preview() )
		$inst_options = get_option('inst_options');
	else
		global $inst_options;

	// Carrega CSS principal do site
	wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css?family=Montserrat:400,700' );
	wp_enqueue_style( 'ufpel', get_stylesheet_uri(), array( 'fonts', 'dashicons') );
	wp_enqueue_style( 'ufpel-contraste', get_stylesheet_directory_uri() . "/css/contraste.css", array( 'ufpel' ) );

	// Insere CSS inline com personalizações do usuário
	// https://codex.wordpress.org/Function_Reference/wp_add_inline_style
	$cor = $inst_options['color_scheme'];
	$custom_css = "
		.corTexto,
		#titulo a:hover { color: $cor; }
		.corFundo { background-color: $cor; }
		.corBorda { border-color: $cor; }
		.corFill  { fill: $cor; }
	" . $inst_options['wpinst_style'];

	wp_add_inline_style( 'ufpel', $custom_css );


	// Carrega scripts

	wp_enqueue_script( 'jcarousel', get_template_directory_uri().'/js/jcarousel/jquery.jcarousel.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'jcarousel-swipe', get_template_directory_uri().'/js/jcarousel/jquery.jcarousel-swipe.min.js', 'jcarousel' );
	wp_enqueue_script( 'base-site', get_template_directory_uri().'/js/base.js', array( 'jquery' ) );

	if ( $inst_options['wpinst_barra'] == "sim" )
		wp_enqueue_script( 'barra-gov', '//barra.brasil.gov.br/barra.js', array( 'jquery' ), null, true );

}

add_action('wp_enqueue_scripts', 'carrega_scripts_frontend');


// Adiciona atributo 'defer' no script da Barra do Governo
function ufpel_add_defer_attribute( $tag, $handle ) {
	if ( 'barra-gov' !== $handle )
		return $tag;
	return str_replace( ' src', ' defer="defer" src', $tag );
}

add_filter( 'script_loader_tag', 'ufpel_add_defer_attribute', 10, 2 );
