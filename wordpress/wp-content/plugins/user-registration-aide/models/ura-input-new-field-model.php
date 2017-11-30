<?php

/**
 * Class INPUT_NEW_FIELDS_MODEL
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class INPUT_NEW_FIELDS_MODEL
{
	
	/**	
	 * Function input_options_array
	 * Creates and returns array of html types for new fields field type 
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns array $input array of html input types for use in plugin
	*/
	
	function input_options_array(){
		$input = array(
			'checkbox'			=>	__( 'Checkbox', 'user-registration-aide' ),
			'datebox'        	=>  __( 'Datepicker', 'user-registration-aide' ),
			'hidden'        	=>  __( 'Hidden', 'user-registration-aide' ),
			'multiselectbox' 	=>	__( 'Multi Select', 'user-registration-aide' ),
			'number'			=>	__( 'Number', 'user-registration-aide' ),
			'radio'				=>	__( 'Radio', 'user-registration-aide' ),
			'selectbox'			=>	__( 'Select', 'user-registration-aide' ),
			'textbox'			=>	__( 'Text', 'user-registration-aide' ),
			'textarea'			=>	__( 'Text Area', 'user-registration-aide' ),
			'url'				=>	__( 'URL', 'user-registration-aide' )
		);
		return $input;
		
	}
		
	/**	
	 * Function option_fields_array
	 * Creates and returns array of html types that require option fields
	 * uses add_filter 'get_option_fields_array' on line 509 of user-registration-aide.php
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @params array $options
	 * @returns array $options array of html input types that require additional options fields
	 * @access public
	*/
	
	function option_fields_array( $options ){
		$options = array(
			'multiselectbox' 	=>	__( 'Multi Select', 'user-registration-aide' ),
			'radio'				=>	__( 'Radio', 'user-registration-aide' ),
			'selectbox'			=>	__( 'Select', 'user-registration-aide' ),
			'checkbox'			=>	__( 'Checkbox', 'user-registration-aide' )
		);
		return $options;
		
	}
	
	/**	
	 * Function options_text_inputs
	 * Creates and returns array of text box titles for new fields input field options
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @params
	 * @returns array $texts of titles for field options text boxes
	 * @access public
	*/
	
	function options_text_inputs(){
		$texts = array();
		for( $i = 0; $i <= 15; $i++ ){
			$texts[$i] = '<input  style="width: 180px;" type="text" title="'.__( 'Enter options here for your radio, select, or checkbox options! Just enter like you want them displayed, single words are best. Like the example above or this for Cars, Chevy Chrysler Ford Toyota GM Honda or for Marital Status: Divorced Single Widowed NOTE: DO NOT USE COMMAS in your field options please! It will mess up your whole array of options strings!!', 'user-registration-aide' ) . '" value="" name="ura_newOption'.$i.'" id="ura_newOption'.$i.'" />';
		}
		return $texts;
	}
	
	/**	
	 * Function text_options_array
	 * Creates and returns array of text boxes for new fields input field options
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @params
	 * @returns array $texts for field options
	 * @access public
	*/
		
	function text_options_array(){
		$texts = array();
		for( $i = 0; $i <= 15; $i++ ){
			$texts[$i] = 'ura_newOption'.$i;
		}
		return $texts;
	}
	
	/**	
	 * Function options_keys_array
	 * Creates and returns array of text boxes for new fields input field options keys
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @params
	 * @returns array $texts for field options keys
	 * @access public
	*/
		
	function options_keys_array(){
		$texts = array();
		for( $i = 0; $i <= 15; $i++ ){
			$texts[$i] = '<input  style="width: 180px;" type="text" title="'.__( 'Enter option keys here for your radio, select, or checkbox options! Use lower case letters and underscores( _ ). Like the example above or this for Cars, chevy, honda, or for Marital Status: single, divorced or for a second address use address_1 or for phone area code you could use area_code. NOTE: DO NOT USE COMMAS in your field options please! It will mess up your whole array of options strings!!', 'user-registration-aide' ) . '" value="" name="ura_newOptionKey'.$i.'" id="ura_newOptionKey'.$i.'" maxlength="30" />';
		}
		return $texts;
	}
	
	/**	
	 * Function remove_options_commas
	 * Takes existing new key and strips out commas for proper formatting
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @params string $option
	 * @returns string $option stripped of commas for proper formatting
	 * @access public
	*/
	
	function remove_options_commas( $option ){
		$new_option = str_replace( ',', '', $option );
		return $new_option;
	}
	
	/**	
	 * Function new_fields_input_model
	 * Handles new fields input admin form options and settings
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @params
	 * @returns string $msg ( results of update updated or error  msg )
	 * @access public
	*/
		
	function new_fields_input_model( $msg ){
		global $current_user, $bp;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){
			wp_die( __( 'You do not have permissions to activate this plugin, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$bp_options = ( string ) '';
			$options = get_option( 'csds_userRegAide_Options' );
			$registratrionFields = get_option( 'csds_userRegAide_registrationFields' );
			$knownFields = get_option( 'csds_userRegAide_knownFields' );
			$knownFields_count = count( $knownFields );
			$field_missing = '';
			$number_fields = ( int ) 0;
			$duplicate = ( int ) 0;
			$field_type = ( string ) '';
			$field_options = ( string ) '';
			$nf_error_cnt = ( int ) 0;
			$nf_errors = ( string ) '';
			$field_option = ( string ) '';
			$tfield = array();
			$foptions = array();
			$bp_options = array();
			$result = array();
			$field_key = ( string ) '';
			$option_mk = ( string ) '';
			$action = ( string ) '';
			$new_id = ( int ) 0;
			$db_error = ( int ) 0;
			$min = ( int ) 0;
			$max = ( int ) 0;
			$step = ( int ) 0;
			$option_order = ( int ) 0;
			$plugin = ( string ) 'buddypress/bp-loader.php';
			$actions = new CSDS_URA_ACTIONS();
			$eom = new EDIT_FIELD_OPTIONS_MODEL();
			$csds_userRegMod_fields_missing = ( string ) '';
			
			// do stuff to handle updates
			if( isset( $_POST['new_fields_update'] ) ){
				if( wp_verify_nonce( $_POST['wp_nonce_csds-regFieldsAdmin'], 'csds-regFieldsAdmin' ) ){	
					
					// Field type and field options
					if( empty( $_POST['input_type'] ) || $_POST['input_type'] == '' ){
						$nf_errors = " Field Type ";
						$csds_userRegMod_fields_missing .= ' Field Type ';
						$nf_error_cnt ++;
					}else{
						//exit( 'TYPE: ' );	
						$tfield = $_POST['input_type'];
						if( is_array( $tfield ) ){
							$field_type = $tfield[0];
						}else{
							$field_type = $tfield;
						}
					}
					
					if( !isset( $_POST['reg_form_use'] ) ){
						$registration_field = 0;
					}else{
						//exit( $_POST['reg_form_use'] );
						$registration_field = $_POST['reg_form_use'];
					}
					
				// Checking for blank fields
					if( $_POST['ura_newFieldKey'] == '' && $_POST['ura_newField'] == '' ){
						$seperator = " & ";
						$csds_userRegMod_fields_missing = ' New Field Key ' . $seperator . ' New Field Name ';
						$number_fields = 2;
					}elseif( $_POST['ura_newFieldKey'] == '' ) {
						$csds_userRegMod_fields_missing1 = " New Field Key ";
						$ura_newField = esc_attr( stripslashes( trim( $_POST['ura_newField'] ) ) );
						$csds_userRegMod_fields_missing = $csds_userRegMod_fields_missing1;
						$field_missing = esc_attr( stripslashes( $_POST['ura_newField'] ) );
						$number_fields = 1;
					}elseif( $_POST['ura_newField'] == '' ){
						$csds_userRegMod_fields_missing2 = " New Field Name ";
						$ura_newFieldKey = esc_attr( stripslashes( trim( $_POST['ura_newFieldKey'] ) ) );
						$csds_userRegMod_fields_missing = $csds_userRegMod_fields_missing2;
						$field_missing = esc_attr( stripslashes( $_POST['ura_newFieldKey'] ) );
						$number_fields = 1;
					}elseif( isset( $_POST['ura_newFieldKey'] ) && isset( $_POST['ura_newField'] ) ){
						$dup_key = $this->duplicate_field_key( $_POST['ura_newFieldKey'] );
						$dup_name = $this->duplicate_field_name( $_POST['ura_newField'] );
						if( $dup_key != 0 && $dup_name == 0 ){
							$dup = ( string ) $_POST['ura_newFieldKey'];
							$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate keys, %s key is already included in the extra fields!***', 'user-registration-aide' ), $dup ) .'</p></div>';
							$action = 'error';
							$result = array( $action, $msg );
							return $result;
						}elseif( $dup_key == 0 && $dup_name != 0 ){
							$dup = ( string ) $_POST['ura_newField'];
							$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate field names, %s is already included in the extra fields!***', 'user-registration-aide' ), $dup ) .'</p></div>';
							$action = 'error';
							$result = array( $action, $msg );
							return $result;
						}elseif( $dup_key != 0 && $dup_name != 0 ){
							$dup = ( string ) $_POST['ura_newField'];
							$dup1 = ( string ) $_POST['ura_newFieldKey'];
							$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate key or name, %2$s and %1$s are already included in the extra fields!***', 'user-registration-aide' ), $dup, $dup1 ) .'</p></div>';
							$action = 'error';
							$result = array( $action, $msg );
							return $result;
						}
					}else{
						$csds_userRegMod_fields_missing = '';
					}
									
					// checking number input for errors
					if( $field_type == 'number' ){
						$min = sanitize_text_field( $_POST['ura_minNumb'] );
						$max = sanitize_text_field( $_POST['ura_maxNumb'] );
						$step = sanitize_text_field( $_POST['ura_stepNumb'] );
						$numb_error = ( int ) 0;
						if( $min == 0 && $max == 0 && $step == 0 ){
							$nf_errors = ( string ) __( ' You Have Not Selected Any Number Fields, Please Select a Minimum Number, Maximum Number and Number Step By to Proceed! ', 'user-registration-aide' );
							$numb_error++;	
						}
						if( $max == $min ){
							if( $numb_error == 0 ){
								$nf_errors = ( string ) __( ' Your Maximum Number is Equal to Your Minimum Number ', 'user-registration-aide' );
								$numb_error++;
							}else{
								$nf_errors .= ( string ) __( ' & Your Maximum Number is Equal to Your Minimum Number ', 'user-registration-aide' );
								$numb_error++;
							}
						}elseif( $max < $min ){ 
							if( $numb_error == 0 ){
								$nf_errors = ( string ) __( ' Your Maximum Number is Less Than Your Minimum Number ', 'user-registration-aide' );
								$numb_error++;
							}else{
								$nf_errors .= ( string ) __( ' Your Maximum Number is Less Than Your Minimum Number ', 'user-registration-aide' );
								$numb_error++;
							}
						}
						
						if( $step > $max ){
							if( $numb_error == 0 ){
								$nf_errors = ( string ) __( ' Your Number Step By is Greater Than Your Maximum Number ', 'user-registration-aide' );
								$numb_error++;
							}else{
								$nf_errors .= ( string ) __( ' & Your Number Step By is Greater Than Your Maximum Number ', 'user-registration-aide' );
								$numb_error++;
							}
						}elseif( $step == $max ){
							if( $numb_error == 0 ){
								$nf_errors = ( string ) __( ' Your Number Step By is the Same as Your Maximum Number, Please
								Select a Lower Step Number ', 'user-registration-aide' );
								$numb_error++;
							}else{
								$nf_errors .= ( string ) __( ' & Your Number Step By is the Same as Your Maximum Number, Please Select a Lower Step Number ', 'user-registration-aide' );
								$numb_error++;
							}
						}elseif( $step <= 0 ){
							if( $numb_error == 0 ){
								$nf_errors = ( string ) __( ' Your Number Step By is 0 Please Select a Step Number ', 'user-registration-aide' );
								$numb_error++;
							}else{
								$nf_errors .= ( string ) __( ' & Your Number Step By is 0 Please Select a Step Number ', 'user-registration-aide' );
								$numb_error++;
							}
						}
						
					}else{
						if( !empty( $_POST['ura_minNumb'] ) ){
							$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options NOT updated Successfully. Cannot have Number in Field Type %s', 'user-registration-aide' ), $field_type ) .'</p></div>';
							$_POST['ura_minNumb'] = 0;
							$_POST['ura_maxNumb'] = 0;
							$_POST['ura_stepNumb'] = 0;
							$action = 'error';
							$result = array( $action, $msg );
							return $result;
						}elseif( !empty( $_POST['ura_maxNumb'] ) ){
							$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options NOT updated Successfully. Cannot have Number in Field Type %s', 'user-registration-aide' ), $field_type ) .'</p></div>';
							$_POST['ura_minNumb'] = 0;
							$_POST['ura_maxNumb'] = 0;
							$_POST['ura_stepNumb'] = 0;
							$action = 'error';
							$result = array( $action, $msg );
							return $result;
						}elseif( !empty( $_POST['ura_stepNumb'] ) ){
							$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options NOT updated Successfully. Cannot have Number in Field Type %s', 'user-registration-aide' ), $field_type ) .'</p></div>';
							$_POST['ura_minNumb'] = 0;
							$_POST['ura_maxNumb'] = 0;
							$_POST['ura_stepNumb'] = 0;
							$action = 'error';
							$result = array( $action, $msg );
							return $result;
						}
					}
					
					$text_options = $this->text_options_array();
					$option_types = array();
					$option_types = apply_filters( 'get_option_fields_array', $option_types );
					$nf_error = ( string ) '';
					$nf_error = __( ' You Can Only Add Field Options With Radio Buttons, Select or Multi-Select Options ', 'user-registration-aide' );
					
					// checking field options for errors
					
					foreach( $text_options as $txt_id => $txt_name ){
						if( $txt_id == 0 ){
							
							if( empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'radio' ){
								$nf_errors .=  __( ' Radio Button Field Options  Missing', 'user-registration-aide' );
								$csds_userRegMod_fields_missing .= ' Field Options ';
								$nf_error_cnt ++;
								break;
							}elseif( empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'checkbox' ){
								$nf_errors .=  __( ' CheckBox Field Options Missing', 'user-registration-aide' );
								$csds_userRegMod_fields_missing .= ' Field Options ';
								$nf_error_cnt ++;
								break;
							}elseif( empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'selectbox' ){
								$nf_errors .=  __( ' Select Field Options  Missing', 'user-registration-aide' );
								$csds_userRegMod_fields_missing .= ' Field Options ';
								$nf_error_cnt ++;
								break;
							}elseif( empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'multiselectbox' ){
								$nf_errors .=  __( ' Multi-Select Box Field Options Missing', 'user-registration-aide' );
								$csds_userRegMod_fields_missing .= ' Field Options ';
								$nf_error_cnt ++;
								break;
							}elseif( !empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'radio' ){
								//exit( '!EMPTY & RADIO' );
								$dup_key = $this->duplicate_field_key( $_POST['ura_newOptionKey'.$txt_id] );
								$dup_name = $this->duplicate_field_name( $_POST['ura_newOption'.$txt_id] );
								if( !empty( $dup_key ) ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. Duplicate Field Option Key %s', 'user-registration-aide' ), $dup_key ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}elseif( !empty( $dup_name ) ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. Duplicate Field Option Name %s', 'user-registration-aide' ), $dup_name ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}else{
									$field_option = sanitize_text_field( $_POST['ura_newOption'.$txt_id] );
									$field_option = $this->remove_options_commas( $field_option );
									$bp_options[$txt_id] = sanitize_text_field( $_POST['ura_newOption'.$txt_id] );
									$bp_options[$txt_id] = $this->remove_options_commas( $bp_options[$txt_id] );
									$field_key[$txt_id] = $actions->replace_spaces( $_POST['ura_newOptionKey'.$txt_id] );
									$field_key[$txt_id] = sanitize_key( $field_key[$txt_id] );
								}
							}elseif( !empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'selectbox' ){
								$dup_key = $this->duplicate_field_key( $_POST['ura_newOptionKey'.$txt_id] );
								$dup_name = $this->duplicate_field_name( $_POST['ura_newOption'.$txt_id] );
								if( !empty( $dup_key ) ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. Duplicate Field Option Key %s', 'user-registration-aide' ), $dup_key ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}elseif( !empty( $dup_name ) ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. Duplicate Field Option Name %s', 'user-registration-aide' ), $dup_name ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}else{
									$field_option = sanitize_text_field( $_POST['ura_newOption'.$txt_id] );
									$field_option = $this->remove_options_commas( $field_option );
									$bp_options[$txt_id] = sanitize_text_field( $_POST['ura_newOption'.$txt_id] );
									$bp_options[$txt_id] = $this->remove_options_commas( $bp_options[$txt_id] );
									$field_key[$txt_id] = $actions->replace_spaces( $_POST['ura_newOptionKey'.$txt_id] );
									$field_key[$txt_id] = sanitize_key( $field_key[$txt_id] );
								}
							}elseif( !empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'multiselectbox' ){
								$dup_key = $this->duplicate_field_key( $_POST['ura_newOptionKey'.$txt_id] );
								$dup_name = $this->duplicate_field_name( $_POST['ura_newOption'.$txt_id] );
								if( !empty( $dup_key ) ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. Duplicate Field Option Key %s', 'user-registration-aide' ), $dup_key ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}elseif( !empty( $dup_name ) ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. Duplicate Field Option Name %s', 'user-registration-aide' ), $dup_name ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}else{
									$field_option = sanitize_text_field( $_POST['ura_newOption'.$txt_id] );
									$field_option = $this->remove_options_commas( $field_option );
									$bp_options[$txt_id] = sanitize_text_field( $_POST['ura_newOption'.$txt_id] );
									$bp_options[$txt_id] = $this->remove_options_commas( $bp_options[$txt_id] );
									$field_key[$txt_id] = $actions->replace_spaces( $_POST['ura_newOptionKey'.$txt_id] );
									$field_key[$txt_id] = sanitize_key( $field_key[$txt_id] );
								}
							}elseif( !empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'checkbox' ){
								$field_option = sanitize_text_field( $_POST['ura_newOption'.$txt_id] );
								$field_option = $this->remove_options_commas( $field_option );
								$bp_options[$txt_id] = sanitize_text_field( $_POST['ura_newOption'.$txt_id] );
								$bp_options[$txt_id] = $this->remove_options_commas( $bp_options[$txt_id] );
								$field_key[$txt_id] = $actions->replace_spaces( $_POST['ura_newOptionKey'.$txt_id] );
								$field_key[$txt_id] = sanitize_key( $field_key[$txt_id] );
							}elseif( !empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'datebox' ){
								$nf_errors .= $nf_error;
								$csds_userRegMod_fields_missing .= ' Field Options ';
								$nf_error_cnt ++;
								break;
							}elseif( !empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'textbox' ){
								$nf_errors .= $nf_error;
								$csds_userRegMod_fields_missing .= ' Field Options ';
								$nf_error_cnt ++;
								break;
							}elseif( !empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'textarea' ){
								$nf_errors .= $nf_error;
								$csds_userRegMod_fields_missing .= ' Field Options ';
								$nf_error_cnt ++;
								break;							
							}elseif( !empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'number' ){
								$nf_errors = ( string ) __( ' You Have Selected Options for a Number Field, There can be no Options in a Number Field! ', 'user-registration-aide' );
								$numb_error++;
								$nf_error_cnt ++;
								break;							
							}elseif( !empty( $_POST['ura_newOption'.$txt_id] ) && $field_type == 'url' ){
								$nf_errors .= $nf_error;
								$csds_userRegMod_fields_missing .= ' Field Options ';
								$nf_error_cnt ++;
								break;							
							}
						}elseif( $txt_id >= '1' ){
							if( !empty( $_POST['ura_newOption'.$txt_id] ) ){
								$dup_key = $this->duplicate_field_key( $_POST['ura_newOptionKey'.$txt_id] );
								$dup_name = $this->duplicate_field_name( $_POST['ura_newOption'.$txt_id] );
								if( !empty( $dup_key ) ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. Duplicate Field Option Key %s', 'user-registration-aide' ), $dup_key ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}elseif( !empty( $dup_name ) ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. Duplicate Field Option Name %s', 'user-registration-aide' ), $dup_name ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}else{
									$temp_field = sanitize_text_field( $_POST['ura_newOption'.$txt_id] );
									$temp_field = $this->remove_options_commas( $temp_field );
									$field_option .= ','.$temp_field;
									$bp_options[$txt_id] = $temp_field;
									$field_key[$txt_id] = $actions->replace_spaces( $_POST['ura_newOptionKey'.$txt_id] );
									$field_key[$txt_id] = sanitize_key( $field_key[$txt_id] );
								}
							}
						}
					}
				
					if( $csds_userRegMod_fields_missing != '' && $number_fields == 2 && $duplicate == 0 ){
						$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. Missing both fields for: %s ***', 'user-registration-aide' ), $csds_userRegMod_fields_missing ) .'</p></div>';
						$action = 'error';
						$result = array( $action, $msg );
						return $result;
					}elseif( $csds_userRegMod_fields_missing != '' && $number_fields == 1  && $duplicate == 0 && $nf_error_cnt == 0 ){
						$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. Missing following field for: %1$s: %2$s ***', 'user-registration-aide' ), $field_missing, $csds_userRegMod_fields_missing ) .'</p></div>';
						$action = 'error';
						$result = array( $action, $msg );
						return $result;
					}elseif( $csds_userRegMod_fields_missing != '' && $number_fields == 1  && $duplicate == 1 ){
						$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. Following field already exists: %1$s and you are missing the following field: %2$s***', 'user-registration-aide' ), $field_missing, $csds_userRegMod_fields_missing ) .'</p></div>';
						$action = 'error';
						$result = array( $action, $msg );
						return $result;
					}elseif( !empty( $nf_errors ) || $nf_error_cnt >= 1 ){
						$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Add New Field Options not updated successfully. %s ***', 'user-registration-aide' ), $nf_errors ) .'</p></div>';
						$action = 'error';
						$result = array( $action, $msg );
						return $result;
					}else{
						
						$results = esc_attr( $_POST['ura_newFieldKey'] );
						$results = $actions->replace_spaces( $results );
						$results = sanitize_key( $results );
						$new_field = sanitize_text_field( $_POST['ura_newField'] );
						
						// Checking for duplicate field in new fields
					
						if( !empty( $newFields ) ){
							foreach( $newFields as $key => $field ){
								if( $key != $results && $field != $new_field ){
									$msg = '<div id="message" class="updated"><p>'. sprintf( __( 'Add New Field %s option updated successfully!', 'user-registration-aide' ), $new_field ) .' </p></div>';
								}elseif( $key == $results ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate fields, %s is already included in the extra fields!***', 'user-registration-aide' ), $results ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}elseif( $field == $new_field ){
									$msg = '<div id="message" class="error"><p>'.sprintf( __( '***Cannot add duplicate fields, %s is already included in the extra fields!***', 'user-registration-aide' ), $new_field ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}else{
								 // do nothing yet
								}
							}	
						}else{
						
							// Checking for duplicate fields in known fields
						
							foreach( $knownFields as $key => $field ){
								if( $key != $results && $field != $new_field ){
									$msg = '<div id="message" class="updated"><p>'. sprintf( __( 'Add New Field %s option updated successfully!', 'user-registration-aide'), $new_field ).'</p></div>';
								}elseif( $key == $results ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate fields, %s is already included in the extra fields!***', 'user-registration-aide' ), $results ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}elseif( $field == $new_field ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate fields, %s is already included in the extra fields!***', 'user-registration-aide' ), $new_field ) .'</p></div>';
									$action = 'error';
									$result = array( $action, $msg );
									return $result;
								}else{
								
								}
							}
						}
						
						// Updating arrays and database options
						$key_exists = ( string ) '';
						$option_exists = ( string ) '';
						
						if( current_user_can( 'activate_plugins', $current_user->ID ) ){
							$prf_mdl = new URA_PROFILE_MODEL();//csds_add_field_to_users_meta(
							$prf_mdl->csds_add_field_to_users_meta( $results );
							unset( $prf_mdl );					
							if( is_plugin_active( $plugin ) ){
								if( !empty( $new_field ) && !empty( $field_type ) ){
									$desc = ( string ) '';
									$required = ( boolean ) false;
									$required = sanitize_text_field( $_POST['required'] );
									
									$db_error = ( int ) 0;
									if( $required == 1 || $required == 'true' ){
										$required = 1;
									}else{
										$required = 0;
									}
									$type = ( string ) '';
									
									$type = sanitize_text_field( $_POST['input_type'] );
									$desc = sanitize_text_field( $_POST['ura_newFieldDesc'] );
									$name = $new_field;
									$key = $results;								
									
									$bpf = new URA_BP_FUNCTIONS();
									$bp_id = $bpf->add_bp_field( $type, $name, $desc, $required, $key );
									
									
									// updating bp options if any to update
									$default_option = ( string ) '';
									$order_by = ( string ) 'custom';
									$option_keys = ( string ) '';
									$field = new FIELDS_DATABASE();
									$count = $field->fields_count();
									$key_exists = $field->meta_key_exists( $key );
									if( !empty( $bp_options ) ){
										foreach( $bp_options as $index => $options ){
											$meta_key = $field_key[$index];
											$option_exists .= $field->meta_key_exists( $meta_key );
											if( !empty( $type ) ){
												if( $type == 'checkbox' || $type == 'radio' ){
													$name_exists = '';
													//$name_exists = $field->field_name_exists( $options );
												}else{
													//$name_exists = '';
													$name_exists = $field->field_name_exists( $options );
												}
											}
											if( !empty( $option_exists ) ){
												if( $index == 0 ){
													$option_keys = $meta_key;
												}else{
													$option_keys .= ' - '.$meta_key;
												}
											}
											if( !empty( $name_exists ) ){
												if( $index == 0 ){
													$name_exists = $meta_key;
												}else{
													$name_exists .= ' - '.$meta_key;
												}
											}
										}
									}
									if( !empty( $key_exists ) ){
										$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate Meta Keys, Meta Key %s is already being used in the extra fields!***', 'user-registration-aide' ), $new_field ) .'</p></div>';
										$db_error++;
										$action = 'error';
										$result = array( $action, $msg );
										//return $result;
									}elseif( !empty( $option_keys ) ){
										$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate Option Meta Keys, Meta Key %s is already being used in the extra fields!***', 'user-registration-aide' ), $option_keys ) .'</p></div>';
										$db_error++;
										$action = 'error';
										$result = array( $action, $msg );
										//return $result;
									}elseif( !empty( $name_exists ) ){
										$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate Option Names, Option Name %s is already being used in the extra fields!***', 'user-registration-aide' ), $name_exists ) .'</p></div>';
										$db_error++;
										$action = 'error';
										$result = array( $action, $msg );
										//return $result;
									}
										
										//unset( $field );
									
									if( $db_error == 0 ){
										$bp_order = ( int ) 0;
										$field->meta_key = $key;
										$field->option_meta_key = '';
										$field->parent_id = '0';
										$field->data_type = $type;
										$field->field_name = $name;
										$field->field_description = $desc;
										$field->field_required = $required;
										$field->registration_field = $registration_field;
										$field->is_default_option = '0';
										$field->field_order = $count;
										$field->approve_view = '0';
										$field->option_order = '0';
										$field->min_number = $min;
										$field->max_number = $max;
										$field->number_step = $step;
										$field->bp_id = $bp_id;
										$field->bp_parent_id = '0';
										$field->bp_group_id = '1';
										$field->insert_fields( $field );
										
										if( !empty( $bp_options ) ){
											$parent_id = $field->meta_key_exists( $key );
											$option_order = ( int ) 1;
											foreach( $bp_options as $index => $fields ){
												if( $index == 0 ){
													$default_option = true;
												}else{
													$default_option = false;
												}
												
												$order = ( int ) 0;
												$order = $index + 1;
												$bp_order = $order;
												$bp__option_id = $bpf->add_bp_field_option( $bp_id, $fields, $default_option, $index, $order_by, $key );
												$meta_key = $field_key[$index];
												$option_mk = $parent_id.'_'.$meta_key;
												$ometa_key = $parent_id.'_'.$order;
												$key_exists = $field->meta_key_exists( $ometa_key );
												$omk_exists = $eom->duplicate_option_keys( $parent_id, $option_mk );
												if( empty( $omk_exists ) && empty( $key_exists ) ){
													$field->meta_key = $ometa_key;
													$field->option_meta_key = $option_mk;
													$field->parent_id = $parent_id;
													$field->data_type = 'option';
													$field->field_name = $fields;
													$field->field_description = $desc;
													$field->field_required = 'false';
													$field->is_default_option = $default_option;
													$field->field_order = '0';
													$field->approve_view = '0';
													$field->option_order = $option_order;
													$field->bp_id = $bp__option_id;
													$field->bp_parent_id = $bp_id;
													$field->bp_group_id = '1';
													$field->insert_fields( $field );
												}else{
													$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate Meta Keys, Meta Key %s is already being used in the extra fields!***', 'user-registration-aide' ), $meta_key ) .'</p></div>';
													$db_error++;
													$action = 'error';
													$result = array( $action, $msg );
													//return $result;
												}
												$option_order++;
											}
										}
									}
								}
							}else{
								$field = new FIELDS_DATABASE();
								$count = $field->fields_count();
								$key_exists = $field->meta_key_exists( $key );
								$type = sanitize_text_field( $_POST['input_type'] );
								$name = $new_field;
								$key = $results;
								$bp_id = ( int ) 0;
								$bp__option_id = ( int ) 0;
								$desc = ( string ) '';
								$required = sanitize_text_field( $_POST['required'] );
								if( !empty( $key_exists ) ){
									$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate Meta Keys, Meta Key %s is already being used in the extra fields!***', 'user-registration-aide' ), $key ) .'</p></div>';
									$db_error++;
									$action = 'error';
									$result = array( $action, $msg );
									//return $result;
								}else{
									$field->meta_key = $key;
									$field->option_meta_key = '';
									$field->parent_id = '0';
									$field->data_type = $type;
									$field->field_name = $name;
									$field->field_description = $desc;
									$field->field_required = $required;
									$field->registration_field = $registration_field;
									$field->is_default_option = '0';
									$field->field_order = $count;
									$field->approve_view = '0';
									$field->option_order = '0';
									$field->min_number = $min;
									$field->max_number = $max;
									$field->number_step = $step;
									$field->bp_id = $bp_id;
									$field->bp_parent_id = '0';
									$field->bp_group_id = '1';
									$field->insert_fields( $field );
									
									if( !empty( $bp_options ) ){
										$parent_id = $field->meta_key_exists( $key );
										
										foreach( $bp_options as $index => $fields ){
											if( $index == 0 ){
												$default_option = 'true';
											}else{
												$default_option = 'false';
											}
											$order = ( int ) 0;
											$order = $index + 1;
											$meta_key = $field_key[$index];
											$option_mk = $parent_id.'_'.$meta_key;
											$ometa_key = $parent_id.'_'.$order;
											$key_exists = $field->meta_key_exists( $ometa_key );
											$omk_exists = $eom->duplicate_option_keys( $parent_id, $option_mk );
											if( empty( $omk_exists ) && empty( $key_exists ) ){
												$field->meta_key = $ometa_key;
												$field->option_meta_key = $option_mk;
												$field->parent_id = $parent_id;
												$field->data_type = 'option';
												$field->field_name = $fields;
												$field->field_description = $desc;
												$field->field_required = 'false';
												$field->registration_field = 'false';
												$field->is_default_option = '0';
												$field->field_order = '0';
												$field->option_order = $order;
												$field->bp_id = $bp__option_id;
												$field->bp_parent_id = $bp_id;
												$field->bp_group_id = '1';
												$field->insert_fields( $field );
											}else{
												$msg = '<div id="message" class="error"><p>'. sprintf( __( '***Cannot add duplicate Meta Keys, Meta Key %s is already being used in the extra fields!***', 'user-registration-aide' ), $meta_key ) .'</p></div>';
												$db_error++;
												$action = 'error';
												$result = array( $action, $msg );
												//return $result;
											}
										}
									}
								}
							}
						}else{
							wp_die( '<div id="message" class="error"><p>'. __( 'You do not have adequate permissions to edit this plugin! Please check with Administrator to get additional permissions.', 'user-registration-aide' ) .'</p></div>' );
						}
					}
					if( $action != 'error' ){
						$action = 'updated';
					}
					$result = array( $action, $msg );
					//wp_die( print_r( $result ) );
					return $result;
				}
			}
		}		
	}
	
	/**	
	 * Function duplicate_field_key
	 * Handles checking for duplicates for new field keys before writing to database to eliminate database errors
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $key ( new field key )
	 * @returns int $id ( results of checking for duplicates 0 for false 1 for true )
	*/
	
	function duplicate_field_key( $key ){
		$ura_field = new FIELDS_DATABASE();
		$known_fields = get_option( 'csds_userRegAide_knownFields' );
		$id = $ura_field->meta_key_exists( $key );
		if( $id == 0 ){
			foreach( $known_fields as $key1 => $value ){
				if( $key1 == $key ){
					$id = 1;
					return $id;
					break;
				}elseif( $key == $value ){
					$id = 1;
					return $id;
					break;
				}else{
					$id = 0;
				} // end if
			} // end foreach
		}
		return $id;
		
	} // end function
	
	/**	
	 * Function duplicate_field_name
	 * Handles checking for duplicates for new field names before writing to database to eliminate database errors
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @params string $name ( new field title or name )
	 * @returns int $id ( results of checking for duplicates 0 for false 1 for true )
	 * @access public
	 * @author Brian Novotny
	 * @website http://creative-software-design-solutions.com
	*/
	
	function duplicate_field_name( $name ){
		$ura_field = new FIELDS_DATABASE();
		$known_fields = get_option( 'csds_userRegAide_knownFields' );
		$id = $ura_field->field_name_exists( $name );
		if( $id == 0 ){
			foreach( $known_fields as $key1 => $value ){
				if( $key1 == $name ){
					$id = 1;
					return $id;
					break;
				}elseif( $name == $value ){
					$id = 1;
					return $id;
					break;
				}else{
					$id = 0;
				} // end if
			} // end foreach
		}
		return $id;
		
	} // end function
	
} // end class