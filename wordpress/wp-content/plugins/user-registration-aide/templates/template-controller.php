<?php

/**
 * Class URA_TEMPLATE_CONTROLLER
 * User Registration Aide - Controller Class for Custom Templates
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_TEMPLATE_CONTROLLER
{
		
	/**
	 * function template_array
	 * Returns array of custom URA template slugs-titles for other functions
	 * @category function
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $templates custom URA page templates
	*/
	
	function template_array(){
		$templates = array(
			'ura-account-locked-out'								=>	'Account Locked Out',
			'ura-email-confirm'										=>	'Email Confirm',
			'ura-update-password'									=>	'Update Password',
			'ura-lost-password'										=>	'Lost Password',
			'ura-reset-password'									=>	'Reset Password'
		);
		return $templates;
	}
	
	/** 
	 * function get_custom_templates_menu_ids
	 * gets the menu item ids for ura custom template pages 
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $post_ids array of ids for pages
	*/

	function get_custom_templates_menu_ids(){
		global $wpdb;
		$index = ( int ) 0;
		$key_1 = 'ura-page';
		$table_1 = $wpdb->prefix . "posts";
		$table_2 = $wpdb->prefix . "postmeta";
		$sql_1 = "SELECT ID FROM $table_1 WHERE ura_post_type = %s";
		$run_query_1 = $wpdb->prepare( $sql_1, $key_1 );
		$ids = $wpdb->get_results( $run_query_1 );
		$post_ids = array();
		$meta_post_id = ( string ) '';
		foreach( $ids as $object ){
			$sql_2 = "SELECT post_id FROM $table_2 WHERE meta_key = '_menu_item_object_id' AND meta_value = '$object->ID'";
			$id = $wpdb->get_var( $sql_2 );
			if( !empty( $id ) ){
				$post_ids[$index] = $id;
				$index++;
			}
			
		}
		return $post_ids;
	}
	
	/**
	 * function template_ids
	 * Returns URA custom page ids for other functions
	 * @category function
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns $ids csv of custom URA page IDs
	*/
	
	function template_ids(){
		global $wpdb;
		$templates = $this->template_array();
		$cnt = ( int ) 0;
		$table = $wpdb->prefix . 'posts';
		$id = ( string ) '';
		$type = ( string ) 'ura-page';
		$sql_1 = "SELECT ID FROM $table WHERE ura_post_type = %s";
		$run_query_1 = $wpdb->prepare( $sql_1, $type );
		$ids = $wpdb->get_results( $run_query_1 );
		foreach( $ids as $object ){
			if( $cnt == 0 ){
				$id = $object->ID;
			}else{
				$id .= ','.$object->ID;
			}
			$cnt++;
		}
		/*
		foreach( $templates as $key	=> $value ){
			$sql = "SELECT ID FROM $table WHERE post_name = %s";
			$run_query = $wpdb->prepare( $sql, $key );
			$id = $wpdb->get_var( $run_query );
			if( $cnt == 0 ){
				$ids = $id;
			}else{
				$ids .= ','.$id;
			}
			$cnt++;
		}
		*/
		return $id;
	}
	
	/**
	 * function page_ids
	 * Returns URA custom page ids for other functions
	 * @category function
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $ids
	*/
	
	function page_ids(){
		global $wpdb;
		$templates = $this->template_array();
		$cnt = ( int ) 0;
		$table = $wpdb->prefix . 'posts';
		$id = array();
		$type = ( string ) 'ura-page';
		$sql_1 = "SELECT ID FROM $table WHERE ura_post_type = %s";
		$run_query_1 = $wpdb->prepare( $sql_1, $type );
		$ids = $wpdb->get_results( $run_query_1 );
		foreach( $ids as $object ){
			$id[$cnt] = $object->ID;
			$cnt++;
		}
		return $id;
		/*
		foreach( $templates as $key	=> $value ){
			$sql = "SELECT ID FROM $table WHERE post_name = %s";
			$run_query = $wpdb->prepare( $sql, $key );
			$id = $wpdb->get_var( $run_query );
			$ids[$cnt] = $id;
			$cnt++;
		}
		return $ids;
		*/
	}
			
	/**
	 * function locked_account_redirect
	 * Redirects locked accounts to account locked out page
	 * @category function
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params ( int ) $user_id, date time $release_time 
	 * @returns
	*/
	
	function locked_account_redirect( $user_id, $release_time ){
		$url = get_site_url();
		//$redirect_url = trailingslashit( $url."/account-locked-out?release=$release_time" );
		//$redirect_url = esc_url( $redirect_url );
		$url = site_url().'/account-locked-out';
		$redirect_url = add_query_arg( array(
			'release'	=>	$release_time			
		), $url );
		//exit( $new_query );
		wp_safe_redirect( esc_url_raw( $redirect_url ) );
	}
	
	/**
	 * function create_extra_post_type
	 * Creates new field in posts table for custom posts types
	 * @category function
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function create_extra_post_type() {
		
		global $wpdb;
		$options = get_option( 'csds_userRegAide_Options' );
		if( array_key_exists( 'post_column_created', $options ) ){
			if( empty( $options['post_column_created'] ) || $options['post_column_created'] == 0 ){
				$options['post_column_created'] = 2;
				update_option( 'csds_userRegAide_Options', $options );
				unset( $options );
			}
		}else{
			$options['post_column_created'] = 2;
			update_option( 'csds_userRegAide_Options', $options );
			unset( $options );
		}
		$options = get_option( 'csds_userRegAide_Options' );
		$table_name = $wpdb->prefix . "posts";
		$col_sql = "SHOW COLUMNS FROM $table_name LIKE 'ura_post_type'";
		$col_exists = $wpdb->query( $col_sql );
		if( $col_exists == 1 ){
			$options['post_column_created'] = 1;
			update_option( 'csds_userRegAide_Options', $options );
		}else{
			$sql = "ALTER TABLE $table_name ADD ura_post_type VARCHAR(10)";
			$fields = $wpdb->query( $sql );
			$options['post_column_created'] = 1;
			update_option( 'csds_userRegAide_Options', $options );
		}
		
	}
	
	/**
	 * function create_extra_post_type
	 * Adds custom pages on install/update depending on if BuddyPress is active
	 * @category function
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function csds_pages_setup(){
		global $wpdb, $user;
		$options = get_option( 'csds_userRegAide_Options' );
		$user = wp_get_current_user();
		$user_id = $user->ID;
		$plugin = 'buddypress/bp-loader.php';
		$confirm_name = ( string ) '';
		$title = ( string ) '';
		$post_id = ( int ) 0;
		
		/* trying to keep wordpress from adding new pages to menu but apparently doesnt work
		$auto_add = get_option( 'nav_menu_options' );
		$auto_add['auto_add'] = false;
		update_option( 'nav_menu_options', $auto_add );
		*/
		
		$templates = $this->template_array();
		foreach( $templates as $slug => $title ){
			$table = $wpdb->prefix . 'posts';
			$sql = "SELECT post_name FROM $table WHERE post_name = %s";
			$run_query = $wpdb->prepare( $sql, $slug );
			$name = $wpdb->get_var( $run_query );
			
			if ( $name != $slug ) {
				$url = get_site_url();
				$new_post = array(
					'post_name'			=>	$slug,
					'guid'				=>	$url.'/'.$slug.'/',
					'post_title' 		=>	$title,
					'post_content' 		=>	'',
					'post_status' 		=>	'publish',
					'comment_status'	=>	'closed',
					'ping_status'		=>	'closed',
					'post_author' 		=>	$user_id,
					'post_type' 		=>	'page',
					'post_category' 	=>	array(0),
					'ura_post_type'		=>	'ura-page'
				);
				$post_id = wp_insert_post( $new_post );
				
				$table = $wpdb->prefix . 'posts';
				$data = array(
					'ura_post_type'		=> 'ura-page'
				);
				$where = array(
					'post_name'	=>	$slug
				);
				$wpdb->update( $table, $data, $where );
				// updates pages menus in widgets to exclude our custom pages
				if( !$post_id == NULL ){
					$pages = get_option( 'widget_pages' );
					$count = count( $pages );
					if( $count <= 2 ){
						if( $pages[2]['exclude'] == null ){
							$pages[2]['exclude'] = $post_id;
						}else{
							$pages[2]['exclude'] .= ','.$post_id;
						}
					}elseif( $count >= 3 ){
						for( $i = 2; $i <= $count; $i++ ){
							if( $pages[$i]['exclude'] == null ){
								$pages[$i]['exclude'] = $post_id;
							}else{
								$pages[$i]['exclude'] .= ','.$post_id;
							}
						}
					}
					update_option( 'widget_pages', $pages );
				}
			}
		}
		$options['template_slug_change'] = 1;
		$options['pages_created'] = 1;
		update_option( 'csds_userRegAide_Options', $options );
		
	}
	
}