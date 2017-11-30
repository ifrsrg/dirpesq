<?php

// filter to modify front end menu and remove our custom template pages
//wp_die( 'HELLO' );
//add_filter( 'wp_nav_menu_objects', 'ura_menu_exclude_pages', 10, 2 );
add_filter( 'get_pages', 'ura_menu_exclude_custom_pages', 10, 1 );
add_action( 'register_new_user', 'remove_default_password_nag', 10, 1 );

// authenticate so other plugins can't override my new user approve/email verify operations
add_filter( 'authenticate', 'ura_authenticate', 10, 1 );
add_filter( 'ura_authenticate', 'ura_authenticate_verify', 10, 1 );

/**
 * function ura_menu_exclude_custom_pages
 * Removes custom page templates from front end menus so user doesn't have to
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params array $pages List of pages to retrieve.
 * @returns array $pages The page menu items, with ura custom template pages removed
*/

function ura_menu_exclude_custom_pages( $pages ){
	$tc = new URA_TEMPLATE_CONTROLLER();
	$ids = $tc->page_ids();
	$page_length = count( $pages );
	for ( $i = 0; $i < $page_length; $i++ ) {
		$page = & $pages[$i];
		if ( in_array( $page->ID, $ids ) ) {
			unset( $pages[$i] );
		}
	}
	return $pages;
}

/**
 * function ura_menu_exclude_pages
 * Removes custom page templates from front end menus so user doesn't have to
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params array $items The menu items, sorted by each menu item's menu order.
 * @params object $args An object containing wp_nav_menu() arguments.
 * @returns array $items The menu items, with ura custom templates removed
*/

function ura_menu_exclude_pages( $items, $args ){
	$tc = new URA_TEMPLATE_CONTROLLER();
	$ids = $tc->get_custom_templates_menu_ids();
	foreach( $items as $i => $object ){
		if( in_array( $object->ID, $ids ) ){
			unset( $items[$i] );
		}
	}
	return $items;
}

/**	
 * Function remove_default_password_nag
 * Remove default password message if user entered own password
 * @since 1.0.0
 * @updated 1.5.3.0
 * @access public
 * @params int    $user_id ID of the newly created user.
 * @params string $notify  Type of notification that should happen. See {@see wp_send_new_user_notifications()}
 *                        for more information on possible values.
 * @returns int $user_id
 */

function remove_default_password_nag( $user_id ) {
	global $wpdb;
	$reg_fields = get_option( 'csds_userRegAide_registrationFields' );
	if( empty( $reg_fields ) ){
		return $user_id;
	}else{
		$table = $wpdb->prefix . "usermeta";
		$data = array(
			'meta_value'	=>	0
		);
		$where = array(
			'user_id'			=>	$user_id,
			'meta_key'			=>	'default_password_nag'
		);
	
		if( array_key_exists( 'user_pass', $reg_fields ) ){
			$delete = $wpdb->delete( $table, $where );
		}
	}
	return $user_id;
}	

add_action( 'init', 'ura_update_template_slugs' );

/** 
 * function ura_update_template_slugs
 * updates template slugs to help ensure no duplicates and resulting issues
 * @since 1.5.3.2
 * @updated 1.5.3.2
 * @access public
 * @params 
 * @returns 
*/

function ura_update_template_slugs(){
	global $wpdb;
	$options = get_option( 'csds_userRegAide_Options' );
	$updated = ( int ) 0;
	if( !array_key_exists( 'template_slug_change', $options ) || $options['template_slug_change'] == 2 ){
		$templates = ura_old_template_array();
		$url = get_site_url();
		$table = $wpdb->prefix . 'posts';
		foreach( $templates as $key	=> $title ){
			$data = array(
				'post_name'		=>	'ura-'.$key,
				'guid'			=>	$url.'/'.'ura-'.$key.'/'
			);
			$where = array(
				'post_name'			=>	$key,
				'ura_post_type'		=>	'ura-page'
			);
			$results = $wpdb->update( $table, $data, $where );
			if( $results === false ){
				$updated++;
			}elseif( $results === 0 ){
				$updated++;
			}
		}
		if( $updated >= 1 ){
			$tc = new URA_TEMPLATE_CONTROLLER();
			$tc->csds_pages_setup();	
		}
		$options['template_slug_change'] = 1;
		update_option( 'csds_userRegAide_Options', $options );
	}
	
}

/**
 * function ura_old_template_array
 * Returns array of old custom URA template slugs-titles for updating to new slugs
 * @category function
 * @since 1.5.3.2
 * @updated 1.5.3.2
 * @access public
 * @params
 * @returns array $templates prior custom URA page templates
*/

