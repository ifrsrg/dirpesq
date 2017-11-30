<?php

/**
 * Class  REGISTRATION_FORM_MSGS_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class REGISTRATION_FORM_MSGS_VIEW
{
	
	/**	
	 * Function rf_msgs_view
	 * Handles the view for the registration form messages admin settings page
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params array $options plugin settings to fill in fields for editing 
	 * @returns
	*/
	
	function rf_msgs_view( $options ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			?>				
			<table class="regForm" width="100%">
				<tr>
					<th colspan="2">
					<?php _e( 'Add And Edit Your Own Custom Messages to Top and Bottom of Login & Registration Forms:', 'user-registration-aide' );?>
					</th>
				</tr>
				<tr>
					<th colspan="2">
					<?php _e( 'Due to Internationalization Issues, These Custom Messages are NOT Translatable so Enter Them in the Primary Language Your Site Will be Using:', 'user-registration-aide' );?>
					</th>
				</tr>
				<tr>
					<td colspan="2">
					<?php _e( 'Choose to add a special message to top of the registration form:', 'user-registration-aide' );?>
					<span title="<?php _e( 'Add your own custom messages to the top of the Wordpress login-registration-lost-password pages', 'user-registration-aide' );?>">
					<input type="radio" name="csds_select_RegFormLoginMessage" id="csds_select_RegFormLoginMessage" value="1" <?php
					if ( $options['show_login_message'] == 1 ) echo 'checked' ;?>/> <?php _e( 'Yes', 'user-registration-aide'); ?></span>
					<span title="<?php _e( 'Use the default Wordpress messages for the login-registration-lost-password pages', 'user-registration-aide' );?>">
					<input type="radio" name="csds_select_RegFormLoginMessage" id="csds_select_RegFormLoginMessage" value="2" <?php
					if ( $options['show_login_message'] == 2 ) echo 'checked' ;?>/> <?php _e( 'No', 'user-registration-aide' ); ?> </span>
					</td>
				</tr>
				<tr>						
					<td width="40%">
					<?php _e( 'Enter new Login form message for top of login form here: ', 'user-registration-aide' );?>
					</td>
					<td width="60%">
					<textarea name="csds_RegFormTopLogin_Message" id="csds_RegFormTopLogin_Message" class="regForm" rows="1" title="<?php _e( 'Enter a custom message here for top of login form!', 'user-registration-aide' );?>"><?php _e( esc_textarea( $options['login_message'] ), 'user-registration-aide' ) ;?></textarea>
					</td>
				</tr>
				<tr>
					<td width="40%"><?php _e( 'Enter new Registration form message for top of page here: ', 'user-registration-aide' );?></td>
					<td width="60%">
					<textarea name="csds_RegFormTopRegistration_Message" id="csds_RegFormTopLogin_Message" class="regForm" rows="1" title="<?php _e( 'Enter a custom message here for top of registration form!', 'user-registration-aide' );?>"><?php _e( esc_textarea( $options['reg_top_message'] ), 'user-registration-aide' );?></textarea>
					</td>
				</tr>
				<tr>
					<td width="40%"><?php _e( 'Enter additional new Login form message for top of login page here: ', 'user-registration-aide' );?>
					</td>
					<td width="60%">
					<textarea name="csds_LoginFormLogin_Message" id="csds_LoginFormLogin_Message" class="regForm" rows="1" title="<?php _e( 'Enter an additional new custom message here for top of login form!', 'user-registration-aide' );?>"><?php _e( esc_textarea( $options['login_messages_login'] ),'user-registration-aide' );?></textarea>
					</td>
				</tr>
				<tr>
					<td width="40%"><?php _e( 'Enter new Login form message for top of login page when users log out here: ', 'user-registration-aide' );?>
					</td>
					<td width="60%">
					<textarea name="csds_LoginFormLoggedOut_Message" id="csds_LoginFormLoggedOut_Message" class="regForm" rows="1" title="<?php _e('Enter a custom message here for top of login form after user is logged out!', 'user-registration-aide');?>"><?php _e( esc_textarea( $options['login_messages_logged_out'] ), 'user-registration-aide' );?></textarea>
					</td>
				</tr>
				<tr>
					<td width="40%"><?php _e( 'Enter new Login Form message for top of Login Page after user successfully registers here: ', 'user-registration-aide' );?>
					</td>
					<td width="60%">
					<textarea name="csds_LoginFormRegisteredSuccess_Message" id="csds_LoginFormRegisteredSuccess_Message" class="regForm" rows="1" title="<?php _e( 'Enter a custom message here for top of login form after user has successfully registered!', 'user-registration-aide' );?>"><?php _e( esc_textarea( $options['login_messages_registered']),'user-registration-aide' );?></textarea>
					</td>
				</tr>
				<tr>
					<td width="40%"><?php _e( 'Enter new Lost Password Form message for top of Lost Password Page Here: ', 'user-registration-aide' );?>
					</td>
					<td width="60%">
					<textarea name="csds_LostPassword_Message" id="csds_LostPassword_Message" class="regForm" rows="1" title="<?php _e( 'Enter a custom message here for top of lost password form if user attempts to recover lost password!', 'user-registration-aide' );?>"><?php _e( esc_textarea( $options['login_messages_lost_password'] ),'user-registration-aide' );?></textarea>
					</td>
				</tr>
				<tr>
					<td width="40%"><?php _e( 'Enter new Lost Password Form Check Email Message for top of Lost Password Page Here After User has Submitted Lost Password Reset Request: ', 'user-registration-aide' );?>
					</td>
					<td width="60%">
					<textarea name="csds_LostPassword_confirmMessage" id="csds_LostPassword_confirmMessage" class="regForm" rows="1" title="<?php _e( 'Enter a custom message here for top of lost password form after user requests lost password reset!', 'user-registration-aide' );?>"><?php _e( esc_textarea( $options['reset_password_confirm'] ),'user-registration-aide' );?></textarea>
					</td>
				</tr>
				<?php
				$plugin = 'new-user-approve/new-user-approve.php';
				$class = 'pw_new_user_approve';
				if( class_exists( $class ) && is_plugin_active( $plugin ) ){
					?>
					<tr>
						<td width="40%"><?php _e( 'Enter New User Approve Registration Form Message Here: ', 'user-registration-aide' );?>
						</td>
						<td width="60%">
						<textarea name="csds_nua_pre_reg_msg" id="csds_nua_pre_reg_msg" class="regForm" rows="1" title="<?php _e( 'Enter a custom message here for top of lost password form if user attempts to recover lost password!', 'user-registration-aide' );?>"><?php _e( esc_textarea( $options['nua_pre_register_msg'] ),'user-registration-aide' );?></textarea>
						</td>
					</tr>
					<tr>
						<td width="40%"><?php _e( 'Enter New User Approve Successful Registration Form Message Here Part 1: ', 'user-registration-aide' );?>
						</td>
						<td width="60%">
						<textarea name="csds_nua_post_reg_msg_1" id="csds_nua_post_reg_msg_1" class="regForm" rows="1" title="<?php _e( 'Enter a custom message here for top of lost password form if user attempts to recover lost password!', 'user-registration-aide' );?>"><?php _e( esc_textarea( $options['nua_post_register_msg_1'] ),'user-registration-aide' );?></textarea>
						</td>
					</tr>
					<tr>
						<td width="40%"><?php _e( 'Enter New User Approve Successful Registration Form Message Here Part 2: ', 'user-registration-aide' );?>
						</td>
						<td width="60%">
						<textarea name="csds_nua_post_reg_msg_2" id="csds_nua_post_reg_msg_2" class="regForm" rows="1" title="<?php _e( 'Enter a custom message here for top of lost password form if user attempts to recover lost password!', 'user-registration-aide' );?>"><?php _e( esc_textarea( $options['nua_post_register_msg_2'] ),'user-registration-aide' );?></textarea>
						</td>
					</tr>
				<?php
				}
				?>
				</table>
				<br/>
				<table class="regForm" width="100%">
				<tr>
					<td colspan="2"><?php _e( 'Choose to add your own special message to bottom of registration form: ', 'user-registration-aide' );?>
					<span title="<?php _e( 'Select this option to add your own custom message to the bottom of the registration form', 'user-registration-aide' );?>">
					<input type="radio" name="csds_select_RegFormMessage" id="csds_select_RegFormMessage" value="1" <?php
					if( $options['select_pass_message'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option to use the default Wordpress message on the bottom of the registration form',  'user-registration-aide' );?>">
					<input type="radio" name="csds_select_RegFormMessage" id="csds_select_RegFormMessage" value="2" <?php
					if( $options['select_pass_message'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span></td>
											
				</tr>	
				<tr>
					<td width="40%">
					<?php _e( 'Enter new Custom Bottom Registration Form Message Here: ', 'user-registration-aide' );?>
					</td>
					<td width="60%">
					<textarea name="csds_RegForm_Message" id="csds_RegForm_Message" class="regForm" wrap="soft" rows="1" 
					title="<?php _e( 'Enter a custom message here for bottom of registration form if users can or even can&#39t create their own password!', 'user-registration-aide' );?>"> <?php _e( esc_textarea($options['registration_form_message'] ), 'user-registration-aide' );?></textarea>
					</td>
				</tr>
			</table>
			<br/>
			<table class="regForm" width="100%">
			<tr>
			<td>
			<div class="submit">
			<input type="submit" class="button-primary" name="reg_form_login_message_update" value="<?php _e('Update Top & Bottom Registration Form Message Options', 'user-registration-aide'); ?>"  />
			</div>
			</td>
			</tr>
			</table>
		<?php
		
		}
	}
}