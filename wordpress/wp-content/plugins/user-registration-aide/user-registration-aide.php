<?php
/**
Plugin Name: User Registration Aide
Plugin URI: http://creative-software-design-solutions.com/wordpress-user-registration-aide-force-add-new-user-fields-on-registration-form/

Description: Customize the entire user management experience to your own liking!. Adds additional fields to registration form and profile for better user management and control. Customize the Default WordPress Registration Form & Login Page CSS & Messaging. New User Approve - Email Verification & Password Reset Security Questions are new. It also helps reduce unwanted spam registrations. Has anti-spam built in, customize default WordPress login/registration forms both in design and messaging, adds agreement policy and link to policy to registration form for members, can create custom redirects after login and registration, short code for password change, custom password strength options, and password update management. Can limit amount of time between password changes and the number of times before duplicate passwords allowed, set minimum password length and also require special characters, upper and lower case letters and numbers so your site is made more secure. 

Version: 1.5.3.8
Author: Brian Novotny
Author URI: http://creative-software-design-solutions.com/
Text Domain: user-registration-aide

User Registration Aide - Customize the entire user management experience to your own liking!. Adds additional fields to registration form and profile for better user management and control. Customize the Default WordPress Registration Form & Login Page CSS & Messaging. Gives you more control over who registers, and allows you to manage users easier!

Copyright ©  2012 - 2016 Brian Novotny

Users Registration Helper - Forces new users to register additional fields

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
*/

// define constants
define( 'URA_PLUGIN_PATH', WP_PLUGIN_DIR.'/user-registration-aide/' );
define( 'URA_JS_PATH', plugin_dir_url( __FILE__ ).'js/' );
define( 'URA_CSS_PATH', plugin_dir_url( __FILE__ ).'css/' );
define( 'URA_IMAGES_PATH', plugin_dir_url( __FILE__ ).'images/' );
define( 'URA_SCREENSHOTS_PATH', plugin_dir_url( __FILE__ ).'screenshots/' );
define( 'URA_CLASSES_PATH', WP_PLUGIN_DIR.'/user-registration-aide/classes/' );
define( 'URA_CONTROLLERS_PATH', WP_PLUGIN_DIR.'/user-registration-aide/controllers/' );
define( 'URA_HELPERS_PATH', WP_PLUGIN_DIR.'/user-registration-aide/helpers/' );
define( 'URA_MEMBERS_PATH',  WP_PLUGIN_DIR.'/user-registration-aide/members/' );
define( 'URA_MODELS_PATH', WP_PLUGIN_DIR.'/user-registration-aide/models/' );
define( 'URA_VIEWS_PATH', WP_PLUGIN_DIR.'/user-registration-aide/views/' );
define( 'URA_THEME_DIR', WP_PLUGIN_DIR.'/user-registration-aide/themes/' );
define( 'URA_THEME_URL', WP_PLUGIN_DIR.'/user-registration-aide/themes/' );
define( 'URA_TEMPLATE_DIR', WP_PLUGIN_DIR.'/user-registration-aide/templates/' );
define( 'URA_TEMPLATE_URL', WP_PLUGIN_DIR.'/user-registration-aide/templates/' );
define( 'URA_TEMPLATES_PATH',  WP_PLUGIN_DIR.'/user-registration-aide/templates/' );

// includes requires
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

// ura files
require_once ( URA_PLUGIN_PATH."e-mails.php" ); //
require_once ( URA_PLUGIN_PATH."ura-functions.php" ); //

// Classes
require_once ( URA_CLASSES_PATH."ura-actions-filters.php" ); // 
require_once ( URA_CLASSES_PATH."ura-bp.php" ); // 
require_once ( URA_CLASSES_PATH."ura-dash-msgs.php" ); //
require_once ( URA_CLASSES_PATH."ura-email-class.php" ); //  
require_once ( URA_CLASSES_PATH."ura-messages.php" ); // 
require_once ( URA_CLASSES_PATH."user-reg-aide-options.php" ); // Behind the scenes options functions & default options
require_once ( URA_CLASSES_PATH."user-reg-aide-customCSS.php" ); // Handles custom css for registration forms 
require_once ( URA_CLASSES_PATH."user-reg-aide-actions.php" ); // Handles custom actions to eliminate redundancy
require_once ( URA_CLASSES_PATH."math-functions.php" ); // new for adding math problem to reduce spammers
require_once ( URA_CLASSES_PATH."ura-styles.php" ); // styles for plugin interface
require_once ( URA_CLASSES_PATH."ura-screen-options.php" ); // styles for plugin interface
require_once ( URA_CLASSES_PATH."user-reg-aide-xwrd-functions.php" ); // new for password options and related functions

