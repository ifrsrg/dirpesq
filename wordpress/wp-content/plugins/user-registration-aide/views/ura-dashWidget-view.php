<?php

/**
 * Class URA_DASH_WIDGET_VIEW
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_DASH_WIDGET_VIEW
{

	/**
	 * function user_display_name_view
	 * Displays options for dashboard widget on URA admin page
	 * @since 1.3.6
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @return 
	*/
	
	function dashboard_widget_options(){
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$options = get_option( 'csds_userRegAide_Options' );
			$model = new URA_DASH_WIDGET_MODEL();
			$all_fields = $model->fields_array();
			$sel_fields = $model->selected_dw_fields_array();
			$order_array = $model->dash_widget_fields_array();
			?>
			<table class="adminPage_Dash">
				<tr colspan="2">
					<th colspan="3"><?php _e( 'Dashboard Widget Display Options', 'user-registration-aide' );?> </th>
				</tr>
				<tr>
					<td style="text-align:center;"><?php _e( 'Choose to display or not display the dashboard widget on the WordPress Admin page: ', 'user-registration-aide' );?>
					<span title="<?php _e( 'Select this option to show the Creative Software Design Solutions Users table Dashboard Widget', 'user-registration-aide' );?>">
					<br/>
					<input type="radio" name="csds_dashWidgetDisplay" id="csds_dashWidgetDisplay" value="1" <?php
					if ($options['show_dashboard_widget'] == 1) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
					<span title="<?php _e( 'Select this option NOT to show the Creative Software Design Solutions Users table Dashboard Widget',  'user-registration-aide' );?>">
					<input type="radio" name="csds_dashWidgetDisplay" id="csds_dashWidgetDisplay" value="2" <?php
					if ($options['show_dashboard_widget'] == 2) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
					<br/>
					<div style="text-align:center;" class="submit"><input type="submit" class="button-primary" name="dash_widget_display_option" value="<?php _e( 'Update Dashboard Widget Display Option', 'user-registration-aide' );?>"/></div>
					</td>
									
					<td><?php// adding option for user to choose own fields for dashboard widget ?>
					<p><?php _e( 'Here, you can select your own fields to show on the User Registration Aide Dashboard Widget.', 'user-registration-aide' );?></p>
					<p><b><?php _e( 'Note: You can only select a maximum of 5 fields due to the space constraints in the WordPress Dashboard! (Hold down the control key to select more than 1 field just like adding new fields to the registration form.', 'user-registration-aide' );?></b></p>
					<p style="text-align:center;" class="adminPage"><select name="dw_selectedFields[]" id="csds_userRegMod_Select" title="<?php _e( 'You can only select up to 5 fields due to space limitations, just hold down the control key while selecting multiple fields.', 'user-registration-aide' );?>" size="8" multiple style="height:100px">
					<?php
					foreach( $all_fields as $key1 => $value1 ){
						if( !empty( $sel_fields ) ){
							if( in_array( $value1, $sel_fields ) ){
								$selected = "selected=\"selected\"";
							}else{
								$selected = NULL;
							} // end if
						}else{
							$selected = NULL;
						} // end if
						
					echo "<option value=\"$key1\" $selected >$value1</option>";
						
					} // end foreach ?>
					</select>
					</p>
					<div style="text-align:center;" class="submit"><input type="submit" class="button-primary" name="dash_widget_fields_update" value="<?php _e( 'Update Dashboard Widget Fields', 'user-registration-aide' );?>"/></div>
					</td>
					
					<?php // setting field order for field in dashboard widget 
					$i = ( int ) 0;
					$fieldKey = (string) '';
					$fieldOrder = ( int ) 0;
					$fieldKey = (string) '';
					$fieldKeyUpper = (string) '';
					$i = count($sel_fields);
					$cnt = ( int ) 1; 
					// Table for field order ?>
					<td>
					<br/>
					<table class="newFields1">
					<tr>
					<th colspan="2"><?php _e( 'Set Dashboard Widget Field Order', 'user-registration-aide' );?></th>
					</tr>
					<tr>
					<th><?php _e( 'Dashboard Widget Field Name: ', 'user-registration-aide' );?></th>
					<th><?php _e( 'Current Field Order: ', 'user-registration-aide' );?></th>
					</tr>
					
					
					<?php
					if( !empty( $sel_fields ) ){
						foreach( $sel_fields as $skey => $svalue ){ ?>
							<tr>
							<td class="fieldName"> <?php
								$fieldKeyUpper = strtoupper( $svalue );
								echo '<label for="'.$skey.'"><b>'.$fieldKeyUpper.'</b></label>';
								//Changed from check box to label here ?>
							</td>
							<td class="fieldOrder">
								<select  class="fieldOrder" name="csds_editDWFieldOrder[]" title="<?php __( 'Make sure that there are no duplicate field order numbers, like two fields having number 2 for their order!', 'user-registration-aide' );?>">
								<?php
								for( $ii = 1; $ii <= $i; $ii++ ){
									//if($ii == $cnt){
										$fieldOrder = $order_array[$cnt]['order'];
										$fieldKey = $order_array[$cnt]['key'];
									//}
									if( $ii == $fieldOrder ){
										echo '<option selected="'.$fieldOrder.'" >'.$fieldOrder.'</option>';
									}else{
										echo '<option value="'.$ii.'">'.$ii.'</option>';
									}									
								}?>
								</select>
							</td>
						</tr>
							<?php
							$cnt ++;
						}
					echo '';
					} ?>
					</table>
					<div style="text-align:center;" class="submit"><input type="submit" class="button-primary" name="dash_widget_field_order_update" value="<?php _e( 'Update Dashboard Widget Field Order', 'user-registration-aide' );?>"/></div>
						
					</td>
				</tr>
				
			</table> <?php
		}
	} // end function
	
	/**
	 * function user_display_name_view
	 * Displays dashboard widget users for wp admin page for news & update information
	 * @since 1.3.0
	 * @updated 1.5.2.0
	 * @access public
	 * @handles display users line 97 &$this
	 * @params $users array of wordpress site users
	 * @return 
	*/
	
	function display_users( $users ){
		
		global $wpdb, $roles, $current_user;
		
		// old $logo = IMAGES_PATH."csds-dash_logo.png";
		// new
		$logo = URA_IMAGES_PATH."csds-dash_logo.png";
		$cols = array();
		$model = new URA_DASH_WIDGET_MODEL();
		$cols = $model->selected_dw_fields_array();
		$count = count( $users );
		$col_cnt = count( $cols );
		$col_span = $col_cnt + 1;
		
		echo '<div class="csds-dash-widget" id="csds-dash-widget">';
		
		$current_user = wp_get_current_user();
		if( current_user_can( 'manage_options', $current_user->ID ) ){					
			echo '<table class="admin-dash">';
				echo '<tr>';
				echo '<th colspan="'.$col_span.'" class="main_title">';
				echo __( 'Quick Glance Current Site Users:', 'user-registration-aide' );
				echo '</th>';
				echo '</tr>';
				echo '<tr>';
				foreach( $cols as $key => $name ){
					echo '<th class="col_titles">'. __( $name, 'user-registration-aide' ).'</th>';
				}
				echo '</tr>';
				
				foreach( $users as $numb => $id ){ 
					$nuser = get_userdata( $id );
					echo '<tr>';
					
					foreach( $cols as $key => $value ){
					
						if( $key != 'roles' ){
							echo '<td>'.$nuser->$key.'</td>';
						}elseif( $key == 'roles' ){
							//$user = new WP_User( $nuser->ID );
							//$roles = $nuser->$key;
							$roles = $nuser->roles;
							$userrole = array_shift( $roles );
							echo '<td>'.$userrole.'</td>';
						
						}
						
					}
					
					echo '</tr>';
				}
				
				echo '</table>';
				echo '<br />';
			echo '<a href="http://creative-software-design-solutions.com" target="_blank" rel="follow"><img src="'.$logo.'" /></a>';
			echo '</div>';
		}else{
			echo '<a href="http://creative-software-design-solutions.com" target="_blank" rel="follow"><img src="'.$logo.'" /></a>';
			echo '</div>';
		}
				
	}
	
}// end class