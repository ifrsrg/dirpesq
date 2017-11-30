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

class URA_AGREEMENT_VIEW
{

	/**	
	 * Function agreement_msg_view
	 * URA agreement message options editing view
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params array $options options settings for plugin page
	 * @returns 
	*/
	
	function agreement_msg_view( $options ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			// Form for adding additional agreement to signup for website 
			?>
			<table class="regForm" width="100%">
				<tr>
					<th colspan="3">
						<?php _e( 'Add Your Own Agreement Message and Policy Link with Confirmation of Agreement to Bottom of Registration Form:', 'user-registration-aide' );?>
					</th>
				</tr>
				<tr>
					<td colspan="3"><?php _e('Choose to add a special message to bottom of registration form for new users requiring them to read and agree to terms and conditions of the website:', 'user-registration-aide'); ?></td>
				</tr>
				<tr>
					<td width="25%"><?php _e('Show Custom Link for Agreement/Guidelines/Policy Page: ', 'user-registration-aide');?><br/>
						<span title="<?php _e('Select this option to show link for custom agreement message page on registration page',  'user-registration-aide');?>">
							<input type="radio" id="csds_userRegAide_agreement_link" name="csds_userRegAide_agreement_link" value="1" <?php
							if ( $options['show_custom_agreement_link'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' ); ?></span>
						<span title="<?php _e('Select this option to NOT show link for custom agreement message page on registration page',  'user-registration-aide');?>">
							<input type="radio" id="csds_userRegAide_agreement_link" name="csds_userRegAide_agreement_link"  value="2" <?php
							if ( $options['show_custom_agreement_link'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					</td> 
					<td width="25%"><?php _e( 'Enter a title to display for link to Agreement/Guidelines/Policies URL: ', 'user-registration-aide' );?>
					<input  style="width: 200px;" type="text" title="<?php _e( 'Enter the title for the URL where your agreement/guidelines/policy page is located.', 'user-registration-aide' );?>" value="<?php _e( $options['agreement_title'], 'user-registration-aide' );?>" name="csds_userRegAide_newAgreementTitle" id="csds_userRegAide_newAgreementTitle" /></td>
					<td width="50%"><?php _e('Enter Link to Agreement/Guidelines/Policies URL: ', 'user-registration-aide');?>
					<input  style="width: 350px;" type="text" title="<?php esc_url( _e( 'Enter the URL where your agreement/guidelines/policy page is located . Example: (http://mysite.com/agreement.php)', 'user-registration-aide' ) );?>" value="<?php _e( $options['agreement_link'], 'user-registration-aide' );?>" name="csds_userRegAide_newAgreementURL" id="csds_userRegAide_newAgreementURL" /></td></tr>
				<tr>
					<td width="25%"><?php _e( 'Show Message Confirming Agreement for Agreement/Guidelines/Policy Page: ', 'user-registration-aide' );?><br/>
						<span title="<?php _e( 'Select this option to show custom agreement message on registration page',  'user-registration-aide' );?>">
							<input type="radio" id="csds_userRegAide_show_agreement_message" name="csds_userRegAide_show_agreement_message" value="1" <?php
							if ( $options['show_custom_agreement_message'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' ); ?></span>
						<span title="<?php _e( 'Select this option NOT to show custom agreement message on registration page',  'user-registration-aide' );?>">
							<input type="radio" id="csds_userRegAide_show_agreement_message" name="csds_userRegAide_show_agreement_message"  value="2" <?php
							if ( $options['show_custom_agreement_message'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					</td>
					<td width="25%"><?php _e( 'Show Checkbox Confirming Agreement for Agreement/Guidelines/Policy Page: ', 'user-registration-aide' );?><br/>
						<span title="<?php _e( 'Select this option to show checkbox for user to check stating they agree to the agreement terms and conditions',  'user-registration-aide' );?>">
							<input type="radio" id="csds_userRegAide_agreement_checkbox" name="csds_userRegAide_agreement_checkbox" value="1" <?php
							if ($options['show_custom_agreement_checkbox'] == 1) echo 'checked' ;?> /> <?php _e('Yes', 'user-registration-aide'); ?></span>
						<span title="<?php _e('Select this option NOT to show checkbox for user to check stating they agree to the agreement terms and conditions',  'user-registration-aide');?>">
							<input type="radio" id="csds_userRegAide_agreement_checkbox" name="csds_userRegAide_agreement_checkbox"  value="2" <?php
							if ($options['show_custom_agreement_checkbox'] == 2) echo 'checked' ;?> /> <?php _e('No', 'user-registration-aide'); ?></span>
					</td>
					<td width="50%"><?php _e( 'Add your special message below to add to bottom of registration form if new users must agree to terms or policies:', 'user-registration-aide' );?></td>
				<tr>
					<td colspan="3"><textarea name="csds_RegForm_Agreement_Message" id="csds_RegForm_Agreement_Message" class="regForm" rows="1" 
						title="<?php _e( 'Enter a custom message here for bottom of registration form if users can create their own password or for other reasons!', 'user-registration-aide' );?>"><?php _e( esc_textarea( $options['agreement_message'] ),'user-registration-aide' );?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="3">
					<input type="submit" class="button-primary" name="reg_form_agreement_message_update" value="<?php _e('Update Registration Form Agreement Options', 'user-registration-aide');?>" />
					</td>
				</tr>
			</table>
			<?php
		}
	}
}