// controllers
require_once ( URA_CONTROLLERS_PATH."ura-custom-options-controller.php" ); //
require_once ( URA_CONTROLLERS_PATH."ura-dashWidget-controller.php" ); //
require_once ( URA_CONTROLLERS_PATH."ura-edit-new-fields-controller.php" ); // 
require_once ( URA_CONTROLLERS_PATH."ura-edit-reg-form-fields-controller.php" ); // 
require_once ( URA_CONTROLLERS_PATH."ura-input-new-fields-controller.php" ); // 
require_once ( URA_CONTROLLERS_PATH."ura-lost-xwrd-controller.php" ); // 
require_once ( URA_CONTROLLERS_PATH."ura-menu-controller.php" ); //
require_once ( URA_CONTROLLERS_PATH."ura-reg-form-msgs-controller.php" ); // 
require_once ( URA_CONTROLLERS_PATH."ura-reg-form-options-controller.php" ); // 
require_once ( URA_CONTROLLERS_PATH."ura-screen-options-controller.php" ); //
require_once ( URA_CONTROLLERS_PATH."ura-sec-questions-controller.php" ); //
require_once ( URA_CONTROLLERS_PATH."ura-update-xwrd-controller.php" ); // 

// helpers
require_once ( URA_HELPERS_PATH."ura-fields-database.php" ); // 
require_once ( URA_HELPERS_PATH."ura-html.php" );

// members
require_once ( URA_MEMBERS_PATH."ura-members-admin.php" );
require_once ( URA_MEMBERS_PATH."ura-members-actions.php" );
require_once ( URA_MEMBERS_PATH."ura-pending-approval.php" ); //future use not used yet

// models
require_once ( URA_MODELS_PATH."ura-dashWidget-model.php" ); //
require_once ( URA_MODELS_PATH."ura-display-name-model.php" ); // 
require_once ( URA_MODELS_PATH."ura-edit-field-type-model.php" ); // 
require_once ( URA_MODELS_PATH."ura-edit-new-field-model.php" ); // 
require_once ( URA_MODELS_PATH."ura-edit-new-field-option-model.php" ); // 
require_once ( URA_MODELS_PATH."ura-edit-numbers-model.php" ); // 
require_once ( URA_MODELS_PATH."ura-edit-reg-form-fields-model.php" );
require_once ( URA_MODELS_PATH."ura-input-new-field-model.php" ); //
require_once ( URA_MODELS_PATH."ura-lost-xwrd-model.php" ); //
require_once ( URA_MODELS_PATH."ura-menu-model.php" ); //
require_once ( URA_MODELS_PATH."ura-new-user-approve-model.php" );
require_once ( URA_MODELS_PATH."ura-nua-settings-model.php" ); //
require_once ( URA_MODELS_PATH."ura-option-order-model.php" ); //
require_once ( URA_MODELS_PATH."ura-profile-model.php" ); //
require_once ( URA_MODELS_PATH."ura-registration-form-model.php" ); //
require_once ( URA_MODELS_PATH."ura-reg-form-css-model.php" ); //
require_once ( URA_MODELS_PATH."ura-reg-form-msgs-model.php" ); //
require_once ( URA_MODELS_PATH."ura-reg-form-options-model.php" ); //
require_once ( URA_MODELS_PATH."ura-sec-questions-model.php" ); //
require_once ( URA_MODELS_PATH."ura-style-options-model.php" ); //
require_once ( URA_MODELS_PATH."ura-support-model.php" ); //
require_once ( URA_MODELS_PATH."ura-xwrd-change-model.php" ); //
require_once ( URA_MODELS_PATH."ura-xwrd-strength-options-model.php" ); //
require_once ( URA_MODELS_PATH."ura-xwrd-update-model.php" ); //

// templates
require_once ( URA_TEMPLATES_PATH."template-controller.php" );
require_once ( URA_TEMPLATES_PATH."ura-template-loader.php" );
require_once ( URA_TEMPLATES_PATH."ura-template-model.php" );

