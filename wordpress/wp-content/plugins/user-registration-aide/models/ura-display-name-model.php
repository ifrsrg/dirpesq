<?php

/**
 * Class URA_DISPLY_NAME_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_DISPLAY_NAME_MODEL
{
	
	/**	
	 * function update_display_name_options
	 * Handles the updatesfor the support section in the admin settings pages
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns string $msg
	*/
	
	function update_display_name_options( $msg ){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$options = get_option('csds_userRegAide_Options');
			$msg = ( string ) '';
			if( isset( $_POST['update_display_name_field'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
					$options['custom_display_name'] = esc_attr( stripslashes( $_POST['csds_displayNameYesNo'] ) );
					$field = (string) '' ;
					$register = (int) 0;
					$fields = array();
					$regFields = array();
					$display_fields = $this->names_array();
					$fields = $_POST['display_name_field'];
					$roles = ( string ) '';
					foreach( $fields as $key	=>	$title ){
						$field = $title;
					}
					$options['custom_display_field'] = esc_attr( stripslashes( $field ) );
					if( !empty( $_POST['display_name_role_select'] ) ){
						$roles = $_POST['display_name_role_select'];
						$options['display_name_role'] = $roles;
					}else{
						$msg = '<div id="message" class="error"><p>'. __( 'Error No Display Name Role Selected!', 'user-registration-aide' ).'</p></div>';
						return $msg;
					}
					/*
					$select_roles = array();
					foreach( $roles as $role_key =>	$role_value ){
						$select_roles[$role_key] = $role_value;
					}
					*/
					//$options['display_name_role'] = $select_roles;
					$profile_update = $_POST['csds_profileDispNameYN'];
					$default_role = $_POST['default_role'];
					if( isset( $_POST['csds_users_can_register'] ) ){
						$register = 1;
						update_option( "users_can_register", $register );
					}else{
						$register = 0;
						update_option( "users_can_register", $register );
					}
					
					$options['default_user_role'] = $default_role;
					$options['show_profile_disp_name'] = $profile_update;
					update_option("csds_userRegAide_Options", $options);
					update_option( "default_role", $default_role );
					if( $_POST['csds_displayNameYesNo'] == '1' ){
						$regFields = get_option('csds_userRegAide_registrationFields');
						$display_field  = $options['custom_display_field'];
						if( $display_field != 'last_name' ){
							if( is_array( $regFields ) ){
								if( !in_array( 'First Name', $regFields ) ){
									$regFields['first_name'] = 'First Name';
									update_option( "csds_userRegAide_registrationFields", $regFields );
								}
							}
						}
						if( $display_field != 'first_name' ){
							if( is_array( $regFields ) ){
								if( !in_array( 'Last Name', $regFields ) ){
									$regFields['last_name'] = 'Last Name';
									update_option( "csds_userRegAide_registrationFields", $regFields );
								}
							}
						}
					}
				}
				$msg = '<div id="message" class="updated"><p>'. __( 'Display Name Options Updated!', 'user-registration-aide' ).'</p></div>';
			}elseif( isset( $_POST['change_user_display_names'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
					$msg = $this->update_current_user_display_names();
				}
			}elseif( isset( $_POST['restore_default_display_names'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-customOptions'], 'csds-customOptions' ) ){
					$msg = $this->restore_user_display_names();
				}
			}
		}
				
		return $msg;
		
	}
	
	/**	
	 * function names_array
	 * Creates a names array for the display name select box
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @params 
	 * @returns array $display_fields 
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function names_array(){
		$display_fields = array(
			'nickname'			=>	__( 'Nickname', 'user-registration-aide' ),
			'first_name'		=>	__( 'First Name', 'user-registration-aide' ),
			'last_name'			=>	__( 'Last Name', 'user-registration-aide' ),
			'first_last_name'	=>	__( 'First - Last Name', 'user-registration-aide' ),
			'last_first_name'	=>	__( 'Last - First Name', 'user-registration-aide' )		
		);
		return $display_fields;
	}
		
	/**	
	 * function update_current_user_display_names
	 * Updates existing users display name to chosen field by role
	 * @since 1.4.0.0
	 * @updated 1.5.2.0
	 * @params 
	 * @returns string $msg ( results of function success or failure ) 
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function update_current_user_display_names(){
		global $wp_roles;
		$options = get_option('csds_userRegAide_Options');
		$msg = ( string ) '';
		if( $options['custom_display_name'] == 2 ){
			
			$msg = '<div id="message" class="error"><p>'. __( 'You Must Set The Use Custom Display Name Field to Yes to Use This Feature!', 'user-registration-aide' ).'</p></div>';
			
		}elseif( $options['custom_display_name'] == 1 ){
			$roles = $wp_roles->get_names();
			$custom_display_role = $options['display_name_role'];
			$custom_display_name = $options['custom_display_field'];
			
			
			if( $custom_display_role == 'all_roles' ){
				$user_args = array(
					'fields' 	=>	'all_with_meta'
				);
				$this->update_user_display_names( $user_args, $custom_display_name );
			// Admin specified custom role
			}else{			
				$user_args = array(
					'role' 	=>	$custom_display_role
				);
				$msg = $this->update_user_display_names( $user_args, $custom_display_name );
			}
			
			if( $custom_display_role != 'all_roles' ){
				foreach( $roles as $rkey => $rvalue ){
					if( !in_array( $rkey, $custom_display_role ) ){
						$user_args = array(
							'role' 	=>	$rkey
						);
						$msg = $this->check_unused_roles_display_names( $user_args, $custom_display_name );
					}
				}
			}
			$msg = '<div id="message" class="updated"><p>'. __( 'Current Display Names Updated!', 'user-registration-aide' ).'</p></div>';
			
		}
		return $msg;
	}
	
	/**	
	 * function check_unused_roles_display_names
	 * Reverts previously changed users display name to default display name by role if roles 
	 * have been changed and user display name was not changed back to original
	 * @since 1.4.0.0
	 * @updated 1.5.2.0
	 * @params array $user_args, string $custom_display_name
	 * @returns string $msg ( results of function success or failure )
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function check_unused_roles_display_names( $user_args, $custom_display_name ){
		global $wpdb;
		$field = (string) '';
		$users = get_users($user_args);
		$display_name = (string) '';
		$old_display_name = (string) '';
		$results = ( int ) 0;
		
		foreach( $users as $user ){
		
			// backing up original display name just in case it need to be restored
			$field = get_user_meta( $user->ID, 'default_display_name', true );
			if( $field ){
				$display_name = $field;
			}else{
				$display_name = $user->user_login;
			}
			
			if( empty( $field ) ){
				update_user_meta( $user->ID, 'default_display_name', $display_name );
			}
			
			// updating new display name
			$add_display_name = $wpdb->prepare( "UPDATE $wpdb->users SET display_name = %s WHERE ID = %d", $display_name, $user->ID );
			$results = $wpdb->query( $add_display_name );
		}
		if( $results === false ){
			$msg = '<div id="message" class="error"><p>'. __( 'Error Restoring Display Names!', 'user-registration-aide' ).'</p></div>';
		}else{
			$msg = '<div id="message" class="updated"><p>'. __( 'Display Names Restored!', 'user-registration-aide' ).'</p></div>';
		}
		return $msg;
	}
		
	/**	
	 * function update_user_display_names
	 * Function to process new display name updates 
	 * @since 1.4.0.0
	 * @updated 1.5.2.0
	 * @params array $user_args, string $custom_display_name
	 * @returns string $msg ( results of function success or failure )
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function update_user_display_names( $user_args, $custom_display_name ){
		global $wpdb;
		$field = (string) '';
		$users = get_users( $user_args );
		$display_name = (string) '';
		$old_display_name = (string) '';
		$results = ( int ) 0;
		foreach( $users as $user ){
		
			if( $custom_display_name == 'first_last_name' ){
				$display_name = $user->first_name.' '.$user->last_name;
			}elseif( $custom_display_name == 'last_first_name' ){
				$display_name = $user->last_name.' '.$user->first_name;
			}elseif( $custom_display_name == 'first_name' ){
				$display_name = $user->first_name;
			}elseif( $custom_display_name == 'last_name' ){
				$display_name = $user->last_name;
			}elseif( $custom_display_name == 'nickname' ){
				$display_name = $user->nickname;
			}
			
			// backing up original display name just in case it need to be restored
			if( $user->display_name ){
				$old_display_name = $user->display_name;
			}else{
				$old_display_name = $user->user_login;
			}
			
			$field = get_user_meta( $user->ID, 'default_display_name', true );
			if( empty( $field ) ){
				update_user_meta( $user->ID, 'default_display_name', $old_display_name );
			}
			
			// updating new display name
			$add_display_name = $wpdb->prepare( "UPDATE $wpdb->users SET display_name = %s WHERE ID = %d", $display_name, $user->ID );
			$results = $wpdb->query( $add_display_name );
			
		}
		if( $results === false ){
			$msg = '<div id="message" class="error"><p>'. __( 'Error Update Display Names!', 'user-registration-aide' ).'</p></div>';
		}else{
			$msg = '<div id="message" class="updated"><p>'. __( 'Display Names Updated!', 'user-registration-aide' ).'</p></div>';
		}
		return $msg;
	}
	
	/**	
	 * function restore_user_display_names
	 * Restores default users display name to chosen field by role
	 * @since 1.4.0.0
	 * @updated 1.5.2.0
	 * @params
	 * @returns string $msg ( results of function success or failure )
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function restore_user_display_names(){
		global $wpdb;
		$options = get_option('csds_userRegAide_Options');
		$custom_display_name = $options['custom_display_field'];
		$user_args = array(
			'fields' 	=>	'all_with_meta'
		);
		$field = (string) '';
		$results = ( int ) 0;
		$users = get_users($user_args);
		$display_name = (string) '';
		
		foreach( $users as $user ){
			$field = get_user_meta( $user->ID, 'default_display_name', true );
			if( empty( $field ) ){
				$display_name = $user->user_login;
			}else{
				$display_name = $field;
			}
			$add_display_name = $wpdb->prepare( "UPDATE $wpdb->users SET display_name = %s WHERE ID = %d", $display_name, $user->ID );
			$results = $wpdb->query( $add_display_name );
		}
		$options['custom_display_name'] = "2";
		$options['custom_display_role'] = "2";
		update_option( "csds_userRegAide_Options", $options );
		if( $results === false ){
			$msg = '<div class="error"><p>'. __( 'Error Restoring Display Names!', 'user-registration-aide' ).'</p></div>';
		}else{
			$msg = '<div class="updated"><p>'. __( 'Display Names Restored!', 'user-registration-aide' ).'</p></div>';
		}
		return $msg;
	}
}