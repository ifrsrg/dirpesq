<?php

/**
 * Class URA_XWRD_CHANGE_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_XWRD_CHANGE_MODEL
{
	
	/** 
	 * function pwrd_change_options_update
	 * Handles updating settings options for password settings and uses action to View for settings page
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg ( results of function updated or error message to user )
	 */
	
	function pwrd_change_options_update( $msg ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$options = get_option('csds_userRegAide_Options');
			$interval = ( int ) 0;
			$change_time = ( int ) 0;
			$duplicate_time = ( int ) 0;
			$time = array();
			$dup_times = array();
			$signup = (int) 0;
			$current = (int) 0;
			$reset = (int) 0;
			$url = (string) '';
			$title = (string) '';
			if( isset( $_POST['updt_pwrd_chgd_options'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
					$signup = sanitize_text_field( $_POST['newUser_xwrdChange'] );
					$current = sanitize_text_field( $_POST['xwrd_chg_curUsers'] );
					$url = 'update-password';
					$title = 'Update Password';
					$reset = sanitize_text_field( $_POST['xwrd_reset'] );
					$show_fields = sanitize_text_field( $_POST['show_xwrd_fields'] );
					$interval = sanitize_text_field( $_POST['password_change_interval'] );
					$dup_times = sanitize_text_field( $_POST['dup_password_change_times'] );
					$ssl = sanitize_text_field( $_POST['xwrd_chg_ssl'] );
									
					$options['xwrd_change_on_signup'] = $signup;
					$options['xwrd_require_change'] = $current;
					$options['xwrd_change_interval'] = $interval;
					$options['xwrd_change_ssl'] = $ssl;
					$options['xwrd_change_name'] = $url;
					$options['xwrd_chng_title'] = $title;
					$options['allow_xwrd_reset'] = $reset;
					$options['show_password_fields'] = $show_fields;
					$options['xwrd_duplicate_times'] = $dup_times;
					update_option( "csds_userRegAide_Options", $options );
					if( $current == 1 || $signup == 1 ){
						$db_exists = apply_filters( 'xwrd_db_exists', false );
						if( empty( $db_exists ) || $db_exists === false ){
							do_action( 'install_xwrd_db' );
						}
					}
					if( $reset == 2 ){
						add_filter( 'allow_password_reset', array( &$this, 'xwrd_reset_disable' ), 10, 2 );
					}
					
					if( $show_fields == 2 ){
						add_filter( 'show_password_fields', array( &$this, 'xwrd_show_disable' ), 10, 2 );
					}
					$msg = '<div id="message" class="updated"><p>'. __( 'Password options updated successfully!', 'user-registration-aide' ) .'</p></div>';
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
			}
		}
		return $msg;
	}
	
	/**	
	 * function xwrd_show_disable
	 * Handles filter to allow password fields to be shown on user profile/edit page 
	 * Only works on non-admins Password reset will be shown to admins
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns boolean true for success false for error
	 */
	
	function xwrd_show_disable( $allow, $user ){
		$options = get_option('csds_userRegAide_Options');
		$show_fields = $options['show_password_fields'];
		if( $show_fields == 1 ){
			return true;
		}elseif( $show_fields == 2 ){
			if( !empty( $user->roles ) && is_array( $user->roles ) && $user->roles[0] == 'administrator' ){
				return true;
			}else{
				return false;
			}
			
		}
		
	}
	
	
}