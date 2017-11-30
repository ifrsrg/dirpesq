<?php

/**
 * Class XWRD_STRENGTH_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class XWRD_STRENGTH_MODEL
{
	
	/**	
	 * function xwrd_strength_options_update
	 * updates password strength options and returns string $msg of results
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg ( results of function updated or error message to user )
	*/
	
	function xwrd_strength_options_update( $msg ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$options = get_option('csds_userRegAide_Options');
			if( isset( $_POST['psr_update'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
					$update = array();
					$ucnt = (int) 0;
					$field = (string) '';
					$update = get_option('csds_userRegAide_Options');
				//	Updates default password strength requirements
					if( isset( $_POST['csds_select_DefaultPSR'] ) && isset( $_POST['csds_select_CustomPSR'] ) ){ 
						if( $_POST['csds_select_DefaultPSR'] == 1 && $_POST['csds_select_CustomPSR'] == 2 ){
							$update['default_xwrd_strength'] = 1;
							$update['require_xwrd_length'] = 1;
							$update['xwrd_length'] = 8;
							$update['xwrd_uc'] = 1;
							$update['xwrd_lc'] = 1;
							$update['xwrd_numb'] = 1;
							$update['xwrd_sc'] = 1;
							$update['custom_xwrd_strength'] = 2;
						
						}elseif( $_POST['csds_select_CustomPSR'] == 1 && $_POST['csds_select_DefaultPSR'] == 1 ){
							if( $options['custom_xwrd_strength'] == 2 ){
								$update['default_xwrd_strength'] = 2;
								if( !empty( $_POST['csds_select_CustomPSR'] ) ){ // Updates custom password strength requirements
									$update['custom_xwrd_strength'] = 1;
									$update['default_xwrd_strength'] = 2;
								}else{
									$ucnt ++;
									$field .= __( ' Custom Password Strength Requirement Option ', 'user-registration-aide' );
								}
							
								if( !empty( $_POST['csds_select_MinXwrdLngth'] ) ){ // Updates minimum password length
									$update['require_xwrd_length'] = sanitize_text_field( $_POST['csds_select_MinXwrdLngth'] );
								}else{
									$ucnt ++;
									$field .= __( ' Minimum Password Length Requirement Option ', 'user-registration-aide' );
								}
								
								if( !empty($_POST['csds_xwrdLength'] ) ){ // Updates minimum password length
									$update['xwrd_length'] = sanitize_text_field( $_POST['csds_xwrdLength'] );
								}else{
									$ucnt ++;
									$field .= __( ' Minimum Password Length Requirement Option ', 'user-registration-aide' );
								}
								
								if( !empty( $_POST['csds_select_UCPSR'] ) ){ // Updates password strength requirement upper case letter
									$update['xwrd_uc'] = sanitize_text_field( $_POST['csds_select_UCPSR'] );
								}else{
									$ucnt ++;
									$field .= __( ' Password Strength Requirement Option Upper Case Letter ', 'user-registration-aide' );
								}
								
								if( !empty( $_POST['csds_select_LCPSR'] ) ){ // Updates password strength requirement lower case letter
									$update['xwrd_lc'] = sanitize_text_field( $_POST['csds_select_LCPSR'] );
								}else{
									$ucnt ++;
									$field .= __( ' Password Strength Requirement Option Lower Case Letter ', 'user-registration-aide' );
								}
								
								if( !empty( $_POST['csds_select_NumbPSR'] ) ){ // Updates password strength requirement number
									$update['xwrd_numb'] = sanitize_text_field( $_POST['csds_select_NumbPSR'] );
								}else{
									$ucnt ++;
									$field .= __( ' Password Strength Requirement Option Number ', 'user-registration-aide' );
								}
								
								if( !empty( $_POST['csds_select_SCPSR'] ) ){ // Updates password strength requirement special character
									$update['xwrd_sc'] = sanitize_text_field( $_POST['csds_select_SCPSR'] );
								}else{
									$ucnt ++;
									$field .= __( ' Password Strength Requirement Option Special Character ', 'user-registration-aide' );
								}
							}elseif( $options['default_xwrd_strength'] == 2 ){
								$update['default_xwrd_strength'] = 1;
								$update['require_xwrd_length'] = 1;
								$update['xwrd_length'] = 8;
								$update['xwrd_uc'] = 1;
								$update['xwrd_lc'] = 1;
								$update['xwrd_numb'] = 1;
								$update['xwrd_sc'] = 1;
								$update['custom_xwrd_strength'] = 2;
							}
						}elseif( $_POST['csds_select_DefaultPSR'] == 2 && $_POST['csds_select_CustomPSR'] == 1 ){
							$update['custom_xwrd_strength'] = 1;
							$update['default_xwrd_strength'] = 2;
							if( !empty( $_POST['csds_select_MinXwrdLngth'] ) ){ // Updates minimum password length
								$update['require_xwrd_length'] = sanitize_text_field( $_POST['csds_select_MinXwrdLngth'] );
							}else{
								$ucnt ++;
								$field .= __( ' Minimum Password Length Requirement Option ', 'user-registration-aide' );
							}
							
							if( !empty($_POST['csds_xwrdLength'] ) ){ // Updates minimum password length
								$update['xwrd_length'] = sanitize_text_field( $_POST['csds_xwrdLength'] );
							}else{
								$ucnt ++;
								$field .= __( ' Minimum Password Length Requirement Option ', 'user-registration-aide' );
							}
							
							if( !empty( $_POST['csds_select_UCPSR'] ) ){ // Updates password strength requirement upper case letter
								$update['xwrd_uc'] = sanitize_text_field( $_POST['csds_select_UCPSR'] );
							}else{
								$ucnt ++;
								$field .= __( ' Password Strength Requirement Option Upper Case Letter ', 'user-registration-aide' );
							}
							
							if( !empty( $_POST['csds_select_LCPSR'] ) ){ // Updates password strength requirement lower case letter
								$update['xwrd_lc'] = sanitize_text_field( $_POST['csds_select_LCPSR'] );
							}else{
								$ucnt ++;
								$field .= __( ' Password Strength Requirement Option Lower Case Letter', 'user-registration-aide' );
							}
							
							if( !empty( $_POST['csds_select_NumbPSR'] ) ){ // Updates password strength requirement number
								$update['xwrd_numb'] = sanitize_text_field( $_POST['csds_select_NumbPSR'] );
							}else{
								$ucnt ++;
								$field .= __( ' Password Strength Requirement Option Number ', 'user-registration-aide' );
							}
							
							if( !empty( $_POST['csds_select_SCPSR'] ) ){ // Updates password strength requirement special character
								$update['xwrd_sc'] = sanitize_text_field( $_POST['csds_select_SCPSR'] );
							}else{
								$ucnt ++;
								$field .= __( ' Password Strength Requirement Option Special Character ', 'user-registration-aide' );
							}
							
						}elseif( $_POST['csds_select_CustomPSR'] == 2 && $_POST['csds_select_DefaultPSR'] == 2 ){
							$update['default_xwrd_strength'] = 2;
							$update['require_xwrd_length'] = 1;
							$update['xwrd_length'] = 8;
							$update['xwrd_uc'] = 1;
							$update['xwrd_lc'] = 1;
							$update['xwrd_numb'] = 1;
							$update['xwrd_sc'] = 1;
							$update['custom_xwrd_strength'] = 2;
						}
					}else{
						$ucnt ++;
						$field = __( ' Default Password Strength Requirement Option ', 'user-registration-aide' );
					}
					
					if( $ucnt == 0 ){
						update_option( 'csds_userRegAide_Options', $update );
						$msg = '<div id="message" class="updated"><p>'. __( 'New Password Strength Requirement Options updated successfully.', 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
					}else{
						$ucnt ++;
						$msg = '<div id="message" class="error"><p>'. __( 'Show Password Fields on Profile/User Edit Page Option empty, not updated successfully.', 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
					}
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
			}
		}
		return $msg;
	}
	
	/**	
	 * function xwrd_strength_options_update
	 * Handles checking password strength for various forms that use this feature
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params OBJECT $errors, string $xwrd, string $xwrd2, string $login, string $email
	 * @returns OBJECT $errors
	*/
			
	function check_reg_form_xwrd_strength( $errors, $xwrd, $xwrd2, $login, $email ){
		$error = ( int ) 0;
		$options = get_option( 'csds_userRegAide_Options' );
		// //to check password -- password fields empty
		if( empty( $xwrd ) ){
				$errors->add( 'empty_password', __( "<strong>ERROR</strong>: Please enter and confirm your Password!", 'user-registration-aide' ) );
				$error ++;
				
		}
		if( empty( $xwrd2) ){
				$errors->add( 'empty_confirm_password', __( "<strong>ERROR</strong>: Please Confirm your Password!", 'user-registration-aide' ) );
				$error ++;
		}
		if( $xwrd != $xwrd2 ){
			$errors->add( 'password_mismatch', __( "<strong>ERROR</strong>: Passwords do Not Match!", 'user-registration-aide' ) );
			$error ++;
		}
		if( $xwrd == $login ){ // password same as user login
			$errors->add( 'password_and_login_match', __( "<strong>ERROR</strong>: Username and Password are the same, they must be different!", 'user-registration-aide' ) );
				$error ++;
		// Password strength requirements 	
		}
		if( strlen( trim( $xwrd ) ) < $options['xwrd_length'] ){ // password length too short
			if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['require_xwrd_length'] == 1 ) ){
				$errors->add( 'password_too_short', sprintf( __( "<strong>ERROR</strong>: Password length too short! Should be at least %d characters long!", 'user-registration-aide' ), $options['xwrd_length'] ) );
				$error ++;
			}
		// no number in password
		}
		if( isset( $xwrd ) && !preg_match("/[0-9]/", $xwrd ) ){
			if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['xwrd_numb'] == 1 ) ){
				$errors->add( 'password_missing_number', __( "<strong>ERROR</strong>: There is no number in your password!", 'user-registration-aide' ) );
					$error ++;
			}
		// no lower case letter in password
		}
		if( isset( $xwrd ) && !preg_match("/[a-z]/", $xwrd) ){
			if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['xwrd_lc'] == 1 ) ){
				$errors->add( 'password_missing_lower_case_letter', __( "<strong>ERROR</strong>: Password missing lower case letter!", 'user-registration-aide' ) );
					$error ++;
			}
		// no upper case letter in password
		}
		if( isset( $xwrd ) && !preg_match("/[A-Z]/", $xwrd ) ){
			if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['xwrd_uc'] == 1 ) ){
				$errors->add( 'password_missing_upper_case_letter', __( "<strong>ERROR</strong>: Password missing upper case letter!", 'user-registration-aide' ) );
					$error ++;
			}
		// no special character in password
		}
		if( isset( $xwrd ) && !preg_match("/.[!,@,#,$,%,^,&,*,?,_,~,-,£,(,)]/", $xwrd ) ){
			if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['xwrd_sc'] == 1 ) ){
				$errors->add( 'password_missing_symbol', __( "<strong>ERROR</strong>: Password missing symbol!", 'user-registration-aide' ) );
					$error ++;
			}
		}else{
			//exit('Blow Up');//$_POST['user_pw'] = $_POST['pass1'];
		}
		return $errors;
	}
}