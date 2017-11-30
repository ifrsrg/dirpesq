<?php

/**
 * Class FIELDS_DATABASE
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class FIELDS_DATABASE
{
	// Class Variables
	public $ID;
	public $meta_key;
	public $option_meta_key;
	public $parent_id;
	public $data_type;
	public $field_name;
	public $field_description;
	public $field_required;
	public $registration_field;
	public $wp_field;
	public $is_default_option;
	public $field_order;
	public $approve_view;
	public $option_order;
	public $min_number;
	public $max_number;
	public $number_step;
	public $bp_id;
	public $bp_parent_id;
	public $bp_group_id;
	
	public static $instance;
	
	/** 
	 * function __construct
	 * Contstructor
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	public function __construct() {
		$this->FIELDS_DATABASE();
	}
	
	/** 
	 * function FIELDS_DATABASE
	 * Creates new database if needed
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function FIELDS_DATABASE() { //constructor
		global $wpdb;
		self::$instance = $this;
		//$table_name = $wpdb->prefix . "ura_fields";
		//if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
		//	$this->create_fields_database();
		//}
	}
	
	/** 
	 * function create_fields_database
	 * Creates Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns  
	*/
	
	function create_fields_database(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		
		if ( class_exists( 'BuddyPress' ) ){
			$sql = "CREATE TABLE " . $table_name . " (
					ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					meta_key varchar(30) NOT NULL,
					option_meta_key varchar(40) DEFAULT '',
					parent_id bigint(20) NOT NULL,
					creation_date datetime NOT NULL default '0000-00-00 00:00:00',
					last_updated datetime NOT NULL default '0000-00-00 00:00:00',
					data_type varchar(15) NOT NULL,
					field_name varchar(150) NOT NULL,
					field_description longtext NULL DEFAULT '',
					field_required tinyint(1) NOT NULL DEFAULT '0',
					registration_field tinyint(1) NOT NULL DEFAULT '0',
					is_default_option tinyint(1) NULL DEFAULT '0',
					field_order bigint(20) NOT NULL DEFAULT '0',
					approve_view tinyint(1) NULL DEFAULT '0',
					option_order bigint(20) NULL DEFAULT '0',
					min_number bigint(20)	DEFAULT '0',
					max_number bigint(20) DEFAULT '0',
					number_step mediumint(20) DEFAULT '0',
					bp_ID bigint(20) NOT NULL,
					bp_parent_ID bigint(20) NOT NULL DEFAULT '0',
					bp_group_ID bigint(20) NOT NULL DEFAULT '0',
					PRIMARY KEY  ID ( ID ),
					UNIQUE KEY bp_ID ( bp_ID )
					);";
		}else{
			$sql = "CREATE TABLE " . $table_name . " (
					ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					meta_key varchar(30) NOT NULL,
					option_meta_key varchar(40) DEFAULT '',
					parent_id bigint(20) NOT NULL,
					creation_date datetime NOT NULL default '0000-00-00 00:00:00',
					last_updated datetime NOT NULL default '0000-00-00 00:00:00',
					data_type varchar(15) NOT NULL,
					field_name varchar(150) NOT NULL,
					field_description longtext DEFAULT '',
					field_required tinyint(1) NOT NULL DEFAULT '0',
					registration_field tinyint(1) NOT NULL DEFAULT '0',
					is_default_option tinyint(1) DEFAULT '0',
					field_order bigint(20) NOT NULL DEFAULT '0',
					approve_view tinyint(1) DEFAULT '0',
					option_order bigint(20) DEFAULT '0',
					min_number bigint(20)	DEFAULT '0',
					max_number bigint(20) DEFAULT '0',
					number_step mediumint(20) DEFAULT '0',
					bp_ID bigint(20) DEFAULT '0',
					bp_parent_ID bigint(20) DEFAULT '0',
					bp_group_ID bigint(20) DEFAULT '0',
					PRIMARY KEY  ID ( ID )
					);";
		}
		if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}
	
	/** 
	 * function min_number
	 * Returns Minimum Number for Number Input Field
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key field meta key
	 * @returns  int $min minimum number for number field type
	 */
	
	function get_next_field_id(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT MAX(ID) FROM $table_name";
		$max = $wpdb->get_var( $sql );
		//exit( $key );
		return $max;
	}
	
	/** 
	 * function min_number
	 * Returns Minimum Number for Number Input Field
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key field meta key
	 * @returns  int $min minimum number for number field type
	*/
	
	function min_number( $key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT min_number FROM $table_name WHERE meta_key = %s";
		$run_query = $wpdb->prepare( $sql, $key );
		$min = $wpdb->get_var( $run_query );
		//exit( $key );
		return $min;
	}
		
	/** 
	 * function max_number
	 * Returns Maximum Number for Number Input Field
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key field meta key
	 * @returns  int $max maximum number for number field type
	*/
	
	function max_number( $key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT max_number FROM $table_name WHERE meta_key = %s";
		$run_query = $wpdb->prepare( $sql, $key );
		$max = $wpdb->get_var( $run_query );
		return $max;
	}
		
	/** 
	 * function step_number
	 * Returns Step Number for Number Input Field
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key field meta key
	 * @returns  int $step step number for number field type
	*/
	
	function step_number( $key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT number_step FROM $table_name WHERE meta_key = %s";
		$run_query = $wpdb->prepare( $sql, $key );
		$step = $wpdb->get_var( $run_query );
		return $step;
	}
	
	/** 
	 * function select_none_reg_form_fields
	 * Select none for registration form, sets all registration form fields to false
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns 
	*/
	
	function select_none_reg_form_fields(){
		global $wpdb;
		$fields = $this->get_all_fields();
		foreach( $fields as $object ){
			$meta_key = $object->meta_key;
			$this->update_fields( $meta_key, 'field_required', false );
			$this->update_fields( $meta_key, 'approve_view', false );
			$this->update_fields( $meta_key, 'registration_field', false );
		}
		
	}
	
	/** 
	 * function select_none_req_fields
	 * Select none for registration form required fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns 
	*/
	
	function select_none_req_fields(){
		global $wpdb;
		$fields = $this->get_all_fields();
		foreach( $fields as $object ){
			$meta_key = $object->meta_key;
			$this->update_fields( $meta_key, 'field_required', true );
		}
		
	}
	
	/** 
	 * function field_type_finder
	 * Returns Field Type for Number Input Field
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key field meta_key
	 * @returns string $type field data type
	*/
	
	function field_type_finder( $key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT data_type FROM $table_name WHERE meta_key = %s";
		$run_query = $wpdb->prepare( $sql, $key );
		$type = $wpdb->get_var( $run_query );
		return $type;
	}
	
	/** 
	 * function fields_count
	 * Counts NON Option Fields in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key field meta_key
	 * @returns int $count count of non option fields in database
	*/
	
	function fields_count(){
		global $wpdb;
		$option = (string) 'option';
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT COUNT(ID) FROM $table_name WHERE data_type != %s";
		$run_query = $wpdb->prepare( $sql, $option );
		$count = $wpdb->get_var( $run_query );
		$count += 1;
		return $count;
	}
	
	/** 
	 * function options_count
	 * Counts Options for parent field in Fields database table 
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $parent_id options parent field id
	 * @returns int $count count of option fields in database for parent field
	*/
	
	function options_count( $parent_id ){
		global $wpdb;
		$option = ( string ) 'option';
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT COUNT(*) FROM $table_name WHERE data_type = %s AND parent_id = %d";
		$run_query = $wpdb->prepare( $sql, $option, $parent_id );
		$count = $wpdb->get_var( $run_query );
		$count+=1;
		return $count;
	}
	
	/** 
	 * function options_count_error
	 * Returns count of fields that have options
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $parent_id options parent field id
	 * @returns int $count count of option fields in database for parent field
	*/
	
	function options_count_error( $parent_id ){
		global $wpdb;
		$option = ( string ) 'option';
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT COUNT(*) FROM $table_name WHERE data_type = %s AND parent_id = %d";
		$run_query = $wpdb->prepare( $sql, $option, $parent_id );
		$count = $wpdb->get_var( $run_query );
		//$count+=1;
		return $count;
	}
	
	/** 
	 * function check_for_missing_options
	 * Returns int 1 or true if a field is supposed to have options but does not otherwise returns 0 or false
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $missing
	 * @returns int $missing Returns int 1 or true if a field is supposed to have options but does not otherwise returns 0 or false
	 * 
	*/
	
	function check_for_missing_options( $missing ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		if( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) == $table_name ) {
			$fields = $this->get_option_parent_fields();
			$cnt = ( int ) 0;
			$errs = ( int ) 0;
			
			foreach( $fields as $object ){
				$id = $object->ID;
				$cnt = $this->options_count_error( $id );
				if( empty( $cnt ) || $cnt == 0 ){
					$errs++;
					$missing++;
					return $missing;
				}
			}
			if( $errs == 0 ){
				return $missing;
			}else{
				return $missing;
			}
		}else{
			return $missing;
		}
	}	
	
	/** 
	 * function bp_signup_fields
	 * Gets BP Signup Fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $missing
	 * @returns ARRAY_A $field
	 * 
	*/
	
	function bp_signup_fields(){
		global $wpdb;
		$option = (string) 'option';
		$table_name = $wpdb->prefix . 'ura_fields';
		$sql = "SELECT meta_key, bp_ID FROM $table_name WHERE data_type != %s";
		$run_query = $wpdb->prepare( $sql, $option );
		$fields = $wpdb->get_results( $run_query, ARRAY_A );
		return $fields;
	}
	
	/** 
	 * function insert_fields
	 * Inserts New Fields into Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params OBJECT FIELD DATABASE FIELD
	 * @returns int|boolean count of rows affected or 0/false for error 
	*/
	
	function insert_fields( $field ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
			$this->create_fields_database();
		}
		/*
		$sql = "INSERT INTO " . $table_name . " ( meta_key, parent_id, creation_date, last_updated, data_type, field_name, field_description, field_required, is_default_option, field_order, approve_view, option_order, bp_ID, bp_parent_ID, bp_group_ID  ) " .
				"VALUES (%s, %d, now(), now(), %s, %s, %s, %d, %d, %d, %d, '%d', '%d', '%d' )";
		$insert = $wpdb->prepare( $sql, $field->meta_key, $field->parent_id, $field->data_type, $field->field_name, $field->field_description, $field->field_required, $field->is_default_option, $field->field_order, $field->option_order, $field->bp_id, $field->bp_parent_id, $field->bp_group_id );
		$results = $wpdb->query( $insert );
		return $results;*/
		$data = array(
			'meta_key'				=>	$field->meta_key,
			'option_meta_key'		=>	$field->option_meta_key,
			'parent_id'				=>	$field->parent_id,
			'creation_date'			=>	current_time('mysql', 1),
			'last_updated'			=>	current_time('mysql', 1),
			'data_type'				=>	$field->data_type,
			'field_name'			=>	$field->field_name,
			'field_description'		=>	$field->field_description,
			'field_required'		=>	$field->field_required,
			'registration_field'	=>	$field->registration_field,
			'is_default_option'		=>	$field->is_default_option,
			'field_order'			=>	$field->field_order,
			'approve_view'			=>	$field->approve_view,
			'option_order'			=>	$field->option_order,
			'min_number'			=>	$field->min_number,
			'max_number'			=>	$field->max_number,
			'number_step'			=>	$field->number_step,
			'bp_ID'					=>	$field->bp_id,
			'bp_parent_ID'			=>	$field->bp_parent_id,
			'bp_group_ID'			=>	$field->bp_group_id
		);
		$result = $wpdb->insert( $table_name, $data );
		return $result;
	}
	
	/** 
	 * function get_dash_widget_fields
	 * Returns dash widget fields
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns OBJECT FIELD DATABASE FIELD 
	*/
	
	function get_dash_widget_fields(){
		global $wpdb;
		$option = (string) 'option';
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT meta_key, field_name FROM $table_name WHERE data_type != %s";
		$run_query = $wpdb->prepare( $sql, $option );
		$field = $wpdb->get_results( $run_query, OBJECT );
		return $field;	
	}
	
	/** 
	 * function get_field_by_meta_key
	 * Returns Object Field from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $meta_key field meta key
	 * @returns OBJECT FIELD DATABASE FIELD 
	*/
	
	function get_field_by_meta_key( $meta_key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT * FROM $table_name WHERE meta_key = %s";
		$run_query = $wpdb->prepare( $sql, $meta_key );
		$field = $wpdb->get_row( $run_query, OBJECT );
		return $field;	
	}
	
	/** 
	 * function get_field_by_id
	 * Returns Object Field from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $id 
	 * @returns OBJECT FIELD DATABASE FIELD 
	*/
	
	function get_field_by_id( $id ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT * FROM $table_name WHERE ID = %d";
		$run_query = $wpdb->prepare( $sql, $id );
		$field = $wpdb->get_row( $run_query, OBJECT );
		return $field;	
	}
	
	/** 
	 * function get_field_by_id
	 * Gets and Returns Field ID from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $meta_key field meta key
	 * @returns int field id $id
	*/
	
	function get_field_id_by_meta_key( $meta_key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT ID FROM $table_name WHERE meta_key = %s";
		$run_query = $wpdb->prepare( $sql, $meta_key );
		$id = $wpdb->get_var( $run_query );
		return $id;	
	}
	
	/** 
	 * function update_field_order
	 * Updates Field Order for field in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key,, string $value ( field value )
	 * @returns int|boolean count of rows affected or 0/false for error 
	*/
	
	function update_field_order( $key, $value ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$update_sql = "UPDATE $table_name SET field_order = %d WHERE meta_key = %s";
		$update = $wpdb->prepare( $update_sql, $value, $key );
		$results = $wpdb->query( $update );
		return $results;
	}
	
	/** 
	 * function update_option_order
	 * Updates Field Options Order for field in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key,, string $value ( field value )
	 * @returns int|boolean count of rows affected or 0/false for error 
	*/	
	
	function update_option_order( $key, $value ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$update_sql = "UPDATE $table_name SET option_order = %d WHERE meta_key = %s";
		$update = $wpdb->prepare( $update_sql, $value, $key );
		$results = $wpdb->query( $update );
		return $results;
	}
	
	/** 
	 * function update_option_order
	 * Resets Options Order for option fields after one of the options from same parent has been deleted in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $parent_id parent ID of options to be updated, int $order current order of option deleted
	 * @returns int|boolean count of rows affected or 0/false for error 
	 */	
	
	function reset_option_order( $parent_id, $order ){
		global $wpdb;
		$table = $wpdb->prefix . "ura_fields";
		$current_sql = "SELECT * FROM $table WHERE parent_id = %d";
		$current = $wpdb->prepare( $current_sql, $parent_id );
		$options = $wpdb->get_results( $current, OBJECT );
		if( !empty( $options ) ){
			if( is_array( $options ) ){
				foreach( $options as $object ){
					$option_order = $object->option_order;
					$meta_key = $object->meta_key;
					if( $option_order >= $order ){
						$option_order -= 1 ;
						$data = array(
							'option_order'	=>	$option_order
						);
						$where = array(
							'meta_key'	=>	$meta_key
						);
						$format = array( 
							'%d'
						);
						$where_format = array(
							'%s'
						);
						$update = $wpdb->update( $table, $data, $where, $format, $where_format );
						if( $update === false ){
							return $update;
						}
					}
				}
			}
			
		}
		
		return true;
	}
	
	/** 
	 * function meta_key_exists
	 * Checks for existence of meta key to prevent database error on duplication in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key
	 * @returns int $id field id if meta key already exists
	*/	
	
	function meta_key_exists( $key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT ID FROM $table_name WHERE meta_key = '%s'";
		$run_query = $wpdb->prepare( $sql, $key );
		$id = $wpdb->get_var( $run_query );
		return $id;	
	}
	
	/** 
	 * function meta_key_change
	 * Checks and changes meta key to prevent database error on duplication in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key ( meta_key )
	 * @returns string $new_key or false on error for too long
	*/
	
	function meta_key_change( $key ){
		
		$length = strlen( $key );
		$new_key = (string) '';
		$new_key_1 = (string) '';
		
		if( $length <= 27 ){
			for( $i = 1; $i <= 20; $i++ ){
				$new_key = $key.'_'.$i;
				$key_exists = $this->meta_key_exists( $new_key );
				if( empty( $key_exists ) ){
					return $new_key;
					//break;
				}
			}
		}elseif( $length == 28 ){
			for( $i = 1; $i <= 20; $i++ ){
				$new_key = $key.$i;
				$key_exists = $this->meta_key_exists( $new_key );
				if( empty( $key_exists ) ){
					return $new_key;
					//break;
				}
			}
		}elseif( $length == 29 ){
			for( $i = 1; $i <= 20; $i++ ){
				$new_key = substr_replace( $key, "", -1 );
				$new_key_1 = $new_key.$i;
				$key_exists = $this->meta_key_exists( $new_key_1 );
				if( empty( $key_exists ) ){
					return $new_key_1;
					//break;
				}
			}
		}else{
			return;
		}
	}
	
	/** 
	 * function field_name_exists
	 * Checks and changes meta key to prevent database error on duplication in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $name ( field_name )
	 * @returns string $name ( field name )
	 */
	function dup_field_names( $name ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT COUNT(ID) FROM $table_name WHERE field_name = %s";
		$run_query = $wpdb->prepare( $sql, $name );
		$cnt = $wpdb->get_var( $run_query );
		//wp_die( 'COUNT----------'.$cnt );
		return $cnt;	
	}
	
	/** 
	 * function field_name_exists
	 * Checks and changes meta key to prevent database error on duplication in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $name ( field_name )
	 * @returns string $name ( field name )
	*/
	function field_name_exists( $name ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT field_name FROM $table_name WHERE field_name = %s";
		$run_query = $wpdb->prepare( $sql, $name );
		$field = $wpdb->get_var( $run_query );
		//wp_die( 'FIELD ___'.$field );
		return $field;	
	}
	
	/** 
	 * function field_name_change
	 * Checks and changes meta key to prevent database error on duplication in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $name ( meta_key )
	 * @returns string $new_name or false on error for too long
	*/
	
	function field_name_change( $name ){
		global $wpdb;
		$new_name = (string) '';
		for( $i = 1; $i <= 30; $i++ ){
			$new_name = $name.' '.$i;
			$name_exists = $this->field_name_exists( $new_name );
			if( empty( $name_exists ) ){
				return $new_name;
				//break;
			}else{
				$new_name = $name.'-'.$i;
				$name_exists = $this->field_name_exists( $new_name );
				if( empty( $name_exists ) ){
					return $new_name;
				}
			}
		}
		
	}
	
	/** 
	 * function get_field_options
	 * Gets specific Field Options from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params Fields Database Object $field
	 * @returns OBJECT $options
	*/	
	
	function get_field_options( $field ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$sql = "SELECT * FROM $table_name WHERE data_type = %s AND parent_id = %d";
		$run_query = $wpdb->prepare( $sql, $option, $field->parent_id );
		$options = $wpdb->get_results( $run_query, OBJECT );
		usort( $options, array( &$this, 'ura_list_options_sort' ) );
		return $options;	
	}
	
	/** 
	 * function get_total_field_options
	 * Gets specific Field Options from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key ( meta_key )
	 * @returns OBJECTS $options
	*/	
	
	function get_total_field_options( $key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$sql = "SELECT ID FROM $table_name WHERE meta_key = %s";
		$run_query = $wpdb->prepare( $sql, $key );
		$id = $wpdb->get_var( $run_query );
		$sql = "SELECT * FROM $table_name WHERE data_type = %s AND parent_id = %d";
		$run_query = $wpdb->prepare( $sql, $option, $id );
		$options = $wpdb->get_results( $run_query, OBJECT );
		usort( $options, array( &$this, 'ura_list_options_sort' ) );
		//exit( print_r( $options ) );
		return $options;	
	}
	
	/** 
	 * function get_field_options_edit
	 * Gets specific Field Options from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $id ( parent_id )
	 * @returns OBJECT $options
	*/
	
	function get_field_options_edit( $id ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$sql = "SELECT * FROM $table_name WHERE data_type = %s AND parent_id = %d";
		$run_query = $wpdb->prepare( $sql, $option, $id );
		$options = $wpdb->get_results( $run_query, OBJECT );
		usort( $options, array( &$this, 'ura_list_options_sort' ) );
		return $options;	
	}
	
	/** 
	 * function ura_list_options_sort
	 * Sorts all Field Options by option order
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params options array $a, options array $b $id ( parent_id )
	*  @returns sorted OBJECT $fields
	*/
	
	function ura_list_options_sort( $a, $b ){
		if( $a->option_order == $b->option_order){
			return 0;
		}
		return ( $a->option_order < $b->option_order ) ? -1 : 1;
	}
		
	/** 
	 * function options_order_update
	 * Updates Options Order after option deleted
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int ( parent_id ), int option_order
	 * @returns int|boolean count of rows affected or 0/false for error 
	*/
	
	function options_order_update( $parent_id, $order ){
		global $wpdb;
		$old_order = (int) 0;
		$new_order = (int) 0;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$sql = "SELECT * FROM $table_name WHERE parent_id = %s AND option_order > %d";
		$run_query = $wpdb->prepare( $sql, $parent_id, $order );
		$options = $wpdb->get_results( $run_query, OBJECT );
		foreach( $options as $option ){
			$old_order = $option->option_order;
			$new_order = $old_order - 1;
			$meta_key = $option->meta_key;
			$update_sql = "UPDATE $table_name SET option_order = %d WHERE meta_key = %s";
			$update = $wpdb->prepare( $update_sql, $new_order, $meta_key );
			$results = $wpdb->query( $update );
		}
		return $results;	
	}
	
	/** 
	 * function get_field_type
	 * Gets specific Field Type from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $meta_key ( meta_key )
	 * @returns string $type ( data_type )
	*/
	
	function get_field_type( $meta_key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$sql = "SELECT data_type FROM $table_name WHERE meta_key = %s";
		$run_query = $wpdb->prepare( $sql, $meta_key );
		$type = $wpdb->get_var( $run_query );
		return $type;	
	}
	
	/** 
	 * function get_field_type
	 * Gets array of fields OBJECTS that will appear on the registration form from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns array of OBJECT $reg_fields required on registration form
	*/
	
	function get_registration_fields(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$registration = true;
		$option = 'option';
		$sql = "SELECT * FROM $table_name WHERE registration_field = %d AND data_type != %s";
		$run_query = $wpdb->prepare( $sql, $registration, $option );
		$reg_fields = $wpdb->get_results( $run_query, OBJECT );
		if( !empty( $reg_fields ) ){
			usort( $reg_fields, array( &$this, 'ura_list_fields_sort' ) );
		}
		return $reg_fields;	
	}
	
	/** 
	 * function get_required_fields
	 * Gets array of OJECTS for required fields on registration form from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns array of OBJECT $reg_fields required fields on registration form
	*/
	
	function get_required_fields(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$registration = true;
		$required = true;
		$option = 'option';
		$sql = "SELECT * FROM $table_name WHERE registration_field = %d AND data_type != %s AND field_required = %s";
		$run_query = $wpdb->prepare( $sql, $registration, $option, $required );
		$reg_fields = $wpdb->get_results( $run_query, OBJECT );
		if( !empty( $reg_fields ) ){
			usort( $reg_fields, array( &$this, 'ura_list_fields_sort' ) );
		}
		return $reg_fields;	
	}
	
	/** 
	 * function get_optional_fields
	 * Gets array of OJECTS for optional fields on registration form from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns array of OBJECT $reg_fields optional fields on registration form
	*/
	
	function get_optional_fields(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$registration = true;
		$required = false;
		$option = 'option';
		$sql = "SELECT * FROM $table_name WHERE registration_field = %d AND data_type != %s AND field_required = %s";
		$run_query = $wpdb->prepare( $sql, $registration, $option, $required );
		$opt_fields = $wpdb->get_results( $run_query, OBJECT );
		if( !empty( $opt_fields ) ){
			usort( $opt_fields, array( &$this, 'ura_list_fields_sort' ) );
		}
		return $opt_fields;	
	}
	
	/** 
	 * function get_bp_registration_fields
	 * Gets array of BP required fields OBJECTS on registration form from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns array of OBJECTS $reg_fields optional fields on registration form
	*/
	
	function get_bp_registration_fields(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$registration = true;
		$sql = "SELECT * FROM $table_name WHERE ( registration_field = %d OR bp_group_ID = 1 )";
		$run_query = $wpdb->prepare( $sql, $registration );
		$reg_fields = $wpdb->get_results( $run_query, OBJECT );
		if( !empty( $reg_fields ) ){
			usort( $reg_fields, array( &$this, 'ura_list_fields_sort' ) );
		}
		return $reg_fields;	
	}
	
	/** 
	 * function get_field_id
	 * Returns Field ID for Field meta_key
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $meta_key
	 * @returns int $id
	*/
	
	function get_field_id( $bpid ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT ID FROM $table_name WHERE bp_ID = %d";
		$run_query = $wpdb->prepare( $sql, $bpid );
		$id = $wpdb->get_var( $run_query );
		return $id;	
	}
	
	/** 
	 * function get_all_fields
	 * Returns all non 'option' Fields in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns array of OBJECTS $fields non option fields on registration form
	*/
	
	function get_all_fields(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$sql = "SELECT * FROM $table_name WHERE data_type != %s";
		$run_query = $wpdb->prepare( $sql, $option );
		$fields = $wpdb->get_results( $run_query, OBJECT );
		usort( $fields, array( &$this, 'ura_list_fields_sort' ) );
		//exit( print_r( $fields ) );
		return $fields;	
	}
	
	/** 
	 * function get_option_parent_fields
	 * Returns all non 'option' Fields in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns array of OBJECTS $fields option fields parents on registration form
	*/
	
	function get_option_parent_fields(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$select = 'selectbox';
		$mselect = 'multiselectbox';
		$chbox = 'checkbox';
		$radio = 'radio';
		$sql = "SELECT * FROM $table_name WHERE data_type = %s OR data_type = %s OR data_type = %s OR data_type = %s ";
		$run_query = $wpdb->prepare( $sql, $select, $mselect, $chbox, $radio );
		$fields = $wpdb->get_results( $run_query, OBJECT );
		usort( $fields, array( &$this, 'ura_list_fields_sort' ) );
		//exit( print_r( $fields ) );
		return $fields;	
	}
	
	/** 
	 * function ura_list_fields_sort
	 * Sorts all Fields for forms by field order
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params object $a, object $b
     * @returns $a, $b sorted by field order OBJECT 
	*/
	
	function ura_list_fields_sort( $a, $b ){
		if( $a->field_order == $b->field_order){
			return 0;
		}
		return ( $a->field_order < $b->field_order ) ? -1 : 1;
	}
	
	/** 
	 * function get_nua_fields
	 * Returns all Fields selected in New User Approve Fields View from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns OBJECT $fields for approve view
	*/
	
	function get_nua_fields(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$approve = true;
		$sql = "SELECT * FROM $table_name WHERE approve_view = %d";
		$run_query = $wpdb->prepare( $sql, $approve );
		$fields = $wpdb->get_results( $run_query, OBJECT );
		return $fields;	
	}
	
	/** 
	 * function get_number_fields
	 * Returns all Fields selected in New User Approve Fields View from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns OBJECT $fields that have number data type
	*/
	
	function get_number_fields(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$number= 'number';
		$sql = "SELECT * FROM $table_name WHERE data_type = %s";
		$run_query = $wpdb->prepare( $sql, $number );
		$fields = $wpdb->get_results( $run_query, OBJECT );
		return $fields;	
	}
	
	/** 
	 * function check_nua_fields
	 * Checks all Fields for New User Approve Fields View from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns 
	*/
	
	function check_nua_fields(){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$approve = true;
		$unapprove = false;
		$fields = $this->get_all_fields();
		foreach( $fields as $object ){
			$meta_key = $object->meta_key;
			$view = $object->approve_view;
			$required = $object->field_required;
			if( $required == false ){
				$this->update_fields( $meta_key, 'approve_view', false );
			}
		}
	}
	
	/** 
	 * function update_fields
	 * Updates non option field in Database with Meta Key as field identifier
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $meta_key ( meta_key ), string $field_key ( database field name ), string $field_value ( value of field ) 
	 * @returns database results $results ( number of rows updated or 0|false if error )
	*/
	
	function update_fields( $meta_key, $field_key, $field_value ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$update_sql = "UPDATE $table_name SET $field_key = %s WHERE meta_key = %s";
		$update = $wpdb->prepare( $update_sql, $field_value, $meta_key );
		$results = $wpdb->query( $update );
		return $results;
	}
	
	/** 
	 * function update_options_fields
	 * Updates Option Type Field in Database
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $meta_key ( option_meta_key ), string $field_key ( database field name ), string $field_value ( value of field ) 
	 * @returns database results $results ( number of rows updated or 0|false if error )
	*/
	
	function update_options_fields( $meta_key, $field_key, $field_value ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$update_sql = "UPDATE $table_name SET $field_key = %s WHERE option_meta_key = %s";
		$update = $wpdb->prepare( $update_sql, $field_value, $meta_key );
		$results = $wpdb->query( $update );
		return $results;
	}
		
	/** 
	 * function get_bp_id
	 * Gets row with BP Id in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $id ( bp_ID )
	 * @returns OBJECT field row $row
	*/
	
	function get_bp_id( $id ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT * FROM $table_name WHERE bp_ID = %d";
		$select = $wpdb->prepare( $sql, $id );
		$row = $wpdb->get_row( $select, OBJECT );
		return $row;
	}
	
	/** 
	 * function get_bp_id_key
	 * Gets ID from Fields with Meta Key
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $meta_key ( meta_key )
	 * @returns int $id ( ID )
	*/
	
	function get_bp_id_key( $meta_key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT ID FROM $table_name WHERE meta_key = %s";
		$select = $wpdb->prepare( $sql, $meta_key );
		$id = $wpdb->get_var( $select );
		return $id;
	}
	
	/** 
	 * function get_bp_id_by_meta_key
	 * Gets BP Id from Fields with Meta Key
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $meta_key ( meta_key )
	 * @returns int $id ( ID )
	*/
	
	function get_bp_id_by_meta_key( $meta_key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "SELECT bp_ID FROM $table_name WHERE meta_key = %s";
		$select = $wpdb->prepare( $sql, $meta_key );
		$id = $wpdb->get_var( $select );
		return $id;
	}
	
	/** 
	 * function update_bp_ids
	 * Updates BP Id in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params object $field
	 * @returns database results $results ( rows affected or 0|false for error )
	*/
	
	function update_bp_ids( $field ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$update_sql = "UPDATE $table_name SET bp_ID = %s WHERE meta_key = %s";
		$update = $wpdb->prepare( $update_sql, $field->bp_id, $field->meta_key );
		$results = $wpdb->query( $update );
		return $results;
	}
	
	/** 
	 * function update_bp_parent_ids
	 * Updates BP Id in Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params object $field
	 * @returns database results $results ( rows affected or 0|false for error )
	*/
	
	function update_bp_parent_ids( $field ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$update_sql = "UPDATE $table_name SET bp_parent_ID = %s WHERE meta_key = %s";
		$update = $wpdb->prepare( $update_sql, $field->parent_id, $field->meta_key );
		$results = $wpdb->query( $update );
		return $results;
	}
		
	/** 
	 * function delete_options
	 * Deletes Option from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $meta_key ( meta_key )
	 * @returns database results $results ( rows affected or 0|false for error )
	*/
	
	function delete_options( $parent_id, $meta_key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$results = $wpdb->delete( $table_name, array( 'parent_id'	=>	$parent_id, 'option_meta_key' => $meta_key ) );
		return $results;
		
	}
	
	/** 
	 * function update_data_type_delete_ura_options
	 * Deletes ALL Options from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $parent_id
	 * @returns database results $results ( rows affected or 0|false for error )
	*/
	
	function update_data_type_delete_ura_options( $parent_id ){
		global $wpdb;
		$options = $this->get_all_field_options( $parent_id );
		if( $options ){
			$table = $wpdb->prefix . "ura_fields";
			$where = array(
				'parent_id' => $parent_id,
				'data_type' => 'option'
			);
			$results = $wpdb->delete( $table, $where );
		}else{
			$results = 1;
		}
		return $results;
		
	}
	
	/** 
	 * function get_all_field_options
	 * Gets all Field Options from Fields for specific field used for changing data type
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $parent_id
	 * @returns sorted array of OBJECTS $options
	*/
		
	function get_all_field_options( $parent_id ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$sql = "SELECT * FROM $table_name WHERE data_type = %s AND parent_id = %d";
		$run_query = $wpdb->prepare( $sql, $option, $parent_id );
		$options = $wpdb->get_results( $run_query, OBJECT );
		usort( $options, array( &$this, 'ura_list_options_sort' ) );
		return $options;	
	}
	
	/** 
	 * function get_field_options_count
	 * Gets all Field Options from Fields for specific field used for changing data type
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $parent_id
	 * @returns int $cnt count of options for parent field
	 */
	
	function get_field_options_count( $parent_id ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$cnt = ( int ) 0;
		$sql = "SELECT COUNT(ID) FROM $table_name WHERE data_type = %s AND parent_id = %d";
		//$sql = "SELECT COUNT(ID) FROM $table_name WHERE data_type = %s AND parent_id = 37";
		$run_query = $wpdb->prepare( $sql, $option, $parent_id );
		//$run_query = $wpdb->prepare( $sql, $option );
		$cnt = $wpdb->get_var( $run_query );
		//wp_die( 'COUNT DB -- '.$cnt );
		//exit( $cnt );
		return $cnt;	
	}
	
	/** 
	 * function options_duplicate_keys
	 * Checks Field Options from database for duplicate field keys
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $parent_id, string $meta_key
	 * @returns int ID if exists or NULL for no duplicates
	 */
	
	function options_duplicate_keys( $parent_id, $meta_key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$sql = "SELECT ID FROM $table_name WHERE data_type = %s AND parent_id = %d AND meta_key = %s";
		$run_query = $wpdb->prepare( $sql, $option, $parent_id, $meta_key );
		$id = $wpdb->get_var( $run_query );
		return $id;	
	}
	
	/** 
	 * function options_duplicate_titles
	 * Checks Field Options from database for duplicate field keys
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $parent_id, string $title
	 * @returns int ID if exists or NULL for no duplicates
	 */
	
	function options_duplicate_titles( $parent_id, $title ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$sql = "SELECT ID FROM $table_name WHERE data_type = %s AND parent_id = %d AND field_name = %s";
		$run_query = $wpdb->prepare( $sql, $option, $parent_id, $title );
		$id = $wpdb->get_var( $run_query );
		return $id;	
	}
	
	/** 
	 * function delete_fields
	 * Deletes Field from Fields database table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params object $field
	 * @returns
	*/
		
	function delete_fields( $field ){
		global $wpdb;
		$reg_fields = get_option( 'csds_userRegAide_registrationFields' );
		$table_name = $wpdb->prefix . "ura_fields";
		$id = $field->ID;
		$key = $field->meta_key;
		$options = $this->get_field_options_edit( $id );
		if( !empty( $options ) ){
			foreach( $options as $object ){
				$meta_key = $object->option_meta_key;
				$this->delete_options( $id, $meta_key );
			}
		}
		if( !empty( $reg_fields ) && is_array( $reg_fields ) ){
			if( array_key_exists( $key, $reg_fields ) ){
				unset( $reg_fields[$key] );
				update_option( 'csds_userRegAide_registrationFields', $reg_fields );
			}
			$this->update_order( $id );
		}
		$wpdb->delete( $table_name, array( 'meta_key' => $field->meta_key ) );
		
		//exit( print_r( $field ) );
		
	}
	
	/** 
	 * function update_order
	 * Updates URA Field Order When Field Deleted
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $id ( Field ID )
	 * @returns 
	*/
	
	function update_order( $id ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$field = $this->get_field_by_id( $id );
		$field_order = $field->field_order;
		$all_fields = $this->get_all_fields();
		$plugin = 'buddypress/bp-loader.php';
		foreach( $all_fields as $object ){
			$old_order = $object->field_order;
			if( $old_order > $field_order ){
				
				$new_order = $old_order - 1;
				$data = array(
					'field_order'	=>	$new_order
				);
				$where = array(
					'ID'			=>	$object->ID
				);
				$wpdb->update( $table_name, $data, $where );
			}
		}
		if( is_plugin_active( $plugin ) ){
			$bp = new URA_BP_FUNCTIONS();
			$bp->update_bp_fields_orders(); // updates BP field order
		}
	}
	
	/** 
	 * function data_type_change_number
	 * Updates numbers data type specific fields if user changes data type to something other than number
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $meta_key
	 * @returns int $results ( number of rows updated ) or false if there is an error
	 */
	
	function data_type_change_number( $meta_key ){
		global $wpdb;
		$table_name = $wpdb->prefix . "ura_fields";
		$option = 'option';
		$data = array(
			'min_number'	=>	0,
			'max_number'	=>	0,
			'number_step'	=>	0
		);
		$where = array(
			'meta_key'	=>	$meta_key
		);
		//$sql = "SELECT data_type FROM $table_name WHERE meta_key = %s";
		//$run_query = $wpdb->prepare( $sql, $meta_key );
		//$type = $wpdb->get_var( $run_query );
		//return $type;
		$results = $wpdb->update( $table_name, $data, $where );
		return $results;
			
	}
	
	/** 
	 * function trim_key_length
	 * Select statement to get bp_profile_fields option field parent id
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key meta key to be trimmed so it won't throw a database error
	 * @returns string $short_key the shortened string 30 characters or less
	 */
	
	function trim_key_length( $key ){
		$max = ( int ) 29;
		$short_key = substr( $key, 0, $max );
		return $short_key;
	}
	
	/** 
	 * function update_ura_fields_database
	 * Merges Fields from Fields Options in WP Options DB Table to ura_fields table
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function update_ura_fields_database(){
		global $wpdb;
		$options = get_option( 'csds_userRegAide_Options' );
		if( $options['database_updated'] == '1' ){
			//return;
		}
		$strlngth = ( int ) 0;
		$cnt = (int) 1;
		$ura_field_cnt = ( int ) 0;
		$fcnt = ( int ) 0;
		$focnt = ( int ) 0;
		$index = ( int ) 1;
		$bpid = (int) 0;
		$table_name = $wpdb->prefix . "ura_fields";
		if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name ) {
			$this->create_fields_database();
		}
		$new_fields = get_option( 'csds_userRegAide_NewFields' );
		$all_fields = get_option( 'csds_userRegAideFields' );
		$field_order = get_option( 'csds_userRegAide_fieldOrder' );
		$reg_fields = get_option( 'csds_userRegAide_registrationFields' );
		$optional_fields = get_option( 'csds_ura_optionalFields' );
		if( !empty( $new_fields ) ){
			foreach( $new_fields as $key => $title ){
				$actions = new CSDS_URA_ACTIONS();
				$new_key = $actions->replace_spaces( $key );
				$new_key = sanitize_key( $new_key );
				$strlngth = strlen( $new_key );
				if( $strlngth >= 30 ){
					$new_key = $this->trim_key_length( $new_key );
				}
				$new_title = sanitize_text_field( $title );
				$this->meta_key = $new_key;
				$this->option_meta_key = '';
				$this->parent_id = '0';
				$this->field_name = $new_title;
				$this->data_type = 'textbox';
				$this->min_number = 0;
				$this->max_number = 0;
				$this->step_number = 0;
				$this->approve_view = 0;
				
				$fcnt = count( $new_fields  );
				$focnt = count( $field_order );
				if( $fcnt == $focnt && !empty( $field_order ) ){
					if( array_key_exists( $key, $field_order ) ){
						$this->field_order = $field_order[$key];
					}else{
						$this->field_order = $index;
					}
				}else{
					$this->field_order = $index;
				}
				
				if( !empty( $reg_fields ) ){
					if( array_key_exists( $key, $reg_fields ) ){
						$this->registration_field = true;
					}else{
						$this->registration_field = false;
					}
				}
				if( !empty( $optional_fields ) ){
					if( array_key_exists( $key, $optional_fields ) ){
						$this->field_required = false;
					}else{
						$this->field_required = true;
					}
				}
				$key_exists = $this->meta_key_exists( $new_key );
				$name_exists = $this->field_name_exists( $new_title );
				// getting BP field id
				$plugin = 'buddypress/bp-loader.php';
				if( is_plugin_active( $plugin ) ){
					$bp_name_exists = $this->bp_field_name_exists( $title );
					$has_rows = $this->bp_has_rows();
					
					if( !empty( $has_rows ) ){
						$cnt = $this->bp_last_id();
						$cnt++;
						if( $bpid == 0 ){
							$bpid = $cnt;
						}
						if( empty( $bp_name_exists ) ){
							$this->bp_id = $bpid;
							$bpid++;
						}else{
							$this->bp_id = xprofile_get_field_id_from_name( $title );
							$id = $this->bp_id;
							$this->bp_parent_id = $this->bp_parent_id( $id );
							$this->bp_group_id = $this->bp_group_id( $id );
						}
					}else{
						$cnt++;
					}
				}
				if( empty( $key_exists ) ){
					if( empty( $name_exists ) ){
						if( empty( $bp_name_exists ) ){
							$sql = "INSERT INTO " . $table_name . " ( meta_key, option_meta_key, parent_id, creation_date, last_updated, data_type, field_name, field_description, field_required, registration_field, is_default_option, field_order, approve_view, option_order, min_number, max_number, number_step,  bp_ID, bp_parent_ID, bp_group_ID  ) " .
								"VALUES ('%s', '%s', '%d', now(), now(), '%s', '%s', '%s', '%d',  '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d' )";
							$insert = $wpdb->prepare( $sql, $this->meta_key, $this->option_meta_key, $this->parent_id, $this->data_type, $this->field_name, $this->field_description, $this->field_required, $this->registration_field, $this->is_default_option, $this->field_order, $this->approve_view, $this->option_order, $this->min_number, $this->max_number, $this->number_step, $this->bp_id, $this->bp_parent_id, $this->bp_group_id );
							$results = $wpdb->query( $insert );
						}else{
							$sql = "INSERT INTO " . $table_name . " ( meta_key, option_meta_key, parent_id, creation_date, last_updated, data_type, field_name, field_description, field_required, registration_field, is_default_option, field_order, approve_view, option_order, min_number, max_number, number_step,  bp_ID, bp_parent_ID, bp_group_ID  ) " .
								"VALUES ('%s', '%s', '%d', now(), now(), '%s', '%s', '%s', '%d',  '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d' )";
							$insert = $wpdb->prepare( $sql, $this->meta_key, $this->option_meta_key, $this->parent_id, $this->data_type, $this->field_name, $this->field_description, $this->field_required, $this->registration_field, $this->is_default_option, $this->field_order, $this->approve_view, $this->option_order, $this->min_number, $this->max_number, $this->number_step, $this->bp_id, $this->bp_parent_id, $this->bp_group_id );
							$results = $wpdb->query( $insert );
						}
					}	
				}
				$index++;
			}
		}
		$options['database_update'] = '1';
		update_option( 'csds_userRegAide_Options', $options );
		$ura_field_cnt = $this->fields_count();
		$ura_field_cnt-=1;
		if( $fcnt == $ura_field_cnt ){
			delete_option( 'csds_userRegAide_NewFields' );
			delete_option( 'csds_userRegAide_fieldOrder' );
		}
	}
	
	/** 
	 * function bp_last_id
	 * Select statement to get bp_profile_fields field id
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns int Buddy Press last field id $id
	*/
	
	function bp_last_id(){
		global $wpdb;
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$sql = "SELECT id FROM $table_name ORDER BY id DESC LIMIT 1";
		$id = $wpdb->get_var( $sql );
		return $id;	
	}
	
	/** 
	 * function bp_name_from_id
	 * Select statement to get bp_profile_fields field name by id
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $id Buddy Press Field ID
	 * @returns string Buddy Press field name
	*/
	
	function bp_name_from_id( $id ){
		global $wpdb;
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$sql = "SELECT name FROM $table_name WHERE id = %s";
		$run_query = $wpdb->prepare( $sql, $id );
		$name = $wpdb->get_var( $run_query );
		return $name;	
	}
	
	/** 
	 * function bp_parent_id
	 * Select statement to get bp_profile_fields option field parent id
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $id Buddy Press OPTION Field ID
	 * @returns int Buddy Press field parent_id
	*/
	
	function bp_parent_id( $id ){
		global $wpdb;
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$sql = "SELECT parent_id FROM $table_name WHERE id = %s";
		$run_query = $wpdb->prepare( $sql, $id );
		$parent_id = $wpdb->get_var( $run_query );
		return $parent_id;	
	}
	
	/** 
	 * function bp_group_id
	 * Select statement to get bp_profile_fields group id
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $id Buddy Press Field ID
	 * @returns int Buddy Press group id
	*/
	
	function bp_group_id( $id ){
		global $wpdb;
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$sql = "SELECT group_id FROM $table_name WHERE id = %s";
		$run_query = $wpdb->prepare( $sql, $id );
		$group_id = $wpdb->get_var( $run_query );
		return $group_id;	
	}
	
	/** 
	 * function bp_has_rows
	 * Select statement to determine whether bp_profile_fields table has any rows
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns array of rows if exists $exists
	*/
	
	function bp_has_rows(){
		global $wpdb;
		$option = 'option';
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$sql = "SELECT * FROM $table_name WHERE type != %s";
		$run_query = $wpdb->prepare( $sql, $option );
		$exists = $wpdb->get_results( $run_query, ARRAY_A );
		return $exists;	
	}
	
	/** 
	 * function bp_field_name_exists
	 * Select statement to determine whether bp field name exists
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $name
	 * @returns field name $exists if name already exists
	*/
	
	function bp_field_name_exists( $name ){
		global $wpdb;
		$table_name = $wpdb->prefix.'bp_xprofile_fields';
		$sql = "SELECT name FROM $table_name WHERE name = %s";
		$run_query = $wpdb->prepare( $sql, $name );
		$exists = $wpdb->get_var( $run_query );
		return $exists;	
	}
	
	/** 
	 * function update_reg_form_fields_options
	 * Updates the required fields from fields table to registration fields database options
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params int $id Buddy Press Field ID
	 * @returns int Buddy Press group id
	*/
	
	function update_reg_form_fields_options(){
		global $wpdb;
		$reg_fields = get_option( 'csds_userRegAide_registrationFields' );
		$optional_fields = get_option( 'csds_ura_optionalFields' );
		//update_option( 'csds_userRegAide_registrationFields', $reg_fields );
		$options = get_option( 'csds_userRegAide_Options' );
		if( !array_key_exists( 'reg_fields_updated', $options ) || $options['reg_fields_updated'] == "2" ){
			$table_name = $wpdb->prefix.'ura_fields';
			$required = true;
			$sql = "SELECT * FROM $table_name WHERE field_required = %d";
			$run_query = $wpdb->prepare( $sql, $required );
			$fields = $wpdb->get_results( $run_query );
			foreach( $fields as $object ){
				$key = $object->meta_key;
				$name = $object->field_name;
				unset( $reg_fields[$key] );
			}
			update_option( 'csds_userRegAide_registrationFields', $reg_fields );
			
			$options['reg_fields_updated'] = "1";
			update_option( 'csds_userRegAide_Options', $options );
		}
	}
	
	/** 
	 * function update_table_columns
	 * Updates database table if needed
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function update_table_columns(){
		global $wpdb;
		$table = $wpdb->prefix.'ura_fields';
		$sql = "SELECT * FROM $table ORDER BY ID DESC LIMIT 1";
		$results = $wpdb->get_row( $sql, ARRAY_N );
		$cnt = count( $results );;
		$options = get_option( 'csds_userRegAide_Options' );
		if( !array_key_exists( 'database_fields_update', $options ) ){
			if( $cnt == 19 ){
				
				$wpdb->query("ALTER TABLE $table ADD registration_field TINYINT(1) NOT NULL DEFAULT 0 AFTER field_required");
				
				$reg_fields = get_option( 'csds_userRegAide_registrationFields' );
				$ura_fields = $this->get_all_fields();
				foreach( $ura_fields as $object ){
					if( array_key_exists( $object->meta_key, $reg_fields ) ){
						$this->update_fields( $object->meta_key, 'registration_field', 1 );
					}
				}
				$options['table_columns_updated'] = "1";
				$options['database_fields_update'] = "1";
				update_option( 'csds_userRegAide_Options', $options );
			}
		}
	}
	
		
}