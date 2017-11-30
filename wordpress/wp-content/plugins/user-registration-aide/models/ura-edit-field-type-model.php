<?php

/**
 * Class EDIT_FIELD_TYPE_MODEL
 *
 * @category Class
 * @since 1.5.1.4
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class EDIT_FIELD_TYPE_MODEL
{

	/** 
	 * function edit_new_field_type_model
	 * URA Edit Fields Type Model handles options page updates for changing field types
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns string $msg ( results of function success or failure ) 
	*/
	
	function edit_new_field_type_model(){
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
			$err_msg = ( string ) '';
			$post_msg = ( string ) '';
			$submit = ( int ) 0;
			$data_types = array();
			$types_changed = ( int ) 0;
			if( isset( $_POST['change_field_type'] ) ){
				$field_type = ( string ) '';
				$field_id = ( int ) 0;
			
				$bpf = new URA_BP_FUNCTIONS();
				$bp_types = $bpf->options_fields_array();
				$options_array = $bpf->options_fields_array();
				
				$index = ( int ) 0;
				$fields = ( string ) '';
				$types = ( string ) '';
				$class = ( string ) '';
				
				$changed = ( int ) 0;
				$results = 0;
				$ura_results = ( boolean ) false;
				$msg = __( 'Field Type Successfully Changed for ', 'user-registration-aide' );
				$msg1 = __( 'Field Types Successfully Changed for ', 'user-registration-aide' );
				if( !empty( $ura_fields ) ){
					foreach( $ura_fields as $object ){
						$field_key = $object->meta_key.'_data_type';
						$meta_key = $object->meta_key;
						$old_type = $object->data_type;
						if( isset( $_POST[$field_key] ) ){
							$data_type = $_POST[$field_key];
							$id = $object->bp_ID;
							if( !array_key_exists( $old_type, $options_array ) ){
								if( $data_type != $old_type ){
									$changed++;
									$results = $field->update_fields( $meta_key, 'data_type', $data_type );
									if( $results == 1 ){
										if( $old_type == 'number' ){
											$data_results = $field->data_type_change_number( $meta_key );
										}
										if( is_plugin_active( $plugin ) ){
											$field_type = $_POST[$field_key];
											$bp_results = $bpf->update_bp_field_type( $id, $data_type );
											if( $bp_results != 0 ){
												if( $index == 0 ){
													$fields = 'Field Name: '.$object->field_name.' - Field Type: '.$field_type;
													$class = 'updated';
												}else{
													$fields .= '<br/>';
													$fields .= ' - Field Name: '.$object->field_name.' - Field Type: '.$field_type;
												}
											}else{
												$msg = __( '**Warning: BP Field Type Could Not Be Changed!', 'user-registration-aide' );
												$class = 'error';
											}
										}
										if( $changed <= 1 ){
											$fields = 'Field Name: '.$object->field_name.' - Field Type: '.$data_type;
										}elseif( $changed >= 2 ){
											$fields .= '<br/>';
											$fields .= ' Field Name: '.$object->field_name.' - Field Type: '.$data_type;
										}
										$types_changed++;
										$data_types[] = $data_type;
										$class = 'updated';
									}else{
										$msg = __( '**Warning: Field Type Could Not Be Changed!', 'user-registration-aide' );
										$class = 'error';
									}
									$index++;
								}
							}elseif( array_key_exists( $old_type, $options_array ) ){
								$parent_id = $object->ID;
								$bp_parent_id = $object->bp_ID;
								
								if( $data_type != $old_type ){
									//$changed++;
									if( !array_key_exists( $data_type, $options_array ) ){
										$ura_results = $field->update_data_type_delete_ura_options( $parent_id );
									}else{
										$ura_results = true;
									}
									if( !$ura_results == false ){
										if( $data_type != $old_type ){
											$results = $field->update_fields( $meta_key, 'data_type', $data_type );
											$changed++;
											if( !$results == false ){
												if( is_plugin_active( $plugin ) ){
													$type = $_POST[$field_key];
													$bpid = $object->bp_ID;
													$bp_results = $bpf->update_bp_field_type( $bpid, $type );
													if( !$bp_results == false ){
														if( $index == 0 ){
															$fields = 'Field Name: '.$object->field_name.' - Field Type: '.$type;
															$class = 'updated';
														}else{
															$fields .= '<br/>';
															$fields .= ' - Field Name: '.$object->field_name.' - Field Type: '.$type;
															$class = 'updated';
														}
													}else{
														$msg = __( '**Warning: BP Field Type Could Not Be Changed!', 'user-registration-aide' );
														$class = 'error';
													}
												}
												if( $index == 0 ){
													$fields = 'Field Name: '.$object->field_name.' - Field Type: '.$data_type;
													$class = 'updated';
												}else{
													$fields .= '<br/>';
													$fields .= ' - Field Name: '.$object->field_name.' - Field Type: '.$data_type;
													$class = 'updated';
												}
												$types_changed++;
												$data_types[] = $data_type;
												$class = 'updated';
											}else{
												$msg = __( '**Warning: Field Type Could Not Be Changed!', 'user-registration-aide' );
												$class = 'error';
											}
											$index++;
										}
									}
								}
							}
						}
					}
					if( $changed === 0 ){
						$msg = __( ' No Field Type Changes Made! ', 'user-registration-aide' );
						$class = 'error';
					}
				}else{
					$msg = __( ' No New Fields Have Been Created Yet! ', 'user-registration-aide' );
					$class = 'error';
				}
				if( $class != 'error' && $changed >= 1 ){
					$ura_msg = new CSDS_URA_MESSAGES();
					$match = ( int ) 0;
					$option_fields = array();
					$option_fields = apply_filters( 'get_option_fields_array', $option_fields );
					
					foreach( $data_types as $data_key => $data_value ){
						if( array_key_exists( $data_value, $option_fields ) ){ 
							$msg = __( 'You changed a data type to a type that requires options and do not have any options for it.</p><p>To use it properly please add some options or change the field type to one that does not require options!', 'user-registration-aide' );
							$class = 'error';
						}
					}
					
				}
				$msg = $msg.$fields;
				$msg2 = '<div id="message" class="'.$class.'"><p>'.$msg.'</p></div>';
			}
		}
		return $msg2;
	}
}