// views
require_once ( URA_VIEWS_PATH."ura-add-options-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-agreement-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-dashWidget-view.php" ); //
//require_once ( URA_VIEWS_PATH."ura-debug.php" ); // 
require_once ( URA_VIEWS_PATH."ura-display-name-view.php" ); // 
require_once ( URA_VIEWS_PATH."ura-edit-new-field-options-view.php" ); // 
require_once ( URA_VIEWS_PATH."ura-edit-number-view.php" ); // 
require_once ( URA_VIEWS_PATH."ura-edit-reg-form-fields-view.php" );
require_once ( URA_VIEWS_PATH."ura-field-order-title-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-field-type-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-input-new-field-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-lost-xwrd-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-math-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-nua-settings-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-option-order-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-profile-title-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-profile-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-redirect-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-reg-form-css-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-reg-form-msgs-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-registration-form-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-sec-questions-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-style-options-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-support-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-xwrd-change-options-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-xwrd-strength-options-view.php" ); //
require_once ( URA_VIEWS_PATH."ura-xwrd-update-view.php" ); //

// ----------------------------------------------

/**
 * Creates Class CSDS_USER_REG_AIDE & Adds Actions and Hooks to register
 *
 * @category Class CSDS_USER_REG_AIDE
 * @since 1.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class CSDS_USER_REG_AIDE
{

	public static $instance;
	protected $retrieve_password_for   = '';
	public    $during_user_creation    = false; // hack
	public $file;
		
	/** 
	 * function CSDS_USER_REG_AIDE
	 * Loads all the actions and filters
	 * @since 1.0.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns 
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
		
	function CSDS_USER_REG_AIDE(){ 
	
		global $wp_version;
		$this->plugin_dir = dirname( __FILE__ );
		$this->file = plugin_basename( __FILE__ );
		$this->plugin_url = trailingslashit( get_option( 'siteurl' ) ) . 'wp-content/plugins/' . basename( dirname( __FILE__ ) ) .'/';
		$this->ref = explode( '?',$_SERVER['REQUEST_URI'] );
		$this->ref = $this->ref[0];
		
		// Gets plugin directory
		$plugins_dir = dirname( __FILE__ );
		
		// Actions and Filters
		$af = new URA_ACTIONS_FILTERS();
		add_action( 'init', array( &$af, 'initiate_plugin_actions' ) );
		unset( $af );
		
		$ura_options = new URA_OPTIONS();
		
		// Checks to make sure options are up to date
		$ura_options->check_options_table(); // Line 
		
		// Filling default User Registration Aide options db into memory
		$options = get_option( 'csds_userRegAide_Options' );
		
		unset( $ura_options );		
		
		// defines
		if ( ! defined( 'WP_CONTENT_URL' ) ){
			define( 'WP_CONTENT_URL', WP_SITEURL . '/wp-content' );
		}
		if ( ! defined( 'WP_CONTENT_DIR' ) ){
			define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
		}
		if ( ! defined( 'WP_PLUGIN_URL' ) ){
			define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
		}
		if ( ! defined( 'WP_PLUGIN_DIR' ) ){
			define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
		}
		if ( ! defined( 'WPMU_PLUGIN_URL' ) ){
			define( 'WPMU_PLUGIN_URL', WP_CONTENT_URL. '/mu-plugins' );
		}
		if ( ! defined( 'WPMU_PLUGIN_DIR' ) ){
			define( 'WPMU_PLUGIN_DIR', WP_CONTENT_DIR . '/mu-plugins' );
		}
		if( ! defined( 'WP_DEBUG' ) ){
			define( 'WP_DEBUG', true );
		}
		if( ! defined( 'WP_DEBUG_DISPLAY' ) ){
			define( 'WP_DEBUG_DISPLAY', false );
		}
		if( ! defined( 'WP_DEBUG_LOG' ) ){
			define( 'WP_DEBUG_LOG', true );
		}
		
		// Gets plugin directory
		$plugins_dir = dirname( __FILE__ );
		
		// Administration Pages
		$mc = new URA_MENU_CONTROLLER();
		add_action( 'init', array( &$mc, 'set_admin_menus' ) ); 
		unset( $mc );
		
		// Members
		$ura_admin = new URA_MEMBERS_ADMIN();
		add_action( 'init', array( &$ura_admin, 'setup_globals' ) );
		add_action( 'init', array( &$ura_admin, 'setup_actions' ) );
		unset( $ura_admin );
		
		// templates initiation
		$tc = new URA_TEMPLATE_CONTROLLER();
		
			
		if( $options['pages_created'] == 2 ){
			add_action( 'init', array( &$tc, 'create_extra_post_type' ) );
			add_action( 'init', array( &$tc, 'csds_pages_setup' ) );
			// to keep custom template pages off of front end menus
			// templates update
			
		}
		
		unset( $tc );
		// Translation File - 
		add_action( 'init', array( &$this, 'csds_userRegAide_translationFile' ) ); // Line 385 &$this
				
		// Deactivation hook for deactivation of User Registration Aide Plugin
		register_deactivation_hook( __FILE__, array( &$this, 'csds_userRegAide_deactivation' ) ); // Line 1427 &$this
				
		// Checking field options have been properly added
		$fdb = new FIELDS_DATABASE();
		$ura_dash_msgs = new URA_DASH_MSGS();
		add_filter( 'missing_options', array( &$fdb, 'check_for_missing_options' ), 10, 1 );
		add_filter( 'no_options_admin_msg', array( &$ura_dash_msgs, 'show_urgent_message' ), 10, 2 );
		add_action( 'admin_notices', array( &$ura_dash_msgs, 'option_missing_errors' ), 10, 1 );
		add_filter( 'no_options_msg', array( &$ura_dash_msgs, 'ura_option_missing_error' ), 10, 1 );
		add_filter( 'no_options_msg_string', array( &$ura_dash_msgs, 'get_no_options_msg_string' ), 10, 1 );
		unset( $fdb, $ura_dash_msgs );
				
	}

	// ----------------------------------------     Installation - Setup Functions     ----------------------------------------
	
	/** 
	 * function csds_userRegAide_install
	 * Installs options and default fields to database for plugin
	 * @since 1.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns 
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function csds_userRegAide_install(){
		$ura_options = new URA_OPTIONS();
		$ura_options->csds_userRegAide_fill_known_fields(); // Line 229 user-reg-aide-options.php
		$ura_options->csds_userRegAide_DefaultOptions(); // Line 59 user-reg-aide-options.php
		$fdb = new FIELDS_DATABASE();
		$fdb->create_fields_database();
		unset( $ura_options, $fdb );
	}
		
	/**	
	 * Function csds_userRegAide_translationFile
	 * Adds the translation directory to the plugin folder
	 * @since 1.1.0
	 * @updated 1.5.2.0
	 * @access public
	 * @handles add_action 'init' line 388 &$this
	 * @params
	 * @returns
	*/
	
	function csds_userRegAide_translationFile(){
		$plugin_path = plugin_basename( dirname( __FILE__ ) .'/languages' );
		load_plugin_textdomain( 'user-registration-aide', false, $plugin_path );
	}
	
		
	// ----------------------------------------     Deactivation Functions     ----------------------------------------

	/**
	 * function csds_userRegAide_deactivation
	 * Deactivation Function
	 * @since 1.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @handles 'register_deavtivation_hook' line 304 &$this
	 * @params string $field
	 * @returns
	*/
	
	function csds_userRegAide_deactivation(){
		// Do nothing here in case user wants to reactivate at a later time Just included because I am fairly sure WordPress requires something like this
		// uninsttall.php handles plugin deletion
	}
			
} //end CSDS_USER_REG_AIDE class

// ----------------------------------------     Pluggable Functions     ----------------------------------------




// ----------------------------------------     Plugin Initiate Functions     ----------------------------------------

/**
 * function 
 * Activates & runs The Plugin!
 * @since 1.0.0
 * @updated 1.5.2.0
 * @access public
 * @params
 * @returns
*/
# 
if( class_exists( 'CSDS_USER_REG_AIDE' ) ){
	$csds_userRegAide_Instance = new CSDS_USER_REG_AIDE();
	if( isset( $csds_userRegAide_Instance ) ){
		// if(function_exists('csds_userRegAide_install')){
			register_activation_hook( __FILE__, array(  &$csds_userRegAide_Instance, 'csds_userRegAide_install' ) );
		// }
		
		//register_deactivation_hook( __FILE__, array(  &$csds_userRegAide_Instance, 'Uninstall' ) );
	}
}

?>