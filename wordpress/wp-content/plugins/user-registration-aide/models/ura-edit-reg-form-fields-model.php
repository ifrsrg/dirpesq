<?php

/**
 * Class URA_EDIT_REGISTRATION_FIELDS_CONTROLLER
 *
 * @category Class
 * @since 1.5.1.4
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_EDIT_REGISTRATION_FORM_FIELDS_MODEL
{
	
	/** 
	 * function update_registration_fields_settings_model
	 * Updates selected fields for registration form on URA admin page
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg ( results of function success or failure ) 
	*/
	
	function update_registration_fields_settings_model( $msg ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$ura_options = new URA_OPTIONS(); 
			$options = get_option( 'csds_userRegAide_Options' );
			$current_user = wp_get_current_user();
			$field = new FIELDS_DATABASE();
			$ura_fields = $field->get_all_fields(); 
			do_action( 'update_reg_fields' );
			// Checking to see that database options are up to date to the latest version
			if( array_key_exists( 'csds_userRegAide_db_Version', $options ) ){
				if( $options['csds_userRegAide_db_Version'] != "1.5.2.0" ){
					
						$ura_options->csds_userRegAide_updateOptions();
					
				}
			}
				
			// Checking to see that registration fields isn't empty
			
			if( empty( $registratrionFields ) ){
				
					$ura_options->csds_userRegAide_updateRegistrationFields();
				
			}
					
			// Declaring - Defining Variables
			$all_fields = array();
			$regFields = array();
			$seperator = ( string ) '';
			$csds_userRegAide_newFieldKey = ( string ) '';
			$csds_userRegAide_newField = ( string ) '';
			$csds_userRegMod_fields_missing = ( string ) '';
			$csds_userRegMod_fields_missing1 = ( string ) '';
			$csds_userRegMod_fields_missing2 = ( string ) '';
			$key = ( string ) '';
			$key1 = ( string ) '';
			$key2 = ( string ) '';
			$value = ( string ) '';
			$value1 = ( string ) '';
			$value2 = ( string ) '';
			$cnt = ( int ) '';
			$cnt1 = ( int ) 0;
			$submit = ( int ) 0;
			$field = ( string ) '';
			$new_field = ( string ) '';
			$msg = ( string ) '';
			$post_msg = ( string ) '';
			$results2 = array();
			
					
			// Updating Arrays from options db
			
			if( isset( $_POST['select_none'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
					$plugin = 'buddypress/bp-loader.php'; 
					$clear_reg_fields = array();
					update_option( 'csds_userRegAide_registrationFields', $clear_reg_fields );
					$field_db = new FIELDS_DATABASE();
					$field_db->select_none_reg_form_fields();
					if( is_plugin_active( $plugin ) ){
						do_action( 'bp_required_fields_update' );
					}
					$msg = '<div id="message" class="updated"><p>'. __( 'All Registration Form Fields Unselected Successfully!', 'user-registration-aide' ) .'</p></div>';
					unset( $field_db );
					return $msg;
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
			}elseif( isset( $_POST['reg_fields_update'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){
					$option_fields = array();
					$option_fields = apply_filters( 'get_option_fields_array', $option_fields );
					$plugin = 'buddypress/bp-loader.php'; 
					$xwrd = 2;
					$knownFields = get_option( 'csds_userRegAide_knownFields' );
					$reg_fields = get_option( 'csds_userRegAide_registrationFields' );
					$knownFields_count = count( $knownFields );
					if( !empty( $_POST['additionalFields'] ) ){
						$results2 =  $_POST['additionalFields'];
						//exit( print_r( $results2 ) );
						if( !empty( $results2 ) ){
							foreach( $results2 as $key => $value ){
								if( !empty( $knownFields ) ){
									foreach( $knownFields as $key1 => $value1 ){
										$value = trim( $value );
										$key1 = trim( $key1 );
										if( $key1 == $value ){
											if( !empty( $reg_fields ) ){
												if( !array_key_exists( $value, $reg_fields ) ){
													$reg_fields[$key1] = $value1;
												}
											}else{
												$reg_fields[$key1] = $value1;
											}
										}
										if( !empty( $reg_fields ) ){
											if( array_key_exists( $key1, $reg_fields ) && !in_array( $key1, $results2 ) ){
												unset( $reg_fields[$key1] );
											}
										}
										if( $key1 != 'user_pass' ){
											$xwrd = 0;
											$options['password'] = $xwrd;
											$options['user_password'] = $xwrd;
											update_option("csds_userRegAide_Options", $options);
										}elseif( $key1 == 'user_pass' ){
											$xwrd = 1;
											$options['password'] = $xwrd;
											$options['user_password'] = $xwrd;
											update_option("csds_userRegAide_Options", $options);
										}
									}
								}
								
								if( !empty( $ura_fields ) ){
									$field = new FIELDS_DATABASE();
									foreach( $ura_fields as $object ){
										$meta_key = $object->meta_key;
										$name = $object->field_name;
										$data_type = $object->data_type;
										if( $value == $meta_key ){
											if( array_key_exists( $data_type, $option_fields ) ){
												$field_options = $field->get_total_field_options( $meta_key );
												if( !empty( $field_options ) ){
													$field->update_fields( $meta_key, 'registration_field', true );
													$regFields[$meta_key] = $name;
												}else{
													$msg = '<div id="message" class="error"><p>'. sprintf( __( 'Registration Form Add New Field Options NOT Updated ! Cannot add A Field Type of %s Without any Existing Options to the Registration Form!', 'user-registration-aide' ), $data_type ) .'</p></div>';	
													return $msg;	
												}
											}else{
												$field->update_fields( $meta_key, 'registration_field', true );
												$regFields[$meta_key] = $name;
											}
										}
										if( !in_array( $meta_key, $results2 ) ){
											$field->update_fields( $meta_key, 'registration_field', false );
											$field->update_fields( $meta_key, 'field_required', false );
											$field->update_fields( $meta_key, 'approve_view', false );
										}
									}
									
								}
								unset( $field );
							}
							update_option( 'csds_userRegAide_registrationFields', $reg_fields );
							if( is_plugin_active( $plugin ) ){
								do_action( 'bp_required_fields_update' );
							}
						}else{
							$selected = '';
							$regFields = array();
							update_option("csds_userRegAide_registrationFields", $regFields);
						}
					}else{
						$selected = '';
						$regFields = array();
						update_option("csds_userRegAide_registrationFields", $regFields);
					}
					$msg = '<div id="message" class="updated"><p>'. __( 'Registration Form Add New Field Options Updated Successfully!', 'user-registration-aide' ) .'</p></div>';	
					return $msg;					//Report to the user that the data has been updated successfully
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
			}elseif( isset( $_POST['select_required_none'] ) ){ ////// needs to be updated
				if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){
					$plugin = 'buddypress/bp-loader.php'; 
					$clear_reg_fields = array();
					update_option( 'csds_ura_optionalFields', $clear_reg_fields );
					$field_db = new FIELDS_DATABASE();
					$field_db->select_none_req_fields(); 
					
					if( is_plugin_active( $plugin ) ){
						do_action( 'bp_required_fields_update' );
					}
					unset( $field_db );
					$msg = '<div id="message" class="updated"><p>'. __( 'All Optional Registration Form Fields Unselected Successfully!', 'user-registration-aide' ) .'</p></div>';
					return $msg;
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
				
			}elseif( isset( $_POST['required_fields_update'] ) ){
				$optional_fields = get_option( 'csds_ura_optionalFields' );
				$knownFields = get_option( 'csds_userRegAide_knownFields' );
				if( empty( $optional_fields ) ){
					$optional_fields = array();	
				}
				$update = array();
				if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
					$plugin = 'buddypress/bp-loader.php'; 
					$xwrd = 2;
					
					if( !empty( $_POST['requiredFields'] ) ){
						$results2 =  $_POST['requiredFields'];
						//exit( print_r( $results2 ) );
						if( !empty( $results2 ) ){
							foreach( $results2 as $key => $value ){
								if( !empty( $knownFields ) ){
									foreach( $knownFields as $key1 => $value1 ){
										if( $value == $key1 ){
											$update[$key1] = $value1;
										}	
										update_option( "csds_ura_optionalFields", $update );
									}
								}
								if( !empty( $ura_fields ) ){
									$field = new FIELDS_DATABASE();
									foreach( $ura_fields as $object ){
										$meta_key = $object->meta_key;
										$name = $object->field_name;
										if( $value == $meta_key ){
											$field->update_fields( $meta_key, 'field_required', false );
										}
										if( !in_array( $meta_key, $results2 ) ){
											$field->update_fields( $meta_key, 'field_required', true );
										}
									}
								}
								unset( $field );
							}
							if( is_plugin_active( $plugin ) ){
								do_action( 'bp_required_fields_update' );
							}
						}else{
							$selected = '';
							$optional_fields = array();
							update_option( "csds_ura_optionalFields", $optional_fields );
						}
					}else{
						$selected = '';
						$optional_fields = array();
						update_option( "csds_ura_optionalFields", $optional_fields );
					}
					$msg = '<div id="message" class="updated"><p>'. __( 'Registration Form Required Fields Updated Successfully!', 'user-registration-aide' ) .'</p></div>';					//Report to the user that the data has been updated successfully
					return $msg;
					
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
				return $msg;
			}elseif( isset( $_POST['update-asterisk-colon'] ) ){
				//exit( 'BUTTON PRESSED' );
				if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
					
					if( isset( $_POST['use_asterisk'] ) ){
						$options['designate_required_fields'] = $_POST['use_asterisk'];
						$options['show_asterisk'] = $_POST['use_asterisk'];
					}
					if( isset( $_POST['use_colon'] ) ){
						$options['reg_form_use_colon'] = $_POST['use_colon'];
					}
					update_option( "csds_userRegAide_Options", $options );
					$msg = '<div id="message" class="updated"><p>'. __( 'Registration Form Field Title Punctuation Updated Successfully!', 'user-registration-aide' ) .'</p></div>';					//Report to the user that the data has been updated successfully
					return $msg;
					
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
			}
		}
		return $msg;
	}
	
} // end class