<?php

/**
 * Class EDIT_FIELD_OPTIONS_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class EDIT_FIELD_OPTIONS_MODEL
{

	/**	
	 * Function edit_new_field_options_model
	 * URA Edit New Fields Options Model handles options page updates
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns string $msg1 ( results of update updated or error  msg )
	*/
	
	function edit_new_field_options_model(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$plugin = 'buddypress/bp-loader.php';
			$field = new FIELDS_DATABASE();
			$ura_fields = $field->get_all_fields();
			$pre_msg = ( string ) '';
			$msg = ( string ) '';
			$msg1 = ( string ) '';
			$post_msg = ( string ) '';
			$submit = ( int ) 0;
			$class = ( string ) '';
			$final_results = ( int ) 0;
			if( isset( $_POST['delete_option'] ) ){
				
				$selected = ( string ) '';
				$deleted_key = ( string ) '';
				$deleted_name = ( string ) '';
				$option_key = ( string ) '';
				$key = ( string ) '';
				$order = ( int ) 0;
				$parent_id = ( int ) 0;
				$delete = ( boolean ) false;
				
				foreach( $ura_fields as $object ){
					$options = $field->get_field_options_edit( $object->ID );
					$key = $object->meta_key.'_delete';
					
					if( isset( $_POST[$key] ) ){
						if( !empty( $options ) ){
							if( $_POST[$key] != 'ZERO' ){
								$selected = $_POST[$key];
								foreach( $options as $option ){
									$option_key = $option->option_meta_key.'_delete';
									if( $option_key == $selected ){
										//exit( 'DELETE MATCH!!!' );
										$deleted_key = $option->option_meta_key;
										$deleted_name = $option->field_name;
										$order = $option->option_order;
										$id = $option->parent_id;
										$bpid = $option->bp_ID;
										$bp_parent_id = $option->bp_parent_ID;
									}
								}
								if( is_plugin_active( $plugin ) ){
									$bp = new URA_BP_FUNCTIONS();
									$final_results = $bp->edit_options_order_for_deletion( $bpid, $bp_parent_id );
									if( $final_results != 0 ){
										$delete = xprofile_delete_field( $bpid );
									
									//exit( print_r( $delete ) );
										if( $delete == false ){
											$msg = sprintf( __( ' Delete BP Option Error Messages - Unsuccessful BP & URA Field Option Deletion for Option %s ', 'user-registration-aide' ), $deleted_name );
											$class = 'error';
										}else{
											if( !empty( $deleted_key ) ){
												$id = $option->parent_id;
												$ura_delete = $field->delete_options( $id, $deleted_key );
												if( $ura_delete == 1 ){
													do_action( 'update_option_order', $id, $order );
													$msg .= sprintf( __( '%s Option Deleted Successfully From URA & BP Field Options!', 'user-registration-aide' ), $deleted_name );
													$class = 'updated';
													$final_results++;
												}else{
													$msg = sprintf( __( ' BP Field Option %1s Successfully Deleted **** Error Messages - Unsuccessful URA Fields Deletion for Option: %2s', 'user-registration-aide' ), $deleted_name, $deleted_name );
													$class = 'error';
												}
											}
											$selected = '';
											$option = '';
											$delete = '';
											$deleted_key = '';
											$deleted_name = '';
											$option_key = '';
											$key = '';
										}
									}else{
										$msg = sprintf( __( ' Error Messages - Unsuccessful BP & URA Field Option Deletion for Option: %s ', 'user-registration-aide' ), $deleted_name );
										$class = 'error';
									}
								}else{
									if( !empty( $deleted_key ) ){
										//$order = $option->option_order;
										$id = $option->parent_id;
										$ura_delete = $field->delete_options( $id, $deleted_key );
										$update_options = $field->reset_option_order( $id, $order );
										if( $update_options === false ){
											$msg = sprintf( __( ' Error Messages - Option Order Could Not be Updated!', 'user-registration-aide' ), $deleted_name );
											$class = 'error';
										}else{
											if( $ura_delete == 1 ){
												$msg .= sprintf( __( '%s  Option Deleted Successfully!', 'user-registration-aide' ), $deleted_name );
												do_action( 'update_option_order', $id, $order );
												$class = 'updated';
												$final_results++;
											}else{
												$msg = sprintf( __( ' Error Message - Unsuccessful URA Field Option Deletion: %s', 'user-registration-aide' ),$deleted_name );
												$class = 'error';
											}
										}
									}
									$selected = '';
									$option = '';
									$delete = '';
									$deleted_key = '';
									$deleted_name = '';
									$option_key = '';
									$key = '';
								}
								
							}
						}
					}
				}
				if( $final_results <= 0 ){
					$class = 'error';
					$msg = __( 'No Fields Selected for Deletion! Please Try Again!', 'user-registration-aide' );
				}
				$msg1 = '<div id="message" class="'.$class.'"><p>'.$msg.'</p></div>';
				
			}elseif( isset( $_POST['edit_field_option_name'] ) ){
				$index = ( int ) 0;
				$old_name = ( string ) '';
				$changed = ( string ) '';
				$count = ( int ) 0;
				$chnged_cnt = ( int ) 0;
				$field_msg = ( string ) '';
				$fields_updated = ( string ) '';
				$fields_not_updated = ( string ) '';
				$bp_fields_not_updated = ( string ) '';
				$updated_fields = array();
				$old_fields = array();
				$bp_updated_fields = array();
				$no_update = array();
				$bp_no_update = array();
				$ura_msg = ( string ) __( ' The Following URA Option Name(s) Were Successfully Changed: ', 'user-registration-aide' );
				$bp_msg = ( string ) __( ' BP Option Names Successfully Updated ', 'user-registration-aide' );
				$bp_error_msg = ( string ) __( ' The Following BP Option Names Could Not Be Updated at This Time: ', 'user-registration-aide' );
				$error_msg = ( string ) __( ' The Following URA Option Names Could Not Be Updated at This Time: ', 'user-registration-aide' );
				$dup_error_msg = ( string ) __( ' The Following URA Option Names Could Not Be Updated at This Time: ', 'user-registration-aide' );
				$dup_error_msg_2 = ( string ) __( ' That Option Name Already Exists! ', 'user-registration-aide' );
				$fcount = count( $ura_fields ) - 1;
				$findex = ( int ) 0;
				foreach( $ura_fields as $object ){
					$options = $field->get_field_options_edit( $object->ID );
					$ocount = count( $options ) - 1;
					//exit( 'COUNT: '.$count );
					if( !empty( $options ) ){
						$index = 0;
						
						foreach( $options as $option ){
							$post_key = $option->option_meta_key.'_'.$index;
							$old_name = $option->field_name;
							$parent_id = $option->parent_id;
							if( isset( $_POST[$post_key] ) ){
								$post_name = $_POST[$post_key];
								
								if( $post_name != $old_name ){
									//wp_die( $post_name.' - '.$old_name );
									$id = $this->duplicate_option_names( $parent_id, $post_name );
									//wp_die( '---ID: ---'.$id );
									if( empty( $id ) ){
										$changed = $_POST[$post_key];
										$meta_key = $option->option_meta_key;
										$old_name = $option->field_name;
										if( is_plugin_active( $plugin ) ){
											$bpf = new URA_BP_FUNCTIONS();
											$results = $bpf->edit_buddypress_option_name( $old_name, $changed );
											
											if( $results == 1 ){
												$ura_results = $field->update_options_fields( $meta_key, 'field_name', $changed );
												if( $ura_results == 1 ){
													$updated_fields[$meta_key] = $option->field_name;
													$old_fields[$meta_key] = $changed;
												}else{
													//$msg .= ' Error Messages - URA Option Names Not Changed';
													$no_update[$meta_key] = $option->field_name;
												}
												
											}else{
												$bp_no_update[$meta_key] = $option->field_name;
											}
											
										}else{
											$ura_results = $field->update_options_fields( $meta_key, 'field_name', $changed );
											if( $ura_results == 1 ){
												$updated_fields[$meta_key] = $option->field_name;
												$old_fields[$meta_key] = $changed;
											}else{
												$no_update[$meta_key] = $option->field_name;
											}
										}
										$changed = '';
										$option = '';
										$old_name = '';
									}else{
										$class = 'error';	
										$msg = $dup_error_msg.$post_name.$dup_error_msg_2;
										$msg1 = '<div id="message" class="'.$class.'"><p>'.$msg.'</p></div>';
										return $msg1;
									}
								}
							}
							$index++;
						}
						
					}
					$findex++;
				}
				$i = ( int ) 0;
				if( !empty( $updated_fields ) ){
					foreach( $updated_fields as $ukey => $uvalue ){
						if( $i == 0 ){
							$fields_updated .= $uvalue;
							if( array_key_exists( $ukey, $old_fields ) ){
								$fields_updated .= ' to '.$old_fields[$ukey];	
							}
						}else{
							$fields_updated .= ' - '.$uvalue;
							if( array_key_exists( $ukey, $old_fields ) ){
								$fields_updated .= ' to '.$old_fields[$ukey];	
							}
						}
						$i++;
					}
					$class = 'updated';
				}elseif( empty( $updated_fields ) && empty( $no_update ) ){
					$class = 'error';
					$msg = __( 'No Field Option Titles Were Changed! Please Try Again!', 'user-registration-aide' );
				}
				$i = 0;
				if( !empty( $no_update ) ){
					foreach( $no_update as $nkey => $nvalue ){
						if( $i == 0 ){
							$fields_not_updated .= $nvalue;
						}else{
							$fields_not_updated .= ' - '.$nvalue;
						}
						$i++;
					}
					$class = 'error';
				}
				$i = 0;
				if( !empty( $bp_no_update ) ){
					foreach( $bp_no_update as $bpnkey => $bpnvalue ){
						if( $i == 0 ){
							$bp_fields_not_updated .= $bpnvalue;
						}else{
							$bp_fields_not_updated .= ' - '.$bpnvalue;
						}
						$i++;
					}
					$class = 'error';
				}
				$i = 0;
				
				if( !empty(  $fields_updated ) ){
					$msg = $ura_msg.$fields_updated;
				}
				if( !empty( $fields_not_updated ) ){
					$msg .= ' - '.$error_msg.$fields_not_updated;
				}
				if( !empty( $bp_fields_not_updated ) ){
					$msg .= ' - '.$bp_error_msg.$bp_fields_not_updated;
				}
				
				
				$msg1 = '<div id="message" class="'.$class.'"><p>'.$msg.'</p></div>';
				return $msg1;
			}elseif( isset( $_POST['add_field_option'] ) ){
							
				$option_key = ( string ) '';
				$option_title = ( string ) '';
				$order_by = ( string ) '';
				$count = ( int ) 0;
				$duplicate = ( int ) 0;
				$duplicate_title = ( int ) 0;
				$duplicate_key = ( int ) 0;
				$new_cnt = ( int ) 0;
				$bp_id = ( int ) 0;
				$parent_id = ( int ) 0;
				
				$class = ( string ) '';
				$option_meta_key = ( string ) '';
				$meta_key = ( string ) '';
				if( !empty( $_POST['csds_addFieldOption'] ) ){
					$option_key = $_POST['csds_addFieldOption'];
					if( is_array( $option_key ) ){
						$option_key = $option_key[0];
					}
				}
				
				if( isset( $_POST['new_field_option_title'] ) ){
					$option_title = $_POST['new_field_option_title'];
				}
				
				if( isset( $_POST['new_field_option_key'] ) ){
					$actions = new CSDS_URA_ACTIONS();
					$results = $_POST['new_field_option_key'];
					$results = $actions->replace_spaces( $results );
					$option_meta_key = sanitize_key( $results );
				}
				
				//exit( $option_key );
				$ometa_key = ( string ) '';
				$object = $field->get_field_by_meta_key( $option_key );
				if( !empty( $object ) ){
					$ocnt = $field->options_count( $object->ID );
					$ometa_key = $object->ID.'_'.$ocnt;
				}
				$infm = new INPUT_NEW_FIELDS_MODEL();
				$dup_meta_key = $infm->duplicate_field_key( $ometa_key );
				if( $dup_meta_key != false ){
					$duplicate++;
					$duplicate_key++;
					//wp_die( '-----DUPLICATE:-----'.$dup );
				}
				$option_mk = $object->ID.'_'.$option_meta_key;
				$dup_key = $this->duplicate_option_keys( $object->ID, $option_mk );
				if( $dup_key != false ){
					$duplicate++;
					$duplicate_key++;
					//wp_die( '-----DUPLICATE:-----'.$dup );
				}
				$dup_title = $this->duplicate_option_names( $object->ID, $option_title );
				if( $dup_title != false ){
					$duplicate++;
					$duplicate_title++;
					//wp_die( '-----DUPLICATE:-----'.$dup );
				}
				if( !empty( $option_title ) && !empty( $option_mk ) ){
					if( !empty( $object ) ){
						//exit( print_r( $object ) );
						$options = $field->get_field_options_edit( $object->ID );
						$icount = count( $options );
						$count = $field->options_count( $object->ID );
						//$new_cnt = $count;
						$index = ( int ) 1;
						$max_length = ( int ) 29;
						if( is_array( $options ) ){
							foreach( $options as $option ){
								if( $option->field_name == $option_title ){
									$duplicate++;
									$duplicate_title++;
								}
								if( $option->meta_key == $option_mk ){
									$duplicate++;
									$duplicate_key++;
								}
								if( ( $index == $icount - 1 ) && $duplicate <= 0 ){
									if( $option->field_name != $option_title ){
										$parent_id = $object->ID;
									}
									if( $option->meta_key != $option_mk ){
										$meta_key = $option_mk;
									}
								}
								$index++;
							}
						}else{
							if( $option->field_name == $option_title ){
								$duplicate++;
								$duplicate_title++;
							}
							
							if( $option_mk == $option->meta_key ){
								$duplicate++;
								$duplicate_key++;
							}
							if( ( $index == $icount - 1 ) && $duplicate <= 0 ){
								if( $option->field_name != $option_title ){
									$parent_id = $object->ID;
								}
								if( $option->meta_key != $option_mk ){
									$meta_key = $option_meta_key;
								}
							}
						}
						if( $duplicate <= 0 ){
							if( is_plugin_active( $plugin ) ){
								$bp_id = $object->bp_ID;
								$count = $field->options_count( $object->ID );
								$bpf = new URA_BP_FUNCTIONS();
								$new_cnt = $bpf->bp_field_options_count( $bp_id );
								$bp_oid = $bpf->add_bp_field_option( $bp_id, $option_title, 0, $new_cnt, $order_by, $object->meta_key );
								//$meta_key = $option_meta_key;
								if( $bp_oid != 0 ){
									$new_field = new FIELDS_DATABASE();
									$new_field->meta_key = $ometa_key;
									$new_field->option_meta_key = $option_mk;
									$new_field->parent_id = $object->ID;
									$new_field->data_type = 'option';
									$new_field->field_name = $option_title;
									$new_field->field_description = $meta_key;
									$new_field->field_required = false;
									$new_field->registration_field = false;
									$new_field->is_default_option = false;
									
									$new_field->field_order = 0;
									$new_field->approve_view = false;
									$new_field->option_order = $count;
									$new_field->min_number = 0;
									$new_field->max_number = 0;
									$new_field->number_step = 0;
									$new_field->bp_id = $bp_oid;
									$new_field->bp_parent_id = $object->bp_ID;
									$new_field->bp_group_id = $object->bp_group_ID;
									$result = $field->insert_fields( $new_field );
									if( $result == 0 ){
										$msg = sprintf( __( 'Create Option Error: The Option %s Could Not Be Created, Please Try Again!', 'user-registration-aide' ), $option_title );
										$class = 'error';
									}else{
										$msg =  sprintf( __( 'Create Option Success: The Option %1s Was Successfully Created for %2s!', 'user-registration-aide' ), $option_title, $object->field_name );
										$class = 'updated';
									}
								}else{
									$msg =  sprintf( __( ' Error Messages - Unsuccessful BP Option Update: The Following Option: %s Not Added', 'user-registration-aide' ), $option_title );
									$class = 'error';
								}
							}else{
								//$meta_key = $option_meta_key;
								$count = $field->options_count( $object->ID );
								$new_field = new FIELDS_DATABASE();
								$new_field->meta_key = $ometa_key;
								$new_field->option_meta_key = $option_mk;
								$new_field->parent_id = $object->ID;
								$new_field->data_type = 'option';
								$new_field->field_name = $option_title;
								$new_field->field_description = $meta_key;
								$new_field->field_required = false;
								$new_field->registration_field = false;
								$new_field->is_default_option = false;
								$new_field->field_order = 0;
								$new_field->approve_view = false;
								$new_field->option_order = $count;
								$new_field->min_number = 0;
								$new_field->max_number = 0;
								$new_field->number_step = 0;
								$new_field->bp_id = 0;
								$new_field->bp_parent_id = $object->bp_ID;
								$new_field->bp_group_id = $object->bp_group_ID;
								$result = $field->insert_fields( $new_field );
								if( $result == 0 ){
									$msg = sprintf( __( 'Create Option Error: The Option %s Could Not Be Created, Please Try Again!', 'user-registration-aide' ), $option_title );
									$class = 'error';
								}else{
									$msg = sprintf( __( 'Create Option Success: The Option %1s Was Successfully Created for %2s!', 'user-registration-aide' ), $option_title, $object->field_name );
									$class = 'updated';
								}
							}
						}else{
							if( $duplicate_key == 0 && $duplicate_title >= 1 ){
								$msg = sprintf( __( 'Create Option Error: The Option Title %s Was a Duplicate, Option Title Already Exists!, Please Try Again!', 'user-registration-aide' ), $option_title );
								$class = 'error';
							}elseif( $duplicate_key >= 1 && $duplicate_title >= 1 ){
								$msg = sprintf( __( 'Create Option Error: The Option Title %1s & Option Key %2s Were Duplicates, Option Title and Key Already Exists!, Please Try Again!', 'user-registration-aide' ), $option_title, $option_meta_key );
								$class = 'error';	
							}elseif( $duplicate_key >= 1 && $duplicate_title == 0 ){
								$msg = sprintf( __( 'Create Option Error: The Option Key %s Was a Duplicate, Option Key Already Exists!, Please Try Again!', 'user-registration-aide' ), $option_meta_key );
								$class = 'error';	
							}
						}
					}
				}
				$msg1 = '<div id="message" class="'.$class.'"><p>'.$msg.'</p></div>';
			}
		}
		
		return $msg1;
	}
	
	/**	
	 * Function edit_new_field_options_model
	 * URA Edit New Fields Options Model handles options page updates
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns string $msg1 ( results of update updated or error  msg )
	 */
	
	function duplicate_option_names( $parent_id, $title ){
		$fdb = new FIELDS_DATABASE();
		$id = $fdb->options_duplicate_titles( $parent_id, $title );
		return $id;
	}
	
	/**	
	 * Function edit_new_field_options_model
	 * URA Edit New Fields Options Model handles options page updates
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns string $msg1 ( results of update updated or error  msg )
	 */
	
	function duplicate_option_keys( $parent_id, $key ){
		$fdb = new FIELDS_DATABASE();
		$id = $fdb->options_duplicate_keys( $parent_id, $key );
		return $id;
	}
	
}?>