<?php

/**
 * Class UPDATE_PASSWORD_VIEW
 *
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class UPDATE_PASSWORD_VIEW
{
	
	/**
	 * function update_xwrd_view
	 * Handles password update page view
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function update_xwrd_view( $results ){
		global $interim_login;
		$line = $results[0];
		$nonce = $results[1];
		$action = $results[2];
		$err = $results[3];
		
		$get_action = ( string ) '';
		$actions = array();
		$actions = apply_filters( 'xwrd_update_actions', $actions );
		if( $action == null || array_key_exists( $action, $actions ) ){
			?>
			<form name="resetpassform" id="resetpassform" method="post" >
			<?php echo $nonce; //wp_nonce_field( $nonce[0], $nonce[1] ); ?>
			<div class="my-header">
			
			<h1 class="entry-title"><?php _e( 'Update Password Form:', 'user-registration-aide' ); ?></h1>
			
			</div>
							
			<?php
			if( empty( $action ) || $action == 'error' || $err >= 1 ){
				?>
				<div class="template-error">
				<?php
				echo  $line; 
				?>
				</div>
				<?php
			}else{
				?>
				<div class="template-message">
				<?php
				echo  $line; 
				?>
				</div>
				<?php
			}
			?>
			<table class="pass-reset">
			<tr>
				<th class="pass-reset"><?php _e( 'Field Name:', 'user-registration-aide' ); ?></th>
				<th class="pass-reset"><?php _e( 'Field Value:', 'user-registration-aide' ); ?></th>
			</tr>
			<tr>
				<td class="pass-reset"><label for="user_email"><?php _e( 'E-mail:', 'user-registration-aide' ); ?></label></td>
				<td class="pass-reset"><input type="text" name="user_email" id="user_email" value="" class="reset-xwrd" title="<?php _e( 'Email Address Used When Registered For Site', 'user-registration-aide' ); ?>" /></td>
			</tr>
			<tr>
				<td class="pass-reset"><label for="user_login"><?php _e( 'Username:', 'user-registration-aide' ); ?></label></td>
				<td class="pass-reset"><input type="text" name="user_login" id="user_login" class="reset-xwrd" value="" size="20" title="<?php _e( 'Login Name For Site', 'user-registration-aide' ); ?>" /></td>
			</tr>
			<?php
				switch( $action ){
					case 'lost_password_reset':
						do_action( 'lp_xwrd_fields' );
						break;
					case 'lost_password_reset_error':
						do_action( 'lp_xwrd_fields' );
						break;
					case 'password-never-changed':
						do_action( 'xwrd_fields' );
						break;
					case 'expired-password':
						do_action( 'xwrd_fields' );
						break;
					case 'new-register':
						do_action( 'xwrd_fields' );
						break;
					default:
						do_action( 'xwrd_fields' );
						break;
					
				}	
			?>
			<tr>
				<td colspan="2"  class="pass-reset-submit">
				<div class="template-submit">
				<input type="submit" class="button-primary" name="update_xwrd-reset" id="update_xwrd-reset" value="<?php _e( 'Update Password', 'user-registration-aide' ); ?>" />
				</td>
			</tr>
			
			</table>
			
			
			</form>
			<?php
		}else{
			//wp_die( $line );
			$redirect_to = get_site_url();
			?>
			<form method="post" name="change_password" id="change_password">
			<?php echo $nonce; //wp_nonce_field( $nonce[0], $nonce[1] ); ?>
			<div class="my-header">
			
			<h1 class="entry-title"><?php _e( 'Update Password Form:', 'user-registration-aide' ); ?></h1>
			
			</div>
			<?php
			if( $action == 'error' || $err >= 1 ){
				?>
				<div class="template-error">
				<?php
				echo  $line;
			
			}elseif( $action == 'success' ){
				?>
				<div class="template-message">
				<?php
				echo  $line;
					
			}else{
				?>
				<div class="template-message">
				<?php
				echo  $line;
			}
			?>
			
				</div>
			</form>
			<?php
			
		}
	}
	
	/**
	 * function lp_xwrd_fields_vie
	 * Handles lost password password update password fields view
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function lp_xwrd_fields_view(){
		?>
		<tr>
			<td>
				<div class="user-pass1-wrap">
				<p>
					<label for="pass1"><?php _e( 'New Password', 'user-registration-aide' ); ?></label>
				</p>
			</td>
			<td>
					<div class="wp-pwd">
						<span class="password-input-wrapper">
							<input type="password" data-reveal="0" data-pw="" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" aria-describedby="pass-strength-result" />
						</span>
						<div id="pass-strength-result" class="hide-if-no-js" aria-live="polite">
							<?php _e( 'Strength Indicator', 'user-registration-aide' ); ?>
						</div>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="pass-reset">
				<label for="xwrd2"><?php _e( 'Confirm Password:', 'user-registration-aide' ); ?></label>
			</td>
			<td class="pass-reset">
				<input name="xwrd2" type="password" id="xwrd2" class="reset-xwrd" size="20" value="" autocomplete="off" title="<?php _e( 'Confirm Your New Password For This Site, MUST MATCH NEW PASSWORD!', 'user-registration-aide' ); ?>" />
			</td>
		</tr>
		<?php
	}
	
	/**
	 * function xwrd_fields_view
	 * Handles password update password fields view
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function xwrd_fields_view(){
		?>
		<tr>
			<td class="pass-reset"><label for="old_pass1"><?php _e( 'Old Password:', 'user-registration-aide' ); ?></label></td>
			<td class="pass-reset"><input type="password" name="old_pass1" id="old_pass1" class="reset-xwrd" size="20" value="" autocomplete="off" title="<?php _e( 'Password Sent to You When Registered OR Current Password For Site', 'user-registration-aide' ); ?>" /></td>
		</tr>
		<tr>
			<td>
				<div class="user-pass1-wrap">
				<p>
					<label for="pass1"><?php _e( 'New Password', 'user-registration-aide' ); ?></label>
				</p>
			</td>
			<td>
					<div class="wp-pwd">
						<span class="password-input-wrapper">
							<input type="password" data-reveal="0" data-pw="" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" aria-describedby="pass-strength-result" />
						</span>
						<div id="pass-strength-result" class="hide-if-no-js" aria-live="polite">
							<?php _e( 'Strength Indicator', 'user-registration-aide' ); ?>
						</div>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="pass-reset">
				<label for="xwrd2"><?php _e( 'Confirm Password:', 'user-registration-aide' ); ?></label>
			</td>
			<td class="pass-reset">
				<input name="xwrd2" type="password" id="xwrd2" class="reset-xwrd" size="20" value="" autocomplete="off" title="<?php _e( 'Confirm Your New Password For This Site, MUST MATCH NEW PASSWORD!', 'user-registration-aide' ); ?>" />
			</td>
		</tr>
		<?php
	}
	
}

