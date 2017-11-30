<?php

/**
 * Class URA_DISPLY_NAME_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_DISPLY_NAME_VIEW
{
	/**
	 * function user_display_name_view
	 * Handles Display Name settings view
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @return 
	*/
	
	function user_display_name_view(){
		global $wp_roles, $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$options = get_option( 'csds_userRegAide_Options' );
			$current_role = get_option( 'default_role' );
			$selRole = $options['display_name_role'];
			$selField = $options['custom_display_field'];
		
			?>
			<table class="displayName">
				<tr>
					<th colspan="3">
					<?php _e( 'Edit User Display Name Options Here:', 'user-registration-aide' );?>
					</th>
				</tr>
				<tr>
					<th class="displayName"><?php _e( 'Display Name Choices', 'user-registration-aide' );?> </th>
					<th class="displayName"><?php _e( 'Display Name Field', 'user-registration-aide' );?> </th>
					<th class="displayName"><?php _e( 'Display Name Role', 'user-registration-aide' );?> </th>
				</tr>
				<tr>
					<td>
					<p><label for="csds_displayNameYesNo" title="<?php _e( 'Using this option will remove the option for users to choose their own display name on the user profile and will give total control of user display names to the administrators of this site', 'user-registration-aide' );?>"><?php _e( 'Use Custom Display Name?: ', 'user-registration-aide' );?><input type="radio" id="csds_displayNameYesNo" name="csds_displayNameYesNo"  value="1" <?php
					if ( $options['custom_display_name'] == 1 ) echo 'checked' ;?>/> <?php _e( 'Yes', 'user-registration-aide' );?>
					<input type="radio" id="csds_displayNameYesNo" name="csds_displayNameYesNo"  value="2"<?php
					if ( $options['custom_display_name'] == 2 ) echo 'checked' ;?>/> <?php _e( 'No', 'user-registration-aide' );?>
					</label>
					</td>
					<td class="displayName">
					<p class="displayName"> 
					<?php _e( 'Select Display Name Fields Here:', 'user-registration-aide' );?>
					<select name="display_name_field[]" id="display_name_fields" title="<?php _e( 'You can select the field(s) here that you want to use in place of the current default WordPress User Display Name which is the User Login', 'user-registration-aide' );?>"  size="8" style="height:40px">
					<?php
					$dnm = new URA_DISPLAY_NAME_MODEL();
					$dn_fields = $dnm->names_array();
					$options = get_option( 'csds_userRegAide_Options' );
					$custom_display_name = $options['custom_display_field'];
										
					foreach( $dn_fields as $key1 => $value1 ){
						if( $key1 == $custom_display_name ){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						}
						
						echo "<option value=\"$key1\" $selected >$value1</option>";
					}
							
					?>								
					</select>
					</p>
					</td>
					<td class="displayName">
					<p class="displayName">
					<?php _e( 'Select Display Name Role Here:', 'user-registration-aide' );?>
					<select name="display_name_role_select" id="display_name_roles_select" title="<?php _e( 'You can select the role here that you want to use for the Custom WordPress User Display Name or Select All Roles for everyone on the site', 'user-registration-aide' );?>" multiple size="8" style="height:40px">
					<?php
					$selected = ( string ) '';
					$register = ( int ) 0;
					$checked = ( string ) '';
					$def_role = 'all_roles';
					$def_role_title = __( 'All Roles', 'user-registration-aide' );
					$role_names = $wp_roles->get_names();
					$roles = array();
					$sel_roles = $options['display_name_role'];
					//exit( print_r( $role_names ) );
					if( $sel_roles == "all_roles" ){
						$selected = "selected=\"selected\"";
						echo "<option value=\"$def_role\" $selected >$def_role_title</option>";
					}elseif( $sel_roles != "all_roles" ){
						$selected = NULL;
						echo "<option value=\"$def_role\" $selected >$def_role_title</option>";
					}
					
					foreach( $role_names as $role_id => $role_name ){
						//foreach( $sel_roles as $key => $value ){
							if( $role_id == $sel_roles ){
								$selected = "selected=\"selected\"";
							}else{
								$selected = NULL;
							}
							
							echo "<option value=\"$role_id\" $selected >$role_name</option>";
							//break;
						//}
						
					}
					
					
					?>
					</select>
					</td>
				</tr>
				<tr>
					<td>
					<p><label for="csds_profileDispNameYN" title="<?php _e( 'Using this option will remove the option for users to choose their own display name on the user profile and will give total control of user display names to the administrators of this site', 'user-registration-aide' );?>"><?php _e( 'Allow User To Update Own Display Name in User Profile: ', 'user-registration-aide' );?>
					<br/>
					<input type="radio" id="csds_profileDispNameYN" name="csds_profileDispNameYN"  value="1" <?php
					if ( $options['show_profile_disp_name'] == 1 ) echo 'checked' ;?>/><?php _e( 'Yes', 'user-registration-aide' );?>
					<input type="radio" id="csds_profileDispNameYN" name="csds_profileDispNameYN"  value="2"<?php
					if ( $options['show_profile_disp_name'] == 2 ) echo 'checked' ;?>/><?php _e( 'No', 'user-registration-aide' );?>
					</label>
					</td>
					<td>
					<p><label for="csds_profileDispNameYN" title="<?php _e( 'Using this option will remove the option for users to choose their own display name on the user profile and will give total control of user display names to the administrators of this site', 'user-registration-aide' );?>"><?php _e( 'New User Default Role: ', 'user-registration-aide' );?>
					<select name="default_role" id="select_default_role" title="<?php _e( 'You can select the role here that you want to use for the Default WordPress New User Sign-up', 'user-registration-aide' );?>" multiple size="8" style="height:40px"><?php
					$current_role = get_option( 'default_role' );
					foreach( $role_names as $role_id => $role_name ){
						if( $role_id == $current_role ){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						}
						
						echo "<option value=\"$role_id\" $selected >$role_name</option>";
					}
					?>
					</label>
					</td>
					<td>
					<?php $register = get_option( 'users_can_register' ); ?>
					<label for="csds_users_can_register">
					<input name="csds_users_can_register" type="checkbox" id="csds_users_can_register" value="<?php echo $register; ?>"
					<?php
					if( $register == '1' ){ 
						echo 'checked="yes">';
					}else{
						echo '>';
					}
					_e( 'Anyone can register', 'user-registration-aide' );?></label>
					
				</tr>
				<tr>
					<td colspan="3" class="displayName">
					<div class="submit">
					<input type="submit" class="button-primary" name="update_display_name_field" id="update_display_name_field" value="<?php _e( 'Update Display Name Options', 'user-registration-aide' );?>" />
					</div>
					</td>
				<tr>
					<td colspan="2" class="displayName">
					<div class="submit">
					<label for="change_display_names"><?php _e( 'Change Existing Users Display Names to Your Custom Display Name Field - Current Display Name Field:', 'user-registration-aide' );?> <?php echo $custom_display_name; ?>
					<br/>
					<b><?php _e( 'CAUTION - THIS CAN TAKE QUITE SOME TIME IF YOU HAVE A SLOW SERVER OR LOTS OF USERS!!', 'user-registration-aide' );?></b></label>
					<br/>
					<input type="submit" class="button-primary" name="change_user_display_names" id="change_user_display_names" title="<?php _e( 'Changes the Current Users Display Names to the Field of Your Choice and Creates Backup Existing User Display Names to the User-Meta Table in Case you Wish to Go Back to The Default WordPress Display Name', 'user-registration-aide' );?>" value="<?php _e( 'Update Display Names', 'user-registration-aide' );?>" />
					</div>
					</td>
					<td colspan="1" class="displayName">
						<label for="restore_default_display_names"><?php _e( 'Restore Existing Users Display Names to WordPress Default Display Name:', 'user-registration-aide' );?></label>
						<br/>
						<input type="submit" class="button-primary" name="restore_default_display_names" id="restore_default_display_names" title="<?php _e( 'Restores Existing Users Display Names to the Default WordPress Display Name From the Backup Created When Display Names Were Customized',  'user-registration-aide' );?>" value="<?php _e( 'Restore Default Display Names', 'user-registration-aide' );?>" />
					</td>
				</tr>
			</table>
				
			<?php
		}		
	}
}