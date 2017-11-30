<?php

/**
 * Class URA_FIELD_ORDER_TITLE_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_FIELD_TYPE_VIEW
{

	/**	
	 * Function field_data_type_view
	 * URA field type editing view
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns 
	*/
	
	function field_data_type_view(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$field = new FIELDS_DATABASE();
			?>
			<table class="style">	
				<tr>
					<th colspan="3"><?php _e( 'Change Field Type: ', 'user-registration-aide' );?></th>
				</tr>
				<tr>
					<th colspan="2"><?php _e( 'Field Title: ', 'user-registration-aide' );?></th>
					<th><?php _e( 'Field Type: ', 'user-registration-aide' );?></th>
				</tr>
				<?php
				$nfc = new INPUT_NEW_FIELDS_MODEL();
				$input = $nfc->input_options_array();
				$ura_fields = $field->get_all_fields();
				if( !empty( $ura_fields ) ){
					
					foreach( $ura_fields as $objects ){
						?>
						<tr>
							<td class="fieldName" colspan="2">
							<?php
								echo '<label for="'.$objects->meta_key.'">'. _e( $objects->field_name, 'user-registration-aide').'</label>';
							?>
							</td>
							<td>
							<select  class="fieldOrder" name="<?php echo $objects->meta_key.'_data_type'; ?>" title="<?php _e( 'Select the field type to change to', 'user-registration-aide' );?>">
							<?php
								foreach( $input as $key => $title ){
									if( $objects->data_type == $key ){
										$selected = "selected=\"selected\"";
										}else{
										$selected = null;
									}
									echo "<option value=\"$key\" $selected >$title</option>";
								}
								
							?>
							</select>
							</td>
						</tr>
						<?php
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
					<?php
				}?>
				<tr>
					<td colspan="3">
					<div class="submit"><input type="submit" class="button-primary" name="change_field_type" value="<?php _e( 'Change Field Type', 'user-registration-aide' );?>"  /></div>
					</td>
				</tr>
			</table>
				
			<?php
		}
	}
}