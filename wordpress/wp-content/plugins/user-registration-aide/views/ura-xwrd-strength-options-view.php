<?php

/**
 * Class XWRD_STRENGTH_OPTIONS_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class XWRD_STRENGTH_OPTIONS_VIEW
{
	
	/** 
	 * function xwrd_strength_settings_view
	 * View for password settings options page
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params array $options
	 * @returns 
	*/	
	
	function xwrd_strength_settings_view( $options ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			//Form for password strength requirements	
			$i1 = (int) 12; 
			$span = array( 'regForm', __( 'Password Strength Requirements:', 'user-registration-aide' ), 'user-registration-aide' );
			do_action( 'start_mini_wrap', $span ); ?>		
			<table class="regForm" width="100%">
				<tr>
					<th colspan="5">
						<?php _e( 'Password Strength Requirements Option Settings:', 'user-registration-aide' );?>
					</th>
				</tr>
				<tr> <?php // Default Password Strength Requirements Options Yes/No ?>
					<td width="50%"><?php _e( 'Use Default Password Strength Requirements: ', 'user-registration-aide' );?>
					<span title="<?php _e( 'Select this option to use the default password strength requirements (Upper Case, Lower Case letters, number and special character and minimum length of 8)', 'user-registration-aide' );?>">
					<input type="radio" name="csds_select_DefaultPSR" id="csds_select_DefaultPSR" value="1" <?php
					if( $options['default_xwrd_strength'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option not to use the default Password Strength Requirements',  'user-registration-aide' );?>">
					<input type="radio" name="csds_select_DefaultPSR" id="csds_select_DefaultPSR" value="2" <?php
					if( $options['default_xwrd_strength'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					</td>
					<?php // Custom Password Strength Requirements Option Yes/No ?>
					<td width="50%"><?php _e( 'Use Custom Password Strength Requirements: ', 'user-registration-aide' );?>
					<span title="<?php _e( 'Select this option to create your own custom password strength requirements', 'user-registration-aide' );?>">
					<input type="radio" name="csds_select_CustomPSR" id="csds_select_CustomPSR" value="1" <?php
					if( $options['custom_xwrd_strength'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option not to use a custom Password Strength Requirements',  'user-registration-aide' );?>">
					<input type="radio" name="csds_select_CustomPSR" id="csds_select_CustomPSR" value="2" <?php
					if( $options['custom_xwrd_strength'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					</td>
				</tr>
				<tr>
					<?php // Custom Password Strength Requirement Password Length ?>
					<td width="50%"><?php _e( 'Require Minimum Password Length: ', 'user-registration-aide' );?>
					<span title="<?php _e( 'Select this option to require a minimum password length in the custom Password Strength Requirements which you can select in the next column', 'user-registration-aide' );?>">
					<input type="radio" name="csds_select_MinXwrdLngth" id="csds_select_MinXwrdLngth" value="1" <?php
					if( $options['require_xwrd_length'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option NOT to require a minimum password length in the custom Password Strength Requirements',  'user-registration-aide' );?>">
					<input type="radio" name="csds_select_MinXwrdLngth" id="csds_select_MinXwrdLngth" value="2" <?php
					if( $options['require_xwrd_length'] == 2 ) echo 'checked' ;?> /> <?php _e('No', 'user-registration-aide'); ?></span>
					</td>
					<td width="50%"><?php _e( 'Password Minimum Length: ', 'user-registration-aide' );?><select <?php // class="fieldOrder" ?> name="csds_xwrdLength" id="csds_xwrdLength" title="<?php __( 'Select the minimum length for the password here', 'user-registration-aide' );?>">
						<?php
						for( $ii = 1; $ii <= $i1; $ii++ ){
							if( $ii == $options['xwrd_length'] ){
								//echo '<option selected="'.$fieldKey.'" >'.$fieldOrder.'</option>';
								echo '<option selected="'.$ii.'" >'.$ii.'</option>';
							}else{
								echo '<option value="'.$ii.'">'.$ii.'</option>';
							}									
						} ?>
					</td>
				</tr>
				<tr>
					<?php // Custom Password Strength Requirement Upper Case Letter ?>
					<td width="50%"><?php _e( 'Require Upper Case Letter: ', 'user-registration-aide' );?>
					<span title="<?php _e( 'Select this option to require an Upper Case Letter in the custom Password Strength Requirements', 'user-registration-aide' );?>">
					<input type="radio" name="csds_select_UCPSR" id="csds_select_UCPSR" value="1" <?php
					if( $options['xwrd_uc'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option NOT to require an Upper Case Letter in the custom Password Strength Requirements',  'user-registration-aide' );?>">
					<input type="radio" name="csds_select_UCPSR" id="csds_select_UCPSR" value="2" <?php
					if( $options['xwrd_uc'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					</td>
					<?php // Custom Password Strength Requirement Lower Case Letter ?>
					<td width="50%"><?php _e( 'Require Lower Case Letter: ', 'user-registration-aide' );?>
					<span title="<?php _e( 'Select this option to require a Lower Case Letter in the password strength requirements', 'user-registration-aide' );?>">
					<input type="radio" name="csds_select_LCPSR" id="csds_select_LCPSR" value="1" <?php
					if( $options['xwrd_lc'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option NOT to require a Lower Case Letter in the password strength requirements',  'user-registration-aide' );?>">
					<input type="radio" name="csds_select_LCPSR" id="csds_select_LCPSR" value="2" <?php
					if( $options['xwrd_lc'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					</td>
				</tr>
				<tr>
					<?php // Custom Password Strength Requirement Number ?>
					<td width="50%"><?php _e( 'Require Number: ', 'user-registration-aide' );?>
					<span title="<?php _e( 'Select this option to require a Number in the custom Password Strength Requirements', 'user-registration-aide' );?>">
					<input type="radio" name="csds_select_NumbPSR" id="csds_select_NumbPSR" value="1" <?php
					if( $options['xwrd_numb'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option NOT to require a Number in the custom Password Strength Requirements',  'user-registration-aide' );?>">
					<input type="radio" name="csds_select_NumbPSR" id="csds_select_NumbPSR" value="2" <?php
					if( $options['xwrd_numb'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					</td>
					<?php // Custom Password Strength Requirement Special Character ?>
					<td width="50%"><?php _e( 'Require Special Character: ', 'user-registration-aide' );?>
					<span title="<?php _e( 'Select this option to require a Special Character','user-registration-aide' );?> (!,@,#,$,%,^,&,*,?,_,~,-,£,(,))<?php _e( ' in the password strength requirements', 'user-registration-aide' );?>">
					<input type="radio" name="csds_select_SCPSR" id="csds_select_SCPSR" value="1" <?php
					if ($options['xwrd_sc'] == 1) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option to NOT require an Lower Case Letter in the password strength requirements',  'user-registration-aide' );?>">
					<input type="radio" name="csds_select_SCPSR" id="csds_select_SCPSR" value="2" <?php
					if ( $options['xwrd_sc'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<input type="submit" class="button-primary" name="psr_update" value="<?php _e( 'Update Password Strength Requirement Options', 'user-registration-aide' ); ?>"  />
					</td>
				</tr>
			</table>
			<?php
		}
	}
}