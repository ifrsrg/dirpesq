<?php

/**
 * Class EDIT_NEW_FIELDS_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class EDIT_NEW_FIELDS_MODEL
{	

	/**	
	 * Function new_fields_edit_model
	 * Controls new fields editing submission of changes for deleting fields, field order and field title or name
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns string $msg ( results of function updated or error  message to display to user )
	*/
	
	function new_fields_edit_model( $msg ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$field = new FIELDS_DATABASE();
			$options = get_option( 'csds_userRegAide_Options' );
			$delete_error = ( int ) 0;
			$msg = ( string ) '';
			$count = ( int ) 0;
			$field_id = ( int ) 0;
			$submit = ( int ) 0;
			$seperator = ( string ) '';
			$results1 = ( string ) '';
			$plugin = ( string ) '';
			$msg1 = ( string ) '';
			$upd_msg = ( string ) '<div id="message" class="updated"><p>';
			$err_msg = ( string ) '<div id="message" class="error"><p>';
			$end_msg = ( string ) '</p></div>';
			$view = ( string ) 'edit-new-fields';
			if( $options['csds_userRegAide_db_Version'] != "1.5.2.0" ){
				do_action( 'update_options' ); // Line 259 user-registration-aide.php
			}
			
			$registrationFields = get_option( 'csds_userRegAide_registrationFields' );
			$fieldKey = '';
			$fieldOrder = '';
			$options = get_option( 'csds_userRegAide_Options' );
			$delete = ( int ) 0;
			$nuaFields = array();
			$plugin = 'buddypress/bp-loader.php';
			// Handles the delete field form
			if ( isset( $_POST['delete_field'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
					$results1 = '';
					
					// Checking for field to delete if empty gives warning and exits
					$dfield = new FIELDS_DATABASE();
					if( !empty( $_POST['deleteNewFields'] ) ){
						$results1 =  $_POST['deleteNewFields'];
						$delete_error = 0;
						$dfield = $field->get_field_by_meta_key( $results1 );
						$field_name = $dfield->field_name;
					}else{
						$delete_error = 1;
					}
					
					if( !empty( $results1 ) ){
						// getting BP field id
						
						if( is_plugin_active( $plugin ) ){
							//exit( print_r( $dfield ) );
							$field_id = $dfield->bp_ID;
						}
						
						// Delete field from usermeta must be done b4 deleting bp field otherwise it deletes field before deleting data from users
						do_action( 'delete_usermeta_field', $results1 ); // Deletes field from user meta 
						
						// deleting BP xprofile field
						if( is_plugin_active( $plugin ) ){
							xprofile_delete_field( $field_id );	
						}
						
						if( $delete_error == 0 ){
							$field->delete_fields( $dfield );
						}
						
						if( !empty( $registrationFields ) ){
							if( array_key_exists( $results1, $registrationFields ) ){
								unset( $registrationFields[$results1] );
							}
						}
					
						$regFields = $registrationFields;
						if( !empty( $regFields ) ){
							update_option( "csds_userRegAide_registrationFields", $regFields );
						}
					}
					
					//Report to the user that the data has been updated successfully or that an error has occurred
					if( $delete_error == 0 ){
						$msg = sprintf( __( ' %s Successfully Deleted!' , 'user-registration-aide' ), $field_name );
						$msg = $upd_msg.$msg.$end_msg;
					}elseif( $delete_error == 1 ){
						$msg = __( 'No field was selected for deletion, you must select a field to delete first!' , 'user-registration-aide' );
						$msg = $err_msg.$msg.$end_msg;
					}
				}
				return $msg;
			// Handles the new fields order form
			}elseif( isset( $_POST['field_order'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
					$key = '';
					$key1 = '';
					$key2 = '';
					$value = '';
					$value1 = '';
					$value2 = '';
					$results = '';
					$aa = '';
					$field_order_error = ( int ) 0;
					$ura_fields = $field->get_all_fields();	
					$msg = ( string ) '';
					// Getting values from new field order select options
					$results = $_POST['csds_editFieldOrder'];
					$aa = ( int ) 0;
						
					//foreach($csds_userRegAide_fieldOrder as $key => $value){
					foreach( $ura_fields as $object ){
						$meta_key = $object->meta_key;
						foreach( $results as $key1 => $value1 ){
							foreach( $results as $key2 => $value2 ){
								if( $key1 != $key2 && ( $value1 == $value2 ) ){
									$field_order_error = 1;
									break;
								}
							}
							if( $field_order_error != 1 ){
								if( $aa == $key1 ){
									$temp[$meta_key] = $value1;
								}
							}else{
								$temp[$meta_key]= $value;
							}
						}
						$aa++;
					}
							
					// Updating New Field Order
					if( $field_order_error != 1 ){	// Checking for errors duplicate fields before updating
						$fieldOrder = $temp;
						asort( $fieldOrder );
						foreach( $fieldOrder as $key => $value ){
							$field->update_field_order( $key, $value );
						}
						
						$plugin = 'buddypress/bp-loader.php';
						
						if( is_plugin_active( $plugin ) ){
							$results = do_action( 'update_bp_field_order' );
							if( !is_wp_error( $results ) ){
								$msg = __( 'New Field Order Updated Successfully.', 'user-registration-aide' );
								$msg = $upd_msg.$msg.$end_msg;
							}else{
								$msg = $results;
								$msg = $err_msg.$msg.$end_msg;
							}
						}else{
						
							$msg =  __( 'New Field Order Updated Successfully.', 'user-registration-aide' );
							$msg = $upd_msg.$msg.$end_msg;
						}
						
					}else{ // Duplicate fields error display message
						$msg = __( '***Error Updating New Field Order, Two or More Fields Have the Same Order!***', 'user-registration-aide' );
						$msg = $err_msg.$msg.$end_msg;
						
					}
				}
			}elseif( isset( $_POST['edit_field_name'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
					$results = array();
					$changed = ( int ) 0;
					$empty = ( int ) 0;
					$emptyFields = array();
					
					// getting BP field id
					
					$msgs = array();
					$ura_fields = $field->get_all_fields();	
					$new_name = array();
					// Checking for new field title changed if empty gives warning and exits
					foreach( $ura_fields as $object ){
						$post_key = $object->meta_key.'_title';
						$key = $object->meta_key;
						$name = $object->field_name;
						if( !empty( $_POST[$post_key] ) ){
							if( $_POST[$post_key] != $name ){
								$INFM = new INPUT_NEW_FIELDS_MODEL();
								$cnt = ( int ) 0;
								$cnt = $field->dup_field_names( $_POST[$post_key] );
								if( $cnt >= 1 ){
									$dup = ( string ) $_POST[$post_key];
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate field names, %s is already included in the extra fields!***', 'user-registration-aide' ), $dup ) .'</p></div>';
									return $msg;
									break;
								}
								$field_key = 'field_name';
								$meta_key = $key;
								$field_value = $_POST[$post_key];
								
								$field->update_fields( $meta_key, $field_key, $field_value );
								// updating bp field name
								$plugin = 'buddypress/bp-loader.php';
								if( is_plugin_active( $plugin ) ){
									$bpf = new URA_BP_FUNCTIONS();
									$results[$key] = $bpf->update_bp_fieldname_change( $key, $_POST[$post_key] );								
								}
								$results[$key] = $field_value;
								$new_name[$key] = $name;
								$changed ++;
							}
						}else{
							$error_results[$key] = $name;
							$empty ++;
							//$changed ++;
						}
						
					}
					$i = ( int ) 0;
					$ii = ( int ) 0;
					if( $empty > 0 ){
						$i = count( $error_results );
						$msg = '<div id="message" class="error"><p>';
						foreach( $error_results as $keys => $values ){
							$ii++;
							if( !empty( $values ) ){
								if( $ii < $i ){
									$msg .= sprintf( __( ' %s Name Not Entered! & ' , 'user-registration-aide'), strtoupper( $keys ) ) ;
								}else{
									$msg .= sprintf( __( ' %s Name Not Entered!' , 'user-registration-aide'), strtoupper( $keys ) ) ;
								}
							}
							
						}
						$msg .= '</p></div>';
					}
					
					//Report to the user that the data has been updated successfully or that an error has occurred
					if( $changed >= 1 ){
						$i = count( $results );
						$msg = '<div id="message" class="updated"><p>';
						
						foreach( $results as $keys => $values ){
							$ii++;		
							if( !empty( $values ) ){
								if( $ii < $i ){
									$msg .= sprintf( __( ' %1s Name Successfully changed to %2s! & ' , 'user-registration-aide'), $new_name[$keys], $values ) ;
								}else{
									$msg .= sprintf( __(' %1s Name Successfully changed to %2s!' , 'user-registration-aide'), $new_name[$keys], $values );
								}
							
							}
							
						}
						$msg .= '</p></div>';
					}elseif( $changed == 0 && $empty == 0){
						$msg = '<div id="message" class="error"><p>'. __( 'No field was changed!' , 'user-registration-aide' ) .'</p></div>';
						
					}
				}
			}
		}
		return $msg;
	}
	
}