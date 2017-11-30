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

class URA_FIELD_ORDER_TITLE_VIEW
{

	/**	
	 * Function field_order_title_view
	 * URA edit new field order and titles view
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function field_order_title_view(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$field = new FIELDS_DATABASE();
			$ura_fields = $field->get_all_fields();
			?>
			<table class="style">	
			<tr>
				<th colspan="3"><?php _e( 'Edit New Field Orders and Titles', 'user-registration-aide' );?> </th>
			</tr>
			<tr>
				<th><?php _e( 'Edit New Field Order', 'user-registration-aide' );?> </th>
				<th colspan="2"><?php _e( 'Edit New Field Name', 'user-registration-aide' );?> </th>
			</tr>
			<tr>
				<td>						
				<?php
						
				// Edit new field order form 
				?>
				<p><?php _e( 'Here you can select or change the order for the new additional fields on the registration form and profile. You must not have the same number twice, so make sure you change all fields accordingly so there are no duplicates or you will generate an error!', 'user-registration-aide' );?></p>
				<?php
				$i = '';
				$cnt = '';
				$fieldKey = '';
				$fieldOrder = '';
				$fieldKeyUpper = '';
				$i = count( $ura_fields );
				$cnt = 1;
				
				// Table for field order
				?>
				<table class="newFields">
				<tr>
				<th><?php _e( 'Additional New Field Name: ', 'user-registration-aide' ); ?></th>
				<th><?php _e( 'Current Field Order: ', 'user-registration-aide' ); ?></th>
				</tr>
				<?php
				if( !empty( $ura_fields )){
					foreach( $ura_fields as $object ){?>
						<tr>
						<td class="fieldName"> <?php
						$fieldKeyUpper = strtoupper( $object->meta_key );
						echo '<label for="'.$object->meta_key.'">'.$fieldKeyUpper.'</label>';
						//Changed from check box to label here ?>
						</td>
						<td class="fieldOrder">
						<select  class="fieldOrder" name="csds_editFieldOrder[]" title="<?php _e( 'Edit your new field order here. Make sure that there are no duplicate field order numbers, like two fields having number 2 for their order!', 'user-registration-aide' );?>">
						<?php
						for( $ii = 1; $ii <= $i; $ii++ ){
							if( $ii == $object->field_order ){
								echo '<option selected="'.$object->meta_key.'" >'.$object->field_order.'</option>';
							}else{
								echo '<option value="'.$ii.'">'.$ii.'</option>';
							}									
						}
						$cnt ++; ?>
						</select>
						</td>
						</tr>
						<?php
					}
					?>
					</table>
						<div class="submit"><input type="submit" class="button-primary" name="field_order" value="<?php _e( 'Update Field Order', 'user-registration-aide' );?>"/></div>
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
					</table> <?php
				}
				
				// Edit new field fields
				
				?>
				</td>
				<td colspan="2">
				<p>
				<?php _e( 'Here you can change the field name displayed to the user for the new additional fields on the registration form and profile. !', 'user-registration-aide' );?>
				</p>
				<?php
				$i = '';
				$cnt = '';
				$fieldKey = '';
				$fieldKeyUpper = '';
				$length = ( int ) 0;
				// Table for new field edits
				?>
				<table class="newFields">
					<tr>
						<th><?php _e( 'Additional New Field ID: ', 'user-registration-aide' );?></th>
						<th><?php _e( 'Current Field Title: ', 'user-registration-aide' );?></th>
					</tr>
				<?php
				if( !empty( $ura_fields ) ){
					foreach( $ura_fields as $objects ){ 
						$length = strlen( $objects->field_name );
						$meta_key = $objects->meta_key;
						$key_length = strlen( $meta_key );
						?>
						<tr>
						<?php
						if( $key_length <= 20 ){
							?>
							<td class="fieldName"> <?php
							$fieldKeyUpper = strtoupper( $objects->meta_key );
							echo '<label for="'.$objects->meta_key.'">'. __( $fieldKeyUpper, 'user-registration-aide' ).'</label>'; ?>
							</td>
						<?php
						}else{ 
							$key1 = substr( $meta_key, 0, 19 );
							$key2 = substr( $meta_key, 20, 30 );
							?>
							<td class="fieldName"> <?php
							$fieldKeyUpper1 = strtoupper( $key1 );
							$fieldKeyUpper2 = strtoupper( $key2 );
							echo '<label for="'.$objects->meta_key.'">'. __( $fieldKeyUpper1, 'user-registration-aide' ).'<br/>'. __( $fieldKeyUpper2, 'user-registration-aide').'</label>'; ?>
							</td>
							<?php
						}
						?>
						<td class="fieldOrder">
						<?php 
						if( $length <= 30 ){ 
							?>
							<input  type="text" class="fieldOrder" name="<?php echo $objects->meta_key.'_title' ?>" id="<?php echo $objects->meta_key.'_title' ?>" title="<?php _e( 'Edit your field name here', 'user-registration-aide' );?>" value="<?php _e( $objects->field_name, 'user-registration-aide' );?>" /> 
						<?php
						}else{ 
							//$rows = 6;
							$rows = ( $length/20 ) + 1; ?>
							<textarea rows="<?php $rows; ?>" cols="20" class="fieldOrder" name="<?php echo $objects->meta_key.'_title' ?>" id="<?php echo $objects->meta_key.'_title' ?>" title="<?php _e( 'Edit your field name here', 'user-registration-aide' );?>"><?php _e( $objects->field_name, 'user-registration-aide' );?>
							</textarea>
							<?php
						} ?>
							</td>
						</tr> 
						<?php
					} 
				}else{
					?>
					<tr>
						<td class="fieldName" colspan="2">
						<p class="deleteFields">
						<?php _e( 'No new fields currently exist, you have to add new fields on the main page before you can change the order!', 'user-registration-aide' ); ?>
						</p>
						</td>
					</tr>
					<?php
				}?>
				</table>
				<div class="submit"><input type="submit" class="button-primary" name="edit_field_name" value="<?php _e('Update Field Names', 'user-registration-aide');?>"  /></div>
				</td>
				</tr>
				</table>
				
			<?php
		}
	}
}?>