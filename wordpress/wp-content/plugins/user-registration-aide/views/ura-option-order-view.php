<?php

/**
 * Class OPTION_ORDER_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class OPTION_ORDER_VIEW
{
	
	/** 
	 * function options_order_viewer
	 * Handles new question fields input admin form options orders and settings view
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function options_order_viewer(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$newQuestionText = ( string ) '';
			$newQuestionKey = ( string ) '';
			$question_type = ( string ) '';
			$selected = ( string ) '';
			//$msg = ( string ) '';
			$field = new FIELDS_DATABASE();
			$infm = new INPUT_NEW_FIELDS_MODEL();
			$ura_fields = $field->get_all_fields();
			$option_fields = array();
			$option_fields = apply_filters( 'get_option_fields_array', $option_fields );
			$span = array( 'regForm', 'Edit Option Orders Here:', 'user-registration-aide');
		
			$tab = 'edit_new_fields';
			$section = 'option_order';
			?>
				
			<table class="style">	
				<tr>
					<th colspan="3" class="adminPage"><?php _e( 'Edit Options Order Here:', 'user-registration-aide' );?> </th>
				</tr>
				<tr>
					<th class="adminPage"><?php _e( 'Parent Field Name:', 'user-registration-aide' );?> </th>
					<th class="adminPage" colspan="2"><?php _e( 'Option Order:', 'user-registration-aide' );?> </th>
				</tr>
				<?php
				if( !empty( $ura_fields ) ){
					foreach( $ura_fields as $object ){
						$field_key = $object->meta_key;
						$data_type = $object->data_type;
						$title = $object->field_name;
						$id = $object->ID;
						$count = $field->options_count( $id );
						$options = $field->get_total_field_options( $field_key );
						if( !empty( $options ) ){
							?>
							<tr>
								<td align="left">
								<fieldset>
								<legend>Field Options Parent Title: </legend>
								<?php
								echo '<input  style="width: 100%;" type="text" title="'.__( 'Parent Title for options - Read Only! Change Title above!', 'user-registration-aide' ) . '" value="'. $title . '" name="'. $field_key . '_key" id="'. $field_key . '_key" readonly />';
								?>
								</fieldset>
								</td>
								<td align="left" colspan="2" title="<?php _e( 'Select the new field order here for your options', 'user-registration-aide' );?>">
								<?php
								foreach( $options as $objects ){ 
									$option_key = $objects->meta_key;
									$length = strlen( $option_key );
									$width = '50px';
									?>
									<fieldset>
									<legend><?php _e( $objects->field_name, 'user-registration-aide' ) ;?>: </legend>
									<select name="<?php echo $option_key; ?>" id="<?php echo $option_key; ?>" style="width: <?php echo $width; ?>" >
									<?php
									$i = count( $options );
									for( $ii = 1; $ii <= $i; $ii++ ){
										if( $ii == $objects->option_order ){
											echo '<option selected="'.$objects->meta_key.'" >'.$objects->option_order.'</option>';
										}else{
											echo '<option value="'.$ii.'">'.$ii.'</option>';
										}									
									}
									?>
									
									</select>
									</fieldset>
									<?php
								}
								?>
								</td>
							</tr>
							<?php
						}elseif( empty( $options ) && array_key_exists( $data_type, $option_fields ) ){
							?>
							<tr>
								<td align="left">
								<fieldset>
								<legend>Field Options Parent Title: </legend>
								<?php
								echo '<input  style="width: 100%;" type="text" title="'.__( 'Parent Title for options - Read Only! Change Title above!', 'user-registration-aide' ) . '" value="'. $title . '" name="'. $field_key . '_key" id="'. $field_key . '_key" readonly />';
								?>
								</fieldset>
								</td>
								<td align="left" colspan="2">
								
								<fieldset>
								<legend><?php _e( 'No Options Exist', 'user-registration-aide' ) ;?>: </legend>
								<?php _e( 'This Field Currently Has no Options! Please add Some Options Below!', 'user-registration-aide' ) ;?>
								</fieldset>
								</td>
							</tr>
							<?php
						}
					}
				}else{
					?>
					<tr>
						<td class="fieldName" colspan="3">
						<p class="deleteFields">
						<?php _e( 'No new fields currently exist, you have to add new fields on the main page before you can change the order!', 'user-registration-aide' ); ?>
						</p>
						</td>
					</tr>
					</table>
					<?php
				}
				?>
				<tr>
					<td colspan="3">
					<input type="submit" class="button-primary" name="option_order_update" value="<?php _e( 'Update Option Order', 'user-registration-aide' ); ?>" />
					</td>
				</tr>
			</table>
			<br/>
			<?php
			//do_action('end_wrapper'); // adds all closing tags for page wrappers
		}
	}
}
	