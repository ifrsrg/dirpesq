<?php

/**
 * Class URA_OPTIONS
 *
 * @category Class
 * @since 1.2.0
 * @updated 1.5.3.3
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_OPTIONS
{

	/**	
	 * Function csds_userRegAide_DefaultOptions
	 * Adds the new default options for the options fields on admin forms
	 * @since 1.2.0
	 * @updated 1.5.3.0
	 * @access public
	 * @handles action 'init' Line 118 user-registration-aide.php
	 * @params 
	 * @returns string $msg ( results of function updated or error  message to display to user )
	*/
	
	function csds_userRegAide_DefaultOptions(){
		
		global $wpdb;
		$options = array();
		$dw_fields = array();
		$options = get_option('csds_userRegAide_Options');
		
		if( empty( $options ) ){
		
			$options = $this->csds_userRegAide_defaultOptionsArray();
			update_option( 'csds_userRegAide_Options', $options );
			
			// For updates from older versions
			$support = get_option( 'csds_userRegAide_support' );
			if( !empty( $support ) ){
				delete_option( 'csds_userRegAide_support' );
			}
			$link = get_option( 'csds_display_link' );
			if( !empty( $link ) ){
				delete_option( 'csds_display_link' );
			}
			$name = get_option( 'csds_display_name' );
			if( !empty( $name ) ){
				delete_option( 'csds_display_name' );
			}
			$dbv = get_option( 'csds_userRegAide_dbVersion' );
			if( !empty( $dbv ) ){
				delete_option( 'csds_userRegAide_dbVersion' );
			}
		}else{
			$options = get_option( 'csds_userRegAide_Options' );
		}
		
	}
	
	/**	
	 * Function csds_userRegAide_defaultOptionsArray
	 * Creates Array for all the new default options for the options fields on admin forms
	 * @since 1.2.0
	 * @updated 1.5.3.3
	 * @access public
	 * @handles line 67, 407 & 456 &$this
	 * @params 
	 * @returns array $csds_userRegAide_Options - Default options array
	*/
	
	function csds_userRegAide_defaultOptionsArray(){
		global $current_site;
		$site_name = ( string ) get_bloginfo('name');
		$site_name_2 = ( string ) $site_name;
		$s = ( string ) '';
		$login_url = ( string ) esc_url_raw( wp_login_url() );
		if( !is_multisite() ){
			$site_name = get_option('blogname');
			$url = wp_login_url();
			$login_url = $url;
			$registered_url = $url.'?checkemail=registered';
		}else{
			$site_name = $current_site->site_name;
			$url_login = network_site_url('/wp-login.php');
			$signup_url = network_site_url('/wp-signup.php');
			$login_url = $url_login;
			$registered_url = $signup_url;
		}
		$csds_userRegAide_Options = array(
			"csds_userRegAide_db_Version"		=> "1.5.3.2",
			"database_updated"					=> "2",
			"select_pass_message" 				=> "2",
			"password"							=> "2",
			"registration_form_message" 		=> __( "You can use the password you entered here to log in right away, and for your reference, your registration details will be emailed after signup", "user-registration-aide" ),
			"agreement_message" 				=> __( "I have read and understand and agree to the terms and conditions of the guidelines/agreement policy required for this website provided in the link below", "user-registration-aide" ),
			"empty"								=> __( "No password Entered!", "user-registration-aide" ),
			"short" 							=> __( "Password Entered is too Short!", "user-registration-aide" ), 
			"bad" 								=> __( "Password Entered is Bad, Too Weak", "user-registration-aide" ),
			"good" 								=> __( "Password Entered is fairly tough and is good to accept", "user-registration-aide" ),
			"strong" 							=> __( "Password Entered is very strong!", "user-registration-aide" ),
			"mismatch" 							=> __( "Password Entered does not match Password Confirm! Try Again Please!", "user-registration-aide" ),
			"show_support" 						=> "2",
			"support_display_link" 				=> "http://creative-software-design-solutions.com/#axzz24C84ExPC",
			"support_display_name" 				=> "Creative Software Design Solutions",
			"show_logo" 						=> "2",
			"logo_url" 							=> esc_url_raw( home_url( "/wp-admin/images/wordpress-logo.png" ) ),
			"logo_height" 						=> "274px",
			"logo_width" 						=> "63px",
			"show_background_image" 			=> "2",
			"background_image_repeat" 			=> "1",
			"hor_bckgrnd_image_repeat" 			=> "2",
			"background_image_position"			=> "center_top",
			"background_image_url" 				=> "",
			"show_background_color" 			=> "2",
			"reg_background_color" 				=> "#FFFFFF",
			"show_reg_form_page_color" 			=> "2",
			"reg_form_page_color" 				=> "#FFFFFF",
			"show_reg_form_page_image" 			=> "2",
			"reg_form_page_image_repeat" 		=> "1",
			"reg_form_page_image" 				=> "",
			"show_login_text_color" 			=> "2",
			"login_text_color" 					=> "#BBBBBB",
			"hover_text_color" 					=> "#FF0000",
			"show_shadow"						=> "2",
			"shadow_size" 						=> "0px",
			"shadow_color" 						=> "#FFFFFF",
			"change_logo_link" 					=> "2",
			"use_custom_lf_text_size"			=> "2",
			"custom_lf_text_size"				=> "14",
			"use_custom_rf_text_size"			=> "2",
			"custom_rf_text_size" 				=> "14",
			"show_custom_agreement_link" 		=> "2",
			"agreement_title" 					=> __( "Agreement Policy", "user-registration-aide" ),
			"show_custom_agreement_message" 	=> "2",
			"show_custom_agreement_checkbox" 	=> "2",
			"new_user_agree" 					=> 	"2",
			"designate_required_fields"			=>	"1",
			"reg_form_use_colon"				=>  "1",
			"agreement_link" 					=> esc_url_raw( site_url() ),
			"show_login_message" 				=>	"2",
			"login_message"						=>	__( "Welcome to our site! Please login for our site here!", 'user-registration-aide' ),
			"reg_top_message"					=>	__( "Welcome to our site! Please register for our site here!", 'user-registration-aide' ),
			"login_messages_login" 				=>	__( "Extra Login messages", 'user-registration-aide' ),
			"login_messages_lost_password" 		=>	__( "Please enter your username(login name) or email address here. You will then soon receive a link to create a new password via email!", "user-registration-aide" ),
			"login_messages_logged_out" 		=>	__( "Thank you for visiting our site! You are now logged out", "user-registration-aide" ),
			"login_messages_registered" 		=>	__( "Thank you for registering for our site! You account is now active!", "user-registration-aide" ),
			"reset_password_messages_security"	=> 	__( "Enter your new password here and confirm it, and enter your correct security question and answer, if you don't have one, just ignore that step for now and after you complete this, go to your profile and add a security question and answer to your profile to improve your personal security as well as our websites! Thank you!", "user-registration-aide" ),
			"reset_password_messages_normal"	=> 	__( "Enter your new password below and confirm it, Thank you!", "user-registration-aide" ),
			"reset_password_confirm"			=>	__( "You may now check your email for a confirmation link to reset your password!", "user-registration-aide" ),
			"reset_password_success_security" 	=>	__( 'You have successfully reset your password! You may now login again with your new password!', "user-registration-aide" ),
			"reset_password_success_normal"	  	=>	__( 'You have successfully changed your password! You may now login again with your new password!', "user-registration-aide" ),
			
			// security question stuff for future use if wordpress ever adds the appropriate actions ??? -----------------------------------------------
			"add_security_question"				=>	"2",
			"rp_fill_in_security_question"		=>	__( "You haven't added your security question and security answer yet, please do so on your profile page after you have finished resetting your password!", "user-registration-aide" ),
			"fill_in_security_question_answer"	=>	__( "You haven't added your security question and security answer yet, please do so on your profile page to improve your personal security!", "user-registration-aide" ),
			"fill_in_security_question"			=>	__( "You haven't added your security question yet, please do so on your profile page to improve your personal security!", "user-registration-aide" ),
			"fill_in_security_answer"			=>	__( "You need to enter your security answer for your security question otherwise you won't be able to reset your password without an administrators help!", "user-registration-aide" ),
			// end security question stuff -----------------------------
			
			"activate_anti_spam"				=>	"2",
			"division_anti_spam"				=>	"1",
			"multiply_anti_spam"				=>	"1",
			"minus_anti_spam"					=>	"1",
			"addition_anti_spam"				=>	"1",
			"activate_now"						=>	"2",
			"activation_message"				=>	__( "Welcome to our site! Your account is now activated!", "user-registration-aide" ),
			"ms_activate_now"					=> "2",
			"user_password"						=> "0",
			"ms_user_activation_message" 		=> __( 'Your user account is now activated for our site!, you may proceed and login now!', 'user-registration-aide' ), 
			"ms_activate_blog_now"				=> "2",
			"ms_non_activation_now"				=>	"2",
			"ms_non_activation_message"			=>	__( "Before you can start using this site and your new username, you must activate it by checking your email inbox and clocking on the activation link givern. *** If you do not activate your user account within two days, you will have to sign up again! ***", "user-registration-aide" ),
			"wp_user_notification_message"		=>	__( "Thank you for registering with us, Here are your new login credentials: ", 'user-registration-aide' ),
			"redirect_registration"				=>	"2",
			"registration_redirect_url"			=> 	esc_url_raw( $registered_url ),
			"redirect_login"					=>	"2",
			"login_redirect_url"				=>	esc_url_raw( admin_url() ),
			"change_profile_title"				=>	"2",
			"profile_title"						=>	__( "User Registration Aide Additional Fields", "user-registration-aide" ),
			"show_dashboard_widget"				=>	"1",
			"dwf1_key"							=>	"user_nicename",
			"dwf1"								=>	__( "Username", "user-registration-aide" ),
			"dwf1_order" 						=>	"1",
			"dwf2_key"							=>	"user_email",
			"dwf2"								=>	__( "Email", "user-registration-aide" ),
			"dwf2_order" 						=>	"2",
			"dwf3_key"							=>	"first_name",
			"dwf3"								=>	__( "First Name", "user-registration-aide" ),
			"dwf3_order" 						=>	"3",
			"dwf4_key"							=>	"last_name",
			"dwf4"								=>	__( "Last Name", "user-registration-aide" ),
			"dwf4_order" 						=>	"4",
			"dwf5_key"							=>	"roles",
			"dwf5"								=>	__( "Role", "user-registration-aide" ),
			"dwf5_order" 						=>	"5",
			"default_xwrd_strength"				=>	"2",
			"custom_xwrd_strength"				=>	"2",
			"require_xwrd_length"				=>	"1",
			"xwrd_length"						=>	"8",
			"xwrd_sc"							=>	"1",
			"xwrd_numb"							=>	"1",
			"xwrd_uc"							=>	"1",
			"xwrd_lc"							=>	"1",
			"xwrd_require_change"				=>	"2",
			"xwrd_change_on_signup"				=>	"2",
			"xwrd_change_interval"				=>	"180",
			"xwrd_duplicate_times"				=>	"3",
			"xwrd_change_ssl"					=>	"2",
			"allow_xwrd_reset"					=>	"1",
			"show_password_fields"				=>	"1",
			"xwrd_chng_title"					=>	__( "Change Password", "user-registration-aide" ),
			"xwrd_change_name"					=>	"change-password",
			"xwrd_chng_email_url"				=>	"?action=new-register",
			"xwrd_chng_exp_url"					=>	"?action=expired-password",
			"xwrd_chng_nc_url"					=>	"?action=password-never-changed",
			"lost_xwrd_table"					=>	"2",
			"updated"							=>	"2",
			"new_user_email_verify"				=>	"2",
			"custom_display_name"				=>	"2",
			"custom_display_field"				=>	"first_last_name",
			"custom_display_role"				=>	"2",
			"display_name_role"					=>	"all_roles",
			"show_profile_disp_name"			=>	"2",
			"default_user_role"					=>	"subscriber",
			"tbl_background_color"				=>	"#f2f2f2",
			"tbl_color"							=>	"#000000",
			"tbl_border-width"					=>	"1px",
			"border-style"						=>	"solid",
			"border-color"						=>	"#666666",
			"border-spacing"					=>	"1px",
			"border-collapse"					=>	"separate",
			"div_stuffbox_bckgrd_color"			=>	"#f2f2f2",
			"tbl_padding"						=>	"1px",
			"math_num1"							=>	"0",
			"math_num2"							=>	"0",
			"math_oper"							=>	"eee",
			"math_answer"						=>	"gssad3aqwet",
			"designate_required_fields"			=>	"1",
			"nua_pre_register_msg"				=>	sprintf( __( 'After you register, your request will be sent to the site administrator for approval. You will then receive an email with further instructions.', 'user-registration-aide' ) ),
			"nua_post_register_msg_1"			=>	sprintf( __( 'An email has been sent to the site administrator. The administrator will review the information that has been submitted and either approve or deny your request.', 'user-registration-aide' ) ),
			"nua_post_register_msg_2"			=>	sprintf( __( 'You will receive an email with instructions on what you will need to do next. Thanks for your patience.', 'user-registration-aide' ) ),
			"lph_enabled"						=>	"2",
			"lph_db_version"					=>	"1.6.0.0",
			"login_attempts"					=>	"5",
			"retry_every"						=>	"5",
			"lockdown_length"					=>	"30",
			"lockout_invalid_usernames"			=>	"2",
			"hide_login_errors"					=>	"2",
			"protected_by"						=>	"1",
			"new_user_approve"					=>	"2",
			"verify_email"						=>	"2",
			"buddy_press_approval"				=>	"2",
			"buddy_press_registration"			=>	"2",
			"buddy_press_fields_sync"			=>	"2",
			"ura_fields_sync"					=>	"2",
			"post_column_created"				=>	"2",
			"database_update"					=>	"2",
			"pages_created"						=>	"2",
			"multiple_db_prefixes"				=>	"2",
			"db_prefixes"						=>	"",
			"user_roles_updated"				=>	"2",
			"designate_required_fields"			=>	"1",
			"reg_form_use_colon"				=>  "1",
			"show_asterisk"						=>  "1",
			"table_columns_updated"				=>	"2",
			"reg_fields_updated"				=>	"2",
			"template_slug_change"				=>	"2"
		
		);
		return $csds_userRegAide_Options;
	}
	
	/**	
	 * Function csds_userRegAide_fill_known_fields
	 * Fills array of known fields
	 * @since 1.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @handles action 'init' Line 135 user-registration-aide.php and multiple calls 
	 * @params 
	 * @returns array $csds_userRegAide_Options - Default options array
	*/

	function csds_userRegAide_fill_known_fields(){
	
		$csds_userRegAide_knownFields = get_option('csds_userRegAide_knownFields');
		$csds_userRegAideFields = get_option('csds_userRegAide_knownFields'); 
		$new_fields = get_option('csds_userRegAide_NewFields'); 
		if( !empty( $csds_userRegAide_knownFields ) && !empty( $csds_userRegAideFields ) ){
			$csds_userRegAide_knownFields = array();
			$csds_userRegAideFields = array();
		}
		
		$csds_userRegAide_knownFields = array(
			'first_name'	=> __( 'First Name', 'user-registration-aide' ),
			'last_name'		=> __( 'Last Name', 'user-registration-aide' ),
			'nickname'		=> __( 'Nickname', 'user-registration-aide' ),
			'user_url'		=> __( 'Website', 'user-registration-aide' ),
			'aim'			=> __( 'AIM', 'user-registration-aide' ),
			'yim'			=> __( 'Yahoo IM', 'user-registration-aide' ),
			'jabber'		=> __( 'Jabber / Google Talk', 'user-registration-aide' ),
			'description'   => __( 'Biographical Info', 'user-registration-aide' ),
			'user_pass'		=> __( 'Password', 'user-registration-aide' )
		);
		
		if( empty( $csds_userRegAideFields ) ){
			if( empty( $new_fields ) ){
				update_option( "csds_userRegAideFields", $csds_userRegAide_knownFields );
			}else{
				$all_fields = array();
				$all_fields = $csds_userRegAide_knownFields + $new_fields;
				update_option( "csds_userRegAideFields", $all_fields );
			}
		}else{
		 
		}
		update_option( "csds_userRegAide_knownFields", $csds_userRegAide_knownFields );
		
		if( !empty( $new_fields ) ){
			foreach( $csds_userRegAideFields as $key1 => $field1 ){
				foreach( $new_fields as $key => $field ){
					if( !$key1 == $key ){
						$csds_userRegAideFields[$key] = $field;
					}
				}
			}
		}
						
		// Updates the field order set to default by order entered into program
		
		if( empty( $csds_userRegAide_fieldOrder ) && !empty( $new_fields ) ){
			$this->csds_userRegAide_update_field_order();
		}
		
		if( empty( $csds_userRegAide_registrationFields ) ){
			$this->csds_userRegAide_updateRegistrationFields();
		}
		return true;	
	}
	
	/**	
	 * Function csds_userRegAide_update_known_fields
	 * Updates array of known fields to new version
	 * @since 1.5.0.4
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns boolean true or false for success or failure
	*/

	function csds_userRegAide_update_known_fields(){
	
		$csds_userRegAide_knownFields = get_option( 'csds_userRegAide_knownFields' );
		$csds_userRegAideFields = get_option( 'csds_userRegAide_knownFields' ); 
		$new_fields = get_option( 'csds_userRegAide_NewFields' ); 
		if( !empty( $csds_userRegAide_knownFields ) && !empty( $csds_userRegAideFields )){
			$csds_userRegAide_knownFields = array();
			$csds_userRegAideFields = array();
		}
		
		$csds_userRegAide_knownFields = array(
			'first_name'	=> __( 'First Name', 'user-registration-aide' ),
			'last_name'		=> __( 'Last Name', 'user-registration-aide' ),
			'nickname'		=> __( 'Nickname', 'user-registration-aide' ),
			'user_url'		=> __( 'Website', 'user-registration-aide' ),
			'aim'			=> __( 'AIM', 'user-registration-aide' ),
			'yim'			=> __( 'Yahoo IM', 'user-registration-aide' ),
			'jabber'		=> __( 'Jabber / Google Talk', 'user-registration-aide' ),
			'description'   => __( 'Biographical Info', 'user-registration-aide' ),
			'user_pass'		=> __( 'Password', 'user-registration-aide' )
		);
		
		if( empty( $csds_userRegAideFields ) ){
			if( empty( $new_fields ) ){
				update_option( "csds_userRegAideFields", $csds_userRegAide_knownFields );
			}else{
				$all_fields = array();
				$all_fields = $csds_userRegAide_knownFields + $new_fields;
				update_option( "csds_userRegAideFields", $all_fields );
			}
		}else{
			$all_fields = array();
			$all_fields = $csds_userRegAide_knownFields + $new_fields;
			update_option( "csds_userRegAideFields", $all_fields );
		}
		
		update_option( "csds_userRegAide_knownFields", $csds_userRegAide_knownFields );
		
		if( !empty( $csds_userRegAide_NewFields ) ){
			foreach( $csds_userRegAideFields as $key1 => $field1 ){
				foreach( $csds_userRegAide_NewFields as $key => $field ){
					if( !$key1 == $key ){
						$csds_userRegAideFields[$key] = $field;
					}
				}
			}
		}
						
		// Updates the field order set to default by order entered into program
		
		if( empty( $csds_userRegAide_fieldOrder ) && !empty( $csds_userRegAide_NewFields ) ){
			$this->csds_userRegAide_update_field_order();
		}
		
		if( empty( $csds_userRegAide_registrationFields ) ){
			$this->csds_userRegAide_updateRegistrationFields();
		}
		
		return true;
		
	}

	/**	
	 * Function csds_userRegAide_updateRegistrationFields
	 * Updates the registration fields array and storage method in options db upgrade in 1.1.0
	 * @since 1.1.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/

	function csds_userRegAide_updateRegistrationFields(){

		global $csds_userRegAide_knownFields, $csds_userRegAide_getOptions, $csds_userRegAide_NewFields, $csds_userRegAide_fieldOrder;
		$csds_userRegAide_knownFields = array();
		$csds_userRegAide_newFields = array();
		$csds_userRegAide_fieldOrder = get_option('csds_userRegAide_fieldOrder');
		$csds_userRegAide_NewFields = get_option('csds_userRegAide_NewFields');
		$csds_userRegAide_knownFields = get_option('csds_userRegAide_knownFields');
		$csds_userRegAideFields = get_option('csds_userRegAideFields');
		$csds_userRegAide_registrationFields = get_option('csds_userRegAide_registrationFields');
				
		// Checks to see if older version of additional fields exists and if so transfers them to new option
		
		if( !empty( $csds_userRegAide_getOptions["additionalFields"] ) ){
			foreach( $csds_userRegAide_getOptions["additionalFields"] as $key => $value ){
				if( !empty( $csds_userRegAide_knownFields ) ){
					foreach( $csds_userRegAide_knownFields as $key1 => $value1 ){
						if($value == $key1){
							$csds_userRegAide_registrationFields[$key1] = $value1;
							$csds_userRegAide_registrationFields = $csds_userRegAide_registrationFields;
							update_option("csds_userRegAide_registrationFields", $csds_userRegAide_registrationFields);
						}
					}
				}
				if( !empty( $csds_userRegAide_NewFields ) ){
					foreach( $csds_userRegAide_NewFields as $key2 => $value2 ){
						if( $value == $key2 ){
							$csds_userRegAide_registrationFields[$key2] = $value2;
							$csds_userRegAide_registrationFields = $csds_userRegAide_registrationFields;
							update_option("csds_userRegAide_registrationFields", $csds_userRegAide_registrationFields);
						}
					}
				}
			}
		}
		if( !empty( $csds_userRegAide_knownFields ) && !empty( $csds_userRegAide_NewFields ) ){
			$csds_userRegAideFields = array_merge( $csds_userRegAide_knownFields, $csds_userRegAide_NewFields );
			update_option( 'csds_userRegAideFields', $csds_userRegAideFields );
		}elseif( !empty( $csds_userRegAide_knownFields ) && empty($csds_userRegAide_NewFields ) ){
			$csds_userRegAideFields = $csds_userRegAide_knownFields;
			update_option( 'csds_userRegAideFields', $csds_userRegAideFields );
		}else{
		
		}
			
		
	}

	/**	
	 * Function csds_userRegAide_update_field_order
	 * Fills and arranges the order of new fields based on order of creation initially
	 * @since 1.1.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/

	function csds_userRegAide_update_field_order(){
		
		$csds_userRegAide_NewFields = get_option( 'csds_userRegAide_NewFields' );
		$csds_userRegAide_knownFields = get_option( 'csds_userRegAide_knownFields' );
		$csds_userRegAideFields = get_option( 'csds_userRegAideFields' );
		$csds_userRegAide_fieldOrder = get_option( 'csds_userRegAide_fieldOrder' );
		if( empty( $csds_userRegAide_fieldOrder ) ){
			if( !empty( $csds_userRegAide_NewFields ) ){
				$i = ( int ) 1;
				foreach( $csds_userRegAide_NewFields as $key => $field ){
					$csds_userRegAide_fieldOrder[$key] = $i;
					$i++;
				}
			}
			$csds_userRegAideFields = array();
			$csds_userRegAideFields = $csds_userRegAide_knownFields + $csds_userRegAide_NewFields;
			update_option( 'csds_userRegAide_fieldOrder', $csds_userRegAide_fieldOrder );
		}else{
			$csds_userRegAide_fieldOrder = array();
			update_option( 'csds_userRegAide_fieldOrder', $csds_userRegAide_fieldOrder );
			if( !empty( $csds_userRegAide_NewFields ) ){
				$i = ( int ) 1;
				foreach( $csds_userRegAide_NewFields as $key => $field ){
					$csds_userRegAide_fieldOrder[$key] = $i;
					$i++;
				}
			}
			$csds_userRegAideFields = array();
			if( !empty( $csds_userRegAide_NewFields ) ){
				$csds_userRegAideFields = array_merge( $csds_userRegAide_knownFields, $csds_userRegAide_NewFields );
			}else{
				$csds_userRegAideFields = $csds_userRegAide_knownFields;
			}
			update_option( 'csds_userRegAideFields', $csds_userRegAideFields );
			update_option( 'csds_userRegAide_fieldOrder', $csds_userRegAide_fieldOrder );
		}
	}

	/**	
	 * Function csds_userRegAide_updateOptions
	 * Updates Database Options
	 * @since 1.2.5
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/

	function csds_userRegAide_updateOptions(){
		global $wpdb;
		$exist_options = array();
		$exist_options = get_option( 'csds_userRegAide_Options' );
		$update = array();
		$update = get_option( 'csds_userRegAide_Options' );
		$default_options = array();
		$default_options = $this->csds_userRegAide_defaultOptionsArray();
		
		$return = ( boolean ) false;
		if( empty( $exist_options ) ){
			$this->csds_userRegAide_DefaultOptions();
			$exist_options = get_option( 'csds_userRegAide_Options' );
		}else{
			if( !empty( $default_options ) && !empty( $exist_options ) && is_array( $default_options ) && is_array( $exist_options ) ){
				foreach( $default_options as $key => $value ){
					foreach( $exist_options as $key1 => $value1 ){
						if( !array_key_exists( $key, $exist_options ) ){
							if( $key == 'database_updated' ){
								$exist_options[$key] = "1";
							}else{
								$exist_options[$key] = $value;
							}
						}
						if( $key == $key1 ){
							if( !empty( $value1 ) ){
								if( $key1 == 'csds_userRegAide_db_Version' ){
									if( $value1 == "1.5.0.1" ){
										$return = $this->csds_userRegAide_update_known_fields();
										if( $return == true ){
											$exist_options[$key1] = "1.5.3.2";
										}
									}
									if( $value1 != '1.5.2.0' ){
										$table = $wpdb->prefix . "ura_fields";
										if( $wpdb->get_var("SHOW TABLES LIKE '$table'") != $table ) {
											$fdb = new FIELDS_DATABASE();
											$fdb->update_ura_fields_database();
										}
										$exist_options[$key1] = "1.5.3.2";
									}
									if( $value1 != '1.5.3.2' ){
										if( $exist_options['xwrd_require_change'] == 1 ){
											$db_exists = apply_filters( 'xwrd_db_exists', false );
											if( empty( $db_exists ) || $db_exists == false ){
												$xwrd = new PASSWORD_FUNCTIONS();
												$xwrd->install_xwrd_databases();
												unset( $xwrd );
											}
										}
										$exist_options[$key1] = "1.5.3.2";
									}
																		
								}elseif( $key1 == 'database_updated' ){
									$exist_options['database_updated'] = "1"; 
								}elseif( $key1 == 'updated' ){
									$exist_options[$key1] = "1";
								}elseif( $key1 == 'login_message' ){
									$lm = "Welcome to " . get_bloginfo('name') . "! Please login for our site here!";
									if( $value1 == $lm ){
										$exist_options["login_message"] = __( "Welcome to our site! Please login for our site here!", 'user-registration-aide' );
									}
								}elseif( $key1 == 'reg_top_message' ){
									$rtm = "Welcome to " . get_bloginfo('name') . "! Please register for our site here!";
									if( $value1 == $rtm ){
										$exist_options["reg_top_message"] = __( "Welcome to our site! Please register for our site here!", 'user-registration-aide' );
									}
								}elseif( $key1 == 'login_messages_logged_out' ){
									$lmlo = "Thank you for visiting us at  " . get_bloginfo('name') . "! You are now logged out";
									if( $value1 == $lmlo ){
										$exist_options["login_messages_logged_out"] = __( "Thank you for visiting our site! You are now logged out", 'user-registration-aide' );
									}
								}elseif( $key1 == 'login_messages_registered' ){
									$lmr = "Thank you for registering with us at  " . get_bloginfo('name') . "! You account is now active!";
									if( $value1 == $lmr ){
										$exist_options["login_messages_registered"] = __( "Thank you for registering for our site! You account is now active!", 'user-registration-aide' );
									}
								}elseif( $key1 == 'tbl_background_color' ){
									if( $value1 == '#CCCCFF' ){
										$exist_options['tbl_background_color'] = '#f2f2f2';
									}
								}elseif( $key1 == 'tbl_border-width' ){
									if( $value1 == '5px' ){
										$exist_options['tbl_border-width'] = '1px';
									}
								}elseif( $key1 == 'border-style' ){
									if( $value1 == 'ridge' ){
										$exist_options['border-style'] = 'solid';
									}
								}elseif( $key1 == 'border-spacing' ){
									if( $value1 == '5px' ){
										$exist_options['border-spacing'] = '1px';
									}
								}elseif( $key1 == 'div_stuffbox_bckgrd_color' ){
									if( $value1 == '#CCCCFF' ){
										$exist_options['div_stuffbox_bckgrd_color'] = '#f2f2f2';
									}
								}elseif( $key1 == 'tbl_padding' ){
									if( $value1 == '5px' ){
										$exist_options['tbl_padding'] = '1px';
									}
								}else{
									if( $value1 != $value ){
										$exist_options[$key1] = $value1;
									}else{
										$exist_options[$key1] = $value1;
									}
								}
							}else{
								
								$exist_options[$key1] = $value1;	//$update[$key] = $value;
								
							}
						}else{
							if( !array_key_exists( $key, $exist_options ) ){
								$exist_options[$key] = $value;
							}
						}
						
					}
				}
				update_option( 'csds_userRegAide_Options', $exist_options );
			}
		}
	}
	
	/**	
	 * Function update_ura_options_database
	 * Updates Database version & options
	 * @since 1.2.5
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function update_ura_options_database(){
		$options = get_option( 'csds_userRegAide_Options' );
		if( !empty( $options ) ){
			if( array_key_exists( 'csds_userRegAide_db_Version', $options ) ){
				if( $options['csds_userRegAide_db_Version'] != '1.5.3.2' ){
					do_action( 'update_options' );
				}
			}else{
				$options['csds_userRegAide_db_Version'] = '1.5.3.2';
				update_option( "csds_userRegAide_Options", $options );
			}
			if( !array_key_exists( 'database_updated', $options ) ){
				do_action( 'update_options' );
			}
		}else{
			do_action( 'update_options' );
		}
	}
	
	/**	
	 * Function check_options_table
	 * Checks options database to make sure it doesn't need upgraded
	 * @since 1.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function check_options_table(){
		
		// Filling existing WordPress profile fields into options db
		$kf = get_option( 'csds_userRegAide_knownFields' );
		if( empty( $kf ) ){
			$this->csds_userRegAide_fill_known_fields(); // 
		}
		
		// checking ura options table from options db and updating if needed
		$default_options = $this->csds_userRegAide_defaultOptionsArray();
		$options = get_option( 'csds_userRegAide_Options' );
		if( !empty( $options ) && !empty( $default_options ) ){
			$default_count = count( $default_options );
			$options_count = count( $options );
		
			if( $options_count < $default_count ){
				$this->csds_userRegAide_updateOptions();
			}elseif( $options['updated'] == 2 ){
				$this->csds_userRegAide_updateOptions();
			}elseif( $options['csds_userRegAide_db_Version'] != '1.5.3.0' ){
				$this->csds_userRegAide_updateOptions();
			}
			
			if( !array_key_exists( 'database_updated', $options ) ){
				$this->csds_userRegAide_updateOptions();
			}elseif( array_key_exists( 'database_updated', $options ) ){
				if( $options['database_updated'] == 0 ||  $options['database_updated'] == 2 ){
					$this->csds_userRegAide_updateOptions();
				}
			}
		}else{
			$this->csds_userRegAide_updateOptions();
		}
	}
	
}
?>