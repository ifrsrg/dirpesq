<?php

/**
 * Class URA_EDIT_FIELDS_OPTIONS_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_EDIT_FIELDS_OPTIONS_VIEW
{

	/** 
	 * function edit_new_fields_options_view
	 * URA Edit New Fields Options View handles new field options page views
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function edit_new_fields_options_view(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$field = new FIELDS_DATABASE();
			$ura_fields = $field->get_option_parent_fields();
			//exit( print_r( $ura_fields ) );
			if( $ura_fields == null ){
				$nf_msg = '<div id="message" class="error"><p>'. __( 'No new fields have been created, please go to the main page to add some new fields first before you try and edit them! ', 'user-registration-aide' ).'</p></div>';
				$nf_msg .= '<br />';
			}
						
		// Displays the Edit New Additional Fields Administration Page
				
			?>
			<table class="style">	
			
				<tr>
					<th colspan="3"><?php _e( 'Edit Additional Fields Options for Profile &  Registration Form:', 'user-registration-aide' );?> </th>
				</tr>
			
			
				<tr>
					<th colspan="2"><?php _e( 'Delete New Field Options', 'user-registration-aide' );?> </th>
					<th><?php _e( 'Edit New Field Options Titles', 'user-registration-aide' );?> </th>
				</tr>
				<tr>
					<td  colspan="2">						
				
					<p><?php _e( 'Here you can delete new field options!', 'user-registration-aide' );?></p>
					<?php
					
					$fieldKey = ( string ) '';
					$fieldOrder = ( string ) '';
					$fieldKeyUpper = ( string ) '';
					
					
					// Table for field order
					?>
					<br/>
					<table class="newFields">
					<tr>
						<th><?php _e( 'Field Options Parent Title: ', 'user-registration-aide' ); ?></th>
						<th><?php _e( 'New Field Options: ', 'user-registration-aide' ); ?></th>
					
					</tr>
					<?php
					
					if( !empty( $ura_fields ) ){
						foreach( $ura_fields as $object ){
							//exit( print_r( $object ) );
							$options = $field->get_field_options_edit( $object->ID );
							if( !empty( $options ) ){
								?>
								<tr>
									<td class="fieldName"> <?php
									$fieldKeyUpper = strtoupper( $object->field_name );
									echo '<label for="'.$object->meta_key.'">'.$fieldKeyUpper.'</label>';
									//Changed from check box to label here ?>
									</td>
									<td class="fieldOrder">
									<select  class="fieldOrder" name="<?php echo $object->meta_key.'_delete'; ?>" id="<?php echo $object->meta_key; ?>" title="<?php _e( "Select an Option to Delete Here", "user-registration-aide" );?>">
									<?php
									echo '<option value="ZERO">---</option>';
									foreach( $options as $option ){
										$value = $option->option_meta_key.'_delete';
										$name = $option->field_name;
										echo '<option value="'.$value.'">'.$name.'</option>';
									}
									?>
									</select>
									</td>
								</tr>
								<?php
							}else{
								?>
								<tr>
									<td class="fieldName"> <?php
									$fieldKeyUpper = strtoupper( $object->field_name );
									echo '<label for="'.$object->meta_key.'">'.$fieldKeyUpper.'</label>';
									//Changed from check box to label here ?>
									</td>
									<td class="fieldOrder">
									<select  class="fieldOrder" name="<?php echo $object->meta_key.'_delete'; ?>" id="<?php echo $object->meta_key; ?>" title="<?php _e( "Select an Option to Delete Here", "user-registration-aide" );?>">
									<?php
									echo '<option value="ZERO">'.__( 'No Options Currently Exist for This Field!', 'user-registration-aide' ).'</option>';
									?>
									</select>
									</td>
								</tr>
								<?php
							}
							
						}
						?>
						</table>
							<div class="submit"><input type="submit" class="button-primary" name="delete_option" value="<?php _e( 'Delete Field Option', 'user-registration-aide' );?>"/></div>
							<?php
					}else{
						?>
						<tr>
							<td class="fieldName" colspan="2">
							<p class="deleteFields">
							<?php _e( ' No new fields currently exist, you have to add new fields on the main page before you can create and delete options!', 'user-registration-aide' ); ?>
							</p>
							</td>
						</tr>
						</table> <?php
					}
					
					// Edit new option field 
					//$newFields = get_option('csds_userRegAide_NewFields');
					?>
					</td>
					<td>
					<p>
					<?php _e( 'Here you can change the option name displayed to the user for the new additional fields on the registration form and profile. !', 'user-registration-aide' );?>
					</p>
					<?php
					$i = '';
					$cnt = '';
					$fieldKey = '';
					$fieldKeyUpper = '';
					$bpf = new URA_BP_FUNCTIONS();
					$options_array = $bpf->options_fields_array();
					// Table for new field edits
				?>
				<table class="newFields">
				<tr>
					<th><?php _e( 'Field Option Parent Title: ', 'user-registration-aide' );?></th>
					<th><?php _e( 'Field Option Title: ', 'user-registration-aide' );?></th>
				</tr>
				<?php
				$type = ( string ) '';
				if( !empty( $ura_fields ) ){
					foreach( $ura_fields as $object ){
						$type = $object->data_type;
						$options = $field->get_field_options_edit( $object->ID );
						if( !empty( $options ) ){
							?>
							<tr>
								<td class="fieldName" title="<?php _e( 'Hover over the Option Title to View the Option Key', 'user-registration-aide' ); ?>"> 
								<?php $fieldKeyUpper = strtoupper( $object->field_name );
								echo '<label for="'.$object->meta_key.'">'. __( $fieldKeyUpper, 'user-registration-aide' ).'</label>'; ?>
								</td>
								<td class="fieldOrder" title="<?php _e( 'Hover over the Option Title to View the Option Key', 'user-registration-aide' ); ?>">
								<?php
								$index = ( int ) 0;
								if( !empty ( $options ) ){
									foreach( $options as $option ){
										$meta_key = $option->option_meta_key;
										$option_name = $option->field_name;
										?>
										<input  type="text" class="fieldOrder" name="<?php echo $meta_key.'_'.$index ?>" id="<?php echo $meta_key.'_'.$index ?>" title="<?php printf( __( 'Field Option Key: - - %s - - Edit your field option name here', 'user-registration-aide' ), $meta_key );?>" value="<?php _e( $option_name, 'user-registration-aide' );?>" />
										<?php
										$index++;
									}
									
								}else{
									echo 'NO OPTIONS AT THIS TIME';
								}
								?>
								</td>
							</tr>
							<?php
							
						}else{
							?>
							<tr>
								<td class="fieldName"> <?php
								$fieldKeyUpper = strtoupper( $object->field_name );
								echo '<label for="'.$object->meta_key.'">'. __( $fieldKeyUpper, 'user-registration-aide' ).'</label>'; ?>
								</td>
								<td class="fieldOrder">
								<?php
								$index = ( int ) 0;
								if( !empty ( $options ) ){
									foreach( $options as $option ){
										$meta_key = $option->option_meta_key;
										$option_name = $option->field_name;
										?>
										<input  type="text" class="fieldOrder" name="<?php echo $meta_key.'_'.$index ?>" id="<?php echo $meta_key.'_'.$index ?>" title="<?php _e( 'Edit your field option name here', 'user-registration-aide' );?>" value="<?php _e( $option_name, 'user-registration-aide' );?>" />
										<?php
										$index++;
									}
									
								}else{
									echo __( 'NO OPTIONS AT THIS TIME', 'user-registration-aide' );
								}
								?>
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
					<?php
				}?>
				</table>
					<div class="submit"><input type="submit" class="button-primary" name="edit_field_option_name" value="<?php _e( 'Update Field Option Title Names', 'user-registration-aide' );?>"  /></div>
					</td>
				</tr>
				
				<?php // Table for adding additional new field options ?>
				</table>
				<br/>
				
				
			<?php
		}
	}
}?>