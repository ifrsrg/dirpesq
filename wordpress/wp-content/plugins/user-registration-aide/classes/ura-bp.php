<?php

/**
 * Class URA_BP_FUNCTIONS
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/


class URA_BP_FUNCTIONS
{
	/** 
	 * function xprofile_update_wp_meta
	 * Updates Wordpress Meta fields when user updates buddypress xprofile fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $user_id which is current BP user id
	 * @returns
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/

	function xprofile_update_wp_meta( $user_id ) {
		global $wpdb;
		$options = get_option( 'csds_uraFieldOptions' );
		$options['user_password'] = 1;
		$fields = new FIELDS_DATABASE();
		$table_name = $wpdb->prefix.'bp_xprofile_data';
		$field_key = (string) '';
		$all_info = array();
		$field_id = (int) 0;
		//$query = "SELECT * FROM $table_name WHERE user_id = %d AND last_updated BETWEEN date_sub( now(), INTERVAL 1 HOUR ) AND now()";
		$query = "SELECT * FROM $table_name WHERE user_id = %d AND last_updated > date_sub( now(), INTERVAL 1 HOUR )";
		$query = $wpdb->prepare( $query, $user_id );
		$results = $wpdb->get_results( $query, ARRAY_A );
		//exit( print_r( $results ) );
		foreach( $results as $result ){
			//exit( print_r( $result ) );
			$field_id = $result['field_id'];
			$field = $fields->get_bp_id( $field_id );
			$value = maybe_unserialize( $result['value'] );
			$key = $field->meta_key;
			$type = $field->data_type;
			
			if( $field->data_type != 'option' ){
				if( $field_id == '1' ){
					wp_update_user( array( 'ID' => $user_id, 'display_name' => $value ) );
				}else{
					if( $type == 'datebox' ){
						$temp = explode( " ", $value );
						$update = $temp[0];
						update_user_meta( $user_id, $key, $update );
					}else{
						if( !is_array( $value ) ){
							update_user_meta( $user_id, $key, $value );
						}else{
							$value = maybe_serialize( $value );
							update_user_meta( $user_id, $key, $value );
						}
					}
				}
			}
		}
		
	}
	
	/** 
	 * function add_bp_field
	 * Adds new fields for buddypress xprofile
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $type ( field data type ), string $name ( field name ), $desc ( field description ) 
	 * int $required ( 0 false 1 true ), string $key ( unique field meta key )
	 * @returns string $bp_field_results ( results of inserting new field into Buddy Press )
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function add_bp_field( $type, $name, $desc, $required, $key ){
		
		$object_id = (int) 0;
		$object_type = (string) '';
		$meta_key = (string) '';
		$meta_value = (string) '';
		$meta_key_2 = (string) '';
		$meta_value_2 = (string) '';
		$unique = (boolean) false;
		$meta_results = (boolean) false;
		$meta_results_2 = (boolean) false;
		$field_group = 1;
		$choices = array();
		$options = get_option( 'csds_uraFieldOptions' );
		$count = $this->bp_fields_count();
		//$count += 1;
		if( !empty( $options ) ){
			if( array_key_exists( $key, $options ) ){
				$choices = $options[$key];
				$cnts = explode( ',', $choices );
				$cnt = count( $cnts );
			}
		}
		
		if( !empty( $field_group ) ){
			$bp_args = array(
				'field_id' => null,
				'field_group_id' => $field_group,
				'parent_id' => null,
				'type' => $type,
				'name' => $name,
				'description' => $desc,
				'is_required' => $required,
				'can_delete' => true,
				'order_by' => '',
				'is_default_option' => false,
				'field_order'	=>	$count,
				'option_order' => null,
			);
		}
		
		$bp_field_results = xprofile_insert_field( $bp_args );
		
		$object_id = $bp_field_results;
		$object_type = 'field';
		$meta_key = 'default_visibility';
		$meta_key_2 = 'allow_custom_visibility';
		
		if( isset( $_POST['default_visibility'] ) ){
			$meta_value = $_POST['default_visibility'];
		}else{
			$meta_value = '';
		}
		
		if( isset( $_POST['allow_custom_visibility'] ) ){
			$meta_value_2 = $_POST['allow_custom_visibility'];
		}else{
			$meta_value_2 = '';
		}
		$meta_results = bp_xprofile_add_meta( $object_id, $object_type, $meta_key, $meta_value, $unique ); 
		$meta_results_2 = bp_xprofile_add_meta( $object_id, $object_type, $meta_key_2, $meta_value_2, $unique ); 
		return $bp_field_results;
	
	}
	
	/** 
	 * function bp_fields_count
	 * Counts BP NON OPTION Fields in BP XPROFILE Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns int $count ( count of bp parent fields, not including option fields )
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function bp_fields_count(){
		global $wpdb;
		$option = (string) 'option';
		$table_name = $wpdb->prefix . 'bp_xprofile_fields';
		$sql = "SELECT COUNT(id) FROM $table_name WHERE type != %s";
		$run_query = $wpdb->prepare( $sql, $option );
		$count = $wpdb->get_var( $run_query );
		//$count += 1;
		return $count;
	}
	
	/** 
	 * function add_bp_field_option
	 * Adds new fields options for buddypress xprofile fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $bp_id parent field id
	 * @params string $field for field name
	 * @params boolean $default_option whether option is default option or not
	 * @params int $index for option order
	 * @params string $order_by how the group is sorted
	 * @params string $key which is the option key if select or another field with multiple options
	 * @returns bool|int False on failure, ID of new field option on success.
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function add_bp_field_option( $bp_id, $field, $default_option, $index, $order_by, $key ){
		global $wpdb;
		$fields = new FIELDS_DATABASE();
		$object_id = (int) 0;
		$object_type = (string) '';
		$meta_key = (string) '';
		$meta_value = (string) '';
		$meta_key_2 = (string) '';
		$meta_value_2 = (string) '';
		$unique = (boolean) false;
		$meta_results = (boolean) false;
		$meta_results_2 = (boolean) false;
		$field_group = 1;
		$temp = $fields->get_field_by_meta_key( $key );
		$id = $temp->ID;
		$cnt = $fields->options_count( $id );
		$items = (int) 0;		
		$is_default_option = (int) 0;
		$order = (int) 0;
		$order = $index + 1;
		
		if( $index == 0 ){
			if( !empty( $field_group ) ){
				$bp_args = array(
					'field_id' => null,
					'field_group_id' => $field_group,
					'parent_id' => $bp_id,
					'type' => 'option',
					'name' => $field,
					'description' => '',
					'is_required' => '',
					'can_delete' => true,
					'order_by' => $order_by,
					'is_default_option' => $default_option,
					'option_order' => $order,
				);
			}
		}else{
			if( !empty( $field_group ) ){
				$bp_args = array(
					'field_id' => null,
					'field_group_id' => $field_group,
					'parent_id' => $bp_id,
					'type' => 'option',
					'name' => $field,
					'description' => '',
					'is_required' => '',
					'can_delete' => true,
					'order_by' => $order_by,
					'is_default_option' => $default_option,
					'option_order' => $order,
				);
			}
		}
		
		$bp_field_results = xprofile_insert_field( $bp_args );
		$object_id = $bp_field_results;
		if( $order == 1 && !empty( $object_id ) ){
			$table = $wpdb->prefix.'bp_xprofile_fields';
			$data = array(
				'is_default_option'	=>	'1'
			);
			$where = array(
				'id'	=>	$object_id
			);
			$wpdb->update( $table, $data, $where );
			
		}
		$object_type = 'field';
		$meta_key = 'default_visibility';
		$meta_key_2 = 'allow_custom_visibility';
		if( isset( $_POST['default_visibility'] ) ){
			$meta_value = $_POST['default_visibility'];
		}else{
			$meta_value = '';
		}
		
		if( isset( $_POST['allow_custom_visibility'] ) ){
			$meta_value_2 = $_POST['allow_custom_visibility'];
		}else{
			$meta_value_2 = '';
		}
		$meta_results = bp_xprofile_add_meta( $object_id, $object_type, $meta_key, $meta_value, $unique ); 
		$meta_results_2 = bp_xprofile_add_meta( $object_id, $object_type, $meta_key_2, $meta_value_2, $unique ); 
		
		return $bp_field_results;
	
	}
		
	/** 
	 * function update_bp_profile
	 * Updates buddypress xprofile fields when user updates Wordpress Profile fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $user_id current user id
	 * @params BP OBJECT $field BP FIELD OBJECT
	 * @params string $value Field Value
	 * @returns bool|int False on failure, ID of new field option on success.
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function update_bp_profile( $user_id, $field, $value ){
		global $wpdb;
		$update = (string) '';
		$insert = (string) '';
		$results = (string) '';
		$exists = (int) 0;
		$table_name = (string) '';
		$args = array();
		$table_name = $wpdb->prefix.'bp_xprofile_data';
		$exists = $wpdb->get_var( "SELECT id FROM $table_name WHERE field_id = $field AND user_id = $user_id" );
		if( !empty( $value ) ){
			if( empty( $exists ) || $exists <= 0 ){
				$insert = "INSERT INTO " . $table_name . " ( field_id, user_id, value, last_updated ) " .
						"VALUES ('%d', '%d', '%s', now())";
				$insert = $wpdb->prepare( $insert, $field, $user_id, $value );
				$results = $wpdb->query( $insert );
			}else{
				$update = "UPDATE $table_name SET value = %s, last_updated = now() WHERE field_id = '%d' AND user_id = '%d'";
				$update = $wpdb->prepare( $update, $value, $field, $user_id );
				$results = $wpdb->query( $update );
			}
			return $results;
		}else{
			$results = 0;
			return $results;
		}
		
			
	}
	
	/** 
	 * function user_approve_update_bp_profile
	 * Updates buddypress xprofile fields when new user approved from URA 
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $user_id current user id
	 * @params BP OBJECT $field BP FIELD OBJECT
	 * @params string $value Field Value
	 * @params boolean $is_required
	 * @returns
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function user_approve_update_bp_profile( $user_id, $field, $value, $is_required ){
		$results = xprofile_set_field_data( $field, $user_id, $value, $is_required );
	
	}

	/** 
	 * function transfer_signups_table
	 * Gets all unactived signups and transfers them to ura so they can be properly displayed 
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $parent_id
	 * @returns OBJECT $options all options for that parent id
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function transfer_signups_table(){
		global $wpdb;
		$options = get_option( 'csds_userRegAide_Options' );
		$approve = $options['new_user_approve'];
		$approval = $options['buddy_press_approval'];
		$verify = $options['verify_email'];
		$field = new FIELDS_DATABASE();
		$fields = $field->get_all_fields();
		$signups = $this->get_unactivated_signups();
		//exit( print_r( $signups ) );
		foreach( $signups as $signup ){
			$user_name = $signup->user_login;
			$user_email = $signup->user_email;
			$meta = $signup->meta;
			$new_meta = maybe_unserialize( $meta );
			$random_password = $new_meta['password'];
			//exit( print_r( $new_meta ) );
			//exit( $user_name.' - '.$user_email.' - '.$random_password );
			$user_id = wp_create_user( $user_name, $random_password, $user_email );
			//exit( print_r( $user_id ) );
			if( !is_wp_error( $user_id ) ){
				$activation_key = $signup->activation_key;
				// new user approval update
				if( $approve == '1' ){
					update_user_meta( $user_id, 'approval_status', 'pending' );
					$table_name = $wpdb->prefix.'users';
					$data = array(
						'user_activation_key' => $activation_key
					);
					$where = array(
						'ID' => $user_id
					);
					$wpdb->update( $table_name, $data, $where );
					$table_name = $wpdb->prefix.'users';
					$update_sql = "UPDATE $table_name SET user_status = 2 WHERE ID = %d";
					$update = $wpdb->prepare( $update_sql, $user_id );
					$results = $wpdb->query( $update );
				}else{
					update_user_meta( $user_id, 'approval_status', 'approved' );
				}
			
				
				foreach( $fields as $object ){
					$thisValue = $object->meta_key;
					$bpvalue = $object->bp_ID;
					$type = $object->data_type;
					$bpvalue = 'field_'.$bpvalue;
					$bpkey = $object->meta_key;
					if( isset( $_POST[$bpvalue] ) ){
						exit( 'ISSET' );
						if( is_array( $_POST[$bpvalue] ) ){
							$temp = $_POST[$bpvalue];
							$arr = true;
							if( !empty( $temp ) ){
								if( is_array( $temp ) ){
									foreach( $temp as $tkey => $tvalue ){
										$tvalue = apply_filters( 'pre_user_description', $tvalue );
										$temp[$tkey] = $tvalue;
									}
								}else{
									$temp = apply_filters( 'pre_user_description', $temp );
								}
								
							}
							$newValue1 = serialize( $temp );
						}else{
							if( $type != 'datebox' ){
								$newValue = apply_filters('pre_user_description', $_POST[$bpvalue]);
							}else{
								$value = apply_filters('pre_user_description', $_POST[$bpvalue]);
								$temp = explode( " ", $value );
								$newValue = $temp[0];
							}
						}
						
						if( $arr == true ){
							update_user_meta( $user_id, $bpkey, $newValue1);
							$arr = false;
						}elseif( $arr == false ){
							update_user_meta( $user_id, $bpkey, $newValue);
						}
						
						
					}
					$arr = false;
				}
							
				update_user_meta( $user_id, 'email_verification', 'unverified' );
			}else{
				$user = get_user_by( 'login', $user_name );
				$user_id = $user->ID;
				$activation_key = $signup->activation_key;
				// new user approval update
				if( $approve == '1' ){
					update_user_meta( $user_id, 'approval_status', 'pending' );
					$table_name = $wpdb->prefix.'users';
					$data = array(
						'user_activation_key' => $activation_key
					);
					$where = array(
						'ID' => $user_id
					);
					$wpdb->update( $table_name, $data, $where );
					$table_name = $wpdb->prefix.'users';
					$update_sql = "UPDATE $table_name SET user_status = 2 WHERE ID = %d";
					$update = $wpdb->prepare( $update_sql, $user_id );
					$results = $wpdb->query( $update );
				}else{
					update_user_meta( $user_id, 'approval_status', 'approved' );
				}
			
				
				foreach( $fields as $object ){
					$thisValue = $object->meta_key;
					$bpvalue = $object->bp_ID;
					$type = $object->data_type;
					$bpvalue = 'field_'.$bpvalue;
					$bpkey = $object->meta_key;
					if( isset( $_POST[$bpvalue] ) ){
						exit( 'ISSET' );
						if( is_array( $_POST[$bpvalue] ) ){
							$temp = $_POST[$bpvalue];
							$arr = true;
							if( !empty( $temp ) ){
								if( is_array( $temp ) ){
									foreach( $temp as $tkey => $tvalue ){
										$tvalue = apply_filters( 'pre_user_description', $tvalue );
										$temp[$tkey] = $tvalue;
									}
								}else{
									$temp = apply_filters( 'pre_user_description', $temp );
								}
								
							}
							$newValue1 = serialize( $temp );
						}else{
							if( $type != 'datebox' ){
								$newValue = apply_filters('pre_user_description', $_POST[$bpvalue]);
							}else{
								$value = apply_filters('pre_user_description', $_POST[$bpvalue]);
								$temp = explode( " ", $value );
								$newValue = $temp[0];
							}
						}
						
						if( $arr == true ){
							update_user_meta( $user_id, $bpkey, $newValue1);
							$arr = false;
						}elseif( $arr == false ){
							update_user_meta( $user_id, $bpkey, $newValue);
						}
						
						
					}
					$arr = false;
				}
							
				update_user_meta( $user_id, 'email_verification', 'unverified' );
			}
			
		}
		
	}
	
	/** 
	 * function get_unactivated_signups
	 * Gets all the signups that haven't been activated yet and transfers them for viewing
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns OBJECT $signups of all unactivated signups users
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function get_unactivated_signups(){
		global $wpdb;
		$value = 'IS NULL';
		$table_name = $wpdb->prefix . 'signups';
		$sql = "SELECT * FROM $table_name WHERE activated IS NULL";
		$signups = $wpdb->get_results( $sql, OBJECT );
		return $signups;
	}
	
	/**
	 * Updates Wordpress Fields when user signs up on BuddyPress Registration Form  
	 * 
	 * @category function bp_signup_update_wp_fields
	 * @since 1.6.0.0
	 * @access public
	 * 
	 *
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	/** 
	 * function bp_signup_update_wp_fields
	 * Updates Wordpress Fields when user signs up on BuddyPress Registration Form
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function bp_signup_update_wp_fields(){
		global $wpdb;
		// variables declare
		$user_email = (string) '';
		$user_name = (string) '';
		$user_id = (int) 0;
		$xwrd = (string) '';
		$arr = (boolean) false;
		$temp = array();
		$tmp_str = (string) '';
		$activation_key = (string) '';
		$random_password = (string) '';
		$approve = (int) 0;
		$addData = (string) '';
		$newPass = (string) '';
		$newValue = (string) '';
		$newWebsite = (string) '';
		$newValue1 = array();
		$tvalue = (string) '';
		$tkey = (string) '';
		$userID = (int) 0;
		$table_name = (string) '';
		$sql = (string) '';
		
		$field = new FIELDS_DATABASE();
		$regFields = $field->get_bp_registration_fields();
		$options = get_option( 'csds_userRegAide_Options' );
		$approve = $options['new_user_approve'];
		$approval = $options['buddy_press_approval'];
		$verify = $options['verify_email'];
		if( isset( $_POST['signup_username'] ) ){
			$user_name = $_POST['signup_username'];
		}
		
		if( isset( $_POST['signup_email'] ) ){
			$user_email = $_POST['signup_email'];
		}
		
		if( isset( $_POST['signup_password'] ) ){
			$xwrd = $_POST['signup_password'];
		}
		
		if( isset( $_POST['signup_submit'] ) ){
			$user_id = username_exists( $user_name );
			if ( !$user_id and email_exists( $user_email ) == false ) {
				if( !empty( $xwrd ) ){
					$random_password = $xwrd;
					$user_id = wp_create_user( $user_name, $random_password, $user_email );
				}else{
					$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
					$user_id = wp_create_user( $user_name, $random_password, $user_email );
				}
			} else {
				$random_password = __('User already exists.  Password inherited.');
			}
			
			if( !empty( $user_id ) ){
				$activation_key = wp_hash( $user_id );
				// new user approval update
				if( $approve == '1' ){
					update_user_meta( $user_id, 'approval_status', 'pending' );
					$table_name = $wpdb->prefix.'users';
					$data = array(
						'user_activation_key' => $activation_key
					);
					$where = array(
						'ID' => $user_id
					);
					$wpdb->update( $table_name, $data, $where );
				}else{
					update_user_meta( $user_id, 'approval_status', 'approved' );
				}
			}
					
			if( !empty( $regFields ) ){
				foreach( $regFields as $object ){
					$thisValue = $object->meta_key;
					$bpvalue = $object->bp_ID;
					$type = $object->data_type;
					$bpvalue = 'field_'.$bpvalue;
					$bpkey = $object->meta_key;
					if( isset( $_POST[$bpvalue] ) ){
						if($thisValue == "first_name"){
							$newValue = apply_filters('pre_user_first_name', $_POST[$bpvalue]);
							$fname = apply_filters('pre_user_first_name', $_POST[$bpvalue]);
						}elseif($thisValue == "last_name"){
							$newValue = apply_filters('pre_user_last_name', $_POST[$bpvalue]);
							$lname = apply_filters('pre_user_first_name', $_POST[$bpvalue]);
						}elseif($thisValue == "nickname"){
							$newValue = apply_filters('pre_user_nickname', $_POST[$bpvalue]);
						}elseif($thisValue == "description"){
							$newValue = apply_filters('pre_user_description', $_POST[$bpvalue]);
						}elseif($thisValue == "user_url"){
							$newWebsite = apply_filters('pre_user_url', $_POST[$bpvalue]);
							$addData = $wpdb->prepare("UPDATE $users_table SET user_url =('$newWebsite') WHERE ID = '$user_id'");
							$wpdb->query($addData);
						}elseif($thisValue == "user_pass"){
							$newPass = $_POST['pass1'];
							$newPass = wp_hash_password($newPass);
							
							$addData = $wpdb->prepare("UPDATE $users_table SET user_pass = %s WHERE ID = %d", $newpass, $user_id);
							$wpdb->query($addData);
							
						}else{
							if( is_array( $_POST[$bpvalue] ) ){
								$temp = $_POST[$bpvalue];
								$arr = true;
								if( !empty( $temp ) ){
									if( is_array( $temp ) ){
										foreach( $temp as $tkey => $tvalue ){
											$tvalue = apply_filters( 'pre_user_description', $tvalue );
											$temp[$tkey] = $tvalue;
										}
									}else{
										$temp = apply_filters( 'pre_user_description', $temp );
									}
									
								}
								$newValue1 = serialize( $temp );
							}else{
								if( $type != 'datebox' ){
									$newValue = apply_filters('pre_user_description', $_POST[$bpvalue]);
								}else{
									$value = apply_filters('pre_user_description', $_POST[$bpvalue]);
									$temp = explode( " ", $value );
									$newValue = $temp[0];
								}
							}
						}
						if( $arr == true ){
							update_user_meta( $user_id, $bpkey, $newValue1);
							$arr = false;
						}elseif( $arr == false ){
							update_user_meta( $user_id, $bpkey, $newValue);
						}
					}
			
					$arr = false;
				}
			}
						
			if( $approval == 2 && $verify == 1 ){
				update_user_meta( $user_id, 'email_verification', 'unverified' );
			}
			
			$plugin = 'buddypress/bp-loader.php';
			if( is_plugin_active( $plugin ) ){
				update_user_meta( $user_id, 'email_verification', 'unverified' );
			}
		}
		
	}
	
	/** 
	 * function bp_register_form
	 * Updates buddypress registration page when user chooses to use WordPress default or buddypress registration page
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $bp_register 
	 * @returns string $existing_pages ( current registration form page or wordpress registration form page )
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function bp_register_form( $bp_register ){
		global $bp, $wpdb;

		// Get the existing WP pages
		$existing_pages = bp_core_get_directory_page_ids();

		// Set up an array of components (along with component names) that have
		// directory pages.
		$directory_pages = array();

		// Loop through loaded components and collect directories
		if ( is_array( $bp->loaded_components ) ) {
			foreach( $bp->loaded_components as $component_slug => $component_id ) {

				// Only components that need directories should be listed here
				if ( isset( $bp->{$component_id} ) && !empty( $bp->{$component_id}->has_directory ) ) {

					// component->name was introduced in BP 1.5, so we must provide a fallback
					$directory_pages[$component_id] = !empty( $bp->{$component_id}->name ) ? $bp->{$component_id}->name : ucwords( $component_id );
				}
			}
		}
		// bp_setup_current_user
		/** Directory Display *****************************************************/

		$directory_pages = apply_filters( 'bp_directory_pages', $directory_pages );
			
		$table_name = $wpdb->prefix.'posts';
		$page_ids = bp_core_get_directory_pages();
		$page_id = $wpdb->get_var( "SELECT ID FROM {$table_name} WHERE post_title = 'Register' " );
		
		if( $bp_register == '2' ){
			unset( $existing_pages['register'] );
		}else{
			$existing_pages['register'] = $page_id;
		}
		bp_core_update_directory_page_ids( $existing_pages );
		//wp_cache_set( 'directory_pages', $pages, 'bp' );
			
		return $existing_pages;
	}
	
	/**
	 * Updates buddypress page ids when user chooses to use WordPress default or buddypress registration page  
	 * 
	 * @category function update_page_id
	 * @since 1.6.0.0
	 * @access public
	 * @params array $page_ids
	 * 
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	/** 
	 * function update_page_ids
	 * Updates buddypress page ids when user chooses to use WordPress default or buddypress registration page
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params array $page_ids
	 * @returns array $page_ids
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
		
	function update_page_ids( $page_ids ){
		$plugin = 'buddypress/bp-loader.php';
		if( is_plugin_active( $plugin ) ){
			$update = get_option( 'csds_userRegAide_Options' );
			if( in_array( 'register', $page_ids ) ){
				$update['buddy_press_registration'] = 1;
			}elseif( array_key_exists( 'register', $page_ids ) ){
				$update['buddy_press_registration'] = 1;
			}else{
				$update['buddy_press_registration'] = 2;
			}
			update_option("csds_userRegAide_Options", $update);
			return $page_ids;
		}else{
			return;
		}
	}
		
	/** 
	 * function bp_activated
	 * Updates user meta for approval status when new user activated with Buddypress
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $user_id
	 * @params string $key
	 * @params WordPress Object $user
	 * @returns
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function bp_activated( $user_id, $key, $user ){
		$options = get_option('csds_userRegAide_Options');
		$verified = get_user_meta( $user_id, 'email_verification', true );
		$approved = get_user_meta( $user_id, 'approved_email', true );
		$verify = $options['verify_email'];
		$approve = $options['new_user_approve'];
		$status = get_user_meta( $user_id, 'approval_status', true );
		$bp_approve = $options['buddy_press_approval'];
		if( empty( $approved ) || $approved == 0 ){
			if( empty( $verified ) || $verified == 'unverified' ){
				do_action( 'bp_email_verified', $user_id );
			}
		}
		if( $approve == 1 && $bp_approve == 2 ){
			//update_user_meta( $user_id, 'approval_status', 'approved' );
			update_user_meta( $user_id, 'email_verification', 'verified' );
			
			
			do_action( 'sync_bp_xprofile_fields' );
		}
		
	}
		
	/** 
	 * function sync_bp_fields
	 * Syncs Existing BuddyPress Fields and transfers fields and data to WordPress user profiles and meta
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns boolean true for success or false for failure
	*/
	
	function sync_bp_fields(){
		global $wpdb;	
		$options = get_option('csds_userRegAide_Options');
		$plugin = 'buddypress/bp-loader.php';
		$class = 'BuddyPress';
		$msg = (string) '';
		$error = (int) 0;
		$order = (int) 1;
		if ( class_exists( $class ) ){
			if( is_plugin_active( $plugin ) ){
				if( !empty( $options ) ){
					if( $options['buddy_press_fields_sync'] == 2 || $options['buddy_press_fields_sync'] == "2" ){
						$table_name = $wpdb->prefix . "ura_fields";
						if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
							$field = new FIELDS_DATABASE();
							$field->create_fields_database();
							unset( $field );
						}
						$req_fields = get_option( 'csds_userRegAide_registrationFields' );
						$temp_field_key = (string) '';
						$all_info = array();
						$field_id = (int) 0;
						$field_name = (string) '';
						$field_title = (string) '';
						$option_str = (string) 'option';
						$objects = $this->select_bp_fields();
						$cnt = (int) 1;
						
						$foption = (string) '';
						$length = (int) 0;
						$max_length = (int) 29;
						$error = (int) 0;
						$msg = (string) '';
						$meta_key = (string) '';
						foreach( $objects as $object ){
							if( $object->id != '1' ){
								//exit( print_r( $object ) );
								$field = new FIELDS_DATABASE();
								$field_id = $object->id;
								$field_name = $object->name;
								$parent_id = $object->parent_id;
								$group_id = $object->group_id;
								$data_type = $object->type;
								$length = strlen( $field_name );
								$temp_field_key = explode( ' ', $object->name );
								$temp_field_key = implode( '_', $temp_field_key );
								$field_key_temp = strtolower( $temp_field_key );
								$field_key = preg_replace("/[^a-zA-Z0-9\s]/", "", $field_key_temp);
								if( $length > $max_length ){
									$field_key = substr( $field_key, 0, $max_length );
								}
								
								$key_exists = $field->meta_key_exists( $field_key );
												
								if( empty( $key_exists ) ){
									$field_key = $field_key;
								}else{
									$exists = $field->meta_key_change( $field_key );
									if( empty( $exists ) ){
										$msg .= $field_key.' Meta Key already exists! ';
										$error++;
									}else{
										$field_key = $exists;
									}
								}
								
								$name_exists = $field->field_name_exists( $field_name );
								
								if( empty( $name_exists ) ){
									$field_name = $field_name;
								}else{
									$fieldname_exists = $field->field_name_change( $field_name );
									if( empty( $fieldname_exists ) ) {
										$msg .= $field_name.' Field Title already exists! ';
										$error++;
									}else{
										$field_name = $fieldname_exists;
									}
								}
								
								if( $error == 0 ){
									$field->meta_key = $field_key;
									$field->field_name = $field_name;
									$field->data_type = $data_type;
									$field->field_description = $object->description;
									$field->field_required = $object->is_required;
									$field->is_default_option = $object->is_default_option;
									$field->field_order = $order;
									$field->approve_view = '0';
									$field->bp_id = $field_id;
									$field->bp_parent_id = $parent_id;
									$field->bp_group_id = $group_id;
									$field->insert_fields( $field );
									
									$order++;
									
									if( $object->is_required == true ){
										$req_fields[$field_key] = $field_name;
										update_option( 'csds_userRegAide_registrationFields', $req_fields );
									}
									$parent_id = $field->get_field_id_by_meta_key( $field_key );
									$field_id = $object->id;
									$table_name = $wpdb->prefix.'bp_xprofile_fields';
									$options_query = "SELECT * FROM $table_name WHERE parent_id = %d";
									$options_query = $wpdb->prepare( $options_query, $field_id );
									$bp_options = $wpdb->get_results( $options_query, OBJECT );
									$id = $field->get_field_id( $field_id );
									if( !empty( $bp_options ) ){
										foreach( $bp_options as $option ){
											$option_name = $option->name;
											$option_id = $option->id;
											$length = strlen( $field_name );
											$temp_option_key = explode( ' ', $option->name );
											$temp_option_key = implode( '_', $temp_option_key );
											$option_key_temp = strtolower( $temp_option_key );
											$option_key = preg_replace( "/[^a-zA-Z0-9\s]/", "", $option_key_temp );
											if( $length > 30 ){
												$option_key = substr( $option_key, 0, 30 );
											}
											
											$option_key_exists = $field->meta_key_exists( $option_key );
												
											if( empty( $option_key_exists ) ){
												$option_key = $option_key;
											}else{
												$exists = $field->meta_key_change( $option_key );
												if( empty( $exists ) ){
													$msg .= $option_key.' Meta Key already exists! ';
													$error++;
												}else{
													$option_key = $exists;
												}
											}
											
											$option_name_exists = $field->field_name_exists( $option_name );
											
											if( empty( $option_name_exists ) ){
												$option_name = $option_name;
											}else{
												$fieldname_exists = $field->field_name_change( $option_name );
												if( empty( $fieldname_exists ) ) {
													$msg .= $option_name.' Field Title already exists! ';
													$error++;
												}else{
													$option_name = $fieldname_exists;
												}
											}
											
											$field->meta_key = $option_key;
											$field->parent_id = $parent_id;
											$field->field_name = $option_name;
											$field->data_type = 'option';
											$field->field_description = $option->description;
											$field->field_required = $option->is_required;
											$field->is_default_option = $option->is_default_option;
											$field->field_order = 0;
											$field->option_order = $cnt;
											$field->bp_id = $option->id;
											$field->bp_parent_id = $option->parent_id;
											$field->bp_group_id = $option->group_id;
											$field->insert_fields( $field );
											$cnt++;
										}
									}
									
									//do_action( 'update_new_field_order', $field_key );
									
									// getting list of user ids
									$table_name = $wpdb->prefix.'users';
									$users_id_query = "SELECT ID FROM $table_name";
									$user_ids = $wpdb->get_col( $users_id_query );
									
									// updating wordpress profile user meta from buddypress xprofile data
									$table_name = $wpdb->prefix.'bp_xprofile_data';
									foreach( $user_ids as $id ){
										$update_query = "SELECT value FROM $table_name WHERE field_id = %d AND user_id = %d";
										$update_query = $wpdb->prepare( $update_query, $field_id, $id );
										$update_results = $wpdb->get_var( $update_query );
										if( !empty( $update_results ) ){
											if( $data_type != 'datebox' ){
												update_user_meta( $id, $field_key, $update_results );
											}else{
												$temp = explode( " ", $update_results );
												$update = $temp[0];
												update_user_meta( $id, $field_key, $update );
											}
										}
										
									}
									
								}
							}
							$error = 0;
							$cnt = 1;
						}
						$options['buddy_press_fields_sync'] = 1;
						update_option( 'csds_userRegAide_Options', $options );
					}
				}
			}
			
			return true;
		}
		
	}
	
	/** 
	 * function sync_wp_to_bp_fields
	 * Syncs WordPress Fields and transfers fields and data to BuddyPress xprofiles fields and data
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function sync_wp_to_bp_fields(){
		global $wpdb;
		$options = get_option('csds_userRegAide_Options');
		$plugin = 'buddypress/bp-loader.php';
		$class = 'BuddyPress';
		if ( class_exists( $class ) ){
			if( is_plugin_active( $plugin ) ){
				if( $options['ura_fields_sync'] == 2 ){
					$field = new FIELDS_DATABASE();
					$ura_fields = $field->get_all_fields();
					$table_name = $wpdb->prefix.'bp_xprofile_fields';
					$temp_field_key = (string) '';
					$all_info = array();
					$bp_id = (int) 0;
					$field_name = (string) '';
					$type = (string) '';
					$name = (string) '';
					$desc = (string) '';
					$required = (boolean) false;
					$order_by = (string) '';
					$key = (string) '';
					$cnt = (int) 1;
					$foption = (string) '';
					
					foreach( $ura_fields as $object ){
						$type = $object->data_type;
						$required = $object->field_required;
						$desc = $object->meta_key;
						$name = $object->field_name;
						$key = $object->meta_key;
						
						$bp_id = $this->add_bp_field( $type, $name, $desc, $required, $key );
						$required = false;	
						$options = $field->get_field_options( $object );
						if( !empty( $options ) ){
							foreach( $options as $option ){
								if( $cnt == 1 ){
									$id = $this->add_bp_field_option( $bp_id, $option->field_name, 1, $cnt, $order_by, $key );
								}else{
									$id = $this->add_bp_field_option( $bp_id, $option->field_name, 0, $cnt, $order_by, $key );
								}
								$cnt++;
								$option->parent_id = $bp_id;
								$option->bp_id = $id;
								$field->update_bp_ids( $option );
								$field->update_bp_parent_ids( $option );
							}
						}
						
						// getting list of user ids
						$table_name = $wpdb->prefix.'users';
						$users_id_query = "SELECT ID FROM $table_name";
						$user_ids = $wpdb->get_col( $users_id_query );
						
						// updating BuddyPress xprofile data from WordPress user profile user_meta data
						$table_name = $wpdb->prefix.'bp_xprofile_data';
						foreach( $user_ids as $id ){
							$value = get_user_meta( $id, $field_key, true );
							if( !empty( $bp_id ) ){
								$exists = $wpdb->get_var( "SELECT id FROM $table_name WHERE field_id = $bp_id AND user_id = $id" );
							}else{
								$exists = '';
							}
							if( !empty( $value ) ){
								if( empty( $exists ) || $exists <= 0 ){
									$insert = "INSERT INTO " . $table_name . " ( field_id, user_id, value, last_updated ) " .
											"VALUES ('%d', '%d', '%s', now())";
									$insert = $wpdb->prepare( $insert, $bp_id, $id, $value );
									$results = $wpdb->query( $insert );
								}else{
									$update = "UPDATE $table_name SET value = %s, last_updated = now() WHERE field_id = '%d' AND user_id = '%d'";
									$update = $wpdb->prepare( $update, $value, $bp_id, $id );
									$results = $wpdb->query( $update );
								}
								
							}else{
								$results = 'Nothing to update!';
								
							}
							
						}
						$cnt = 1;
					}
				}
				$options['ura_fields_sync'] = 1;
				update_option( 'csds_userRegAide_Options', $options );
			}
		}
	}
	
	/** 
	 * function check_required_fields
	 * Syncs WordPress Required Fields with BuddyPress required xprofiles fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function check_required_fields(){
		global $wpdb;
		
		$req_fields = get_option( 'csds_userRegAide_registrationFields' );
		
		$temp_field_key = (string) '';
		$all_info = array();
		$field_id = (int) 0;
		$field_name = (string) '';
		$option = 'option';
		$results = $this->select_bp_fields();
		$table_1 = $wpdb->prefix.'ura_fields';
		$query_1 = "SELECT * FROM $table_1 WHERE data_type != %s";
		$query_1 = $wpdb->prepare( $query_1, $option );
		$results_1 = $wpdb->get_results( $query_1, OBJECT );
		$cnt = (int) 0;
		$foption = (string) '';
		if( !empty( $results ) ){
			foreach( $results as $object ){
				if( $object->id != '1' ){
					$field_name = $object->name;
					$field_id = $object->id;
					$temp_field_key = explode( ' ', $object->name );
					$temp_field_key = implode( '_', $temp_field_key );
					$field_key = strtolower( $temp_field_key );
					if( !empty( $results_1 ) ){	
						foreach( $results_1 as $fields ){
							$fields_id = $fields->bp_ID;
							$fields_key = $fields->meta_key;
							$fields_name = $fields->field_name;
							
							if( $object->is_required == true ){
								if( $field_id == $fields_id ){
									if( !array_key_exists( $fields_key, $req_fields ) ){
										if( !in_array( $fields_name, $req_fields ) ){
											$req_fields[$fields_key] = $fields_name;
										}
									}
								}
							}elseif( $object->is_required == false ){
								if( $field_id == $fields_id ){
									if( array_key_exists( $fields_key, $req_fields ) ){
										if( in_array( $fields_name, $req_fields ) ){
											unset( $req_fields[$fields_key] );
										}
									}
								}
							}
						}
					}
				}
				
			}
		}
		update_option( "csds_userRegAide_registrationFields", $req_fields );	
				
	}
	
	/** 
	 * function check_required_fields
	 * Syncs BuddyPress Required XProfile Fields with WordPress URA required Fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns OBJECT $results Results of query for all non option bp fields as database object type
	*/
	
	function select_bp_fields(){
		global $wpdb;
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$option = 'option';
		$query = "SELECT * FROM $table_name WHERE type != %s";
		$query = $wpdb->prepare( $query, $option );
		$results = $wpdb->get_results( $query, OBJECT );
		return $results;
	}
	
	/** 
	 * function check_ura_required_fields
	 * Syncs BuddyPress Required XProfile Fields with WordPress URA required Fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function check_ura_required_fields(){
		global $wpdb;
		// function option arrays 
		$req_fields = get_option( 'csds_userRegAide_registrationFields' );
		// function variables
		$cnt = (int) 0;
		$foption = (string) '';
		$field_id = (int) 0;
		$field_name = (string) '';
		$bpid = (int) 0;
		$field = new FIELDS_DATABASE();
		// database variables & queries 
		$results = $this->select_bp_fields();
		$ura_fields = $field->get_all_fields();
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		foreach( $results as $object ){
			foreach( $ura_fields as $obj ){
				if( $object->id != '1' ){
					$field_name = $object->name;
					$field_id = $object->id;
					$bpid = $obj->bp_ID;
					if( $field_id == $bpid ){
						$field_key = $obj->meta_key;
						if( array_key_exists( $field_key, $req_fields ) ){
							if( $object->is_required == '0' ){
								$update = "UPDATE $table_name SET is_required = %s WHERE id = '%d'";
								$update = $wpdb->prepare( $update, 1, $bpid );
								$results = $wpdb->query( $update );
							}
						}else{
							if( $object->is_required == '1' ){
								$update = "UPDATE $table_name SET is_required = %s WHERE id = '%d'";
								$update = $wpdb->prepare( $update, 0, $bpid );
								$results = $wpdb->query( $update );
							}
						}
					}
				}
			}
		}
				
	}
	
	/** 
	 * function update_bp_fields_orders
	 * Updates BP fields order when fields order updated in URA
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns query $results or WP_Error Object $errors on error
	*/
	
	function update_bp_fields_orders(){
		global $wpdb;
		$errors = new WP_Error();
		$error = (int) 0;
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$update = "UPDATE $table_name SET field_order = %d WHERE id = %d";
		$field = new FIELDS_DATABASE();
		$ura_fields = $field->get_all_fields();
		foreach( $ura_fields as $object ){
			$bpid = $object->bp_ID;
			$order = $object->field_order;
			$order += 1;
			//$update = $wpdb->prepare( $update, $order, $bpid );
			//$results = $wpdb->query( $update );
			$results = $wpdb->query( $wpdb->prepare( $update, $order, $bpid ) );
			if( $results == FALSE ){
				$errors->add('bp_database_field_update_error', __( '<strong>ERROR</strong>:'. $results, 'user-registration-aide' ) );
				$error++;
				//return $errors;
				//exit( print_r( $errors ) );
			}
			
		}
		if( $error == 0 ){
			return $results;
		}else{
			return $errors;
		}
	}
	
	/** 
	 * function update_bp_options_orders
	 * Updates BP options order when options order updated in URA
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $id
	 * @returns query $results or WP_Error Object $errors on error
	*/
	
	function update_bp_options_orders( $id ){
		global $wpdb;
		$errors = new WP_Error();
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$field = new FIELDS_DATABASE();
		$options = $field->get_field_options_edit( $id );
		$update = "UPDATE $table_name SET option_order = %d WHERE id = %d";
		$error = ( int ) 0;
		$results = ( string ) 0;
		if( !empty( $options ) ){
			foreach( $options as $option ){
				$bpid = $option->bp_ID;
				$order = $option->option_order;
				//$order += 1;
				$results = $wpdb->query( $wpdb->prepare( $update, $order, $bpid ) );
				if( $results == FALSE ){
					$errors->add('bp_database_option_update_error', __( '<strong>ERROR</strong>:'. $results, 'user-registration-aide' ) );
					$error++;
					//return $errors;
					//exit( print_r( $errors ) );
				}
			}
		}
		
		if( $error == 0 ){
			return $results;
		}else{
			return $errors;
		}
	}
	
	/** 
	 * function updating_new_fields_order
	 * Updates URA fields order when new fields added with BuddyPress Profile Fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $field_key
	 * @params string $field_type ( data type )
	 * @returns
	*/
	
	function updating_new_fields_order( $field_key, $field_type  ){
		global $wpdb;
		$cnt = (int) 0;
		$field = new FIELDS_DATABASE();
		if( $field_type != 'option' ){
			$ura_fields = $field->get_all_fields();
			$cnt = count( $ura_fields );
			$cnt += 1;
			$column = 'field_order';
		}else{
			$nfield = $field->get_field_by_meta_key( $field_key );
			$id = $nfield->ID;
			$cnt = $field->options_count( $id );
			$cnt += 1;
			$column = 'option_order';
		}
		
		$field->update_fields( $field_key, $column, $cnt );		
		
	}
		
	/** 
	 * function delete_buddypress_field_option
	 * Deletes WordPress Profile Field when deleted in BuddyPress Profile Fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params BuddyPress Field Object $field
	 * @returns
	*/
	
	function delete_buddypress_field_option( $field ){
		
		$field_name = $field->name;
		$field_id = $field->id;
		
		if( empty( $field_id ) ){
			$field_id = xprofile_get_field_id_from_name( $field_name );	
		}
		
		$parent_id = $field->parent_id;
		$field_type = $field->type;
		$req_fields = get_option( 'csds_userRegAide_registrationFields' );
		$fields = new FIELDS_DATABASE();
		$ura_fields = $fields->get_all_fields();
		if( !empty( $ura_fields ) ){
			foreach( $ura_fields as $object ){
				if( $field_id == $object->bp_ID ){
					$d_field = new FIELDS_DATABASE();
					$d_field->meta_key = $object->meta_key;
					$d_field->ID = $field_id;
					$field_key = $object->meta_key; 
					if( array_key_exists( $field_key, $req_fields ) ){
						unset( $req_fields[$field_key] );
					}
					$fields->delete_fields( $d_field );
					
				}
			}
		}
		
		update_option( 'csds_userRegAide_registrationFields', $req_fields );
		
	}
		
	/** 
	 * function save_buddypress_field_option
	 * Updates WordPress profile fields when saved in BuddyPress Profile Fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params BuddyPress Field Object $field
	 * @returns
	*/
	
	function save_buddypress_field_option( $field ){
		//exit( print_r( $field ) );
		$length = (int) 0;
		$required = $field->is_required;
		$field_id = $field->id;
		$group_id = $field->group_id;
		$bp_parent_id = $field->parent_id;
		$field_type = $field->type;
		$field_name = $field->name;
		$description = $field->description;
		$required = $field->is_required;
		$field_order = $field->field_order;
		$option_order = $field->option_order;
		$default_option = $field->is_default_option;
		$length = strlen( $field_name );
		if( $field_type != 'option' ){
			$parent_id = '0';
		}
		$req_fields = get_option( 'csds_userRegAide_registrationFields' );
		
		$bp_types = $this->options_fields_array();
		$fields = new FIELDS_DATABASE();
		$ura_fields = $fields->get_all_fields(); 
		$bpid = $fields->get_bp_id( $field_id );
		
		$temp_field_key = explode( ' ', $field_name );
		$temp_field_key = implode( '_', $temp_field_key );
		$field_key = strtolower( $temp_field_key ); 
		$fields->field_name = $field_name;
		$fields->data_type = $field_type;
		$fields->meta_key = $field_key;
		$fields->parent_id = $parent_id;
		$fields->field_description = $description;
		$fields->field_required = $required;
		$fields->field_order = $field_order;
		$fields->option_order = $option_order;
		$fields->is_default_option = $default_option;
		
		if( empty( $field_id ) ){
			$field_id = xprofile_get_field_id_from_name( $field_name );	
		}
		
		$fields->bp_id = $field_id;
		$fields->bp_parent_id = $bp_parent_id;
		$fields->bp_group_id = $group_id;
		
		if( !empty( $bpid ) ){
			$field_key = $bpid->meta_key;
			
			if( $field_id == $bpid->bp_ID ){
				if( $bpid->field_name != $field_name ){
					$fields->update_fields( $field_key, 'field_name', $field_name );
				}
				if( $field_type != $bpid->data_type ){
					$fields->update_fields( $field_key, 'data_type', $field_type );
				}
				if( $required != $bpid->field_required ){
					$fields->update_fields( $field_key, 'field_required', $field_type );
				}
				if( $description != $bpid->field_description ){
					$fields->update_fields( $field_key, 'field_description', $description );
				}
				if( $group_id != $bpid->bp_group_ID ){
					$fields->update_fields( $field_key, 'bp_group_ID', $group_id );
				}
				
			}
			
			$options_type = $this->options_fields_array();
			if( array_key_exists( $field_type, $options_type ) ){
				$this->update_bp_options( $field_id, $field_key );
			}
			
		}else{
		
			$fields->insert_fields( $fields );
			
		}
		
		if( $required == '1' ){
			if( !array_key_exists( $field_key, $req_fields ) ){
				$req_fields[$field_key] = $field_name;
				update_option( 'csds_userRegAide_registrationFields', $req_fields );
			}
		}elseif( $required == '0' ){
			if( array_key_exists( $field_key, $req_fields ) ){
				unset( $req_fields[$field_key] );
				update_option( 'csds_userRegAide_registrationFields', $req_fields );
			}
		}
		
		$this->updating_new_fields_order( $field_key, $field_type );
		
	}
	
	/** 
	 * function update_bp_options
	 * Updates WordPress Profile Field Options when updated in BuddyPress Profile Fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $field_id Buddy Press Field ID
	 * @params string $field_key field meta key
	 * @returns OBJECT $field new BP_XProfile_Field
	*/
	
	function update_bp_options( $field_id, $field_key ){
		global $wpdb;
		
		$field = new BP_XProfile_Field( $field_id );
		//exit( print_r( $field ) );
		$field_id = $field->id;
		$parent_id = $field->parent_id;
		$new_options = array();
		$option = (string) 'option';
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$sql = "SELECT * FROM $table_name WHERE type = %s AND parent_id = %d";
		$query = $wpdb->prepare( $sql, $option, $field_id );
		$results = $wpdb->get_results( $query, OBJECT );
		
		$table_1 = $wpdb->prefix.'ura_fields';
		$sql_1 = "SELECT * FROM $table_1 WHERE data_type = %s AND bp_parent_id = %d";
		$query_1 = $wpdb->prepare( $sql_1, $option, $field_id );
		$results_1 = $wpdb->get_results( $query_1, OBJECT );
		$field = new FIELDS_DATABASE();
		$error = (int) 0;
		
		$sql_2 = "SELECT ID FROM $table_1 WHERE bp_ID = %d";
		$query_2 = $wpdb->prepare( $sql_2, $field_id );
		$parent_id = $wpdb->get_var( $query_2 );
	
		foreach( $results_1 as $object_1 ){
			$d_field = new FIELDS_DATABASE();
			$d_field->meta_key = $object_1->meta_key;
			$d_field->ID = $object_1->ID;
			$field->delete_fields( $d_field );	
		}
		
		foreach( $results as $object ){
			
			$field_id = $object->id;
			$field_name = $object->name;
			$bp_parent_id = $object->parent_id;
			$group_id = $object->group_id;
			$length = strlen( $field_name );
			$temp_field_key = explode( ' ', $object->name );
			$temp_field_key = implode( '_', $temp_field_key );
			$field_key = strtolower( $temp_field_key );
			if( $length > 30 ){
				$field_key = trimtext( $field_key, 0, 30 );
			}
			
			$key_exists = $field->meta_key_exists( $field_key );
							
			if( empty( $key_exists ) ){
				$field->meta_key = $field_key;
			}else{
				$msg .= $field_key.' Meta Key already exists! ';
				$error++;
			}
			
			$name_exists = $field->field_name_exists( $field_name );
			
			if( empty( $name_exists ) ){
				$field->field_name = $field_name;
			}else{
				$msg .= $field_name.' Field Title already exists! ';
				$error++;
			}
			
			if( $error == 0 ){
				$field->meta_key = $field_key;
				$field->parent_id = $parent_id;
				$field->field_name = $field_name;
				$field->data_type = $object->type;
				$field->field_description = $object->description;
				$field->field_required = $object->is_required;
				$field->is_default_option = $object->is_default_option;
				$field->approve_view = '0';
				$field->bp_id = $field_id;
				$field->bp_parent_id = $bp_parent_id;
				$field->bp_group_id = $group_id;
				$field->insert_fields( $field );
			}
		}
		
		$serialized = serialize( $new_options );
		$field_options[$field_key] = $serialized;
		update_option( 'csds_uraFieldOptions', $field_options );
		$serialized = '';
		$new_options = '';
		
		return $field;
	}
	
	/** 
	 * function edit_buddypress_option_name
	 * Updates BuddyPress XProfile Option Field Name when updated in URA Edit New Fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $old_name
	 * @params string $new_name
	 * @returns
	*/
	
	function edit_buddypress_option_name( $old_name, $new_name ){
		global $wpdb;
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$sql = "SELECT id FROM $table_name WHERE name = %s";
		$query = $wpdb->prepare( $sql, $old_name );
		$field_id = $wpdb->get_var( $query );
		
		$update_sql = "UPDATE $table_name SET name = %s WHERE id = %d";
		$update_query = $wpdb->prepare( $update_sql, $new_name, $field_id );
		$results = $wpdb->query( $update_query );
		return $results;
	}
		
	/** 
	 * function update_bp_field_type
	 * Updates BuddyPress XProfile Field Type when updated in URA Edit New Fields 
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $bpid bp field id
	 * @params string $field_type field data type
	 * @returns database results $results of update query
	*/
	
	function update_bp_field_type( $bpid, $field_type ){
		global $wpdb;
		
		if( !empty( $bpid ) ){
			$table_name = $wpdb->prefix.'bp_xprofile_fields';
			$update_sql = "UPDATE $table_name SET type = %s WHERE id = %d";
			$update_query = $wpdb->prepare( $update_sql, $field_type, $bpid );
			$results = $wpdb->query( $update_query );
			$singles =  $this->single_fields_array();
			$type = 'option';
			$count = $this->bp_field_options_count( $bpid );
			if( !empty( $count ) || $count == 0 ){
				if( array_key_exists( $field_type, $singles ) ){
					//$delete_sql = "DELETE FROM $table_name WHERE parent_id = '%d' AND type = '%s'";
					//$delete_results = $wpdb->prepare( $delete_sql, $field_id, $type );
					//$results2 = $wpdb->query( $delete_results );
					$delete_sql = array(
						'parent_id'	=>	$bpid,
						'type'		=>	'option'
					);
					$wpdb->delete( $table_name, $delete_sql );
				}
			}
			return $results;
		}
		
	}
	
	/** 
	 * function update_bp_fieldname_change
	 * Updates BuddyPress XProfile Field Name when updated in URA Edit New Fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $field_key
	 * @params string $field_name
	 * @returns database query results $results ( false if error or int number of fields updated )
	*/
	
	function update_bp_fieldname_change( $field_key, $field_name ){
		
		global $wpdb;
		$field_db = new FIELDS_DATABASE(); 
		$field = $field_db->get_field_by_meta_key( $field_key );
		$field_id = $field->bp_ID;
		
		if( !empty( $field_id ) ){
			$table_name = $wpdb->prefix.'bp_xprofile_fields';
			$update_sql = "UPDATE $table_name SET name = %s WHERE id = %d";
			$update_query = $wpdb->prepare( $update_sql, $field_name, $field_id );
			$results = $wpdb->query( $update_query );
			return $results;
		}
		
	}
	
	/** 
	 * function edit_options_order_for_deletion
	 * Alters BP Option order for field when deleting option if option order is 1 which prevents 
	 * BP from deleting the option
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $bpid
	 * @params int $bp_parent_id
	 * @returns int $final_results count of fields updated
	*/
	
	function edit_options_order_for_deletion( $bpid, $bp_parent_id ){
		global $wpdb;
		$option_order = (int) 0;
		$order = (int) 0;
		$new_order = (int) 0;
		$id = (int) 0;
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$sql = "SELECT option_order FROM $table_name WHERE id = %d";
		$query = $wpdb->prepare( $sql, $bpid );
		$option_order = $wpdb->get_var( $query );
		$final_results = (int) 0;
		if( $option_order == 1 ){
			$count = $this->bp_field_options_count( $bp_parent_id );
			$options = $this->select_bp_field_options( $bp_parent_id );
			foreach( $options as $option ){
				$order = $option->option_order;
				$id = $option->id;
				if( $id != $bpid ){
					if( $order != 1 ){
						$new_order = $order - 1;
						$results = $this->update_bp_field_options_order_deletion( $new_order, $id );
						if( $results == false ){
							$final_results = 0;
							break;
						}else{
							$final_results += $results;
						}
					}
				}else{
					$new_order = $count + 1;
					$results = $this->update_bp_field_options_order_deletion( $new_order, $id );
					if( $results == false ){
						$final_results = 0;
							break;
					}else{
						$final_results += $results;
					}
				}
			}
		}
		
		return $final_results;
	}
	
	/** 
	 * function bp_field_options_count
	 * Counts BP OPTION Fields in BP XPROFILE Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $parent_id
	 * @returns int $count count of field options for given parent field
	*/
	
	function bp_field_options_count( $parent_id ){
		global $wpdb;
		$option = (string) 'option';
		$table_name = $wpdb->prefix . 'bp_xprofile_fields';
		$sql = "SELECT COUNT(id) FROM $table_name WHERE parent_id = %d";
		$run_query = $wpdb->prepare( $sql, $parent_id );
		$count = $wpdb->get_var( $run_query );
		//$count += 1;
		return $count;
	}
	
	/** 
	 * function select_bp_field_options
	 * SELECTS ALL BP OPTION Fields for parent_id in BP XPROFILE Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $parent_id
	 * @returns OBJECT $options all options for that parent id
	*/
	
	function select_bp_field_options( $parent_id ){
		global $wpdb;
		$option = (string) 'option';
		$table_name = $wpdb->prefix . 'bp_xprofile_fields';
		$sql = "SELECT * FROM $table_name WHERE parent_id = %d";
		$run_query = $wpdb->prepare( $sql, $parent_id );
		$options = $wpdb->get_results( $run_query, OBJECT );
		return $options;
	}
	
	/** 
	 * function update_bp_field_options_order_deletion
	 * UPDATES BP OPTION Field in BP XPROFILE Fields database table so ura can
	 * delete option with option order #1
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $order
	 * @params int $id
	 * @returns database query results $results ( false if error or int number of fields updated )
	*/
	
	function update_bp_field_options_order_deletion( $order, $id ){
		global $wpdb;
		$option = (string) 'option';
		$table = $wpdb->prefix.'bp_xprofile_fields';
		$data = array(
			'option_order' => $order
		);
		$where = array(
			'id' => $id
		);
		$results = $wpdb->update( $table, $data, $where );
		return $results;
	}
	
	/** 
	 * function update_data_type_delete_bp_options
	 * Deletes ALL Options from BP XPROFILE Fields database table (for changing type to non-option field)
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $bp_parent_id
	 * @returns database query results $results ( false if error or int number of fields updated )
	*/
	
	function update_data_type_delete_bp_options( $bp_parent_id ){
		global $wpdb;
		$table = $wpdb->prefix.'bp_xprofile_fields';
		$where = array(
			'parent_id' => $bp_parent_id
		);
		$results = $wpdb->delete( $table, $where );
		return $results;
		
	}
	
	/** 
	 * function options_fields_array
	 * Creates and returns an array of input data types that can have additional options
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns array of datatypes that have options $bp_types
	*/
	
	function options_fields_array(){
		$bp_types = array(
			'checkbox'       => 'Checkboxes',
			'multiselectbox' => 'Multi Select Box',
			'radio'          => 'Radio Buttons',
			'selectbox'      => 'Drop Down Select Box'
		);
		return $bp_types;
	}
	
	/** 
	 * function single_fields_array
	 * Creates and returns an array of input data types that cannot have additional options
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns array of data types that have options $singles
	*/ 
	
	function single_fields_array(){
		$singles = array(
			'datebox'        => 'Datebox',
			'number'         => 'Number',
			'url'            => 'URL',
			'textarea'       => 'Textarea',
			'textbox'        => 'Textbox'
		);
		return $singles;
	}
	
	/** 
	 * function bp_account_activation_filter
	 * Bypasses BP email verification instant account activation if user has not been approved by admin
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $user_id
	 * @returns int $user_id
	*/
	
	function bp_account_activation_filter( $user_id ){
		global $wpdb;
		//$plaintext_pass = (string) '';
		if( is_wp_error( $user_id ) ){
			return $user_id;
		}else{
			update_user_meta( $user_id, 'email_verification', 'verified' );
			$status = get_user_meta( $user_id, 'approval_status', true );
			$options = get_option('csds_userRegAide_Options');
			$approve = $options['new_user_approve'];
			$bp_approve = $options['buddy_press_approval'];
			if( $approve == 1 && $bp_approve == 2 ){
				if( $status == 'pending' ){
					$table_name = $wpdb->prefix.'users';
					$update_sql = "UPDATE $table_name SET user_status = 2 WHERE ID = %d";
					$update = $wpdb->prepare( $update_sql, $user_id );
					$results = $wpdb->query( $update );
				}elseif( $status == 'approved' ){
					$table_name = $wpdb->prefix.'users';
					$update_sql = "UPDATE $table_name SET user_status = 0 WHERE ID = %d";
					$update = $wpdb->prepare( $update_sql, $user_id );
					$results = $wpdb->query( $update );
					$table = $wpdb->prefix.'signups';
					$user = get_user_by( 'id', $user_id );
					$login = $user->user_login;
					$where = array(
						'user_login'	=>	$login
					);
					$where_format = array(
						'%s'
					);
					$wpdb->delete( $table, $where, $where_format );
				}
			}
			do_action( 'new_user_email_verified', $user_id );
			
		}
		return $user_id;
	}
	
	/** 
	 * function bp_account_activation_filter
	 * Deletes user from signups table if BuddyPress is active - we eliminate that useless table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $user_email
	 * @returns
	*/
	
	function delete_bp_signups( $user_email ){
		global $wpdb;
		$options = get_option('csds_userRegAide_Options');
		$approve = $options['new_user_approve'];
		$bp_approve = $options['buddy_press_approval'];
		if( $approve == "2" && $bp_approve == "2" ){
			$table = $wpdb->prefix . "signups";
			$where = array( 
				'user_email'	=>	$user_email
			);
			$wpdb->delete( $table, $where );
		}
		
	}
	
	/** 
	 * function bp_activation_screen
	 * Testing for activation screen message filter
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg
	 * @returns
	*/
	
	function bp_activation_screen( $msg ){
		$key = isset( $_GET['key'] ) ? $_GET['key'] : '';

		// grab the key (the new way)
		if ( empty( $key ) ) {
			$key = bp_current_action();
		}
		//$request_uri = $_SERVER['REQUEST_URI'];
		//$key_url = explode( '/', $request_uri );
		//exit( 'REQUEST URI: '.$key[2] );
		//$key = $key_url[2];
		$user_id = $this->get_user_by_key( $key );
		$status = get_user_meta( $user_id, 'approval_status', true );
		$verified = get_user_meta( $user_id, 'email_verification', true );
		$email_status = get_user_meta( $user_id, 'email_status', true ); 
		// grab the key (the old way)
		
		
		//exit( print_r( $msg ) );
		//exit( 'KEY: '.$key.'<br/>EMAIL STATUS: '.$email_status.'<br/>'.'USER ID: '.$user_id );
		$options = get_option('csds_userRegAide_Options');
		$approve = $options['new_user_approve'];
		$bp_approve = $options['buddy_press_approval'];
		$user_status = $this->get_user_status( $user_id );
		//exit( print_r( $user ) );
		if( $approve == 1 && $bp_approve == 2 ){
			if( $status == 'pending' ){
				bp_core_add_message( __( 'Your Email was Verified! Your Account Needs an Administrator Approval Before you can Login!', 'buddypress' ) );
			}elseif( $status == 'approved' ){
				bp_core_add_message( __( 'Your Email has Been Verified! Your Account has Been Approved by an Administrator! Your Account is now Active!', 'buddypress' ) );
			}
		}elseif( $approve == 2 && $bp_approve == 2 ){
			if( $email_status == 1 ){
				bp_core_add_message( __( 'Your Email has Been Verified! Your Account is now Active!', 'buddypress' ) );
			}
			update_user_meta( $user_id, 'email_status', 0 );
		}
		//$msg['admin_activate'] = 'Your account need to be activated by an Administrator!';
		//return $msg;
	}
	
	/** 
	 * function get_user_status
	 * Testing for activation screen message filter
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $user_id
	 * @returns int $user_status ( current status of WP user ( $user_id ) )
	*/
	
	function get_user_status( $user_id ){
		global $wpdb;
		$table_name = $wpdb->prefix.'users';
		$status_sql = "SELECT user_status FROM $table_name WHERE ID = %d";
		$get_status = $wpdb->prepare( $status_sql, $user_id );
		$user_status = $wpdb->get_var( $get_status );
		return $user_status;
	}
	
	/** 
	 * function get_user_by_key
	 * Testing for activation screen message filter
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key ( user activation key )
	 * @returns int $user_id ( gets user by user activation key ( $key ) )
	*/
	
	function get_user_by_key( $key ){
		global $wpdb;
		$table_name = $wpdb->prefix.'users';
		$id_sql = "SELECT ID FROM $table_name WHERE user_activation_key = %s";
		$get_id = $wpdb->prepare( $id_sql, $key );
		$user_id = $wpdb->get_var( $get_id );
		return $user_id;
	}
	
	/**
	 * Filter the WP Users List Table views to include 'bp-signups'.
	 *
	 * @since BuddyPress (2.0.0)
	 *
	 * @params  array $views WP List Table views.
	 * @return array The views with the signup view added.
	 
	public function approval_filter_view( $views = array() ) {
		$signup_page = VIEWS_PATH."ura-new-user-approve-view.php";
		// Remove the 'current' class from All if we're on the signups view
		if ( $signup_page == get_current_screen()->id ) {
			$views['all'] = str_replace( 'class="current"', '', $views['all'] );
			$class        = 'current';
		} else {
			$class        = '';
		}

		$signups = 5;
		$url     = add_query_arg( 'page', 'new-user-approval',  VIEWS_PATH."ura-new-user-approve-view.php" );
		$text    = sprintf( _x( 'Approvals %s', 'approve users', 'user-registration-aide' ), '<span class="count">(' . number_format_i18n( $signups ) . ')</span>' );

		$views['registered'] = sprintf( '<a href="%1$s" class="%2$s">%3$s</a>', $url, $class, $text );

		return $views;
	}
	*/
}?>