<?php

/**
 * Class REGISTRATION_FORM_CSS_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.3.3
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class REGISTRATION_FORM_CSS_MODEL
{
	
	/**	
	 * Function reg_form_css_updater
	 * Handles updating options for registration form custom settings options view
	 * @since 1.5.2.0
	 * @updated 1.5.3.3
	 * @access public
	 * @params string $msg
	 * @returns string $msg ( results of function updated or error  message to display to user )
	*/
	
	function reg_form_css_updater( $msg ){
		global $current_user;
		$msg = ( string ) '';
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			if( isset( $_POST['csds_userRegAide_logo_update'] ) ){ 
				if( wp_verify_nonce( $_POST['wp_nonce_csds-regFormCSSMsgs'], 'csds-regFormCSSMsgs' ) ){
					$ecnt = (int) 0;
					$efields = array();
					$update = get_option( 'csds_userRegAide_Options' );
					// show custom logo options
					if( !empty( $_POST['csds_userRegAide_logo'] ) ){
						$update['show_logo'] = sanitize_text_field( $_POST['csds_userRegAide_logo'] );
							if( !empty( $_POST['csds_userRegAide_newLogoURL'] ) && $_POST['csds_userRegAide_logo'] == 1 ){
								$update['logo_url'] = esc_url_raw(trim($_POST['csds_userRegAide_newLogoURL']));
							}elseif( empty( $_POST['csds_userRegAide_newLogoURL'] ) && $_POST['csds_userRegAide_logo'] == 1 ){
								$ecnt ++;
								$efields[$ecnt] = __( 'New Logo URL', 'user-registration-aide' );
							}elseif( $_POST['csds_userRegAide_logo'] == 2 ){
								$update['logo_url'] = esc_url_raw( trim( $_POST['csds_userRegAide_newLogoURL'] ) );
							}
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Show Logo Option', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_userRegAide_change_logo_link'] ) ){
						$update['change_logo_link'] = sanitize_text_field( $_POST['csds_userRegAide_change_logo_link'] );
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Change Logo Link', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_logo_height'] ) ){
						$update['logo_height'] = sanitize_text_field( $_POST['csds_logo_height'] );
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Logo Height', 'user-registration-aide' );
					}
					if( !empty( $_POST['csds_logo_width'] ) ){
						$update['logo_width'] = sanitize_text_field( $_POST['csds_logo_width'] );
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Logo Width', 'user-registration-aide' );
					}
					
					// Show custom Form Background Image options
					if( !empty( $_POST['csds_userRegAide_background_image'] ) ){
						$update['show_background_image'] = sanitize_text_field( $_POST['csds_userRegAide_background_image'] );
						if( !empty( $_POST['csds_userRegAide_newBackgroundImageURL'] ) && $_POST['csds_userRegAide_background_image'] == 1 ){
							$update['background_image_url'] = esc_url_raw( trim( $_POST['csds_userRegAide_newBackgroundImageURL'] ) );
						}elseif( $_POST['csds_userRegAide_background_image'] == 1 && empty( $_POST['csds_userRegAide_newBackgroundImageURL'] ) ){
							$ecnt ++;
							$efields[$ecnt] = __( 'Background Image URL', 'user-registration-aide' );
						}elseif( $_POST['csds_userRegAide_background_image'] == 2 ){
							$update['background_image_url'] = esc_url_raw( trim( $_POST['csds_userRegAide_newBackgroundImageURL'] ) );
						}
						if( !empty( $_POST['csds_repeat_background_image'] ) ){
							$update['background_image_repeat'] = sanitize_text_field( $_POST['csds_repeat_background_image'] );
						}else{
							$ecnt ++;
							$efields[$ecnt] = __( 'Background Image Repeat', 'user-registration-aide' );
						}
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Show Custom Form Background Image Option', 'user-registration-aide' );
					}
					
					// Show Custom Page Background Image Options
					if( !empty( $_POST['csds_userRegAide_page_background_image'] ) ){
						$update['show_reg_form_page_image'] = sanitize_text_field( $_POST['csds_userRegAide_page_background_image'] );
						if( !empty( $_POST['csds_userRegAide_newPageBackgroundImage'] ) && $_POST['csds_userRegAide_page_background_image'] == 1 ){
							$update['reg_form_page_image'] = esc_url_raw(trim( $_POST['csds_userRegAide_newPageBackgroundImage'] ) );
						}elseif( empty( $_POST['csds_userRegAide_newPageBackgroundImage']) && $_POST['csds_userRegAide_page_background_image'] == 1 ){
							$ecnt ++;
							$efields[$ecnt] = __( 'Custom Page Background Image URL', 'user-registration-aide' );
						}elseif( $_POST['csds_userRegAide_page_background_image'] == 2 ){
							$update['reg_form_page_image'] = esc_url_raw( trim( $_POST['csds_userRegAide_newPageBackgroundImage'] ) );
						}
						if( !empty( $_POST['csds_repeat_page_background_image'] ) ){
							if( !empty( $_POST['csds_repeat_page_bkgrd_image_hor'] ) ){
								if( $_POST['csds_repeat_page_background_image'] == 1 && $_POST['csds_repeat_page_bkgrd_image_hor'] == 2 ){ 
									$update['reg_form_page_image_repeat'] = sanitize_text_field( $_POST['csds_repeat_page_background_image'] );
									$update['hor_bckgrnd_image_repeat'] = sanitize_text_field( $_POST['csds_repeat_page_bkgrd_image_hor'] );
								}elseif( $_POST['csds_repeat_page_background_image'] == 2 && $_POST['csds_repeat_page_bkgrd_image_hor'] == 2 ){ 
									$update['reg_form_page_image_repeat'] = sanitize_text_field( $_POST['csds_repeat_page_background_image'] );
									$update['hor_bckgrnd_image_repeat'] = sanitize_text_field( $_POST['csds_repeat_page_bkgrd_image_hor'] );
								}elseif( $_POST['csds_repeat_page_background_image'] == 2 && $_POST['csds_repeat_page_bkgrd_image_hor'] == 1 ){
									$update['reg_form_page_image_repeat'] = sanitize_text_field( $_POST['csds_repeat_page_background_image'] );
									$update['hor_bckgrnd_image_repeat'] = sanitize_text_field( $_POST['csds_repeat_page_bkgrd_image_hor'] );
								}elseif( $_POST['csds_repeat_page_background_image'] == 1 && $_POST['csds_repeat_page_bkgrd_image_hor'] == 1 ){
									if( $update['reg_form_page_image_repeat'] == 1 && $update['hor_bckgrnd_image_repeat'] == 2 ){
										$update['reg_form_page_image_repeat'] = 2;
										$update['hor_bckgrnd_image_repeat'] = 1;
									}elseif( $update['reg_form_page_image_repeat'] == 2 && $update['hor_bckgrnd_image_repeat'] == 1 ){
										$update['reg_form_page_image_repeat'] = 1;
										$update['hor_bckgrnd_image_repeat'] = 2;
									}elseif( $update['reg_form_page_image_repeat'] == 2 && $update['hor_bckgrnd_image_repeat'] == 2 ){
										$update['reg_form_page_image_repeat'] = 1;
										$update['hor_bckgrnd_image_repeat'] = 2;
									}
								}
							}
						}else{
							$ecnt ++;
							$efields[$ecnt] = __( 'Background Page Image Repeat', 'user-registration-aide' );
						}
												
						if( !empty( $_POST['image_position'] ) ){
							$update['background_image_position'] = sanitize_text_field( $_POST['image_position'] );
						}else{
							$update['background_image_position'] = 'left_top';
							//$ecnt ++;
							//$efields[$ecnt] = __( 'Background Page Image Repeat', 'user-registration-aide' );
						}
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Show Custom Page Background Image', 'user-registration-aide' );
					}
					
					// Show Custom Form Background Color Options
					if( !empty( $_POST['csds_userRegAide_background_color'] ) ){
						$update['show_background_color'] = sanitize_text_field( $_POST['csds_userRegAide_background_color'] );
						if( !empty($_POST['csds_userRegAide_newBackgroundColor'] ) && $_POST['csds_userRegAide_background_color'] == 1 ){
							$update['reg_background_color'] = sanitize_text_field( $_POST['csds_userRegAide_newBackgroundColor'] );
						}elseif( empty($_POST['csds_userRegAide_newBackgroundColor'] ) && $_POST['csds_userRegAide_background_color'] == 1 ){
							$ecnt ++;
							$efields[$ecnt] = __( 'Custom Form Background Color', 'user-registration-aide' );
						}elseif( $_POST['csds_userRegAide_background_color'] == 2 ){
							$update['reg_background_color'] = sanitize_text_field( $_POST['csds_userRegAide_newBackgroundColor'] );
						}
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Show Custom Form Background Color Option', 'user-registration-aide' );
					}
					
					// Show Custom Page Background Color Options
					if( !empty( $_POST['csds_userRegAide_page_background_color'] ) ){
						$update['show_reg_form_page_color'] = sanitize_text_field( $_POST['csds_userRegAide_page_background_color'] );
						if( !empty( $_POST['csds_userRegAide_newPageBackgroundColor'] ) && $_POST['csds_userRegAide_page_background_color'] == 1 ){
							$update['reg_form_page_color'] = sanitize_text_field( $_POST['csds_userRegAide_newPageBackgroundColor'] );
						}elseif( empty( $_POST['csds_userRegAide_newPageBackgroundColor'] ) && $_POST['csds_userRegAide_page_background_color'] == 1 ){
							$ecnt ++;
							$efields[$ecnt] = __( 'Custom Page Background Color', 'user-registration-aide' );
						}elseif( $_POST['csds_userRegAide_page_background_color'] == 2 ){
							$update['reg_form_page_color'] = sanitize_text_field( $_POST['csds_userRegAide_newPageBackgroundColor'] );
						}
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Show Custom Page Background Color Option', 'user-registration-aide' );
					}
					
					// check to make sure user is not making a background page image and page color at same time
					$options = get_option( 'csds_userRegAide_Options' );
					if( $update['show_reg_form_page_image'] == 1 && $update['show_reg_form_page_color'] == 1 ){
						if( $options['show_reg_form_page_image'] == 2 && $options['show_reg_form_page_color'] == 1 ){
							$update['show_reg_form_page_image'] = 1;
							$update['show_reg_form_page_color'] = 2;
						}elseif( $options['show_reg_form_page_image'] == 2 && $options['show_reg_form_page_color'] == 1  ){
							$update['show_reg_form_page_image'] = 2;
							$update['show_reg_form_page_color'] = 1;
						}elseif( $options['show_reg_form_page_image'] == 2 && $options['show_reg_form_page_color'] == 2  ){
							$update['show_reg_form_page_image'] = 2;
							$update['show_reg_form_page_color'] = 2;
							$ecnt ++;
							$efields[$ecnt] = __( 'You Cannot Have Page Background Image and Color Both Selected!', 'user-registration-aide' );
						}
					}
					if( $update['show_background_image'] == 1 && $update['show_background_color'] == 1 ){
						if( $options['show_background_image'] == 2 && $options['show_background_color'] == 1 ){
							$update['show_background_image'] = 1;
							$update['show_background_color'] = 2;
						}elseif( $options['show_background_image'] == 2 && $options['show_background_color'] == 1  ){
							$update['show_background_image'] = 2;
							$update['show_background_color'] = 1;
						}elseif( $options['show_background_image'] == 2 && $options['show_background_color'] == 2  ){
							$update['show_background_image'] = 2;
							$update['show_background_color'] = 2;
							$ecnt ++;
							$efields[$ecnt] = __( 'You Cannot Have Form Background Image and Color Both Selected!', 'user-registration-aide' );
						}
					}
					
					// Show Custom Text - Links Colors
					if( !empty( $_POST['csds_userRegAide_text_color'] ) ){
						$update['show_login_text_color'] = sanitize_text_field( $_POST['csds_userRegAide_text_color'] );
						if( !empty( $_POST['csds_userRegAide_newTextColor'] ) ){
							$update['login_text_color'] = sanitize_text_field( $_POST['csds_userRegAide_newTextColor'] );
						}elseif( empty( $_POST['csds_userRegAide_newTextColor'] ) ){
							$ecnt ++;
							$efields[$ecnt] = __( 'Login Form Text-Links Color', 'user-registration-aide' );
						}
						if( !empty( $_POST['csds_userRegAide_newHoverTextColor'] ) ){
							$update['hover_text_color'] = sanitize_text_field( $_POST['csds_userRegAide_newHoverTextColor'] );
						}elseif( empty( $_POST['csds_userRegAide_newHoverTextColor'] ) ){
							$ecnt ++;
							$efields[$ecnt] = __( 'New Links Hover Color', 'user-registration-aide' );
						}
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Show Login Text Color Option', 'user-registration-aide' );
					}
					
					// Show Registration Form Custom Text Size
					if( !empty( $_POST['ura_use_custom_rf_text_size'] ) ){
						$update['use_custom_rf_text_size'] = sanitize_text_field( $_POST['ura_use_custom_rf_text_size'] );
						if( !empty( $_POST['ura_custom_rf_text_size'] ) ){
							$update['custom_rf_text_size'] = sanitize_text_field( $_POST['ura_custom_rf_text_size'] );
						}elseif( empty( $_POST['ura_custom_rf_text_size'] ) ){
							$ecnt ++;
							$efields[$ecnt] = __( 'Registration Form Text-Size', 'user-registration-aide' );
						}
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Show Registration Text Size Option', 'user-registration-aide' );
					}
					
					// Show Login Form Custom Text Size
					if( !empty( $_POST['ura_use_custom_lf_text_size'] ) ){
						$update['use_custom_lf_text_size'] = sanitize_text_field( $_POST['ura_use_custom_lf_text_size'] );
						if( !empty( $_POST['ura_custom_lf_text_size'] ) ){
							$update['custom_lf_text_size'] = sanitize_text_field( $_POST['ura_custom_lf_text_size'] );
						}elseif( empty( $_POST['ura_custom_lf_text_size'] ) ){
							$ecnt ++;
							$efields[$ecnt] = __( 'Login Form Text-Size', 'user-registration-aide' );
						}
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Show Login Text Size Option', 'user-registration-aide' );
					}
					
					// Show Link Shadows 
					if( !empty( $_POST['csds_userRegAide_show_shadow'] ) ){
						$ss = ( string ) sanitize_text_field( $_POST['csds_userRegAide_shadowSize'] );
						$update['show_shadow'] = sanitize_text_field( $_POST['csds_userRegAide_show_shadow'] );
						if( !empty($_POST['csds_userRegAide_shadowSize']) ){
							if( $ss == '0px'){
								$ss = '1px';
							}
							$update['shadow_size'] = $ss;
						}elseif( empty( $_POST['csds_userRegAide_shadowSize'] ) ){
							$ecnt ++;
							$efields[$ecnt] = __( 'Shadow Size in PX', 'user-registration-aide' );
						}
						if( !empty( $_POST['csds_userRegAide_shadowColor'] ) ){
							$update['shadow_color'] = sanitize_text_field( $_POST['csds_userRegAide_shadowColor'] );
						}elseif( empty( $_POST['csds_userRegAide_shadowColor'] ) ){
							$ecnt ++;
							$efields[$ecnt] = __( 'Shadow Color', 'user-registration-aide' );
						}
					}else{
						$ecnt ++;
						$efields[$ecnt] = __( 'Show Link Shadows Option', 'user-registration-aide' );
					}
					
					if( $ecnt == 0 ){
						$msg = '<div id="message" class="updated"><p>'. __( 'New Registration Form CSS Options updated successfully.', 'user-registration-aide' ) .'</p></div>'; //Report to the user that the data has been updated successfully
						update_option( "csds_userRegAide_Options", $update );
						return $msg;
					}else{
						foreach( $efields as $key => $value ){
							if( $key == 1 ){
								$efield = $value;
							}else{
								$efield .= ' & '. $value;
							}
						}
						$msg = '<div id="message" class="error"><p>'. __( 'New Registration Form CSS Options Errors ', 'user-registration-aide' ) . $efield .'</p></div>'; //Report to the user that the data has been updated successfully
						return $msg;
					}
					return $msg;
				}else{
					wp_die( __( 'Invalid Security Check!', 'user-registration-aide' ) );
				}
			}
		}
		return $msg;
	}
}