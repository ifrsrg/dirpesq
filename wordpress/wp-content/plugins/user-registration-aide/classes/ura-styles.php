<?php

/**
 * Class URA_STYLES
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_STYLES
{
		
	// ----------------------------------------     WordPress Enqueue Scripts & Styles Functions     ----------------------------------------
	
	/** Registers the main admin pages stylesheet for the plugin
	 * @since 1.3.0
	 * @updated 1.5.2.0
	 * @handles action 'wp_enqueue_scripts' line 167 &$this
	 * @handles action 'admin_init' line 168 &$this
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	 */
	
	function csds_userRegAide_stylesheet(){
		$xwrd_check = URA_JS_PATH."xwrd-check.js";
		wp_register_style( 'user_regAide_style', plugins_url( 'user-registration-aide/css/user-reg-aide-style.php' ) );
		wp_register_style( 'user_regAide_menu_style', plugins_url( 'user-registration-aide/css/wp-admin-menu-stylesheet.css' ) );
		wp_register_style( 'user_regAide_admin_style', plugins_url( 'user-registration-aide/css/csds_ura_only_stylesheet.css' ) );
		wp_register_style( 'user_regAide_lost_pass_style', plugins_url( 'user-registration-aide/css/regist_login_stylesheet.css' ) );
		wp_register_style( 'templates_style', plugins_url( 'user-registration-aide/css/templates-style.css' ) );
		wp_register_script( 'xwrd2_check', $xwrd_check, false );
	}
	
	/** Enqueues CSS stylesheet for settings menu on all plugins admin page
	 * 
	 * @since 1.3.0
	 * @updated 1.5.2.0
	 * @handles action 'admin_print_styles' line 229 &$this
	 * @access private
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	 */
	
	function add_settings_css(){
		wp_enqueue_style( 'user_regAide_menu_style', plugins_url( 'user-registration-aide/css/wp-admin-menu-stylesheet.css' ) );
	}
	
	/**	
	 * Function add_admin_settings_css
	 * Enqueues CSS stylesheet for my menu settings on my pages
	 * @since 1.3.0
	 * @updated 1.5.2.0
	 * @access public
	 * @handles action 'admin_print_styles' line 140, 144, 148, 152 &$this
	 * @params 
	 * @returns 
	 */
	
	function add_admin_settings_css(){
		wp_enqueue_style( 'user_regAide_admin_style', plugins_url( 'user-registration-aide/css/csds_ura_only_stylesheet.css' ) );
		wp_enqueue_script( 'xwrd2_check' );
	}
	
	/**	
	 * Function add_lostpassword_css
	 * Enqueues CSS stylesheet for lost password form
	 * @since 1.3.0
	 * @updated 1.5.2.0
	 * @access public
	 * @handles action 'login_enqueue_scripts' line 191, 193, 197, 201 &$this
	 * @params 
	 * @returns 
	 */
	
	function add_lostpassword_css(){
		$options = get_option( 'csds_userRegAide_Options' );
		if( $options['add_security_question'] == "1" ){
			wp_enqueue_style( 'user_regAide_lost_pass_style', plugins_url( 'user-registration-aide/css/regist_login_stylesheet.css' ) );
		}
	}
	
	/** 
	 * function enqueueXwrdStyles
	 * Enqueues the stylesheet for the password change page
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns
	 */ 
	
	function enqueueXwrdStyles(){
		$request = $_SERVER['REQUEST_URI'];
		
		if( $request == '/reset-password/' ){
			wp_enqueue_style( 'user_regAide_style' );
		}
	}
	
	/** 
	 * function csds_userRegAide_enqueueMyStyles
	 * Enqueues the stylesheet for the plugin
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns
	 */ 
	
	function csds_userRegAide_enqueueMyStyles(){
		wp_enqueue_style( 'user_regAide_style' );
		
	}
	
	/** 
	 * function register_password_scripts
	 * Registers the scripts for the Word Press default password strength meter
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	 */ 
	
	function register_password_scripts(){
		$jq_color = URA_JS_PATH.'jquery.color.js';
		$jq_color_min = includes_url().'js/jquery/jquery.color.min.js';
		$xwrd_min = admin_url().'js/password_strength_meter.min.js';
		$css = URA_CSS_PATH.'templates-style.css';
		wp_register_style( 'templates_style', $css, false );
		wp_register_script( 'password-strength-meter.min', $xwrd_min, false );
		wp_register_script( 'jquery_color', $jq_color, false );
		wp_register_script( 'jquery_color_min', $jq_color_min, false );
	}
	
	/** 
	 * function load_password_scripts
	 * Enqueues the scripts for the Word Press default password strength meter
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	 */ 
	
	function load_password_scripts(){
		wp_enqueue_style( 'templates_style' );
		wp_enqueue_script( 'jquery_color' );
		wp_enqueue_script( 'jquery_color_min' );
		wp_enqueue_script( 'user-profile' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'utils' );
		wp_enqueue_script( 'password-strength-meter' );
		wp_enqueue_script( 'password-strength-meter.min' );
	}
	
	/** 
	 * function my_style_color_function
	 * Enqueues color picker for stylesheet settings page custom stylesheet settings
	 * @since 1.4.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns
	 */ 
	
	function my_style_color_function(){
		$url = URA_JS_PATH.'ura-screen-options.js';
		wp_enqueue_script('screen-options-custom-autosave', $url, array( 'jquery' ) );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		
	}
	
}