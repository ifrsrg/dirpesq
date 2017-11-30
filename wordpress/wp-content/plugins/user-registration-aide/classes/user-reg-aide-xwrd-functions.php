<?php

/**
 * Class  PASSWORD_FUNCTIONS
 *
 * @category Class
 * @since 1.5.0.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class PASSWORD_FUNCTIONS
{
		
	/**	
	 * function install_xwrd_databases
	 * Install the database for recording password change information
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function install_xwrd_databases(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_xwrd_change";
		
		if ( ! empty( $wpdb->charset ) ) {
		  $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		}

		if ( ! empty( $wpdb->collate ) ) {
		  $charset_collate .= " COLLATE {$wpdb->collate}";
		}
		
		if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			$sql = "CREATE TABLE " . $table_name . " (
				`xwrd_change_ID` bigint(20) NOT NULL AUTO_INCREMENT,
				`user_ID` bigint(20) NOT NULL,
				`change_date` datetime NOT NULL default '0000-00-00 00:00:00',
				`change_IP` varchar(100) NULL default '',
				`old_password` varchar(100) NOT NULL default '',
				`change_uagent` varchar(100) NULL default '',
				`change_referrer` varchar(100) NULL default '',
				`change_uhost` varchar(100) NULL default '',
				`change_uri_request` varchar(100) NULL default '',
				 PRIMARY KEY  (`xwrd_change_ID`)
				)$charset_collate;";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta( $sql );
		}
	}
		
	/**	
	 * function check_xwrd_table_exists
	 * Checks to see if the database for recording password change information is installed or not
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns boolean false if no database, true if it is installed
	*/
	
	function check_xwrd_table_exists( $exists ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_xwrd_change";
		if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
			return false;
		}else{
			return true;
		}
	}
	
	// ----------------------------------------     Password Functions     ----------------------------------------
	
	/**	
	 * Function csds_userRegAide_createNewPassword
	 * Filter for the default WordPress create password function
	 * Inserts password entered by user if option chosen to let users enter 
	 * own password instead of using default random password emailing
	 * @since 1.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @Filters 'random_password' line 236 &$this (Priority: 0 - Params: 1)
	 * @params string $password 
	 * @returns string $password
	 */
	
	function csds_userRegAide_createNewPassword( $password ){
		global $wpdb;
		$options = get_option( 'csds_userRegAide_Options' );
		$password1 = '';
		$data_meta = array();
		if( !is_multisite() ) {
			if( isset( $_POST["pass1"] ) ){
				$password = $_POST["pass1"];
			}
			}else{
			if ( !empty( $_GET['key'] ) ){
				$key = $_GET['key'];
				}elseif( !empty( $_POST['key'] ) ){
				$key = $_POST['key'];
			}
			if( !empty( $key ) ){
				// seems useless since this code cannot be reached with a bad key anyway you never know
				$key = $wpdb->escape( $key );
				
				$sql = "SELECT active, meta FROM ".$wpdb->signups." WHERE activation_key='".$key."'";
				$data = $wpdb->get_results( $sql );
				
				// checking to make sure data is not empty
				if( isset( $data[0] ) ){
					// if account not active
					if( !$data[0]->active ){
						$meta = maybe_unserialize( $data[1]->meta );
						
						if ( !empty($meta['pass1'] ) ) {
							$password = $meta['pass1'];
							}else{
							$password = $password;
						}
					}
				}
			}
		}
		return $password;
		
	}
		
	/**	
	 * function password_change_form
	 * Shortcode for showing and processing password change form
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns string $form ( password change form )
	*/
	
	function password_change_form(){
		global $wpdb, $current_user, $post;
		
		apply_filters( 'force_ssl', false, $post );
		$options = get_option('csds_userRegAide_Options');
		$table_name = ( string ) $wpdb->prefix . "ura_xwrd_change";
		$ip = $this->get_user_ip_address();
		$nonce = wp_nonce_field(  'csds-passChange', 'wp_nonce_csds-passChange' );
		
		// declaring function variables
		$login = ( string ) '';
		$email = ( string ) '';
		$password = ( string ) '';
		$line = ( string ) '';
		$request_uri = ( string ) '';
		$referer = ( string ) '';
		$user_host = ( string ) '';
		$user_agent = ( string ) '';
		$changed = ( string ) '';
		$err = ( int ) 0;
		$xwrd_err = ( int ) 0;
		$ssl = 'NO';
		if( is_ssl() ){
			$ssl = 'YES';
		}
				
		// post id
		$post_id = $post->ID;
		$title_id = $this->title_id( $post );
		$post_name = $post->post_name;
		//server variables
		$method = $_SERVER['REQUEST_METHOD'];
		$request_uri = $_SERVER['REQUEST_URI'];
		$user_host = gethostbyaddr($ip);
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		
		if( isset( $_GET['action'] ) && $_GET['action'] == 'new-register' ){
			$line = '<h2>'.__( 'The Password You Received in the Email Was Temporary and Has Expired, Please Change Your Password Now!', 'user-registration-aide' ).'</h2>';
		}elseif( isset( $_GET['action'] ) && $_GET['action'] == 'expired-password' ){
			$line = '<h2>'.__( 'Your Password Has Expired, Please Change Your Password!', 'user-registration-aide' ).'</h2>';
			$referer = $_SERVER['HTTP_REFERER'];
		}elseif( isset( $_GET['action'] ) && $_GET['action'] == 'password-never-changed' ){
			$line = '<h2>'.__( 'You Have Not Changed Your Password Since You Signed Up and Your Password Has Expired, Please Change Your Password Now!', 'user-registration-aide' ).'</h2>';
		}
		if( isset( $_POST['user_login'] ) ){
			$login = $_POST['user_login'];
		}
		if( isset( $_POST['user_email'] ) ){
			$email = $_POST['user_email'];
		}
		if( isset( $_POST['old_pass1'] ) ){
			$password = $_POST['old_pass1'];
		}
		// form shortcode
		$form = (string) '';
		$form .= '<form method="post" name="change_password" id="change_password">';
		$form .= '<div class="reset-xwrd">';
		$form .= $nonce; //wp_nonce_field( $nonce[0], $nonce[1] );
		$form .= '<h2>'.__( 'Password Change Form', 'user-registration-aide' ).'</h2>';
		$form .= $line;
		//$form .= 'Post ID: '.$post_id.'<br/>';
		//$form .= 'Post Name: '.$options['xwrd_change_name'].'<br/>';
		//$form .= 'Title ID: '.$title_id.'<br/>';
		$form .= '<table>';
		$form .= '<tr><td><label for="user_email">E-mail:</label></td>';
		$form .= '<td><input type="text" name="user_email" id="user_email" value="'.$email.'" class="reset-xwrd" title="'.__( 'Email Address Used When Registered For Site', 'user-registration-aide' ).'" /></td></tr>';
		$form .= '<tr><td><label for="user_login">'. __( 'Username:', 'user-registration-aide' ).' </label>';
		$form .= '</td><td><input type="text" name="user_login" id="user_login" class="reset-xwrd" value="'.$login.'" size="20" title="Login Name For Site" /></td></tr>';
		$form .= '<tr><td><label for="old_pass1">'.__( 'Old Password:', 'user-registration-aide' ).'</label></td>';
		$form .= '<td><input type="password" name="old_pass1" id="old_pass1" class="reset-xwrd" size="20" value="'.$password.'" autocomplete="off" title="'.__( 'Password Sent to You When Registered OR Current Password For Site', 'user-registration-aide' ).'" /></td></tr>';
		$form .= '<tr><td>';
		$form .= '<div class="user-pass1-wrap">';
		$form .= '<p>';
		$form .= '<label for="pass1">'.__( 'New password', 'user-registration-aide' ).'</label>';
		$form .= '</p>';
		$form .= '</td>';
		$form .= '<td>';
		//$form .= '<p class="user-pass1-wrap">';
		//$form .= '<div class="wp-pwd">';
		//$form .= '<span class="password-input-wrapper">';
		$form .= '<input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" />';
		$form .= '</td>';
		$form .= '</tr>';
		$form .= '<tr>';
		$form .= '<td>';
		$form .= '</td>';
		$form .= '<td>';
		$form .= '<div id="pass-strength-result" class="hide-if-no-js" aria-live="polite">';
		$form .= __( 'Strength Indicator', 'user-registration-aide' );
		$form .= '</div>';
		//$form .= '</span>';
		//$form .= '</div>';
		//$form .= '</p>';
		$form .= '</td>';
		$form .= '</tr>';
		$form .= '<tr>';
		$form .= '<td>';
		$form .= '<label for="xwrd2">'.__( 'Confirm Password', 'user-registration-aide' ).'</label>';
		$form .= '</td>';
		$form .= '<td>';
		$form .= '<input type="password" name="xwrd2" id="xwrd2" class="input" size="20" value="" autocomplete="off" />';
		$form .= '<br class="clear" />';
		$form .= '</td></tr>';
		//$form .= '<tr><td><label for="pass1">'.__( 'New Password:', 'user-registration-aide' ).'</label></td>';
		//$form .= '<td><input type="password" data-reveal="0" data-pw="" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" aria-describedby="pass-strength-result" title="'.__( 'Enter Your New Password For This Site,YOU CANNOT USE THE SAME PASSWORD AS BEFORE!', 'user-registration-aide' ).'" /></td></tr>';
		//$form .= '<tr><td colspan="2"><div id="pass-strength-result" class="hide-if-no-js" aria-live="polite">'.__( 'Strength indicator', 'user-registration-aide' ).'</div>';
		//$form .= '<p class="description indicator-hint">'.__( 'Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp', 'user-registration-aide' ).'</p></td></tr>';
		//$form .= '<tr><td><label for="xwrd2">'.__( 'Confirm Password', 'user-registration-aide' ).'</label></td>';
		//$form .= '<td><input type="password" name="xwrd2" id="xwrd2" class="input" size="20" value="" autocomplete="off" /></td></tr>';
		$form .= '<br class="clear" />';
		$form .= '<tr><td colspan="2"><div class="submit">';
		$form .= '<input type="submit" class="button-primary" name="update_xwrd-reset" id="update_xwrd-reset" value="'.__( 'Change Password', 'user-registration-aide' ).'" /></td></tr></table>';
		$form .= '</div>';
		$form .= '</div>';
		$form .= '</form>';
		
		// end form shortcode
		
		if( isset( $_POST['update_xwrd-reset'] ) ){
			$wp_error = new WP_Error();
			if( ! wp_verify_nonce( $_POST['wp_nonce_csds-passChange'], 'csds-passChange' ) ){
				exit( 'Failed Security Validation!' );
			}/* -- testing -- else{
				$wp_error->add( 'nonce_verified' , __( "<b>ERROR</b>: Nonce Verified!",'user-registration-aide' ) );
			} */
			$request_uri = $_SERVER['REQUEST_URI'];
			$referer = $_SERVER['HTTP_REFERER'];
			//$referrer = (string) '';
			$verify_xwrd = array();
			$login = $_POST['user_login'];
			$email = $_POST['user_email'];
			$email2 = ( string ) '';
			$password = $_POST['old_pass1'];
			$pass1 = $_POST['pass1'];
			$pass2 = $_POST['xwrd2'];
			$user = get_user_by( 'login', $login );
			$check_user = apply_filters( 'authenticate', null, $login, $password );
			
			if( empty( $login ) ){
				$wp_error->add( 'empty_email' , __( "<b>ERROR</b>: Please Enter Your Username!",'user-registration-aide' ) );
				$err++;
			}elseif( !username_exists( $login ) ){
				$wp_error->add( 'empty_email' , __( "<b>ERROR</b>: Username Does Not Exist!",'user-registration-aide' ) );
				$err++;
			}
			if( empty( $_POST['user_email'] ) ){
				$wp_error->add( 'empty_email' , __( "<b>ERROR</b>: Please Enter your Email",'user-registration-aide' ) );
				$err++;
			}elseif( !empty( $email ) && !is_wp_error( $user ) ){
				$email2 = $user->user_email;
				if( $email != $email2 ){
					$wp_error->add( 'emails_not_match', __( "<b>ERROR</b>: Email Associated With This Account And Email Entered Do Not Match!", 'user-registration-aide' ) );
					$err++;
				}
			}
			if( $password == $pass1 ){
				$wp_error->add( 'old_new_password_match', __( "<b>ERROR</b>: NEW PASSWORD SAME AS OLD PASSWORD, PLEASE ENTER A DIFFERENT PASSWORD!", 'user-registration-aide' ) );
				$err++;
			}
					
			// filter for password strength and duplicate errors
			$verify = apply_filters( 'custom_password_strength', $pass1, $pass2, $login, $email, $wp_error, $err );
			$verify_msgs = $verify->get_error_messages();
			$verify_xwrd = apply_filters( 'duplicate_verify', $user, $pass1, $wp_error, $err );
			$verify_xwrd_msgs = $verify_xwrd->get_error_messages();
			
			// Errors Displayed
			if( is_wp_error( $check_user ) ){
				$xwrd_err++;
				$errors = $check_user->get_error_messages();
				foreach( $errors as $error ){
					echo '<div id="my-message" class="my-error">'.$error.'</div>';
				}
			}elseif( !empty( $verify_xwrd_msgs ) ){
				$xwrd_err++;
				foreach( $verify_xwrd_msgs as $error ){
					echo '<div id="my-message" class="my-error">'.$error.'</div>';
				}
			}elseif( !empty( $verify_msgs ) ){
				$xwrd_err++;
				foreach( $verify_msgs as $error ){
					echo '<div id="my-message" class="my-error">'.$error.'</div>';
				}
			}elseif( $err >= 1 ){
				if( !empty( $wp_error ) ){
					$errors = $wp_error->get_error_messages();
					foreach( $errors as $error ){
						echo '<div id="my-message" class="my-error">'.$error.'</div>';
					}
				}
			}elseif( $xwrd_err === 0 && $err === 0 ){
				//wp_die( 'GOOD TO GO!' );
				$user_id = $user->ID;
				$old_pass = $user->user_pass;
				$credentials = array(
					'user_login' => $login,
					'user_password' => $password
				);
									
				// storing password change data in database
				$insert = "INSERT INTO " . $table_name . " ( user_ID, change_date, change_IP, old_password, change_uagent, change_referrer, change_uhost, change_uri_request ) " ."VALUES ( '" . $user_id . "', now(), '%s', '%s', '%s', '%s', '%s', '%s' )";
				
				// for testing previous duplicate passwords
				//$insert = "INSERT INTO " . $table_name . " ( user_ID, change_date, change_IP, old_password, change_uagent, change_referrer, change_uhost, change_uri_request ) " . "VALUES ( '" . $user_id . "', (now() - INTERVAL 360 DAY), '%s', '%s', '%s', '%s', '%s', '%s' )";
				
				$insert = $wpdb->prepare( $insert, $ip, $old_pass, $user_agent, $referer, $user_host, $request_uri );
				$results = $wpdb->query( $insert );
				
				if( $results == 1){
					$changed .= '<div id="my-message" class="my-updated"><b>Updated</b>: Password Change Records Updated!</div>';
				}else{
					$form .= '<div id="my-message" class="my-error"><b>Error</b>: Database Record Failed!</div>';
					$form .= '<div id="my-message" class="my-error"><b>Error</b>: Password Update Failed!</div>';
				}
				
				if( $results == 1 ){
					$results2 = wp_set_password( $pass1, $user_id );
					
					if( is_wp_error( $results2 ) ){
						$errors = $results2->get_error_messages();
						foreach( $errors as $error ){
							$form .= '<div id="my-message" class="my-error">'.$error.'</div>';
						}
						
					}else{
						// shows login form if successful password change - **must log user out to properly change password**
						//$changed .= '<h2>Refer: '.$referer.'</h2>';
						//$changed .= '<h2>Request: '.$request_uri.'</h2>';
						$changed .= '<div id="my-message" class="my-updated"><b>Updated</b>: Password Updated!</div>';
						$changed .= '<div id="my-message" class="my-updated"><b>Message</b>: You Were Logged Out to Change Your Password Properly, Sorry for Any Inconvenience!</div>';
						$args = array(
							'echo'           => true,
							'redirect'       => site_url( $_SERVER['REQUEST_URI'] ), 
							'form_id'        => 'loginform',
							'label_username' => __( 'Username', 'user-registration-aide' ),
							'label_password' => __( 'Password', 'user-registration-aide' ),
							'label_remember' => __( 'Remember Me', 'user-registration-aide' ),
							'label_log_in'   => __( 'Log In', 'user-registration-aide' ),
							'id_username'    => 'user_login',
							'id_password'    => 'user_pass',
							'id_remember'    => 'rememberme',
							'id_submit'      => 'wp-submit',
							'remember'       => true,
							'value_username' => NULL,
							'value_remember' => false
						);
						
						$changed .= wp_login_form( $args );
						return $changed;		
							
					}
				}
			}
		}
		
		return $form;
	}
	
	/**	
	 * function get_user_ip_address
	 * Get the user/client IP address
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns string $ip_address
	*/
	
	function get_user_ip_address() {
		$ip_address = (string) '';
		if( !empty( $_SERVER['HTTP_CLIENT_IP'] ) ){
			$ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}elseif( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){
			$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}elseif( !empty( $_SERVER['HTTP_X_FORWARDED'] ) ){
			$ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
		}elseif( !empty( $_SERVER['HTTP_FORWARDED'] ) ){
			$ip_address = $_SERVER['HTTP_FORWARDED'];
		}elseif( !empty( $_SERVER['REMOTE_ADDR'] ) ){
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}else{
			$ip_address = 'UNKNOWN';
		}
		return esc_attr( $ip_address );
	}
	
	/**	
	 * function xwrd_reset_disable
	 * Handles filter to allow password reset and updates settings according to admin choices
	 * Will not disable admin password resets, only non-admins
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns boolean true for not disabled false for disabled
	*/
	
	function xwrd_reset_disable( $allow, $user_id ){
		$options = get_option( 'csds_userRegAide_Options' );
		$reset = $options['allow_xwrd_reset'];
		$user = get_user_by( 'ID', $user_id );
		$approve = $options['new_user_approve'];
		$verify = $options['verify_email'];
		$fields = get_option( 'csds_userRegAide_registrationFields' );
		if( !empty( $fields ) ){
			if( array_key_exists( 'user_pass', $fields ) ){
				$password = true;
			}else{
				$password = false;
			}
		}else{
			$password = false;
		}
		if( $password == false ){
			$password_set = get_user_meta( $user_id, 'password_set', true );
			if( !empty( $password_set ) ){
				if( $password_set == 2 ){
					$mpe = get_user_meta( $user_id, 'my-xwrd-email', true );
					if( !empty ( $mpe ) ){
						if( $mpe == 'meem' ){
							update_user_meta( $user_id, 'my-xwrd-email', '' );
							return true;
						}else{
							return false;
						}
					}else{
						return false;
					}
				}
			}
		}
		if( !empty( $approve ) ){
			if( $approve == 1 ){
				$approval_status = get_user_meta( $user_id, 'approval_status', true );
				if( !empty( $approval_status ) ){
					if( $approval_status == 'pending' ){
						return false;
					}
				}
			}
		}
		if( !empty( $verify ) ){
			if( $verify == 1 ){
				$email_verified = get_user_meta( $user_id, 'email_verification', true );
				if( !empty( $email_verified ) ){
					if( $email_verified == 'unverified' ){
						return false;
					}
				}
			}
		}
		if( !empty( $reset ) ){
			if( $reset == 1 ){
				return true;
			}elseif( $reset == 2 ){
				if ( !empty( $user->roles ) && is_array( $user->roles ) && $user->roles[0] == 'administrator' ){
					return true;
				}else{
					return false;
				}
			}
		}
	}
	
	
		
	/**	
	 * function remove_xwrd_reset_text
	 * Removes the 'Lost your Password' text/link from login form
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $text
	 * @returns string $text
	*/
	
	function remove_xwrd_reset_text( $text ){
		$options = get_option('csds_userRegAide_Options');
		if( array_key_exists( 'allow_xwrd_reset', $options ) ){
			$reset = $options['allow_xwrd_reset'];
		}else{
			$reset = 1;
		}
		if( $reset == 2 ){
			if( is_page( 'login_url' ) ){
				$user_login = $_POST['user_login'];
				$user = get_user_by( 'login', $user_login );
				if( empty( $user ) ){
					$user = get_user_by( 'email', $user_login );
				}
				$cur_user = new WP_User( $user->ID );
				if ( !empty( $cur_user->roles ) && is_array( $cur_user->roles ) && $cur_user->roles[0] == 'administrator' ){
					return $text;
				}else{
					return str_replace( array( 'Lost your password?', 'Lost your password' ), '', trim( $text, '?' ) );
				}
			}else{
				return $text;
			}
			
		}
		
		return $text;
	}
	
	/**	
	 * function xwrd_strength_verify
	 * Checks password fields for strength settings and errors for shortcode and registration form pages
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $pass1, string $login, string $email, WP_Error OBJECT $errors, int $error 
	 * @returns WP_Error OBJECT $errors
	*/
	
	function xwrd_strength_verify( $pass1, $pass2, $login, $email, $errors, $error ){
		//$errors = new WP_Error();
		$options = get_option('csds_userRegAide_Options');
		$dup_times = trim( $options['xwrd_duplicate_times'] );
		//to check password -- password fields empty
		if( empty( $pass1 ) || $pass1 == '' ){
			$errors->add('empty_password', __( "<strong>ERROR</strong>: Please Enter Your Password!", 'user-registration-aide' ) );
			$error ++;
		}
		 // password same as user login
		if( $pass1 == $login ){
			$errors->add( 'password_and_login_match', __( "<strong>ERROR</strong>: Username and Password are the Same, They Must be Different!", 'user-registration-aide' ) );
				$error ++;
		}
		// confirm password
		if( empty( $pass2 ) || $pass2 == '' ){
			$errors->add( 'empty_confirm_password', __( "<strong>ERROR</strong>: Please Confirm Your Password!", 'user-registration-aide' ) );
			$error ++;
		}
		
		// password not confirmed
		if( !empty( $pass1 ) && !empty( $pass2 ) && $pass1 != $pass2 ){
			$errors->add( 'password_mismatch', __( "<strong>ERROR</strong>: Password and Confirm Passwords do Not Match!", 'user-registration-aide' ) );
			$error ++;
		}
		// Password strength requirements
		if( strlen( trim( $pass1 ) ) < $options['xwrd_length'] ){ // password length too short
			if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['require_xwrd_length'] == 1 ) ){
				$errors->add( 'password_too_short', sprintf( __( "<strong>ERROR</strong>: Password length too short! Should be at least %d characters long!", 'user-registration-aide' ), $options['xwrd_length'] ) );
				$error ++;
			}
		// no number in password
		}
		if( $pass1 != '' && !preg_match("/[0-9]/", $pass1 )){
			if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['xwrd_numb'] == 1 ) ){
				$errors->add( 'password_missing_number', __( "<strong>ERROR</strong>: There is no number in your password!", 'user-registration-aide' ) );
				$error ++;
			}
		// no lower case letter in password
		}
		if( $pass1 != '' && !preg_match("/[a-z]/", $pass1 )){
			if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['xwrd_lc'] == 1 ) ){
				$errors->add( 'password_missing_lower_case_letter', __( "<strong>ERROR</strong>: Password missing lower case letter!", 'user-registration-aide' ) );
					$error ++;
			}
		// no upper case letter in password
		}
		if( $pass1 != '' && !preg_match("/[A-Z]/", $pass1 ) ){
			if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['xwrd_uc'] == 1 ) ){
				$errors->add( 'password_missing_upper_case_letter', __( "<strong>ERROR</strong>: Password missing upper case letter!", 'user-registration-aide' ) );
					$error ++;
			}
		// no special character in password
		}
		if( $pass1 != '' && !preg_match("/.[!,@,#,$,%,^,&,*,?,_,~,-,£,(,)]/", $pass1 ) ){
			if( $options['default_xwrd_strength'] == 1 || ( $options['custom_xwrd_strength'] == 1 && $options['xwrd_sc'] == 1 ) ){
				$errors->add( 'password_missing_symbol', __( "<strong>ERROR</strong>: Password missing symbol!", 'user-registration-aide' ) );
					$error ++;
			}
		}
		//$results = array( $error, $errors );
		//return $results;
		return $errors;
	}
	
	/**	
	 * function xwrd_change_duplicate_verify
	 * Checks password for duplicate password entries
	 * @since 1.5.0.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params WP_User OBJECT $user, string $password, WP_Error OBJECT $errors, int $error
	 * @returns WP_Error OBJECT $errors
	*/
	
	function xwrd_change_duplicate_verify( $user, $password, $errors, $error ){
		global $wpdb;
		$options = get_option('csds_userRegAide_Options');
		$dup_times = $options['xwrd_duplicate_times'];
		$user_id = $user->ID;
		$db_exists = apply_filters( 'xwrd_db_exists', false );
		if( empty( $db_exists ) || $db_exists === false ){
			$xwrd = new PASSWORD_FUNCTIONS();
			$xwrd->install_xwrd_databases();
			unset( $xwrd );
		}elseif( $db_exists === true ){
			$table_name = $wpdb->prefix . "ura_xwrd_change";
			$sql = "SELECT old_password FROM $table_name WHERE user_ID = '%d' ORDER BY change_date DESC LIMIT %d";
			$sql_results = $wpdb->prepare( $sql, $user_id, $dup_times );
			$xwrds = $wpdb->get_results( $sql_results, ARRAY_A ); 
		}
		
		$match = ( boolean ) false;
		$i = ( int ) 0;
		
		// to check password -- password fields empty
		if( !empty( $xwrds ) ){
			foreach( $xwrds as $xwrds1 ){
				foreach( $xwrds1 as $key => $xwrd ){
					if( $i < $dup_times ){
						$match = wp_check_password( $password, $xwrd, $user_id );
						if( $match != false ){
							$errors->add( 'duplicate_password', sprintf( __( "<strong>ERROR</strong>: Please Change Your New Password! This Password Matches A Previous Password! You Cannot Use Duplicate Passwords for %d Times!", 'user-registration-aide' ), $dup_times ) );
							return $errors;
						}
					}
					$i++;
				}
			}
		}
		return $errors;
	}
	
	/**	
	 * function non_admin_login_redirect
	 * Redirect non-admin user after successful login.
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $redirect_to, string $request, WP_User OBJECT $user
	 * @return string $redirect_to
	*/
	
	function non_admin_login_redirect( $redirect_to, $request, $user ) {
		global $user;
		$options = get_option('csds_userRegAide_Options');
		$redirect = $options['redirect_login'];
		$redirect_url = $options['login_redirect_url'];
		//echo $redirect_to;
		if( $redirect == 1 ){
			if ( isset( $user->roles ) && is_array( $user->roles ) ) {
				//check for admins
				if ( in_array( 'administrator', $user->roles ) ) {
					// redirect them to the default redirect page
					return $redirect_to;
				} else {
					// redirect users to the specified page
					return $redirect_url;
				}
			} else {
				// redirect them to the default redirect page
				return $redirect_to;
			}
		}else{
			// redirect them to the default redirect page
			return $redirect_to;
		}
	}
	
	/**	
	 * function xwrd_change_login_check
	 * Checks user for last password change on authentication - if need password change * redirects to password change pag
	 * @since 1.5.0.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $username
	 * @return
	*/
	
	function xwrd_change_login_check( $username ){
		global $wpdb;
		$options = get_option('csds_userRegAide_Options');
				
		if( !username_exists( $username ) ){
			return;
		}
		$fields = get_option( 'csds_userRegAide_registrationFields' );
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
		$change = $options['xwrd_require_change'];
		$signup_change = $options['xwrd_change_on_signup'];
		
		if( $change == 2 ){
			return;
		}
		
		// for security questions 
		$sec_question = $options['add_security_question'];
		if( $sec_question == 1 ){
			$sq_results = array();
			$sq_result = ( boolean ) false;
			$sq_msg = ( string ) '';
		}
		
		// if user never changed password check signup/registration date for when plugin is new users->user_registered
		$days = $options['xwrd_change_interval'];
		$user = get_user_by( 'login', $username );
		$xwrd_chg_name = '/ura-update-password';
		$expired_password = $options['xwrd_chng_exp_url'];
		$never_changed = $options['xwrd_chng_nc_url'];
		$new_user = $options['xwrd_chng_email_url'];
		$site = site_url();
		$user_id = $user->ID;
		$exists = ( boolean ) false;
		$exists = apply_filters( 'xwrd_db_exists', $exists );
		$table_name = $wpdb->prefix . "ura_xwrd_change";
		$sql_cnt = "SELECT COUNT(user_ID) FROM $table_name WHERE user_ID = '$user_id'";
		$cnt = $wpdb->get_var( $sql_cnt );
		if( empty( $cnt ) || $cnt <= 0 || $cnt == '' ){
			if( $signup_change == 1 && $password == false ){
				$url = $xwrd_chg_name.$new_user;
				$redirect = $site.$url;
				wp_redirect( $redirect );
				exit;
			}
			$table_name = $wpdb->prefix . "users";
			$sql = "SELECT ID FROM $table_name WHERE ID = %d AND date_add(user_registered, INTERVAL %d DAY) < NOW()";
			$run_query = $wpdb->prepare( $sql, $user_id, $days );
			$date = $wpdb->get_var( $run_query );
			if( !empty( $date ) ){
				$url = $xwrd_chg_name.$never_changed;
				$redirect = $site.$url;
				wp_redirect( $redirect );
				exit;
			}else{
				if( $sec_question == 1 ){
					$sq_results = apply_filters( 'security_questions_completed', $sq_results );
					$sq_result = $sq_results[0];
					$sq_msg = $sq_results[1];
					if( $sq_result == 1 ){
						
					}
				}
			}
		}else{
			$table_name = $wpdb->prefix . "ura_xwrd_change";
			$sql = "SELECT change_date FROM $table_name WHERE user_ID = %d AND date_add(change_date, INTERVAL %d DAY) > NOW() ORDER BY user_ID DESC";
			$run_query = $wpdb->prepare( $sql, $user_id, $days );
			$run_query = $wpdb->prepare( $sql, $user_id, $days );
			$date = $wpdb->get_var( $run_query );
			if( empty( $date ) ){
				$url = $xwrd_chg_name.$expired_password;
				$redirect = $site.'/'.$url;
				wp_redirect( $redirect );
				exit;
			}else{
				if( $sec_question == 1 ){
					$sq_results = apply_filters( 'security_questions_completed', $sq_results );
					$sq_result = $sq_results[0];
					$sq_msg = $sq_results[1];
				}
			}
			
		}
		
	}
	
	/**	
	 * function xwrd_change_lost_password_check
	 * Checks user for last password change on authentication - if need password change 
	 * returns link for lost passowrd seet if admin is using password change options
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $username
	 * @returns string $redirect 
	*/
	
	function xwrd_change_lost_password_check( $username ){
		global $wpdb;
		$options = get_option('csds_userRegAide_Options');
		
		if( !username_exists( $username ) ){
			return;
		}
		
		$change = $options['xwrd_require_change'];
		
		if( $change == 2 ){
			return;
		}
		
		// if user never changed password check signup/registration date for when plugin is new users->user_registered
		$days = $options['xwrd_change_interval'];
		$user = get_user_by( 'login', $username );
		$xwrd_chg_name = '/ura-update-password';
		$lost_password = '?action=lost-password-need-change';
		$never_changed = '?action=lost-password-never-changed';
		$site = site_url();
		$user_id = $user->ID;
		$exists = ( boolean ) false;
		$exists = apply_filters( 'xwrd_db_exists', $exists );
		$table_name = $wpdb->prefix . "ura_xwrd_change";
		$sql_cnt = "SELECT COUNT(user_ID) FROM $table_name WHERE user_ID = '$user_id'";
		$cnt = $wpdb->get_var( $sql_cnt );
		if( empty( $cnt ) || $cnt <= 0 || $cnt == '' ){
			$url = $xwrd_chg_name.$never_changed;
			$redirect = $site.$url;
			return $redirect;
		}else{
			$url = $xwrd_chg_name.$lost_password;
			$redirect = $site.'/'.$url;
			return $redirect;
		}
		
	}
	
	/**	
	 * function xwrd_chng_ssl_redirect
	 * Redirect to ssl change password page if ssl is available
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @return
	*/
	
	function xwrd_chng_ssl_redirect(){
		global $post, $page;
		if( !empty( $post ) ){
			$id = $post->ID;
		}else{
			return;	
		}
		$xwrd_id = $this->title_id( $post );
		$options = get_option('csds_userRegAide_Options');
		$ssl = $options['xwrd_change_ssl'];
		$name = $options['xwrd_change_name'];
		$action = $options['xwrd_chng_email_url'];
		$action1 = $options['xwrd_chng_exp_url'];
		if( $ssl == 1 ){
			if( $id == $xwrd_id && !is_ssl() ){
				if( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ){
					wp_redirect(preg_replace('|^http://|', 'https://', $_SERVER['REQUEST_URI']), 301 );
					exit();

				}else{
					wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
					exit();
				}
			}elseif(  $id != $xwrd_id && is_ssl() && !is_admin() ){

				if( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ){
					wp_redirect( preg_replace( '|^https://|', 'http://', $_SERVER['REQUEST_URI'] ), 301 );
					exit();

				}else{
					wp_redirect( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
					exit();
				}

			}
		}

	}
	
	/**	
	 * function title_id
	 * Returns Post ID for checking if page is Password Change Page
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params WP_Post OBJECT $post
	 * @return int $titleid WordPress post_id
	*/
	
	function title_id( $post ){
		global $wpdb, $post;
		$options = get_option('csds_userRegAide_Options');
		$name = $options['xwrd_change_name'];
		$ssl = $options['xwrd_change_ssl'];
		$titleid = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $name . "'");
		return $titleid;
	}
	
	/**	
	 * function xwrd_chng_ssl
	 * pre_post_link filter for SSL Change Password Page if used
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $permalink, WP_Post OBJECT $post, bool $leavename
	 * @return string $permalink
	*/
	
	function xwrd_chng_ssl( $permalink, $post, $leavename ) {
		global $wpdb;
		$options = get_option('csds_userRegAide_Options');
		$name = $options['xwrd_change_name'];
		$ssl = $options['xwrd_change_ssl'];
		$titleid = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $name . "'");
		
		if( $ssl == 1 ){
			if( $titleid == $post->ID && !is_ssl() ){
				return preg_replace( '|^http://|', 'https://', $permalink );
			}
		}
		
		return $permalink;

	}
	
}