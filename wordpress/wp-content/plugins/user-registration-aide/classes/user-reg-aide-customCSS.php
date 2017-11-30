<?php

/**
 * Class URA_CUSTOM_CSS
 *
 * @category Class
 * @since 1.3.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_CUSTOM_CSS
{
	
	/** 
	 * function csds_userRegAide_Logo_Title_Color
	 * Sets the text color and logo links & shadows in registration/login pages
	 * @since 1.2.0
	 * @updated 1.5.3.8
	 * @Filters 'login_headertitle' Line 125 & 145 (multisite) $this
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function csds_userRegAide_Logo_Title_Color(){
		$options = get_option('csds_userRegAide_Options');
		$show_text_color = $options['show_login_text_color'];
		$text_color = $options['login_text_color'];
		$hover_color = $options['hover_text_color'];
		$show_shadow = $options['show_shadow'];
		$shadow_size = $options['shadow_size'];
		$shadow_color = $options['shadow_color'];
		$change_rf_font = $options['use_custom_rf_text_size'];
		$change_lf_font = $options['use_custom_lf_text_size'];
		if( empty( $change_rf_font ) ){
			$rf_font_size = '14';
			$rf_height = 40;
		}elseif( $change_rf_font == 2 ){
			$rf_font_size = '14';
			$rf_height = 40;
		}elseif( $change_rf_font == 1 ){
			$rf_font_size = $options['custom_rf_text_size'];
			$rf_height = $rf_font_size * 2;
		}
		if( empty( $change_lf_font ) ){
			$lf_font_size = '14';
		}elseif( $change_lf_font == 2 ){
			$lf_font_size = '14';
		}else{
			$lf_font_size = $options['custom_lf_text_size'];
		}
		if( $show_text_color == 1 && $show_shadow == 2 ){
			echo '<style type="text/css">#loginform label{ font-family: verdana,arial; font-size:'.$lf_font_size.'px; color: '.$text_color.'; font-weight:bold;}';
			echo '#loginform input{height:36px; font-family: verdana,arial; font-size:'.$lf_font_size.'px; font-weight:bold;} ';
			echo '#registerform label{font-family: verdana,arial; font-size:'.$rf_font_size.'px; color: '.$text_color.'; font-weight:bold;} ';
			echo '#registerform input{font-family: verdana,arial; font-size:'.$rf_font_size.'px; font-weight:bold;} ';
			echo '.login #pass-strength-result { font-size:'.$rf_font_size.'px; font-weight: 600; margin: -1px 5px 16px 0; padding: 6px 5px; text-align: center; width: 100%; }';
			echo 'body.login #nav a  {color:'.$text_color.' !important; font-weight:bold; font-size:1.4em; margin-left:-9999; text-shadow: 0px 0px 0px #000000;}';
			echo '.login #backtoblog a { color:'.$text_color.' !important; font-weight:bold; font-size:1.5em; text-shadow: 0px 0px 0px #000000;}';
			echo '.login #nav a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.4em;}';
			echo '.login #backtoblog a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.5em;} </style>';
		}elseif( $show_text_color == 1 && $show_shadow == 1 ){
			echo '<style type="text/css">#loginform label{ font-family: verdana,arial; font-size:'.$lf_font_size.'; color: '.$text_color.'; font-weight:bold;}';
			echo '#loginform input{height:36px; font-family: verdana,arial; font-size:'.$lf_font_size.'px; font-weight:bold;} ';
			echo '#registerform label{font-family: verdana,arial; font-size:'.$rf_font_size.'px; color: '.$text_color.'; font-weight:bold;} ';
			echo '#registerform input{font-family: verdana,arial; font-size:'.$rf_font_size.'px; font-weight:bold;} ';
			echo '.login #pass-strength-result { font-size:'.$rf_font_size.'px; font-weight: 600; margin: -1px 5px 16px 0; padding: 6px 5px; text-align: center; width: 100%; }';
			echo 'body.login #nav a  {color:'.$text_color.' !important; font-weight:bold; font-size:1.4em; margin-left:-9999; text-shadow:'.$shadow_size.' '.$shadow_size.' '.$shadow_size.' '.$shadow_color.';}';
			echo '.login #backtoblog a { color:'.$text_color.' !important; font-weight:bold; font-size:1.5em; text-shadow:'.$shadow_size.' '.$shadow_size.' '.$shadow_size.' '.$shadow_color.';}';
			echo '.login #nav a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.4em;}';
			echo '.login #backtoblog a:hover { color:'.$hover_color.' !important; font-weight:bold; font-size:1.5em;} </style>';
		}elseif( $show_text_color == 2 && $show_shadow == 2 && $change_rf_font == 1 || $change_lf_font == 1 ){
			echo '<style type="text/css">#loginform label{ font-family: verdana,arial; font-size:'.$lf_font_size.'px; font-weight:bold;}';
			echo '#loginform input[type="text"]{height:40px; font-family: verdana,arial; font-size:'.$lf_font_size.'px; font-weight:bold;} ';
			echo '#registerform label{font-family: verdana,arial; font-size:'.$rf_font_size.'px; font-weight:bold;} ';
			echo '#registerform input{height:'.$rf_height.'px; font-family: verdana,arial; font-size:'.$rf_font_size.'px; font-weight:bold;} ';
			echo '.login #pass-strength-result { font-size:'.$rf_font_size.'px; font-weight: 600; margin: -1px 5px 16px 0; padding: 6px 5px; text-align: center; width: 100%; }';
			echo 'body.login #nav a  {color:#555d66 !important; font-size:16px;}';
			echo '.login #backtoblog a { color:#555d66 !important; font-size:16px;;}';
			echo '.login #nav a:hover { color:#00a0d2 !important;}';
			echo '.login #backtoblog a:hover { color:#00a0d2 !important;} </style>';
		}
		
		if ( $options['change_logo_link'] == 2 ){
			return;
		}elseif( $options['change_logo_link'] == 1 ){
			return get_bloginfo( 'name' );
		}
		
	}
	
	/** 
	 * function reg_form_fields_cnt
	 * Gets accurate cnt of all fields on registration form to determine height so page images won't be cut off or too long
	 * @since 1.5.2.0
	 * @updated 1.5.3.0
	 * 
	 * @access public
	 * @params
	 * @returns int $cnt of fr fields
	*/

	function reg_form_fields_cnt(){
		$options = get_option( 'csds_userRegAide_Options' );
		$fdb = new FIELDS_DATABASE();
		$rf = $fdb->get_registration_fields();
		$cnt = ( int ) 2;
		$ocnt = ( int ) 0;
		$fields = get_option( 'csds_userRegAide_registrationFields' );
		
		if( !empty( $fields ) ){
			if( array_key_exists( 'user_pass', $fields ) ){
				$cnt += 2;
			}
		}	
		
		if( $options['show_custom_agreement_message'] == 1 ){
			$cnt += 3;
		}
		
		if( $options['select_pass_message'] == 1 ){
			$cnt += 2;
		}
		
		if( $options['show_login_message'] == 1 ){
			$cnt += 2;
		}
		
		if( $options['add_security_question'] == 1 ){
			$cnt += 6;
		}
		
		if( !empty( $rf ) ){
			$cnt += count( $rf );
			foreach( $rf as $object ){
				$data_type = $object->data_type;
				$parent_id = $object->ID;
				if( $data_type == 'multiselectbox' || $data_type == 'radio' || $data_type == 'checkbox' ){
					$cnt += $fdb->get_field_options_count( $parent_id );
				}
			}
		}
		
		return $cnt;
	}

	/** 
	 * function csds_userRegAide_Logo_Head
	 * Sets up head for custom login and registration pages
	 * @since 1.2.0
	 * @updated 1.5.3.0
	 * @handles action 'login_head' Line 126 &$this Handles multisite action 'signup_header' line 146 &$this
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function csds_userRegAide_Logo_Head(){
		global $errors;
		$options = get_option( 'csds_userRegAide_Options' );
		//$errors = new WP_Error();
		$show_logo = $options['show_logo'];
		$reg_form_height = ( int ) 100;
		$err_cnt = ( int ) 0;
		$rf_height_perct = ( string ) '';
		$ie = ( boolean ) false;
		$ie_reg_form_height = ( string ) '';
		$fields_cnt = ( int ) 0;
		$logo_url = ( string ) '';
		$logo_width = ( string ) '';
		$logo_height = ( string ) '';
		$repeat_image = ( string ) '';
		$repeat_page_image = ( string ) '';
		$hor_repeat = ( int ) 0;
		$show_background_image = ( int ) 0;
		$background_image = ( string ) '';
		$background_position = ( string ) '';
		$show_background_color = ( int ) 0;
		$background_color = ( string ) '';
		$show_page_image = ( int ) 0;
		$page_image = ( string ) '';
		$show_page_color = ( int ) 0;
		$page_color = ( string ) '';
		$fields = get_option( 'csds_userRegAide_registrationFields' );
		$fields_cnt = $this->reg_form_fields_cnt();
		// accounts for image size on registration page with errors
		if( !empty( $errors ) ){
			$codes = $errors->get_error_codes();
			$err_cnt = count( $codes ); 
			if( $err_cnt >= 1 ){
				//wp_die( $err_cnt );
				$reg_form_height  += ( $err_cnt * 2 );
			}
		}
		/* accounts for different browsers and page length - page image will not extend  
		 * itself beyond word press default without some help
		*/
		$agent = $_SERVER['HTTP_USER_AGENT'];
		if( !empty( $agent ) ){
			if( preg_match( '/Chrome/i', $agent ) ){ 
				$reg_form_height = $reg_form_height + ( $fields_cnt * 10 );
			}elseif( preg_match( '/Firefox/i', $agent ) ){
				$reg_form_height = $reg_form_height +( $fields_cnt * 10 );
			}elseif( preg_match( '/Safari/i', $agent ) ){
				$reg_form_height = $reg_form_height + ( $fields_cnt * 10 );
			}elseif( preg_match( '/Opera/i', $agent ) ){
				$reg_form_height = $reg_form_height + ( $fields_cnt * 10 );
			}elseif( preg_match( '/Netscape/i', $agent ) ){
				$reg_form_height = $reg_form_height + ( $fields_cnt * 10 );
			}elseif( preg_match( '/Flock/i', $agent ) ){
				$reg_form_height = $reg_form_height + ( $fields_cnt * 10 );
			}elseif( preg_match( '/Lynx/i', $agent ) ){
				$reg_form_height = $reg_form_height + ( $fields_cnt * 10 );
			}elseif( preg_match( '/MSIE/i', $agent ) ){	
				$ie = true;
				$reg_form_height = $reg_form_height + ( $fields_cnt * 42 );
			}else{
				$reg_form_height = $reg_form_height + ( $fields_cnt * 102 );
			}
		}else{
			$reg_form_height = $reg_form_height + ( $fields_cnt * 10 );
		}
		?>
		<!--[if IE]>
		<?php
		// for internet explorer registration page height adjustment
		$ie_reg_form_height = $reg_form_height + ( $fields_cnt * 42 );
		?>
		<![endif]-->
		
		<?php
		
		if( $ie == true ){
			$ie_reg_form_height = $reg_form_height + ( $fields_cnt * 42 );
			$reg_form_height = $ie_reg_form_height;
		}
		
		$rf_height_perct = $reg_form_height.'%';
		$logo_url = $options['logo_url'];
		$logo_width = $options['logo_width'];
		$logo_height = $options['logo_height'];
		$repeat_image = $options['background_image_repeat'];
		$repeat_page_image = $options['reg_form_page_image_repeat'];
		$hor_repeat = $options['hor_bckgrnd_image_repeat'];
		$background_position = $options['background_image_position'];
		if( $repeat_image == 1 ){
			$repeat_image = 'repeat';
		}else{
			$repeat_image = 'no-repeat';
		}
		
		if( $repeat_page_image == 1 ){
			$repeat_page_image = 'repeat';
		}elseif( $repeat_page_image == 2 && $hor_repeat == 2 ){
			$repeat_page_image = 'no-repeat';
		}elseif( $repeat_page_image == 2 && $hor_repeat == 1 ){
			$repeat_page_image = 'repeat-x';
		}
		$show_background_image = $options['show_background_image'];
		if( $show_background_image == 1 && !empty( $options['background_image_url'] ) ){
			$background_image = $options['background_image_url'];
		}
		$show_background_color = $options['show_background_color'];
		$background_color = $options['reg_background_color'];
		$show_page_image = $options['show_reg_form_page_image'];
		if( $show_page_image == 1 && !empty( $options['reg_form_page_image'] ) ){
			$page_image = $options['reg_form_page_image'];
		}
		$show_page_color = $options['show_reg_form_page_color'];
		$page_color = $options['reg_form_page_color'];
		
		if ( $show_logo == 1 ){
			$size = array();
			$size = getimagesize( esc_url( $logo_url ) );
			if( !empty( $size ) && is_array( $size ) ){
				$width = $size[0].'px';
				$height = $size[1].'px';
			}else{
				$width = $options['logo_width'];
				$height = $options['logo_height'];
				$size_error = 1;
			}
			
			if ( function_exists( 'theme_my_login' ) ) {
				echo '<style type="text/css">div.site-content header.entry-header h1.entry-title a { background-image:url('.esc_url($logo_url).') !important; background-size: contain; width: 100%; } </style>';
				echo '<style type="text/css">header.entry-header a { background-image:url('.esc_url($logo_url).') !important; background-size: contain; width: 100%; } </style>';
				echo '<style>h1.entry-title a { background-image:url('.esc_url($logo_url).') !important; background-size: contain; width: 100%; } </style>';
			}else{
				if( $width >= 321 ){
					echo '<style type="text/css">body.login div#login { width: '.$width.'; } </style>';
				}
				if( $width >= 321 ){
						echo '<style type="text/css">body.login div#login h1 a { background-image:url('.esc_url($logo_url).') !important; background-size: contain; width: '.$width.'; height: '.$height.'; background-position: left top;  } </style>';
				}elseif( $width <= 320 ){
					//echo '<style type="text/css">body.login div#login h1 a { background-image:url('.esc_url($logo_url).') !important; background-size: contain; width: 100%; background-position: center;  } </style>';
					echo '<style type="text/css">body.login div#login h1 a { background-image:url('.esc_url($logo_url).') !important; background-size: contain; width: '.$width.'; height: '.$height.';  background-position: center;  } </style>';
				}
			}
		}
		
		
		
		if( $show_background_image == 1 && !empty( $background_image ) ){
			echo '<style type="text/css">#loginform{ background:url('.$background_image.') '.$repeat_image.' center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">#registerform{ background:url('.$background_image.') '.$repeat_image.' center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">#lostpasswordform{ background:url('.$background_image.') '.$repeat_image.' center;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
		}
			
		if( $show_background_color == 1 ){
			echo '<style type="text/css">#loginform{ background-color:'.$background_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">#registerform{ background-color:'.$background_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">#lostpasswordform{ background-color:'.$background_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">p.message{ background-color:'.$background_color.' !important; } </style>';
		}
		
		if( $show_page_image == 1 && !empty( $page_image ) ){
			echo '<style type="text/css">body.login{ background:url('.$page_image.') '.$repeat_page_image.'; background-position: '.$background_position. '; padding-top:30px; font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">body.login login-action-lostpassword wp-core-ui{ background:url('.$page_image.') '.$repeat_page_image.';  background-position: '.$background_position. '; padding-top:30px; font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">body.login-action-register{ height: '.$rf_height_perct.'; background:url('.$page_image.') '.$repeat_page_image.';  background-position: '.$background_position.'; padding-top:30px; font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';//$reg_form_height $rf_height_perct
		}
		
			
		if( $show_page_color == 1 ){
			echo '<style type="text/css">body.login{ background-color:'.$page_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
			echo '<style type="text/css">body.login login-action-lostpassword wp-core-ui{ background-color:'.$page_color.' !important;padding-top:30px;font:11px "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; } </style>';
		}
		
	}

	/** 
	 * function csds_userRegAide_CustomLoginLink
	 * Sets Custom Logo link to site url if option chosen to add custom logo
	 * @Filters 'login_headerurl' Lines 124 & 144 (for multisite) &$this
	 * @since 1.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns string url logo_link
	*/
	
	function csds_userRegAide_CustomLoginLink(){
		$options = get_option('csds_userRegAide_Options');
		if ( $options['change_logo_link'] == 2 ){
			return 'http://www.wordpress.org';
		}
		if ( $options['change_logo_link'] == 1 ){
			return site_url();
		}
	}
	
	/** 
	 * function csds_userRegAide_Password_Header
	 * Handles scripts and css for password headers on registration forms
	 * @handles action 'login_head' Line 123 &$this
	 * @since 1.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns string url logo_link
	*/
	
	function csds_userRegAide_Password_Header(){
		
		$options = get_option( 'csds_userRegAide_Options' );
			
		$css = URA_CSS_PATH."user-reg-aide-style.php";
		$reg_fields = get_option( 'csds_userRegAide_registrationFields' );
		$wp_pswrd_strength = admin_url().'js/user-profile.js';
		$wp_admin_psm_js = admin_url().'js/password-strength-meter.js';
		$wp_incl_jq = includes_url().'js/jquery/jquery.js';
		$jq_color = URA_JS_PATH."jquery.color.js";
		$jq_color_min = includes_url().'js/jquery/jquery.color.min.js';
		$min_user_profile = URA_JS_PATH."ura-user-profile.min.js";
		$user_profile = URA_JS_PATH."ura-user-profile.js";
		$xwrd_strength = URA_JS_PATH.'ura-password-strength-meter.js';
		$xwrd_check = URA_JS_PATH."xwrd-check.js";
		if( !empty( $reg_fields ) ){		
			if( array_key_exists( 'user_pass', $reg_fields ) ){
				wp_register_script( "jquery", $wp_incl_jq, false );
				wp_enqueue_script( 'jquery' );
						
				wp_register_script( "jquery_color", $jq_color, false );
				wp_enqueue_script( 'jquery_color' );
				
				wp_register_script( "jquery_color_min", $jq_color_min, false );
				wp_enqueue_script( 'jquery_color_min' );
						
				wp_register_style( "user-reg-aide-style", $css, false, false );
				wp_enqueue_style( 'user-reg-aide-style' );
				
				wp_register_script( 'xwrd2_check', $xwrd_check, false );
				wp_enqueue_script( 'xwrd2_check' );
				
				wp_enqueue_script( 'password-strength-meter' );
				wp_enqueue_script( 'user-profile' );
					
			}else{
			
			}
		}
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'utils' );
		
	}
	
	/** 
	 * function password_options
	 * Handles scripts and css for password headers on password change form
	 * @handles action 'login_head' Line 123 &$this
	 * @since 1.5.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns string url logo_link
	*/
	
	function password_options(){
		global $post;
		$xwrds = new PASSWORD_FUNCTIONS();
		$id = ( int ) 0;
		$xwrd_id = ( int ) 0;
		if( isset( $post ) ){
			$id = $post->ID;
			$xwrd_id = $xwrds->title_id( $post );
		}
		$css = URA_CSS_PATH."user-reg-aide-style.php";
		$reg_fields = get_option( 'csds_userRegAide_registrationFields' );
		$wp_pswrd_strength = admin_url().'js/user-profile.js';
		$wp_admin_psm_js = admin_url().'js/password-strength-meter.js';
		$wp_incl_jq = includes_url().'js/jquery/jquery.js';
		$jq_color = URA_JS_PATH."jquery.color.js";
		$jq_color_min = URA_JS_PATH."jquery.color.min.js";
		$xwrd_check = URA_JS_PATH."xwrd-check.js";
		if( $id == $xwrd_id ){
			wp_register_script( "jquery", $wp_incl_jq, false );
			wp_enqueue_script( 'jquery' );
					
			wp_register_script( "jquery_color", $jq_color, false );
			wp_enqueue_script( 'jquery_color' );
			
			wp_register_script( "jquery_color_min", $jq_color_min, false );
			wp_enqueue_script( 'jquery_color_min' );
					
			wp_register_style( "user-reg-aide-style", $css, false, false );
			wp_enqueue_style( 'user-reg-aide-style' );
			
			wp_register_style( "user-reg-aide-style", $css, false, false );
			wp_enqueue_style( 'user-reg-aide-style' );
			
			//wp_register_script( 'xwrd2_check', $xwrd_check, false );
			//wp_enqueue_script( 'xwrd2_check' );
			
			wp_enqueue_script( 'password-strength-meter' );
			wp_enqueue_script( 'user-profile' );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'utils' );
		}
	}
} // end class ?>