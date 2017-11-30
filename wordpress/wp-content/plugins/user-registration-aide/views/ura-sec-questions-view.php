<?php

/**
 * Class SECURITY_QUESTIONS_VIEW
 *
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class SECURITY_QUESTIONS_VIEW
{
	
	public static $instance;
	
	public function __construct() {
		$this->SECURITY_QUESTIONS_VIEW();
	}
	
	function SECURITY_QUESTIONS_VIEW() { //constructor
		
		self::$instance = $this;
		
	}
	
	/**
	 * function security_questions_settings_table
	 * View for security question settings
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function sec_questions_settings_view() {
		global $current_user;
		$current_user = wp_get_current_user();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){	
			wp_die( __( 'You do not have permissions to modify this plugins settings, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$options = get_option( 'csds_userRegAide_Options' );
		}
	
		?>
		<table class="regForm" width="100%">
			<tr>
				<th colspan="2">
				<?php _e( 'Lost Password Security Questions Options: ', 'user-registration-aide' );?>
				</th>
			</tr>
			<tr> <?php // Security Question Change Options ?>
				<td width="50%"><?php _e( 'Require Security Questions for Lost Password Reset?: ', 'user-registration-aide' );?>
				<span title="<?php _e( 'Select this option to require users to answer security question before resetting lost password', 'user-registration-aide' );?>">
				<input type="radio" name="security_question" id="security_question" value="1" <?php
				if ( $options['add_security_question'] == 1 ) echo 'checked' ;?> /> <?php _e( 'Yes', 'user-registration-aide' );?></span>
				<span title="<?php _e( 'Select this option not to answer security question before resetting lost password',  'user-registration-aide' );?>">
				<input type="radio" name="security_question" id="security_question" value="2" <?php
				if ( $options['add_security_question'] == 2 ) echo 'checked' ;?> /> <?php _e( 'No', 'user-registration-aide' ); ?></span>
				</td>
			</tr>
			<tr>
				<td>
				<div class="submit">
				<input type="submit" class="button-primary" name="sec_question_options" id="sec_question_options" value="<?php _e( 'Update Security Question Options', 'user-registration-aide' );?>" />
				</div>
			</tr>
		</table>
		<?php
	}
	
	/**
	 * function sec_questions_rf_view
	 * View for security question on registration form
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function sec_questions_rf_view(){
		$sqm = new SECURITY_QUESTIONS_MODEL();
		$question_1 = $sqm->security_questions_array_1();
		$question_2 = $sqm->security_questions_array_2();
		$question_3 = $sqm->security_questions_array_3();
		$label_1 = 'Security Question 1: ';
		$label_2 = 'Security Question 2: ';
		$label_3 = 'Security Question 3: ';
		$label_11 = 'Security Question Answer 1: ';
		$label_22 = 'Security Question Answer 2: ';
		$label_33 = 'Security Question Answer 3: ';
		$a1_value = ( string ) '';
		$a2_value = ( string ) '';
		$a3_value = ( string ) '';
		$name = ( string ) '';
		$id = ( string ) '';
		$label = ( string ) '';
		$a_label = ( string ) '';
		$value1 = ( string ) '';
		$a_value = ( string ) '';
		for( $index = 1; $index <= 3; $index++ ){
			if( $index == 1 ){
				$question = $question_1;
				$label = $label_1;
				$a_label = $label_11;
				$a_value = $a1_value;
				$name = 'security_question_1';
				$id = 'security_question_1';
				$a_name = 'security_answer_1';
				$a_id = 'security_answer_1';
			}elseif( $index == 2 ){
				$question = $question_2;
				$label = $label_2;
				$a_label = $label_22;
				$a_value = $a2_value;
				$name = 'security_question_2';
				$id = 'security_question_2';
				$a_name = 'security_answer_2';
				$a_id = 'security_answer_2';
			}elseif( $index == 3 ){
				$question = $question_3;
				$label = $label_3;
				$a_label = $label_33;
				$a_value = $a3_value;
				$name = 'security_question_3';
				$id = 'security_question_3';
				$a_name = 'security_answer_3';
				$a_id = 'security_answer_3';
			}
			?>
			<p>
			<label><?php _e( $label.'*:', 'user-registration-aide' ); ?></label>
			<br />
			<select name="<?php echo $name; ?>" id="<?php echo $id; ?>" style="font-size: 20px; width: 97%;	padding: 3px; margin-right: 6px;">
			<option value="" >---</option>
			<?php
			foreach( $question as $key => $value ){
				$value1 = $value;
				?>
				<option value="<?php echo trim( $key );?>" ><?php echo trim( $value1 ); ?> </option>
				
				<?php
			}
			?>
			</select>
			</p>
			<p>
			<label><?php _e( $a_label.'*:', 'user-registration-aide' ); ?><br />
			<input autocomplete="on" type="text" name="<?php echo $a_name; ?>" id="<?php echo $a_id; ?>" class="input" value="<?php echo $a_value;?>" size="25" style="font-size: 20px; width: 97%;	padding: 3px; margin-right: 6px;" />
			</label>
			</p>
			<?php
		}
	}
	
	
	/**
	 * function sec_questions_rf_view
	 * View for security question on registration form
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function sec_questions_lpf_view( $user ){
		$sqm = new SECURITY_QUESTIONS_MODEL();
		$question_1 = $sqm->security_questions_array_1();
		$question_2 = $sqm->security_questions_array_2();
		$question_3 = $sqm->security_questions_array_3();
		
		$question = ( string ) '';
		
		$name = ( string ) '';
		$id = ( string ) '';
		$label = ( string ) '';
		
		$sq = ( string ) '';
		$sa = ( string ) '';
		
		$user_id = $user->ID;
		$sq = array();
		$single = true;
		$sq[0] = get_user_meta( $user_id, 'security_question_1', $single );
		$sq[1] = get_user_meta( $user_id, 'security_question_2', $single );
		$sq[2] = get_user_meta( $user_id, 'security_question_3', $single );
			
		if( !empty( $sq[0] ) && !empty( $sq[1] ) && !empty( $sq[2] ) ){
			//wp_die( print_r( $sq ) );
			?>
			<tr>
			<th colspan="2" style="text-align:center;">
			<?php _e( 'Select and answer one of your security questions to complete the Lost Password process!', 'user-registration-aide' );?>
			</th>
			</tr>
			<tr>
			<td>
			<label><?php _e( 'Lost Password Security Question', 'user-registration-aide' ); ?></label>
			</td>
			<td>
			<select name="lp_security_question" id="lp_security_question" class="regular-text code" title="<?php _e( 'Select one of the following security questions and answer it successfully to complete the Lost Password process!', 'user-registration-aide' );?>" >
			<option value="-1" >---</option>
			<?php
			foreach( $sq as $index => $value ){
				switch( $index ){
					case 0:
						$question = $question_1[$value];
						break;
					case 1:
						$question = $question_2[$value];
						break;
					case 2:
						$question = $question_3[$value];
						break;
					default:
						$question = $question_1[$value];
						break;
				}
				?>
				<option value="<?php echo $index;?>" ><?php echo trim( $question ); ?> </option>
				<?php
			}
			?>
			</select>
			</td>
			</tr>
			<tr>
			<td>
			<label><?php _e( 'Lost Password Security Answer', 'user-registration-aide' ); ?></label>
			</td>
			<td>
			<input autocomplete="on" type="text" name="lp_security_answer" id="lp_security_answer" class="regular-text code" title="<?php _e( 'Successfully answer the security question you chose to complete the Lost Password process!', 'user-registration-aide' );?>" value="" />
			</td>
			</tr>
			<?php
		}else{
			?>
			<tr>
			<th colspan="2" style="text-align:center;">
			<?php _e( 'Please Fill in Your Security Questions After Resetting Your Password', 'user-registration-aide' );?>
			</th>
			</tr>
			<?php
		}
	}
	
	/**
	 * function sec_questions_profile_view
	 * View for security question on user profile
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params WP_User OBJECT $user
	 * @returns
	*/
	
	function sec_questions_profile_view( $user ){
		$sqm = new SECURITY_QUESTIONS_MODEL();
		$question_1 = $sqm->security_questions_array_1();
		$question_2 = $sqm->security_questions_array_2();
		$question_3 = $sqm->security_questions_array_3();
		$label_1 = 'Security Question 1* ';
		$label_2 = 'Security Question 2* ';
		$label_3 = 'Security Question 3* ';
		$label_11 = 'Security Question 1 Answer* ';
		$label_22 = 'Security Question 2 Answer* ';
		$label_33 = 'Security Question 3 Answer* ';
		$a1_value = ( string ) '';
		$a2_value = ( string ) '';
		$a3_value = ( string ) '';
		$name = ( string ) '';
		$id = ( string ) '';
		$label = ( string ) '';
		$a_label = ( string ) '';
		$value1 = ( string ) '';
		$a_value = ( string ) '';
		
		$sq = ( string ) '';
		$sa = ( string ) '';
		
		$user_id = $user->ID;
				
		for( $index = 1; $index <= 3; $index++ ){
			if( $index == 1 ){
				$question = $question_1;
				$label = $label_1;
				$a_label = $label_11;
				$a_value = $a1_value;
				$name = 'security_question_1';
				$id = 'security_question_1';
				$pid = 'Security Question 1';
				$a_name = 'security_answer_1';
				$a_id = 'security_answer_1';
				$pa_id = 'Security Answer 1';
				$single = true;
				$sq = get_user_meta( $user_id, $name, $single );
				$sa = get_user_meta( $user_id, $a_name, $single );
				//exit( $user_id.' - -'.$sq.' - - '.$sa );
			}elseif( $index == 2 ){
				$question = $question_2;
				$label = $label_2;
				$a_label = $label_22;
				$a_value = $a2_value;
				$name = 'security_question_2';
				$id = 'security_question_2';
				$pid = 'Security Question 2';
				$a_name = 'security_answer_2';
				$a_id = 'security_answer_2';
				$pa_id = 'Security Answer 2';
				$single = true;
				$sq = get_user_meta( $user_id, $name, $single );
				$sa = get_user_meta( $user_id, $a_name, $single );
			}elseif( $index == 3 ){
				$question = $question_3;
				$label = $label_3;
				$a_label = $label_33;
				$a_value = $a3_value;
				$name = 'security_question_3';
				$id = 'security_question_3';
				$pid = 'Security Question 3';
				$a_name = 'security_answer_3';
				$a_id = 'security_answer_3';
				$pa_id = 'Security Answer 3';
				$single = true;
				$sq = get_user_meta( $user_id, $name, $single );
				$sa = get_user_meta( $user_id, $a_name, $single );
			}
			//echo $user_id;
			if( $sq == null ){
				do_action( 'profile_select', $user, $label, $name, $id, $sq );
				do_action( 'profile_textbox', $user, $a_label, $a_name, $id, $sa );
			}else{
				do_action( 'profile_select', $user, $label, $name, $id, $sq );
				do_action( 'profile_textbox', $user, $a_label, $a_name, $id, $sa );
			}
		}
	}
	
	// nothing yet
	
	function security_questions_lost_password(){
		
	}
}?>