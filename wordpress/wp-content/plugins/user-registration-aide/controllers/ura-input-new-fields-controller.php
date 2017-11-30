<?php

/**
 * Class INPUT_NEW_FIELDS_CONTROLLER
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class INPUT_NEW_FIELDS_CONTROLLER
{
		
	/**	
	 * Function initiate_new_fields_input_page
	 * Controls new fields input admin form options and settings view
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function initiate_new_fields_input_page(){
		global $current_user;
		$msg = ( string ) '';
		$action = ( string ) '';
		$input = array();
		$msg = $this->input_new_fields_msgs();
		$input = $this->input_new_fields_update( $input );
		
		if( !empty( $input ) ){
			$action = $input[0];
			$msg = $input[1];
		}
		// initiate form view
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){	
			wp_die( __( 'You do not have permissions to modify this plugins settings, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$tab = 'registration_fields';
			$form = array( 'post', 'csds_userRegAide_admin' );
			$nonce = array( 'csds-regFieldsAdmin', 'wp_nonce_csds-regFieldsAdmin' );
			$msg1 = apply_filters( 'no_options_msg', $msg );
			//if( !empty( $msg ) || !empty( $msg1 ) ){
			//	do_action( 'start_msg_wrapper',  $msg, $msg1, $tab, $form, $nonce );
			//}else{
			do_action( 'start_wrapper',  $msg, $msg1, $tab, $form, $nonce );
			//} 
			if( isset( $_GET['tab'] ) ){
				$minitab = $_GET['tab'];	
			}else{
				$minitab = 'add_new_fields';
			}
			do_action( 'mini_tabs', $tab, $minitab  );
			
			if( $minitab ==  'dash_widget' ){
				do_action( 'display_dw_options' ); // handles updates to dashboard widget options line 243 user-registration-aide.php
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'add_new_fields' ){
				do_action( 'new_fields_input_view', $action ); // handles updates to dashboard widget options line 243 user-registration-aide.php
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}
		}
		
	}
	
	/**	
	 * Function input_new_fields_msgs
	 * Controls new fields filters for new fields inputs 
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns string $msg ( results of function updated or error  message to display to user )
	*/
	
	function input_new_fields_msgs(){
		$msg = ( string ) '';
		if( isset( $_POST['dash_widget_display_option'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFieldsAdmin'], 'csds-regFieldsAdmin' ) ){	
				//do_action('update_dw_display_options'); // handles updates to dashboard widget options line 244 user-registration-aide.php
				$msg = apply_filters( 'update_dw_display_options', $msg );
				if( !empty( $msg ) ){
					return $msg;
				}
			}
		}elseif( isset( $_POST['dash_widget_fields_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFieldsAdmin'], 'csds-regFieldsAdmin' ) ){	
				//do_action('update_dw_field_options'); // handles updates to dashboard widget options line 244 user-registration-aide.php
				$msg = apply_filters( 'update_dw_field_options', $msg );
				if( !empty( $msg ) ){
					return $msg;
				}
			}
		}elseif( isset( $_POST['dash_widget_field_order_update'] ) ){
			if( wp_verify_nonce( $_POST['wp_nonce_csds-regFieldsAdmin'], 'csds-regFieldsAdmin' ) ){				
				//do_action('update_dw_field_order'); // handles updates to dashboard widget options line 244 user-registration-aide.php
				$msg = apply_filters( 'update_dw_field_order', $msg );
				if( !empty( $msg ) ){
					return $msg;
				}
			}
		}elseif( isset( $_POST['csds_userRegAide_support_submit'] ) ){
			$nonce = 'wp_nonce_csds-regFieldsAdmin';
			$nonce1 = 'csds-regFieldsAdmin';
			$msg = apply_filters( 'ura_support_update', $msg, $nonce, $nonce1 );
			if( !empty( $msg ) ){
				return $msg;
			}
		}
		
		return $msg;
	}
	
	function input_new_fields_update( $results ){
		$results = array();
		if( isset( $_POST['new_fields_update'] ) ){
			$results = apply_filters( 'new_fields_input_filter', $results );
		
			if( !empty( $results ) ){
				return $results;
			}
		}
	}
	
}?>