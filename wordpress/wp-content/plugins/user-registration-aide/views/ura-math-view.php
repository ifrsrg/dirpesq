<?php

/**
 * Class URA_MATH_PROBLEM_VIEW
	*
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
 */

class URA_MATH_PROBLEM_VIEW
{
	
	/**	
	 * Function anti_spam_math_view
	 * URA math problem options editing view
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params array $options options settings for plugin page
	 * @returns 
	 */

	function anti_spam_math_view( $options ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			//Form for adding security math problem to registration form
			?>
			<table class="regForm" width="100%">
				<tr>
					<th colspan="5">
						<?php _e( 'Edit Anti-Bot-Spammer Options for Registration Form:', 'user-registration-aide' );?>
					</th>
				</tr>
				<tr>
					<td>
						<?php _e( 'Choose to add a anti-spammer anti-bot math problem to the registration form to help reduce spammers and bots accessing your site and spamming it: ', 'user-registration-aide' );?>
						<br/>
						<span title="<?php _e('Select this option to activate the anti-spam math problem to registration form',  'user-registration-aide');?>">
							<input type="radio" name="csds_select_AntiBot" id="csds_select_AntiBot" value="1" <?php
							if( $options['activate_anti_spam'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
						</span>
						<span title="<?php _e( 'Select this option to de-activate the anti-spam math problem to registration form',  'user-registration-aide' );?>">
							<input type="radio" name="csds_select_AntiBot" id="csds_select_AntiBot" value="2" <?php
							if( $options['activate_anti_spam'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?>
						</span>
					</td>
					<?php // use division option in the anti-spam math problem ?>
					<td>
						<?php _e( 'Choose this option to use division in the anti-spam math problem:', 'user-registration-aide' );?>
						<br/>
						<span title="<?php _e( 'Select this option to use division in the anti-spam math problem on the registration form',  'user-registration-aide' );?>">
							<input type="radio" name="csds_div_AntiBot" id="csds_div_AntiBot" value="1" <?php
							if( $options['division_anti_spam'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
						</span>
						<span title="<?php _e( 'Select this option to de-activate the anti-spam math problem to registration form',  'user-registration-aide' );?>">
							<input type="radio" name="csds_div_AntiBot" id="csds_div_AntiBot" value="2" <?php
							if( $options['division_anti_spam'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?>
						</span>
					
					</td>
					<?php // use multiplication option in the anti-spam math problem ?>
					<td>
						<?php _e( 'Choose this option to use multiplication in the anti-spam math problem:', 'user-registration-aide' );?>
						<br/>
						<span title="<?php _e( 'Select this option to use multiplication in the anti-spam math problem on the registration form',  'user-registration-aide' );?>">
							<input type="radio" name="csds_multiply_AntiBot" id="csds_multiply_AntiBot" value="1" <?php
							if( $options['multiply_anti_spam'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
						</span>
						<span title="<?php _e( 'Select this option to de-activate the anti-spam math problem to registration form',  'user-registration-aide' );?>">
							<input type="radio" name="csds_multiply_AntiBot" id="csds_multiply_AntiBot" value="2" <?php
							if( $options['multiply_anti_spam'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' );?>
						</span>
					</td>
					<?php // use subtraction option in the anti-spam math problem ?>
					<td>
						<?php _e( 'Choose this option to use subtraction in the anti-spam math problem:', 'user-registration-aide' );?>
						<br/>
						<span title="<?php _e( 'Select this option to use subtraction in the anti-spam math problem on the registration form',  'user-registration-aide' );?>">
							<input type="radio" name="csds_minus_AntiBot" id="csds_minus_AntiBot" value="1" <?php
							if( $options['minus_anti_spam'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
						</span>
						<span title="<?php _e( 'Select this option to de-activate the anti-spam math problem to registration form',  'user-registration-aide' );?>">
							<input type="radio" name="csds_minus_AntiBot" id="csds_minus_AntiBot" value="2" <?php
							if( $options['minus_anti_spam'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?>
						</span>
					</td>
					<?php // use addition option in the anti-spam math problem ?>
					<td>
						<?php _e( 'Choose this option to use addition in the anti-spam math problem:', 'user-registration-aide' );?>
						<br/>
						<span title="<?php _e( 'Select this option to use addition in the anti-spam math problem on the registration form',  'user-registration-aide' );?>">
							<input type="radio" name="csds_add_AntiBot" id="csds_add_AntiBot" value="1" <?php
							if( $options['addition_anti_spam'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?>
						</span>
						<span title="<?php _e( 'Select this option to de-activate the anti-spam math problem to registration form',  'user-registration-aide' );?>">
							<input type="radio" name="csds_add_AntiBot" id="csds_add_AntiBot" value="2" <?php
							if( $options['addition_anti_spam'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="5">
						<input type="submit" class="button-primary" name="anti-bot-spammer" value="<?php _e( 'Update Anti-Bot-Spammer Math Problem Options', 'user-registration-aide' ); ?>"  />
					</td>
				</tr>
			</table>
			<?php 
		}
	}
}