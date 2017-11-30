<?php

/**
 * Class  REGISTRATION_FORM_OPTIONS_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class REGISTRATION_FORM_OPTIONS_MODEL
{
	
	/**	
	 * function reg_form_message_update
	 * Handles updates for registration form message options view
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg ( results of function success or failure ) 
	*/
	
	function reg_form_message_update( $msg ){
		//$msg = ( string ) '';
		if( isset( $_POST['reg_form_message_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				// add code to handle new registration form message
				$update = array();
				$ucnt = (int) 0;
				$update = get_option('csds_userRegAide_Options');
				if( !empty( $_POST['csds_select_RegFormMessage'] ) ){
					$update['select_pass_message'] = esc_textarea( $_POST['csds_select_RegFormMessage'] );
				}else{
					$ucnt ++;
				}
				if( !empty( $_POST['csds_RegForm_Message'] ) ){
					$update['registration_form_message'] = esc_textarea( $_POST['csds_RegForm_Message'] );
				}else{
					$ucnt ++;
				}
				if( $ucnt == 0 ){
					update_option( "csds_userRegAide_Options", $update );
					$msg = '<div id="message" class="updated"><p>'. __( 'New Bottom Registration Form Message Options updated successfully.', 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
				}else{
					$msg = '<div id="message" class="error"><p>'. __( 'New Bottom Registration Form Message Options empty, not updated successfully.', 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
				}
			}
		}
		return $msg;
	}
	
	/**	
	 * function reg_form_redirects
	 * Handles updates for registration form redirects
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg ( results of function success or failure ) 
	*/
	
	function reg_form_redirects( $msg ){
		//$msg = ( string ) '';
		if( isset( $_POST['redirects_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$update = array();
				$ucnt = (int) 0;
				$field = (string) '';
				$update = get_option( 'csds_userRegAide_Options' );
				if( !empty( $_POST['csds_registration_redirect_option'] ) ){
					$update['redirect_registration'] = sanitize_text_field( $_POST['csds_registration_redirect_option'] );
				}else{
					$ucnt ++;
					$field = __( ' Registration Redirect URL Option ', 'user-registration-aide' );
				}
				if( !empty( $_POST['csds_registration_redirect_url'] ) ){
					$update['registration_redirect_url'] = esc_url_raw( trim( $_POST['csds_registration_redirect_url'] ) );
				}else{
					$ucnt ++;
					$field .= __( ' Registration Redirect URL ', 'user-registration-aide' );
				}
				if( !empty( $_POST['csds_login_redirect_option'] ) ){
					$update['redirect_login'] = sanitize_text_field( $_POST['csds_login_redirect_option'] );
				}else{
					$ucnt ++;
					$field .= __( ' Login Redirect URL Option ', 'user-registration-aide' );
				}
				if( !empty( $_POST['csds_login_redirect_url'] ) ){
					$update['login_redirect_url'] = esc_url_raw( trim( $_POST['csds_login_redirect_url'] ) );
				}else{
					$ucnt ++;
					$field .= __( ' Login Redirect URL ', 'user-registration-aide' );
				}
				update_option( 'csds_userRegAide_Options', $update );
				if( $ucnt == 0 ){
					$msg = '<div id="message" class="updated"><p>'. __( 'New Login & Registration Form Custom Redirects Options updated successfully.', 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
				}else{
					$msg = '<div id="message" class="error"><p>'. sprintf( __( 'New Login & Registration Form Custom Redirects Options Field %s Empty!', 'user-registration-aide' ), $field ) .'</p></div>'; //Report to the user that the data has been updated successfully
				}
			}
		}
		return $msg;
	}
	
	/**	
	 * function reg_form_agree
	 * Handles updates for registration form agreement message
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg ( results of function success or failure ) 
	*/
	
	function reg_form_agree( $msg ){
		//$msg = ( string ) '';
		if( isset( $_POST['reg_form_agreement_message_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$update = array();
				$ucnt = (int) 0;
				$field = (string) '';
				$update = get_option( 'csds_userRegAide_Options' );
				if( !empty( $_POST['csds_userRegAide_agreement_link'] ) ){
					$update['show_custom_agreement_link'] = sanitize_text_field( $_POST['csds_userRegAide_agreement_link'] );
				}else{
					$ucnt ++;
					$field = __( ' Show User Custom Agreement Link Option ', 'user-registration-aide' );
				}
				if( !empty( $_POST['csds_userRegAide_newAgreementURL'] ) ){
					$update['agreement_link'] = esc_url_raw( trim( $_POST['csds_userRegAide_newAgreementURL'] ) );
				}else{
					$ucnt ++;
					$field .= __( ' Custom Agreement Link ', 'user-registration-aide' );
				}
				if( !empty( $_POST['csds_userRegAide_newAgreementTitle'] ) ){
					$update['agreement_title'] = sanitize_text_field( $_POST['csds_userRegAide_newAgreementTitle'] );
				}else{
					$ucnt ++;
					$field .= __( ' Custom Agreement Title ', 'user-registration-aide' );
				}
				if( !empty( $_POST['csds_userRegAide_show_agreement_message'] ) ){
					$update['show_custom_agreement_message'] = sanitize_text_field( $_POST['csds_userRegAide_show_agreement_message'] );
				}else{
					$ucnt ++;
					$field .= __( ' Show Custom Agreement Message Confirmation Agreement Option ', 'user-registration-aide' );
				}
				if( !empty( $_POST['csds_userRegAide_agreement_checkbox'] ) ){
					$update['show_custom_agreement_checkbox'] = sanitize_text_field( $_POST['csds_userRegAide_agreement_checkbox'] );
				}else{
					$ucnt ++;
					$field .= __( ' Show Custom Agreement Checkbox Option ', 'user-registration-aide' );
				}
				if( !empty( $_POST['csds_RegForm_Agreement_Message'] ) ){
					$update['agreement_message'] = sanitize_text_field( $_POST['csds_RegForm_Agreement_Message'] );
				}else{
					$ucnt ++;
					$field .= __( ' Custom Agreement Message ', 'user-registration-aide' );
				}
				update_option( 'csds_userRegAide_Options', $update );
				if( $ucnt == 0 ){
					$msg = '<div id="message" class="updated"><p>'. __( 'New Registration Form Agreement Message Options updated successfully.', 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
				}else{
					$msg = '<div id="message" class="error"><p>'. sprintf( __( 'New Registration Form Agreement Message Options Fields %s Empty!', 'user-registration-aide' ), $field ) .'</p></div>'; //Report to the user that the data has been updated successfully
				}
			}
		}
		return $msg;
	}
	
	/**	
	 * function reg_form_anti_spam_math
	 * Handles updates for registration form anti spam math problem
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg ( results of function success or failure ) 
	*/
	
	function reg_form_anti_spam_math( $msg ){
		//$msg = ( string ) '';
		if( isset( $_POST['anti-bot-spammer'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$update = array();
				$mcnt = (int) 0;
				$errors = (string) '';
				$update = get_option( 'csds_userRegAide_Options' );
				$update['activate_anti_spam'] = sanitize_text_field( $_POST['csds_select_AntiBot'] );
				if( $_POST['csds_select_AntiBot'] == 1 ){
					if( $_POST['csds_div_AntiBot'] == 2 ){
						$mcnt ++;
					}
					if( $_POST['csds_multiply_AntiBot'] == 2 ){
						$mcnt ++;
					}
					if( $_POST['csds_minus_AntiBot'] == 2 ){
						$mcnt ++;
					}
					if( $_POST['csds_add_AntiBot'] == 2 ){
						$mcnt ++;
					}
					
					if( $mcnt != 4 ){
						$update['division_anti_spam'] = sanitize_text_field( $_POST['csds_div_AntiBot'] );
						$update['multiply_anti_spam'] = sanitize_text_field( $_POST['csds_multiply_AntiBot'] );
						$update['minus_anti_spam'] = sanitize_text_field( $_POST['csds_minus_AntiBot'] );
						$update['addition_anti_spam'] = sanitize_text_field( $_POST['csds_add_AntiBot'] );
					}elseif( $mcnt == 4 ){
						$errors = __( "You have not selected any operators (+. -, /, *) to use and selected to use the anti-spam math problem! Please try again and select at least one operator of select no to use the anti-spam math problem", 'user-registration-aide' );
					}
				}elseif( $_POST['csds_select_AntiBot'] == 2 ){
					$update['division_anti_spam'] = sanitize_text_field( $_POST['csds_div_AntiBot'] );
					$update['multiply_anti_spam'] = sanitize_text_field( $_POST['csds_multiply_AntiBot'] );
					$update['minus_anti_spam'] = sanitize_text_field( $_POST['csds_minus_AntiBot'] );
					$update['addition_anti_spam'] = sanitize_text_field( $_POST['csds_add_AntiBot'] );
				}
				
				if( empty( $errors ) || $errors == '' ){
					$msg = '<div id="message" class="updated"><p>'. __( 'Anti-Bot-Spammer Math Problem Options updated successfully.', 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
					update_option( "csds_userRegAide_Options", $update);
				}else{
					$msg = '<div id="message" class="error"><p>'. __( "You have not selected any operators (+. -, /, *) to use and selected to use the anti-spam math problem! Please try again and select at least one operator of select no to use the anti-spam math problem", 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
					
				}
			}
		}
		return $msg;
	}
	
	/**	
	 * function profile_title
	 * Handles updates for user profile plugin title
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg ( results of function success or failure ) 
	*/
	
	function profile_title( $msg ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$field = ( string ) '';
			if( isset( $_POST['update_profile_title'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
					$update = array();
					$ucnt = (int) 0;
					$update = get_option('csds_userRegAide_Options');
					if( !empty( $_POST['csds_change_profile_title'] ) ){
						$update['change_profile_title'] = sanitize_text_field( $_POST['csds_change_profile_title'] );
					}else{
						$ucnt ++;
						$field .= __( ' Change Profile Plugin Title Option ', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_profile_title'] ) ){
						$update['profile_title'] = sanitize_text_field( $_POST['csds_profile_title'] );
					}else{
						$ucnt ++;
						$field .= __( ' Profile Plugin Title Option ', 'user-registration-aide' );
					}
					
					if( $ucnt == 0 ){
						$msg = '<div id="message" class="updated"><p>'. __( 'New Profile Page Extra Fields Title Updated Successfully.', 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
						update_option( "csds_userRegAide_Options", $update );
					}else{
						$msg = '<div id="message" class="error"><p>'. __( 'New Profile Page Extra Fields Title Empty!', 'user-registration-aide' ).'</p></div>'; //Report to the user that the data has been updated successfully
					}
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
			}
		}
			return $msg;
	}
	
}