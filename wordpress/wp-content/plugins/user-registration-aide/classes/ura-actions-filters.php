<?php

/**
 * Class URA_ACTIONS_FILTERS
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_ACTIONS_FILTERS
{
		
	/**	
	 * Function initiate_plugin_actions
	 * initiates all plugin filters and actions
	 * @since 1.5.2.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function initiate_plugin_actions(){
			
		// custom actions for this plugin
		
		// Adds widget to wp admin page dashboard
		$dashWidget_control = new URA_DASH_WIDGET_CONTROLLER();
		$dashWidget_view = new URA_DASH_WIDGET_VIEW();
		add_action( 'wp_dashboard_setup', array( &$dashWidget_control, 'ura_dashboard_widget_action' ) );  // Line 59 user-reg-aide-dashWidget.php
		//allow_password_reset
		$mm = new URA_MENU_MODEL();
		$dw_model = new URA_DASH_WIDGET_MODEL();
		$support = new URA_SUPPORT_MODEL();
		$support_view = new URA_SUPPORT_VIEW();
		$actions = new CSDS_URA_ACTIONS();
		$dashWidget_control = new URA_DASH_WIDGET_CONTROLLER();
		$dashWidget_view = new URA_DASH_WIDGET_VIEW();
		$ura_options = new URA_OPTIONS();
		add_action( 'create_tabs', array( &$mm, 'options_tabs_page' ), 10, 1 ); // 
		add_action( 'show_support', array( &$support_view, 'show_support_section' ) ); // Line 141 user-reg-aide-actions.php
		add_filter( 'ura_support_update', array( &$support, 'ura_support_update_filter' ), 10, 3 );
		add_action( 'update_field_order', array( $ura_options, 'csds_userRegAide_update_field_order' ) ); // Line 331 &$ura_options
		add_action( 'update_options', array( $ura_options, 'csds_userRegAide_updateOptions' ) ); // Line 402 &$ura_options
		add_action( 'update_database', array( $ura_options, 'update_ura_options_database' ) ); // Line 402 &$ura_options
		add_action( 'display_dw_options', array( $dashWidget_view, 'dashboard_widget_options' ) ); // 
		add_filter( 'update_dw_display_options', array( $dw_model, 'update_dashboard_widget_options' ), 10, 1 ); 
		add_filter( 'update_dw_field_options', array( $dw_model, 'update_dashboard_widget_fields' ), 10, 1 ); // 
		add_filter( 'update_dw_field_order', array( $dw_model, 'update_dashboard_widget_field_order' ), 10, 1 ); // 
		
		// Adds stylesheet to admin dashboard widget
		add_action( 'admin_print_styles', array( &$dashWidget_control, 'csds_dashboard_widget_style' ) ); // Line 655 &$dashWidget
		unset( $dashWidget_control, $dashWidget_view, $ura_options, $dw_model, $support, $support_view );
		
		// new custom actions 7/28/14 for new views
		$display_name = new URA_DISPLAY_NAME_MODEL();
		$dnv = new URA_DISPLY_NAME_VIEW();
		$styles_model = new URA_STYLE_OPTIONS_MODEL();
		$styles_view = new URA_STYLE_OPTIONS_VIEW();
		
		add_filter( 'display_name_options_update', array( &$display_name, 'update_display_name_options' ), 10, 1 );
		add_action( 'display_name_view', array( &$dnv, 'user_display_name_view' ) );
		add_filter( 'ura_styles_border_array', array( &$styles_model, 'ura_border_style_array' ), 10, 1 );
		add_filter( 'ura_styles_collapse_array', array( &$styles_model, 'ura_border_collapse_array' ), 10, 1 );
		add_action( 'style_options_view', array( &$styles_view, 'custom_style_view' ) );	
		add_filter( 'style_options_update', array( &$styles_model, 'style_options_page_update' ), 10, 1 );
		unset( $display_name, $styles_view, $styles_model, $dnv  );
		
		// Adding custom stylesheets
		// Enqueues color picker for stylesheet settings page
		$uras = new URA_STYLES();
		$customCSS = new URA_CUSTOM_CSS();
		add_action( 'admin_enqueue_scripts', array( &$uras, 'my_style_color_function' ) ); // &$this line 764
		add_action( 'wp_enqueue_scripts', array( &$uras, 'enqueueXwrdStyles' ) ); // for password strength meter
		add_action( 'admin_print_styles', array( &$uras, 'add_settings_css' ) ); // Line 734 &$this
		add_action( 'wp_enqueue_scripts', array( &$uras, 'csds_userRegAide_stylesheet' ) ); // Line 779 &$this
		add_action( 'admin_init', array( &$uras, 'csds_userRegAide_stylesheet' ) ); // Line 779 &$this
		
		// styling enqueues and registering for css styling on plugin admin pages
		if( isset( $_GET['page'] ) && $_GET['page'] == 'user-registration-aide' ){
			add_action( 'admin_print_styles', array( &$uras, 'add_admin_settings_css' ) ); // Line 748 &$this
		}
		
		if( isset( $_GET['page'] ) && $_GET['page'] == 'edit-new-fields' ){
			add_action( 'admin_print_styles', array( &$uras, 'add_admin_settings_css' ) ); // Line 748 &$this
		}
		
		if( isset( $_GET['page'] ) && $_GET['page'] == 'registration-form-options' ){
			add_action( 'admin_print_styles', array( &$uras, 'add_admin_settings_css' ) ); // Line 748 &$this
		}
		
		if( isset($_GET['page']) && $_GET['page'] == 'registration-form-css-options' ){
			add_action('admin_print_styles', array( &$uras, 'add_admin_settings_css' ) ); // Line 748 &$this
		}
		
		if( isset( $_GET['page']) && $_GET['page'] == 'custom-options' ){
			add_action( 'admin_print_styles', array( &$uras, 'add_admin_settings_css' ) ); // Line 748 &$this
		}
				
		if( isset( $_GET['action'] ) && $_GET['action'] == 'register' ){
			add_action( 'login_enqueue_scripts', array( &$uras, 'add_lostpassword_css' ) ); // Line 762 &$this
			add_action( 'login_enqueue_scripts', array( &$customCSS, 'csds_userRegAide_Password_Header' ) );
		}
		
		if( isset( $_GET['action']) && $_GET['action'] == 'rp' ){
			add_action( 'login_enqueue_scripts', array( &$uras, 'add_lostpassword_css' ) ); // Line 762 &$this
		}						
				
		// Customize Registration & Login Forms
		
		add_filter( 'login_headerurl', array( &$customCSS, 'csds_userRegAide_CustomLoginLink' ) ); // Line 151 &$customCSS
		add_filter( 'login_headertitle', array( &$customCSS, 'csds_userRegAide_Logo_Title_Color' ) ); // Line 56 &$customCSS
		add_action( 'login_head', array( &$customCSS, 'csds_userRegAide_Logo_Head' ), 0 ); // Line 100 &$customCSS
		
		// Changes the Login & Register form messages for this site at top of forms
		$msgs = new CSDS_URA_MESSAGES();
		add_filter( 'login_message', array( &$msgs, 'ura_login_message' ) ); // Line 502 &$this
		add_filter( 'login_messages', array( &$msgs, 'my_login_messages' ) ); // Line 578 &$this
		unset( $msgs );
		
		$rfm = new REGISTRATION_FORM_MODEL();
		// Filter to modify redirect of successful user login
		add_filter( 'login_redirect', array( &$rfm, 'ura_login_redirect' ), 0, 1 ); // Line 449 &$this (Params: string $redirect_to)
		
		// fixes new user approve bugs hasn't been updated to new registration process yet
		
		$plugin = 'new-user-approve/new-user-approve.php';
		$class = 'pw_new_user_approve';
		$rfv = new URA_REGISTRATION_FORM_VIEW();
		if( class_exists( $class ) && is_plugin_active( $plugin ) ){
			$pw_nua = pw_new_user_approve::instance();
			remove_action( 'new_user_approve_approve_user', array( &$pw_nua, 'approve_user' ) );//pw_new_user_approve::
			remove_filter( 'registration_errors', array( &$pw_nua, 'show_user_pending_message' ) );
			add_filter( 'registration_errors', array( &$rfv, 'ura_show_user_pending_message' ), 20, 3 );
			//remove_action( 'new_user_approve_approve_user', pw_new_user_approve::approve_user() );//pw_new_user_approve::
			remove_action( 'register_post', array( &$pw_nua, 'create_new_user' ), 10, 3 );
			//remove_filter( 'login_message', array( &$pw_nua, 'welcome_user' ) );
			//add_action(  'register_post', array( &$regForm, 'ura_create_new_user' ), 10, 3 );
			add_action( 'new_user_approve_approve_user', array( &$actions, 'ura_approve_user' ) );
			add_filter( 'nua_registration_message', array( &$actions, 'ura_register_message' ), 10, 1 );
			add_filter( 'nua_success_registration_message', array( &$actions, 'ura_success_register_message' ), 10, 1 );
			unset( $pw_nua );
		}
		
		// Handles new fields and actions/filters for registration form
		if( isset( $_GET['action'] ) && $_GET['action'] == 'register' ){
			add_action( 'register_form', array( &$rfv, 'add_fields_registration_form' ), 0 ); // Line 57 &$regForm
			add_action( 'user_register', array( &$rfm, 'update_new_user_fields' ), 1, 1 ); // Line 283 &$regForm (Params: int $user_id)
			add_filter( 'registration_errors', array( &$rfm, 'verify_new_user_fields' ), 1, 3 );
			add_filter( 'registration_redirect', array( &$rfm, 'ura_registration_redirect'), 1, 1 ); 
			add_action( 'password_input', array( &$rfv, 'display_password_fields' ) );
			add_action( 'fields_input', array( &$rfv, 'display_other_fields' ), 1, 3 );
			add_action( 'tml_fields_input', array( &$rfv, 'display_other_fields_theme_my_login' ), 1, 3 );
			add_action( 'ta_input', array( &$rfv, 'display_text_area_fields' ), 1, 3 );
			add_action( 'tml_ta_input', array( &$rfv, 'display__text_area_fields_theme_my_login' ), 1, 3 );
			add_action( 'known_fields_rf', array( &$rfv, 'display_known_fields' ), 1, 3 );
			add_action( 'tml_known_fields_rf', array( &$rfv, 'display_known_fields_theme_my_login' ), 1, 3 );
			add_filter( 'create_label', array( &$rfm, 'create_field_label' ), 10, 2 );
			add_filter( 'create_kf_label', array( &$rfm, 'create_known_field_label' ), 10, 2 );
		}
		
		// for theme my login if activated
		
		if ( function_exists( 'theme_my_login' ) ) {
			include_once( WP_PLUGIN_DIR . '/theme-my-login/theme-my-login.php' );
			add_action( 'register_form', array( &$rfv, 'add_fields_registration_form' ), 20 ); // Line 57 &$regForm
			add_action( 'user_register', array( &$rfm, 'update_new_user_fields' ), 20, 1 ); // 
			add_filter( 'registration_errors', array( &$rfm, 'verify_new_user_fields' ), 20, 3 ); // 
			add_filter( 'registration_redirect', array( &$rfm, 'ura_registration_redirect' ), 0, 1 ); // 
			add_action( 'login_enqueue_scripts', array( &$customCSS, 'csds_userRegAide_Logo_Head' ), 20 );
			add_action( 'login_head', 'wp_print_head_scripts', 25 );
			add_action( 'login_head', array( &$customCSS, 'csds_userRegAide_Logo_Head' ), 30 );
			add_action( 'login_footer', 'wp_print_footer_scripts', 30 );
			add_filter( 'create_label', array( &$rfm, 'create_field_label' ), 10, 2 );
			add_filter( 'create_kf_label', array( &$rfm, 'create_known_field_label' ), 10, 2 );
			
		}
		unset( $rfv, $rfm );
		// Adds settings page to wordpress plugins page & css
		add_filter( 'plugin_action_links', array( &$mm, 'add_plugins_settings_link' ), 10, 2 ); // Line 621 &$this (Params: array $links, str $file)
		add_action( 'start_wrapper', array( &$actions, 'start_wp_wrapper' ), 10, 5 );
		add_action( 'start_msg_wrapper', array( &$actions, 'start_wp_msg_wrapper' ), 10, 5 );
		add_action( 'create_msg_tabs', array( &$mm, 'msg_options_tabs_page' ), 10, 3 );
		add_action( 'end_wrapper', array( &$actions, 'end_wp_wrapper' ), 10, 0 );
		add_action( 'start_mini_wrap', array( &$actions, 'start_mini_wp_wrapper' ), 10, 1 );
		add_action( 'end_mini_wrap', array( &$actions, 'end_mini_wp_wrapper' ), 10, 0 );
		add_action( 'mini_tabs', array( &$mm, 'show_mini_tabs' ), 10, 2 );
		unset( $actions, $mm );
		
		// Handles user profiles and extra fields
		$prf_mdl = new URA_PROFILE_MODEL();
		$prf_view = new URA_PROFILE_VIEW();
		add_action( 'show_user_profile', array( &$prf_view, 'csds_show_user_profile' ), 0, 1 ); //
		add_action( 'edit_user_profile', array( &$prf_view, 'csds_show_user_profile' ), 0, 1 ); //
		add_action( 'personal_options_update', array( &$prf_mdl, 'csds_update_user_profile' ), 0, 1 ); // 
		add_action( 'edit_user_profile_update', array( &$prf_mdl, 'csds_update_user_profile' ), 0, 1 ); // 
		add_action( 'profile_update', array( &$prf_mdl, 'csds_update_user_profile' ), 0, 1 ); // 
		add_filter( 'user_profile_update_errors', array( &$prf_mdl, 'check_profile_fields' ), 10, 3 );
		add_action( 'delete_usermeta_field', array( &$prf_mdl, 'csds_delete_field_from_users_meta'), 10, 1 ); // Line 1206 &$this
		// Add new column to the user list
		add_filter( 'manage_users_columns', array( &$prf_mdl, 'csds_userRegAide_addUserFields' ) ); //
		add_filter( 'manage_users_custom_column', array( &$prf_mdl, 'csds_userRegAide_fillUserFields' ), 0, 3 ); // 
		unset( $prf_mdl, $prf_view );
		
		// views models controllers actions & filters for new field input
		$infc = new INPUT_NEW_FIELDS_CONTROLLER();
		$infm = new INPUT_NEW_FIELDS_MODEL();
		$infv = new INPUT_NEW_FIELDS_VIEW();
		add_filter( 'get_option_fields_array', array( &$infm, 'option_fields_array' ), 10, 1 );
		add_action( 'new_fields_input_controller', array( &$infc, 'initiate_new_fields_input_page' ), 10 );
		add_filter( 'new_fields_input_filter', array( &$infm, 'new_fields_input_model' ), 10, 1 );
		add_action( 'new_fields_input_view', array( &$infv, 'new_fields_input_viewer' ), 10, 1 );
		unset( $infc, $infm, $infv );
		
		// actions for edit new fields page
		$enfm = new EDIT_NEW_FIELDS_MODEL();
		$enfc = new URA_EDIT_NEW_FIELDS_CONTROLLER();
		$eoom = new OPTION_ORDER_MODEL();
		$oov = new OPTION_ORDER_VIEW();
		$enm = new EDIT_NUMBERS_MODEL();
		$env = new URA_EDIT_NUMBERS_VIEW();
		$enfom = new EDIT_FIELD_OPTIONS_MODEL();
		$efov = new URA_EDIT_FIELDS_OPTIONS_VIEW();
		$eftm = new EDIT_FIELD_TYPE_MODEL();	
		$rf_view = new URA_EDIT_REGISTRATION_FORM_FIELDS_VIEW();
		$rf_ctrl = new URA_EDIT_REGISTRATION_FIELDS_CONTROLLER();
		$rf_model = new URA_EDIT_REGISTRATION_FORM_FIELDS_MODEL();
		$fotv = new URA_FIELD_ORDER_TITLE_VIEW();
		$ftv = new URA_FIELD_TYPE_VIEW();
		$aov = new URA_ADD_OPTIONS_VIEW();
		add_action( 'reg_form_fields_view', array( $rf_view, 'edit_reg_form_fields_view' ), 10, 1 );
		add_filter( 'reg_fields_selections', array( $rf_model, 'update_registration_fields_settings_model' ), 10, 1 );
		add_action( 'reg_fields_controller', array( $rf_ctrl, 'update_registration_fields_settings_controller' ) , 10 );
		add_action( 'new_fields_editing_controller', array( &$enfc, 'edit_new_fields_controller' ), 10, 1 );
		add_filter( 'edit_new_field_model', array( &$enfm, 'new_fields_edit_model' ), 10, 1 );
		add_action( 'field_order_view', array( $fotv, 'field_order_title_view' ) );
		add_action( 'field_type_view', array( $ftv, 'field_data_type_view' ) );
		add_action( 'new_option_view', array( $aov, 'add_new_options_view' ) );
		add_filter( 'options_order_model', array( $eoom, 'options_order_model' ), 10 );
		add_action( 'options_order_view', array( $oov, 'options_order_viewer' ), 10 );
		add_filter( 'new_field_options_model', array( $enfom, 'edit_new_field_options_model' ), 10 );
		add_action( 'new_field_options_view', array( $efov, 'edit_new_fields_options_view' ), 10 );
		add_filter( 'edit_data_type', array( $eftm, 'edit_new_field_type_model' ), 10, 1 );
		add_filter( 'edit_numbers_model', array( $enm, 'numbers_type_editing_model' ), 10, 1 );
		add_action( 'number_editor_view', array( $env, 'numbers_type_editing_view' ), 10 );
		//add_action( 'manage_views', array( &$vm, 'view_manager' ), 10, 3 );
		unset( $enfm, $enfc, $eoom, $oov, $enm, $env, $enfom, $efov, $fotv, $ftv );
		$pwdm = new XWRD_STRENGTH_MODEL();
		add_filter( 'xwrd_strength_checker', array( &$pwdm, 'check_reg_form_xwrd_strength' ), 10, 5 );
		unset( $pwdm );
		
		// password options
		$xwrd = new PASSWORD_FUNCTIONS();
		$uco = new URA_CUSTOM_OPTIONS_CONTROLLER();
		$xwrd_view = new XWRD_STRENGTH_OPTIONS_VIEW();
		$pwdm = new XWRD_STRENGTH_MODEL();
		$xcv = new URA_XWRD_CHANGE_VIEW();
		$xcm = new URA_XWRD_CHANGE_MODEL();
		$styles = new URA_STYLES();
		$pum = new UPDATE_PASSWORD_MODEL();
		$puv = new UPDATE_PASSWORD_VIEW();
		$puc = new UPDATE_PASSWORD_CONTROLLER();
		// shortcode for custom password change form
		add_shortcode( 'change_password', array( &$xwrd, 'password_change_form' ) ); // shortcode for xwrd change page
		//add_action( 'register_new_user', array( &$xwrd, 'remove_default_password_nag' ), 10, 1 );
		// password change scripts and styles for URA templates that use password stregth meter
		add_action( 'xwrd_scripts_register', array( &$styles, 'register_password_scripts' ) );
		add_action( 'xwrd_scripts_load', array( &$styles, 'load_password_scripts' ) );
		add_action( 'password_update_page_load', array( &$pum, 'update_password_form_template' ) );
		add_action( 'xwrd_update_control', array( &$puc, 'update_password_control' ) );
		add_filter( 'xwrd_update_model', array( &$pum, 'update_xwrd_model' ) );
		add_action( 'xwrd_update_view', array( &$puv, 'update_xwrd_view' ), 10, 1 );
		add_action( 'lp_xwrd_fields', array( &$puv, 'lp_xwrd_fields_view' ) );
		add_action( 'xwrd_fields', array( &$puv, 'xwrd_fields_view' ) );
		add_filter( 'xwrd_update_actions', array( &$pum, 'update_password_actions_array' ), 10, 1 );
		unset( $styles, $pum, $puv );
		
		// Sets new password if user can enter own password on registration
		add_filter( 'random_password',  array( &$xwrd, 'csds_userRegAide_createNewPassword' ), 0, 1 ); // 
		
		add_filter( 'xwrd_set_options_update', array( &$pwdm, 'xwrd_strength_options_update' ), 10, 1 ); 
		add_action( 'xwrd_settings_view', array( &$xwrd_view, 'password_settings_view' ) ); 
		add_action( 'xwrd_chng_settings_view', array( &$xcv, 'password_change_settings_view' ) ); 
		add_filter( 'xwrd_chng_settings_update', array( &$xcm, 'pwrd_change_options_update' ), 10, 1 ); 
		add_filter( 'xwrd_db_exists', array( &$xwrd, 'check_xwrd_table_exists' ), 10, 1 );
		add_action( 'install_xwrd_db', array( &$xwrd, 'install_xwrd_databases' ) );
		add_filter( 'custom_password_strength', array( &$xwrd, 'xwrd_strength_verify' ), 10, 6 );
		add_filter( 'login_redirect', array( &$xwrd, 'non_admin_login_redirect' ), 10, 3 );
		add_filter( 'duplicate_verify', array( &$xwrd, 'xwrd_change_duplicate_verify' ), 10, 4 );
		add_action( 'template_redirect', array( &$xwrd, 'xwrd_chng_ssl_redirect' ) );
		add_filter( 'pre_post_link', array( &$xwrd, 'xwrd_chng_ssl'), 10, 3 );
		
		add_filter( 'lost_xwrd_email_link', array( &$xwrd, 'xwrd_change_lost_password_check' ), 10, 1 );
		// password reset text
		//add_filter( 'gettext', array( &$xwrd, 'remove_xwrd_reset_text' ), 10, 1 );
		add_filter( 'allow_password_reset', array( &$xwrd, 'xwrd_reset_disable' ), 10, 2 );
		add_filter( 'show_password_fields', array( &$xcm, 'xwrd_show_disable' ), 10, 2 );
		
		// login check for users needing to change password
		add_action( 'wp_authenticate', array( &$xwrd, 'xwrd_change_login_check' ), 1 );
		unset( $xwrd, $xcm, $pwdm, $xwrd_view, $customCSS );
		
		// lost password actions & filters
		$lpc = new LOST_PASSWORD_CONTROLLER();
		$lpm = new LOST_PASSWORD_MODEL();
		$lpv = new LOST_PASSWORD_VIEW();
		$sqc = new SECURITY_QUESTIONS_CONTROLLER();
		$sqm = new SECURITY_QUESTIONS_MODEL();
		$sqv = new SECURITY_QUESTIONS_VIEW();
		$emails = new URA_EMAIL_CLASS();
		
		add_action( 'password_reset', array( &$lpm, 'lost_xwrd_reset_email_meta' ), 10, 2 );
		add_filter( 'lostpassword_url', array( &$lpm, 'lost_xwrd_url' ), 10, 2 );
		add_action( 'xwrd_lost_control', array( &$lpc, 'lost_xwrd_control' ), 10, 1 );
		add_filter( 'xwrd_lost_email', array( &$emails, 'lost_xwrd_email' ), 10, 1 );
		add_filter( 'retrieve_password_message', array( &$emails, 'ura_lost_xwrd_approve' ), 10, 4 );
		add_filter( 'xwrd_lost_model', array( &$lpm, 'lost_xwrd_model' ), 10, 1 );
		add_action( 'xwrd_lost_view', array( &$lpv, 'lost_xwrd_view' ), 10, 1 );
		add_action( 'sq_settings_control', array( &$sqc, 'sec_questions_settings_controller' ), 10, 1 );
		add_filter( 'sq_settings_model', array( &$sqm, 'sec_questions_settings_model' ), 10, 1 );
		add_action( 'sq_settings_view', array( &$sqv, 'sec_questions_settings_view' ), 10, 0 );
		add_action( 'sq_rf_control', array( &$sqc, 'sec_questions_rf_controller' ), 10, 1 );
		add_filter( 'sq_rf_model', array( &$sqm, 'sec_questions_rf_model' ), 10, 1 );
		add_action( 'sq_rf_view', array( &$sqv, 'sec_questions_rf_view' ), 10, 1 );
		add_action( 'sq_profiles_control', array( &$sqc, 'sec_questions_profile_controller' ), 10, 1 );
		add_action( 'sq_profiles_view', array( &$sqv, 'sec_questions_profile_view' ), 10, 1 );
		add_action( 'sq_lp_view', array( &$sqv, 'sec_questions_lpf_view' ), 10, 1 );
		add_filter( 'security_questions_completed', array( &$sqm, 'missing_security_answers_alert' ), 10, 1 );
		add_action( 'show_security_question_alert', array( &$sqm, 'show_security_questions_user_alert' ), 10, 1 );
		add_action( 'admin_notices', array( &$sqm, 'user_sq_alert_msg' ), 10, 1 );
		add_action( 'admin_init', array( &$sqm, 'user_sq_alert_msg_ignore' ) );
		add_filter( 'lost_xwrd_actions_array', array( &$lpm, 'lost_password_actions_array' ), 10, 1 );
		//add_filter( 'display_security_question_alert', show_security_questions_user_alert
		// reg form & profile extra fields actions and filters
		// New Registration Form Actions
		$html = new URA_HTML();
		add_action( 'rf_textbox', array( &$html, 'reg_form_text' ), 10, 4 );
		add_action( 'rf_textarea', array( &$html, 'reg_form_textarea' ), 10, 4 );
		add_action( 'rf_radio', array( &$html, 'reg_form_radio' ), 10, 4 );
		add_action( 'rf_select', array( &$html, 'reg_form_select' ), 10, 4 );
		add_action( 'rf_checkbox', array( &$html, 'reg_form_checkbox' ), 10, 4 );
		add_action( 'rf_datebox', array( &$html, 'reg_form_datepicker' ), 10, 4 );
		add_action( 'rf_multiselect', array( &$html, 'reg_form_multi_select' ), 10, 4 );
		add_action( 'rf_number', array( &$html, 'reg_form_number' ), 10, 4 );
		add_action( 'rf_url', array( &$html, 'reg_form_url' ), 10, 4 );
		add_action( 'rf_hidden', array( &$html, 'reg_form_hidden' ), 10, 4 );
		
		// New Profile Page Actions
		add_action( 'profile_textbox', array( &$html, 'profile_textbox' ), 10, 5 );
		add_action( 'profile_textarea', array( &$html, 'profile_textarea' ), 10, 5 );
		add_action( 'profile_radio', array( &$html, 'profile_radio' ), 10, 5 );
		add_action( 'profile_select', array( &$html, 'profile_select' ), 10, 5 );
		add_action( 'profile_checkbox', array( &$html, 'profile_checkbox' ), 10, 5 );
		add_action( 'profile_datebox', array( &$html, 'profile_datepicker' ), 10, 5 );
		add_action( 'profile_multiselect', array( &$html, 'profile_multi_select' ), 10, 5 );
		add_action( 'profile_number', array( &$html, 'user_profile_number' ), 10, 5 );
		add_action( 'profile_url', array( &$html, 'profile_url' ), 10, 5 );
		unset( $html );
		
		//actions and filters for registration form settings options ( Registration Form Options )
		$rfom = new REGISTRATION_FORM_OPTIONS_MODEL();
		$nuam = new URA_NUA_SETTINGS_MODEL();
		$xsov = new XWRD_STRENGTH_OPTIONS_VIEW();
		$amv = new URA_AGREEMENT_VIEW();
		$rdv = new URA_REDIRECTS_VIEW();
		$mpv = new URA_MATH_PROBLEM_VIEW();
		$nuav = new URA_NUA_SETTINGS_VIEW();
		$ptv = new URA_PROFILE_TITLE_VIEW();
		add_filter( 'rf_msg_update', array( &$rfom, 'reg_form_message_update' ), 10, 1 );
		add_filter( 'rf_redirects', array( &$rfom, 'reg_form_redirects' ), 10, 1 );
		add_filter( 'rf_agreement', array( &$rfom, 'reg_form_agree' ), 10, 1 );
		add_filter( 'rf_anti_spam', array( &$rfom, 'reg_form_anti_spam_math' ), 10, 1 );
		add_filter( 'rf_nua', array( &$nuam, 'new_user_approval_settings_model' ), 10, 1 );
		add_filter( 'rf_prof_title', array( &$rfom, 'profile_title' ), 10, 1 );
		add_filter( 'rf_options_support', array( &$rfom, 'rf_options_support_model' ), 10, 1 );
		add_action( 'rf_options_view_1', array( &$xsov, 'xwrd_strength_settings_view' ), 10, 1 );
		add_action( 'rf_options_view_2', array( &$rdv, 'rf_redirect_view' ), 10, 1 );
		add_action( 'rf_options_view_3', array( &$amv, 'agreement_msg_view' ), 10, 1 );
		add_action( 'rf_options_view_4', array( &$mpv, 'anti_spam_math_view' ), 10, 1 );
		add_action( 'rf_options_view_5', array( &$nuav, 'new_user_approval_settings_view' ), 10, 1 );
		add_action( 'rf_options_view_6', array( &$ptv, 'prof_title_view' ), 10, 1 );
		unset( $rfom, $xsov, $amv, $rdv );
		
		$rfcm = new REGISTRATION_FORM_CSS_MODEL();
		$rfmm = new REGISTRATION_FORM_MSGS_MODEL();
		$rfcv = new REGISTRATION_FORM_CSS_VIEW();
		$rfmv = new REGISTRATION_FORM_MSGS_VIEW();
		add_filter( 'rf_css_update', array( &$rfcm, 'reg_form_css_updater' ), 10, 1 );
		add_filter( 'rf_msgs_update', array( &$rfmm, 'reg_form_msgs_updater' ), 10, 1 );
		add_action( 'rf_css_view', array( &$rfcv, 'rf_css_options_view' ), 10 );
		add_action( 'rf_msg_settings_view', array( &$rfmv, 'rf_msgs_view' ), 10, 1 );
		unset( $rfcm, $rfmm, $rfcv, $rfmv );
		
		// new user approve actions and filters
		//$nuafc = new NUA_FIELDS_CONTROLLER();
		//$nuafv = new NUA_FIELDS_VIEW();
		//add_action( 'approve_new_user_fields', array( &$nuafc, 'unapproved_users_fields_contoller' ) );
		//add_action( 'nua_fields_view', array( &$nuafv, 'nua_field_options_view' ) );
		unset( $nuafc );
		
		// members actions & filters
		$so = new URA_SCREEN_OPTIONS();
		$uma = new URA_MEMBERS_ACTIONS();
		$ura_admin = new URA_MEMBERS_ADMIN();
		 
		add_filter( 'screen_settings', array( &$so, 'append_screen_settings' ), 10, 2);
		add_action( 'admin_print_scripts', array( &$so, 'add_autosave_script' ) );
		add_action( 'email_verification', array( &$uma, 'verify_email_account' ), 10, 1 );
		add_action( 'update_member_key', array( &$uma, 'update_user_activation_key' ), 10, 1 );
		
		add_action( 'update_member_status', array( &$uma, 'update_user_status_field' ), 10, 1 );
		add_filter( 'email_confirmation',	array( &$uma, 'update_user_status_field' ), 10, 1 );
		add_action( 'ura_signups_admin_load', array( &$ura_admin, 'signups_admin_index' ), 10, 2 );
		//signups_admin_manage
		add_action( 'user_actions', array( &$ura_admin, 'signups_admin_manage' ), 10, 2 );
		// 
		
		add_filter( 'create_user_key', array( &$emails, 'create_activation_key' ), 10, 1 );
		add_filter( 'user_password_key', array( &$emails, 'password_activation_key_msg' ), 10, 1 );
		add_filter( 'user_table_views', array( &$uma, 'get_user_table_views' ), 10, 1 );
		$nua_list = new URA_USERS_LIST_TABLE_NUA();
		add_filter( 'modify_users_views', array( &$nua_list, 'get_parent_views' ), 10, 1 );
		add_action( 'new_user_email', array( &$emails, 'ura_new_user_notification' ), 10, 1 );
		add_action( 'admin_email', array( &$emails, 'ura_admin_new_user_notification_email' ), 10, 1 );
		add_action( 'new_user_approved', array( &$emails, 'ura_new_user_approved_notification' ), 10, 1 );
		add_action( 'email_verified_admin_notice', array( &$emails, 'admin_email_verified_notification' ), 10, 1 );
		add_action( 'user_email_verification', array( &$emails, 'user_email_verified' ), 10, 1 );
		add_action( 'set_password_email_notice', array( &$emails, 'set_password_email_instructions' ), 10, 1 );
		add_action( 'validate_password_reset', array( &$uma, 'xwrd_reset_key_set' ), 10, 2 );
		//add_filter( 'password_reset_key_expired', array( &$uma, 'xwrd_reset_key_set' ), 10, 2 );
		add_action( 'signup_complete_email', array( &$emails, 'registration_completed_email_instructions' ), 10, 1 );
		add_action( 'activate_new_user_account', array( &$uma, 'clear_user_status_field' ), 10, 1 );
		add_filter( 'process_user_link_action', array( &$uma, 'process_members_link_actions' ), 10, 2 );
		unset( $so, $uma, $ura_admin );
		
		/*
		 * page template actions & filters  
		 * @since 1.5.3.8
		*/
		$tm = new URA_TEMPLATE_MODEL();
		add_action( 'page_template_header', array( $tm, 'template_model_page_header' ), 10, 1 );
		add_action( 'page_template_sidebar', array( $tm, 'template_model_page_sidebar' ), 10, 1 );
		add_action( 'page_template_footer', array( $tm, 'template_model_page_footer' ), 10, 1 );
		add_filter( 'page_template_header_filter', array( $tm, 'template_model_filter_top' ), 10, 1 );
		add_filter( 'page_template_sidebar_filter', array( $tm, 'template_model_filter_sidebar' ), 10, 1 );
		add_filter( 'page_template_footer_filter', array( $tm, 'template_model_filter_footer' ), 10, 1 );
		
		// Filters for user input into extra and new fields
		add_filter( 'pre_user_first_name', 'esc_html' ); // Wordpress Filters
		add_filter( 'pre_user_first_name', 'strip_tags' ); // Wordpress Filters
		add_filter( 'pre_user_first_name', 'trim' ); // Wordpress Filters
		add_filter( 'pre_user_first_name', 'wp_filter_kses' ); // Wordpress Filters
		add_filter( 'pre_user_last_name', 'esc_html' ); // Wordpress Filters
		add_filter( 'pre_user_last_name', 'strip_tags' ); // Wordpress Filters
		add_filter( 'pre_user_last_name', 'trim' ); // Wordpress Filters
		add_filter( 'pre_user_last_name', 'wp_filter_kses' ); // Wordpress Filters
		add_filter( 'pre_user_nickname', 'esc_html' ); // Wordpress Filters
		add_filter( 'pre_user_nickname', 'strip_tags' ); // Wordpress Filters ura_users_column_defaults
		add_filter( 'pre_user_nickname', 'trim' ); // Wordpress Filters
		add_filter( 'pre_user_nickname', 'wp_filter_kses' ); // Wordpress Filters
		add_filter( 'pre_user_url', 'esc_url' ); // Wordpress Filters
		add_filter( 'pre_user_url', 'strip_tags' ); // Wordpress Filters
		add_filter( 'pre_user_url', 'trim' ); // Wordpress Filters
		add_filter( 'pre_user_url', 'wp_filter_kses' ); // Wordpress Filters
		add_filter( 'pre_user_description', 'esc_html' ); // Wordpress Filters
		add_filter( 'pre_user_description', 'strip_tags' ); // Wordpress Filters
		add_filter( 'pre_user_description', 'trim' ); // Wordpress Filters
		add_filter( 'pre_user_description', 'wp_filter_kses' ); // Wordpress Filters
		
	}
}