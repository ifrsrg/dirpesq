<?php

//Add our panel to the "Screen Options" box
add_screen_options_panel(
	'ura_users_admin-default-settings',       //Panel ID
	'Show on Screen',              //Panel title. 
	'ura_users_approve_columns_panel', //The function that generates panel contents.
	array( 'users_page_pending-approval', 'users_page_pending-deletion', 'users_page_pending-verification' ),            //Pages/screens where the panel is displayed. 
	'ura_users_approve_save_defaults',  // function that gets triggered when settings submitted/saved.
	true           //Auto-submit settings (via AJAX) when they change. 
);

/** 
 * function merge_all_fields
 * merges all available fields for screen options panel
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params
 * @returns array $wp_fields for screen options panel
*/

function merge_all_fields(){
	$fields = new FIELDS_DATABASE();
	$ura_fields = $fields->get_all_fields();
	$wp_fields = get_option( 'csds_userRegAide_knownFields' );
	unset( $wp_fields['user_pass'] );
	foreach( $ura_fields as $object ){
		$wp_fields[$object->meta_key] = $object->field_name;
	}
	return $wp_fields;
}

/** 
 * function ura_users_get_settings_fields
 * merges all available fields for screen options panel
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params
 * @returns array $keys keys of settings fields
*/

function ura_users_get_settings_fields() {
	//return array('disable_wpautop', 'disable_wptexturize', 'disable_convert_chars', 'disable_convert_smilies');
	;
	$wp_fields = merge_all_fields();
	$count = (int) count( $wp_fields );
	$index = (int) 0;
	$keys = (string) '';
	foreach( $wp_fields as $key	=>	$value ){
		if( $count < $index ){
			$keys .= $key.',';
		}else{
			$keys .- $key;
		}
		$index++;
	}
	
	return array( $keys );
}

/** 
 * function ura_users_get_column_defaults
 * Retrieve the default settings for our post/page meta box. Settings are saved in user meta.
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params
 * @returns array $defaults
*/

function ura_users_get_column_defaults(){
	//By default, all tweaks are disabled
	
	$wp_fields = merge_all_fields();
	$defaults = array();
	foreach( $wp_fields as $key	=>	$value ){
		$defaults[$key] = false;
	}
	
	if ( !function_exists('wp_get_current_user') || !function_exists('get_user_meta') ){
		return $defaults;
	}
	
	//Get current defaults, if any
	$user = wp_get_current_user();
	$user_defaults = get_user_meta( $user->ID, 'ura_users_column_defaults', true );
	if ( is_array( $user_defaults ) ){
		$defaults = array_merge( $defaults, $user_defaults );
	}
	
	return $defaults;
}

/** 
 * function ura_users_set_default_settings
 * Update default settings for our post/page meta box.
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params array $new_defaults
 * @returns bool True on success, false on failure.
*/

function ura_users_set_default_settings( $new_defaults ){
	if ( !function_exists( 'wp_get_current_user' ) || !function_exists( 'update_user_meta' ) ){
		return false;
	}
	//exit( print_r( $new_defaults ) );
	//Get current defaults, if any
	
	$user = wp_get_current_user();
	if ( isset( $user ) && $user && isset( $user->ID ) ){
		return update_user_meta( $user->ID, 'ura_users_column_defaults', $new_defaults );
	} else {
		return false;
	}
}

/** 
 * function ura_users_approve_columns_panel
 * Generate the "Raw HTML defaults" panel for Screen Options.
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params
 * @returns string $output for screen panel output
*/

function ura_users_approve_columns_panel(){
	$defaults = ura_users_get_column_defaults();
	
	//Output checkboxes 
	$wp_fields = merge_all_fields();
	unset( $wp_fields['user_pass'] );
 	$output = '<div class="metabox-prefs">';
	foreach( $wp_fields as $field => $legend ){
		$esc_field = esc_attr($field);
		$output .= sprintf(
			'<label for="ura_soc-%s" style="line-height: 20px;">
				<input type="checkbox" name="ura_soc-%s" id="ura_soc-%s"%s>
				%s
			</label>',
			$esc_field,
			$esc_field,
			$esc_field,
			($defaults[$field]?' checked="checked"':''),
			$legend
		);
	}
	$output .= "</div>";
	
	return $output;
}

/** 
 * function ura_users_approve_save_defaults
 * Processes the form fields and save new settings
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @params array $params
 * @returns
*/

function ura_users_approve_save_defaults( $params ){
	//Get current defaults
	$defaults = ura_users_get_column_defaults();
	$fields = new FIELDS_DATABASE();
	$wp_fields = get_option( 'csds_userRegAide_knownFields' );
	unset( $wp_fields['user_pass'] );	
	//Read new values from the submitted form
	foreach( $defaults as $field => $old_value ){
		if ( isset( $params['ura_soc-'.$field] ) && ( $params['ura_soc-'.$field] == 'on' ) ){
			$defaults[$field] = true;
			if( !array_key_exists( $field, $wp_fields ) ){
				$fields->update_fields( $field, 'approve_view', true );
			}
		} else {
			$defaults[$field] = false;
			if( !array_key_exists( $field, $wp_fields ) ){
				$fields->update_fields( $field, 'approve_view', false );
			}
		}
	}
	
	//Store the new defaults
	ura_users_set_default_settings( $defaults );
}

?>