<?php

/**
 * Class URA_NUA_SETTINGS_MODEL
	*
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
 */

class URA_NUA_SETTINGS_MODEL
{
	
	/**	
	 * Function new_user_approval_settings_view
	 * URA settings for new user approval
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params array $options options settings for plugin page
	 * @returns 
	 */
	
	function new_user_approval_settings_model( $msg ){
		global $current_user;
		$plugin = 'buddypress/bp-loader.php';
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$options = get_option('csds_userRegAide_Options');
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				if( isset( $_POST['newUserApprove_submit'] ) ){
					$update = array();
					$ucnt = (int) 0;
					$options = get_option('csds_userRegAide_Options');
					if( !empty( $_POST['csds_newUserApprove'] ) ){
						$options['new_user_approve'] = esc_attr( stripslashes( $_POST['csds_newUserApprove'] ) );
					}else{
						$ucnt ++;
					}
					
					// for buddypress approval
					
					if( !empty( $_POST['csds_emailVerify'] ) ){
						$options['verify_email'] = esc_attr( stripslashes( $_POST['csds_emailVerify'] ) );
						if( $_POST['csds_emailVerify'] == 1 ){
							do_action( 'setup_custom_pages' );
						}
					}else{
						$ucnt ++;
					}
					
					if( is_plugin_active( $plugin ) ){
						if( !empty( $_POST['csds_bpRegister'] ) ){
							$bp_register = esc_attr( stripslashes( $_POST['csds_bpRegister'] ) );
							$options['buddy_press_registration'] = $bp_register;
							$bpf = new URA_BP_FUNCTIONS();
							$page_ids = $bpf->bp_register_form( $bp_register );
						}else{
							$ucnt ++;
						}
					}
					
					if( $ucnt == 0 ){
						update_option( 'csds_userRegAide_Options', $options);
						$msg = '<div id="message" class="updated fade"><p class="message">'. __( 'New User Approval Options updated successfully.', 'user-registration-aide' ) .'</p></div>'; 
						return $msg;
					}else{
						$msg = '<div id="message" class="error"><p class="message">'. __( 'New User Approval Options empty, not updated successfully.', 'user-registration-aide' ) .'</p></div>'; 
						return $msg;
					}
				// syncs buddypress to wordpress profiles
				}
				if( is_plugin_active( $plugin ) ){
					if( isset( $_POST['sync_profiles_submit'] ) ){
						do_action( 'sync_bp_xprofile_fields' );
						$msg = '<div id="message" class="updated fade"><p class="message">'. __( 'BuddyPress To WordPress Profiles Sync Done!', 'user-registration-aide' ) .'</p></div>'; //Reports profiles synced
						return $msg;
					}elseif( isset( $_POST['sync_wp_profiles_submit'] ) ){
						do_action( 'sync_wp_user_fields' );
						$msg = '<div id="message" class="updated fade"><p class="message">'. __( 'WordPress To BuddyPress Profiles Sync Done!', 'user-registration-aide' ) .'</p></div>'; //Reports profiles synced
						return $msg;
					}elseif( isset( $_POST['signup_transfer'] ) ){
						do_action( 'sync_signups' );
					}
				}
			}else{
				wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
			}
		}
	}
	
}