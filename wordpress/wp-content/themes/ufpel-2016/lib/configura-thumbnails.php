<?php
add_theme_support( 'post-thumbnails' );

function ufpel20_new_thumbnail_sizes() {

//	if ( get_theme_mod( 'updated_thumbnails' ) != 's' ) {
		// thumbnails e widget destaque
		update_option('thumbnail_size_w', 212);
		update_option('thumbnail_size_h', 159);
		update_option('thumbnail_crop', 0);

		// thumbnails resolução retina
		update_option('medium_size_w', 424);
		update_option('medium_size_h', 318);
		update_option('medium_crop', 0);

		// imagem de capa
		update_option('large_size_w', 984);
		update_option('large_size_h', 220);
		update_option('large_crop', 1);

//		set_theme_mod( 'updated_thumbnails', 's' );
//	}
}
add_action( 'admin_init', 'ufpel20_new_thumbnail_sizes' );


?>
