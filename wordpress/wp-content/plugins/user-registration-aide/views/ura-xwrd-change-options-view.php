<?php

/**
 * Class  URA_XWRD_CHANGE_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_XWRD_CHANGE_VIEW
{
	
	/** 
	 * function password_change_settings_view
	 * Handles password change settings view
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function password_change_settings_view() {
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$options = get_option( 'csds_userRegAide_Options' );
			?>
			<table class="regForm" width="100%">
				<tr>
					<th colspan="2">
					<?php _e( 'Password Change Options:', 'user-registration-aide' );?>
					</th>
				</tr>
				<tr> <?php // Password Change Options ?>
					<td width="50%"><?php _e( 'Require Password Change After User Registers and Gets Password From Email: ', 'user-registration-aide' );?>
					<br/>
					<span title="<?php _e( 'Select this option to require new users who have registered for your site and received their Password from the default WordPress Email to change their password before logging in', 'user-registration-aide' );?>">
					<input type="radio" name="newUser_xwrdChange" id="newUser_xwrdChange" value="1" <?php
					if ($options['xwrd_change_on_signup'] == 1) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option not to require new users to change their password before logging in',  'user-registration-aide' );?>">
					<input type="radio" name="newUser_xwrdChange" id="newUser_xwrdChange" value="2" <?php
					if ($options['xwrd_change_on_signup'] == 2) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					</td>
					<?php // Require existing users to change passwords at selected intervals ?>
					<td width="50%"><?php _e( 'Require Existing Users to Change Passwords After Specified Time Period: ', 'user-registration-aide' );?>
					<br/>
					<span title="<?php _e( 'Select this option to require current users to change passwords at selected intervals', 'user-registration-aide' );?>">
					<input type="radio" name="xwrd_chg_curUsers" id="xwrd_chg_curUsers" value="1" <?php
					if ($options['xwrd_require_change'] == 1) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option not to require current users to change passwords at selected intervals',  'user-registration-aide' );?>">
					<input type="radio" name="xwrd_chg_curUsers" id="xwrd_chg_curUsers" value="2" <?php
					if ($options['xwrd_require_change'] == 2) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					</td>
				</tr>
				<tr>
					<?php // Custom Password Strength Requirements Option Yes/No ?>
					<td width="50%" title="<?php _e( 'Enter the amount of time in days between users having to change their passwords, the defaults are every 30 days up to one year. It is good security practice to require a password change at least every 6 months, or 180 days!', 'user-registration-aide' );?>">
					<?php _e( 'Maximum Time Allowed in Days Between Password Change Dates: ', 'user-registration-aide' );?>
					<select name="password_change_interval" id="password_changed_interval" title="<?php _e( 'You can select the maximum time allowed in days here before users are required to change their passwords. Good security practices call for 180 days', 'user-registration-aide' );?>"  size="8" style="height:40px">
					<?php
					$interval = trim( $options['xwrd_change_interval'] );
					for( $days = 30; $days <= 360; $days += 30 ){
					/* testing
					for( $days = 1; $days <= 30; $days += 1 ){ */
						if( $days == $interval ){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						}
						
						echo "<option value=\"$days\" $selected >$days</option>";
					}
					?>
					</select>
					</td>
					<?php // Custom Password Strength Requirements Option Yes/No ?>
					<td width="50%" title="<?php _e( 'Select the number of times checked for password duplicates before allowing a duplicate password. For instance, a user has changed password 6 times before allowing duplicates would mean that on the 7th password change, the user could duplicate the first password they entered of those 6 passwords. This is useful to prevent users from entering the same 1 or 2 passwords over and over again making it easier to hack their accounts and your site!', 'user-registration-aide' );?>"><?php _e( 'Maximum Password Changes Before Duplicates Allowed: ', 'user-registration-aide' );?>
					<select name="dup_password_change_times" id="max_dup_xword_change_times" title="<?php _e( 'You can select the number of password changes allowed before a user can enter a duplicate password. Useful for those users that use the same passwords over and over again which makes it easy to hack their accounts and your site! This option will eliminate that problem for the most part in addition to strong password strength requirements!', 'user-registration-aide' );?>"  size="8" style="height:40px">
					<?php
					$dup_times = trim( $options['xwrd_duplicate_times'] );
					for( $times = 1; $times <= 9; $times += 1 ){
						if( $times == $dup_times ){
							$selected = "selected=\"selected\"";
						}else{
							$selected = NULL;
						}
						
						echo "<option value=\"$times\" $selected >$times</option>";
					}
					?>
					</select>
					</td>
				</tr>
				<?php 
				/*
				<tr>
					<td width="50%"><label for="xwrd_chg_url" title="<?php _e( 'Only Add The Distinct Page Name Please!! ( EXAMPLE: change-password for page titled Change Password) No /( FORWARD SLASHES!!)', 'user-registration-aide' );?>"><?php _e( 'Password Change Shortcode Page Name: ', 'user-registration-aide' );?>
					</label>/
					<input type="text" name="xwrd_chg_url" id="xwrd_chg_url" title="<?php _e( 'Only Add The Distinct Page Name Please!! ( EXAMPLE: change-password for page titled Change Password) No /( FORWARD SLASHES!!)', 'user-registration-aide' );?>" value="<?php echo $options['xwrd_change_name']; ?>" />/<br/>
					</td>
					
					<?php // Password Change Post Title -- ?>
					<td width="50%">
					<label for="xwrd_chg_title" title="<?php _e( 'Only Add The Distinct Page Title!!', 'user-registration-aide' );?>"><?php _e( 'Password Change Shortcode Page Title: ', 'user-registration-aide' );?>
					</label>
					<input type="text" name="xwrd_chg_title" id="xwrd_chg_title" title="<?php _e( 'Only Add The Distinct Page Title Please!! (Change Password) No /(SLASHES!!)', 'user-registration-aide' );?>" value="<?php echo $options['xwrd_chng_title']; ?>" /><br/>
					</td>
					
				</tr>
				*/
				?>
				<tr>
					<td width="50%">
					<label for="xwrd_chg_ssl" title="<?php _e( 'Requires SSL Certificate on Website Server To Use SSL!', 'user-registration-aide' );?>"><?php _e( 'Use SSL(HTTPS://) Secure Page For Password Change Page: ', 'user-registration-aide' );?>
					</label>
					<span title="<?php _e( 'Select this option to require SSL for Custom Password Change Page! NOTE: REQUIRES SSL CERTIFICATE ON WEBSITE!', 'user-registration-aide' );?>">
					<input type="radio" name="xwrd_chg_ssl" id="xwrd_chg_ssl" value="1" <?php
					if ($options['xwrd_change_ssl'] == 1) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
					</span>
					<span title="<?php _e( 'Select this option to not use SSL on Custom Password Change Page! No Certificate Required, Use This Option if YOu Have No SSL Certificate!',  'user-registration-aide' );?>">
					<input type="radio" name="xwrd_chg_ssl" id="xwrd_chg_ssl" value="2" <?php
					if ($options['xwrd_change_ssl'] == 2) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?>
					</span>
					</td>
					<td width="50%">
					<label for="xwrd_reset" title="<?php _e( 'This will not include Administrators!', 'user-registration-aide' );?>"><?php _e( 'Allow Lost Password Reset for Non-Admins: ', 'user-registration-aide' );?>
					</label>
					<span title="<?php _e( 'Select this option to allow users to reset passwords with the lost password link on login page', 'user-registration-aide' );?>">
					<input type="radio" name="xwrd_reset" id="xwrd_reset" value="1" <?php
					if ($options['allow_xwrd_reset'] == 1) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
					</span>
					<span title="<?php _e( 'Select this option to not allow users to reset passwords with the lost password link on login page',  'user-registration-aide' );?>">
					<input type="radio" name="xwrd_reset" id="xwrd_reset" value="2" <?php
					if ($options['allow_xwrd_reset'] == 2) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?>
					</span>
					</td>
					
				</tr>
				<tr>
					<td colspan="2">
					<label for="show_xwrd_fields" title="<?php _e( 'This will not include Administrators!', 'user-registration-aide' );?>"><?php _e('Show Password Fields on Profile/User Edit Page<b> (NOTE: WILL NOT UTILIZE PASSWORD STRENGTH OR PASSWORD CHANGE REQUIREMENTS!): ', 'user-registration-aide' );?>
					</label>
					<span title="<?php _e( 'Select this option to allow users to change passwords on the user profile/edit page. NOTE: Will not enforce password strength or password change requirements!!!', 'user-registration-aide' );?>">
					<input type="radio" name="show_xwrd_fields" id="show_xwrd_fields" value="1" <?php
					if ($options['show_password_fields'] == 1) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option to not allow users to change passwords user profile/edit page',  'user-registration-aide' );?>">
					<input type="radio" name="show_xwrd_fields" id="show_xwrd_fields" value="2" <?php
					if ($options['show_password_fields'] == 2) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<input type="submit" class="button-primary" name="updt_pwrd_chgd_options" id="updt_pwrd_chgd_options" value="<?php _e( 'Update Password Change Options', 'user-registration-aide' );?>" />
					</td>
				</tr>
			</table>
		<?php
		}
	}
	
}