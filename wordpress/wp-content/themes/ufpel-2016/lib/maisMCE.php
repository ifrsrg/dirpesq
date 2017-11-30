<?php
/**
 * @package maisMCE
 * @version 1.1
 */
/*
   Plugin Name: maisMCE
   Plugin URI: http://cti.ufpel.edu.br
   Description: Adiciona mais opções ao editor
   Author: CTI
   Version: 1.1
   Author URI: http://cti.ufpel.edu.br
*/

if ( ! function_exists( 'habilitar_mais_botoes_2' ) ) {
	function habilitar_mais_botoes_2( $buttons ) {
		$buttons[] = 'backcolor';
		$buttons[] = 'hr';
		$buttons[] = 'anchor';
		return $buttons;
  	}

	function habilitar_mais_botoes_3( $buttons ) {
		$buttons[] = 'fontselect';
		$buttons[] = 'fontsizeselect';
		return $buttons;
	}

	add_filter( "mce_buttons_2", "habilitar_mais_botoes_2" );
	add_filter( "mce_buttons_3", "habilitar_mais_botoes_3" );
}
?>