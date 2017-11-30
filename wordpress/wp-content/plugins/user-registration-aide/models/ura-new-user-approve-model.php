<?php

/**
 * Class NEW_USER_APPROVE_MODEL
 * User Registration Aide - New User Approve Page Controller
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class NEW_USER_APPROVE_MODEL
{
	
	public static $instance;
	
	/** 
	 * function __construct
	 * New user approve table controller constructor
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	public function __construct() {
		$this->NEW_USER_APPROVE_MODEL();
	}
	
	/** 
	 * function NEW_USER_APPROVE_MODEL
	 * New user approve table view controller
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function NEW_USER_APPROVE_MODEL() { //constructor
		
		self::$instance = $this;
		
	}
	
	/** 
	 * function un_count
	 * Creates and returns a count of unapproved, denied and unverified users
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $status
	 * @returns int $count count of users unapproved
	*/
	
	function un_count( $status ){
		global $wpdb;
		$table = $wpdb->prefix.'users';
		$sql = "SELECT ID FROM $table WHERE user_status = 2";
		$ids = $wpdb->get_results( $sql, ARRAY_A );
		$count = ( int ) 0;
		foreach( $ids as $arr ){
			foreach( $arr as $id ){
				$pending = get_user_meta( $id, 'approval_status', true );
				$verified = get_user_meta( $id, 'email_verification', true );
				$password = get_user_meta( $id, 'password_set', true );
				if( $status == 'approve' ){
					if( $pending == 'pending' ){
						$count++;
					}
				}elseif( $status == 'denied' ){
					if( $pending == 'denied' ){
						$count++;
					}
				}elseif( $status == 'unverified' ){
					if( $verified == 'unverified' ){
						$count++;
					}
				}elseif( $status == 'password' ){
					if( $password == 2 ){
						if( $pending == 'approved' && $verified == 'verified' ){
							$count++;
						}
					}
				}
			}
		}
		return $count;
		
	}
	
	/** 
	 * function subscriber_count
	 * Creates and returns a count of unapproved, denied and unverified users
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $status
	 * @returns int $count count of users unapproved
	*/
	
	function subscriber_count(){
		global $wpdb;
		$table = $wpdb->prefix.'users';
		$sql = "SELECT ID FROM $table WHERE user_status = 0";
		$ids = $wpdb->get_results( $sql, ARRAY_A );
		$count = ( int ) 0;
		foreach( $ids as $arr ){
			foreach( $arr as $id ){
				$nuser = get_userdata( $id );
				$roles = $nuser->roles;
				$userrole = array_shift( $roles );
				if( $userrole == 'subscriber' ){
					$count++;
				}
			}
		}
		return $count;
	}
	
	/** 
	 * function role_count
	 * Creates and returns a count of unapproved users
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $role
	 * @returns int $count count of users unapproved
	*/
	
	function role_count( $role ){
		global $wpdb;
		$count = ( int ) 0;
		$args = array(
				'role' 			=> 'subscriber',
				'meta_key'     	=> 'approval_status',
				'meta_value'   	=> 'pending',
				'meta_compare'	=> '=',
				'meta_key'		=> 'email_verification',
				'meta_value'   	=> 'verified',
				'meta_compare'	=> '='
			);
			
		$wp_user_search = new WP_User_Query( $args );
		$count = $wp_user_search->get_total();
		return $count;
	}
	
	/** 
	 * function role_count_2
	 * Creates and returns a count for approved and verified users ( selected role ) for user view user counts
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $role WordPress role for selected count
	 * @returns int $count count of users in current role
	*/
		
	function role_count_2( $role ){
		global $wpdb;
		$count = ( int ) 0;
		$args = array(
			'role' 			=> $role,
			'meta_key'     	=> 'approval_status',
			'meta_value'   	=> 'approved',
			'meta_compare' 	=> '=',
			'meta_key'		=> 'email_verification',
			'meta_value'   	=> 'verified',
			'meta_compare' 	=> '=',
			'orderby'		=> 'registered',
			'order'			=> 'DESC',
			'fields' 		=> 'all_with_meta'
		);
		$wp_user_search = new WP_User_Query( $args );
		$search = $wp_user_search->get_results();
		$count = count( $search );
		return $count;
		
	}
	
	/** 
	 * function un_list
	 * Creates and returns an array for unapproved users to view in table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array WP_OBJECT WP_USERS $users array of WP Users for unapproved users list
	*/
		
	function un_list(){
		global $wpdb;
		$table = $wpdb->prefix.'users';
		$args = array(
			'blog_id'      => $GLOBALS['blog_id'],
			'role'         => '',
			'meta_key'     => 'approval_status',
			'meta_value'   => 'pending',
			'meta_compare' => '=',
			'meta_query'   => array(),
			'include'      => array(),
			'exclude'      => array(),
			'orderby'      => 'user_registered',
			'order'        => 'ASC',
			'offset'       => '',
			'search'       => '',
			'number'       => '',
			'count_total'  => false,
			'fields'       => 'all_with_meta',
			//'fields'     => 'all',
			'who'          => ''
		); 
		$users = get_users( $args );
		
	}
	
	/** 
	 * function unapproved_user_list
	 * Creates and returns an array for unapproved users to view in table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array WP_OBJECT WP_USERS $users array of WP Users for unapproved users list
	*/
	
	function unapproved_user_list(){
		global $wpdb, $current_user;
		$field = new FIELDS_DATABASE();
		$field->check_nua_fields();
		$sel_fields = $field->get_nua_fields();
		
		$args = array(
			'blog_id'      => $GLOBALS['blog_id'],
			'role'         => '',
			'meta_key'     => 'approval_status',
			'meta_value'   => 'pending',
			'meta_compare' => '=',
			'meta_query'   => array(),
			'include'      => array(),
			'exclude'      => array(),
			'orderby'      => 'user_registered',
			'order'        => 'ASC',
			'offset'       => '',
			'search'       => '',
			'number'       => '',
			'count_total'  => false,
			'fields'       => array( 'ID', 'user_login', 'user_email', 'user_registered' ),
			'who'          => ''
		); 
		
		$users = get_users( $args );
		
		foreach( $users as $user ){
			$user1 = get_user_by( 'login', $user->user_login );
			$meta_key = 'emails_sent';
			$verified_key = 'email_verification';
			$user->$meta_key = get_user_meta( $user1->ID, $meta_key, true );
			$user->$verified_key = get_user_meta( $user1->ID, $verified_key, true );
			if( empty( $user->$verified_key ) ){
				update_user_meta( $user1->ID, $verified_key, 'unverified' );
				$user->$verified_key = 'unverified';
			}
			foreach( $sel_fields as $object ){
				$key = $object->meta_key;
				$field = $object->field_name;
				if( $key == 'user_login' ){
					$user->$key = $user->user_login;
				}elseif( $key == 'user_email' ){
					$user->$key = $user->user_email;
				}else{
					$user->$key = get_user_meta( $user1->ID, $key, true );
				}
			}
			
		}
		return $users;
	}
	
	/** 
	 * function denied_user_list
	 * Creates and returns an array for denied users to view in table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array WP_OBJECT WP_USERS $users array of WP Users for denied users list
	*/
	
	function denied_user_list(){
		global $wpdb, $current_user;
		$args = array(
			'blog_id'      => $GLOBALS['blog_id'],
			'role'         => '',
			'meta_key'     => 'approval_status',
			'meta_value'   => 'denied',
			'meta_compare' => '=',
			'meta_query'   => array(),
			'include'      => array(),
			'exclude'      => array(),
			'orderby'      => 'login',
			'order'        => 'ASC',
			'offset'       => '',
			'search'       => '',
			'number'       => '',
			'count_total'  => false,
			'fields'       => 'all',
			'who'          => ''
		); 
		
		$users = get_users( $args );
		return $users;
	}
		
	/** 
	 * function new_user_approve_screen_options
	 * Renders the screen options records per page
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function new_user_approve_screen_options(){
	
		global $new_user_approve_page;
		$screen = get_current_screen();
		if( !is_object($screen) || $screen->id != $new_user_approve_page ){
		return;
		}
		
		$args = array(
			'label' 	=> __('Records Per Page', 'user-registration-aide'),
			'default' 	=> 10,
			'option' 	=> 'ura_nua_per_page'
		);
		
		add_screen_option( 'per_page', $args );
	}
	
	/** 
	 * function approve_user
	 * Approves New User and sends notification email and sets approval status to approved
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $user_login
	 * @returns
	*/
	
	function approve_user( $user_id ){
		global $wpdb;
		$plugin = 'buddypress/bp-loader.php';
		require_once( ABSPATH.'wp-admin/includes/user.php' );
		//wp_die( $user_id );
		if( empty( $user_id ) ){
			return;
		}else{
			$user = get_user_by( 'ID', $user_id );
		}
		$password = ( boolean ) false;
		$r_fields = get_option( 'csds_userRegAide_registrationFields' );
		if( !empty( $r_fields ) ){
			if( array_key_exists( 'user_pass', $r_fields ) ){
				$password = true;
			}
		}
		$email_verify = get_user_meta( $user_id, 'email_verification', true );
		$password_set = get_user_meta( $user_id, 'password_set', true );
		
		update_user_meta( $user->ID, 'approval_status', "approved" );
		
		if( is_plugin_active( $plugin ) ){
			$field = new FIELDS_DATABASE();
			$fields = $field->get_registration_fields();
			$bpf = new URA_BP_FUNCTIONS();
			foreach( $fields as $object ){
				$field_id = $object->bp_ID;
				$user_id = $user->ID;
				$meta_key = $object->meta_key;
				$value = get_user_meta( $user_id, $meta_key, true );
				$value = maybe_unserialize( $value );
				$results = $bpf->user_approve_update_bp_profile( $user_id, $field_id, $value, true );
			}
			if( $email_verify == 'verified' ){
				$table = $wpdb->prefix.'signups';
				$where = array(
					'user_login'	=>	$user_login
				);
				$where_format = array(
					'%s'
				);
				$wpdb->delete( $table, $where, $where_format );
			}
		}
		if( $email_verify == 'verified' ){
			if( $password == true || $password_set == 1 ){
				do_action( 'activate_new_user_account', $user->ID );
			}
		}
		
		do_action( 'new_user_approved', $user->ID );
				
	}
	
	/** 
	 * function denied_user_delete
	 * Deletes user from signups ( if needed ) and users database
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $username ( user_login )
	 * @returns
	*/
	
	function denied_user_delete( $user_id ){
		global $wpdb;
		
		require_once( ABSPATH.'wp-admin/includes/user.php' );
		
		$user = get_user_by( 'ID', $user_id );
		$table = $wpdb->prefix.'signups';
		$login = $user->user_login;
		$where = array(
			'user_login'	=>	$login
		);
		$where_format = array(
			'%s'
		);
		if( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table ) {
			wp_delete_user( $user->ID );
		}else{
			$wpdb->delete( $table, $where, $where_format );
			wp_delete_user( $user->ID );
		}
		//wp_delete_user( $user->ID );
	
	}
		
	/** 
	 * function resend_user_emai
	 * Resends Email Verification link to User that has not verified email yet
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $user_login
	 * @returns
	*/
	
	function resend_user_email( $user_id ){
		global $wpdb;
		//wp_die( 'RESEND EMAIL USER ID: '.$user_id );
		$options = get_option( 'csds_userRegAide_Options' );
		$approve = $options['new_user_approve'];
		$verify = $options['verify_email'];
		$plugin = 'buddypress/bp-loader.php';
		require_once( ABSPATH.'wp-admin/includes/user.php' );
		$user = get_user_by( 'ID', $user_id );
		$verified = get_user_meta( $user->ID, 'email_verification', true );
		$approved = get_user_meta( $user->ID, 'approval_status', true );
		if( $verify == 1 ){
			do_action( 'new_user_email', $user->ID );
		}
				
	}
		
	/** 
	 * function password_email_update
	 * Resends Password reset Email to New User 
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $user_id
	 * @returns
	*/
	
	function password_email_update( $user_id ){
		global $wpdb;
		//wp_die( 'EMAIL UPDATE: ' );
		$password = ( boolean ) false;
		$r_fields = get_option( 'csds_userRegAide_registrationFields' );
		if( array_key_exists( 'user_pass', $r_fields ) ){
			$password = true;
		}
		$options = get_option( 'csds_userRegAide_Options' );
		$approve = $options['new_user_approve'];
		$verify = $options['verify_email'];
		$plugin = 'buddypress/bp-loader.php';
		require_once( ABSPATH.'wp-admin/includes/user.php' );
		$user = get_user_by( 'ID', $user_id );
		$user_password = get_user_meta( $user->ID, 'password_set', true );
		$approved = get_user_meta( $user->ID, 'approval_status', true );
		if( $verify == 1 || $approve == 1){
			if( $user_password == 2 ){
				do_action( 'set_password_email_notice', $user );
			}
		}
	}
	
	/**
	 * function deny_user
	 * Denies new user in users database
	 * @category function
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $user_login 
	 * @returns
	*/
	
	function deny_user( $user_id ){
		require_once( ABSPATH.'wp-admin/includes/user.php' );
		update_user_meta( $user_id, 'approval_status', "denied" );
	}
	
	/**
	 * function activate_user
	 * Activates new user account in users database
	 * @category function
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $username ( user_login ) 
	 * @returns
	*/
	
	function activate_user( $user_id ){
		global $wpdb;
		require_once( ABSPATH.'wp-admin/includes/user.php' );
		
		$user = get_user_by( 'ID', $user_id );
		update_user_meta( $user->ID, 'email_verification', 'verified' );
		update_user_meta( $user->ID, 'approval_status', 'approved' );
		$table_name = $wpdb->prefix.'users';
		$update_sql = "UPDATE $table_name SET user_status = 0 WHERE ID = %d";
		$update = $wpdb->prepare( $update_sql, $user->ID );
		$results = $wpdb->query( $update );
		
		$plugin = 'buddypress/bp-loader.php';
		if( is_plugin_active( $plugin ) ){
			$table = $wpdb->prefix.'signups';
			$where = array(
				'user_login'	=>	$user_login
			);
			$where_format = array(
				'%s'
			);
			$wpdb->delete( $table, $where, $where_format );
		}
	}
	
}?>