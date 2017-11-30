<?php

/**
 * Class URA_DASH_MSGS
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_DASH_MSGS
{
		
	/** 
	 * function show_urgent_message
	 * Sets the <p> class="messages" to custom message (2nd message on login form)
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @Filters 'login_messages' line 172 &$this 
		*
	 * @params string $msg, boolean $error_msg
	 * @returns nothing prints urgent message
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	 */
	
	function show_urgent_message( $msg, $error_msg = false ){
		$cnt = ( int ) 0;
		$cnt = $this->error_page_define();
		if( $cnt == 0 ){
			if ( $error_msg == true ) {
				echo '<div id="message" class="notice notice-error"><p><strong>'.$msg.'</strong></p></div>';
			}
			else {
				echo '<div id="message" class="updated fade"><p><strong>'.$msg.'</strong></p></div>';
			}
		}
	}
	
	/** 
	 * function no_options_ms
	 * shows urgent message if user changed a field with no options in it
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns and displays string $msg urgent message
	*/	
	
	function no_options_msg(){
		$msg = ( string ) '';
		$msg = apply_filters( 'no_options_msg_string', $msg );
		$this->show_urgent_message( $msg, false );
	}
	
	/** 
	 * function get_no_options_msg_string
	 * shows urgent message if user changed a field with no options in it
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns string $msg urgent message
 */
	
	function get_no_options_msg_string(){
		$msg = ( string ) '';
		$add_options_link = ( string ) admin_url( 'admin.php?page=edit-new-fields&tab=add_options' );
		$change_field_type_link = ( string ) admin_url( 'admin.php?page=edit-new-fields&tab=field_type' );
		$msg = sprintf( __(  'You changed a data type to a type that requires options and do not have any options for it.</p><p>To use it properly please add some options <a href="%1s"> HERE </a> or change the field type <a href="%2s"> HERE </a> to one that does not require options!',  'user-registration-aide' ), $add_options_link, $change_field_type_link ) ;
		return $msg;
	}
	
	/** 
	 * function show_admin_message
	 * shows urgent message if user changed a field with no options in it
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg to $this->show_urgent_message
	 */
	
	function show_admin_message( $msg ){
		$this->show_urgent_message( $msg );
	}
	
	/** 
	 * function ura_option_missing_error
	 * checks for a field with missing options and returns an error message 
	 * to display if their is one or more fields missing required options
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg 
	*/
	
	function ura_option_missing_error( $msg ){
		$missing = ( int ) 0;
		$msg1 = ( string ) '';
		$msg2 = ( string ) '';
		$missing = apply_filters( 'missing_options', $missing );
		if( $missing >= 1 ){
			if( empty( $msg ) ){
				$msg1 = '<div id="message" class="notice notice-error"><p><strong>';
				$msg1 .= apply_filters( 'no_options_msg_string', $msg );
				$msg1 .= '</strong></p></div>';
				return $msg1;
				}else{
				$msg2 = '<div id="message" class="notice notice-error"><p><strong>';
				$msg2 .= apply_filters( 'no_options_msg_string', $msg );
				$msg2 .= '</strong></p></div>';
				return $msg2;
			}
			}else{
			return $msg;
		}
		
	}
	
	/** 
	 * function option_missing_errors
	 * checks for a field with missing options and returns an admin notice error message 
	 * to display if their is one or more fields missing required options
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @handles action 'admin-notices' line 585 &$this
	 * @access public
	 * @params
	 * @returns string $msg 
	 */
	
	function option_missing_errors(){
		//$ura_msg = new CSDS_URA_MESSAGES();
		$missing = ( int ) 0;
		$msg = ( string ) '';
		$msg = apply_filters( 'no_options_msg_string', $msg );
		$missing = apply_filters( 'missing_options', $missing );
		if( $missing >= 1 ){
			apply_filters( 'no_options_admin_msg', $msg, true );
		}
	}
	
	/** 
	 * function error_page_define
	 * confirms if page is a ura settings page
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns int $cnt for confirmation page is a ura settings page or not
	*/
	
	function error_page_define(){
		$cnt = ( int ) 0;
		if( isset( $_GET['page'] ) && $_GET['page'] == 'user-registration-aide' ){
			$cnt++;
		}
		
		if( isset( $_GET['page'] ) && $_GET['page'] == 'edit-new-fields' ){
			$cnt++;
		}
		
		if( isset( $_GET['page'] ) && $_GET['page'] == 'registration-form-options' ){
			$cnt++;
		}
		
		if( isset( $_GET['page'] ) && $_GET['page'] == 'registration-form-css-options' ){
			$cnt++;
		}
		
		if( isset( $_GET['page'] ) && $_GET['page'] == 'custom-options' ){
			$cnt++;
		}
		return $cnt;
	}
	
	/** 
	 * function my_help_setup
	 * Adds a help option to the plugins page
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns
	 */
	
	function my_help_setup(){
		global $ura_settings_0, $ura_settings_1, $ura_settings_2, $ura_settings_3, $ura_settings_4, $ura_settings_5, $new_user_pending_approval_page, $new_user_pending_deletion_page, $new_user_pending_verification_page;
		
		/* Custom help sidebar message. */
		$help_0 = '<p>'. __( 'For More Help and Instructions Visit Our Plugin Page:', 'user-registration-aide' );
		$help_1 = ( string ) '<p>'. __( '<a href="http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/" target="blank">User Registration Aide Home Page', 'user-registration-aide' ).'</a></p>';
		$help_2 = ( string ) '<h2><a href="http://creative-software-design-solutions.com/wp-content/uploads/2017/05/USER_REGISTRATION_AIDE_INSTRUCTIONS.pdf" target="new">'.__( 'View User Registration Aide Instructions Here', 'user-registration-aide' ).'</a></h2>';
		
		$screen = get_current_screen();
		if( !empty( $screen ) ){
			// Add my_help_tab if current screen is My Admin Page
			$screen->add_help_tab( array(
			'id'		=> 	'ura_help_tab',
			'title'		=>	__( 'User Registration Aide Help Tab', 'user-registration-aide' ),
			'content'	=>	'<p style="text-align:center;"><b>'. __( 'User Registration Aide Help', 'user-registration-aide' ).'</b></p>',
			'callback'	=>	array( &$this, 'my_help_message' ) )
			);
			$screen->set_help_sidebar( $help_0.$help_1.$help_2 );
		}
		
	}
	
	/** 
	 * function my_help_message
	 * Adds to the help message on this plugins page
	 * @since 1.5.2.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	 */
	
	function my_help_message(){
		global $ura_settings_0, $ura_settings_1, $ura_settings_2, $ura_settings_3, $ura_settings_4, $ura_settings_5, $new_user_pending_approval_page, $new_user_pending_deletion_page, $new_user_pending_verification_page, $ura_rf_css;
		$screen = get_current_screen();
		if( $screen->base == $ura_settings_0 ){
			echo '<p style="text-align:center;"><b>'. __( 'User Registration Aide: Input New Fields Here', 'user-registration-aide' ).'</b></p>';
			echo '<ul><li>'. __( 'Change Dashboard Widget Settings and Add New Fields On This Page', 'user-registration-aide' ).'</li>';
		}
		
		if( $screen->base == $ura_settings_3 ){
			echo '<p style="text-align:center;"><b>'. __( 'User Registration Aide: Edit New Fields Here', 'user-registration-aide' ).'</b></p>';
			echo '<ul><li>'. __( 'Edit Custom Registration Form Fields, Delete Fields and Edit New Fields Options On This Page', 'user-registration-aide' ).'</li>';
		}
		
		if( $screen->base == $ura_settings_1 ){
			echo '<p style="text-align:center;"><b>'. __( 'User Registration Aide: Edit Registration Form Options Here', 'user-registration-aide' ).'</b></pi>';
			echo '<ul><li>'. __( 'Change Password Strength Requirements, Custom Redirects, Agreement Message, Anti_Spam Math Problem & Profile Page Title Options On This Page', 'user-registration-aide' ).'</li>';
		}
		
		if( $screen->base == $ura_settings_2 ){
			if( isset( $_GET['tab'] ) ){
				$tab = $_GET['tab'];				
			}
			echo '<p style="text-align:center;"><b>'. __( 'User Registration Aide: Edit Registration Form Messages & CSS Here', 'user-registration-aide' ).'</b></p>';
			echo '<ul><li>'. __( 'Change Registration Form Messages and CSS Options On This Page', 'user-registration-aide' ).'</li>';
			if( $tab == 'css' ){
				echo '<li>'. __( 'The Logo Size Determines the Size of the Registration Form if it is Larger than 320px. If your Image is 500px Wide, the Registration Form will be 500px Wide!', 'user-registration-aide' ).'</li>';
			}
		}
		
		if( $screen->base == $ura_settings_4 ){
			echo '<p style="text-align:center;"><b>'. __( 'User Registration Aide: Edit Custom Option Settings Here', 'user-registration-aide' ).'</b></p>';
			echo '<ul><li>'. __( 'Update Password Change Options, User Display Name & Plugin Style Options On This Page', 'user-registration-aide' ).'</li>';
		}
		if( $screen->base != $new_user_pending_approval_page && $screen->base != $new_user_pending_deletion_page && $screen->base != $new_user_pending_verification_page ){
			echo '<ul><li>'. __( 'For Most Help Options Just Hover Over the Input Box Area or Selection You Wish to Make', 'user-registration-aide' ).'</li>';
			echo '<li>'. __( 'A Help Box Pop-up Will Appear And Give You More Specific Instructions For That Specific Field Selection Or Option', 'user-registration-aide' ).'</li>';
			echo '<li>'. __( 'If You Still Cannot Find The Help You Need See Sidebar For Link to Plugin Homepage', 'user-registration-aide' ).'</li></ul>';
		}elseif( $screen->base == $new_user_pending_approval_page ){
			echo '<p>'. __( 'This is the administration screen for pending accounts on your site.', 'user-registration-aide' ) . '</p>';
			echo '<p>'. __( 'From the screen options, you can customize the displayed columns and the pagination of this screen.', 'user-registration-aide' ) . '</p>';
			echo '<p>'. __( 'You can reorder the list of your pending accounts by clicking on the Username, Name or Registered column headers.', 'user-registration-aide' ) . '</p>';
			echo '<p>'. __( 'Using the Bulk Actions or hovering over Username, you can Approve users, Send another Email to Users reminding them to verify Email, or Deny new Users membership. Denied Users do not get deleted if denied, that is another process which is handled on the Pending Deletion Screen in case you made a mistake so you cannot delete a new user by accident!', 'user-registration-aide' ) . '</p>';
		}elseif( $screen->base == $new_user_pending_deletion_page ){
			echo '<p>'. __( 'This is the administration screen for denied user accounts pending deletion on your site.', 'user-registration-aide' ) . '</p>';
			echo '<p>'. __( 'From the screen options, you can customize the displayed columns and the pagination of this screen.', 'user-registration-aide' ) . '</p>';
			echo '<p>'. __( 'You can reorder the list of your pending accounts by clicking on the Username, Name or Registered column headers.', 'user-registration-aide' ) . '</p>';
			echo '<p>'. __( 'This is where you can delete user accounts that have been denied membership to your site using the Bulk Actions or by hovering over the Username. They are not deleted when denied to make sure you don\'t permanently delete a new user by accident. You can also approve a user here if you did make a mistake and want to grant them access to the site. ', 'user-registration-aide' ) . '</p>';
		}elseif( $screen->base == $new_user_pending_verification_page ){
			echo '<p>'. __( 'Hovering over a row in the pending accounts list will display action links that allow you to manage pending accounts. You can perform the following actions:', 'user-registration-aide' ) . '</p>';
			echo '<ul><li>'. __( '"Activate" allows you to bypass the email verification and administration approval process and instantly granting a new user access to the site.', 'user-registration-aide' ) . '</li></ul>';
			echo '<li>'. __( '"Deny" allows you to deny a pending account access to your site. It is a safewfty mechanism so you can re-approve a User account so you will not lose all the User\'s information from a mistaken deletion. You can approve or delete the User from the Rending Deletion Page.', 'user-registration-aide' ) . '</li>';
			echo '<li>' . __( '"Email" sends the User an Email reminder to verify their Email address.', 'user-registration-aide' ) . '</li></ul>';
			echo '<p>'. __( 'By hovering over a Username you will be able to do any actions to a pending account from any screen.', 'user-registration-aide' ) . '</p>';
			echo '<p>'. __( 'Bulk actions allow you to perform any of these 3 actions for the selected rows.', 'user-registration-aide' ) . '</p>';
		}elseif( $screen->base == $new_user_pending_password_page ){
			echo '<p>'. __( 'Hovering over a row in the pending accounts list will display action links that allow you to manage pending accounts. You can perform the following actions:', 'user-registration-aide' ) . '</p>';
			echo '<li>' . __( '"Resend Password Email" sends the User an Email reminder to set their new user account password with an passwrod reset activation link.', 'user-registration-aide' ) . '</li></ul>';
			echo '<p>'. __( 'By hovering over a Username you will be able to do any actions to a pending account from any screen.', 'user-registration-aide' ) . '</p>';
			echo '<p>'. __( 'Bulk actions allow you to perform any of these actions for the selected rows.', 'user-registration-aide' ) . '</p>';
		}
	}

}