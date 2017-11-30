<?php

/**
 * Class URA_NUA_SETTINGS_VIEW
 *
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
 */

class URA_NUA_SETTINGS_VIEW
{
	
	/**	
	 * Function new_user_approval_settings_view
	 * URA settings for new user approval
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params array $options options settings for plugin page
	 * @returns 
	 */
	
	function new_user_approval_settings_view( $options ){
		$plugin = 'buddypress/bp-loader.php';
		?>
		<table class="regForm" width="100%">
			<tr>
				<th colspan="2">
					<?php _e( 'New User Approve & Verify Email Option Settings:', 'user-registration-aide' );?>
				</th>
			</tr>
			<tr>
				<td colspan="2" width="100%"><?php _e( 'Choose to require approval for new users using URA Approval on New User Registration: ', 'user-registration-aide' );?>
				<span title="<?php _e( 'Select this option to make new users wait for approval before signup is complete and new user account is created',  'user-registration-aide' );?>">
				<input type="radio" name="csds_newUserApprove" id="csds_newUserApprove" value="1" <?php
				if ( $options['new_user_approve'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
				</span>
				<span title="<?php _e( 'Select this option to make new users NOT WAIT for approval before signup is complete and new user account is created and create new user account right away using the normal WordPress Registration Process',  'user-registration-aide' );?>">
				<input type="radio" name="csds_newUserApprove" id="csds_newUserApprove" value="2" <?php
				if ( $options['new_user_approve'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?>
				</span>
				</td>
			</tr>
			<?php
			// verify email
			?>
			<tr>
				<td colspan="2" width="100%"><?php _e( 'Choose to require new users to verify email After New User Registration: ', 'user-registration-aide' );?>
				<span title="<?php _e( 'Select this option to make new users verify their email before new user account is created',  'user-registration-aide' );?>">
				<input type="radio" name="csds_emailVerify" id="csds_emailVerify" value="1" <?php
				if ( $options['verify_email'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
				</span>
				<span title="<?php _e( 'Select this option to make new users NOT WAIT for email verification before account is activated and new user account is created and create new user account right away using the normal WordPress Registration Process',  'user-registration-aide' );?>">
				<input type="radio" name="csds_emailVerify" id="csds_emailVerify" value="2" <?php
				if ( $options['verify_email'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?>
				</span>
				</td>
			</tr>
			
			<?php
			if( is_plugin_active( $plugin ) ){
				?>
				<tr>
					<td colspan="2" width="100%"><?php _e( 'Use Default Buddy Press Registration Form: ', 'user-registration-aide' );?>
					<span title="<?php _e( 'Select this option to to use the default BuddyPress registration form',  'user-registration-aide' );?>">
					<input type="radio" name="csds_bpRegister" id="csds_bpRegister" value="1" <?php
					if ( $options['buddy_press_registration'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
					</span>
					<span title="<?php _e( 'Select this option to to use the default WordPress registration Form',  'user-registration-aide' );?>">
					<input type="radio" name="csds_bpRegister" id="csds_bpRegister" value="2" <?php
					if ( $options['buddy_press_registration'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?>
					</span>
					</td>
				</tr>
			<?php
			}
			?>
			<tr>
			<th colspan="2">
			<input type="submit" class="button-primary" name="newUserApprove_submit" id="newUserApprove_submit" value="<?php _e( 'Update New User Approve Options', 'user-registration-aide' ); ?>"  />
			<?php /*
			<div class="submit"><input type="submit" class="button-primary" name="signup_transfer" id="signup_transfer" value="<?php _e('Signups Transfer', 'user-registration-aide'); ?>"  /></div>
			*/
			?>
			</th>
			</tr>
			<?php /*
			<tr>
			<th>
			<div class="submit"><input type="submit" class="button-primary" name="sync_profiles_submit" id="sync_profiles_submit" value="<?php _e('Sync BuddyPress Profiles to WordPress Profiles', 'user-registration-aide'); ?>" title="<?php _e('Syncs BuddyPress Profiles to WordPress Profiles ONLY USE WHEN FIRST INSTALLING!!! Everything is Automatic After the First Sync!', 'user-registration-aide'); ?>"  /></div>
			</th>
			<th>
			<div class="submit"><input type="submit" class="button-primary" name="sync_wp_profiles_submit" id="sync_wp_profiles_submit" value="<?php _e('Sync WordPress Profiles to BuddyPress Profiles', 'user-registration-aide'); ?>" title="<?php _e('Syncs WordPress Profiles and Fields to BuddyPress xprofile fields xprofile data. ONLY USE WHEN FIRST INSTALLING!!! Everything is Automatic After the First Sync!', 'user-registration-aide'); ?>"  /></div>
			</th>
			</tr>
			*/?>
		</table>
		<?php
	}
}