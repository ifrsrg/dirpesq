<?php

/**
 * Class URA_SUPPORT_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_SUPPORT_VIEW
{
	/**
	 * Function show_support_section
	 * @handles custom action for show support 'show_support' $ura line 240 and support section view
     * @since 1.3.0
     * @updated 1.5.3.8
	 * @params
     * @access public
     * @author Brian Novotny
     * @website http://creative-software-design-solutions.com
    */
	
	function show_support_section(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$options = get_option('csds_userRegAide_Options');
			?>
			
				<table class="csds_support">
					<tr>
						<th class="csds_support_links" colspan="4"><a href="http://creative-software-design-solutions.com" target="_blank">Creative Software Design Solutions</a></th>
					</tr>
					<tr>
						<th class="csds_support_th" colspan="4"><?php _e( 'Please show your support & appreciation and help us out with a donation!', 'user-registration-aide' );?></th>
					</tr>
					<tr>
						<td>
						<p><?php _e( 'Show Plugin Support: ', 'user-registration-aide' );?><input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_support"  value="1" <?php
						if ( $options['show_support'] == 1 ) echo 'checked' ;?>/><?php _e( 'Yes', 'user-registration-aide' );?> 
						<input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_support"  value="2"<?php
						if ( $options['show_support'] == 2 ) echo 'checked' ;?>/><?php _e( 'No', 'user-registration-aide' );?>
						<div class="submit">
						<input type="submit" class="button-primary" name="csds_userRegAide_support_submit" id="csds_userRegAide_support_submit" value="<?php _e( 'Update Plugin Support', 'user-registration-aide' );?>"/>
						</div>
						</td>
						<td>
						<h2 class="support"><?php _e( 'Plugin Configuration Help', 'user-registration-aide' );?></h2>
						<?php
						echo '<ul>';
						echo '<li><a href="http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/" target="_blank">Plugin Page & Screenshots</a></li>';
						echo '</ul>';
						echo '</td>';
						echo '<td>';
						echo '<h2 class="support">'.__( 'Coming Soon 1.5.4.0 Private Posts, ', 'user-registration-aide' );
						echo '<br/>';
						echo __( ' New User Approval, ', 'user-registration-aide' );
						echo '<br/>';
						echo __( ' and Login Protection Features!', 'user-registration-aide' ).'</h2>';
						echo '<h2 class="support">'.__( 'Check Official Website', 'user-registration-aide' ).'</h2>';
						echo '<ul>';
						echo '<li><a href="http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/" target="_blank">Check official website for live demo</a></li></ul></td>';?>
						<td>
						</form>
							<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_new">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="AUKQZVNVH5RSG">
							<table>
							<tr><td><input type="hidden" name="on0" value="Donations">Donations</td></tr><tr><td><select name="os0">
								<option value="Option 1">Option 1 $50.00 USD</option>
								<option value="Option 2">Option 2 $75.00 USD</option>
								<option value="Option 3">Option 3 $100.00 USD</option>
								<option value="Option 4">Option 4 $125.00 USD</option>
								<option value="Option 5">Option 5 $150.00 USD</option>
								<option value="Option 6">Option 6 $175.00 USD</option>
								<option value="Option 7">Option 7 $200.00 USD</option>
							</select> </td></tr>
							</table>
							<input type="hidden" name="currency_code" value="USD">
							<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
							</form>
							<form>
						</td>
					</tr>
				</table>
				
			<?php
		}		
	}
}