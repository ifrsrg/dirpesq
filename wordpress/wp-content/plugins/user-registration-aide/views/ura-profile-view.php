<?php

/**
 * Class URA_PROFILE_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_PROFILE_VIEW
{
	
	/** 
	 * function csds_show_user_profile
	 * Show the additional fields added on the user profile page
	 * @since 1.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params WP USER OBJECT $user
	 * @returns
	*/	
	 
	function csds_show_user_profile( $user ){
	 
	 global $current_user, $screen;
		$screen = get_current_screen();
		$screen_id = $screen->id;
		//wp_die( 'PAGE: '.$screen_id );
		$options = get_option( 'csds_userRegAide_Options' );
		$current_role = get_option( 'default_role' );
		$selRole = $options['display_name_role'];
		$show_display_name = $options['show_profile_disp_name'];
		if( $options['custom_display_name'] == 1 && $show_display_name == 2 ){
			$user_displayed = new WP_User( $user->ID );
			$user_role = array_shift( $user->roles );
			if( $selRole == 'all_roles' || $user_role == $selRole ){
				?>
				<script>
					jQuery(document).ready(function() {
						jQuery('#display_name').parent().parent().hide();
					});
				</script>
				<?php
			}
		}
		$user_id = $user->ID;
		$fieldKey = '';
		$fieldName = '';
		//$csds_userRegAide_NewFields = get_option( 'csds_userRegAide_NewFields' );
		
		if( $options['change_profile_title'] == 1 ){
			echo '<h3>'. __( $options['profile_title'], 'user-registration-aide' ).'</h3>';
		}else{
			echo '<h3>'. __( 'User Registration Aide Additional Fields', 'user-registration-aide' ).'</h3>';
		}
		
		$field = new FIELDS_DATABASE();
		$ura_fields = $field->get_all_fields();
		
		echo '<table class="form-table">';
		$current_user = wp_get_current_user();
		if( current_user_can( 'edit_user', $current_user->ID ) || current_user_can( 'edit_users', $current_user->ID ) ){
			if( !empty( $ura_fields ) ){
				foreach( $ura_fields as $object ){
					$fieldKey = $object->meta_key;
					$fieldName = $object->field_name;
					$atype = $object->data_type;
					$label = $fieldName;
					$name = $fieldKey;
					$id = $fieldKey;
					$value = get_user_meta( $user_id, $fieldKey, TRUE );
					
					if( $atype == 'textbox' ){
						do_action( 'profile_textbox', $user, $label, $name, $id, $value );
					}elseif( $atype == 'radio' ){
						do_action( 'profile_radio', $user, $label, $name, $id, $value );
					}elseif( $atype == 'selectbox' ){
						do_action( 'profile_select', $user, $label, $name, $id, $value );
					}elseif( $atype == 'checkbox' ){
						do_action( 'profile_checkbox', $user, $label, $name, $id, $value );
					}elseif( $atype == 'textarea' ){
						do_action( 'profile_textarea', $user, $label, $name, $id, $value );
					}elseif( $atype == 'datebox' ){
						do_action( 'profile_datebox', $user, $label, $name, $id, $value );
					}elseif( $atype == 'multiselectbox' ){
						do_action( 'profile_multiselect', $user, $label, $name, $id, $value );
					}elseif( $atype == 'number' ){
						do_action( 'profile_number', $user, $label, $name, $id, $value );
					}elseif( $atype == 'url' ){
						do_action( 'profile_url', $user, $label, $name, $id, $value );
					}else{
						 ?>
						
						<tr>
						<th>
						<label for="<?php echo $fieldKey ?>"><?php echo $fieldName ?></label>
						</th>
						<td><input type="text" name="<?php echo $fieldKey ?>" id="<?php echo $fieldKey ?>" value="<?php echo esc_attr( get_user_meta( $user_id, $fieldKey, TRUE ) ) ?>" class="regular-text" /></td>
						</tr>
						
						<?php 
					}
				}
			} 
			if( $options['add_security_question'] == 1 ){
				?>
				<tr>
				<th colspan="2">
				<h3><?php _e( 'Security Question Fields are Required!', 'user-registration-aide' );?></h3>
				</th>
				</tr>
				<?php
				do_action( 'sq_profiles_view', $user );
			}
			?>
			</table>
			<br/>
			<?php
			if( $options['show_support'] == 1 ){
				echo '<a target="_blank" href="'.$options['support_display_link'].'">' . $options['support_display_name'] . '</a>';
				echo '<br/>';
			}
			wp_nonce_field( 'userRegAideProfileForm', 'userRegAideProfileNonce' );
			
		}else{
			if( is_user_logged_in() ){ // wordpress or theme bug for some people
				exit( __( 'Naughty, Naughty! You do not have permissions to do this! Profile View', 'user-registration-aide' ) );
			}else{
				wp_safe_redirect( wp_login_url() );
				exit;
			}
		}
	}
}