<?php

/**
 * Class LOST_PASSWORD_VIEW
 *
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class LOST_PASSWORD_VIEW
{
	
	/**
	 * function lost_xwrd_view
	 * Handles lost password update page input and errors
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $results of messages and errors for form to display on reload
	*/
	
	function lost_xwrd_view( $results ){
		global $interim_login;
		$line = $results[0];
		$nonce = $results[1];
		$action = $results[2];
		$err = $results[3];
		$user = $results[4];
		$stage = $results[5];
		$value = ( string ) '';
		$options = get_option( 'csds_userRegAide_Options' );
		$actions = array();
		$actions = apply_filters( 'lost_xwrd_actions_array', $actions );
		$login = wp_login_url();
		$theme = wp_get_theme();
		if( $action == null || array_key_exists( $action, $actions ) ){
			?>
			<form name="lostpassform" id="lostpassform" method="post" >
				<?php echo $nonce; //wp_nonce_field( $nonce[0], $nonce[1] ); ?>
				<div class="my-header">
					<h1 class="entry-title"><?php _e( 'Update Lost Password Form:', 'user-registration-aide' ); ?></h1>
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
						<td class="pass-reset">
							<label for="lxwrd_user_login"><?php _e( 'Username or Email:', 'user-registration-aide' ); ?></label>
						</td>
						<?php
						if( !empty( $user ) ){
							$value = $user->user_email;
						}else{
							$value = '';
						}
						?>
						<td class="pass-reset">
							<input type="text" name="lxwrd_user_login" id="lxwrd_user_login" class="reset-xwrd" value="<?php echo $value; ?>" size="20" title="<?php _e( 'Login Name For Site', 'user-registration-aide' ); ?>" />
						</td>
					</tr>
					<?php
					if( $options['add_security_question'] == 1 && $action == 'security_question' || $action == 'invalid_sq_answer' ){
						if( !empty( $user ) ){
							do_action( 'sq_lp_view', $user );
						}
					}
					
					?>
					<tr>
						<td colspan="2"  class="pass-reset-submit">
							<div class="template-submit">
								<input type="submit" class="button-primary" name="lost_xwrd_get" id="lost_xwrd_get" value="<?php _e( 'Get New Password', 'user-registration-aide' ); ?>" />
								<br/>
								<a href="<?php echo $login; ?>" title="Login">Login</a>
							</div>
						</td>
					</tr>
				</table>
			</form>
			<?php
		}else{
			//wp_die( $line );
			$redirect_to = get_site_url();
			?>
			<form method="post" name="lost_password" id="lost_password">
			<?php echo $nonce; ?>
				<div class="my-header">
					<h1 class="entry-title"><?php _e( 'Update Lost Password Form:', 'user-registration-aide' ); ?></h1>
				</div>
				<?php
				if( $action == 'lost_xwrd' || $err >= 1 ){
					?>
					<div class="template-error">
					<?php
					echo  $line;
				
				}elseif( $action == 'lost_xwrd_set' ){
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
				<a href="<?php echo $login; ?>" title="Login">Login</a>
				</div>
			</form>
			<?php
			
		}
	}
	
}

