<?php

/**
 * Class INPUT_NEW_FIELDS_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class INPUT_NEW_FIELDS_VIEW
{
	
	/**	
	 * function new_fields_input_viewer
	 * Handles new fields input admin form options and settings view
	 * @since 1.5.2.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $msg
	 * @returns 
	*/

	function new_fields_input_viewer( $action ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			do_action( 'database_update' );
			$class = ( string ) 'BuddyPress';
			$plugin = ( string ) 'buddypress/bp-loader.php';
			$newField = ( string ) '';
			$newFieldKey = ( string ) '';
			$reg_form_use = ( string ) '';
			$required = ( string ) '';
			$field_data_type = ( string ) '';
			$fieldOptions = ( string ) '';
			$msg1 = ( string ) '';
			$minNumb = ( int ) 0;
			$maxNumb = ( int ) 0;
			$stepNumb = ( int ) 0;
			$nfc = new INPUT_NEW_FIELDS_MODEL();
			$input = $nfc->input_options_array();
			$span = array( 'regForm', 'Add Custom Fields With Own Input Option Here:', 'user-registration-aide');
			
			// re-enteringfield data if there was an error
			if( empty( $action ) || $action == 'error' ){
				if( !empty( $_POST['ura_newField'] ) ){
					$newField = sanitize_text_field( $_POST['ura_newField'] );
				}
				if( !empty( $_POST['ura_newFieldKey'] ) ){
					$newFieldKey = sanitize_key( $_POST['ura_newFieldKey'] );
				}
				if( !empty( $_POST['reg_form_use'] ) ){
					$reg_form_use = sanitize_key( $_POST['reg_form_use'] );
				}
				if( !empty( $_POST['required'] ) ){
					$required = sanitize_key( $_POST['required'] );
				}
				if( !empty( $_POST['input_type'] ) ){
					$field_data_type = sanitize_key( $_POST['input_type'] );
				}
				if( !empty( $_POST['ura_fieldOptions'] ) ){
					$fieldOptions = sanitize_text_field( $_POST['ura_fieldOptions'] );
				}
				if( !empty( $_POST['ura_minNumb'] ) ){
					$minNumb = sanitize_text_field( $_POST['ura_minNumb'] );
				}
				if( !empty( $_POST['ura_maxNumb'] ) ){
					$maxNumb = sanitize_text_field( $_POST['ura_maxNumb'] );
				}
				if( !empty( $_POST['ura_stepNumb'] ) ){
					$stepNumb = sanitize_text_field( $_POST['ura_stepNumb'] );
				}
			}
			
			$select_options = array(
				'1'	=>	__( 'Yes', 'user-registration-aide' ),
				'0'	=>	__( 'No', 'user-registration-aide' )
			);
			
			// Shows Aministration Page 
			
			?>
			<br />
				
			<table class="newInputFields">
				<tr>
					<th colspan="4"  class="newInputFields"><?php _e( 'Create Custom Fields Of Various Input Types Here:', 'user-registration-aide' );?> </th>
				</tr>
				<tr>
				
				<?php
					// Form for adding new fields for users profile and registration
					?>							
					<td colspan="4">
					<?php _e( 'Here is where you can enter your custom additional fields, the key name should be lower ', 'user-registration-aide' ); ?>
					<br/>
					<?php _e( 'case and correlate to the field name that the user sees on the registration form and profile.','user-registration-aide' ); ?>
					<br/>
					<?php _e( 'Examples:','user-registration-aide' ); ?>
					</td>
				</tr>
			</table>
			<br/>
				
			<table class="newInputFields">
				<tr>
					<td width="25%"><?php _e( 'Field Key Name: dob', 'user-registration-aide' );?></td>
					<td width="25%"><?php _e( 'Field Type: Select (Drop Down)', 'user-registration-aide' );?></td>
					<td width="25%"><?php _e( 'Registration Form: Yes/No', 'user-registration-aide' );?></td>
					<td width="25%"><?php _e( 'Field Required: Yes/No', 'user-registration-aide' ); ?></td>
				</tr>
				<tr>
					<td align="left">
					<fieldset>
					<legend><?php _e( 'Field Key Name:', 'user-registration-aide' );?></legend>
					<?php
					echo '<input  style="width: 100%;" type="text" title="'.__( 'Enter the database name for your field here, like dob for Date of Birth or full_name, use lower case letters and _ (underscores) ONLY! Keep it short and simple and relative to the field you are creating!', 'user-registration-aide' ) . '" value="'. $newFieldKey . '" name="ura_newFieldKey" id="ura_newFieldKey" maxlength="30" />';
					?>
					</fieldset>
					</td>
					<td align="left">
					<fieldset>
					<legend><?php _e( 'Field Type:', 'user-registration-aide' );?></legend>
					<select name="input_type" id="input_type" title="<?php _e( 'Select the input type for your new custom field here from select, text, textarea, radio or checkbox', 'user-registration-aide' );?>" >
					<?php
					foreach( $input as $key	=>	$title ){
						if( $key == $field_data_type ){
							echo "<option selected=".$field_data_type." value=\"$key\">$title</option>";
						}else{
							echo "<option value=\"$key\">$title</option>";
						}
					}
					?>
					</select>
					</fieldset>
					</td>
					<td align="left">
					<fieldset>
					<legend><?php _e( 'Use on Registration Form:', 'user-registration-aide' );?></legend>
					<select name="reg_form_use" id="reg_form_use" title="<?php _e( 'Choose Yes to use this field on the Registration Form or No not to use it on Registration Form','user-registration-aide' );?>" >
					<?php
					foreach( $select_options as $key =>	$title ){
						if( $key == $reg_form_use ){
							echo "<option selected=".$reg_form_use." value=\"$key\">$title</option>";
						}else{
							echo "<option value=\"$key\">$title</option>";
						}
					}
					?>
					</select>
					</fieldset>
					</td>
					<td>
					<fieldset>
					<legend><?php _e( 'Field Requirement:', 'user-registration-aide' );?></legend>
					<select name="required" id="required" title="<?php _e( 'Select whether the new field is required or not here', 'user-registration-aide' );?>" >
					<?php
					foreach( $select_options as $key =>	$title ){
						if( $key == $required ){
							echo "<option selected=".$required." value=\"$key\">$title</option>";
						}else{
							echo "<option value=\"$key\">$title</option>";
						}
					}
					?>
					</select>
					</fieldset>
					</td>
				</tr>
			</table>
			<br/>
			<table class="newInputFields">
				<tr>
					<td align="left">
					<fieldset>
					<legend><?php _e( 'Input Type Number:', 'user-registration-aide' );?></legend>
					<p title="<?php _e( 'You can select the minimum number to display, maximum number to display and the step, or increment to use, like 5,10,15 for 5 or 2,4,6,8 for 2 or 1,2,3 for 1.', 'user-registration-aide' );?>" >
					<?php _e( 'Only use these choices if your Field Type is a number', 'user-registration-aide' ); ?>
					</p>
					</fieldset>
					</td>
					<td align="left">
					<fieldset>
					<legend><?php _e( 'Minimum Number:', 'user-registration-aide' );?></legend>
					<?php
					echo '<input  style="width: 100%;" type="number" title="'.__( 'Enter the minimum number to use for your number input box', 'user-registration-aide' ) . '" value="'. $minNumb . '" name="ura_minNumb" id="ura_minNumb" maxlength="30" />';
					?>
					</fieldset>
					</td>
					<td align="left">
					<fieldset>
					<legend><?php _e( 'Maximum Number:', 'user-registration-aide' );?></legend>
					<?php
					echo '<input  style="width: 100%;" type="number" title="'.__( 'Enter the maximum number to use for your number input box', 'user-registration-aide' ) . '" value="'. $maxNumb . '" name="ura_maxNumb" id="ura_maxNumb" maxlength="30" />';
					?>
					</fieldset>
					</td>
					<td align="left">
					<fieldset>
					<legend><?php _e( 'Number Step By:', 'user-registration-aide' );?></legend>
					<?php
					echo '<input  style="width: 100%;" type="number" title="'.__( 'Enter the number to increment by in your number select box ( 1, 2, 5 which are the numbers that would be used, like 5 would be 5,10,15,20 like that )', 'user-registration-aide' ) . '" value="'. $stepNumb . '" name="ura_stepNumb" id="ura_stepNumb" maxlength="30" />';
					?>
					</fieldset>
					</td>
				</tr>
			</table>
			<br/>
			<table class="newInputFields">
				<tr>
					<td colspan="4" align="left">
					<fieldset title="">
					<legend><?php _e( 'Field Title:', 'user-registration-aide' );?></legend>
					<?php
					echo '<input  style="width: 100%;" type="text" title="'.__( 'Enter the user friendly name for your field here, like Date of Birth for dob, ect. Keep it short & simple and relative to the field you are creating!', 'user-registration-aide' ) . '" value="'. $newField . '" name="ura_newField" id="ura_newField" maxlength="50" />';
					?>
					</fieldset>
					</td>
				</tr>
			</table>
			<br/>
			<table class="newInputFields">
				<tr>
					<td colspan="4" align="left">
					<fieldset title="<?php _e( 'NOTE: DO NOT USE COMMAS in your field options please! It will mess up your whole array of options strings!!', 'user-registration-aide' );?>">
					<legend title="<?php _e( 'NOTE: DO NOT USE COMMAS in your field options please! It will mess up your whole array of options strings!!', 'user-registration-aide' );?>"><?php _e( 'Field Options:', 'user-registration-aide' );?></legend>
					<?php
					$texts = $nfc->options_text_inputs();
					$text_keys = $nfc->options_keys_array();
					$space = '&nbsp';
					$double_space = '&nbsp;&nbsp;';
					$triple_space = '&nbsp;&nbsp;&nbsp;&nbsp;';
					$quad_space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					$i = ( int ) 1;
					$index = ( int ) 0;
					?>
					<table class="options-number" style="width:95%;">
					<tr>
					<th class="options-number" style="width:5%">
					<?php _e( 'Option Number:', 'user-registration-aide' );?>
					</th>
					<th style="width:22.5%;">
					<?php _e( 'Option Key:', 'user-registration-aide' );?>
					</th>
					<th style="width:22.5%;">
					<?php _e( 'Option Name:', 'user-registration-aide' );?>
					</th>
					<th class="options-number" style="width:5%;">
					<?php _e( 'Option Number:', 'user-registration-aide' );?>
					</th>
					<th style="width:22.5%;">
					<?php _e( 'Option Key:', 'user-registration-aide' );?>
					</th>
					<th style="width:22.5;%">
					<?php _e( 'Option Name:', 'user-registration-aide' );?>
					</th>
					</tr>
					<?php
					for( $x = 1; $x <= 8; $x++ ){
					//foreach( $texts as $id	=> $label ){
						echo '<tr>';
						echo '<td class="options-number">'.$i.':</td>';
						echo '<td width="22.5%">'.$text_keys[$index].'</td>';
						echo '<td width="22.5%">'.$texts[$index].'</td>';
						$i++;
						$index++;
						echo '<td class="options-number">'.$i.':</td>';
						echo '<td width="22.5%">'.$text_keys[$index].'</td>';
						echo '<td width="22.5%">'.$texts[$index].'</td>';
						$i++;
						$index++;
						echo '</tr>';
					}
					?>
			</table>
					</fieldset>
					</td>
				</tr>
			
			<?php
			if ( class_exists( $class ) ){
				if( is_plugin_active( $plugin ) ){
				?>
			</table>
			<br/>
			<table class="newInputFields">
				<tr>
					<td colspan="4" align="left">
					<fieldset>
					<legend><?php _e('BuddyPress Fields:', 'user-registration-aide' );?></legend>
					<fieldset>
					<legend><?php _e('Field Description:', 'user-registration-aide' );?></legend>
					<input  style="width: 100%;" type="textarea" title="'.__( 'Enter the field description to describe in detail the field you are creating here!', 'user-registration-aide' ) . '" value="" name="ura_newFieldDesc" id="ura_newFieldDesc" />
					</fieldset>
					<fieldset>
					<legend><?php _e('Default Visibility:', 'user-registration-aide' );?></legend>
					<input type="radio" name="default_visibility" value="public" checked><?php _e('Everyone', 'user-registration-aide' );?><br>
					<input type="radio" name="default_visibility" value="adminsonly"><?php _e(' Only Me', 'user-registration-aide' );?><br>
					<input type="radio" name="default_visibility" value="loggedin"><?php _e(' All Members', 'user-registration-aide' );?><br>
					</fieldset>
					<fieldset>
					<legend><?php _e( 'Per Member Visibility:', 'user-registration-aide' );?></legend>
					<input type="radio" name="allow_custom_visibility" value="allowed" checked><?php _e(' Let members change this field\'s visibility', 'user-registration-aide' );?><br>
					<input type="radio" name="allow_custom_visibility" value="disabled"><?php _e(' Enforce the default visibility for all members', 'user-registration-aide' );?><br>
					</fieldset>
					</fieldset>
					</td>
				</tr>
					
					<?php
				}
			}
			?>
			</table>
			<br/>
			<table class="newInputFields">
				<tr>
					<td style="text-align:center;" colspan="4">
					<input type="submit" class="button-primary" name="new_fields_update" value="<?php _e( 'Add New Field', 'user-registration-aide' );?>" />
					</td>
				</tr>
			</table>
			
			<?php
		}
	}
}