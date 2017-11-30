<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if( !function_exists( 'wp_get_current_user' ) ) {
    include( ABSPATH . "wp-includes/pluggable.php" ); 
}

if ( !class_exists( 'URA_MEMBERS_ADMIN' ) ) {
 
 /**
 * Class URA_MEMBERS_ADMIN
 * Load Members admin area.
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_MEMBERS_ADMIN
{

	/** Directory *************************************************************/

	/**
	 * Path to the BP Members Admin directory.
	 *
	 * @var string $admin_dir
	 */
	public $admin_dir = '';

	/** URLs ******************************************************************/

	/**
	 * URL to the BP Members Admin directory.
	 *
	 * @var string $admin_url
	 */
	public $admin_url = '';

	/**
	 * URL to the BP Members Admin CSS directory.
	 *
	 * @var string $css_url
	 */
	public $css_url = '';

	/**
	 * URL to the BP Members Admin JS directory.
	 *
	 * @var string
	 */
	public $js_url = '';

	/** Other *****************************************************************/

	/**
	 * Screen id for edit user's profile page.
	 *
	 * @access public
	 * @var string
	 */
	public $user_page = '';
	
	public $capability = '';
	
	public $users_screen = '';
	
	public $do_action = '';
	/**
	 * Constructor method.
	 *
	 * @access public
	 * @since BuddyPress (2.0.0)
	 */
	public function __construct() {
		//$this->setup_globals();
		//$this->setup_actions();
	}

	/**
	 * Set admin-related globals.
	 *
	 * @access private
	 * @since BuddyPress (2.0.0)
	 */
	public function setup_globals() {
		global $current_user;
		
		// Capability depends on config
		$this->capability = 'edit_users';

		// The Edit Profile Screen id
		$this->user_page = '';

		// The Show Profile Screen id
		$this->user_profile = is_network_admin() ? 'users' : 'profile';

		// The current user id
		$this->current_user_id = get_current_user_id();

		// The user id being edited
		$this->user_id = 0;

		// Is a member editing their own profile
		$this->is_self_profile = false;

		// The screen ids to load specific css for
		$this->screen_id = array();

		// The stats metabox default position
		$this->stats_metabox = new StdClass();

		// Data specific to signups
		$this->users_page   = '';
		$this->pending_approval = 'pending-approval';
		$this->pending_deletion = 'pending-deletion';
		$this->pending_verification = 'pending-verification';
		$this->pending_password = 'pending-password';
		$this->users_url    = get_admin_url( 'users.php' );
		
	}

	/**
	 * Set admin-related actions and filters.
	 *
	 * @access private
	 * @since BuddyPress (2.0.0)
	 */
	public function setup_actions() {
		global $current_user;
		$current_user = wp_get_current_user();
		//add_filter( 'set_screen_option', array( &$this, 'filter_test' ), 10, 3 );
		//add_settings_field( 'test', 'Test', 'testing', 'pending-approval', 'default', $args = array() );
		add_action( 'admin_menu', array( $this, 'admin_menus' ), 5 );
		
		// Process changes to member type.
		//add_action( 'bp_members_admin_load', array( $this, 'process_member_type_update' ) );

		/** Signups ***********************************************************/

		if ( current_user_can( 'manage_options' ) ){

			// Filter non multisite user query to remove sign-up users
			if ( ! is_multisite() ) {
				add_action( 'pre_user_query', array( $this, 'remove_ura_signups_from_user_query' ), 10, 1 );
			}

			// Reorganise the views navigation in users.php and signups page
			//if ( current_user_can( $this->capability ) ) {
				//$nuac = new NEW_USER_APPROVE_MODEL();
				add_filter( "views_{$this->users_screen}", array( $this, 'signup_filter_view'    ), 10, 1 );
				add_filter( 'set-screen-option',           array( $this, 'signup_screen_options' ), 10, 3 );
				add_filter( 'user_row_actions', array( &$this, 'user_row_custom_actions' ), 10, 2 );
			//}
		}
		
	}
	
	/** 
	 * function user_row_custom_actions
	 * Adds custom actions to username in URA user list pages
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params array $actions, WP_User_Object $user_object
	 * @returns array $actions 
	*/
		
	function user_row_custom_actions( $actions, $user_object ){
		global $screen;
		$screen = get_current_screen();
		$verified = get_user_meta( 'email_verification', $user_object->ID );
		$userspage = 'users_page_';
		$approve_title = __( 'Approve New User Accounts', 'user-registration-aide' );
		$deny_title = __( 'Deny New User Accounts', 'user-registration-aide' );
		$email_title = __( 'Email Another Email Verification Link to New User Accounts', 'user-registration-aide' );
		$activate_title = __( 'Activate New User Accounts', 'user-registration-aide' );
		$delete_title = __( 'Delete Denied New User Accounts', 'user-registration-aide' );
		$password_title = __( 'Resend Password Reset Email Notification for New User Accounts', 'user-registration-aide' );
		//wp_die( 'CUSTOM ROW ACTIONS ' );
		
		$approval_link = add_query_arg( array( 'action' => 'approve_user', 'user' => $user_object->ID ) );
		$approval_link = remove_query_arg( array( 'new_role' ), $approval_link );
		$approval_link = wp_nonce_url( $approval_link, 'ura-nua' );
		$approval_action = '<a href="' . esc_url( $approval_link ) . '" title="'.$approve_title.'">' . __( 'Approve', 'user-registration-aide' ) . '</a>';
		
		$denied_link = add_query_arg( array( 'action' => 'deny_user', 'user' => $user_object->ID ) );
		$denied_link = remove_query_arg( array( 'new_role' ), $denied_link );
		$denied_link = wp_nonce_url( $denied_link, 'ura-nua' );
		$denied_action = '<a href="' . esc_url( $denied_link ) . '" title="'.$deny_title.'">' . __( 'Deny', 'user-registration-aide' ) . '</a>';
		
		$resend_link = add_query_arg( array( 'action' => 'resend_email', 'user' => $user_object->ID ) );
		$resend_link = remove_query_arg( array( 'new_role' ), $resend_link );
		$resend_link = wp_nonce_url( $resend_link, 'ura-nua' );
		$resend_action = '<a href="' . esc_url( $resend_link ) . '" title="'.$email_title.'">' . __( 'Resend Email', 'user-registration-aide' ) . '</a>';
		
		$delete_link = add_query_arg( array( 'action' => 'delete_user', 'user' => $user_object->ID ) );
		$delete_link = remove_query_arg( array( 'new_role' ), $delete_link );
		$delete_link = wp_nonce_url( $delete_link, 'ura-nua' );
		$delete_action = '<a href="' . esc_url( $delete_link ) . '">' . __( 'Delete User', 'user-registration-aide' ) . '</a>';
		
		$activate_link = add_query_arg( array( 'action' => 'activate_user', 'user' => $user_object->ID ) );
		$activate_link = remove_query_arg( array( 'new_role' ), $activate_link );
		$activate_link = wp_nonce_url( $activate_link, 'ura-nua' );
		$activate_action = '<a href="' . esc_url( $activate_link ) . '" title="'.$activate_title.'">' . __( 'Activate User', 'user-registration-aide' ) . '</a>';
		
		$password_link = add_query_arg( array( 'action' => 'resend_password_email', 'user' => $user_object->ID ) );
		$password_link = remove_query_arg( array( 'new_role' ), $password_link );
		$password_link = wp_nonce_url( $password_link, 'ura-nua' );
		$password_action = '<a href="' . esc_url( $password_link ) . '" title="'.$password_title.'">' . __( 'Resend Password Email', 'user-registration-aide' ) . '</a>';
		
		if( $screen->id == $userspage.'pending-approval' ){
			$actions[] = $approval_action;
			$actions[] = $denied_action;
			$actions[] = $activate_action;
			return $actions;
		}elseif( $screen->id == $userspage.'pending-deletion' ){
			$actions[] = $approval_action;
			$actions[] = $delete_action;
			$actions[] = $activate_action;
			return $actions;
		}elseif( $screen->id == $userspage.'pending-verification' ){
			$actions[] = $resend_action;
			$actions[] = $activate_action;
			return $actions;
		}elseif( $screen->id == $userspage.'pending-password' ){
			$actions[] = $password_action;
			$actions[] = $activate_action;
			return $actions;
		}else{
			return $actions;
		}
	}
	
	
	/**
	 * Get the user ID
	 *
	 * Look for $_GET['user_id']. If anything else, force the user ID to the
	 * current user's ID so they aren't left without a user to edit.
	 *
	 * @since BuddyPress (2.1.0)
	 *
	 * @return int
	 */
	private function get_user_id() {
		$user_id = get_current_user_id();

		// We'll need a user ID when not on the user admin
		if ( ! empty( $_GET['user_id'] ) ) {
			$user_id = $_GET['user_id'];
		}

		return intval( $user_id );
	}

	/** 
	 * function admin_menus
	 * Create the All Users / Profile > Edit Profile and All Users Signups submenus.
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @uses add_submenu_page() To add the Edit Profile page in Users/Profile section.
	 * @params
	 * @returns $message (registration form top message)
	*/
	
	public function admin_menus() {
		
		$edit_page         = 'user-edit';
		$profile_page      = 'profile';
		$this->users_page  = 'users';

		// Self profile check is needed for this pages
		$page_head = array(
			$edit_page        . '.php',
			$profile_page     . '.php',
			$this->user_page,
			$this->users_page . '.php',
		);

		// Setup the screen ID's
		$this->screen_id = array(
			$edit_page,
			$this->user_page,
			$profile_page
		);
		
	}
	
	/** Signups Management ****************************************************/

	/** 
	 * function signup_screen_options
	 * Display the admin preferences about signups pagination.
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @uses add_submenu_page() To add the Edit Profile page in Users/Profile section.
	 * @params int $value, string $option, int $new_value
	 * @returns int the pagination preferences
	*/
	
	public function signup_screen_options( $value = 0, $option = '', $new_value = 0 ) {
		if ( 'users_page_ura_signups_network_per_page' != $option && 'lp_tableview_records_per_page' != $option ) {
			return $value;
		}

		// Per page
		$new_value = (int) $new_value;
		if ( $new_value < 1 || $new_value > 999 ) {
			return $value;
		}

		return $new_value;
	}

	/** 
	 * function remove_ura_signups_from_user_query
	 * Make sure no signups will show in users list.
	 * This is needed to handle signups that may have not been activated
	 * before the 1.5.2.0 upgrade.
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params WP_User_Query $query The users query.
	 * @returns WP_User_Query The users query without the signups.
	*/
	
	public function remove_ura_signups_from_user_query( $query = null ) {
		global $wpdb;

		// Bail if this is an ajax request
		if ( defined( 'DOING_AJAX' ) ) {
			return;
		}


		// Bail if there is no current admin screen
		if ( ! function_exists( 'get_current_screen' ) || ! get_current_screen() ) {
			return;
		}

		// Get current screen
		$current_screen = get_current_screen();
		
		//exit( print_r( $current_screen ) );
		// Bail if not on a users page
		if ( ! isset( $current_screen->id ) || $this->users_page !== $current_screen->id ) {
			return;
		}

		// Bail if already querying by an existing role
		if ( ! empty( $query->query_vars['role'] ) ) {
			if( $query->query_vars['role'] != 'subscriber' ){
				return;
			}
		}
		
		$query->query_where .= " AND {$wpdb->users}.user_status != 2";
		return $query;
	}

	/** 
	 * function get_list_table_class
	 * Load the Signup WP Users List table.
	 * @since 1.5.2.0
	 * @updated 1.5.3.0
	 * @access public
	 * @param  string $class    The name of the class to use.
	 * @param  string $required The parent class.
	 * @return WP_List_Table    The List table.
	*/
	
	public static function get_list_table_class( $class = '', $required = '' ) {
		if ( empty( $class ) ) {
			return;
		}

		if ( ! empty( $required ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-' . $required . '-list-table.php' );
			require_once( MEMBERS_PATH."ura-pending-approval.php"   );
		}

		return new $class();
	}
	
	/** 
	 * function users_admin_load
	 * Set up the signups admin page.
	 * Loaded before the page is rendered, this function does all initial
	 * setup, including: processing form requests, registering contextual
	 * help, and setting up screen options.
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @param
	 * @return 
	 * @global $bp_members_signup_list_table
	*/
	
	public function users_admin_load() {
		global $plugin_page, $ura_pending_approval_list_page, $parent_page, $title;
		$ura_pending_approval_list_page = new URA_USERS_LIST_TABLE_NUA();
		$doaction = $ura_pending_approval_list_page->get_bulk_actions();
		$title = __( 'Users', 'user-registration-aide' ) ;
		$parent_file = 'users.php';
		// Build redirection URL
		$redirect_to = remove_query_arg( array( 'action', 'error', 'updated', 'activated', 'notactivated', 'deleted', 'notdeleted', 'resent', 'notresent', 'do_delete', 'do_resend', 'do_activate', '_wpnonce', 'signup_ids', 'mailto', 'email', 'resend_email' ), $_SERVER['REQUEST_URI'] );
		
		/**
		 * Fires at the start of the signups admin load.
		 *
		 * @since 1.5.3.0
		 *
		 * @param string $doaction Current bulk action being processed.
		 * @param array  $_REQUEST Current $_REQUEST global.
		 */
		do_action( 'ura_signups_admin_load', $doaction, $_REQUEST );

		/**
		 * Filters the allowed actions for use in the user signups admin page.
		 *
		 * @since 1.5.3.0
		 *
		 * @param array $value Array of allowed actions to use.
		 */
		$allowed_actions =  array( 'approve_user', 'deny_user', 'activate_user', 'resend_email', 'resend_password_email', 'delete_user' );

		// Prepare the display of the Community Profile screen
		if ( ! in_array( $doaction, $allowed_actions ) || ( -1 == $doaction ) ) {

			
			// per_page screen option
			add_screen_option( 'per_page', array( 'label' => _x( 'Pending Accounts', 'Pending Accounts per page (screen options)', 'user-registration-aide' ) ) );
		
			$screen = get_current_screen();
			
		} else {
			if ( ! empty( $_REQUEST['approval_ids' ] ) ) {
				$signups = wp_parse_id_list( $_REQUEST['approval_ids' ] );
			}
		}
	}
	
	/** 
	 * function complete_link_action
	 * processes the bulk actions for the user list table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns 
	*/
	
	function actions_array_list(){
		$actions = array(
			'activate_user'				=>	'Activate User',
			'approve_user'				=>	'Approve User',
			'delete_user'				=>	'Delete User',
			'deny_user'					=>	'Deny User',
			'resend_email'				=>	'Resend Email Verification Email',
			'resend_password_email'		=>	'Resend Password Reset Email',
		);
		return $actions;
	}
	/** 
	 * function complete_link_action
	 * processes the bulk actions for the user list table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns 
	*/
   
    function complete_link_action() {
        $action = ( string ) '';
		if( isset( $_GET['action'] ) ){
			$action = $_GET['action'];
		}elseif( isset( $_POST['action'] ) && $_POST['action'] != -1 ){
			$action = $_POST['action'];
		}elseif( isset( $_POST['action2'] ) && $_POST['action2'] != -1 ){
			wp_die( 'ACTION 2' );
			$action = $_POST['action2'];
		}
		if( isset( $_GET['user'] ) ){
			$get_user_id = $_GET['user'];
		}elseif( isset( $_GET['approval_ids'] ) ){
			$ids = $_GET['approval_ids'];
		}
		
		if( !empty( $action ) ){
			$nuac = new NEW_USER_APPROVE_MODEL();
			//Detect when a bulk action is being triggered...
			if( $action == 'do_approve_user' ) {
				if( !empty( $_POST['users'] ) ){
					//wp_die( print_r( $_POST['users'] ) );
					if( is_array( $_POST['users'] ) ){
						foreach( $_POST['users'] as $index => $approve ){
							$user_id = $approve;
							$nuac->approve_user( $user_id );
						}
					}elseif( !is_array( $_POST['users'] ) ){
						$user_id = $_POST['users'];
						$nuac->approve_user( $user_id );
					}
				}elseif( !empty( $get_user_id ) ){
					$nuac->approve_user( $get_user_id );
				}elseif( !empty( $ids ) ){
					if( is_array( $ids ) ){
						foreach( $ids as $index => $approve ){
							$user_id = $approve;
							$nuac->approve_user( $user_id );
						}
					}elseif( !is_array( $ids ) ){
						$user_id = $ids;
						$nuac->approve_user( $user_id );
					}
				}
			}elseif( $action == 'do_deny_user' ) {
				$denied = ( int ) 0;
				if( !empty( $_POST['users'] ) ){
					if( is_array( $_POST['users'] ) ){
						foreach( $_POST['users'] as $index => $approve ){
							$user_id = $approve;
							$nuac->deny_user( $user_id );
							$denied++;
						}
					}elseif( !is_array( $_POST['users'] ) ){
						$user_id = $_POST['users'];
						$nuac->deny_user( $user_id );
						$denied++;
					}
				}elseif( !empty( $get_user_id ) ){
					$nuac->deny_user( $get_user_id );
					$denied++;
				}elseif( !empty( $ids ) ){
					if( is_array( $ids ) ){
						foreach( $ids as $index => $approve ){
							$user_id = $approve;
							$nuac->deny_user( $user_id );
							$denied++;
						}
					}elseif( !is_array( $ids ) ){
						$user_id = $ids;
						$nuac->deny_user( $user_id );
						$denied++;
					}
				}
			}elseif( $action == 'do_resend_email' || $action == 'resend_email' ) {
				if( isset( $_GET['approval_ids'] ) ){
					//wp_die( $ids );
				}
				if( !empty( $_POST['users'] ) ){
					if( is_array( $_POST['users'] ) ){
						foreach( $_POST['users'] as $index => $approve ){
							$user_id = $approve;
							$nuac->resend_user_email( $user_id );
						}
					}elseif( !is_array( $_POST['users'] ) ){
						$user_id = $_POST['users'];
						$nuac->resend_user_email( $user_id );
					}
				}elseif( !empty( $get_user_id ) ){
					$nuac->resend_user_email( $get_user_id );
				}elseif( !empty( $ids ) ){
					if( is_array( $ids ) ){
						foreach( $ids as $index => $approve ){
							$user_id = $approve;
							$nuac->resend_user_email( $user_id );
						}
					}elseif( !is_array( $ids ) ){
						$user_id = $ids;
						$nuac->resend_user_email( $user_id );
					}
				}
			}elseif( $action == 'do_activate_user' ) {
				if( isset( $_POST['users'] ) ){
					if( is_array( $_POST['users'] ) ){
						foreach( $_POST['users'] as $index => $activate ){
							$user_id = $activate;
							$nuac->activate_user( $user_id );
						}
					}elseif( !is_array( $_POST['users'] ) ){
						$user_id = $_POST['users'];
						$nuac->activate_user( $user_id );
					}
				}elseif( !empty( $get_user_id ) ){
					$nuac->activate_user( $get_user_id );
				}elseif( !empty( $ids ) ){
					if( is_array( $ids ) ){
						foreach( $ids as $index => $approve ){
							$user_id = $approve;
							$nuac->activate_user( $user_id );
						}
					}elseif( !is_array( $ids ) ){
						$user_id = $ids;
						$nuac->activate_user( $user_id );
					}
				}
			}elseif( $action == 'do_delete_user' ) {
				if( isset( $_POST['users'] ) ){
					if( is_array( $_POST['users'] ) ){
						foreach( $_POST['users'] as $index => $delete ){
							$user_id = $delete;
							$nuac->denied_user_delete( $user_id );
						}
					}elseif( !is_array( $_POST['users'] ) ){
						$user_id = $_POST['users'];
						$nuac->denied_user_delete( $user_id );
					}
				}elseif( !empty( $get_user_id ) ){
					$nuac->denied_user_delete( $get_user_id );
				}elseif( !empty( $ids ) ){
					if( is_array( $ids ) ){
						foreach( $ids as $index => $approve ){
							$user_id = $approve;
							$nuac->denied_user_delete( $user_id );
						}
					}elseif( !is_array( $ids ) ){
						$user_id = $ids;
						$nuac->denied_user_delete( $user_id );
					}
				}
			}elseif( $action == 'do_resend_password_email' ) {
				if( isset( $_POST['users'] ) ){
					if( is_array( $_POST['users'] ) ){
						foreach( $_POST['users'] as $index => $password ){
							$user_id = $password;
							$nuac->password_email_update( $user_id );
						}
					}elseif( !is_array( $_POST['users'] ) ){
						$user_id = $_POST['users'];
						$nuac->password_email_update( $user_id );
					}
				}elseif( !empty( $get_user_id ) ){
					$nuac->password_email_update( $get_user_id );
				}elseif( !empty( $ids ) ){
					if( is_array( $ids ) ){
						foreach( $ids as $index => $approve ){
							$user_id = $approve;
							$nuac->password_email_update( $user_id );
						}
					}elseif( !is_array( $ids ) ){
						$user_id = $ids;
						$nuac->password_email_update( $user_id );
					}
				}
			}
		}
		
    }
	

	/** 
	 * function signups_admin_manage
	 * This is the confirmation screen for actions.
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @param string $action Delete, activate, or resend activation link.
	 * @return
	*/
	
	public function signups_admin_manage( $action = '', $screen_id ) {
		global $plugin_page, $ura_pending_approval_list_page, $screen, $doaction;
		
		$userspage = 'users_page_';
		$count = ( int ) 0;
		if( isset( $_GET['action'] ) ){
			$action = $_GET['action'];
		}elseif( isset( $_POST['action'] ) && $_POST['action'] != -1 ){
			$action = $_POST['action'];
		}elseif( isset( $_POST['action2'] ) && $_POST['action2'] != -1 ){
			$action = $_POST['action2'];
		}
		
		$actions_done = ( boolean ) false;
		$header_text = ( string ) 'No Actions Found!';
		$helper_text = ( string ) '';
		
		// Get the user IDs from the URL
		$ids = false;
		if ( ! empty( $_POST['users'] ) ) {
			$ids = wp_parse_id_list( $_POST['users'] );
		} elseif ( ! empty( $_GET['signup_id'] ) ) {
			$ids = absint( $_GET['signup_id'] );
		} elseif ( !empty( $_GET['user'] ) ){
			$ids = wp_parse_id_list( $_GET['user'] );
		} elseif ( isset( $_GET['approval_ids'] ) ){
			$ids = $_GET['approval_ids'];
		} elseif ( isset( $_GET['cancel_ids'] ) ){
			$ids = $_GET['cancel_ids'];
		}

		if( !empty( $ids ) ){
			$count = count( $ids );
		}else{
			$count = 0;
		}
		
		// Query for signups, and filter out those IDs that don't
		// correspond to an actual signup
				
		$signups = $this->signups_ids();
		
		if( isset( $_GET['page'] ) ){
			$page = $_GET['page'];
		}
		
		if( empty( $page ) && !empty( $screen_id ) ){
			$pages = explode( '_', $screen_id );
			$page = $pages[2];
		}
		
		//setup header text for message user
		if( !empty( $page ) ){
			if( !empty( $action ) ){
				switch ( $action ) {
					case 'activate_user' :
						$header_text = __( 'Activate Pending Accounts', 'user-registration-aide' );
						$helper_text = __( 'You are about to activate the following account:', 'user-registration-aide' );
						$helper_texts = sprintf( __( 'You are about to activate the following %d accounts:', 'user-registration-aide' ), $count );
						$url_args = array( 'page' => $page );
						break;

					case 'approve_user' :
						$header_text = __( 'Approve Pending Accounts', 'user-registration-aide' );
						$helper_text = __( 'You are about to approve the following account:', 'user-registration-aide' );
						$helper_texts = sprintf( __( 'You are about to approve the following %d accounts:', 'user-registration-aide' ), $count );
						$url_args = array( 'page' => $page );
						break;
						
					case 'cancel_action' :
						$actions_array = $this->actions_array_list();
						if( isset( $_GET['action_cancelled'] ) ){
							$cancelled_action = $_GET['action_cancelled'];
							if( array_key_exists( $cancelled_action, $actions_array ) ){
								$action_cancelled = $actions_array[$cancelled_action];
							}
						}
						$header_text = sprintf( __( 'Cancel Current Action %s', 'user-registration-aide' ), $action_cancelled );
						$helper_text = sprintf( __( 'You are about to cancel the following action %s for the following account:', 'user-registration-aide' ), $action_cancelled );
						$helper_texts = sprintf( __( 'You are about to cancel the following action %s on the following %d accounts:', 'user-registration-aide' ), $action_cancelled, $count );
						$url_args = array( 'page' => $page );
						break;
						
					case 'delete_user' :
						$header_text = __( 'Delete Account', 'user-registration-aide' );
						$helper_text = __( 'You are about to delete the following account:', 'user-registration-aide' );
						$helper_texts = sprintf( __( 'You are about to delete the following %d accounts:','user-registration-aide' ), $count );
						$url_args = array( 'page' => $page );
						break;
					
					case 'deny_user' :
						$header_text = __( 'Deny Pending Accounts', 'user-registration-aide' );
						$helper_text = __( 'You are about to deny the following account:', 'user-registration-aide' );
						$helper_texts = sprintf( __( 'You are about to deny the following %d accounts:', 'user-registration-aide' ), $count );
						$url_args = array( 'page' => $page );
						break;

					case 'resend_email' :
						$header_text = __( 'Resend Email Verification Emails', 'user-registration-aide' );
						$helper_text = __( 'You are about to resend an email verification email to the following account:', 'user-registration-aide' );
						$helper_texts = sprintf( __( 'You are about to resend email verification emails to the following %d accounts:', 'user-registration-aide' ), $count );
						$url_args = array( 'page' => $page );
						break;
					
					case 'resend_password_email' :
						$header_text = __( 'Resend Password Emails', 'user-registration-aide' );
						$helper_text = __( 'You are about to resend a password activation email to the following account:', 'user-registration-aide' );
						$helper_texts = sprintf( __( 'You are about to resend password activation emails to the following %d accounts:', 'user-registration-aide' ), $count );
						$url_args = array( 'page' => $page );
						break;
					
					case 'do_activate_user' :
						$this->complete_link_action();
						$header_text = __( 'Activated Pending Accounts', 'user-registration-aide' );
						$helper_text = __( 'You activated the following account:', 'user-registration-aide' );
						$helper_texts = sprintf( __( 'You activated the following %d accounts:',  'user-registration-aide' ), $count );
						$url_args = array( 'page' => $page );
						break;
						
					case 'do_approve_user' :
						$this->complete_link_action();
						$header_text = __( 'Approved Pending Accounts', 'user-registration-aide' );
						$helper_text = __( 'You approved the following account:', 'user-registration-aide' );
						$helper_texts = sprintf( __( 'You approved the following %d accounts:',  'user-registration-aide' ), $count );
						$actions_done = true;
						$url_args = array( 'page' => $page );
						break;
						
					case 'do_delete_user' :
						$this->complete_link_action();
						$header_text = __( 'Deleted Accounts', 'user-registration-aide' );
						$helper_text = __( 'Account Deleted:', 'user-registration-aide' );
						$helper_texts = sprintf( __( '%d Accounts Deleted:', 'user-registration-aide' ), $count );
						$actions_done = true;
						$url_args = array( 'page' => $page );
						break;

					case 'do_deny_user' :
						$this->complete_link_action();
						$header_text = __( 'Denied Pending Accounts', 'user-registration-aide' );
						$helper_text = __( 'You denied the following account:', 'user-registration-aide' );
						$helper_texts = sprintf( __( 'You denied the following %d accounts:', 'user-registration-aide' ), $count );
						$actions_done = true;
						$url_args = array( 'page' => $page );
						break;

					case 'do_resend_email' :
						$this->complete_link_action();
						$header_text = __( 'Resent Email Verification Emails', 'user-registration-aide' );
						$helper_text = __( 'You resent an email verification email to the following account:', 'user-registration-aide' );
						$helper_texts = sprintf( __( 'You resent email verification emails to the following %d accounts:', 'user-registration-aide' ), $count );
						$actions_done = true;
						$url_args = array( 'page' => $page );
						break;
										
					case 'do_resend_password_email' :
						$this->complete_link_action();
						$header_text = __( 'Resent Password Emails', 'user-registration-aide' );
						$helper_text = __( 'A password activation email was sent to the following account:', 'user-registration-aide' );
						$helper_texts = sprintf( __( 'Password activation emails were sent to the following %d accounts:', 'user-registration-aide' ), $count );
						$actions_done = true;
						$url_args = array( 'page' => $page );
						break;
						
				}
			}else{
				$header_text = __( 'No Actions Found!', 'user-registration-aide' );
				$url_args = array( 'page' => $page );
			}
		}else{
			$header_text = __( 'Can\'t Find Page!', 'user-registration-aide' );
			$url_args = array( 'page' => 'pending-approval' );
		}

		// These arguments are added to all URLs
		if( empty( $url_args ) && !empty( $screen_id ) ){
			if( $screen_id == $userspage.'pending-approval' ){
				$url_args = array( 'page' => 'pending-approval' );
			}elseif( $screen_id == $userspage.'pending-deletion' ){
				$url_args = array( 'page' => 'pending-deletion' );
			}elseif( $screen_id == $userspage.'pending-verification' ){
				$url_args = array( 'page' => 'pending-verification' );
			}elseif( $screen_id == $userspage.'pending-password' ){
				$url_args = array( 'page' => 'pending-password' );
			}
		}
		
		$cancel_action_args = array(
			'action'     		=>  'cancel_action',
			'action_cancelled' 	=> 	$action,
			'cancel_ids'		=>	$ids
		);
	
		// These arguments are only added when performing an action
		$action_args = array(
			'action'     	=> 'do_' . $action,
			//'approval_ids' => implode( ',', $approval_ids )
			//'approval_ids' => implode( ',', $signup_ids )
			'approval_ids' 	=> 	$ids
		);

		$cancel_url = wp_nonce_url(
			add_query_arg(
				array_merge( $url_args, $cancel_action_args ),
				get_admin_url().'users.php'
			),
			'approvals_' . $action
		);
		$cancelled_url = add_query_arg( $url_args, get_admin_url().'users.php' );
		$action_url = wp_nonce_url(
			add_query_arg(
				array_merge( $url_args, $action_args ),
				get_admin_url().'users.php'
			),
			'approvals_' . $action
		);
		if( $action != 'do_cancel_action' ){//!$action == 'do_cancel_action' ){
			?>

			<div class="wrap">
				<?php screen_icon( 'users' ); ?>
				<h2><?php echo esc_html( $header_text ); ?></h2>
				<p>
				<?php 
				if( $count <= 1 ){
					echo esc_html( $helper_text );
				}else{
					echo esc_html( $helper_texts );
				}
				?></p>

				<ol class="bp-signups-list">
				<?php 
				if( !empty( $ids ) ){
					foreach ( $ids as $id ) {
						$user = get_user_by( 'ID', $id );
						if( $user ){ ?>

							<li>
							<?php echo esc_html( $user->user_login ) ?>

							<?php 
							if ( 'resend' == $action ) {
								/*
								<p class="description">
									<?php printf( esc_html__( 'Last notified: %s', 'user-registration-aide' ), $last_notified ) ;?>

									<?php if ( ! empty( $signup->recently_sent ) ) : ?>

										<span class="attention wp-ui-text-notification"> <?php esc_html_e( '(less than 24 hours ago)', 'user-registration-aide' ); ?></span>

									<?php endif; ?>
								</p>
								*/
							} 
							?>
							</li>
							<?php
						}
						
					}
				}else{
					_e( 'No Users Selected!', 'user-registration-aide' );
				}
				?>
				</ol>

				<?php 
				if ( $action == 'delete_user' ) { ?>

					<p><strong><?php esc_html_e( 'This action cannot be undone.', 'user-registration-aide' ) ?></strong></p>

				<?php 
				}
				if( !empty( $ids ) ){
					if( $actions_done == false ){ 
						if( $action != 'cancel_action' ){
							?>
							<a class="button-primary" href="<?php echo esc_url( $action_url ); ?>"><?php esc_html_e( 'Commit Action', 'user-registration-aide' ); ?></a>
						
							<a class="button" href="<?php echo esc_url( $cancel_url ); ?>"><?php esc_html_e( 'Cancel Action', 'user-registration-aide' ) ?></a>
							<?php
						}else{
							?>
							<a class="button-primary" href="<?php echo esc_url( $action_url ); ?>"><?php esc_html_e( 'Commit Action Cancellation ', 'user-registration-aide' ); ?></a>
						
							<a class="button" href="<?php echo esc_url( $cancelled_url ); ?>"><?php esc_html_e( 'Commit Cancel Action Cancellation', 'user-registration-aide' ) ?></a>
							<?php
						}
					}
				}
				
			}else{
				?>
				<div class="wrap">
				<?php screen_icon( 'users' ); ?>
				<h2><?php _e( 'Action Cancelled!', 'user-registration-aide' ); ?></h2>
				<?php
			}
			?>
		</div>

		<?php
	}
	
	/**
	 * Update the user status if the approve or deny link was clicked.
	 *
	 * @uses load-users.php
	 */
	public function update_action() {
		//wp_die( 'NUA UPDATE ACTION' );
		if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'approve', 'deny', 'resend_email', 'resend_password_email' ) ) && !isset( $_GET['new_role'] ) ) {
			check_admin_referer( 'user-registration-aide' );

			$sendback = remove_query_arg( array( 'approved', 'denied', 'deleted', 'ids', 'new_role' ), wp_get_referer() );
			if ( !$sendback )
				$sendback = admin_url( 'users.php' );

			$wp_list_table = _get_list_table( 'WP_Users_List_Table' );
			$pagenum = $wp_list_table->get_pagenum();
			$sendback = add_query_arg( 'paged', $pagenum, $sendback );

			$status = sanitize_key( $_GET['action'] );
			$user = absint( $_GET['user'] );

			//pw_new_user_approve()->update_user_status( $user, $status );

			if ( $_GET['action'] == 'approve_user' ) {
				$sendback = add_query_arg( array( 'pending-approval' => 1, 'ids' => $user ), $sendback );
			} elseif ( $_GET['action'] == 'deny_user' ) {
				$sendback = add_query_arg( array( 'pending-deletion' => 1, 'ids' => $user ), $sendback );
			} elseif ( $_GET['action'] == 'resend_email' ) {
				$sendback = add_query_arg( array( 'pending-verification' => 1, 'ids' => $user ), $sendback );
			} elseif ( $_GET['action'] == 'resend_password_email' ) {
				$sendback = add_query_arg( array( 'pending-password' => 1, 'ids' => $user ), $sendback );
			}

			wp_redirect( $sendback );
			exit;
		}
	}
	
	/** 
	 * function signups_ids
	 * Gets all users that are not approved yet
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @param 
	 * @return OBJECT $ids Array of Users from users table that are not yet active
	*/
	
	function signups_ids(){
		global $wpdb;
		$table_name = $wpdb->prefix . "users";
		$status = 2;
		$sql = "SELECT * FROM $table_name WHERE user_status = %d";
		$select = $wpdb->prepare( $sql, $status );
		$ids = $wpdb->get_results( $select, OBJECT );
		return $ids;
	}
	
	/** 
	 * function add_panel
	 * Add our panel to the "Screen Options" box
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @param 
	 * @return
	*/
	
	function add_panel(){
		$args = array(
			array( &$ura_admin, 'ura_users-default-settings' ),       //Panel ID
			'URA Users Defaults',              //Panel title. 
			array( &$ura_admin, 'ura_users_default_settings_panel' ), //The function that generates panel contents.
			array('users_page_pending-approval', 'users_page_pending-deletion', 'users_page_pending-verification'), //Pages/screens where the panel is displayed. 
			array( &$ura_admin, 'ura_users_save_new_defaults' ),      //The function that gets triggered when settings are submitted/saved.
			true                              //Auto-submit settings (via AJAX) when they change. 
		);
	 
	}
	
	/** 
	 * function ura_users_default_settings_panel
	 * Generate the "Raw HTML defaults" panel for Screen Options.
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @param 
	 * @return string $output
	*/
	
	function ura_users_default_settings_panel(){
		$defaults = ura_users_screen_options_default_settings();
		
		//Output checkboxes 
		$fields = array(
			'disable_wptexturize' 		=> 'Disable wptexturize',
			'disable_wpautop' 			=> 'Disable automatic paragraphs',
			'disable_convert_chars' 	=> 'Disable convert_chars',
			'disable_convert_smilies' 	=> 'Disable smilies',
		 );
		  
		$output = '';
		foreach( $fields as $field => $legend ){
			$esc_field = esc_attr( $field );
			$output .= sprintf(
				'<label for="ura_users_default-%s" style="line-height: 20px;">
					<input type="checkbox" name="ura_users_default-%s" id="ura_users_default-%s"%s>
					%s
				</label><br>',
				$esc_field,
				$esc_field,
				$esc_field,
				( $defaults[$field]?' checked="checked"':'' ),
				$legend
			);
		}
		 
		return $output;
		
	}
	
	/** 
	 * function ura_users_save_new_default
	 * Process the "Raw HTML defaults" form fields and save new settings
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @param array $params
	 * @return void
	*/
		
	function ura_users_save_new_defaults( $params ){
		//Get current defaults
		$defaults = ura_users_get_default_settings();
		 
		//Read new values from the submitted form
		foreach($defaults as $field => $old_value){
			if ( isset( $params['ura_users_default-'.$field] ) && ( $params['ura_users_default-'.$field] == 'on' ) ){
				$defaults[$field] = true;
			} else {
				$defaults[$field] = false;
			}
		}
		 
		//Store the new defaults
		ura_users_set_default_settings( $defaults );
	}
}
} // class_exists check

// Load the BP Members admin
//add_action( 'bp_init', array( 'BP_Members_Admin', 'register_members_admin' ) );
