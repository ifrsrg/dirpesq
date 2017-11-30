<?php

/**
 * Class  URA_CUSTOM_OPTIONS_CONTROLLER
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_CUSTOM_OPTIONS_CONTROLLER
{
	
	/** 
	 * function custom_options_views
	 * Handles custom options page update messages and views
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function custom_options_views(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){	
			wp_die(__( 'You do not have permissions to modify this plugins settings, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ), 'user-registration-aide' );
		}else{
			$msg = ( string ) '';
			$msg = $this->custom_options_updates();
			$tab = 'custom_options';
			$h2 = array( 'adminPage', __( 'User Registration Aide: Custom Options', 'user-registration-aide' ), 'user-registration-aide' );
			$span = array( 'regForm', __( 'Password Change Options:', 'user-registration-aide' ), 'user-registration-aide' );
			$form = array( 'post', 'csds_userRegAide_customOptions' );
			$nonce = array( 'csds-customOptions', 'wp_nonce_csds-customOptions' );
			$msg1 = apply_filters( 'no_options_msg', $msg );
			//if( !empty( $msg ) || !empty( $msg1 ) ){
			//	do_action( 'start_msg_wrapper',  $msg, $msg1, $tab, $form, $h2, $span, $nonce );
			//}else{
				do_action( 'start_wrapper',   $msg, $msg1, $tab, $form, $nonce );
			//}
			if( isset( $_GET['tab'] ) ){
				$minitab = $_GET['tab'];	
			}else{
				$minitab = 'xwrd_change';
			}
			do_action( 'mini_tabs', $tab, $minitab  );
			
			if( $minitab ==  'xwrd_change' ){
				do_action( 'xwrd_chng_settings_view' ); // handles updates to dashboard widget options line 243 user-registration-aide.php
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'display_name' ){
				do_action( 'display_name_view' ); // handles updates to dashboard widget options line 243 user-registration-aide.php
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'security_questions' ){
				do_action( 'sq_settings_control' ); // handles updates to dashboard widget options line 243 user-registration-aide.php
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'ura_css' ){
				do_action( 'style_options_view' ); // handles updates to dashboard widget options line 243 user-registration-aide.php
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}		
						
		}
	}
		
	/** 
	 * function custom_options_updates
	 * Handles custom options page update messages
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns string $msg
	*/
	
	function custom_options_updates(){
		$msg = ( string ) '';
		if( isset( $_POST['updt_pwrd_chgd_options'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
				$msg = apply_filters( 'xwrd_chng_settings_update', $msg );
				if( !empty( $msg ) ){
					return $msg;
				}
			}
		}elseif( isset( $_POST['update_display_name_field'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
				$msg = apply_filters( 'display_name_options_update', $msg );
				if( !empty( $msg ) ){
					return $msg;
				}
			}
		}elseif( isset( $_POST['change_user_display_names'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
				$msg = apply_filters( 'display_name_options_update', $msg );
				if( !empty( $msg ) ){
					return $msg;
				}
			}
		}elseif( isset( $_POST['restore_default_display_names'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
				$msg = apply_filters( 'display_name_options_update', $msg );
				if( !empty( $msg ) ){
					return $msg;
				}
			}
		}elseif( isset( $_POST['sec_question_options'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
				$msg = apply_filters( 'sq_settings_model', $msg );
				if( !empty( $msg ) ){
					return $msg;
				}
			}
		}elseif( isset( $_POST['ura_update_style'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
				$msg = apply_filters( 'style_options_update', $msg );
				if( !empty( $msg ) ){
					return $msg;
				}
			}
		}elseif( isset( $_POST['csds_userRegAide_support_submit'] ) ){
			$nonce = 'wp_nonce_csds-customOptions';
			$nonce1 = 'csds-customOptions';
			$msg = apply_filters( 'ura_support_update', $msg, $nonce, $nonce1 );
			if( !empty( $msg ) ){
				return $msg;
			}
		}else{
			return $msg;
		}
		
	}


}
?>