<?php

/**
 * Class URA_EDIT_NUMBERS_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_EDIT_NUMBERS_VIEW
{
	
	/**	
	 * function numbers_type_editing_view
	 * URA Edit Field Type Numbers View handles number editing section views
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns 
	*/
	
	function numbers_type_editing_view(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$field = new FIELDS_DATABASE();
			$ura_fields = $field->get_number_fields();
		
			// Displays the Edit New Additional Fields Administration Page
			$tab = 'edit_new_fields';
			$section = 'edit_number';
			
			?>
			</table>
				<br/>
				<table class="style">
				<tr>
					<th colspan="4"><?php _e( 'Edit Number Input Type Options for Fields:', 'user-registration-aide' );?> </th>
				</tr>
				
				<tr>
					<th><?php _e( 'Field Title: ', 'user-registration-aide' );?></th>
					<th><?php _e( 'Minimum Number: ', 'user-registration-aide' );?></th>
					<th><?php _e( 'Maximum Number: ', 'user-registration-aide' );?></th>
					<th><?php _e( 'Number Step: ', 'user-registration-aide' );?></th>
				</tr>
				<?php
							
				if( !empty( $ura_fields ) ){
					foreach( $ura_fields as $object ){
						$minNumb = ( int ) $object->min_number;
						$maxNumb = ( int ) $object->max_number;
						$stepNumb = ( int ) $object->number_step;
						$meta_key = $object->meta_key;
						$min_name = $meta_key.'_min';
						$max_name = $meta_key.'_max';
						$step_name = $meta_key.'_step';
						?>
						
						<tr>
							<td align="left">
							<fieldset class="numbers">
							<legend>Field Title: </legend>
							<?php echo $object->field_name; ?>
							</fieldset>
							</td>
							<td align="left">
							<fieldset class="numbers">
							<legend>Minimum Number: </legend>
							<?php
							echo '<input  style="width: 100%;" type="number" title="'.__( 'Enter the minimum number to use for your number input box', 'user-registration-aide' ) . '" value="'. $minNumb . '" name="'.$min_name.'" id="'.$min_name.'" maxlength="30" />';
							?>
							</fieldset>
							</td>
							<td align="left">
							<fieldset class="numbers">
							<legend>Maximum Number: </legend>
							<?php
							echo '<input  style="width: 100%;" type="number" title="'.__( 'Enter the maximum number to use for your number input box', 'user-registration-aide' ) . '" value="'. $maxNumb . '" name="'.$max_name.'" id="'.$max_name.'" maxlength="30" />';
							?>
							</fieldset>
							</td>
							<td align="left">
							<fieldset class="numbers">
							<legend>Number Step By: </legend>
							<?php
							echo '<input  style="width: 100%;" type="number" title="'.__( 'Enter the number to increment by in your number select box ( 1, 2, 5 which are the numbers that would be used, like 5 would be 5,10,15,20 like that )', 'user-registration-aide' ) . '" value="'. $stepNumb . '" name="'.$step_name.'" id="'.$step_name.'" maxlength="30" />';
							?>
							</fieldset>
							</td>
						</tr>
						
						<?php
					}
				}else{
					?>
					<th colspan="4">
					<?php _e( 'No Number Fields Currently Exist!', 'user-registration-aide' );?>
					</th>
					<?php
				}
				?>
				<tr>
					<td colspan="4">
					<input type="submit" class="button-primary" name="number_options_update" id="number_options_update" value="<?php _e( 'Update Number Options', 'user-registration-aide' );?>" />
					
					</td>
				</tr>
				</table>
			
			</table>		
			<?php
		}
	}
	
}