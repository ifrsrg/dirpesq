<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'WP_Users_List_Table' ) ){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php' );// CHECK!!! throwing errors in 1.5.3.0 
}
require_once(ABSPATH . 'wp-admin/includes/template.php' );

/**
 * Class URA_USERS_LIST_TABLE_NUA
 * User Registration Aide - Class for adding new user screens to the users list table
 * Do not access this class directly. Instead, use the new_user_pending_approval_wp_list_view() function. 
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.8
 * @access public
 * @extends WP_Users_List_Table
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_USERS_LIST_TABLE_NUA extends WP_Users_List_Table
{

	public $screen;
	public $_args;
	public $total_count;
	public $do_action;
	public $action_result;
	public $views;
	
	/**
	 * Site ID to generate the Users list table for.
	 *
	 * @since 1.5.3.0
	 * @access public
	 * @var int
	*/
	public $site_id;

	/**
	 * Whether or not the current Users list table is for Multisite.
	 *
	 * @since 1.5.3.0
	 * @access public
	 * @var bool
	*/
	public $is_site_users;
	
	/** 
	 * function __construct
	 * @see WP_List_Table::__construct() for more information on default arguments.
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params array $args An associative array of arguments.
	 * @returns
	*/ 
	
	public function __construct( $args = array() ) {
				
		$page = ( string ) '';
		if( isset( $_GET['page'] ) ){
			$page = $_GET['page'];
		}
		
		if( $page == 'pending-approval' ){
			$args['screen'] = 'pending-approval';
			parent::__construct( array(
				'singular' => 'approve user',
				'plural'   => 'approve users',
				'screen'   => isset( $args['screen'] ) ? $args['screen'] : 'pending-approval',
			) );
		}elseif( $page = 'pending-deletion' ){
			$args['screen'] = 'pending-deletion';
			parent::__construct( array(
				'singular' => 'delete user',
				'plural'   => 'delete users',
				'screen'   => isset( $args['screen'] ) ? $args['screen'] : 'pending-deletion',
			) );
		}elseif( $page = 'pending-verification' ){
			$args['screen'] = 'pending-verification';
			parent::__construct( array(
				'singular' => 'activate user',
				'plural'   => 'activate users',
				'screen'   => isset( $args['screen'] ) ? $args['screen'] : 'pending-verification',
			) );
		}elseif( $page = 'pending-password' ){
			$args['screen'] = 'pending-password';
			parent::__construct( array(
				'singular' => 'resend password email',
				'plural'   => 'resend password emails',
				'screen'   => isset( $args['screen'] ) ? $args['screen'] : 'pending-password',
			) );
		}
		
		if ( $this->is_site_users ){
			$this->site_id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0;
		}
		
	}
	
	/** 
	 * function prepare_items
	 * Prepares screen for new user approveal user list
	 * @since 1.5.3.0
	 * @updated 1.5.3.8
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function prepare_items() {
        global $role, $usersearch, $screen;
		$screen = get_current_screen();
		$screen_id = $screen->id;
		$action = ( string ) '';
		/*
		if( isset( $_GET['action'] ) ){
			$this->process_bulk_action();
		}elseif( isset( $_POST['action'] ) ){
			$this->process_bulk_action();
		}elseif( isset( $_POST['action2'] ) ){
			$this->process_bulk_action();
		}	
		*/
		if( isset( $_GET['action'] ) ){
			$action = $_GET['action'];
			do_action( 'user_actions', $action, $screen_id );
		}elseif( isset( $_POST['action'] ) ){
			$action = $_POST['action'];
			//wp_die( 'POST ACTION: '.$action );
			do_action( 'user_actions', $action, $screen_id  );
		}elseif( isset( $_POST['action2'] ) ){
			$action = $_POST['action2'];
			//wp_die( 'POST ACTION 2: '.$action );
			do_action( 'user_actions', $action, $screen_id  );
		}	
		
		if( isset( $_POST['action_confirm'] ) ){
			//wp_die( 'POST_CONFIRM' );
		}
	
		$userspage = 'users_page_';
		$usersearch = isset( $_REQUEST['s'] ) ? wp_unslash( trim( $_REQUEST['s'] ) ) : '';
		$args = array();
		$role = isset( $_REQUEST['role'] ) ? $_REQUEST['role'] : '';

		$per_page = ( $this->is_site_users ) ? 'site_users_network_per_page' : 'users_per_page';
		$users_per_page = $this->get_items_per_page( $per_page );

		$paged = $this->get_pagenum();
		
		
		$args = array(
			'number'		=> $users_per_page,
			'offset' 		=> ( $paged-1 ) * $users_per_page,
			'role' 			=> $role,
			'meta_key'     	=> 'approval_status',
			'meta_value'   	=> 'pending',
			'meta_compare' 	=> '=',
			'orderby'		=> 'registered',
			'order'			=> 'DESC',
			'search' 		=> $usersearch,
			'fields' 		=> 'all_with_meta'
		);
		
		if( $screen->id == $userspage.'pending-approval' ){
			$args = array(
				'number'		=> $users_per_page,
				'offset' 		=> ( $paged-1 ) * $users_per_page,
				'role' 			=> $role,
				'meta_key'     	=> 'approval_status',
				'meta_value'   	=> 'pending',
				'meta_compare' 	=> '=',
				'orderby'		=> 'registered',
				'order'			=> 'DESC',
				'search' 		=> $usersearch,
				'fields' 		=> 'all_with_meta'
			);
		}elseif( $screen->id == $userspage.'pending-deletion' ){
			$args = array(
				'number'		=> $users_per_page,
				'offset' 		=> ( $paged-1 ) * $users_per_page,
				'role' 			=> $role,
				'meta_key'     	=> 'approval_status',
				'meta_value'   	=> 'denied',
				'meta_compare'	=> '=',
				'orderby'		=> 'registered',
				'order'			=> 'DESC',
				'search' 		=> $usersearch,
				'fields' 		=> 'all_with_meta'
			);
		}elseif( $screen->id == $userspage.'pending-verification' ){
			$args = array(
				'number'		=> $users_per_page,
				'offset' 		=> ( $paged-1 ) * $users_per_page,
				'role' 			=> $role,
				'meta_key'     	=> 'approval_status',
				'meta_value'   	=> 'approved',
				'meta_compare'	=> '=',
				'meta_key'		=> 'email_verification',
				'meta_value'   	=> 'unverified',
				'meta_compare'	=> '=',
				'orderby'		=> 'registered',
				'order'			=> 'DESC',
				'search' 		=> $usersearch,
				'fields' 		=> 'all_with_meta'
			);
		}elseif( $screen->id == $userspage.'pending-password' ){
			$args = array(
				'number'		=> $users_per_page,
				'offset' 		=> ( $paged-1 ) * $users_per_page,
				'role' 			=> $role,
				'meta_query'	=>	array(	
					'relation'		=>	'AND',
						array(
							'key'     	=> 'approval_status',
							'value'   	=> 'approved',
							'compare'	=> '='
						), 
						array(
							'key'		=> 'email_verification',
							'value'   	=> 'verified',
							'compare'	=> '='
						),
						array(
							'key'     	=> 'password_set',
							'value'   	=> '2',
							'compare'	=> '='
						),
				),
				'orderby'		=> 'registered',
				'order'			=> 'DESC',
				'search' 		=> $usersearch,
				'fields' 		=> 'all_with_meta'
			);
		}
		//wp_die( print_r( $args ) );
		if ( '' !== $args['search'] ){
			$args['search'] = '*' . $args['search'] . '*';
		}
		
		if ( $this->is_site_users ){
			$args['blog_id'] = $this->site_id;
		}
		
		if ( isset( $_REQUEST['orderby'] ) ){
			$args['orderby'] = $_REQUEST['orderby'];
		}
		
		if ( isset( $_REQUEST['order'] ) ){
			$args['order'] = $_REQUEST['order'];
		}
		// Query the user IDs for this page
		
		$wp_user_search = new WP_User_Query( $args );

		$this->items = $wp_user_search->get_results();
		
		$this->set_pagination_args( array(
			'total_items'	=> $wp_user_search->get_total(),
			'per_page'		=> $users_per_page,
		) );
		
		if( isset( $this->action_result ) ){
			echo $this->action_result;
		}
		
    }
	
	/** 
	 * function extra_tablenav
	 * 
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params $which
	 * @returns 
	*/
	
	public function extra_tablenav( $which ) {
		return;
	}
	
	/** 
	 * function get_views
	 * Return an associative array listing all the views that can be used
	 * with this table.
	 *
	 * Provides a list of roles and user count for that role for easy
	 * filtering of the user table.
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array An array of HTML links, one for each view including current count of users in each role.
	*/
	
	public function get_views() {
		$views = parent::get_views();
		$views = apply_filters( 'user_table_views', $views );
		return $views;		
	}
	
	/** 
	 * function column_default
	 * sets the column defaults for the user list table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params OBJECT $item, string $column_name
	 * @returns OBJECT $item->$column_name
	*/
	
    public function column_default( $item, $column_name ){
		global $current_user;
		$field = new FIELDS_DATABASE();
		$sel_fields = $field->get_nua_fields();
		$wp_fields = get_option( 'csds_userRegAide_knownFields' );
		unset( $wp_fields['user_pass'] );
		$user = wp_get_current_user();
		$user_defaults = get_user_meta( $user->ID, 'ura_users_column_defaults', true );
		if( $column_name == 'username' ){
			return $item->$column_name;
		}elseif( $column_name == 'name' ){
			return $item->$column_name;
		}elseif( $column_name == 'user_email' ){
			return $item->$column_name;
		}elseif( $column_name == 'user_registered' ){
			return $item->$column_name;
		}elseif( $column_name == 'emails_sent' ){
			return $item->$column_name;
		}elseif( $column_name == 'email_verification' ){
			return $item->$column_name;
		}
	}
	
	/** 
	 * function column_cb
	 * sets the column checkboxes for the user list table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params OBJECT $item column
	 * @returns OBJECT $item
	*/
		
    public function column_cb( $item ){
        return sprintf(
            '<input type="checkbox" name="approval[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item->user_login               //The value of the checkbox should be the record's id
        );
    }
	
	/** 
	 * function get_columns
	 * sets the column checkboxes for the user list table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns array $columns
	*/
		
    public function get_columns(){
		global $current_user;
		$field = new FIELDS_DATABASE();
		$sel_fields = $field->get_nua_fields();
		$wp_fields = get_option( 'csds_userRegAide_knownFields' );
		unset( $wp_fields['user_pass'] );
		$user = wp_get_current_user();
		$user_defaults = get_user_meta( $user->ID, 'ura_users_column_defaults', true );
		$columns = array(
			'cb'       				=> '<input type="checkbox" />', //Render a checkbox instead of text
            'username'				=>	__( 'Username', 'user-registration-aide' ),
			'name'     				=>  __( 'Name', 'user-registration-aide' ),
			'user_registered'		=>	__( 'Registered', 'user-registration-aide' ),
			'user_email'    		=>  __( 'Email', 'user-registration-aide' ),
			'emails_sent'			=>	__( 'Emails Sent', 'user-registration-aide' ),
			'email_verification'	=>	__( 'Email Verified', 'user-registration-aide' ),
			
        );
		if( !empty( $user_defaults ) ){
			if( !empty( $wp_fields ) ){
				foreach( $wp_fields as $key	=> $title ){
					foreach( $user_defaults as $meta => $value ){
						if( $meta == $key ){
							if( !empty( $value ) || $value == 1 ){
								$columns[$key] = $title;
							}
						}
						
					}
				}
			}
		}
		
		if( !empty( $sel_fields ) ){
			foreach( $sel_fields as $object ){
				$key = $object->meta_key;
				$field = $object->field_name;
				if( !empty( $user_defaults ) ){
					if( array_key_exists( $key, $user_defaults ) ){
						if( $user_defaults[$key] == 1 ){
							$columns[$key] = $field;
						}
					}
				}
			}
		}
		return $columns;
    }
	
	/** 
	 * function get_sortable_columns
	 * sets the sortable columns for the user list table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns array $sortable_columns
	*/
		
    public function get_sortable_columns() {
		$columns = array(
			'username' 				=> 'login',
			'name'     				=> 'name',
			'user_registered'		=> 'user_registered',
		);

		return $columns;
       
    }
	
	/** 
	 * function get_bulk_actions
	 * sets the bulk actions for the user list table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns array $actions
	*/
	//public function get_bulk_actions() {
    public function get_bulk_actions() {
		global $screen;
		$screen = get_current_screen();
		$userspage = 'users_page_';
		if( !empty( $screen ) ){
			if( $screen->id == $userspage.'pending-approval' ){
				$actions = array(
					'approve_user'   	 		=> __( 'Approve User', 'user-registration-aide' ),
					'deny_user'    	  			=> __( 'Deny User', 'user-registration-aide' ),
					'activate_user'   	 		=> __( 'Activate User', 'user-registration-aide' )
				);
				return $actions;
			}elseif( $screen->id == $userspage.'pending-deletion' ){
				$actions = array(
					'delete_user'    			=> __( 'Delete Denied User', 'user-registration-aide' ),
					'approve_user'   			=> __( 'Approve User', 'user-registration-aide' ),
					'activate_user'   	 		=> __( 'Activate User', 'user-registration-aide' )
				);
				return $actions;
			}elseif( $screen->id == $userspage.'pending-verification' ){
				$actions = array(
					'resend_email'   	   		=> __( 'Email User', 'user-registration-aide' ),
					'activate_user'   	 		=> __( 'Activate User', 'user-registration-aide' )
				);
				return $actions;
			}elseif( $screen->id == $userspage.'pending-password' ){
				$actions = array(
					'resend_password_email'    	=> __( 'Resend Password Email', 'user-registration-aide' ),
					'activate_user'   	 		=> __( 'Activate User', 'user-registration-aide' )
				);
				return $actions;
			}
		}else{
			return;
		}
	}

	/** 
	 * function process_bulk_action
	 * processes the bulk actions for the user list table
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns 
	*/
   
    function process_bulk_action() {
		wp_die( 'PROCESS BULK ACTION' );
		
    }
	
	/** 
	 * function display_rows
	 * Display signups rows.
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns 
	*/
 
	public function display_rows() {
		$style = '';
		if( !empty( $this->items ) ){
			foreach ( $this->items as $userid => $user_object ) {
				$style = ( ' class="alternate"' == $style ) ? '' : ' class="alternate"';
				echo "\n\t" . $this->single_row( $user_object, $style );
			}
		}
	}

	/**
	 * function single_row
	 * Generate HTML for a single row on the users.php admin panel.
	 *
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @since 4.2.0 The `$style` parameter was deprecated.
	 * @since 4.4.0 The `$role` parameter was deprecated.
	 * @access public
	 *
	 * @param object $user_object The current user object.
	 * @param string $style       Deprecated. Not used.
	 * @param string $role        Deprecated. Not used.
	 * @param int    $numposts    Optional. Post count to display for this user. Defaults
	 *                            to zero, as in, a new user has made zero posts.
	 * @return string Output for a single row.
	*/
	
	public function single_row( $user_object, $style = '', $role = '', $numposts = 0 ) {
		global $screen;
		$screen = get_current_screen();
		$userspage = 'users_page_';
		
		if ( ! ( $user_object instanceof WP_User ) ) {
			$user_object = get_userdata( (int) $user_object );
		}
		$user_object->filter = 'display';
		
		if ( $this->is_site_users ){
			$url = "site-users.php?id={$this->site_id}&amp;";
		}else{
			$url = 'users.php?';
		}
		$user_roles = $this->get_role_list( $user_object );

		// Set up the hover actions for this user
		$actions = array();
		$checkbox = '';
		// Check if the user for this row is editable
		if ( current_user_can( 'list_users' ) ) {
			// Set up the user editing link
			$edit_link = esc_url( add_query_arg( 'wp_http_referer', urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) ), get_edit_user_link( $user_object->ID ) ) );

			if ( current_user_can( 'edit_user',  $user_object->ID ) ) {
				$edit = "<strong><a href=\"$edit_link\">$user_object->user_login</a></strong><br />";
				$actions['edit'] = '<a href="' . $edit_link . '">' . __( 'Edit' ) . '</a>';
			} else {
				$edit = "<strong>$user_object->user_login</strong><br />";
			}

			if ( !is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'delete_user', $user_object->ID ) )
				$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url( "users.php?action=delete&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Delete' ) . "</a>";
			if ( is_multisite() && get_current_user_id() != $user_object->ID && current_user_can( 'remove_user', $user_object->ID ) )
				$actions['remove'] = "<a class='submitdelete' href='" . wp_nonce_url( $url."action=remove&amp;user=$user_object->ID", 'bulk-users' ) . "'>" . __( 'Remove' ) . "</a>";

			/**
			 * Filter the action links displayed under each user in the Users list table.
			 *
			 * @since 2.8.0
			 *
			 * @param array   $actions     An array of action links to be displayed.
			 *                             Default 'Edit', 'Delete' for single site, and
			 *                             'Edit', 'Remove' for Multisite.
			 * @param WP_User $user_object WP_User object for the currently-listed user.
			*/
			$actions = apply_filters( 'user_row_actions', $actions, $user_object );

			// Role classes.
			$role_classes = esc_attr( implode( ' ', array_keys( $user_roles ) ) );

			// Set up the checkbox ( because the user is editable, otherwise it's empty )
			$checkbox = '<label class="screen-reader-text" for="user_' . $user_object->ID . '">' . sprintf( __( 'Select %s' ), $user_object->user_login ) . '</label>'
						. "<input type='checkbox' name='users[]' id='user_{$user_object->ID}' class='{$role_classes}' value='{$user_object->ID}' />";

		} else {
			$edit = '<strong>' . $user_object->user_login . '</strong>';
		}
		$avatar = get_avatar( $user_object->ID, 32 );

		// Comma-separated list of user roles.
		$roles_list = implode( ', ', $user_roles );

		$r = "<tr id='user-$user_object->ID'>";

		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		foreach ( $columns as $column_name => $column_display_name ) {
			$classes = "$column_name column-$column_name";
			if ( $primary === $column_name ) {
				$classes .= ' has-row-actions column-primary';
			}
			if ( 'posts' === $column_name ) {
				$classes .= ' num'; // Special case for that column
			}

			if ( in_array( $column_name, $hidden ) ) {
				$classes .= ' hidden';
			}

			$data = 'data-colname="' . wp_strip_all_tags( $column_display_name ) . '"';

			$attributes = "class='$classes' $data";

			if ( 'cb' === $column_name ) {
				$r .= "<th scope='row' class='check-column'>$checkbox</th>";
			} else {
				$r .= "<td $attributes>";
				switch ( $column_name ) {
					case 'username':
						$r .= "$avatar $edit";
						break;
					case 'name':
						$r .= "$user_object->first_name $user_object->last_name";
						break;
					case 'email':
						$r .= $user_object->user_email;
						break;
					case 'role':
						$r .= esc_html( $roles_list );
						break;
					case 'posts':
						if ( $numposts > 0 ) {
							$r .= "<a href='edit.php?author=$user_object->ID' class='edit'>";
							$r .= '<span aria-hidden="true">' . $numposts . '</span>';
							//$r .= '<span class="screen-reader-text">' . sprintf( _n( '%s post by this author', '%s posts by this author', $numposts ), number_format_i18n( $numposts ) ) . '</span>';
							$r .= '</a>';
						} else {
							$r .= 0;
						}
						break;
					default:
						/**
						 * Filter the display output of custom columns in the Users list table.
						 *
						 * @since 2.8.0
						 *
						 * @param string $output      Custom column output. Default empty.
						 * @param string $column_name Column name.
						 * @param int    $user_id     ID of the currently-listed user.
						*/
						$r .= apply_filters( 'manage_users_custom_column', '', $column_name, $user_object->ID );
				}

				if ( $primary === $column_name ) {
					$r .= $this->row_actions( $actions );
				}
				$r .= "</td>";
			}
		}
		$r .= '</tr>';

		return $r;
	}
	
	
	/** 
	 * function current_action
	 * Capture the bulk action required, and return it.
	 * Overridden from the base class implementation to capture
	 * the role change drop-down.
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns string The bulk action required.
	*/
  
	public function current_action() {
		if ( isset( $_GET['changeit'] ) && !empty( $_REQUEST['new_role'] ) ){
			return 'promote';
		}
		if( isset( $_GET['action'] ) && $_GET['action'] == 'resend_email' ){
			wp_die( "ACTION" );
		}
		return parent::current_action();
		
	}
	
	/** 
	 * function views
	 * Display the list of views available on this table.
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns string The bulk action required.
	*/
   
	public function views() {
		$views = $this->get_views();
		/**
		 * Filter the list of available list table views.
		 *
		 * The dynamic portion of the hook name, `$this->screen->id`, refers
		 * to the ID of the current screen, usually a string.
		 *
		 * @since 3.5.0
		 *
		 * @param array $views An array of available list table views.
		 */
		$views = apply_filters( "views_{$this->screen->id}", $views );
		
		if ( empty( $views ) ){
			return;
		}else{
			echo "<ul class='subsubsub'>\n";
			foreach ( $views as $class => $view ) {
				$views[ $class ] = "\t<li class='$class'>$view";
			}
			echo implode( " |</li>\n", $views ) . "</li>\n";
			echo "</ul>";
		}
	}
	
	/**
	 * function 
	 * 
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function get_parent_views( $view ){
		$views = parent::get_views();
		$views = apply_filters( 'user_table_views', $views );
		return $views;		
			
	}
	
}

/** *************************** RENDER TEST PAGE ********************************
 *******************************************************************************
 * This function renders the admin page and the example list table. Although it's
 * possible to call prepare_items() and display() from the constructor, there
 * are often times where you may need to include logic here between those steps,
 * so we've instead called those methods explicitly. It keeps things flexible, and
 * it's the way the list tables are used in the WordPress core.
 */

