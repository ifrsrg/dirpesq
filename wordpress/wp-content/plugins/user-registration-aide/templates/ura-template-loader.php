<?php

/**
 * function URA_TEMPLATE_CONTROLLER
 * Instantiation for URA_TEMPLATE_CONTROLLER
 * @category function
 * @since 1.5.3.0
 * @updated 1.5.3.8
 * @access public
 * @params string $template
 * @returns string $template
*/

function ura_template_loader( $template ){
	
	$theme = wp_get_theme();
	$style = $theme->get( 'ThemeURI' ).'/stylesheet.css';
	if( empty( $style ) ){
		$style = $theme->get( 'ThemeURI' ).'/style.css';
	}
	if( empty( $style ) ){
		$stylesheet = get_option( 'stylesheet' );
	}
	$css = URA_CSS_PATH.'templates-style.css';
	wp_register_style( 'ura_templates_style', $css, false );
	
	$type = get_post_type();
	$post_type = ( string ) '';
	
	$id = get_the_ID();
	
	$post = get_post( $id, OBJECT );
	//wp_die( $post->post_name );
	if( is_object( $post ) ){
		$post_type = $post->ura_post_type;
	}
	$tc = new URA_TEMPLATE_CONTROLLER();
	$templates = $tc->template_array();
	//wp_die( print_r( $templates ) );
	if( !empty( $post_type ) ){
		if ( $post_type == 'ura-page' ){
			//wp_die( 'POST TYPE' );
			$template = URA_THEME_URL.'page.php';
			wp_enqueue_style( 'ura_templates_style' );
			if( !empty( $style ) ){
				wp_register_style( 'ura_parent_style', $style );
				wp_enqueue_style( 'ura_parent_style' );
			}else{
				wp_enqueue_style( $stylesheet );
			}
		}else{
			return $template;
		}
	}else{
		if( !empty( $post ) ){
			if( array_key_exists( $post->post_name, $templates ) ){
				//wp_die( 'ARRAY_KEY EXISTS' );
				$template = URA_THEME_URL.'page.php';
				wp_enqueue_style( 'ura_templates_style' );
				if( !empty( $style ) ){
					wp_register_style( 'ura_parent_style', $style );
					wp_enqueue_style( 'ura_parent_style' );
				}else{
					wp_enqueue_style( $stylesheet );
				}
			}else{
				return $template;
			}
		}else{
			return $template;
		}
	}
	/*
	if ( $post_type == 'ura-page' ){
		//wp_die( 'POST TYPE' );
		$template = URA_THEME_URL.'page.php';
		
	}
	if( empty( $template ) ){
		if( array_key_exists( $post->post_name, $templates ) ){
			//wp_die( 'ARRAY_KEY EXISTS' );
			$template = URA_THEME_URL.'page.php';
		}
	}
	*/
	return $template;
}

add_filter( 'template_include', 'ura_template_loader' );

/**
 * function ura_get_template_part
 * Get template part (for templates like loop)
 * @category function
 * @since 1.5.3.0
 * @updated 1.5.3.8
 * @access public
 * @params string $slug, string $name
 * @returns
*/

function ura_get_template_part( $slug, $name = '' ){
	$theme = wp_get_theme();
	$style = $theme->get( 'ThemeURI' ).'/stylesheet.css';
	if( empty( $style ) ){
		$style = $theme->get( 'ThemeURI' ).'/style.css';
	}
	if( empty( $style ) ){
		$stylesheet = get_option( 'stylesheet' );
	}
	if( !empty( $style ) ){
		wp_register_style( 'ura_parent_style', $style );
		//wp_enqueue_style( 'ura_parent_style' );
	}else{
		wp_register_style( 'ura_parent_style', $stylesheet );
		//wp_enqueue_style( 'ura_parent_style' );
	}
	//die( $name );
	$filename = $name.'.php';
	
	$jq_color = includes_url().'js/jquery/jquery.color.js';
	$jq_color_min = includes_url().'js/jquery/jquery.color.min.js';
	wp_register_script( 'jquery_color', $jq_color, false);
	wp_enqueue_script( 'jquery_color');
	wp_register_script( 'jquery_color_min', $jq_color_min, false);
	wp_enqueue_script('jquery_color_min');
	//exit( $name );
	if( $name == 'ura-reset-password' || $name == 'change-password' || $name == 'ura-update-password' ){
		do_action( 'password_update_page_load' );
	}
	
	if ( !locate_template( array( $filename, URA_TEMPLATE_URL.'/'.$filename ), true, false ) ) {
		// if not found then load our default, always require template
		wp_enqueue_style( 'ura_templates_style', plugins_url( 'css/templates-style.css', __FILE__ ) );
		if( is_child_theme() ){
			wp_enqueue_style( 'ura_parent_style' );
		}else{
			wp_enqueue_style( 'ura_parent_style' );
		}
		load_template( URA_PLUGIN_PATH.'/templates/'.$filename, false );
	}
	
	get_template_part( URA_THEME_URL.'page.php' );
	
}

/**
 * function ura_locate_template
 * Locates the template to be used ( child-theme or theme or plugin )
 * @category function
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params string $template
 * @returns string $file
*/

function ura_locate_template( $template )
{
	//die( $template );
	$file = locate_template( array( URA_THEME_URL.$template ), false, false );
	if ( empty( $file ) ) {
		$file = URA_THEME_URL.$template;
	}

	return $file;
}

/**
 * function ura_return_template
 * Returns the template to be used ( child-theme or theme or plugin )
 * @category function
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params string $template_name
 * @returns string $template
*/

function ura_return_template( $template_name )
{
	//die( $template_name );
	$template = locate_template( array( $template_name, URA_THEME_URL.$template_name ), false);
	if ( !$template ) {
		$template = URA_PLUGIN_PATH.'/templates/'.$template_name;
	}

	return $template;
}

/**
 * function ura_get_template
 * Get other templates (e.g. product attributes)
 * @category function
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params string $template_name, boolean $require_once
 * @returns string $template
*/

function ura_get_template( $template_name, $require_once = true )
{
	//die( $template_name );
	load_template( ura_return_template( $template_name ), $require_once );
}

/**
 * function ura_body_classes_check
 * checks for theme body class and adds filter if none
 * @category function
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params string $template
 * @returns string $template
*/

function ura_body_classes_check(){
	if ( has_filter( 'body_class', 'twentyfifteen_body_classes' ) ) {
		//add_filter( 'body_class', 'twentyfifteen_body_classes' );
	}
}