<?php

function ufpel_custom_logo() {

	add_theme_support( 'custom-logo', array(
		'height' 		=> 220,
		'width' 		=> 460,
		'flex-width' 	=> true,
		'header-text' 	=> array( 'titulosite' ),
	) );

}
add_action( 'after_setup_theme', 'ufpel_custom_logo' );
