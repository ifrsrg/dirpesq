<?php

/**
 * Class REGISTRATION_FORM_OPTIONS_CONTROLLER
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class REGISTRATION_FORM_OPTIONS_CONTROLLER
{
	
	/** 
	 * function initiate_reg_form_options_view
	 * initiates views for registration form settings admin page
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function initiate_reg_form_options_view(){
		global $current_user;
		$msg = ( string ) '';
		$msg = $this->reg_form_options_msgs();
		$current_user = wp_get_current_user();
		if( current_user_can( 'manage_options', $current_user->ID ) ){	
			$tab = 'registration_form_options';
			$h2 = array( 'adminPage', __( 'User Registration Aide: Custom Registration Form Options', 'user-registration-aide' ), 'user-registration-aide' );
			$span = array( 'regForm', __( 'Edit Your Own Custom Registration Form Options Here:', 'user-registration-aide' ), 'user-registration-aide' );
			$form = array( 'post', 'csds_userRegAide_regForm' );
			$nonce = array( 'csds-regForm', 'wp_nonce_csds-regForm' );
			$msg1 = apply_filters( 'no_options_msg', $msg );
			//if( !empty( $msg ) || !empty( $msg1 ) ){
			//	do_action( 'start_msg_wrapper',  $msg, $msg1, $tab, $form, $h2, $span, $nonce );
			//}else{
				do_action( 'start_wrapper',  $msg, $msg1, $tab, $form, $nonce );
			//}
			$options = get_option('csds_userRegAide_Options');
			if( isset( $_GET['tab'] ) ){
				$minitab = $_GET['tab'];	
			}else{
				$minitab = 'xwrd_strength';
			}
			do_action( 'mini_tabs', $tab, $minitab  );
			if( $minitab ==  'xwrd_strength' ){
				do_action( 'rf_options_view_1', $options ); // 
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab == 'redirects' ){
				do_action( 'rf_options_view_2', $options ); // 
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab == 'agreement' ){
				do_action( 'rf_options_view_3', $options ); // 
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;	
			}elseif( $minitab == 'math_problem' ){
				do_action( 'rf_options_view_4', $options ); //
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab == 'approve' ){
				do_action( 'rf_options_view_5', $options ); // 
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;	
			}elseif( $minitab == 'pp_title' ){
				do_action( 'rf_options_view_6', $options ); // h
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;	
			}
			
		}else{
			wp_die( __( 'You do not have permissions to manage options for this plugin, sorry, please check with the site administrators to resolve this issue please!', 'user-registration-aide' ) );
		}
		// initiate form view
		
		//do_action( 'rf_options_view', $msg );
		
	}
	
	/** 
	 * function initiate_reg_form_options_view
	 * checks all updates and gets messages from reults of updates if any updates performed
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns string $msg ( results of functions updated or error message to user )
	*/
	
	function reg_form_options_msgs(){
		$msg = ( string ) '';
		if( isset( $_POST['reg_form_message_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$msg = apply_filters( 'rf_msg_update', $msg );
				if( !empty( $msg ) ){
						return $msg;
				}
			}
		}elseif( isset( $_POST['psr_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$msg = apply_filters( 'xwrd_set_options_update', $msg );
				if( !empty( $msg ) ){
						return $msg;
				}
			}
		}elseif( isset( $_POST['redirects_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$msg = apply_filters( 'rf_redirects', $msg );
				if( !empty( $msg ) ){
						return $msg;
				}
			}
		}elseif( isset( $_POST['reg_form_agreement_message_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$msg = apply_filters( 'rf_agreement', $msg );
				if( !empty( $msg ) ){
						return $msg;
				}
			}
		}elseif( isset( $_POST['anti-bot-spammer'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$msg = apply_filters( 'rf_anti_spam', $msg );
				if( !empty( $msg ) ){
						return $msg;
				}
			}
		}elseif( isset( $_POST['newUserApprove_submit'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$msg = apply_filters( 'rf_nua', $msg );
				if( !empty( $msg ) ){
						return $msg;
				}
			}	
		}elseif( isset( $_POST['update_profile_title'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regForm'], 'csds-regForm' ) ){
				$msg = apply_filters( 'rf_prof_title', $msg );
				if( !empty( $msg ) ){
						return $msg;
				}
			}
		}elseif( isset( $_POST['csds_userRegAide_support_submit'] ) ){
			$nonce = 'wp_nonce_csds-regForm';
			$nonce1 = 'csds-regForm';
			$msg = apply_filters( 'ura_support_update', $msg, $nonce, $nonce1 );
			if( !empty( $msg ) ){
				return $msg;
			}
			
		}
		return $msg;
	}
}