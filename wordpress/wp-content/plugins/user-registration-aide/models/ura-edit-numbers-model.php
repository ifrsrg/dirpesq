<?php

/**
 * Class EDIT_NUMBERS_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class EDIT_NUMBERS_MODEL
{
	
	/**	
	 * Function numbers_type_editing_model
	 * URA Edit Field Type Numbers Controller handles number editing section views
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg1 ( results of function updated or error  message to display to user )
	*/
	
	function numbers_type_editing_model( $msg ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$msg = ( string ) '';
			$pre_msg = ( string ) '';
			$post_msg = ( string ) '';
			$class = ( string ) '';
			$min = ( boolean ) false;
			$max = ( boolean ) false;
			$step = ( boolean ) false;
			$error = ( int) 0;
			$min_numb = ( int ) 0;
			$max_numb = ( int ) 0;
			$step_numb = ( int ) 0;
			$field = new FIELDS_DATABASE();
			$fields_updated = ( int ) 0;
			$updates = array();
			$updated = ( int ) 0;
			$not_updated = ( int ) 0;
			$submit = ( int ) 0;
			$ura_fields = $field->get_number_fields();
			if ( isset( $_POST['number_options_update'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-newFields'], 'csds-newFields' ) ){	
					$results1 = '';
					if( current_user_can( 'activate_plugins', $current_user->ID ) ){
						foreach( $ura_fields as $object ){
							$updated = 0;
							$error = 0;
							$meta_key = $object->meta_key;
							$title = $object->field_name;
							$minNumb = ( int ) $object->min_number;
							$maxNumb = ( int ) $object->max_number;
							$stepNumb = ( int ) $object->number_step;
							$min_name = $object->meta_key.'_min';
							$max_name = $object->meta_key.'_max';
							$step_name = $object->meta_key.'_step';
							$min = ( int ) $_POST[$min_name];
							$max = ( int ) $_POST[$max_name];
							$step = ( int ) $_POST[$step_name];
							if( $max < $min ){
								$msg = __( '*** Maximum Number Less Than Minimum Number!***', 'user-registration-aide');
								$class = 'error';
								$msg1 = '<div id="message" class="'.$class.'"><p>'.$msg.'</p></div>';
								return $msg1;
							}
							if( $step > ( $max - $min ) ){
								$msg = __( '*** Step Number Greater Than Difference Between Maximum Number and Minimum Number Which Will Throw Errors!***', 'user-registration-aide');
								$class = 'error';
								$msg1 = '<div id="message" class="'.$class.'"><p>'.$msg.'</p></div>';
								return $msg1;
							}
							
							if( $step <= 0 ){
								$msg = __( '*** Step Number is Equal to or Smaller Than 0!***', 'user-registration-aide');
								$class = 'error';
								$msg1 = '<div id="message" class="'.$class.'"><p>'.$msg.'</p></div>';
								return $msg1;							
							}
							if( !empty( $_POST[$min_name] ) ){
								$min_numb = $_POST[$min_name];
								if( $min_numb != $minNumb ){
									$updated++;
								}
								$field->update_fields( $meta_key, 'min_number', $min_numb );
								$min = true;
							}else{
								$error++;
							}
							if( !empty( $_POST[$max_name] ) ){
								$max_numb = $_POST[$max_name];
								if( $max_numb != $maxNumb ){
									$updated++;
								}
								$field->update_fields( $meta_key, 'max_number', $max_numb );
								$max = true;
							}else{
								$error++;
							}
							if( !empty( $_POST[$step_name] ) ){
								$step_numb = $_POST[$step_name];
								if( $step_numb != $stepNumb ){
									$updated++;
								}
								$field->update_fields( $meta_key, 'number_step', $step_numb );
								$step = true;
							}else{
								$error++;
							}
							if( $updated >= 1 ){
								$updates[$meta_key] = $title;
								$fields_updated++;
							}else{
								$not_updated++;
							}
							
						}
						//if( $error == 0 ){
						if( $fields_updated >= 1 ){
							$msg = __( 'The Following Number Type Fields Were Updated: ', 'user-registration-aide');
							foreach( $updates as $key => $title ){
								$msg .= ' - '.$title;
							}
							$class = 'updated';
						}else{
							$msg = __( '*** No Number Fields Were Changed!***', 'user-registration-aide');
							$class = 'error';
						}
					}
					$msg1 = '<div id="message" class="'.$class.'"><p>'.$msg.'</p></div>';
					
				}
				$submit++;
			}
		}
		return $msg1;
		
	}
	
}