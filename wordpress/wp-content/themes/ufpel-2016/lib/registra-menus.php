<?php
function registra_menus() {
	register_nav_menus(
		array(
			'menu-principal' => 'Menu Principal',
			'menu-topo'		 => 'Menu da barra superior'
		)
	);
}
add_action( 'init', 'registra_menus' );
?>
