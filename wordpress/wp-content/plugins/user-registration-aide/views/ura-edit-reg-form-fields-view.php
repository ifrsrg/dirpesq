<?php

/**
 * Class URA_EDIT_REGISTRATION_FORM_FIELDS_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_EDIT_REGISTRATION_FORM_FIELDS_VIEW
{

	/** 
	 * function edit_reg_form_fields_view
	 * Shows view for selecting fields for registration form
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function edit_reg_form_fields_view(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$field = new FIELDS_DATABASE();
			$ura_fields = $field->get_all_fields();
			$options = get_option( 'csds_userRegAide_Options' );
			?>
			<table class="newFields">
				<tr>
					<th colspan="3"><?php _e( 'Delete Fields & Change Fields Required for Registration Form:', 'user-registration-aide' );?> </th>
				</tr>
				<tr>
					<td><?php
					//if( !empty( $newFields ) ){
					if( !empty( $ura_fields ) ){
						echo '<p class="deleteFields">'.__( 'Delete Fields: <br/>Here you can select the new additional fields you added that you want to delete.', 'user-registration-aide' ).'</p>';
						echo '<select name="deleteNewFields" id="csds_userRegMod_delete_Select" title="'.__( 'Please choose a field to delete here, you can only select one field at a time to delete however', 'user-registration-aide' ).'" size="8"  class="deleteFields">';
						
						foreach( $ura_fields as $object ){
							echo '<option value="'.$object->meta_key.'">'.$object->field_name.'</option>';
						}
						echo '</select>';
						?>
						<br/>
						<input type="submit" class="button-primary" name="delete_field" value="<?php _e( 'Delete New Field', 'user-registration-aide' );?>"/></p>
						<?php	
					}else{
						echo '<p class="deleteFields">'.__( 'No new fields currently exist, you have to add new fields on the main page before you can delete any!', 'user-registration-aide' ).'</p>';
					}?>
				</td>
				<td>
				<p class="adminPage"><?php _e( 'By default, Wordpress will only require an email address and username to register an account. Here, you can select additional fields that will be added for new user registration.', 'user-registration-aide' );?>
				<br/>
				</p>
				<p class="adminPage"><?php _e( 'Select Additional Fields to add to the Registration Form:', 'user-registration-aide' );?>
				<br/>
				<select name="additionalFields[]" id="csds_userRegMod_Select" title="<?php _e( 'You can select as many fields here as you want, just hold down the control key while selecting multiple fields. These fields are required on the registration form, so if you can do without them and just have them on the user profile page then leave them out of the registration form!', 'user-registration-aide' );?>" size="8" multiple style="height:100px">
				<?php
				$regFields = get_option( 'csds_userRegAide_registrationFields' );
				$knownFields = get_option( 'csds_userRegAide_knownFields' );
				$field = new FIELDS_DATABASE();
				$ura_fields = $field->get_all_fields(); 
				if( !empty( $knownFields ) ){
					if( !empty( $regFields ) ){
						if( is_array( $regFields ) ){
							foreach( $knownFields as $key1 => $value1 ){
								if( array_key_exists( $key1, $regFields ) ){
									$selected = "selected=\"selected\"";
								}else{
									$selected = NULL;
								}
								
								?>
								<option value="<?php echo $key1 ;?>" <?php echo $selected ;?> ><?php _e( $value1, 'user-registration-aide' );?></option>
								
							<?php
							}
							
						}else{
							//exit();
						}
					}else{
						foreach( $knownFields as $key1 => $value1 ){
							$selected = NULL;
						
							?>
							<option value="<?php echo $key1 ;?>" <?php echo $selected ;?> ><?php _e( $value1, 'user-registration-aide' );?></option>
							
							<?php
						}
						//echo "<option value=\"$key1\" $selected >$value1</option>";
							
					}
					
					if( !empty( $ura_fields ) ){
						//foreach( $newFields as $key2 => $value2 ){
						foreach( $ura_fields as $object ){
							$meta_key = $object->meta_key;
							$name = $object->field_name;
							//$reg_form = $object->registration_form;
							if( $object->registration_field == 1 ){
								//exit( "SELECTED" );
								$selected = "selected=\"selected\"";
							}else{
								//exit( "UNSELECTED" );
								$selected = NULL;
							}
							?>
							<option value="<?php echo $meta_key ;?>" <?php echo $selected ;?> ><?php _e( $name, 'user-registration-aide' );?></option>
							
							<?php
							//echo "<option value=\"$meta_key\" $selected >$name</option>";
						
						}
					}
				}
				?>	
				</select>
				<br/>
				<b><?php _e( 'Hold down "Ctrl" button on keyboard to select or unselect multiple options!', 'user-registration-aide' );?>
				</b>
				</p>
				<div class="submit">
				<input type="submit" name="select_none" class="button-primary" value="<?php _e('Select None', 'user-registration-aide'); ?>" />
				</div>
			<div class="submit"><input type="submit" class="button-primary" name="reg_fields_update" value="<?php _e('Update Registration Form Fields', 'user-registration-aide');?>"/></div>
				</td>
				<td>
				<p class="adminPage"><?php _e( 'Here you can select whether Registration Form Fields are required or not', 'user-registration-aide' );?>
				<br/>
				</p>
				<p class="adminPage"><?php _e( 'Select Registration Form Fields That Will NOT BE REQUIRED for Users to Fill out When Registering:', 'user-registration-aide' );?>
				<br/>
				<select name="requiredFields[]" id="required_fields" title="<?php _e( 'You can select as many fields here as you want, just hold down the control key while selecting multiple fields. Selecting these fields makes them so they are NOT REQUIRED FIELDS on the registration form!', 'user-registration-aide' );?>" size="8" multiple style="height:100px">
				<?php
				$optional_fields = get_option( 'csds_ura_optionalFields' );
				$regFields = get_option( 'csds_userRegAide_registrationFields' );
				$knownFields = get_option( 'csds_userRegAide_knownFields' );
				$field = new FIELDS_DATABASE();
				$ura_fields = $field->get_registration_fields(); 
				$opt_fields = $field->get_optional_fields();
				if( !empty( $regFields ) ){
					if( is_array( $regFields ) ){
						$cnt = count( $regFields );
						foreach( $knownFields as $key1 => $value1 ){
							if( !empty( $regFields ) ){
								
								if( array_key_exists( 'user_pass', $regFields ) && $cnt == 1 && empty( $ura_fields ) ){
									if( $key1 == 'user_pass' ){
										?>
										<option value="<?php _e( 'no_fields_0', 'user-registration-aide' );?>"><?php _e( 'No Registration Form Fields', 'user-registration-aide' );?></option>
										<option value="<?php _e( 'no_fields_1', 'user-registration-aide' );?>"><?php _e( 'Have Been Added Yet', 'user-registration-aide' );?></option>
										<option value="<?php _e( 'no_fields_1', 'user-registration-aide' );?>"><?php _e( 'Except for the Password!', 'user-registration-aide' );?></option>
										<?php
									}
								}else{
									if( !empty( $optional_fields ) ){
										if( array_key_exists( $key1, $optional_fields ) ){
											$selected = "selected=\"selected\"";
										}else{
											$selected = NULL;
										}
									}else{
										$selected = NULL;
									}
									if( $key1 != 'user_pass' ){
										if( array_key_exists( $key1, $regFields ) ){
											?>
											<option value="<?php echo $key1 ;?>" <?php echo $selected ;?> ><?php _e( $value1, 'user-registration-aide' );?></option>
											<?php
										}
									}
								}
							}else{
								_e( 'There currently are no fields selected for the Registration Form, Select some fields for the Registration Form first before you can make them optional or required!', 'user-registration-aide' );
							}
								
								
						}
						
						
					}else{
						//exit();
					}
				}
				if( !empty( $ura_fields ) ){
					foreach( $ura_fields as $object ){
						$meta_key = $object->meta_key;
						$name = $object->field_name;
						//$reg_form = $object->registration_field;
						if( $object->field_required == 0 ){
							//exit( "SELECTED" );
							$selected = "selected=\"selected\"";
						}else{
							//exit( "UNSELECTED" );
							$selected = NULL;
						}
						if( $object->registration_field == 1 ){
							?>
							<option value="<?php echo $meta_key ;?>" <?php echo $selected ;?> ><?php _e( $name, 'user-registration-aide' );?></option>
							<?php
						}
						
					} // end foreach
				}else{
					if( empty( $regFields ) ){
						?>
						<option value="<?php _e( 'no_fields_0', 'user-registration-aide' );?>"><?php _e( 'No Registration Form Fields', 'user-registration-aide' );?></option>
						<option value="<?php _e( 'no_fields_1', 'user-registration-aide' );?>"><?php _e( 'Have Been Added Yet!', 'user-registration-aide' );?></option>
						<?php
					}
				}
				?>							
				</select>
				<br/>
				<b><?php _e( 'Hold down "Ctrl" button on keyboard to select or unselect multiple options!', 'user-registration-aide' );?>
				</b>
				</p>
				<div class="submit">
				<input type="submit" name="select_required_none" class="button-primary" value="<?php _e( 'Select None', 'user-registration-aide' ); ?>" /></div>
				<div class="submit">
				<input type="submit" class="button-primary" name="required_fields_update" value="<?php _e( 'Required Fields Update', 'user-registration-aide' );?>"/>
				</div>
				</td>
			</tr>
			</table>
			<br/>
			<table class="style">	
			<tr>
				<th colspan="3"><?php _e( 'Registration Form Field Title Punctuation', 'user-registration-aide' );?> </th>
			</tr>
			<tr>
				<td colspan="2">
				<?php _e( 'Use the * ( Asterisk ) on the Registration Form to Designate a Field is Required', 'user-registration-aide' );?>
				</td>
				<td>
				<span title="<?php _e( 'Select this option to use the * ( Asterisk ) to Designate a Required Field on the Registration Form( Field Title:* ( if using colon, you can remove that too! ), otherwise Field Title* )', 'user-registration-aide' );?>">
				<input type="radio" name="use_asterisk" id="use_asterisk" value="1" <?php
				if ( $options['designate_required_fields'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
				</span>
				<span title="<?php _e( 'Select this option not to use the * ( Asterisk ) to Designate a Required Field on the Registration Form ( Field Title: ( if using colon, you can remove that too! ), otherwise Field Title )',  'user-registration-aide' );?>">
				<input type="radio" name="use_asterisk" id="use_asterisk" value="2" <?php
				if ( $options['designate_required_fields'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?>
				</span>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<?php _e( 'Use the : ( Colon ) on the Registration Form after Field Title', 'user-registration-aide' );?>
				</td>
				<td>
				<span title="<?php _e( 'Select this option to use the : ( Colon ) After the Field Title on the Registration Form ( Field Title: )', 'user-registration-aide' );?>">
				<input type="radio" name="use_colon" id="use_colon" value="1" <?php
				if ( $options['reg_form_use_colon'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
				</span>
				<span title="<?php _e( 'Select this option to not use the : ( Colon ) After the Field Title on the Registration Form ( Field Title )',  'user-registration-aide' );?>">
				<input type="radio" name="use_colon" id="use_colon" value="2" <?php
				if ( $options['reg_form_use_colon'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?>
				</span>
				</td>
			<tr>
				<td colspan="3">
				<input type="submit" class="button-primary" name="update-asterisk-colon" value="<?php _e( 'Update Field Title Punctuation Option', 'user-registration-aide' );?>"/>
				</td>
			</tr>
			</table>
			<br/>
		<?php
		}
	}
	
	
} // end class