function ura_old_template_array(){
	$templates = array(
		'account-locked-out'								=>	'Account Locked Out',
		'email-confirm'										=>	'Email Confirm',
		'update-password'									=>	'Update Password',
		'lost-password'										=>	'Lost Password',
		'reset-password'									=>	'Reset Password'
	);
	return $templates;
}

// for next update updated 1.5.3.7
if ( !function_exists('wp_authenticate') ):
	/**
	 * Checks a user's login information and logs them in if it checks out.
	 *
	 * @since 2.5.0
	 *
	 * @param string $username User's username
	 * @param string $password User's password
	 * @return WP_User|WP_Error WP_User object if login successful, otherwise WP_Error object.
	*/

	function wp_authenticate( $username, $password )  {
		global $wpdb, $error;
		//wp_die( 'MY AUTHENTICATE' );
		$username = sanitize_user( $username );
		$password = trim( $password );
		$options = get_option( 'csds_userRegAide_Options' );
		//exit( 'MY AUTHENTICATE' );
		
		/**
		 * Filter the user to authenticate.
			*
		 * If a non-null value is passed, the filter will effectively short-circuit
		 * authentication, returning an error instead.
			*
		 * @since 2.8.0
			*
		 * @param null|WP_User $user     User to authenticate.
		 * @param string       $username User login.
		 * @param string       $password User password
		*/
		
		$user = apply_filters( 'authenticate', null, $username, $password );
		
		// ura authentication here
		$user = apply_filters( 'ura_authenticate', $user );
		
		
		if( $user == null ){
			// TODO what should the error message be? (Or would these even happen?)
			// Only needed if all authentication handlers fail to return anything.
			$user = new WP_Error( 'authentication_failed', __( '<strong>ERROR</strong>: Invalid username or incorrect password.', 'user-registration-aide' ) );
			
		}
		
		
		$ignore_codes = array( 'empty_username', 'empty_password' );
		
		if( is_wp_error( $user ) && !in_array( $user->get_error_code(), $ignore_codes ) ){
			
			/**
			 * Fires after a user login has failed.
				*
			 * @since 2.5.0
				*
			 * @param string $username User login.
			*/
			
			do_action( 'wp_login_failed', $username );
		}
		//exit( 'MY AUTHENTICATE' );
		return $user;
	}
endif;

/**
 * Checks a user's login information and logs them in if it checks out.
 *
 * @since 2.5.0
 *
 * @param string $username User's username
 * @param string $password User's password
 * @return WP_User|WP_Error WP_User object if login successful, otherwise WP_Error object.
*/

function ura_authenticate( $user )  {
	$options = get_option( 'csds_userRegAide_Options' );
	if ( is_wp_error( $user ) ) {
		// Not a WP_User - nothing to do here.
		return $user;
	}else{
		// ura authentication here
		$user = apply_filters( 'ura_authenticate', $user );
		return $user;
	}
		
}

/**
 * Checks a user's login information and logs them in if it checks out.
 *
 * @since 2.5.0
 *
 * @param string $username User's username
 * @param string $password User's password
 * @return WP_User|WP_Error WP_User object if login successful, otherwise WP_Error object.
*/

