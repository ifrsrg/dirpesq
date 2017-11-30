<?php

/**
 * Class OPTION_ORDER_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class OPTION_ORDER_MODEL
{
	
	/**	
	 * function options_order_model
	 * Handles option order fields updates
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg1 ( results of function updated or error  message to display to user )
	*/
	
	function options_order_model( $msg ){
		global $current_user, $bp;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$bp_options = ( string ) '';
			$pre_msg = ( string ) '';
			$post_msg = ( string ) '';
			$msg = ( string ) '';
			$field = new FIELDS_DATABASE();
			$fields = $field->get_all_fields();
			$temp = array();
			$submit = ( int ) 0;
			// do stuff to handle updates
			if( isset( $_POST['option_order_update'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
					$msg_wrap = '<div id="message" class="updated"><p>';
					$msg = ( string ) '';
					$msg_end = '</p></div>';
					$err = ( int ) 0;
					$no_change = ( int ) 0;
					$change = ( int ) 0;
					$same = ( int ) 0;
					$cnt = ( int ) 0;
					$field_order_error = ( int ) 0;
					$checked = ( int ) 0;
					$old_key = array();
					$next = ( int ) 0;
					$bp_cnt = ( int ) 0;
					foreach( $fields as $object ){
						$old_key[$cnt] = $object->meta_key;
						$meta_key = $object->meta_key;
						$options = $field->get_total_field_options( $meta_key );
						if( !empty( $options ) ){
							
							foreach( $options as $obj ){
								//foreach( $_POST 
							}
							
							foreach( $options as $obj ){
								$option_key = $obj->meta_key;
								$order =  $_POST[$option_key];
								$temp[$option_key] = $order;
							}
							
							foreach( $temp as $key => $value ){
								foreach( $temp as $key1 => $value1 ){
									if( ( $key != $key1 ) && ( $value == $value1 ) ){
										$field_order_error = 1;
									}
								}
							}
							
							if( $field_order_error != 1 ){// Checking for duplicates before updating
								if( !empty( $temp ) ){
									$fieldOrder = $temp;
									asort( $fieldOrder );
									foreach( $fieldOrder as $key => $value ){
										$field->update_option_order( $key, $value );
									}
									unset( $temp, $fieldOrder );
									$msg = __( 'New Options Order Updated Successfully!', 'user-registration-aide' );
									$class = 'updated';
								}
							}else{
								$msg = __( '***Error Updating New Options Order, Two or More Field Options Have the Same Order!***', 'user-registration-aide' );
								$class = 'error';
							}
							$cnt++;
							
							if ( class_exists( 'BuddyPress' ) ){
								$id = $object->ID;
								$results = do_action( 'update_bp_option_order', $id );
								if( is_wp_error( $results ) ){
									$codes = $results->get_error_codes();
									if( !empty( $codes ) ){
										foreach( $codes as $i => $code ){
											$msg .= ' - '.$code.' - ';
										}
									}
								}else{
									if( $bp_cnt <= 1 ){
										$msg .= __( ' -- Buddy Press Options Orders Updated! ', 'user-registration-aide' );
										$class = 'updated';
									}
								}
								$bp_cnt++;
							}
						}
						
					}
					$pre_msg = '<div id="message" class="'.$class.'"><p>';
					$post_msg .= '</p></div>';
					$msg1 = '<div id="message" class="'.$class.'"><p>'.$msg.'</p></div>';
					return $msg1;
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
			}
		}
	}
}
	