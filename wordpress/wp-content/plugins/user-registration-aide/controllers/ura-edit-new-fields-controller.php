<?php

/**
 * Class URA_EDIT_NEW_FIELDS_CONTROLLER
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/


class URA_EDIT_NEW_FIELDS_CONTROLLER
{
	
	/**	
	 * Function edit_new_fields_controller
	 * Controls new fields editing submission of changes and views
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns 
	*/
	
	function edit_new_fields_controller(){
		global $current_user;
		$msg = ( string ) '';
		$nf_msg = ( string ) '';
		$msg1 = ( string ) '';
		$msg = $this->edit_new_fields_page_controller();
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){	
			wp_die( __( 'You do not have permissions to modify this plugins settings, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$tab = 'edit_new_fields';		
			$form = array( 'post', 'csds_userRegAide_newFields' );
			$nonce = array( 'csds-newFields', 'wp_nonce_csds-newFields' );
			$msg1 = apply_filters( 'no_options_msg', $msg1 );
			//if( !empty( $msg ) || !empty( $msg1 ) ){
			//	do_action( 'start_msg_wrapper',  $msg, $msg1, $tab, $form, $nonce );
			//}else{
				do_action( 'start_wrapper',  $msg, $msg1, $tab, $form, $nonce );
			//} 
			if( isset( $_GET['tab'] ) ){
				$minitab = $_GET['tab'];	
			}else{
				$minitab = 'reg_form_fields';
			}
			do_action( 'mini_tabs', $tab, $minitab  );
			
			if( $minitab ==  'reg_form_fields' ){
				do_action( 'reg_fields_controller' );  // handles updates to dashboard widget options line 243 user-registration-aide.php
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'field_order' ){
				do_action( 'field_order_view' );  // handles updates to dashboard widget options line 243 user-registration-aide.php
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'option_order' ){
				do_action( 'options_order_view' );
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'option_order' ){
				do_action( 'options_order_view' );
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'option_titles' ){
				do_action( 'new_field_options_view' );
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'add_options' ){
				do_action( 'new_option_view' );
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'field_type' ){
				do_action( 'field_type_view' );
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}elseif( $minitab ==  'edit_numbers' ){
				do_action( 'number_editor_view' );
				do_action( 'end_mini_wrap' );
				do_action('end_wrapper');
				return;
			}
			//do_action( 'new_fields_edit_view', $msg );
			//do_action( 'options_order_view' );
			//do_action( 'new_field_options_view' );
			//do_action( 'number_editor_view' );
		}
	}
	
	/**	
	 * Function edit_new_fields_page_controller
	 * Handles new fields editing filtering and gathering messages from submission of changes
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns string $msg ( results of function updated or error  message to display to user )
	*/
	
	function edit_new_fields_page_controller(){
		$msg = ( string ) '';
		$msg = apply_filters( 'reg_fields_selections', $msg );
		if( empty( $msg ) ){
			if( isset( $_POST['delete_field'] ) ){
				$msg = apply_filters( 'edit_new_field_model', '' );
			}elseif( isset( $_POST['field_order'] ) ){
				$msg = apply_filters( 'edit_new_field_model', '' );
			}elseif( isset( $_POST['edit_field_name'] ) ){
				$msg = apply_filters( 'edit_new_field_model', '' );
			}elseif( isset( $_POST['option_order_update'] ) ){
				$msg = apply_filters( 'options_order_model', '' );
			}elseif( isset( $_POST['delete_option'] ) ){
				$msg = apply_filters( 'new_field_options_model', ''  );
			}elseif( isset( $_POST['edit_field_option_name'] ) ){
				$msg = apply_filters( 'new_field_options_model', ''  );
			}elseif( isset( $_POST['add_field_option'] ) ){
				$msg = apply_filters( 'new_field_options_model', ''  );
			}elseif( isset( $_POST['change_field_type'] ) ){
				$msg = apply_filters( 'edit_data_type', ''  );
			}elseif( isset( $_POST['number_options_update'] ) ){
				$msg = apply_filters( 'edit_numbers_model', ''  );
			}elseif( isset( $_POST['csds_userRegAide_support_submit'] ) ){
				$nonce = 'wp_nonce_csds-newFields';
				$nonce1 = 'csds-newFields';
				$msg = apply_filters( 'ura_support_update', $msg, $nonce, $nonce1 );
			}
		}
		return $msg;
	}
}?>