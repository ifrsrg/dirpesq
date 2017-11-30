<?php

/**
 * Class URA_DASH_WIDGET_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_DASH_WIDGET_MODEL
{

	/** 
	 * function update_dashboard_widget_fields
	 * Updates selected fields for dashboard widget on URA admin page
	 * @since 1.3.6
	 * @updated 1.5.2.0
	 * @access public
	 * @handles action 'update_dw_field_options' line 247 user-registration-aide.php
	 * @params string $old_msg
	 * @returns string $msg ( results of update updated or error  msg )
	*/
	
	function update_dashboard_widget_fields( $old_msg ){
		$results = array();
		$fields = array();
		$update = array();
		$cnt = ( int ) 1;
		$errors = ( int ) 0;
		$err_message = (string) '';
		$update = get_option( 'csds_userRegAide_Options' );
		$fields = $this->fields_array();
		$fcnt = ( int ) 0;
		$msg = ( string ) '';
		if( !empty( $_POST['dw_selectedFields'] ) ){
		
			$results = $_POST['dw_selectedFields'];
			$fcnt = count( $results );
			if( $fcnt >= 6 ){
				$errors ++;
				$err_message = __( 'You can only have a maximum of 5 fields to show in dashboard widget!', 'user-registration-aide' );
			}else{
				foreach( $results as $key => $value ){
					foreach( $fields as $key1 => $value1 ){
						if( $value == $key1 ){
							if( $cnt == 1 ){
								$update['dwf1_key'] = $value;
								$update['dwf1'] = $value1;
								$update['dwf1_order'] = $cnt;
							}elseif( $cnt == 2 ){
								$update['dwf2_key'] = $value;
								$update['dwf2'] = $value1;
								$update['dwf2_order'] = $cnt;
							}elseif( $cnt == 3 ){
								$update['dwf3_key'] = $value;
								$update['dwf3'] = $value1;
								$update['dwf3_order'] = $cnt;
							}elseif( $cnt == 4 ){
								$update['dwf4_key'] = $value;
								$update['dwf4'] = $value1;
								$update['dwf4_order'] = $cnt;
							}elseif( $cnt == 5 ){
								$update['dwf5_key'] = $value;
								$update['dwf5'] = $value1;
								$update['dwf5_order'] = $cnt;
							}elseif( $cnt >= 6 ){
								$errors ++;
								$err_message = __( 'You can only have a maximum of 5 fields to show in dashboard widget!', 'user-registration-aide' );
								break;
							} // end if
						}
					} //end 2nd foreach
					$cnt ++;			
				} // end foreach
				//update_option( 'csds_userRegAide_Options', $update );
				if( $fcnt == 1 ){
					//exit( ' - - - - - - - - - - - - - - - COUNT = = = = = = = = = 1 - - - - - - - ' );
					$update['dwf2_key'] = '';
					$update['dwf2'] = '';
					$update['dwf2_order'] = '';
					$update['dwf3_key'] = '';
					$update['dwf3'] = '';
					$update['dwf3_order'] = '';
					$update['dwf4_key'] = '';
					$update['dwf4'] = '';
					$update['dwf4_order'] = '';
					$update['dwf5_key'] = '';
					$update['dwf5'] = '';
					$update['dwf5_order'] = '';
				}elseif( $fcnt == 2 ){
					$update['dwf3_key'] = '';
					$update['dwf3'] = '';
					$update['dwf3_order'] = '';
					$update['dwf4_key'] = '';
					$update['dwf4'] = '';
					$update['dwf4_order'] = '';
					$update['dwf5_key'] = '';
					$update['dwf5'] = '';
					$update['dwf5_order'] = '';
				}elseif( $fcnt == 3 ){
					$update['dwf4_key'] = '';
					$update['dwf4'] = '';
					$update['dwf4_order'] = '';
					$update['dwf5_key'] = '';
					$update['dwf5'] = '';
					$update['dwf5_order'] = '';
				}elseif( $fcnt == 4 ){
					$update['dwf5_key'] = '';
					$update['dwf5'] = '';
					$update['dwf5_order'] = '';
				}
				update_option( 'csds_userRegAide_Options', $update );
			}
		}elseif( empty( $_POST['dw_selectedFields'] ) ){
			$errors ++;
			$err_message = __( "You haven't selected any fields to show in dashboard widget!", 'user-registration-aide' );
		} // end if
				
		if( $errors == 0 ){
			$msg = '<div id="message" class="updated"><p>'. __( 'Dashboard Widget Fields Updated Successfully!', 'user-registration-aide' ) .'</p></div>';					//Report to the user that the data has been updated successfully
		}else{
			$msg = '<div id="message" class="error"><p>'. __( $err_message, 'user-registration-aide' ) .'</p></div>';
			//exit();
		} // end if
		if( !empty( $msg ) ){
			return $msg;
		}
	}
	
	/** 
	 * function update_dashboard_widget_options
	 * Updates display option for dashboard widget on URA admin page
	 * @since 1.3.6
	 * @updated 1.5.2.0
	 * @access public
	 * @handles action 'update_dw_display_options' line 246 user-registration-aide.php
	 * @params string $old_msg
	 * @returns string $msg ( results of update updated or error  msg )
	*/
	
	function update_dashboard_widget_options( $old_msg ){
		$msg = ( string ) '';
		$update = get_option( 'csds_userRegAide_Options' );
		$update['show_dashboard_widget'] = $_POST['csds_dashWidgetDisplay'];
		update_option( 'csds_userRegAide_Options', $update );
		$msg = '<div id="message" class="updated"><p>'. __( 'Dashboard Widget Fields Display Option Updated Successfully!', 'user-registration-aide' ) .'</p></div>';
		
		return $msg;
							//Report to the user that the data has been updated successfully
		
	} // end function
	
	/** 
	 * function update_dashboard_widget_field_order
	 * Updates fields order for dashboard widget on URA admin page
	 * @since 1.3.6
	 * @updated 1.5.2.0
	 * @access public
	 * @handles action 'update_dw_field_order' line 248 user-registration-aide.php
	 * @params string $old_msg
	 * @returns string $msg ( results of update updated or error  msg )
	*/
	
	function update_dashboard_widget_field_order( $old_msg ){
		$errors = ( int ) 0;
		$err_message = (string) '';
		$results = array();
		$dwf_cnt = ( int ) 1;
		$message = (string) '';
		$results = $_POST['csds_editDWFieldOrder'];
		$update = get_option('csds_userRegAide_Options');
		$msg = ( string ) '';
		foreach( $results as $key => $value ){
			foreach( $results as $key1 => $value1 ){
				if( $key != $key1 && ( $value == $value1 ) ){
					$errors ++;
					$err_message = __( 'Duplicate Field Orders, can\'t assign two fields the same order number! Please try again!', 'user-registration-aide' );
					break;
				}
			}
		}
		
		if( $errors == 0 ){
			foreach( $results as $rkey => $rname ){
				if( $dwf_cnt == 1 ){
					$update['dwf1_order'] = $rname;
				}elseif( $dwf_cnt == 2 ){
					$update['dwf2_order'] = $rname;
				}elseif( $dwf_cnt == 3 ){
					$update['dwf3_order'] = $rname;
				}elseif( $dwf_cnt == 4 ){
					$update['dwf4_order'] = $rname;
				}elseif( $dwf_cnt == 5 ){
					$update['dwf5_order'] = $rname;
				}
				$dwf_cnt ++;
			}
			update_option( 'csds_userRegAide_Options', $update );
			$this->update_dash_widget_fields_order();
		}
				
		if( $errors == 0 ){
			$msg = '<div id="message" class="updated"><p>'. __( 'Dashboard Widget Fields Order Updated Successfully!', 'user-registration-aide' ) .'</p></div>';					//Report to the user that the data has been updated successfully
		}else{
			$msg = '<div id="message" class="error""><p>'. __( $err_message, 'user-registration-aide' ) .'</p></div>';
			//exit();
		} // end if
		return $msg;
	}
	
	/** 
	 * function update_dash_widget_fields_order
	 * Sets the fields in order for the dashboard widget fields display after update
	 * @since 1.3.6
	 * @updated 1.5.2.0
	 * @access public
	 * @handles action 'update_dw_field_order' line 248 user-registration-aide.php
	 * @params string $old_msg
	 * @returns
	*/
	
	function update_dash_widget_fields_order(){
		$msg = ( string ) '';
		$cnt = ( int ) 0;
		$dwf = ( int ) 1;
		$update = array();
		$update = get_option( 'csds_userRegAide_Options' );
		$dw_array = $this->dash_widget_fields_array();
		$temp = array();
		$cnt = count( $dw_array );
		
		foreach( $dw_array as $key => $value ){
			foreach( $value as $key1 => $value1 ){
				if( $value1 == 1 && $cnt >=  1 ){
					$temp[1]['key'] = $dw_array[$dwf]['key'];
					$temp[1]['name'] = $dw_array[$dwf]['name'];
					$temp[1]['order'] = $dw_array[$dwf]['order'];
				}elseif( $value1 == 2 && $cnt >=  2 ){
					$temp[2]['key'] = $dw_array[$dwf]['key'];
					$temp[2]['name'] = $dw_array[$dwf]['name'];
					$temp[2]['order'] = $dw_array[$dwf]['order'];				
				}elseif( $value1 == 3 && $cnt >=  3 ){
					$temp[3]['key'] = $dw_array[$dwf]['key'];
					$temp[3]['name'] = $dw_array[$dwf]['name'];
					$temp[3]['order'] = $dw_array[$dwf]['order'];
				}elseif( $value1 == 4 && $cnt >=  4 ){
					$temp[4]['key'] = $dw_array[$dwf]['key'];
					$temp[4]['name'] = $dw_array[$dwf]['name'];
					$temp[4]['order'] = $dw_array[$dwf]['order'];
				}elseif( $value1 == 5 && $cnt ==  5 ){
					$temp[5]['key'] = $dw_array[$dwf]['key'];
					$temp[5]['name'] = $dw_array[$dwf]['name'];
					$temp[5]['order'] = $dw_array[$dwf]['order'];
				}
			}
			$dwf ++;
		}
		
		if( $cnt >=  1 ){
			$update['dwf1_key'] = $temp[1]['key'];
			$update['dwf1'] = $temp[1]['name'];
			$update['dwf1_order'] = $temp[1]['order'];
		}
		if( $cnt >=  2 ){
			$update['dwf2_key'] = $temp[2]['key'];
			$update['dwf2'] = $temp[2]['name'];
			$update['dwf2_order'] = $temp[2]['order'];
		}
		if( $cnt >=  3 ){
			$update['dwf3_key'] = $temp[3]['key'];
			$update['dwf3'] = $temp[3]['name'];
			$update['dwf3_order'] = $temp[3]['order'];
		}
		if( $cnt >=  4 ){
			$update['dwf4_key'] = $temp[4]['key'];
			$update['dwf4'] = $temp[4]['name'];
			$update['dwf4_order'] = $temp[4]['order'];
		}
		if( $cnt >=  5 ){
			$update['dwf5_key'] = $temp[5]['key'];
			$update['dwf5'] = $temp[5]['name'];
			$update['dwf5_order'] = $temp[5]['order'];
		}
		
		update_option( 'csds_userRegAide_Options', $update );
					
		//return $cnt; // testing
		
	}
	
	/** 
	 * function dash_widget_fields_array
	 * Sets the fields in order for the dashboard widget fields display
	 * @since 1.3.6
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns array $order_array
	*/
	
	function dash_widget_fields_array(){
		
		$options = get_option( 'csds_userRegAide_Options' );
		
		if( !empty( $options['dwf1_key'] ) && !empty( $options['dwf1'] ) && !empty( $options['dwf1_order'] ) ){
			$order_array[1] = array( "key" => $options['dwf1_key'], "name" => $options['dwf1'], "order" => $options['dwf1_order'] );
		}
		if( !empty( $options['dwf2_key'] ) && !empty( $options['dwf2'] ) && !empty( $options['dwf2_order'] ) ){
			$order_array[2] = array( "key" => $options['dwf2_key'], "name" => $options['dwf2'], "order" => $options['dwf2_order'] );
		}
		if( !empty( $options['dwf3_key'] ) && !empty($options['dwf3'] ) && !empty( $options['dwf3_order'] ) ){
			$order_array[3] = array( "key" => $options['dwf3_key'], "name" => $options['dwf3'], "order" => $options['dwf3_order'] );
		}
		if( !empty( $options['dwf4_key'] ) && !empty( $options['dwf4'] ) && !empty( $options['dwf4_order'] ) ){
			$order_array[4] = array( "key" => $options['dwf4_key'], "name" => $options['dwf4'], "order" => $options['dwf4_order'] );
		}
		if( !empty( $options['dwf5_key'] ) && !empty( $options['dwf5'] ) && !empty( $options['dwf5_order'] ) ){
			$order_array[5] = array( "key" => $options['dwf5_key'], "name" => $options['dwf5'], "order" => $options['dwf5_order'] );
		}
				
		return $order_array;
		
	}
	
	/** 
	 * function selected_dw_fields_array
	 * Creates and returns an array of all the chosen dashboard widget fields
	 * @since 1.3.6
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns array $sel_fields
	*/
	
	function selected_dw_fields_array(){
		
		$options = get_option( 'csds_userRegAide_Options' );
		$key = ( string ) '';
		$sel_fields = array();
		if( !empty( $options['dwf1_key'] ) && !empty( $options['dwf1'] ) ){
			$key = $options['dwf1_key'];
			$sel_fields[$key] = $options['dwf1'];
		}
		if( !empty( $options['dwf2_key'] ) && !empty( $options['dwf2'] ) ){
			$key = $options['dwf2_key'];
			$sel_fields[$key] = $options['dwf2'];
		}
		if( !empty( $options['dwf3_key'] ) && !empty( $options['dwf3'] ) ){
			$key = $options['dwf3_key'];
			$sel_fields[$key] = $options['dwf3'];
		}
		if( !empty( $options['dwf4_key'] ) && !empty( $options['dwf4'] ) ){
			$key = $options['dwf4_key'];
			$sel_fields[$key] = $options['dwf4'];
		}
		if( !empty( $options['dwf5_key'] ) && !empty( $options['dwf5'] ) ){
			$key = $options['dwf5_key'];
			$sel_fields[$key] = $options['dwf5'];
		}
		
		return $sel_fields;	

	} // end function
		
	/** 
	 * function selected_user_args_fields_array
	 * Creates and returns an array of all the chosen dashboard widget fields
	 * @since 1.3.6
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns array $fields
	*/
	
	function selected_user_args_fields_array(){
		
		$options = get_option( 'csds_userRegAide_Options' );
		$key = ( string ) '';
		$sel_fields = ( string ) '';
		$fields = array();
		if( !empty( $options['dwf1_key'] ) && !empty( $options['dwf1'] ) ){
			$key = $options['dwf1_key'];
			if( $key != 'roles' ){
				$fields[] = $key;
			}else{
				$fields[] = 'ID';
			}
		}
		if( !empty( $options['dwf2_key'] ) && !empty( $options['dwf2'] ) ){
			$key = $options['dwf2_key'];
			if( $key != 'roles' ){
				$fields[] = $key;
			}else{
				$fields[] = 'ID';
			}
		}
		if( !empty( $options['dwf3_key'] ) && !empty( $options['dwf3'] ) ){
			$key = $options['dwf3_key'];
			if( $key != 'roles' ){
				$fields[] = $key;
			}else{
				$fields[] = 'ID';
			}
		}
		if( !empty( $options['dwf4_key'] ) && !empty( $options['dwf4'] ) ){
			$key = $options['dwf4_key'];
			if( $key != 'roles' ){
				$fields[] = $key;
			}else{
				$fields[] = 'ID';
			}
		}
		if( !empty( $options['dwf5_key'] ) && !empty( $options['dwf5'] ) ){
			$key = $options['dwf5_key'];
			if( $key != 'roles' ){
				$fields[] = $key;
			}else{
				$fields[] = 'ID';
			}
		}
		unset ( $options );
		return $fields;
	} // end function
	
	/** 
	 * function fields_array
	 * Displays all the fields user can choose from to display on his dashboard widget
	 * @since 1.3.6
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns array $all_fields
	*/
	
	function fields_array(){
		
		$existing_fields = array(
			'user_login'	=>	__( 'Login', 'user-registration-aide' ),
			'user_nicename'	=>	__( 'Username', 'user-registration-aide' ),
			'user_email'	=>	__( 'Email', 'user-registration-aide' ),
			'user_url'		=>	__( 'Website', 'user-registration-aide' ),
			'display_name'	=>	__( 'Display Name', 'user-registration-aide' ),
			'first_name'	=>	__( 'First Name', 'user-registration-aide' ),
			'last_name'		=>	__( 'Last Name', 'user-registration-aide' ),
			'nickname'		=>	__( 'Nickname', 'user-registration-aide' ),
			'roles'			=>	__( 'Role', 'user-registration-aide' )
		);
		
		$new_fields = array();
		$new_fields = get_option( 'csds_userRegAide_NewFields' );
		$all_fields = array();
		if( !empty( $new_fields ) ){
			$all_fields = array_merge( $existing_fields, $new_fields );
		}else{
			$all_fields = $existing_fields;
		}
		return $all_fields;		
			
	}
	
}