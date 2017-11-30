<?php

/**
 * Class URA_ADD_OPTIONS_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_ADD_OPTIONS_VIEW
{

	/**	
	 * Function add_new_options_view
	 * URA Add new field options settings view
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns 
	*/
	
	function add_new_options_view(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$field = new FIELDS_DATABASE();
			$ura_fields = $field->get_option_parent_fields();
			?>
			<table class="style">	
				<tr>
					<th colspan="3"><?php _e( 'Add Additional Field Options: ', 'user-registration-aide' );?></th>
				</tr>
				<tr>
					<th><?php _e( 'New Field Option Parent Title: ', 'user-registration-aide' );?></th>
					<th colspan="2"><?php _e( 'Field Option Key and Title: ', 'user-registration-aide' );?></th>
				</tr>
				
				<?php
					$bpf = new URA_BP_FUNCTIONS();
					$bp_types = $bpf->options_fields_array();
					$type = ( string ) '';
					unset( $bpf );
					if( !empty( $ura_fields ) ){
						?>
						<tr>
							<td class="fieldName">
							<label for="csds_addFieldOption"><?php _e( 'Select the parent title of the field you want to add an option to here:', 'user-registration-aide' );?></label>
							<br/>
							<select  class="fieldOrder" name="csds_addFieldOption" title="<?php _e( 'Select the parent field to add the field option to here!', 'user-registration-aide' );?>">
							<?php
								foreach( $ura_fields as $object ){
									
									$type = $object->data_type;
									if( array_key_exists( $type, $bp_types ) ){
										echo '<option value="'.$object->meta_key.'">'.$object->field_name.'</option>';
									}
									
									
								}
							?>
							</select>
							</td>
							<td class="newOption" colspan="2">
							<label for="new_field_option_key">Field Option Key:</label>
							<input type="text" class="newOption" name="new_field_option_key" id="new_field_option_key" title="<?php _e( 'Enter your new field option key here. Please make sure you have selected the correct Parent Title!', 'user-registration-aide' );?>" value="" maxlength="30" />
							<br/>
							<label for="new_field_option_title">Field Option Name:</label>
							<input type="text" class="newOption" name="new_field_option_title" id="new_field_option_title" title="<?php _e( 'Enter your new field option name here. Please make sure you have selected the correct Parent Title!', 'user-registration-aide' );?>" value="" />
							</td>
						</tr> 
					<?php
						
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
						<div class="submit">
						<input type="submit" class="button-primary" name="add_field_option" value="<?php _e( 'Add Field Option', 'user-registration-aide' );?>"  />
						</div>
					</td>
				</tr>
			</table>
		<?php
		}
		
	}
	
}