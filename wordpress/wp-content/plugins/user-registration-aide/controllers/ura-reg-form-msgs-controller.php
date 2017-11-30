<?php

/**
 * Class  REG_FORM_MESSAGES_CONTROLLER
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class REG_FORM_MESSAGES_CONTROLLER
{
	
	/**	
	 * Function initiate_rf_msgs_view
	 * Handles the view and update controls for registration form messages and css page
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function initiate_rf_msgs_view(){
		global $current_user;
		$msg = ( string ) '';
		$msg1 = ( string ) '';
		$msg = $this->rf_msg_updates();
		$options = get_option('csds_userRegAide_Options');
		// Shows Aministration Page 
		$current_user = wp_get_current_user();
		if( current_user_can( 'manage_options', $current_user->ID ) ){
			$tab = 'registration_form_css_options';
			$h2 = array( 'adminPage', __( 'User Registration Aide: Custom Registration Form Options', 'user-registration-aide' ), 'user-registration-aide' );
			$span = array( 'regForm', __( 'Add Your Own Custom Message to Top and Bottom of Login & Registration Forms:', 'user-registration-aide' ), 'user-registration-aide' );
			$form = array( 'post', 'csds_userRegAide_regFormCSSMsgs' );
			$nonce = array( 'csds-regFormCSSMsgs', 'wp_nonce_csds-regFormCSSMsgs' );
			$msg1 = apply_filters( 'no_options_msg', $msg );
			//if( !empty( $msg ) || !empty( $msg1 ) ){
				//do_action( 'start_msg_wrapper',  $msg, $msg1, $tab, $form, $h2, $span, $nonce );
			//}else{
				do_action( 'start_wrapper',  $msg, $msg1, $tab, $form,  $nonce );
			//} 
			
			if( isset( $_GET['tab'] ) ){
				$minitab = $_GET['tab'];	
			}else{
				$minitab = 'msgs';
			}
			do_action( 'mini_tabs', $tab, $minitab  );
			
			if( $minitab ==  'msgs' ){
				do_action( 'rf_msg_settings_view', $options ); // handles updates to dashboard widget options line 243 user-registration-aide.php
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'css' ){
				do_action( 'rf_css_view' ); // handles updates to dashboard widget options line 243 user-registration-aide.php
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}
			
			$site_name = ( string ) get_bloginfo( 'name' );
			$size_error = ( int ) 0;
			//Form for adding different message to top of login/registration form 
		}else{
			wp_die( __( 'You do not have permissions to manage options for this plugin, sorry, please check with the site administrators to resolve this issue please!', 'user-registration-aide' ) );
		}
		//do_action( 'rf_msgs_view', $msgs );
		//do_action( 'rf_css_view' );
	}
	
	/**	
	 * Function rf_msg_updates
	 * Handles the update msgs for registration form messages and css page
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns string $msgs ( results of function updated or error message to user )
	*/
	
	function rf_msg_updates(){
		$msgs = ( string ) '';
		if( isset( $_POST['reg_form_login_message_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFormCSSMsgs'], 'csds-regFormCSSMsgs' ) ){
				$msgs = apply_filters( 'rf_msgs_update', $msgs );
				if( !empty( $msgs ) ){
					return $msgs;
				}
			}
		}elseif( isset( $_POST['csds_userRegAide_logo_update'] ) ){ 
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFormCSSMsgs'], 'csds-regFormCSSMsgs' ) ){
				$msgs = apply_filters( 'rf_css_update', $msgs );
				if( !empty( $msgs ) ){
					return $msgs;
				}
			}
		}elseif( isset( $_POST['csds_userRegAide_support_submit'] ) ){ // Handles showing support for plugin
			$nonce = 'wp_nonce_csds-regFormCSSMsgs';
			$nonce1 = 'csds-regFormCSSMsgs';
			$msgs = apply_filters( 'ura_support_update', $msgs, $nonce, $nonce1 );
			return $msgs;
		}else{
			return $msgs;
		} 
	}
	
}