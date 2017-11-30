<?php

//For Debugging and Testing Purposes ------------

// checked and updated 1.5.0.0

// ----------------------------------------------

	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
	require_once ( ABSPATH . 'wp-content/plugins/user-registration-aide/models/ura-profile-model.php' );
	require_once ( ABSPATH . 'wp-content/plugins/user-registration-aide/helpers/ura-fields-database.php' );
	if(!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')){
		exit('Something Bad Happened');
	}else{
		// some options may not exist any more but including for those who don't update often
		delete_option('csds_userRegAideFields');
		delete_option('csds_userRegAide_knownFields');
		delete_option('csds_userRegAide_fieldOrder');
		delete_option('csds_userRegAide_registrationFields');
		delete_option('csds_userRegAide_newField');
		delete_option('csds_userRegAide_dbVersion');
		delete_option('csds_userRegAide');
		delete_option('csds_userRegAide_Options');
		delete_option('csds_userRegAide_SecurityQuestions');
		delete_option('csds_userRegAide_support');
		delete_option('csds_display_link');
		delete_option('csds_display_name');
		delete_option('csds_userRegAide_fieldOrder');
		delete_option('csds_ura_optionalFields');
		
		$table_name = $wpdb->prefix . "ura_xwrd_change";
		$sql = "DROP TABLE IF EXISTS $table_name;";
		$wpdb->query( $sql );
		$pm = new URA_PROFILE_MODEL();
		$id = ( int	) 0;		
		$old_fields = get_option( 'csds_userRegAide_NewFields' );
		if( !empty( $old_fields ) ){
			foreach( $old_fields as $field => $value ){
				$pm->csds_delete_field_from_users_meta( $field );
			}
		}
		delete_option('csds_userRegAide_NewFields');
		$fdb = new FIELDS_DATABASE();
		$fields = $fdb->get_all_fields();
		
		foreach( $fields as $object ){
			$meta_key = $object->meta_key;
			$pm->csds_delete_field_from_users_meta( $meta_key );
		}
		$table_name = $wpdb->prefix . "ura_fields";
		$sql = "DROP TABLE IF EXISTS $table_name;";
		$wpdb->query( $sql );
				
		$args = array( 
			'ura_post_type'		=>	'ura-page',
			'post_type'			=>	'page'
		);
		$ura_posts = get_posts( $args );
		if( is_array( $ura_posts ) ){
			foreach( $ura_posts as $post ){
				if( $post->ura_post_type == 'ura-page' ){
					wp_delete_post( $post->ID, true );
				}
			}
		}
		
	}
	

?>