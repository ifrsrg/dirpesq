<?php

/**
 * Class  URA_REGISTRATION_FORM_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_REGISTRATION_FORM_VIEW
{
	//class public variables
	public static $instance;
	public $numb1;
	public $numb2;
	public $op;
		
	/**	
	 * function display_password_fields
	 * Add password input fields to the new user registration page
	 * @since 1.5.1.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns 
	*/
	
	function display_password_fields(){
		$fieldKey = ( string ) 'pass1';
		$fieldKey2 = ( string ) 'pass2';
		$fieldName = ( string ) __( 'Password', 'user-registration-aide' );
		$fieldName2 = ( string ) __( 'Confirm Password', 'user-registration-aide' );
		$label = apply_filters( 'create_kf_label', $fieldKey, $fieldName );
		?>
		<p class="user-pass1-wrap">
			<label for="pass1"><?php _e( 'Password', 'user-registration-aide' ); ?><?php echo $label; ?></label><br />
			<div class="wp-pwd">
				<span class="password-input-wrapper">
					<input type="password" data-reveal="0" data-pw="" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" aria-describedby="pass-strength-result" />
					<div id="pass-strength-result" class="hide-if-no-js" size="20" aria-live="polite">
					<?php _e( 'Strength Indicator', 'user-registration-aide' ); ?>
					</div>
				</span>
			</div>
		</p>
		<label for="xwrd2"><?php _e( 'Confirm Password' ) ?></label><br />
		<input type="password" name="xwrd2" id="xwrd2" class="input" size="20" value="" autocomplete="off" />
		<br class="clear" />
		<?php
		/*
		<p class="user-pass2-wrap">
			<label for="pass2"><?php _e( 'Confirm new password' ) ?></label><br />
				<input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
		</p>

		<p class="description indicator-hint"><?php echo wp_get_password_hint(); ?></p>
		$fieldKey = ( string ) 'pass1';
		$fieldName = ( string ) 'Password';
		$label = apply_filters( 'create_kf_label', $fieldKey, $fieldName );
		?>
		<p class="user-pass1-wrap">
			<label for="pass1"><?php _e( 'Password', 'user-registration-aide' ); ?><?php echo $label; ?></label><br />
			<div class="wp-pwd">
				<span class="password-input-wrapper">
					<input type="password" data-reveal="1" data-pw="" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" aria-describedby="pass-strength-result" />
				</span>
				<div id="pass-strength-result" class="hide-if-no-js" aria-live="polite"><?php _e( 'Strength Indicator', 'user-registration-aide' ); ?></div>
			</div>
		</p>
		<?php
		*/
	}
	
	/**	
	 * function display_other_fields
	 * Processes fields view for custon fields on sites WITHOUT Theme My Login activated
	 * @since 1.5.1.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $fieldKey field key name, string $fieldName field name title for displaying to end user
	 * @params string $value field value entered by user
	 * @returns 
	*/
	
	function display_other_fields( $fieldKey, $fieldName, $value ){
		if( $fieldKey != 'user_pass' ){
			echo '<br/>';
			$field = new FIELDS_DATABASE();
			$atype = $field->get_field_type( $fieldKey );					
			$name = $fieldKey;
			$id = $fieldKey;
			$value = $value;
			$label = apply_filters( 'create_label', $fieldKey, $fieldName );
			
			if( $atype == 'textbox' ){
				do_action( 'rf_textbox', $label, $name, $id, $value );
			}elseif( $atype == 'radio' ){
				do_action( 'rf_radio', $label, $name, $id, $value );
			}elseif( $atype == 'selectbox' ){
				do_action( 'rf_select', $label, $name, $id, $value );
			}elseif( $atype == 'checkbox' ){
				do_action( 'rf_checkbox', $label, $name, $id, $value );
			}elseif( $atype == 'textarea' ){
				do_action( 'rf_textarea', $label, $name, $id, $value );
			}elseif( $atype == 'datebox' ){
				do_action( 'rf_datebox', $label, $name, $id, $value );
			}elseif( $atype == 'multiselectbox' ){
				do_action( 'rf_multiselect', $label, $name, $id, $value );
			}elseif( $atype == 'number' ){
				do_action( 'rf_number', $label, $name, $id, $value );
			}elseif( $atype == 'url' ){
				do_action( 'rf_url', $label, $name, $id, $value );
			}elseif( $atype == 'hidden' ){
				do_action( 'rf_hidden', $label, $name, $id, $value );
			}else{
			?>
				<p>
				<label><?php _e( $label, 'user-registration-aide'); ?>
				<br />
				<input type="<?php echo $atype; ?>" name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" autocomplete="on" class="input" value="<?php echo $value;?>" size="20" />
				</label>
				</p>
			<?php
			}
			
		}
	}
	
	/**	
	 * function display_other_fields_theme_my_login
	 * Processes fields view for other fields input on sites WITH Theme My Login activated
	 * @since 1.5.1.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $fieldKey field key name, string $fieldName field name title for displaying to end user
	 * @params string $value field value entered by user
	 * @returns 
	*/
	
	function display_other_fields_theme_my_login( $fieldKey, $fieldName, $value ){
		if( $fieldKey != 'user_pass' ){
			echo '<br/>';
			$field = new FIELDS_DATABASE();
			$atype = $field->get_field_type( $fieldKey );					
			$name = $fieldKey;
			$id = $fieldKey;
			$value = $value;
			$label = apply_filters( 'create_label', $fieldKey, $fieldName );
			
			if( $atype == 'textbox' ){
				do_action( 'rf_textbox', $label, $name, $id, $value );
			}elseif( $atype == 'radio' ){
				do_action( 'rf_radio', $label, $name, $id, $value );
			}elseif( $atype == 'selectbox' ){
				do_action( 'rf_select', $label, $name, $id, $value );
			}elseif( $atype == 'checkbox' ){
				do_action( 'rf_checkbox', $label, $name, $id, $value );
			}elseif( $atype == 'textarea' ){
				do_action( 'rf_textarea', $label, $name, $id, $value );
			}elseif( $atype == 'datebox' ){
				do_action( 'rf_datebox', $label, $name, $id, $value );
			}elseif( $atype == 'multiselectbox' ){
				do_action( 'rf_multiselect', $label, $name, $id, $value );
			}elseif( $atype == 'number' ){
				do_action( 'rf_number', $label, $name, $id, $value );
			}elseif( $atype == 'url' ){
				do_action( 'rf_url', $label, $name, $id, $value );
			}elseif( $atype == 'hidden' ){
				do_action( 'rf_hidden', $label, $name, $id, $value );
			}else{
				?>
				<p>
				<label><?php _e( $label, 'user-registration-aide' ); ?>
				<br />
				<input type="text" name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" autocomplete="on" class="input" value="" size="20" />
				</label>
				</p>
				<?php
			}
			
		}
	}
	
	/**	
	 * function display_text_area_fields
	 * Add text area fields to the new user registration page with theme my login plugin inactive
	 * @since 1.5.1.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $fieldKey field key name, string $fieldName field name title for displaying to end user
	 * @params string $value field value entered by user
	 * @returns 
	*/
	
	function display_text_area_fields( $fieldKey, $fieldName, $value ){
		$label = apply_filters( 'create_label', $fieldKey, $fieldName );
		?>
		<p>
		<label><?php _e( 'Description'.$label, 'user-registration-aide' ); ?>
		<br />
		<textarea name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" class="input" value="<?php echo esc_textarea( $value );?>" rows="5" ></textarea>
		</label>
		</p>
		<?php
	}
	
	/**	
	 * function display_text_area_fields_theme_my_login
	 * Add text area fields to the new user registration page with theme my login plugin active
	 * @since 1.5.1.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $fieldKey field key name, string $fieldName field name title for displaying to end user
	 * @params string $value field value entered by user
	 * @returns 
	*/
	
	function display_text_area_fields_theme_my_login( $fieldKey, $fieldName, $value ){
		$label = apply_filters( 'create_label', $fieldKey, $fieldName );
		?>
		<p>
		<label><?php _e( 'Description'.$label, 'user-registration-aide' ); ?></label>
		<br />
		<textarea name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" class="input" value="<?php echo esc_textarea( $value );?>" rows="5" ></textarea>
		</p>
		<?php
	}
		
	/**	
	 * function display_text_area_fields_theme_my_login
	 * Add known fields to the new user registration page with theme my login plugin inactive
	 * @since 1.5.1.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $fieldKey field key name, string $fieldName field name title for displaying to end user
	 * @params string $value field value entered by user
	 * @returns 
	*/
	
	function display_known_fields( $fieldKey, $fieldName, $value ){
		$label = apply_filters( 'create_kf_label', $fieldKey, $fieldName );
		?>
		<p>
		<?php
		if( $fieldKey == 'first_name' ){
			?>
			<label><?php _e( 'First Name', 'user-registration-aide' ); ?><?php echo $label; ?>
			<?php
		}elseif( $fieldKey == 'last_name' ){
			?>
			<label><?php _e( 'Last Name', 'user-registration-aide' ); ?><?php echo $label; ?>
			<?php
		}elseif( $fieldKey == 'nickname' ){
			?>
			<label><?php _e( 'Nickname', 'user-registration-aide' ); ?><?php echo $label; ?>
			<?php
		}elseif( $fieldKey == 'user_url' ){
			?>
			<label><?php _e( 'Website', 'user-registration-aide' ); ?><?php echo $label; ?>
			<?php	
		}else{
			?>
			<label><?php _e( $fieldName, 'user-registration-aide' ); ?><?php echo $label; ?>
			<?php
		}
		?>
		<br />
		<input type="text" name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" autocomplete="on" class="input" value="<?php echo $value;?>" size="20" />
		</label>
		</p>
		<?php
	}
	
	/**	
	 * function display_text_area_fields_theme_my_login
	 * Add known fields to the new user registration page with theme my login plugin active
	 * @since 1.5.1.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $fieldKey field key name, string $fieldName field name title for displaying to end user
	 * @params string $value field value entered by user
	 * @returns 
	*/
	
	function display_known_fields_theme_my_login( $fieldKey, $fieldName, $value ){
		$label = apply_filters( 'create_kf_label', $fieldKey, $fieldName );
		?>
		<p>
		<?php
		if( $fieldKey == 'first_name' ){
			?>
			<label><?php _e( 'First Name'.$label, 'user-registration-aide' ); ?>
			<?php
		}elseif( $fieldKey == 'last_name' ){
			?>
			<label><?php _e( 'Last Name'.$label, 'user-registration-aide' ); ?>
			<?php
		}elseif( $fieldKey == 'nickname' ){
			?>
			<label><?php _e( 'Nickname'.$label, 'user-registration-aide' ); ?>
			<?php
		}elseif( $fieldKey == 'user_url' ){
			?>
			<label><?php _e( 'Website'.$label, 'user-registration-aide' ); ?>
			<?php	
		}else{
			?>
			<label><?php _e( $fieldName.$label, 'user-registration-aide' ); ?>
			<?php
		}
		?>
		<br />
		<input type="text" name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" autocomplete="on" class="input" value="<?php echo $value;?>" size="25" />
		</label>
		</p>
		<?php
	}
	
	/**	
	 * function add_fields_registration_form
	 * Add fields to the new user registration page that the user must fill out when they register
	 * @since 1.0.0
	 * @updated 1.5.2.0
	 * @access public
     * @handles action 'register_form' line 217 user-registration-aide.php
	 * @params
	 * @returns 
	*/
	
	function add_fields_registration_form(){
		$plugin = 'buddypress/bp-loader.php';
		if( is_plugin_active( $plugin ) ){
			$rFields = get_option( 'csds_userRegAide_registrationFields' );
			if( !array_key_exists( 'user_pass', $rFields ) ){
				$rFields['user_pass'] = __( 'Password', 'user-registration-aide' );
			}
			if( !array_key_exists( 'first_name', $rFields ) ){
				$rFields['first_name'] = __( 'First Name', 'user-registration-aide' );
			}
			if( !array_key_exists( 'last_name', $rFields ) ){
				$rFields['last_name'] = __( 'Last Name', 'user-registration-aide' );
			}
			update_option( 'csds_userRegAide_registrationFields', $rFields );
		}
		
		$rfm = new REGISTRATION_FORM_MODEL();
		$rfm->update_registration_fields();
		$fieldKey = '';
		$fieldName = '';
		$regFields = array();
		$options = array();
		//$regFields = get_option( 'csds_userRegAide_registrationFields' );
		$options = get_option( 'csds_userRegAide_Options' );	
		//$field = new FIELDS_DATABASE();
		$type = array();
		$atype	= (string) '';
		
		$regFields = $rfm->reg_fields_array();
		
		if( is_array( $regFields ) ){
			if( !empty( $regFields ) ){
				foreach( $regFields as $fieldKey => $fieldName ){
					if( !empty( $_POST[$fieldKey] ) ){
						foreach( $_POST as $id => $value ){
							if( $id == $fieldKey ){
								$rfm->process_field_viewing( $fieldKey, $fieldName, $value );
							}
						}
					}else{
						$value = ( string ) '';
						$rfm->process_field_viewing( $fieldKey, $fieldName, $value );
					}
				}
			}
		}
		
		if( $options['add_security_question'] == 1 ){
			do_action( 'sq_rf_view' );
		}
		
		if( $options['show_custom_agreement_message'] == 1 ){
			?>
			<br />
			<?php
			_e( $options['agreement_message'], 'user-registration-aide' ); 
			?>
			<br/>
			<?php
		}	
		
		if( $options['show_custom_agreement_checkbox'] == 1 ){
			$avalue = '';
			?>
			<br/>
			<?php
			 _e( 'I Agree with the Terms and Conditions: ', 'user-registration-aide' );
			?>
			<br/>
			<br/>
			<?php
			 if( !empty( $_POST['csds_userRegAide_agree'] ) ){
				$avalue = $_POST['csds_userRegAide_agree'];
				?><input type="radio" id="csds_userRegAide_agree" name="csds_userRegAide_agree" value="1" <?php
				if ( $avalue == 1 ) echo 'checked' ; ?> /> <?php _e( 'I Agree', 'user-registration-aide' ); ?>
					<input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_agree"  value="2" <?php
				if ( $avalue == 2 ) echo 'checked' ; ?> /> <?php _e( 'I Do Not Agree', 'user-registration-aide' ); ?>
				<?php
				echo '<br/>';
			 }else{
				?><input type="radio" id="csds_userRegAide_agree" name="csds_userRegAide_agree" value="1" <?php
				if ( $options['new_user_agree'] == 1 ) echo 'checked' ; ?> /> <?php _e( 'I Agree', 'user-registration-aide' ); ?>
					<input type="radio" id="csds_userRegAide_support" name="csds_userRegAide_agree"  value="2" <?php
				if ( $options['new_user_agree'] == 2) echo 'checked' ; ?> /> <?php _e( 'I Do Not Agree', 'user-registration-aide' ); ?>
				<?php
				echo '<br/>';
			}
		}
		if( $options['show_custom_agreement_link'] == 1 ){
			echo '<br />';
			echo '<a href="'.esc_url( $options['agreement_link'] ).'" target="_blank">'.$options['agreement_title'].'</a>';
			echo '<br />';
		}
				
		// For anti-spammer attempts to prevent bots from registering
		if( $options['activate_anti_spam'] == 1 ){
			$answer = $options['math_answer'];
			$math = new URA_MATH_FUNCTIONS();
			$numbs = array();
			$numbs = $math->random_numbers();
			$this->numb1 = $numbs['first'];
			$this->numb2 = $numbs['second'];
			$operator = $numbs['operator'];
			$op = $math->get_operator( $operator );
			//exit( 'Numbers: '.$numb1.' - '.$numb2.' - '.$op );
			_e( '*Please complete the following arithmatic problem to prove you are human!*:', 'user-registration-aide' ); ?>
			<br />
			<br />
			<p style="text-align: center; border-style: solid; border-width: 1px; padding: 3px;">
			<b>
			<?php _e( $this->numb1, 'user-registration-aide' ); ?><?php _e( ' '.$op.' ', 'user-registration-aide' ); ?><?php _e( $this->numb2.' = ', 'user-registration-aide' ); ?>
			</b>
			</p>
			<br />
			<?php
			$user_answer = '';
			echo '<input  autocomplete="off" style="width: 100%;" type="text" title="'.__( 'Enter the answer to the artithmatic problem here! ***Important: In certain division problems, the answer may not be a whole number so just round UP to the nearest 10th(Example: 5/3 = 1.6666 rounded to the nearest tenth is 1.6!***', 'user-registration-aide' ) . '" value="'. $user_answer . '" name="'.$answer.'" />';
			
		}
		
		if( $options['add_security_question'] == "1" ){
			do_action( 'sq_reg_form_view' );
		}
					
		wp_nonce_field( 'userRegAideRegForm_Nonce', 'userRegAide_RegFormNonce' );
					
		if( $options['select_pass_message'] == 1 ){
			echo '<div style="margin:10px 0;border:1px solid #e5e5e5;padding:10px">';
			echo '<p class="message register" style="margin:5px 0;">';
			_e( $options['registration_form_message'], 'user-registration-aide' );
			echo '</p>';
			echo '</div>';
			?>
		<style type="text/css">
		#reg_passmail{
			display:none;
		}
		</style>
		<?php
		}
			
		if( $options['show_support'] == 1 ){
				echo '<a href="'.$options['support_display_link'].'" target="_blank">' . $options['support_display_name'] . '</a><br/>';
				echo '<br/>';
		}
	}
	
	/**	
	 * function ura_create_new_user
	 * Create a new user after the registration has been validated. Normally,
	 * when a user registers, an email is sent to the user containing their
	 * username and password. The email does not get sent to the user until
	 * the user is approved when using the default behavior of this plugin.
	 * @since 1.5.1.1
	 * @updated 1.5.2.0
	 * @access public
     * @uses register_post
	 * @params string $user_login, string $user_email, WP_Error OBJECT $errors
	 * @returns 
	*/
	
	public function ura_create_new_user( $user_login, $user_email, $errors ) {
		if ( $errors->get_error_code() ) {
			return;
		}
		$rfm = new REGISTRATION_FORM_MODEL();
		$errors = $rfm->verify_new_user_fields( $errors, $user_login, $user_email );
		// create the user
		if( empty( $errors ) ){
			$user_pass = wp_generate_password( 12, false );
			$user_id = wp_create_user( $user_login, $user_pass, $user_email );
			if ( !$user_id ) {
				$errors->add( 'registerfail', sprintf( __( '<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !' ), get_option( 'admin_email' ) ) );
			}
		}
	}
	
	/**	
	 * function ura_show_user_pending_message
	 * Display a message to the user after they have registered
	 * @since 1.5.1.1
	 * @updated 1.5.2.0
	 * @access public
     * @uses registration_errors
	 * @params WP_Error OBJECT $errors
	 * @returns 
	*/
	
	public function ura_show_user_pending_message( $errors ) {
		if( !empty( $errors ) ){
			return $errors;
		}
		if ( !empty( $_POST['redirect_to'] ) ) {
			// if a redirect_to is set, honor it
			wp_safe_redirect( $_POST['redirect_to'] );
			exit();
		}

		// if there is an error already, let it do it's thing
		if( !empty( $errors ) ){
			return $errors;
		}
		if ( $errors->get_error_code() ) {
			return $errors;
		}

		$message = nua_default_registration_complete_message();
		$message = nua_do_email_tags( $message, array(
			'context' => 'pending_message',
		) );
		$message = apply_filters( 'new_user_approve_pending_message', $message );

		$errors->add( 'registration_required', $message, 'message' );

		$success_message = __( 'Registration successful.', 'user-registration-aide' );
		$success_message = apply_filters( 'new_user_approve_registration_message', $success_message );
		//return $errors;

		login_header( __( 'Pending Approval', 'new-user-approve' ), '<p class="message register">' . $success_message . '</p>', $errors );
		login_footer();
		//return $errors;
		// an exit is necessary here so the normal process for user registration doesn't happen
		//exit();
	}
		
} // end class
?>