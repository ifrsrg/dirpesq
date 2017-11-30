<?php

/**
 * Class URA_PROFILE_TITLE_VIEW
	*
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
 */

class URA_PROFILE_TITLE_VIEW
{
	
	/**	
	 * Function prof_title_view
	 * URA profile extra fields title options editing view
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params array $options options settings for plugin page
	 * @returns 
	 */
	
	function prof_title_view( $options ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			//Form for changing profile extra fields title 
			?>
			<table class="regForm" width="100%">
				<tr>
					<th colspan="2">
					<?php _e( 'Change Extra Field Title Options For User Profile Pages:', 'user-registration-aide' );?>
					</th>
				</tr>
				<tr>
					<td width="40%"><?php _e('Choose to change the title for extra fields on the users profile page: ', 'user-registration-aide'); ?>
						<br />
						<span title="<?php _e('Select this option to add your own special title to the extra fields portion of the users profile',  'user-registration-aide');?>">
							<input type="radio" name="csds_change_profile_title" id="csds_change_profile_title" value="1" <?php
							if ($options['change_profile_title'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'user-registration-aide');?></span>
						<span title="<?php _e('Select this option to keep the default title to the extra fields portion of the users profile',  'user-registration-aide');?>">
							<input type="radio" name="csds_change_profile_title" id="csds_change_profile_title" value="2" <?php
							if ($options['change_profile_title'] == 2) echo 'checked' ;?> /> <?php _e('No', 'user-registration-aide'); ?></span></td>
					<td width=60%><?php _e('Extra Fields Title: ', 'user-registration-aide');?>
						<br />
					<input style="width: 90%;" type="text" title="<?php _e(esc_attr('Enter the new title that you would like to have for the extra fields portion on the users profile page here:'), 'user-registration-aide');?>" value="<?php _e(esc_attr($options['profile_title']), 'user-registration-aide');?>" name="csds_profile_title" id="csds_profile_title" /></td>
				</tr>
				<tr>
					<td colspan="2">
					<input type="submit" class="button-primary" name="update_profile_title" value="<?php _e('Update Profile Title Options', 'user-registration-aide'); ?>"  />
					</td>
				</tr>
			</table>
			<?php
		}
	}
}