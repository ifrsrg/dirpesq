<?php

/**
 * Class  URA_HTML
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_HTML
{
	/**	
	 * function reg_form_hidden
	 * Registration form hidden fielld box normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function reg_form_hidden( $label, $name, $id, $value ){
		$value = sanitize_text_field( $value );
		if( !is_plugin_active( 'theme-my-login/theme-my-login.php' ) ){
			?>
			<p>
			<input autocomplete="off" type="hidden" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="input" value="<?php echo $value;?>" />
			</label>
			</p>
			<?php
		}else{
			?>
			<p>
			<label><?php _e( $label, 'user-registration-aide' ); ?></label>
			<br />
			<input autocomplete="on" type="text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="input" value="<?php echo $value;?>" size="25" />
			</p>
			<?php
		}
	}
	
	/**	
	 * function reg_form_text
	 * Registration form text box normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function reg_form_text( $label, $name, $id, $value ){
		
		$value = sanitize_text_field( $value );
		if( !is_plugin_active( 'theme-my-login/theme-my-login.php' ) ){
			?>
			<p>
			<label><?php _e( $label, 'user-registration-aide' ); ?>
			<br />
			<input autocomplete="on" type="text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="input" value="<?php echo $value;?>" size="20" />
			</label>
			</p>
			<?php
		}else{
			?>
			<p>
			<label><?php _e( $label, 'user-registration-aide' ); ?>
			<br />
			<input autocomplete="on" type="text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="input" value="<?php echo $value;?>" size="20" />
			</label>
			</p>
			<?php
		}
	}
	
	/**	
	 * function reg_form_number
	 * Registration form number input normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function reg_form_number( $label, $name, $id, $value ){
		$min = (int) 0;
		$max = (int) 0;
		$step = (int) 0;
		$field = new FIELDS_DATABASE();
		$min = $field->min_number( $name );
		$max = $field->max_number( $name );
		$step = $field->step_number( $name );
		if( empty( $min )){
			$min = 1;
		}
		if( empty( $max ) ){
			$max = 100;
		}
		if( empty( $step ) ){
			$step = 1;
		}
		if( !is_plugin_active( 'theme-my-login/theme-my-login.php' ) ){
			?>
			<p>
			<label><?php _e( $label, 'user-registration-aide' ); ?>
			<br />
			<input autocomplete="on" type="number" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="input" min="<?php echo $min; ?>" max="<?php echo $max; ?>" step="<?php echo $step; ?>" value="<?php echo $value;?>" style="font-size: 18px; width: 97%;	padding: 3px; margin-right: 6px;" />
			</label>
			</p>
			<?php
		}else{
			?>
			<p>
			<label><?php _e( $label, 'user-registration-aide' ); ?>
			<br />
			<input autocomplete="on" type="number" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="input" min="<?php echo $min; ?>" max="<?php echo $max; ?>" step="<?php echo $step; ?>" value="<?php echo $value;?>" />
			</label>
			</p>
			<?php
		}
		
	}
	
	/**	
	 * function reg_form_url
	 * Registration form url input normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function reg_form_url( $label, $name, $id, $value ){
		$value = esc_url_raw( $value );
		if( !is_plugin_active( 'theme-my-login/theme-my-login.php' ) ){
			?>
			<p>
			<label><?php _e( $label, 'user-registration-aide' ); ?>
			<br />
			<input autocomplete="on" type="url" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="input" value="<?php echo $value;?>" size="25" style="font-size: 20px; width: 97%;	padding: 3px; margin-right: 6px;" />
			</label>
			</p>
			<?php
		}else{
			?>
			<p>
			<label><?php _e( $label, 'user-registration-aide' ); ?>
			<br />
			<input type="url" name="<?php echo $name; ?>" id="<?php echo $id; ?>" autocomplete="on" class="input" value="<?php echo $value;?>" size="25" />
			</label>
			</p>
			<?php
		}
		
	}
	
	/**	
	 * function reg_form_textarea
	 * Registration form textarea box normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function reg_form_textarea( $label, $name, $id, $value ){
		$value = esc_textarea( $value );
		if( !is_plugin_active( 'theme-my-login/theme-my-login.php' ) ){
			?>
			<p>
			<label><?php _e( $label, 'user-registration-aide' ); ?>
			<br />
			<input autocomplete="on" type="textarea" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="input" value="<?php echo $value;?>" size="20" style="font-size: 20px; width: 97%;	padding: 3px; margin-right: 6px;" />
			</label>
			</p>
		<?php
		}else{
			?>
			<p>
			<label><?php _e( $label, 'user-registration-aide' ); ?>
			<br />
			<textarea name="<?php echo $fieldKey; ?>" id="<?php echo $fieldKey; ?>" class="input" value="<?php echo esc_textarea( $value );?>" rows="5" ></textarea>
			</label>
			</p>
			<?php
		}
		
	}
	
	/**	
	 * function reg_form_radio
	 * Registration form radio button normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function reg_form_radio( $label, $name, $id, $value ){
		$field = new FIELDS_DATABASE();
		$options = $field->get_total_field_options( $name );
		
		?>
		<p>
		<label><?php _e( $label, 'user-registration-aide' ); ?></label>
		<br />
		<?php
		if( !empty( $options ) ){
			foreach( $options as $object ){
				$value1 = $object->field_name;
				?>
				<input autocomplete="on" type="radio" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="csds_input" value="<?php echo trim( $value1 );?>" /><?php echo trim( $value1 ); ?>
				<br/>
				<?php
			}
		}else{
			?>
			<?php _e( 'No Options for This Field at This Time!', 'user-registration-aide' ); ?>
			<?php
		}
		?>
		</p>
		<?php
	}
	
	/**	
	 * function reg_form_select
	 * Registration form select button normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function reg_form_select( $label, $name, $id, $value ){
		$field = new FIELDS_DATABASE();
		$options = $field->get_total_field_options( $name );
		?>
		<p>
		<label><?php _e( $label, 'user-registration-aide' ); ?>
		<br />
		<select name="<?php echo $name; ?>" id="<?php echo $id; ?>">
		<option value="" >---</option>
		<?php
		if( !empty( $options ) ){
			foreach( $options as $object ){
				$value1 = $object->field_name;
				if( $value1 == $value ){
					$selected = "selected=\"selected\"";
				}else{
					$selected = NULL;
				}
				/* old option tag
				 * <option value="<?php echo trim( $value1 );?>" ><?php echo trim( $value1 ); ?> </option>
				*/
				?>
				<option value="<?php echo trim( $value1 );?>" <?php echo $selected ;?> ><?php _e( trim( $value1 ), 'user-registration-aide' );?></option>
				<?php
			}
		}else{
			?>
			<option value="no_value"><?php _e( 'No Options for This Field at This Time!', 'user-registration-aide' ); ?></option>
			<?php
		}
		?>
		</select>
		</label>
		</p>
		<?php
	}
		
	/**	
	 * function reg_form_multi_selec
	 * Registration form select box mutli-select
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function reg_form_multi_select( $label, $name, $id, $value ){
		
		$field = new FIELDS_DATABASE();
		$options = $field->get_total_field_options( $name );
		
		?>
		<p>
		<label><?php _e( $label, 'user-registration-aide' ); ?>
		<br />
		<select name="<?php echo $name.'[]'; ?>" id="<?php echo $id; ?>" title="Hold Down CTRL Key to Select Multiple Options!" multiple="multiple">
		<?php
		if( !empty( $options ) ){
			foreach( $options as $object ){
				$value1 = $object->field_name;
				if( $value1 == $value ){
					$selected = "selected=\"selected\"";
				}else{
					$selected = NULL;
				}
				/*
				 * <option value="<?php echo trim( $value1 );?>" ><?php echo trim( $value1 ); ?> </option>
				*/
				?>
				<option value="<?php echo trim( $value1 );?>" <?php echo $selected ;?> ><?php _e( trim( $value1 ), 'user-registration-aide' );?></option>
				
			<?php
			}
		}else{
			?>
			<option value="no_value"><?php _e( 'No Options for This Field at This Time!', 'user-registration-aide' ); ?></option>
			<?php
		}
		?>
		</select>
		</label>
		</p>
		<?php
	}
	
	/**	
	 * function reg_form_checkbox
	 * Registration form checkbox normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function reg_form_checkbox( $label, $name, $id, $value ){
		$field = new FIELDS_DATABASE();
		$options = $field->get_total_field_options( $name );
		?>
		<p>
		<label><?php _e( $label, 'user-registration-aide' ); ?></label>
		<br />
		<?php
		if( !empty( $options ) ){
			foreach( $options as $object ){
				$value1 = $object->field_name;
				$key = $object->meta_key;
				if( $value == $value1 ){
					?>
					<label>
					<input checked="checked" type="checkbox" name="<?php echo $name.'[]'; ?>" id="<?php echo $name; ?>" value="<?php echo $value1; ?>">
					<?php echo $value1; ?>
					</label>
					<br/>
					<?php
					
				}else{
					?>
					<label>
					<input type="checkbox" name="<?php echo $name.'[]'; ?>" id="<?php echo $name; ?>" value="<?php echo $value1; ?>">
					<?php echo $value1; ?>
					</label>
					<br/>
					<?php
				}
			}
		}else{
			_e( 'No Options for This Field at This Time!', 'user-registration-aide' );
		}
		?>
		</p>
		<?php
	}
	
	/**	
	 * function reg_form_datepicker
	 * Registration form datepicker normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function reg_form_datepicker( $label, $name, $id, $value ){
		if( empty( $value ) ){
			$value = time();
		}
		?>
		<div id="ui-datepicker-div">
		<p>
		<label><?php _e( $label, 'user-registration-aide' ); ?>
		<br />
		<input type="date" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="custom_date" value=""/>
		</label>
		</p>
		</div>
		<?php
	}
	
	/**	
	 * function profile_textbox
	 * Profile text box normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params OBJECT $user, string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function profile_textbox( $user, $label, $name, $id, $value ){
		$meta = get_user_meta( $user->ID, $name, true );
		$value = sanitize_text_field( $value );
		?>
		<tr>
		<?php
		if( $name === 'security_answer_1' || $name === 'security_answer_2' || $name === 'security_answer_3' ){
			?>
			<th><label title="<?php _e( 'Field Required!', 'user-registration-aide' ); ?>"><?php _e( $label, 'user-registration-aide' ); ?></label></th>
			<?php
		}else{
			?>
			<th><label><?php _e( $label, 'user-registration-aide' ); ?></label></th>
			<?php
		}
		?>
		<td><input autocomplete="on" type="text" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="regular-text code" value="<?php echo $value;?>" />
		</td>
		</tr>
		<?php
		
	}
	
	/**	
	 * function user_profile_number
	 * Profile number normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params OBJECT $user, string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function user_profile_number( $user, $label, $name, $id, $value ){
		$min = (int) 0;
		$max = (int) 0;
		$step = (int) 0;
		$type = (string) '';
		$field = new FIELDS_DATABASE();
		$type = $field->field_type_finder( $name );
		if( $type == 'number' ){
			$min = $field->min_number( $name );
			$max = $field->max_number( $name );
			$step = $field->step_number( $name );
			$meta = get_user_meta( $user->ID, $name, true );
			if( empty( $min )){
				$min = 1;
			}
			if( empty( $max ) ){
				$max = 100;
			}
			if( empty( $step ) ){
				$step = 1;
			}
			
			
			//exit( $name );
			?>
			<tr>
			<th><label><?php _e( $label, 'user-registration-aide' ); ?></label></th>
			<td><input autocomplete="on" type="number" min="<?php echo $min; ?>" max="<?php echo $max; ?>" step="<?php echo $step; ?>" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="regular-text code" value="<?php echo $value;?>" />
			</td>
			</tr>
			<?php
		}
		
	}
	
	/**	
	 * function profile_url
	 * Profile url normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params OBJECT $user, string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function profile_url( $user, $label, $name, $id, $value ){
		$meta = get_user_meta( $user->ID, $name, true );
		$value = esc_url_raw( $value );
		?>
		<tr>
		<th><label><?php _e( $label, 'user-registration-aide' ); ?></label></th>
		<td><input autocomplete="on" type="url" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="regular-text code" value="<?php echo $value;?>" />
		</td>
		</tr>
		<?php
		
	}
	
	/**	
	 * function profile_textarea
	 * Profile textarea box normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params OBJECT $user, string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function profile_textarea( $user, $label, $name, $id, $value ){
		$meta = get_user_meta( $user->ID, $name, true );
		$value = esc_textarea( $value );
		?>
		<tr>
		<th><label><?php _e( $label, 'user-registration-aide' ); ?></label></th>
		<td><textarea name="<?php echo $name; ?>" id="<?php echo $id; ?>" rows="5" cols="30"><?php echo $value;?></textarea></td>
		</tr>
		<?php
		
	}
	
	/**	
	 * function profile_radio
	 * Profile radio button normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params OBJECT $user, string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function profile_radio( $user, $label, $name, $id, $value ){
		$field = new FIELDS_DATABASE();
		$options = $field->get_total_field_options( $name );
		if( empty( $value ) ){
			$meta = get_user_meta( $user->ID, $name, true );
		}else{
			$meta = $value;
		}
		//echo 'META: '.$meta;
		?>
		<tr>
		<th><label><?php _e( $label, 'user-registration-aide' ); ?></label></th>
		<td>
		<?php
		if( !empty( $options ) ){
			foreach( $options as $object ){
				$value1 = $object->field_name;
			//foreach( $values as $order	=>	$value1 ){
				if( $value1 != $meta ){
					?>
					<input autocomplete="on" type="radio" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="csds_input" value="<?php echo trim( $value1 );?>" /><?php echo trim( $value1 ); ?>
					<br/>
					<?php
				}elseif( $value1 == $meta ){
					?>
					<input autocomplete="on" type="radio" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="csds_input" value="<?php echo trim( $value1 );?>" checked="checked" /><?php echo trim( $value1 ); ?>
					<br/>
					<?php
				}
			}
		}else{
			_e( 'No Options for This Field at This Time!', 'user-registration-aide' );
		}
		?>
		</td>
		</tr>
		<?php
	}
	
	/**	
	 * function profile_select
	 * Profile select box normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params OBJECT $user, string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function profile_select( $user, $label, $name, $id, $value ){
		//global $wpdb;
		$selected = (string) '';
		$user_id = $user->ID;
		$empty_value = (string) '';
		$name = trim( $name );
		if( empty( $value ) ){
			$value = get_user_meta( $user_id, $name, true );
			/*if( empty( $value ) ){
				$table_name = $wpdb->prefix . "usermeta";
				$sql = "SELECT meta_value FROM $table_name WHERE meta_key = %s AND user_id = %d";
				$select = $wpdb->prepare( $sql, $name, $user_id );
				exit( 'SELECT: '.$select );
				$value = $wpdb->get_var( $select );
				exit( 'VALUE: '.$value );
			}*/
			//exit( 'VALUE: '.$value );
			if( $value == null ){
				$value == $empty_value;
			}else{
				$value = trim( $value );
			}
		}else{
			$value = trim( $value );
		}
		
		
		if( $name === 'security_question_1' || $name === 'security_question_2' || $name === 'security_question_3' ){
			$sqm = new SECURITY_QUESTIONS_MODEL();
			$title = (string) '';
			$key = (string) '';
			$options_array = array();
			if( $name === 'security_question_1' ){
				//exit( 'NAME 1: '.$name );
				$options_array = $sqm->security_questions_array_1();
				//exit( print_r( $options_array ) );
			}elseif( $name === 'security_question_2' ){
				//exit( 'NAME 2: '.$name );
				$options_array = $sqm->security_questions_array_2();
			}elseif( $name === 'security_question_3' ){
				//exit( 'NAME 3: '.$name );
				$options_array = $sqm->security_questions_array_3();
			}
			?>
			<tr>
			<th><label title="<?php _e( 'Field Required!', 'user-registration-aide' ); ?>"><?php _e( $label, 'user-registration-aide' ); ?></label></th>
			<td>
			<select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="regular-text code" title="<?php _e( 'Field Required!', 'user-registration-aide' ); ?>">
			<option value="" >---</option>
			<?php
			$value1 = ( string ) '';
			if( !empty( $options_array ) ){
				if( is_array( $options_array ) ){
					foreach( $options_array as $key => $title ){
						$value1 = trim( $key );
						$value = trim( $value );
					//foreach( $values as $order	=>	$value1 ){
						if( $value == $key ){
							$selected = "selected=\"selected\"";
							?>
							<option value="<?php echo $value1;?>" <?php echo $selected;?> ><?php echo trim( $title ); ?> </option>
							<?php
						}else{
							$selected = NULL;
							?>
							<option value="<?php echo $value1;?>" <?php echo $selected;?> ><?php echo trim( $title ); ?> </option>
							<?php
						}
					}
				}
			}
			?>
			</select>
			</td>
			</tr>
			<?php
			
		}else{
			$field = new FIELDS_DATABASE();
			//exit( 'NAME 2: '.$name );
			$options = $field->get_total_field_options( $name );
			?>
			<tr>
			<th><label><?php _e( $label, 'user-registration-aide' ); ?></label></th>
			<td>
			<select name="<?php echo $name; ?>" id="<?php echo $id; ?>">
			<option value="" >---</option>
			<?php
			if( !empty( $options ) ){
				foreach( $options as $object ){
					$value1 = $object->field_name;
				//foreach( $values as $order	=>	$value1 ){
					if( $value == $value1 ){
						$selected = "selected=\"selected\"";
						?>
						<option value="<?php echo $value1;?>" <?php echo $selected;?> ><?php echo trim( $value1 ); ?> </option>
						<?php
					}else{
						$selected = NULL;
						?>
						<option value="<?php echo $value1;?>" <?php echo $selected;?> ><?php echo trim( $value1 ); ?> </option>
						<?php
					}
				}
			}else{
				?>
				<option value="no_options"><?php _e( 'No Options for This Field at This Time!', 'user-registration-aide' ); ?></option>
				<?php
			}
			?>
			</select>
			</td>
			</tr>
			<?php
		}
		
		
	}
	
	/**	
	 * function profile_multi_select
	 * Profile select box mutli-select
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params OBJECT $user, string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function profile_multi_select( $user, $label, $name, $id, $value ){
		$field = new FIELDS_DATABASE();
		$options = $field->get_total_field_options( $name );
		$selected = (string) '';
		$selected = NULL;
		$selections = array();
		
		if( !empty( $value ) ){
			$selections = maybe_unserialize( $value );
		}else{
			$selections = get_user_meta( $user->ID, $name, true );
			$selections = maybe_unserialize( $selections );
		}
		
		?>
		<tr>
		<th><label><?php _e( $label, 'user-registration-aide' ); ?></label></th>
		<td>
		<select name="<?php echo $name.'[]'; ?>" id="<?php echo $id; ?>" multiple="multiple" title="Hold Down CTRL Key to Select Multiple Options!">
		<?php
		if( !empty( $options  ) ){
			foreach( $options as $object ){
			//foreach( $values as $order	=>	$value1 ){
				$value1 = $object->field_name;
				$value1 = trim( $value1 );
				if( is_array( $selections ) ){
					if( !empty( $selections ) ){
						foreach( $selections as $key => $select ){
							if( $value1 == trim( $select ) ){
								$selected = "selected=\"selected\"";
								break;
							}elseif( !in_array( $value1, $selections ) ){
								$selected = NULL;
							}
						}
					}elseif( empty( $selections ) ){
						$selected = NULL;
					}
					echo "<option value=\"$value1\" $selected >$value1</option>";
				}else{
					$selected = NULL;
					echo "<option value=\"$value1\" $selected >$value1</option>";
				}
			}
		}else{
			?>
			<option value="no_options"><?php _e( 'No Options for This Field at This Time!', 'user-registration-aide' ); ?></option>
			<?php
		}
		?>
		</select>
		</td>
		</tr>
		<?php
	}
	
	/**	
	 * function profile_checkbox
	 * Profile checkbox normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params OBJECT $user, string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function profile_checkbox( $user, $label, $name, $id, $value ){
		$meta = get_user_meta( $user->ID, $name, true );
		$field = new FIELDS_DATABASE();
		$options = $field->get_total_field_options( $name );
		
		if( empty( $value ) ){
			$value = trim( $meta );
		}
		$fields = maybe_unserialize( $value );
		?>
		<tr>
		<th><label><?php _e( $label, 'user-registration-aide' ); ?></label></th>
		<td>
		<?php
		if( !empty( $options ) ){
			foreach( $options as $object ){
				$value1 = $object->field_name;
				$key = $object->meta_key;
				if( !is_array( $fields ) ){
					if( $fields == $value1 ){
						?>
						<label>
						<input checked="checked" type="checkbox" name="<?php echo $name.'[]'; ?>" id="<?php echo $name; ?>" value="<?php echo $value1; ?>">
						<?php echo $value1; ?>
						</label>
						<br/>
						<?php
					}else{
						?>
						<label>
						<input type="checkbox" name="<?php echo $name.'[]'; ?>" id="<?php echo $name; ?>" value="<?php echo $value1; ?>">
						<?php echo $value1; ?>
						</label>
						<br/>
						<?php
					}
				}else{
					if( in_array( $value1, $fields ) ){
						?>
						<label>
						<input checked="checked" type="checkbox" name="<?php echo $name.'[]'; ?>" id="<?php echo $name; ?>" value="<?php echo $value1; ?>">
						<?php echo $value1; ?>
						</label>
						<br/>
						<?php
					}else{
						?>
						<label>
						<input type="checkbox" name="<?php echo $name.'[]'; ?>" id="<?php echo $name; ?>" value="<?php echo $value1; ?>">
						<?php echo $value1; ?>
						</label>
						<br/>
						<?php
					}
				}
				
			}
		}else{
			_e( 'No Options for This Field at This Time!', 'user-registration-aide' );
		}
		?>
		</td>
		</tr>
		<?php
	}
	
	/**	
	 * function profile_datepicker
	 * Profile datepicker normal
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params OBJECT $user, string $label for input item label, string $name for input item name
	 * @params string $id for input item id, string $value for input item value
	 * @returns 
	*/
	
	function profile_datepicker( $user, $label, $name, $id, $value ){
		if( empty( $value ) ){
			$meta = get_user_meta( $user->ID, $name, true );
			$value = $meta;
			
		}else{
			$value = $value;
		}
		?>
		
		<tr>
		<div id="ui-datepicker-div">
		<th><label><?php _e( $label, 'user-registration-aide' ); ?></label></th>
		<td>
		<input type="date" name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="custom_date" value="<?php echo $value; ?>"/>
		</td>
		</div>
		</tr>
		<?php
	}
}?>