/** 
 * function modify_views_users
 * adds my views to user list table views
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params array $views
 * @returns array $views
*/
  
function modify_views_users( $views ){
	//$pending = new URA_USERS_LIST_TABLE_NUA();
	$view = array();
	$views = apply_filters( 'modify_users_views', $view );
	
	return $views;
}
	

/** 
 * Function new_user_pending_approval_wp_list_view
 * adds my views to user list table views
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params 
 * @returns 
*/

function new_user_pending_approval_wp_list_view(){
    global $plugin_page, $ura_pending_approval_list_page, $parent_page, $title, $screen;
	//$wp_list_table = _get_list_table( 'WP_Users_List_Table' );
    //Create an instance of our package class...
    //Fetch, prepare, sort, and filter our data...
	$ura_pending_approval_list_page = new URA_USERS_LIST_TABLE_NUA();
	//$doaction = $ura_pending_approval_list_page->get_bulk_actions();
	//$title = __( 'Users') ;
	//$parent_file = 'users.php';
	// Build redirection URL
	$sendback = remove_query_arg( array( 'do_approved', 'denied', 'deleted', 'new_role' ), wp_get_referer() );
	$parent_screen = 'users';
	$context = 'normal';
	$screen = get_current_screen();
	$userspage = 'users_page_';
	/*
	if( isset( $_GET['action'] ) ){
		$action = $_GET['action'];
		if( $action != 'do_resend_email' ){
			$user_id = $_GET['user'];
			$ura_pending_approval_list_page->action_result = apply_filters( 'process_user_link_action', $action, $user_id );
			//do_action( 'user_actions', $action );
			//do_action( 'load-users.php' );
		}
		//do_action( 'load-users.php', $action );
	}*/
	$ura_pending_approval_list_page->prepare_items();
	//$title = __( 'Users') ;
	//$parent_file = 'users.php';
	//wp_die( $screen->id );
	
	$page = ( string ) '';
	if( isset( $_GET['page'] ) ){
		$page = $_GET['page'];
		//wp_die( $page );
	}
	if( !empty( $page ) ){
		if( $page == 'pending-approval' ){
			$form_url = add_query_arg(
			array(
				'page' => 'pending-approval',
			),
			get_admin_url().'users.php'
			);
			$sendback = add_query_arg( array( 'page' => $page  ), $sendback );
		}elseif( $page == 'pending-deletion' ){
			$form_url = add_query_arg(
			array(
				'page' => 'pending-deletion',
			),
			get_admin_url().'users.php'
			);
			$sendback = add_query_arg( array( 'page' => $page  ), $sendback );
		}elseif( $page == 'pending-verification' ){
			$form_url = add_query_arg(
			array(
				'page' => 'pending-verification',
			),
			get_admin_url().'users.php'
			);
			$sendback = add_query_arg( array( 'page' => $page  ), $sendback );
		}elseif( $page == 'pending-password' ){
			$form_url = add_query_arg(
			array(
				'page' => 'pending-password',
			),
			get_admin_url().'users.php'
			);
			$sendback = add_query_arg( array( 'page' => $page  ), $sendback );
		}
	}else{
		$form_url = add_query_arg(
		array(
			'page' => $screen->id,
		),
		get_admin_url().'users.php'
		);
	}
		
	?>
		<div class="wrap">
		<?php screen_icon( 'users' ); ?>
		<h2><?php _e( 'Users', 'user-registration-aide' ); ?></h2>
		<?php
		
		?>
		<div class="views">
		<?php // Display each signups on its own row ?>
		<?php $ura_pending_approval_list_page->views(); ?>
		</div>
		<form id="ura-approvals-form" action="<?php echo esc_url( $form_url );?>" method="post">
			<?php $ura_pending_approval_list_page->display(); ?>
		</form>
		</div>
<?php 
		//$pagenum = 1;
		//$sendback = add_query_arg( 'paged', $pagenum, $sendback );
		//wp_redirect( $sendback );
		//exit();
}


/*
function add_lp_ua_options(){
		global $userActivity;
		$option = 'per_page';
		$args = array(
			'label' => 'Records',
			'default' => 10,
			'option' => 'lp_tableview_records_per_page'
		);
		add_screen_option( $option, $args );
		
		$userActivity = new USER_ACTIVITY_VIEW();
}
	
function set_lp_tableview_ua_screen_options($status, $option, $value){
	return $value;
}*/
?>