function ura_authenticate_verify( $user )  {
	// mine
	if( is_wp_error( $user ) ){
		return $user;
	}else{
		$options = get_option( 'csds_userRegAide_Options' );
		$fields = get_option( 'csds_userRegAide_registrationFields' );
		$password = ( boolean ) false;
		if( !empty( $fields ) ){
			if( array_key_exists( 'user_pass', $fields ) ){
				$password = true;
			}else{
				$password = false;
			}
		}else{
			$password = false;
		}
		$cnt = ( int ) 0;
		if( $user ){//|| $options['lockout_invalid_usernames'] == "yes" ) {
			if( $user === false ){ 
				$user_id = -1;
			}else{
				$user_id = $user->ID;
				$approve = ( int ) 0;
				$status = ( string ) '';
				$approve = $options['new_user_approve'];
				$verify = $options['verify_email'];
				if( $approve == 1 && $verify == 1 ){
					//wp_die( 'APPROVE' );
					$status = get_user_meta( $user_id, 'approval_status', true );
					$email_verify = get_user_meta( $user_id, 'email_verification', true );
					if( $status == 'pending' && $email_verify == 'unverified' ){
						$user = new WP_Error( 'pending_approval', sprintf( __( '<p align="center"><strong>ERROR</strong>: We are sorry but your user account must first be approved by an Administrator & Your Email Must Be Verified!<br /><br />Please Verify Your Email & contact an Administrator or try again later.<br/><a href="%s">Home</a></p>', 'user-registration-aide' ), site_url() ) );
						//exit( $error );
					}elseif( $status == 'pending' && $email_verify == 'verified' ){
							$user = new WP_Error( 'pending_approval', sprintf( __( '<p align="center"><strong>ERROR</strong>: We are sorry but your user account must first be approved by an Administrator!<br /><br />Please Contact an Administrator or try again later.<br/><a href="%s">Home</a></p>', 'user-registration-aide' ), site_url() ) );
					}elseif( $status == 'approved' && $email_verify == 'unverified' ){
							$user = new WP_Error( 'email_verified', sprintf( __( '<p align="center"><strong>ERROR</strong>: We are sorry but your email account must first be verified!<br /><br />Please use the email verification link to verify your email address.<br/><a href="%s">Home</a></p>', 'user-registration-aide' ), site_url() ) );
					}elseif( $status == 'denied' ){
						$user = new WP_Error( 'approval_denied', sprintf( __( '<p align="center"><strong>ERROR</strong>: We are sorry but your user account has been denied by an Administrator!<br /><br />Please contact an Administrator if you feel this is an error.<br/><a href="%s">Home</a></p>', 'user-registration-aide' ), site_url() ) );
						//exit( $user );
					}elseif( $status == 'approved' && $email_verify == 'verified' ){
						if( $password === true ){
							$xwrd = get_user_meta( $user_id, 'password_set', true );
							if( $xwrd == 2 ){
								$user = new WP_Error( 'password_not_set', sprintf( __( '<p align="center"><strong>ERROR</strong>: We are sorry but your user account password needs to be set!<br /><br />Please Click on the Set Password Link Sent to You or Contact an Administrator if you feel this is an error.<br/><a href="%s">Home</a></p>', 'user-registration-aide' ), site_url() ) );
							}
						}
					}
				}elseif( $approve == 1 && $verify == 2 ){
					//wp_die( 'APPROVE' );
					$status = get_user_meta( $user_id, 'approval_status', true );
					if( $status == 'pending' ){
						$user = new WP_Error( 'pending_approval', sprintf( __( '<p align="center"><strong>ERROR</strong>: We are sorry but your user account must first be approved by an Administrator!<br /><br />Please contact an Administrator or try again later.<br/><a href="%s">Home</a></p>', 'user-registration-aide' ), site_url() ) );
						//exit( $error );
					}elseif( $status == 'denied' ){
						$user = new WP_Error( 'approval_denied', sprintf( __( '<p align="center"><strong>ERROR</strong>: We are sorry but your user account has been denied by an Administrator!<br /><br />Please contact an Administrator if you feel this is an error.<br/><a href="%s">Home</a></p>', 'user-registration-aide' ), site_url() ) );
						//exit( $user );
					}elseif( $status == 'approved' ){
						if( $password === true ){
							$xwrd = get_user_meta( $user_id, 'password_set', true );
							if( $xwrd == 2 ){
								$user = new WP_Error( 'password_not_set', sprintf( __( '<p align="center"><strong>ERROR</strong>: We are sorry but your user account password needs to be set!<br /><br />Please Click on the Set Password Link Sent to You or Contact an Administrator if you feel this is an error.<br/><a href="%s">Home</a></p>', 'user-registration-aide' ), site_url() ) );
							}
						}
					}
				}elseif( $approve == 2 && $verify == 1 ){
					$email_verify = get_user_meta( $user_id, 'email_verification', true );
					if( $email_verify == 'unverified' ){
						$user = new WP_Error( 'email_verified', sprintf( __( '<p align="center"><strong>ERROR</strong>: We are sorry but your email account must first be verified!<br /><br />Please use the email verification link to verify your email address.<br/><a href="%s">Home</a></p>', 'user-registration-aide' ), site_url() ) );
						//exit( $error );
					}elseif( $email_verify == 'verified' ){
						if( $password === true ){
							$xwrd = get_user_meta( $user_id, 'password_set', true );
							if( $xwrd == 2 ){
								$user = new WP_Error( 'password_not_set', sprintf( __( '<p align="center"><strong>ERROR</strong>: We are sorry but your user account password needs to be set!<br /><br />Please Click on the Set Password Link Sent to You or Contact an Administrator if you feel this is an error.<br/><a href="%s">Home</a></p>', 'user-registration-aide' ), site_url() ) );
							}
						}
					}
				}elseif( $approve == 2 && $verify == 2 ){
					if( $password === true ){
						$xwrd = get_user_meta( $user_id, 'password_set', true );
						if( $xwrd == 2 ){
							$user = new WP_Error( 'password_not_set', sprintf( __( '<p align="center"><strong>ERROR</strong>: We are sorry but your user account password needs to be set!<br /><br />Please Click on the Set Password Link Sent to You or Contact an Administrator if you feel this is an error.<br/><a href="%s">Home</a></p>', 'user-registration-aide' ), site_url() ) );
						}
					}
				}
				
			}
		}
		return $user;
	}
	
}
