<?php

/**
 * Class  URA_SUPPORT_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_SUPPORT_MODEL
{
	
	/**	
	 * function ura_support_update_filter
	 * Handles the updatesfor the support section in the admin settings pages
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg, string $nonce, $string nonce1
	 * @returns string $msg
	*/
	
	function ura_support_update_filter( $msg, $nonce, $nonce1 ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			if( isset( $_POST['csds_userRegAide_support_submit'] ) ){ // Handles showing support for plugin
				//if( wp_verify_nonce( $_POST['wp_nonce_csds-regFormCSSMsgs'], 'csds-regFormCSSMsgs' ) ){
				if( wp_verify_nonce( $_POST[$nonce], $nonce1 ) ){
					$update = array();
					$update = get_option('csds_userRegAide_Options');
					$update['show_support'] = sanitize_text_field( $_POST['csds_userRegAide_support'] );
					update_option( "csds_userRegAide_Options", $update );
					$msg = '<div id="message" class="updated"><p>'. __( 'Support Options Updated Successfully!', 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
					return $msg;
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
			}
		}
		return $msg;
	}
}