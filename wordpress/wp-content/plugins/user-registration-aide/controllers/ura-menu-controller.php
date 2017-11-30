<?php

/**
 * Class URA_MENU_CONTROLLER
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_MENU_CONTROLLER
{
		
	/** 
	 * function set_admin_menus
	 * Sets admin menus for administartion settings pages
	 * @since 1.5.1.4
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function set_admin_menus(){
		// Single Site Administration Menus
		$mm = new URA_MENU_MODEL();
		$dm = new URA_DASH_MSGS();
		$options = get_option('csds_userRegAide_Options');
		$approve = $options['new_user_approve'];
		$verify = $options['verify_email'];
		
		add_action( 'admin_menu', array( &$mm, 'csds_userRegAide_optionsPage' ) ); // Line 646 &$this
		add_action( 'admin_menu', array( &$mm, 'csds_userRegAide_editNewFields_optionsPage' ) ); // Line 712 &$this
		add_action( 'admin_menu', array( &$mm, 'csds_userRegAide_regFormOptionsPage' ) ); // Line 669 &$this
		add_action( 'admin_menu', array( &$mm, 'csds_userRegAide_regFormCSSOptionsPage' ) ); // Line 690 &$this
		add_action( 'admin_menu', array( &$mm, 'csds_userRegAide_customOptionsPage' ) ); // Line 690 &$this
		//add_action( 'admin_menu', array( &$mm, 'csds_userRegAide_debugPage' ) ); // Line 690 &$this
		add_action( 'admin_menu', array( &$dm, 'my_help_setup' ) );
		unset( $options, $dm );
		if( $approve == 1 ){
			add_action( 'admin_menu', array( &$mm, 'ura_newUserApprovalList' ) );
			add_action( 'admin_menu', array( &$mm, 'ura_newUserDeleteList' ) );
			
			// views for users list table filter
			add_filter( 'views_users', 'modify_views_users', 10, 1 );
			
			
		}
		if( $verify == 1 ){
			add_action( 'admin_menu', array( &$mm, 'ura_newUserVerificationList' ) );
			add_filter( 'views_users', 'modify_views_users', 10, 1 );
		}
		
		$r_fields = get_option( 'csds_userRegAide_registrationFields' );
		if( $verify == 1 || $approve == 1){
			if( !empty( $r_fields ) && is_array( $r_fields ) ){
				if( !array_key_exists( 'user_pass', $r_fields ) ){
					add_action( 'admin_menu', array( &$mm, 'ura_newUserPasswordList' ) );
					add_filter( 'views_users', 'modify_views_users', 10, 1 );
				}
			}else{
				add_action( 'admin_menu', array( &$mm, 'ura_newUserPasswordList' ) );
				add_filter( 'views_users', 'modify_views_users', 10, 1 );
			}
		}
	
		// filter for footer removal in admin pages
		add_filter( 'admin_footer_text', array( &$mm, 'remove_admins_footer' ) );
		unset( $mm );
	}
}