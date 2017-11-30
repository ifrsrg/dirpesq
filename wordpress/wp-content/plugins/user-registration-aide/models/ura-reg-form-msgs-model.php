<?php

/**
 * Class  REGISTRATION_FORM_MSGS_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class REGISTRATION_FORM_MSGS_MODEL
{
	
	/**	
	 * Function reg_form_msgs_updater
	 * Handles the update msgs for registration form messages and css page
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg 
	 * @returns string $msg ( results of function updated or error message to user )
	*/
	
	function reg_form_msgs_updater( $msg ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			if( isset( $_POST['reg_form_login_message_update'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-regFormCSSMsgs'], 'csds-regFormCSSMsgs' ) ){
					$update = array();
					$efields = array();
					$efield = ( string ) '';
					$msg = ( string ) '';
					$ecnt = ( int ) 0;
					$update = get_option('csds_userRegAide_Options');
					if( !empty( $_POST['csds_select_RegFormLoginMessage'] ) ){
						$update['show_login_message'] = sanitize_text_field( $_POST['csds_select_RegFormLoginMessage'] );
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Show Login Message', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_RegFormTopLogin_Message'] ) ){
							$update['login_message'] = sanitize_text_field( $_POST['csds_RegFormTopLogin_Message'] );
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Top Login Form Message', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_RegFormTopRegistration_Message'] ) ){
						$update['reg_top_message'] = sanitize_text_field( $_POST['csds_RegFormTopRegistration_Message'] );
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Registration Form Message', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_LoginFormLogin_Message'] ) ){
						$update['login_messages_login'] = sanitize_text_field( $_POST['csds_LoginFormLogin_Message'] );
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Extra Login Messages', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_LoginFormLoggedOut_Message'] ) ){
						$update['login_messages_logged_out'] = sanitize_text_field( $_POST['csds_LoginFormLoggedOut_Message'] );
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Logged Out Message', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_LoginFormRegisteredSuccess_Message'] ) ){
						$update['login_messages_registered'] = sanitize_text_field( $_POST['csds_LoginFormRegisteredSuccess_Message'] );
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Succesful Registration Message', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_LostPassword_Message'] ) ){
						$update['login_messages_lost_password'] = sanitize_text_field( $_POST['csds_LostPassword_Message'] ); 
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Lost Password Message', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_LostPassword_confirmMessage'] ) ){
						$update['reset_password_confirm'] = sanitize_text_field( $_POST['csds_LostPassword_confirmMessage'] ); 
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Lost Password Confirm Message', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_select_RegFormMessage'] ) ){
						$update['select_pass_message'] = sanitize_text_field( $_POST['csds_select_RegFormMessage'] );
						$msg = '<div class="updated"><p>'. __( 'New Bottom Registration Form Message Options updated successfully.', 'user-registration-aide' ) .'</p></div>'; 
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Show Registration Form Bottom Message', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_RegForm_Message'] ) ){
						$update['registration_form_message'] = sanitize_text_field( $_POST['csds_RegForm_Message'] );
						$msg = '<div class="updated"><p>'. __( 'New Bottom Registration Form Message Options updated successfully.', 'user-registration-aide' ) .'</p></div>';
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Registration Form Bottom Message', 'user-registration-aide' );
					}
					$plugin = 'new-user-approve/new-user-approve.php';
					$class = 'pw_new_user_approve';
					if( class_exists( $class ) && is_plugin_active( $plugin ) ){
						if( !empty( $_POST['csds_nua_pre_reg_msg'] ) ){
							$update['nua_pre_register_msg'] = sanitize_text_field( $_POST['csds_nua_pre_reg_msg'] );
						}else{
							$ecnt ++;
							$efields[$ecnt] = __( 'New User Approve Pre Registration Message', 'user-registration-aide' );
						}
						if( !empty( $_POST['csds_nua_post_reg_msg_1'] ) ){
							$update['nua_post_register_msg_1'] = sanitize_text_field( $_POST['csds_nua_post_reg_msg_1'] );
						}else{
							$ecnt ++;
							$efields[$ecnt] = __( 'New User Approve Post Registration Message 1', 'user-registration-aide' );
						}
						if( !empty( $_POST['csds_nua_post_reg_msg_2'] ) ){
							$update['nua_post_register_msg_2'] = sanitize_text_field( $_POST['csds_nua_post_reg_msg_2'] );
						}else{
							$ecnt ++;
							$efields[$ecnt] = __( 'New User Approve Post Registration Message 2', 'user-registration-aide' );
						}
					}
					update_option( "csds_userRegAide_Options", $update );
					if( $ecnt == 0 && empty( $msg ) ){
						$msg = '<div class="updated"><p>'. __( 'New Top Registration Form Message Options updated successfully.', 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
					}elseif( $ecnt == 0 && !empty( $msg ) ){
						if( $update['select_pass_message'] == 1 && $update['show_login_message'] == 1 ){
							$msg = '<div class="updated"><p>'. __( 'New Top & Bottom Registration Form Message Options updated successfully.', 'user-registration-aide' ) .'</p></div>';
						}else{
							$msg = $msg;
						}
					}else{
						foreach( $efields as $key => $value ){
							if( $key == 1 ){
								$efield = $value;
							}else{
								$efield .= ' & '. $value;
							}
						}
						$msg = '<div class="error"><p>'. __( 'New Top Registration Form Message Fields Empty for ', 'user-registration-aide' ) .$efield .'</p></div>'; //Report to the user that the data has been updated successfully
					}
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
			}
		}
		return $msg;
	}
}