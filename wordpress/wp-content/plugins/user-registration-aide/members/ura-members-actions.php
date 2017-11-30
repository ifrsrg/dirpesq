<?php

/**
 * Class URA_MEMBERS_ACTIONS
 *
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_MEMBERS_ACTIONS
{
	
	/** 
	 * function get_user_table_views
	 * Return an associative array listing all the views that can be used
	 * with this table.
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $views An array of HTML links, one for each view including current count of users in each role.
	*/
	
	function get_user_table_views( $views ){
		global $screen;
		$screen = get_current_screen();
		$userspage = 'users_page_';
		$class = ' class="current"';
		$nuac = new NEW_USER_APPROVE_MODEL();
		$role = 'role';
		$page_role = ( string ) '';
		$default_role = get_option( 'default_role' );
		$title = ucfirst( $default_role );
		
		// Remove the 'current' class from the 'All' link
		$views['all'] = str_replace( 'class="current"', '', $views['all'] );
		$options = get_option( 'csds_userRegAide_Options' );
		$verify = $options['verify_email'];
		$approve = $options['new_user_approve'];
		$pending_title = __( ' New User Accounts Pending Approval', 'user-registration-aide' );
		$denied_title = __( 'Denied New User Accounts Pending Deletion', 'user-registration-aide' );
		$verify_title = __( 'New User Accounts Pending Email Verification', 'user-registration-aide' );
		$password_title = __( 'Admin Approved & Email Verified New User Accounts Pending Setting New User Password', 'user-registration-aide' );
		
		$subscriber_count = $nuac->subscriber_count();
		$default_count = ( int ) $nuac->role_count_2( $default_role );
		$pending_count = ( int ) $nuac->un_count( 'approve' );
		$denied_count = ( int ) $nuac->un_count( 'denied' );
		$verify_count = ( int ) $nuac->un_count( 'unverified' );
		$password_count = ( int ) $nuac->un_count( 'password' );
		
		$pending_class = ( string ) 'class=""';
		$denied_class = ( string ) 'class=""';
		$verify_class = ( string ) 'class=""';
		$password_class = ( string ) 'class=""';
		$default_class = ( string ) 'class=""';
		$subscriber_class = ( string ) 'class=""';
		
		$pending_link = ( string ) '';
		$denied_link = ( string ) '';
		$verify_link = ( string ) '';
		$password_link = ( string ) '';
		$default_link = ( string ) '';
		$subscriber_link = ( string ) '';
		
		if( !empty( $screen ) ){
			if( $screen->id == $userspage.'pending-approval' ){
				$pending_class = ' class="current"';
			}elseif( $screen->id == $userspage.'pending-deletion' ){	
				$denied_class = ' class="current"';
			}elseif( $screen->id == $userspage.'pending-verification' ){
				$verify_class = ' class="current"';
			}elseif( $screen->id == $userspage.'pending-password' ){
				$password_class = ' class="current"';
			}
		}
		
		if( isset( $_GET['role'] ) ){
			$page_role = $_GET['role'];
			if( $page_role == 'subscriber' ){
				$subscriber_class = ( string ) 'class="current"';
			}elseif( $page_role == $default_role ){
				$default_class = 'class="current"';
			}
		}
		
		$default_link = sprintf( '<a href="%1$s" class="current">%2$s</a>', esc_url( add_query_arg( 'role', $default_role, get_admin_url(  ).'users.php' ) ), sprintf( _x( $title .' (%d)', $default_role, 'user-registration-aide' ), '<span class="count">(' . number_format_i18n( $default_count ) . ')</span>' ) );
		$subscriber_link = '<a href="users.php?role=subscriber" $subscriber_class>Subscriber<span class="count"> (' . number_format_i18n( $subscriber_count ) . ')</span>';
		$pending_link = sprintf( '<a href="%1$s" '.$pending_class.' title="'.$pending_title.'">%2$s</a>', esc_url( add_query_arg( 'page', 'pending-approval', get_admin_url(  ).'users.php' ) ), sprintf( _x( 'Pending Approval <span class="count">(%d)</span>', 'pending-approval', 'user-registration-aide' ), number_format_i18n( $pending_count ) ) );
		$denied_link = sprintf( '<a href="%1$s" '.$denied_class.' title="'.$denied_title.'">%2$s</a>', esc_url( add_query_arg( 'page', 'pending-deletion', get_admin_url(  ).'users.php' ) ), sprintf( _x( 'Pending Deletion <span class="count">(%d)</span>', 'pending-deletion', 'user-registration-aide' ), number_format_i18n( $denied_count ) ) );
		$verify_link = sprintf( '<a href="%1$s" '.$verify_class.' title="'.$verify_title.'">%2$s</a>', esc_url( add_query_arg( 'page', 'pending-verification', get_admin_url(  ).'users.php' ) ), sprintf( _x( 'Pending Verification <span class="count">(%d)</span>', 'pending-verification', 'user-registration-aide' ), number_format_i18n( $verify_count ) ) );
		$password_link = sprintf( '<a href="%1$s" '.$password_class.' title="'.$password_title.'">%2$s</a>', esc_url( add_query_arg( 'page', 'pending-password', get_admin_url(  ).'users.php' ) ), sprintf( _x( 'Pending Password <span class="count">(%d)</span>', 'pending-password', 'user-registration-aide' ), number_format_i18n( $password_count ) ) );
		
		$views[$default_role] = $default_link;
		$views['subscriber'] = $subscriber_link;
		if( $approve == 1 ){
			$views['pending-approval'] = $pending_link;
			$views['pending-deletion'] = $denied_link;
		}
		if( $verify == 1 ){
			$views['pending-verification'] = $verify_link;
		}
		
		$r_fields = get_option( 'csds_userRegAide_registrationFields' );
		if( $verify == 1 || $approve == 1){
			if( !empty( $r_fields ) && is_array( $r_fields ) ){
				if( !array_key_exists( 'user_pass', $r_fields ) ){
					$views['pending-password'] = $password_link;
				}
			}else{
				$views['pending-password'] = $password_link;
			}
		}
		
		return $views;
			
	}
		
	/** 
	 * function verify_email_account
	 * Verifies new user email from email verification page
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $key
	 * @returns string $msg results of email verification process and further instructions for new users
	*/
	
	function verify_email_account( $key ){
		global $wpdb;
		$options = get_option( 'csds_userRegAide_Options' );
		$approve = $options['new_user_approve'];
		$verify = $options['verify_email'];
		$msg = (string) '';
		$login = ( string ) '';
		$password_key = ( string ) '';
		$password = ( boolean ) false;
		$table = $wpdb->prefix . "users";
		$sql = "SELECT ID FROM $table WHERE user_activation_key = %s";
		$run_query = $wpdb->prepare( $sql, $key );
		$id = $wpdb->get_var( $run_query );
		if ( $id == null ){
			return;
		}else{
			$user = get_user_by( 'ID', $id );
			if( $user == null ){
				return;
			}
			$login = wp_login_url();
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
			$ec = new URA_EMAIL_CLASS();
			if( $approve == 1 && $verify == 1 ){
				$verified = get_user_meta( $user->ID, 'email_verification', true );
				$approved = get_user_meta( $user->ID, 'approval_status', true );
				if( $verified == 'unverified' && $approved == 'pending' ){
					update_user_meta( $user->ID, 'email_verification', 'verified' );
					update_user_meta( $user->ID, 'verified', 1 );
					$msg = sprintf( __( 'Your Email %1s Was Successfully Verified!<br/>Your Account %2s Still Needs an Administrators Approval Before you can Log In.', 'user-registration-aide' ), $user->user_email, $user->user_login );
				}elseif( $verified == 'verified' && $approved == 'pending' ){
					$msg =  sprintf( __( 'Your Email %1s Already Verified!<br/>Your Account %2s Still Needs an Administrators Approval Before you can Log In.', 'user-registration-aide' ), $user->user_email, $user->user_login );
				}elseif( $verified == 'unverified' && $approved = 'approved' ){
					update_user_meta( $user->ID, 'email_verification', 'verified' );
					update_user_meta( $user->ID, 'verified', 2 );
					$this->update_user_status_field( $user->ID );
					$login = wp_login_url();
					if( $password == true ){
						$msg .= sprintf( __( 'Your email %1s was successfully verified!! <br/>You can now <a href="%2s"><b>log in here</b></a> with the username %3s and password you provided when you signed up.', 'user-registration-aide' ), $user->user_email, $login, $user->user_login );
						update_user_meta( $user->ID, 'password_set', 1 );
					}else{
						$msg .= sprintf( __( 'Your email %1s was successfully verified!! <br/>You now need to create a password for %2s <br/>using the instructions we sent to you by email.', 'user-registration-aide' ), $user->user_email, $user->user_login );
					}
				}elseif( $verified == 'verified' && $approved == 'approved' ){
					$this->update_user_status_field( $user->ID );
					$login = wp_login_url();
					if( $password == true ){
						$msg .= sprintf( __( 'Your email %1s is already verified <br/>and your account has been approved!! <br/>You can now <a href="%2s"><b>log in here</b></a> <br/>with the username %3s and password <br/>you provided when you signed up.', 'user-registration-aide' ), $user->user_email, $login, $user->user_login );
						update_user_meta( $user->ID, 'password_set', 1 );
					}else{
						$msg .= sprintf( __( 'Your email %1s was successfully verified!! <br/>You now need to create a password for %2s <br/>using the instructions we sent to you by email.', 'user-registration-aide' ), $user->user_email, $user->user_login );
					}
				}
			}elseif( $approve == 2 && $verify == 1 ){
				$verified = get_user_meta( $user->ID, 'email_verification', true );
				$approved = get_user_meta( $user->ID, 'approval_status', true );
				if( $verified == 'unverified' ){
					update_user_meta( $user->ID, 'email_verification', 'verified' );
					if( $password == true ){
						$msg = sprintf( __( 'Your email %1s was successfully verified!! <br/>You can now <a href="%2s"><b>log in here</b></a> with the username %3s <br/>and password you have on record.', 'user-registration-aide' ), $user->user_email, $login, $user->user_login );
						update_user_meta( $user->ID, 'password_set', 1 );
					}else{
						$msg .= sprintf( __( 'Your email %1s was successfully verified!! <br/>You now need to create a password for %2s <br/>using the instructions we sent to you by email.', 'user-registration-aide' ), $user->user_email, $user->user_login );
						update_user_meta( $user->ID, 'verified', 2 );
					}	
				}elseif( $verified == 'verified' ){
					if( $password == true ){
						$msg .= sprintf( __( 'Your email %1s is already verified <br/>and your account has been approved!! <br/>You can now <a href="%2s"><b>log in here</b></a><br/> with the username %3s and password <br/>you provided when you signed up.', 'user-registration-aide' ), $user->user_email, $login, $user->user_login );
						update_user_meta( $user->ID, 'password_set', 1 );
					}else{
						$msg .= sprintf( __( 'Your email %1s was already verified!! <br/>You now need to create a password for %2s <br/>using the instructions we sent to you by email.', 'user-registration-aide' ), $user->user_email, $user->user_login );
						update_user_meta( $user->ID, 'verified', 2 );
					}	
				}
			}elseif( $approve == 1 && $verify == 2 ){
				$verified = get_user_meta( $user->ID, 'email_verification', true );
				$approved = get_user_meta( $user->ID, 'approval_status', true );
				if( $approved == 'pending' ){
					update_user_meta( $user->ID, 'email_verification', 'verified' );
					$msg = sprintf( __( 'Your Account %s Needs to be Approved <br/>by an Administrator Before you <br/>can Login to This Site!', 'user-registration-aide' ), $user->user_login );
				}elseif( $approved = 'approved' ){
					update_user_meta( $user->ID, 'email_verification', 'verified' );
					$this->update_user_status_field( $user->ID );
					if( $password == true ){
						$msg .= sprintf( __( 'Your Account %1s has been <br/>Approved by an Administrator! <br/>You can now <a href="%2s"><b>log in here</b></a> <br/>with the username and password <br/>you provided when you signed up.', 'user-registration-aide' ), $login, $user->user_login );
						update_user_meta( $user->ID, 'password_set', 1 );
					}else{
						$msg .= sprintf( __( 'Your Account With Email Address %1s is Approved! <br/>You now need to create a password for %2s <br/>using the instructions we sent to you by email.', 'user-registration-aide' ), $user->user_email, $user->user_login );
					}	
				}
			}elseif( $approve == 2 && $verify == 2 ){
				if( $password == true ){
					$msg .= sprintf( __( 'Your Account With Email Address %1s is all Ready!! <br/>You can now <a href="%2s"><b>log in here</b></a> <br/>with the username %3s and password <br/>you provided when you signed up.', 'user-registration-aide' ), $user->user_email, $login, $user->user_login );
					update_user_meta( $user->ID, 'password_set', 1 );
				}else{
					$msg .= sprintf( __( 'Your Account With Email Address %1s <br/>is Almost Ready! <br/>You now need to create a password <br/>for %2s using <br/>the instructions we sent to you by email.', 'user-registration-aide' ), $user->user_email, $user->user_login );
					update_user_meta( $user->ID, 'email_verification', 'verified' );
				}	
			}
			$verified = get_user_meta( $user->ID, 'email_verification', true );
			$approved = get_user_meta( $user->ID, 'approval_status', true );
			$password_set = get_user_meta( $user->ID, 'password_set', true );
			switch( $approved ){
				case 'approved':
					switch( $password_set ){
						case 0:
							do_action( 'set_password_email_notice', $user );
							break;
						case 1:
							do_action( 'signup_complete_email', $user );
							break;
						case 2:
							do_action( 'set_password_email_notice', $user );
							break;
					}
					break;
				case 'pending':
					do_action( 'user_email_verification', $user );
					break;
			}
			
			if( $approved == 'approved' && $password == true ){
				do_action( 'activate_new_user_account', $user->ID );
			}
			do_action( 'email_verified_admin_notice', $user->ID );
			return $msg;
		}
	}	
	
	/**
	 * function set_new_user_password
	 * intercepts WordPress validate_password_reset action to add our own process to the process
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params object $errors WP Error object.
	 * @params WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
	 * @returns 
	*/
	
	function set_new_user_password( $errors, $user ){
		global $errors;
		if( !is_wp_error( $user ) ){
			$id = $user->ID;
			update_user_meta( $id, 'password_set', 1 );
			do_action( 'activate_new_user_account', $id );
			if( isset( $_GET['action'] ) && $_GET['action'] == 'resetpass' ){
				do_action( 'signup_complete_email', $user );
			}
		}
		return;
	}
		
	/** 
	 * function update_user_activation_key
	 * updates user activation key in database
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params int $user_id
	 * @returns
	*/
		
	function update_user_activation_key( $user_id ){
		global $wpdb;
		// db steps to update key
		$user = get_user_by( 'ID', $user_id );
		$table = $wpdb->prefix.'users';
		$update_sql = "UPDATE $table SET user_status = 2 WHERE ID = %d";
		$update = $wpdb->prepare( $update_sql, $user_id );
		$results = $wpdb->query( $update );
		$key = apply_filters( 'create_user_key', $user );
		$update_key = "UPDATE $table SET user_activation_key = %s WHERE ID = %d";
		$update_akey = $wpdb->prepare( $update_key, $key, $user_id );
		$results = $wpdb->query( $update_akey );
	}
	
	/** 
	 * function update_user_status_field
	 * Set new user status if needs to be approved or email verified
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params int $user_id
	 * @returns
	*/
		
	function update_user_status_field( $user_id ){
		global $wpdb;
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
		$options = get_option( 'csds_userRegAide_Options' );
		$approve = $options['new_user_approve'];
		$verify = $options['verify_email'];
		$table = $wpdb->prefix.'users';
		if( $approve == 1 || $verify == 1 ){
			if( $password == false ){
				update_user_meta( $user_id, 'password_set', 2 );
			}else{
				update_user_meta( $user_id, 'password_set', 1 );
			}
			$data = array(
				'user_status'	=>	2
			);
		}else{
			$data = array(
				'user_status'	=>	0
			);
		}
		$where = array(
			'ID'	=>	$user_id
		);
		$wpdb->update( $table, $data, $where );
		
	}
	
	/** 
	 * function clear_user_status_field
	 * Updates user status after approved and email verified
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params int $user_id
	 * @returns
	*/
		
	function clear_user_status_field( $user_id ){
		global $wpdb;
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
		$verified = get_user_meta( $user_id, 'email_verification', true );
		$approved = get_user_meta( $user_id, 'approval_status', true );
		$password_set = get_user_meta( $user_id, 'password_set', true );
		if( $approved == 'approved' && $verified == 'verified' && ( $password == true || $password_set == 1 ) ){
			$table = $wpdb->prefix.'users';
			$data = array(
				'user_status'	=>	0
			);
			$where = array(
				'ID'	=>	$user_id
			);
			$table2 = $wpdb->prefix.'usermeta';
			$wpdb->update( $table, $data, $where );
			delete_user_meta( $user_id, 'approved_email', "" );
			delete_user_meta( $user_id, 'admin_notification', "" );
			delete_user_meta( $user_id, 'admin_email_verified_notification', "" );
			delete_user_meta( $user_id, 'password_set', "" );
			delete_user_meta( $user_id, 'verified', "" );
			$wpdb->delete( $table2, array( 'meta_key' => 'approved_email', 'user_id' => $user_id )  );
			$wpdb->delete( $table2, array( 'meta_key' => 'admin_notification', 'user_id' => $user_id )  );
			$wpdb->delete( $table2, array( 'meta_key' => 'admin_email_verified_notification', 'user_id' => $user_id )  );
			$wpdb->delete( $table2, array( 'meta_key' => 'password_set', 'user_id' => $user_id )  );
			$wpdb->delete( $table2, array( 'meta_key' => 'verified', 'user_id' => $user_id )  );
		}
		return;
	}
	
	/** 
	 * function update_user_roles
	 * Updates user role if is from another blog and using one user table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/
		
	function update_user_roles( ){
		global $user_ID, $wpdb;
		get_currentuserinfo();
		if( !empty( $user_ID ) ){
			$prefix = $wpdb->prefix;
			$base_prefix = $this->get_base_prefix();
			$caps = get_user_meta( $user_ID, $prefix.'capabilities', false  );
			if( !empty( $caps ) ){
				$role = $this->get_user_cap( $user_ID, $prefix );
			}else{
				$caps = get_user_meta( $user_ID, $base_prefix.'capabilities', false  );
				$role = $this->get_user_cap( $user_ID, $base_prefix );
			}
			if( !empty( $role ) ){
				if ( !current_user_can( 'read' ) ){
					$user = new WP_User( $user_ID );
					$user->set_role( $role );
				}
			}
		}
		
	}
	
	/**
	 * function xwrd_reset_key_set
	 * Updates password set key in user meta for user table placement
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params object           $errors WP Error object.
	 * @params WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
	 * @returns 
	*/
	
	function xwrd_reset_key_set( $errors, $user ){
		$fields = get_option( 'csds_userRegAide_registrationFields' );
		if( !empty( $fields ) ){
			if( !array_key_exists( 'user_pass', $fields ) ){
				if( isset( $_GET['action'] ) && $_GET['action'] == 'resetpass' ){
					if( !is_wp_error( $user ) ){
						$password_set = get_user_meta( $user->ID, 'password_set', true );
						$password_set = 1;
						update_user_meta( $user->ID, 'password_set', $password_set );
						do_action( 'activate_new_user_account', $user->ID );
					}
				}elseif( isset( $_GET['action'] ) && $_GET['action'] == 'rp' ){
					if( !is_wp_error( $user ) ){
						$password_set = get_user_meta( $user->ID, 'password_set', true );
						$password_set = 1;
						update_user_meta( $user->ID, 'password_set', $password_set );
						do_action( 'activate_new_user_account', $user->ID );
					}
				}
			}
		}else{
			if( isset( $_GET['action'] ) && $_GET['action'] == 'resetpass' ){
				if( !is_wp_error( $user ) ){
					$password_set = get_user_meta( $user->ID, 'password_set', true );
					$password_set = 1;
					update_user_meta( $user->ID, 'password_set', $password_set );
					do_action( 'activate_new_user_account', $user->ID );
				}
			}elseif( isset( $_GET['action'] ) && $_GET['action'] == 'rp' ){
				if( !is_wp_error( $user ) ){
					$password_set = get_user_meta( $user->ID, 'password_set', true );
					$password_set = 1;
					update_user_meta( $user->ID, 'password_set', $password_set );
					do_action( 'activate_new_user_account', $user->ID );
				}
			}
		}
		unset( $fields );
	}
	
	/** 
	 * function update_users_roles
	 * Updates all users roles if is from another blog and using one user table 
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/
		
	function update_users_roles(){
		global $wpdb;
		$members = $this->get_all_members();
		$base_prefix = $this->get_base_prefix();
		
		foreach ( $members as $user_ID ){
			$user = new WP_User( $user_ID );
			$prefix = $wpdb->prefix;
			$caps = get_user_meta( $user_ID, $prefix.'capabilities', false  );
			
			if( !empty( $caps ) ){
				$role = $this->get_existing_user_cap( $user_ID, $prefix );
			}else{
				$caps = get_user_meta( $user_ID, $base_prefix.'capabilities', false  );
				if( !empty( $caps ) ){
					$role = $this->get_existing_user_cap( $user_ID, $base_prefix );
				}else{
					$prefixes = $this->get_table_prefixes();
					if( !empty( $prefixes ) ){
						if( is_array( $prefixes ) ){
							foreach( $prefixes as $index => $aprefix ){
								$caps = get_user_meta( $user_ID, $aprefix.'capabilities', false  );
								
								if( !empty( $caps ) ){
									
									$role = $this->get_existing_user_cap( $user_ID, $aprefix );
									break;
								}
							}
						}else{
							$caps = get_user_meta( $user_ID, $prefixes.'capabilities', false  );
							if( !empty( $caps ) ){
								$role = $this->get_existing_user_cap( $user_ID, $prefixes );
							}
						}
					}
				}
			}
			
			//exit( 'ROLE: '.$role.'<br/>USER: '.print_r( $user ) );
			if( !empty( $role ) ){
				if ( !$user->has_cap( 'read' ) ){
					$user->set_role( $role );
				}
			}
		}
		//$options = get_option('csds_userRegAide_Options');
		//$options['user_roles_updated'] = "1";
		//update_option( 'csds_userRegAide_Options', $options );
	}
		
	/** 
	 * function roll_update
	 * Updates roles on registration if one user table used for multiple blogs 
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params int $user_id
	 * @returns
	*/
		
	function roll_update( $user_ID ){
		global $wpdb;
		$options = get_option( 'csds_userRegAide_Options' );
		$temps = $options['db_prefixes'];
		$prefixes = $this->get_table_prefixes();
		if ( defined( 'CUSTOM_USER_TABLE' ) || !empty( $temps ) ){
			$base_prefix = $this->get_base_prefix();
			$prefix = $wpdb->prefix;
			$pcnt = (int) 0;
			$results = ( boolean ) false;
			if( !empty( $prefixes ) ){
				if( is_array( $prefixes ) ){
					foreach( $prefixes as $index => $aprefix ){
						if( $prefix != $base_prefix ){
							$level = get_user_meta( $user_ID, $prefix.'user_level', true  );
							$role = $this->get_user_cap( $user_ID, $prefix );
							if( $pcnt == 0 ){
								update_user_meta( $user_ID, $base_prefix.'capabilities', $role );
								update_user_meta( $user_ID, $base_prefix.'level', $level );
								$results = true;
							}
							if( $prefix != $aprefix ){
								update_user_meta( $user_ID, $aprefix.'capabilities', $role );
								update_user_meta( $user_ID, $aprefix.'level', $level );
								$results = true;
							}
						}elseif( $prefix == $base_prefix ){
							$level = get_user_meta( $user_ID, $prefix.'user_level', true  );
							$role = $this->get_user_cap( $user_ID, $prefix );
							update_user_meta( $user_ID, $prefixes.'capabilities', $role );
							update_user_meta( $user_ID, $aprefix.'level', $level );
							$results = true;
						}
						$pcnt++;
					}						
				}else{
					if( $prefix != $base_prefix ){
						$level = get_user_meta( $user_ID, $prefix.'user_level', true  );
						$role = $this->get_user_cap( $user_ID, $prefix );
						update_user_meta( $user_ID, $prefixes.'capabilities', $role );
						update_user_meta( $user_ID, $base_prefix.'level', $level );
						$results = true;
					}elseif( $prefix == $base_prefix ){
						$level = get_user_meta( $user_ID, $prefix.'user_level', true  );
						$role = $this->get_user_cap( $user_ID, $prefix );
						update_user_meta( $user_ID, $prefixes.'capabilities', $role );
						update_user_meta( $user_ID, $prefixes.'level', $level );
						$results = true;
					}
				}
			}
		}
		$pcnt = 0;
		return $user_ID;
	}
	
	/** 
	 * function get_user_cap
	 * Gets the role for given user
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params int $user_id, string $prefix ( database prefix )
	 * @returns string $capabilities
	*/
		
	function get_user_cap( $user_id, $prefix ){
		global $wpdb, $wp_roles;
		if( !empty( $user_id ) && !empty( $prefix ) ){
			$user = get_userdata( $user_id );
			$capabilities = $user->{$prefix . 'capabilities'};
			return $capabilities;
		}
	}
	
	/** 
	 * function get_existing_user_cap
	 * Gets the role for given existing user
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params int $user_id, string $prefix ( database prefix )
	 * @returns string $role
	*/
		
	function get_existing_user_cap( $user_id, $prefix ){
		global $wpdb, $wp_roles;
		if( !empty( $user_id ) && !empty( $prefix ) ){
			$user = get_userdata( $user_id );
			$caps = $user->{$prefix . 'capabilities'};
			if ( !isset( $wp_roles ) ){
				$wp_roles = new WP_Roles();
			}
			if( !empty( $wp_roles ) && !empty( $caps ) ){
				foreach ( $wp_roles->role_names as $role => $name ) {
					if ( array_key_exists( $role, $caps ) ){
						return $role;
					}
				}
			}
		}
	}
	
	/** 
	 * function get_table_prefixes
	 * Gets the prefixes for all tables in database if using same database for multiple sites
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns array $prefixes ( database prefixes )
	*/
		
	function get_table_prefixes(){
		global $wpdb;
		$db_name = DB_NAME;
		$db_object = 'Tables_in_'.$db_name;
		$sql = "show tables from $db_name"; // run the query and assign the result to $result
		$results = $wpdb->get_results( $sql, ARRAY_A );
		$prefixes = array();
		foreach( $results as $result ){
			foreach( $result as $table ){
				$prefix = explode( '_', $table );
				$prefix = $prefix[0].'_';
				if( !in_array( $prefix, $prefixes ) ){
					$prefixes[] = $prefix;
				}
			}
		}
		return $prefixes;
	}
	
	/** 
	 * function get_base_prefix
	 * Gets the base prefix for the primary site when multiple sites using same database
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns array $prefixes ( database prefixes )
	*/
		
	function get_base_prefix(){
		global $wpdb;
		if ( defined( 'CUSTOM_USER_TABLE' ) ){
			$user_table = CUSTOM_USER_TABLE;
			$temp = explode( '_', $user_table );
			$base_prefix = $temp[0].'_';
		}else{
			$base_prefix = $wpdb->prefix;
		}
		return $base_prefix;
	}
	
	/** 
	 * function get_all_members
	 * Gets all members for updating roles if one user table used for multiple blogs
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns array $results all users ID's
	*/
		
	function get_all_members(){
		global $wpdb;
		$results = $wpdb->get_col( "SELECT ID FROM $wpdb->users" );
		return $results;
	}
	
	/** 
	 * function process_members_link_actions
	 * processes the URA link actions for the user list table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $action
	 * @params int $user_id
	 * @returns 
	*/
   
    function process_members_link_actions( $action, $user_id ) {
		global $screen;
		$screen = get_current_screen();
		$userspage = 'users_page_';
		if( !empty( $screen ) ){
			//if( $screen->id == $userspage.'pending-approval' ){
			//wp_die( print_r( $user_id ) );
		}
		if( !empty( $action ) && !empty( $user_id ) ){
			$class = ( string ) 'updated';
			$pre_msg = '<div id="message" class="$class"><p><strong>';
			$post_msg = '</strong></p></div>';
			$msg = ( string ) '';
			$user = get_user_by( 'ID', $user_id );
			$username = $user->user_login;
			$nuac = new NEW_USER_APPROVE_MODEL();
			if( 'do_approve_user' === $action ) {
				$nuac->approve_user( $user_id );
				$msg = sprintf( __( 'New User %s Approved!', 'user-registration-aide' ), $username ); 
			}elseif( 'do_deny_user' === $action ) {
				$nuac->deny_user( $user_id );
				$msg = sprintf( __( 'New User %s Denied!', 'user-registration-aide' ), $username ); 
			}elseif( 'do_resend_email' === $action ) {
				$nuac->resend_user_email( $user_id );
				$msg = sprintf( __( 'New User %s Emailed!', 'user-registration-aide' ), $username ); 
			}elseif( 'do_activate_user' === $action ) {
				$nuac->activate_user( $user_id );
				$msg = sprintf( __( 'New User %s Activated!', 'user-registration-aide' ), $username ); 
			}elseif( 'do_delete_user' === $action ) {
				$nuac->denied_user_delete( $user_id );
				$msg = sprintf( __( 'New User %s Deleted!', 'user-registration-aide' ), $username ); 
			}elseif( 'do_resend_password_email' === $action ) {
				$nuac->password_email_update( $user_id );
				$msg = sprintf( __( 'New User %s Password Reset Link Emailed!', 'user-registration-aide' ), $username ); 
			}
			if( !empty( $msg ) ){
				$class = 'updated';
				$pre_msg = $pre_msg = '<div id="message" class="'.$class.'"><p><strong>';
				return $pre_msg.$msg.$post_msg;
				//echo $pre_msg.$msg.$post_msg;
			}else{
				$class = 'error';
				$pre_msg = $pre_msg = '<div id="message" class="'.$class.'"><p><strong>';
				$msg = 'No Users Updated!';
				return $pre_msg.$msg.$post_msg;
				//echo $pre_msg.$msg.$post_msg;
			}
		}else{
			$class = 'error';
			$msg = 'Unable to Process User, No Users Status Updated!';
			$pre_msg = $pre_msg = '<div id="message" class="'.$class.'"><p><strong>';
			return $pre_msg.$msg.$post_msg;
			//echo $pre_msg.$msg.$post_msg;
		}
	}
}