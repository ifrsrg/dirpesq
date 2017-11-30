<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the URA construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package CSDS URA
 * @subpackage themes
 * @since 1.5.3.0
 * @updated 1.5.3.8
 */
$confirmed = ( boolean ) false; 
get_header(); 
$theme = wp_get_theme();
do_action( 'page_template_header', $theme );
$id = get_the_ID();
$post = get_post( $id, OBJECT );
$post_name = $post->post_name;
$args = array(
	'post_type'=> 'page',
);
query_posts( $args );
// Start the loop.
if( $confirmed == false ){
	while ( have_posts() ) {
		the_post();

		// Include the page content template.
		
		$utc = new URA_TEMPLATE_CONTROLLER();
		$templates = $utc->template_array();
		//wp_die( print_r( $templates ) );
		// selects out template if it exists
		if( array_key_exists( $post_name, $templates ) ){
			if( $confirmed == false ){
				ura_get_template_part( $post_name, $post_name );
			}
			$confirmed = true;
			//break;
		}else{
			get_template_part( 'content', 'page' );
			//break;
		}

	// End the loop.

	}
}
do_action( 'page_template_sidebar', $theme );
do_action( 'page_template_footer', $theme );
