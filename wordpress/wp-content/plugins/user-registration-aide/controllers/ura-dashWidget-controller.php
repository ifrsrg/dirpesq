<?php

/**
 * Class URA_DASH_WIDGET_CONTROLLER
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_DASH_WIDGET_CONTROLLER
{
	
	/** 
	 * function ura_dashboard_widget_action
	 * Changes the default WordPress registration page top message 
	 * @since 1.3.0
	 * @updated 1.5.2.0
	 * @access public
	 * @handles action 'wp_dashboard_setup' line 126 user-registration-aide.php
	 * @params
	 * @returns
	*/
	
	function ura_dashboard_widget_action(){
		$options = get_option( 'csds_userRegAide_Options' );
		if( array_key_exists( 'show_dashboard_widget', $options ) ){
			$show_widget = $options['show_dashboard_widget'];
		}else{
			$show_widget = 2;
		}
		if( $show_widget == 1){
			wp_add_dashboard_widget( 'csds_ura_dash_widget', '<a class="ura-dash-widget" href="http://creative-software-design-solutions.com">Creative Software Design Solutions</a><b> User Registration Aide</b>', array( &$this, 'ura_dashboard_widget_display' ) );
		}
	}
	
	/** 
	 * function ura_dashboard_widget_action
	 * Adds stylesheets for dashboard widget to wp admin page for news & update information
	 * @since 1.3.0
	 * @updated 1.5.2.0
	 * @access public
	 * @handles action 'admin_print_styles' line 218 user-registration-aide.php
	 * @params
	 * @returns
	*/
	
	function csds_dashboard_widget_style(){
		wp_register_style( 'user_regAide_admin_dash_style', URA_CSS_PATH.'/admin-dash.css', false );
		wp_enqueue_style( 'user_regAide_admin_dash_style' );
	}
	
	/** 
	 * function ura_dashboard_widget_display
	 * Adds dashboard widget to wp admin page for news & update information
	 * @since 1.3.0
	 * @updated 1.5.2.0
	 * @access public
	 * @handles action line 60 &$this for 'wp_add_dashboard_widget'
	 * @params
	 * @returns
	*/
	
	function ura_dashboard_widget_display(){
		
		global $wpdb, $role, $wp_object_cache;
		$model = new URA_DASH_WIDGET_MODEL();
		$fields = $model->selected_user_args_fields_array();
		$all_fields = $model->selected_dw_fields_array();
		$index = ( int ) 0;
		$cnt = ( int ) 0;
		$page = ( int ) 1;
		$load = ( int ) 0;
		$id = array();
		$uid = array();
		$field1 = array();
		$field2 = array();
		$field3 = array();
		$field4 = array();
		$field5 = array();
		$ucnt = ( int ) 0;
		$count = ( int ) 0;
		$widget_view = new URA_DASH_WIDGET_VIEW();
					
		$user_args = array(
			'fields'	=>	'ID'
		);
		
		$users = get_users( $user_args );
		$widget_view->display_users( $users );
		
		/*
		if( $index == "0" ){
			$widget_view->display_users( $users );
		}
		*/		
	}
	
}