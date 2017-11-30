<?php

/**
 * Class URA_EMAIL_CLASS
 *
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_EMAIL_CLASS
{
	
	public $user_approved = '';
	public $ura_approve = '';
	public $approved = '';
	public $email_verified = '';
	public $password_set = '';
	public $ura_verify = '';
	public $verified = '';
	public $bp_approve = '';
	public $bp_registration = '';
	public $emails_sent = '';
	public $email_stage = '';
	public $xwrd = '';
	public $xwrd_key = '';
	public $xwrd_email = '';
	public $activation_key = '';
	public $approval_status = '';
	public $email_activation_url = '';
	public $user_email = '';
	public $user_login = '';
	public $admin_notification = '';
	public $admin_email_verified_notification = '';
	public $user_email_verified_notification = '';
	public $approved_email = '';
	public $page = '';
	public $domain = 'user-registration-aide';
	public $blogname = '';
	public $hashed = '';
	public $table = '';
	public $login_url = '';
	public static $instance;
		
	/**
	 * function create_email_activation_url
	 * Creates email activation url for new user email verification
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params WP_User_Object $user
	 * @returns string $activation_url
	*/
	
	function create_email_activation_url( $user ){
		$this->hashed = apply_filters( 'create_user_key', $user );
		$plugin = 'buddypress/bp-loader.php';
		if( is_plugin_active( $plugin ) ){
			$this->email_activation_url = trailingslashit( bp_get_activation_page() ) . "{$this->hashed}/";
			$this->email_activation_url = esc_url( $this->email_activation_url );
		}else{
			$url = get_site_url();
			$this->email_activation_url = trailingslashit( $url."/ura-email-confirm?&action=verify-email&key=$this->hashed&user_email=$user->user_email/" );
			$this->email_activation_url = esc_url_raw( $this->email_activation_url );
		}
		return $this->email_activation_url;
	}
	
	/**	
	 * Function created_activation_key
	 * Creates new user activation key
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params WP_User Object $user
	 * @returns string $key
	*/
	
	function create_activation_key( $user ){
		global $wpdb;
		
		if ( defined( 'CUSTOM_USER_TABLE' ) ){
			$user_table = CUSTOM_USER_TABLE;
			$temp = explode( '_', $user_table );
			$base_prefix = $temp[0].'_';
			$prefix = $wpdb->prefix;
			if( $base_prefix != $prefix ){
				$this->table = $base_prefix.'users';
			}else{
				$this->table = $wpdb->prefix.'users';
			}
		}else{
			$this->table = $wpdb->prefix.'users';
		}
				
		$sql = "SELECT user_activation_key FROM $this->table WHERE ID = %d";
		$run_query = $wpdb->prepare( $sql, $user->ID );
		$this->hashed = $wpdb->get_var( $run_query );
		if( empty( $this->hashed ) ){
					
			// Generate something random for a password reset key.
			$this->xwrd_key = wp_generate_password( 20, false );

			/** This action is documented in wp-login.php */
			do_action( 'retrieve_password_key', $user->user_login, $this->xwrd_key );

			// Now insert the key, hashed, into the DB.
			if ( empty( $wp_hasher ) ) {
				require_once ABSPATH . WPINC . '/class-phpass.php';
				$wp_hasher = new PasswordHash( 8, true );
			}
			$this->hashed = time() . ':' . $wp_hasher->HashPassword( $this->xwrd_key );
			$wpdb->update( $wpdb->users, array( 'user_activation_key' => $this->hashed ), array( 'user_login' => $user->user_login ) );
		}
		$this->hashed = rawurlencode( $this->hashed );
		return $this->hashed;
	}
	
	/**	
	 * Function password_activation_key_msg
	 * Creates and filters password activation key msg
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params WP_User Object 
	 * @returns string $message
	*/
	
	function password_activation_key_msg( $user ){
		global $wpdb;
		$options = get_option( 'csds_userRegAide_Options' );
		$fields = get_option( 'csds_userRegAide_registrationFields' );
		$url = ( string ) '';
		$this->page = 'ura-update-password';
		$this->ura_approve = $options['new_user_approve'];
		$this->bp_approve = $options['buddy_press_approval'];
		$this->bp_registration = $options['buddy_press_registration'];
		$this->user_login = stripslashes( $user->user_login );
		$this->user_email = stripslashes( $user->user_email );
		$password = ( boolean ) false;
		$message = ( string ) '';
		if( !empty( $fields ) ){
			if( array_key_exists( 'user_pass', $fields ) ){
				$password = true;
			}else{
				$password = false;
			}
		}else{
			$password = false;
		}
		if( $password == true ){
			$this->xwrd = __( 'Password User Entered at Registration', $this->domain ) ."\r\n" ;
		}
		
		// login url
		if( $options['xwrd_change_on_signup'] == 1 ){
			$url = site_url();
			$this->login_url = $url.'/'.$this->page.'/?action=new-register';
		}elseif( $options['xwrd_change_on_signup'] == 2 ){
			$this->login_url = wp_login_url() . "\r\n";
		}else{
			$this->login_url = wp_login_url() . "\r\n";
		}
		
		update_user_meta( $user->ID, 'my-xwrd-email', 'meem' );
		$key = get_password_reset_key( $user );
		if( $this->bp_registration == 2 ){
			if( $password == true ){
				$message .= sprintf( __( 'Password: %s', $this->domain ), $this->xwrd ) . "\r\n";
				$message .= sprintf( __( 'Site Login URL: %s', $this->domain ), $this->login_url ) . "\r\n";
			}elseif( $password == false ){
				$message .= __( 'To set your password, visit the following address:', $this->domain ) . "\r\n\r\n";
				$message .=  '<'.network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login') .'>';
			}
		}elseif( $this->bp_registration == 1 ){
			$message .= sprintf( __( 'Password: %s', $this->domain ), $this->xwrd ) . "\r\n";
			$message .= sprintf( __( 'Site Login URL: %s', $this->domain ), $this->login_url ) . "\r\n";
		}
		
		return $message;
	}
	
	/**
	 * function lost_xwrd_email
	 * Sends lost password email notification and instructions for user to reset 
	 * lost password 
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params WP_User_Object $user_data
	 * @returns true if successful or WP_Error if failed
	*/
	
	function lost_xwrd_email( $user_data ) {
		// verify not new user without admin approval or email verification first
		$options = get_option('csds_userRegAide_Options');
		$msg = ( string ) '';
		$this->ura_approve = ( int ) 0;
		$this->ura_approve = $options['new_user_approve'];
		$this->ura_verify = $options['verify_email'];
		$valid_reset = ( boolean ) false;
		// Redefining user_login ensures we return the right case in the email.
		$user_id = $user_data->ID;
		$this->user_login = $user_data->user_login;
		$this->user_email = $user_data->user_email;
		$this->key = get_password_reset_key( $user_data );
		$this->email_activation_url = $this->create_email_activation_url( $user_data );
		// get link for lost passowrd reset if admin using password check options
		$link = apply_filters( 'lost_xwrd_email_link', $user_login );
		if ( is_wp_error( $key ) ) {
			return $key;
		}
		//wp_die( 'MY LOST PASSWORD EMAIL' );
		if( $this->ura_approve == 2 && $this->ura_verify == 2 ){
			$message = __( 'Someone has requested a password reset for the following account:', $this->domain ) . "\r\n\r\n";
			$message .= network_home_url( '/' ) . "\r\n\r\n";
			$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
			$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', $this->domain ) . "\r\n\r\n";
			$message .= __( 'To reset your password, visit the following address:', $this->domain ) . "\r\n\r\n";
			$valid_reset = true;
		}
		
		if( $this->ura_approve == 1 && $this->ura_verify == 1 ){
			$this->approved = get_user_meta( $user_id, 'approval_status', true );
			$this->verified = get_user_meta( $user_id, 'email_verification', true );
			if( $this->approved == 'pending' ){
				$msg = __( 'Your Account has not been Approved by an Administrator yet, you cannot reset your password until your account has been Approved!', $this->domain );
				$msg .= "\r\n\r\n";
			}
			if( $this->verified == 'unverified' ){
				$msg .= __( 'Your Email Address for this Account has not yet been Verified. You cannot reset your passowrd until your email address has been verified!', $this->domain ). "\r\n\n";
				$msg .= sprintf( __( "To Verify Your Email for Account %s Please click the following link to Verify Your E-mail: ", $this->domain  ), $this->user_login ). "\r\n\n";
				$msg .= '<'.$this->email_activation_url. '>'."\r\n\n";
			}
			if( !empty( $msg ) ){
				$message = __( 'Someone has requested a password reset for the following account:', $this->domain ) . "\r\n\r\n";
				$message .= network_home_url( '/' ) . "\r\n\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
				$message .= $msg;
				$valid_reset = false;
			}else{
				$message = __( 'Someone has requested a password reset for the following account:', $this->domain ) . "\r\n\r\n";
				$message .= network_home_url( '/' ) . "\r\n\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
				$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', $this->domain ) . "\r\n\r\n";
				$message .= __( 'To reset your password, visit the following address:', $this->domain ) . "\r\n\r\n";
				$valid_reset = true;
			}
		}elseif( $this->ura_approve == 1 && $this->ura_verify == 2 ){
			$this->approved = get_user_meta( $user_id, 'approval_status', true );
			if( $this->approved == 'pending' ){
				$message = __( 'Someone has requested a password reset for the following account:', $this->domain ) . "\r\n\r\n";
				$message .= network_home_url( '/' ) . "\r\n\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
				$message = __( 'Your Account has not been Approved by an Administrator yet, you cannot reset your password until your account has been Approved!', $this->domain );
				$valid_reset = false;
			}else{
				$message = __( 'Someone has requested a password reset for the following account:', $this->domain ) . "\r\n\r\n";
				$message .= network_home_url( '/' ) . "\r\n\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
				$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', $this->domain ) . "\r\n\r\n";
				$message .= __( 'To reset your password, visit the following address:', $this->domain ) . "\r\n\r\n";
				$valid_reset = true;
			}
		}elseif( $this->ura_approve == 2 && $this->ura_verify == 1 ){
			$this->verified = get_user_meta( $user_id, 'email_verification', true );
			if( $this->verified == 'unverified' ){
				$message = __( 'Someone has requested a password reset for the following account:', $this->domain ) . "\r\n\r\n";
				$message .= network_home_url( '/' ) . "\r\n\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
				$message .= __( 'Your Email Address for this Account has not yet been Verified. You cannot reset your passowrd until your email address has been verified!', $this->domain ) . "\r\n\r\n";
				$message .= sprintf( __( "To Verify Your Email for Account %s Please click the following link to Verify Your E-mail: ", $this->domain  ), $this->user_login ). "\r\n\n";
				$message .= '<'.$this->email_activation_url. '>'."\r\n\n";
				$valid_reset = false;
			}else{
				$message = __( 'Someone has requested a password reset for the following account:', $this->domain ) . "\r\n\r\n";
				$message .= network_home_url( '/' ) . "\r\n\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
				$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.', $this->domain ) . "\r\n\r\n";
				$message .= __( 'To reset your password, visit the following address:', $this->domain ) . "\r\n\r\n";
				$valid_reset = true;
			}
		}
		if( $valid_reset == true ){
			if( empty( $link ) ){
				$message .= '<' . network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $this->user_login ), 'login' ) . ">\r\n";
			}else{
				$message .= '<' . $link.'&key='. $key .'&login=' . rawurlencode( $this->user_login ) . '>\r\n';
			}
		}
		if ( is_multisite() ){
			$this->blogname = $GLOBALS['current_site']->site_name;
		}else{
			/*
			 * The blogname option is escaped with esc_html on the way into the database
			 * in sanitize_option we want to reverse this for the plain text arena of emails.
			 */
			$this->blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		}
		
		$title = sprintf( __('[%s] Lost Password Reset', $this->domain ), $this->blogname );

		if ( $message && !wp_mail( $this->user_email, wp_specialchars_decode( $title ), $message ) ){
			wp_die( __( 'The email could not be sent.', $this->domain ) . "<br />\n" . __( 'Possible reason: your host may have disabled the mail() function.', $this->domain ) );
		}
		return true;
	}
	
	/**
	 * function set_password_email_instructions
	 * Sends email notification and instructions for user to set 
	 * password for new account if password not on registration form
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params WP_User_Object $user
	 * @returns 
	*/
	
	function set_password_email_instructions( $user ){
		$this->blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$this->user_login = stripslashes( $user->user_login );
		$this->user_email = stripslashes( $user->user_email );
		$this->emails_sent = get_user_meta( $user->ID, 'emails_sent', true );
		$this->approval_status = get_user_meta( $user->ID, 'approval_status', true );
		$e_verified = get_user_meta( $user->ID, 'password_set', true );
		if( $this->approval_status == 'approved' ){
			if( $e_verified == 2 ){
				$message = sprintf( __( 'Thank you for registering with %s!', $this->domain ), $this->blogname ) . "\r\n\n";
				$message .= sprintf( __( 'You need to follow the instructions below to finalize', $this->domain ) ) . "\r\n\n";
				$message .= sprintf( __( 'the registration process and set your new password so ', $this->domain ) ) . "\r\n\n";
				$message .= sprintf( __( 'you can login and start using your new account!', $this->domain ) ) . "\r\n\n";
				$message .= sprintf( __( 'Credentials:', $this->domain ) ). "\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n";
				$message .= apply_filters( 'user_password_key', $user );
				if( @wp_mail( $this->user_email, sprintf( __( '[%s] Finalize New User Account Instructions' ), $this->blogname ), $message ) ){
					$this->emails_sent++;
					update_user_meta( $user->ID, 'emails_sent', $this->emails_sent );
				}
			}
		}else{
			if( $e_verified == 2 ){
				$message = sprintf( __( 'Thank you for registering with %s!', $this->domain ), $this->blogname ) . "\r\n\n";
				$message .= sprintf( __( 'Your Account needs Approval for an ', $this->domain ) ) . "\r\n\n";
				$message .= sprintf( __( 'Administrator Before  ', $this->domain ) ) . "\r\n\n";
				$message .= sprintf( __( 'you can login and start using your new account!', $this->domain ) ) . "\r\n\n";
				$message .= sprintf( __( 'Credentials:', $this->domain ) ). "\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n";
				if( @wp_mail( $this->user_email, sprintf( __( '[%s] New User Account Approval' ), $this->blogname ), $message ) ){
					$this->emails_sent++;
					update_user_meta( $user->ID, 'emails_sent', $this->emails_sent );
				}
			}
		}
	}
	
	/**
	 * function registration_completed_email_instructions
	 * Sends email notification and confirms registration process complete and new user account ready to use
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params WP_User_Object $user
	 * @returns 
	*/
	
	function registration_completed_email_instructions( $user ){
		$options = get_option( 'csds_userRegAide_Options' );
		$this->page = 'ura-update-password';
		if( $options['xwrd_change_on_signup'] == 1 ){
			$url = site_url();
			$this->login_url = $url.'/'.$this->page.'/?action=new-register';
		}elseif( $options['xwrd_change_on_signup'] == 2 ){
			$this->login_url = wp_login_url() . "\r\n";
		}
		$this->blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$this->user_login = stripslashes( $user->user_login );
		$this->user_email = stripslashes( $user->user_email );
		$this->emails_sent = get_user_meta( $user->ID, 'emails_sent', true );
		$this->xwrd = 'User Entered';
		$this->ura_approve = $options['new_user_approve'];
		$this->ura_verify = $options['verify_email'];
		$this->approval_status = get_user_meta( $user->ID, 'approval_status', true );
		$this->email_verified = get_user_meta( $user->ID, 'email_verification', true );
		if( ( $this->ura_approve == 1 || $this->ura_verify ) &&  $this->approval_status == 'approved' &&  $this->email_verified == 'verified' ){
			$message = sprintf( __( 'Thank you for registering with %s!', $this->domain ), $this->blogname ) . "\r\n\n";
			$message .= sprintf( __( 'Your account is all ready to go!', $this->domain ) ) . "\r\n\n";
			$message .= sprintf( __( 'You can now login and start using your new account!', $this->domain ) ) . "\r\n\n";
			$message .= sprintf( __( 'Credentials:', $this->domain ) ). "\r\n";
			$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n";
			$message .= sprintf( __( 'Password: %s', $this->domain ), $this->xwrd ) . "\r\n";
			$message .= sprintf( __( 'Login URL: %s', $this->domain ), $this->login_url ) . "\r\n";
			if( @wp_mail( $this->user_email, sprintf( __( '[%s] New User Account Setup Completed' ), $this->blogname ), $message ) ){
				$this->emails_sent++;
				update_user_meta( $user->ID, 'emails_sent', $this->emails_sent );
			}
		}
		return;
	}
	
	/**	
	 * Function ura_new_user_notification
	 * Sends User Email Notification When First Signing Up & replaces wp_new_user_notification for user email portion
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params  int $user_id
	 * @returns
	*/
	
	function ura_new_user_notification( $user_id ){
		global $screen;
		$screen = get_current_screen();
		$options = get_option( 'csds_userRegAide_Options' );
		if( !empty( $screen ) ){
			if( $screen->id == 'user' ){
				$user = new WP_User( $user_id );
				$user_email = $user->user_email;
				$newuser_key = substr( md5( $user_id ), 0, 5 );
				$caps = get_user_meta( $user->ID, 'wp_capabilities', true );
				$roles = array_keys( ( array )$caps );
				add_option( 'new_user_' . $newuser_key, array( 'user_id' => $user_id, 'email' => $user->user_email, 'role' => $roles[0] ) );
				$message = __( 'Hi,

You\'ve been invited to join \'%1$s\' at
%2$s with the role of %3$s.

Please click the following link to confirm the invite:
%4$s' );
				wp_mail( $user_email, sprintf( __( '[%s] Joining confirmation' ), wp_specialchars_decode( get_option( 'blogname' ) ) ), sprintf( $message, get_option( 'blogname' ), home_url(), wp_specialchars_decode( translate_user_role( $roles[0] ) ), home_url( "/newbloguser/$newuser_key/" ) ) );
				$redirect = add_query_arg( array('update' => 'add'), 'user-new.php' );
			}
		}
		
		if( is_int( $user_id ) ){
			$user = new WP_User( $user_id );
			$user_object = new WP_User( $user->ID );
		}else{
			$user = get_user_by( 'email', $user_id );
			$user_id = $user->ID;
			$user_object = new WP_User( $user->ID );
		}
				
		$fields = get_option( 'csds_userRegAide_registrationFields' );
		$this->page = 'ura-update-password';
		$this->ura_approve = $options['new_user_approve'];
		$this->ura_verify = $options['verify_email'];
		$this->bp_approve = $options['buddy_press_approval'];
		$this->bp_registration = $options['buddy_press_registration'];
		$this->user_login = stripslashes( $user->user_login );
		$this->user_email = stripslashes( $user->user_email );
		$this->emails_sent = get_user_meta( $user_id, 'emails_sent', true );
		
		if( $options['xwrd_change_on_signup'] == 1 ){
			$url = site_url();
			$this->login_url = $url.'/'.$this->page.'/?action=new-register';
		}elseif( $options['xwrd_change_on_signup'] == 2 ){
			$this->login_url = wp_login_url() . "\r\n";
		}
		
		$this->blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$this->user_approved = get_user_meta( $user_id, 'approval_status', true );
		$this->email_activation_url = $this->create_email_activation_url( $user );
		$this->approval_status = get_user_meta( $user_id, 'approval_status', true );
		$this->email_verified = get_user_meta( $user_id, 'email_verification', true );
		
		$password = ( boolean ) false;
		if( !empty( $fields ) ){
			if( array_key_exists( 'user_pass', $fields ) ){
				$password = true;
			}else{
				$password = false;
			}
		}else{
			$password = false;
		}
		$message = ( string ) '';
		
		switch( $this->bp_approve ){
			
			case 1:
				if( $this->bp_approve == 1 ){
					return;
				}
				break;
			case 2:
				switch ( $this->ura_approve ){
					case 1:
						if( $this->approval_status == 'pending' ){
							if( $this->email_verified == 'verified' ){
								$message = sprintf( __( 'Thank you for registering with %s!', $this->domain ), $this->blogname ) . "\r\n\n";
								if( $this->ura_verify == 1 ){
									$message .= sprintf( __( 'Your E-mail has been Confirmed and Your Registration for %s is awaiting an Administrator Approval', $this->domain ), $this->blogname ) . "\r\n\n";
									$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n";
									if( @wp_mail( $this->user_email, sprintf( __( '[%s] Registration Waiting Administrators Approval', $this->domain ), $this->blogname ), $message ) ){
										$this->emails_sent++;
									}
								}else{
									$message .= sprintf( __( 'Your New User Account is Created and Your Registration for %s is awaiting an Administrator Approval', $this->domain ), $this->blogname ) . "\r\n\n";
									$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n";
									if( @wp_mail( $this->user_email, sprintf( __( '[%s] New User Account Waiting Administrator Approval', $this->domain ), $this->blogname ), $message ) ){
										$this->emails_sent++;
									}
								}	
							}elseif( $this->email_verified == 'unverified' ){
								$message = sprintf( __( "Thanks for registering!", $this->domain ) )."\r\n";
								$message .= sprintf( __( "To complete the activation of your account %s please click the following link to Verify Your E-mail:
								", $this->domain  ), $this->user_login ). "\r\n\n";
								$message .= '<'.$this->email_activation_url. '>'."\r\n\n";
								$message .= sprintf( __( 'After your email is confirmed your Registration for %s will still need a Administrator\'s approval', $this->domain ), $this->blogname ) . "\r\n\n";
								$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n";
								$message .= sprintf( __( 'Site Login URL: %s', $this->domain ), $this->login_url ) . "\r\n";
								if( @wp_mail( $this->user_email, sprintf( __( '[%s] Please Confirm Email and get Administrator Approval', $this->domain ), $this->blogname ), $message ) ){
									$this->emails_sent++;
								}
							}
						}elseif( $this->approval_status == 'approved' ){
							if( $this->email_verified == 'verified' ){
								$message = sprintf( __( 'Thank you for registering with us!', $this->domain ) ). "\r\n";
								$message .= sprintf( __( 'Your Account has been approved by an Administrator!', $this->domain ) ). "\r\n"; 
								$message .= sprintf( __( 'Your new user account is now active at %s!', $this->domain ), $this->blogname ) . "\r\n\n";
								$message .= sprintf( __( 'Here are your new login credentials for %s:', $this->domain ), $this->blogname ) . "\r\n\n";
								$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n";
								$message .= apply_filters( 'user_password_key', $user_object );
								if( @wp_mail( $this->user_email, sprintf( __( '[%s] Your username and password', $this->domain ), $this->blogname ), $message ) ){
									$this->emails_sent++;
								}
							}elseif( $this->email_verified == 'unverified' ){
								$message = sprintf( __( "Thanks for registering! To complete the activation of your account %1s for %2s please click the following link to confirm your email address:", $this->domain ), $this->user_login, $this->blogname ). "\r\n\n";
								$message .= '<'.$this->email_activation_url. '>'."\r\n\n";
								if( @wp_mail( $this->user_email, sprintf( __( '[%s] Account Approved! Please Confirm Your Email!', $this->domain ), $this->blogname ), $message ) ){
									$this->emails_sent++;
								}
							}
						}
						break;	
					case 2:
						if( $this->email_verified == 'unverified' ){
							$message = sprintf( __( 'Thanks for registering!', $this->domain ) ). "\r\n";
							$message .= sprintf( __( "To complete the activation of your account %s please click the following link to Verify Your E-mail:
								", $this->domain  ), $this->user_login ). "\r\n\n";
							$message .= '<'.$this->email_activation_url. '>'."\r\n\n";
							$message .= sprintf( __( 'Here are your new login credentials for %s:', $this->domain ), $this->blogname ) . "\r\n\n";
							$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n";
							if( @wp_mail( $this->user_email, sprintf( __( '[%s] Verify Your Email', $this->domain ), $this->blogname ), $message ) ){
								$this->emails_sent++;
							}
						}elseif( $this->email_verified == 'verified' ){
							$message = sprintf( __( 'Thanks for registering!', $this->domain ) ). "\r\n";
							$message .= sprintf( __( 'Here are your new login credentials for %s:', $this->domain ), $this->blogname ) . "\r\n\n";
							$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n";
							$message .= apply_filters( 'user_password_key', $user_object );
							if( @wp_mail( $this->user_email, sprintf( __( '[%s] Your New Account Credentials', $this->domain), $this->blogname ), $message ) ){
								$this->emails_sent++;
							}
						}
						break;
				}
				break;
		}
		
		update_user_meta( $user_id, 'emails_sent', $this->emails_sent );
		return;
	}
	
	/**
	 * Function ura_admin_new_user_notification_email
	 * Sends Admin Email Notification of New User signing up and if they need approval or not
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params  int $user_id
	 * @returns
	*/
	
	function ura_admin_new_user_notification_email( $user_id ){
		$options = get_option( 'csds_userRegAide_Options' );
		$user = new WP_User( $user_id );
		$fields = get_option( 'csds_userRegAide_registrationFields' );
		$this->ura_approve = $options['new_user_approve'];
		$this->bp_approve = $options['buddy_press_approval'];
		$this->bp_registration = $options['buddy_press_registration'];
		$this->approval_status = get_user_meta( $user_id, 'approval_status', true );
		$this->user_login = stripslashes( $user->user_login );
		$this->user_email = stripslashes( $user->user_email );
		$message = ( string ) '';
		$this->blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$this->admin_notification = get_user_meta( $user_id, 'admin_notification', true );
		
		if( $this->ura_approve == 1 ){
			if( empty( $this->approval_status ) || $this->approval_status == 'pending' && $this->admin_notification <= 0 ){
				$message  = sprintf( __( 'A new user has registered on your site and is awaiting approval at %s:', $this->domain ), $this->blogname ) . "\r\n\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
				$message .= sprintf( __( 'E-mail: %s', $this->domain ), $this->user_email ) . "\r\n";
				@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Registration Alert', $this->domain ), $this->blogname ), $message );
				$this->admin_notification++;
			}elseif( $this->approval_status == 'approved' ){
				$message  = sprintf( __( 'A new user has registered on your site %s:', $this->domain ), $this->blogname ) . "\r\n\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
				$message .= sprintf( __( 'E-mail: %s', $this->domain ), $this->user_email ) . "\r\n";
				@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Registration Alert', $this->domain ), $this->blogname ), $message );
				$this->admin_notification++;
			}
		}elseif( $this->ura_approve == 2 ){
			$message  = sprintf( __( 'A new user has registered on your site %s:', $this->domain ), $this->blogname ) . "\r\n\r\n";
			$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
			$message .= sprintf( __( 'E-mail: %s', $this->domain ), $this->user_email ) . "\r\n";
			@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Registration Alert', $this->domain ), $this->blogname ), $message );
			$this->admin_notification++;
		}
		update_user_meta( $user_id, 'admin_notification', $this->admin_notification );
		return;
	}
		
	/**
	 * Function admin_email_verified_notification
	 * Sends Admin Email Notification of New User signing up and if they need approval or not
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params  int $user_id
	 * @returns
	*/
	
	function admin_email_verified_notification( $user_id ){
		$options = get_option('csds_userRegAide_Options');
		$user = new WP_User( $user_id );
		$fields = get_option('csds_userRegAide_registrationFields');
		$this->ura_approve = $options['new_user_approve'];
		$this->bp_approve = $options['buddy_press_approval'];
		$this->bp_registration = $options['buddy_press_registration'];
		$this->approval_status = get_user_meta( $user_id, 'approval_status', true );
		$this->user_login = stripslashes( $user->user_login );
		$this->user_email = stripslashes( $user->user_email );
		$message = ( string ) '';
		$this->blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$this->admin_email_verified_notification = get_user_meta( $user_id, 'admin_email_verified_notification', true );
		
		if( $this->ura_approve == 1 ){
			if( empty( $this->approval_status ) || $this->approval_status == 'pending' && $this->admin_email_verified_notification <= 0 ){
				$message  = sprintf( __( 'A new user has verified their email on your site and is still awaiting approval at %s:', $this->domain ), $this->blogname ) . "\r\n\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
				$message .= sprintf( __( 'E-mail: %s', $this->domain ), $this->user_email ) . "\r\n";
				@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Email Address Verification Alert', $this->domain ), $this->blogname ), $message );
				$this->admin_email_verified_notification++;
				update_user_meta( $user_id, 'admin_email_verified_notification', $this->admin_email_verified_notification );
			}elseif( $this->approval_status == 'approved' ){
				$message  = sprintf( __( 'A new user has verified their email on your site %s:', $this->domain ), $this->blogname ) . "\r\n\r\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
				$message .= sprintf( __( 'E-mail: %s', $this->domain ), $this->user_email ) . "\r\n";
				@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Email Address Verification Alert', $this->domain ), $this->blogname ), $message );
				$this->admin_email_verified_notification++;
				update_user_meta( $user_id, 'admin_email_verified_notification', $this->admin_email_verified_notification );
			}
		}elseif( $this->ura_approve == 2 ){
			$message  = sprintf( __( 'A new user has verified their Email Address on your site %s:', $this->domain ), $this->blogname ) . "\r\n\r\n";
			$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n\r\n";
			$message .= sprintf( __( 'E-mail: %s', $this->domain ), $this->user_email ) . "\r\n";
			@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Email Address Verification Alert', $this->domain ), $this->blogname ), $message );
			$this->admin_email_verified_notification++;
			update_user_meta( $user_id, 'admin_email_verified_notification', $this->admin_email_verified_notification );
		}
		return;
	}
	
	/**
	 * function user_email_verified
	 * Sends email notification to new user when they verify email
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params WP_User_object $user
	 * @returns 
	*/
	
	function user_email_verified( $user ){
		$this->blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$this->user_login = stripslashes( $user->user_login );
		$this->user_email = stripslashes( $user->user_email );
		$this->emails_sent = get_user_meta( $user->ID, 'emails_sent', true );
		$this->user_email_verified_notification = get_user_meta( $user->ID, 'user_email_verified_notification', true );
		$message = sprintf( __( 'Thank you for confirming your email with %s!', $this->domain ), $this->blogname ) . "\r\n\n";
		$message .= sprintf( __( 'Your E-mail has been Confirmed and Your Registration for %s is awaiting an Administrator Approval', $this->domain ), $this->blogname ) . "\r\n\n";
		$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n";
		@wp_mail( $this->user_email, sprintf( __( '[%s] Email Confirmed', $this->domain ), $this->blogname ), $message );
		$this->emails_sent++;
		$this->user_email_verified_notification++;
		update_user_meta( $user->ID, 'emails_sent', $this->emails_sent );
		update_user_meta( $user->ID, 'user_email_verified_notification', true );
	}
	
	/**
	 * Function ura_new_user_approved_notification
	 * Sends User Email Notification after user is approved by administrator
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params  int $user_id
	 * @returns
	*/
	
	function ura_new_user_approved_notification( $user_id ){
		$options = get_option( 'csds_userRegAide_Options' );
		$user = new WP_User( $user_id );
		$this->blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$this->page = 'ura-update-password';
		$this->ura_approve = $options['new_user_approve'];
		$this->bp_approve = $options['buddy_press_approval'];
		$this->bp_registration = $options['buddy_press_registration'];
		$this->approval_status = get_user_meta( $user_id, 'approval_status', true );
		$this->email_verified = get_user_meta( $user_id, 'email_verification', true );
		$this->emails_sent = get_user_meta( $user_id, 'emails_sent', true );
		$this->password_set = get_user_meta( $user_id, 'password_set', true );
		$this->user_login = stripslashes( $user->user_login );
		$this->user_email = stripslashes( $user->user_email );
		$message = ( string ) '';
		$this->approved_email = get_user_meta( $user_id, 'approved_email', true );
				
		if( $options['xwrd_change_on_signup'] == 1 ){
			$url = site_url();
			$this->login_url = $url.'/'.$this->page.'/?action=new-register';
			$this->password_set = 1;
		}elseif( $options['xwrd_change_on_signup'] == 2 ){
			$this->login_url = wp_login_url() . "\r\n";
		}
				
		$this->email_activation_url = $this->create_email_activation_url( $user );
		
		if( $this->approved_email == false || $this->approved_email == 0 ){
			if( $this->email_verified == 'verified' ){
				$message = sprintf( __( 'Thank you for registering with %s!', $this->domain ), $this->blogname ) . "\r\n\n";
				$message .= sprintf( __( 'Congratulations! Your Registration for %s has been Approved by an Administrator!', $this->domain ) , $this->blogname ) . "\r\n";
				if( $this->password_set == 1 ){
					$message .= sprintf( __( 'You can now login with the credentials below at the login link given below.', $this->domain ), $this->blogname ) . "\r\n\n";
				}
				$message .= sprintf( __( 'Credentials:', $this->domain ) ). "\r\n";
				$message .= sprintf( __( 'Username: %s' ), $this->user_login ) . "\r\n";
				$message .= apply_filters( 'user_password_key', $user ) . "\r\n";
				@wp_mail( $this->user_email, sprintf( __( '[%s] Your User Account has been Approved!' ), $this->blogname ), $message );
				$this->emails_sent++;
			}elseif( $this->email_verified == 'unverified' ){
				$xwrd = 'User Entered';
				$message = sprintf( __( "Thanks for registering with %s!", $this->domain ), $this->blogname ). "\r\n";
				$message .= sprintf( __( "Your Registration has been Approved by an Administrator and now you must use the email verification link below to activate your account %s. Please click the following link:", $this->domain ), $this->user_login ). "\r\n\n";
				$message .= '<'.$this->email_activation_url. '>'."\r\n\n";
				$message .= sprintf( __( 'Username: %s', $this->domain ), $this->user_login ) . "\r\n";
				@wp_mail( $this->user_email, sprintf( __( '[%s] Your User Account Has Been Approved & Your Email Needs To Be Verified' ), $this->blogname ), $message );
				$this->emails_sent++;
			}
			update_user_meta( $user_id, 'emails_sent', $this->emails_sent );
			update_user_meta( $user_id, 'approved_email', true );
		}
	}
	
	/**
	 * Function ura_lost_xwrd_approve
	 * Changes default lost password email if user tries to change password before being approved 
	 * or verifying email
	 * @since 1.5.3.4
	 * @updated 1.5.3.4
	 * @access public
	 * @params  int $user_id
	 * @returns
	*/
	
	function ura_lost_xwrd_approve( $message, $key, $user_login, $user_data ){
		//wp_die( print_r( $user_data ) );
		//wp_die( 'MY LOST PASSWORD FILTER' );
		$options = get_option('csds_userRegAide_Options');
		$msg = ( string ) '';
		$url = site_url();
		$approve = ( int ) 0;
		$approve = $options['new_user_approve'];
		$verify = $options['verify_email'];
		$user_id = $user_data->ID;
		if( $approve == 2 && $verify == 2 ){
			return $message;
		}
		
		if( $approve == 1 && $verify == 1 ){
			$status = get_user_meta( $user_id, 'approval_status', true );
			$verify = get_user_meta( $user_id, 'email_verification', true );
			if( $status == 'pending' ){
				$msg = __( 'Your Account has not been Approved by an Administrator yet, you cannot reset your password until your account has been Approved!', $this->domain );
				$msg .= "\r\n\n";
			}
			if( $verify == 'unverified' ){
				$msg .= __( 'Your Email Address for this Account has not yet been Verified. You cannot reset your password until your email address has been verified!', $this->domain );
				$msg .= "\r\n\n";
			}
			if( !empty( $msg ) ){
				$msg .= '<'.$url. '>';
				return $msg;
			}else{
				return $message;
			}
		}elseif( $approve == 1 && $verify == 2 ){
			$status = get_user_meta( $user_id, 'approval_status', true );
			if( $status == 'pending' ){
				$msg = __( 'Your Account has not been Approved by an Administrator yet, you cannot reset your password until your account has been Approved!', $this->domain );
				$msg .= "\r\n\n";
				$msg .= '<'.$url. '>';
				return $msg;
			}else{
				return $message;
			}
		}elseif( $approve == 2 && $verify == 1 ){
			$verify = get_user_meta( $user_id, 'email_verification', true );
			if( $verify == 'unverified' ){
				$msg = __( 'Your Email Address for this Account has not yet been Verified. You cannot reset your password until your email address has been verified!', $this->domain );
				$msg .= "\r\n\n";
				$msg .= '<'.$url. '>';
				return $msg;
			}else{
				return $message;
			}
		}
	}
}