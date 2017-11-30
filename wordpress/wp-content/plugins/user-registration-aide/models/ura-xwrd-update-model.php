<?php

/**
 * Class UPDATE_PASSWORD_MODEL
 *
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class UPDATE_PASSWORD_MODEL
{
	
	/**
	 * function update_xwrd_model
	 * Handles password update page input and errors
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $results of messages and errors for form to display on reload
	*/
	
	function update_xwrd_model( $results ){
		global $wpdb, $current_user, $post;
		
		apply_filters( 'force_ssl', false, $post );
		$options = get_option('csds_userRegAide_Options');
		$table_name = ( string ) $wpdb->prefix . "ura_xwrd_change";
		$ip = $this->get_user_ip_address();
		$nonce = wp_nonce_field(  'csds-passChange', 'wp_nonce_csds-passChange' );
		
		// declaring function variables
		$err = ( int ) 0;
		$action = ( string ) '';
		$ssl = ( string ) '';
		$line = ( string ) '';
		$form = ( string ) '';
		$request_uri = ( string ) '';
		$referer = ( string ) '';
		$user_host = ( string ) '';
		$user_agent = ( string ) '';
		$changed = ( string ) '';
		$get_action = ( string ) '';
		$ura_action = ( string ) '';
		$verify_xwrd = array();
		$email2 = ( string ) '';
		$xwrd_check = ( boolean ) false;
		$password = ( string ) '';
		$oldPassHashed = ( string ) '';
		$pass1 = ( string ) '';
		$pass2 = ( string ) '';
		
		//server variables
		$method = $_SERVER['REQUEST_METHOD'];
		$request_uri = $_SERVER['REQUEST_URI'];
		$user_host = gethostbyaddr($ip);
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if( isset( $_SERVER['HTTP_REFERER'] ) ){
			$referer = $_SERVER['HTTP_REFERER'];
		}else{
			if( isset( $_GET['action'] ) ){
				$action = $_GET['action'];
				$referer = '/ura-update-password/?action='.$action;
			}else{
				$referer = '/ura-update-password/?action=rlp';
			}
		}
		
		// initial page load before user eners information
		if( !isset( $_POST['update_xwrd-reset'] ) ){
			if( isset( $_GET['action'] ) ){
				$get_action = $_GET['action'];
			}
			
			$ssl = 'NO';
			
			if( is_ssl() ){
				$ssl = 'YES';
			}
						
			$pfs = new PASSWORD_FUNCTIONS();
			// post id
			$post_id = $post->ID;
			$title_id = $pfs->title_id( $post );
			
			if( isset( $_GET['action'] ) && $_GET['action'] == 'new-register' ){
				$line = '<div class="lost-password-error">'. __( 'The Password You Received in the Email Was Temporary and Has Expired, <br/> Please Change Your Password Now!', 'user-registration-aide' ).'</div>';
				$action = 'new-register';
			}elseif( isset( $_GET['action'] ) && $_GET['action'] == 'expired-password' ){
				$line = '<div class="lost-password-error">'. __( 'Your Password Has Expired, Please Change Your Password!', 'user-registration-aide' ).'</div>';
				$referer = $_SERVER['HTTP_REFERER'];
				$action = 'expired-password';
			}elseif( isset( $_GET['action'] ) && $_GET['action'] == 'password-never-changed' ){
				$line = '<div class="lost-password-error">'. __( 'You Have Not Changed Your Password Since You Signed Up <br/> and Your Password Has Expired, Please Change Your Password Now!', 'user-registration-aide' ).'</div>';
				$action = 'password-never-changed';
			}elseif( isset( $_GET['action'] ) && $_GET['action'] == 'lost-password-never-changed' ){
				$line = '<div class="lost-password-error">'. __( 'You can Reset Your Lost Password Here and <br/>You Have Not Changed Your Password Since You Signed Up <br/> and Your Password Has Expired, Please Change Your Password Now!', 'user-registration-aide' ).'</div>';
				$action = 'lost_password_reset';
			}elseif( isset( $_GET['action'] ) && $_GET['action'] == 'lost-password-need-change' ){
				$line = '<div class="lost-password-error">'. __( 'Your Lost Password is Expired and Needs to be Changed <br/>Please Change Your Password Below Now!', 'user-registration-aide' ).'</div>';
				$action = 'lost_password_reset';
			}
			$m_results = array( $line, $nonce, $action, $err );
			return $m_results;
		}elseif( isset( $_POST['update_xwrd-reset'] ) ){
			
			$wp_error = new WP_Error();
			if( ! wp_verify_nonce( $_POST['wp_nonce_csds-passChange'], 'csds-passChange' ) ){
				wp_die( __( 'Failed Update Password Nonce Security Validation!', 'user-registration-aide' ) );
			}
			$request_uri = $_SERVER['REQUEST_URI'];
			$referer = $_SERVER['HTTP_REFERER'];
			$login = $_POST['user_login'];
			$email = $_POST['user_email'];
			
			if( isset( $_GET['action'] ) && $_GET['action'] != 'rlp' || $_GET['action'] != 'lost-password-need-change' || $_GET['action'] != 'lost-password-never-changed' ){
				if( isset( $_POST['old_pass1'] ) ){
					$password = $_POST['old_pass1'];
					$oldPassHashed = wp_hash_password( $password );
				}else{
					$password = ( string ) '';
					$oldPassHashed = ( string ) '';
				}
				
				$pass1 = $_POST['pass1'];
				$pass2 = $_POST['xwrd2'];
				if( !empty( $password ) ){
					$user = wp_authenticate( $login, $password );	
				}else{
					$user = get_user_by( 'login', $login );
				}
			}else{
				if( $_GET['action'] == 'lost-password-need-change' || $_GET['action'] == 'lost-password-never-changed' ){
					$action = 'lost_password_reset';
				}
				$password = ( string ) '';
				$pass1 = $_POST['pass1'];
				$pass2 = $_POST['xwrd2'];
				$user = get_user_by( 'login', $login );	
			}
			
			if( !empty( $user ) && !is_wp_error( $user ) ){
				if( empty( $oldPassHashed ) ){
					if( !empty( $user ) ){
						$oldPassHashed = $user->user_pass;
					}else{
					
						$sql_oph = "SELECT user_pass FROM $table WHERE ID = '%d'";
						$sql_results = $wpdb->prepare( $sql_oph, $user->ID );
						$oldPassHashed = $wpdb->get_var( $sql_results );
					
					}
				}
				
				if( !empty( $pass1 ) ){
					$user_id = $user->ID;
					$newPassHashed = wp_hash_password( $pass1 );
					if( $user && wp_check_password( $pass1, $user->user_pass, $user->ID ) ){
						$wp_error->add( 'old_new_password_match', __( "<b>ERROR</b>: NEW PASSWORD SAME AS OLD PASSWORD, PLEASE ENTER A DIFFERENT PASSWORD!", 'user-registration-aide' ) );
						$err++;
					}
				}
				
				if( empty( $login ) ){
					$wp_error->add( 'empty_email' , __( "<b>ERROR</b>: Please Enter Your Username!", 'user-registration-aide' ) );
					$err++;
				}elseif( !username_exists( $login ) ){
					$wp_error->add( 'empty_email' , __( "<b>ERROR</b>: Username Does Not Exist!", 'user-registration-aide' ) );
					$err++;
				}
				if( empty( $_POST['user_email'] ) ){
					$wp_error->add( 'empty_email' , __( "<b>ERROR</b>: Please Enter your Email", 'user-registration-aide' ) );
					$err++;
				}elseif( !empty( $email ) ){
					if( !is_wp_error( $user ) ){
						$email2 = $user->user_email;
					}
					
					if( $email != $email2 ){
						$wp_error->add( 'emails_not_match', __( "<b>ERROR</b>: Email Associated With This Account And Email Entered Do Not Match!", 'user-registration-aide' ) );
						$err++;
					}
				}
				if( $password == $pass1 && $action != 'rlp' ){
					//exit( 'RLP 1!!!' );
					$wp_error->add( 'old_new_password_match', __( "<b>ERROR</b>: NEW PASSWORD SAME AS OLD PASSWORD, PLEASE ENTER A DIFFERENT PASSWORD!", 'user-registration-aide' ) );
					$err++;
				}
				
				if( $action != 'rlp' ){
					// filter for field errors
					$verify = apply_filters( 'custom_password_strength', $pass1, $pass2, $login, $email, $wp_error, $err );
					if( !is_wp_error( $user ) ){
						if( isset( $_GET['action'] ) ){
							$g_action = $_GET['action'];
							if( $g_action == 'lost-password-need-change' || $g_action == 'lost-password-never-changed' ){
								$action = 'lost_password_reset';
							}
						}
						$verify_xwrd = apply_filters( 'duplicate_verify', $user, $pass1, $verify, $err );
						$codes = $verify_xwrd->get_error_codes();
						$err += count( $codes );
						if( is_wp_error( $verify_xwrd ) ){
							$wp_error = $verify_xwrd;
							if( $action == 'lost_password_reset' ){
								$action = 'lost_password_reset_error';
							}else{
								$action = 'error';
							}
						}
					}
				}
			}elseif( is_wp_error( $user ) ){
				$errors = $user->get_error_messages();
				$err += count( $errors );
				$action = 'error';
				foreach( $errors as $error ){
					$line .= $error.'<br/>';
				}
				$m_results = array( $line, $nonce, $action, $err );
				return $m_results;
			}elseif( empty( $user ) ){
				$err ++;
				$action = 'error';
				$line .= __( 'Error processing user information, please try again!',  'user-registration-aide' ) . '<br/>';
				$m_results = array( $line, $nonce, $action, $err );
				return $m_results;
			}else{
				$err ++;
				$action = 'error';
				$line .= __( 'Error processing password update information, please try again!',  'user-registration-aide' ) . '<br/>';
				$m_results = array( $line, $nonce, $action, $err );
				return $m_results;
			}
					
			// Errors Displayed
			if( is_wp_error( $user ) ){
				$errors = $user->get_error_messages();
				$err += count( $errors );
				foreach( $errors as $error ){
					$line .= $error.'<br/>';
				}
			}elseif( !empty( $err ) && $get_action != 'rlp' ){
				if( !empty( $wp_error ) ){
					$errors = $wp_error->get_error_messages();
					$err += count( $errors );
					foreach( $errors as $error ){
						$line .= $error.'<br/>';
					}
				}
			}elseif( !is_wp_error( $user ) && empty( $err ) && $action != 'rlp' ){
				if( $err == 0 ){
					
					// storing password change data in database
					$insert = "INSERT INTO " . $table_name . " ( user_ID, change_date, change_IP, old_password, change_uagent, change_referrer, change_uhost, change_uri_request ) " ."VALUES ( '" . $user_id . "', now(), '%s', '%s', '%s', '%s', '%s', '%s' )";
										
					$insert = $wpdb->prepare( $insert, $ip, $oldPassHashed, $user_agent, $referer, $user_host, $request_uri );
					$results = $wpdb->query( $insert );
					
					if( $results == 1){
						$line .= __( '<b>Updated</b>: Password Change Records Updated!', 'user-registration-aide' ).'<br/>';
					}else{
						$line .= __( '<b>Error</b>: Database Record Failed!', 'user-registration-aide' ).'<br/>';
						$line .= __( '<b>Error</b>: Password Update Failed!', 'user-registration-aide' ).'<br/>';
						$line .= $form;
					}
					
					if( $results == 1 ){
						$results2 = wp_set_password( $pass1, $user_id );
						
						if( is_wp_error( $results2 ) ){
							$errors = $results2->get_error_messages();
							foreach( $errors as $error ){
								$line .= $error;
							}
						}else{
							// shows login form if successful password change - **must log user out to properly change password**
							$line .= __( '<b>Updated</b>: Password Updated!', 'user-registration-aide' ).'<br/>';
							$line .= __( '<b>Message</b>: You Were Logged Out to Change Your Password Properly, Sorry for Any Inconvenience!', 'user-registration-aide' ).'<br/>';
							
							$redirect = wp_login_url();
							$redirect = esc_url( $redirect );
							$line .= sprintf( __( '<b>Message</b>: <a href="%s">Log In With New Password Here!', 'user-registration-aide' ), $redirect ).'<br/>';
							$action = 'success';
							//$m_results = array( $line, $nonce, $action, $err );
							//return $m_results;
						}
					}
				}
			}
			$m_results = array( $line, $nonce, $action, $err );
			return $m_results;
		}
		
	}
	
	/**	
	 * function get_user_ip_address
	 * Gets user IP address from all possible lists
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns string $ip_address IP Address of current user
	*/
	 
	function get_user_ip_address() {
		$ip_address = '';
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
	 * function update_password_form_template
	 * Loads all scripts and styles for password strength meter on URA templates and pages
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function update_password_form_template(){
		do_action( 'xwrd_scripts_register' );
		do_action( 'xwrd_scripts_load' );
	}
	
	/**	
	 * function update_password_form_template
	 * Loads all scripts and styles for password strength meter on URA templates and pages
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function update_password_actions_array(){
		$actions = array(
			'new-register'					=>	'New Register',
			'expired-password'				=>	'Expired Password',
			'password-never-changed'		=>	'Password Never Changed',
			'lost_password_reset'			=>	'Lost Password Reset',
			'lost_password_reset_error'		=>	'Lost Password Reset Error',
			'error'							=>	'Error',
			'valid-key'						=>	'Valid Key'
		);
		return $actions;
	}
	
}