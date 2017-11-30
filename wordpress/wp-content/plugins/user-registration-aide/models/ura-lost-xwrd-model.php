<?php

/**
 * Class LOST_PASSWORD_MODEL
 *
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class LOST_PASSWORD_MODEL
{
	public $security_question = '';
	public $sq_use = '';
	public $stage = '';
	public $security_answer = '';
	
	/**
	 * function lost_password_actions_array
	 * creates and returns an array of actions for use in lost password form to eliminate multiple if statements
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $actions
	*/
	
	function lost_password_actions_array( $actions ){
		$actions = array(
			'error'							=>	'Error',
			'lost_xwrd'						=>	'Lost Password',
			'security_question'				=>	'Security Question',
			'invalid_sq_answer'				=>	'Invalid Security Question Answer',
			'add_security_questions'		=>	'Add Security Questions'
		);
		return $actions;
	}
			
	/**
	 * function lost_xwrd_url
	 * Handles password update page input and errors
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $lostpassword_url, string $redirect
	 * @returns string $lostpassword_url
	*/
	
	function lost_xwrd_url( $lostpassword_url, $redirect ){
		$options = get_option('csds_userRegAide_Options');
		$change = $options['xwrd_require_change'];
		$site = site_url();
		//$lost_xwrd_page = ( string ) '/wp-login.php?action=lostpassword';
		$lost_xwrd_page = ( string ) '/ura-lost-password?action=lost_xwrd';
		if( $change == 2 ){
			return $lostpassword_url;
		}else{
			return $site.$lost_xwrd_page;
		}
	}
	
	/**
	 * function update_xwrd_model
	 * Handles password update page input and errors
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $results of messages and errors for form to display on reload
	*/
	
	function lost_xwrd_model( $results ){
		global $wpdb, $wp_hasher;
		$errors = new WP_Error();
		
		$line = ( string ) '';
		$stage = ( string ) '';
		$nonce = wp_nonce_field(  'csds-lost_passChange', 'wp_nonce_csds-lost_passChange' );
		$action = ( string ) '';
		$sq = ( string ) '';
		$sa = ( string ) '';
		$question = ( string ) '';
		$answer = ( string ) '';
		$err = ( int ) 0;
		$user_data = new WP_User();
		$email_results = ( boolean ) false;
		$lp_email = ( int ) 0;
		if( isset( $_GET['action'] ) ){
			$action = $_GET['action'];
		}
		$options = get_option( 'csds_userRegAide_Options' );
		if( $options['add_security_question'] == 1 ){
			$this->sq_use = true;
		}
		if( isset( $_POST['lost_xwrd_get'] ) ){
			$stage = 'stage_2';
			if( empty( $_POST['lxwrd_user_login'] ) ) {
				$line = __( '<strong>ERROR</strong>: Enter a Valid Username <br/>or Email Address.', 'user-registration-aide' );
				$err++;
			}elseif( strpos( $_POST['lxwrd_user_login'], '@' ) ) {
				$user_data = get_user_by( 'email', trim( $_POST['lxwrd_user_login'] ) );
				if ( empty( $user_data ) ){
					$line = __( '<strong>ERROR</strong>: There is no user Registered <br/>With That Email Address.', 'user-registration-aide' );
					$err++;
				}
			}else{
				$login = trim( $_POST['lxwrd_user_login'] );
				$user_data = get_user_by( 'login', $login );
			}
						
			if( !empty( $user_data ) ){
				$lp_email = get_user_meta( $user_data->ID, 'lp_email', true );
			}
			
			if ( empty( $user_data ) ){
				$line = __( '<strong>ERROR</strong>: Invalid Username or Email.', 'user-registration-aide' );
				$err++;
				$action = 'invalid_input';
				$m_results = array( $line, $nonce, $action, $err, $user_data, $stage );
				return $m_results;
			}
			
			if( $this->sq_use == true ){
				if( isset( $_POST['lp_security_question'] ) && !empty( $_POST['lp_security_answer'] ) ){
					$question = $_POST['lp_security_question'];
					$answer = $_POST['lp_security_answer'];
					switch( $question ){
						case -1:
							$line =  __( '<strong>ERROR</strong>: No Security Question Selected!', 'user-registration-aide' );
							$err++;
							$action = 'invalid_input';
							$m_results = array( $line, $nonce, $action, $err, $user_data, $stage );
							return $m_results;
							break;
						case 0:
							$sq = 'security_answer_1';
							break;
						case 1:
							$sq = 'security_answer_2';
							break;
						case 2:
							$sq = 'security_answer_3';
							break;
						default:
							$sq = 'security_answer_1';
							break;
					}
					$sa = get_user_meta( $user_data->ID, $sq, true );
					if( $answer != $sa ){
						$line = __( '<strong>ERROR</strong>: Security Question Answer Incorrect! <br/>Please try Again!', 'user-registration-aide' );
						$err++;
						$action = 'invalid_sq_answer';
						$m_results = array( $line, $nonce, $action, $err, $user_data, $stage );
						return $m_results;
					}else{
						$action = 'verified_security_answer';
					}
				}elseif( isset( $_POST['lp_security_question'] ) && empty( $_POST['lp_security_answer'] ) ){
					$question = $_POST['lp_security_question'];
					if( $question == -1 ){
						$line =  __( '<strong>ERROR</strong>: Security Question and Security Answer Empty! <br/>Please try Again!', 'user-registration-aide' );
					}else{
						$line =  __( '<strong>ERROR</strong>: Security Question Answer Empty! <br/>Please try Again!', 'user-registration-aide' );
					}
					$err++;
					$action = 'invalid_sq_answer';
					$m_results = array( $line, $nonce, $action, $err, $user_data, $stage );
					return $m_results;
				}elseif( !isset( $_POST['lp_security_question'] ) && !isset( $_POST['lp_security_answer'] ) ){
					if( $action == 'lost_xwrd' ){
						$action = 'security_question';
						$m_results = array( $line, $nonce, $action, $err, $user_data, $stage );
						return $m_results;
					}else{
						$action = 'empty_security_fields';
						$m_results = array( $line, $nonce, $action, $err, $user_data, $stage );
						return $m_results;
					}
				}
			}
			
			if( $err <= 0 ){
				$email_results = apply_filters( 'xwrd_lost_email', $user_data );
				if( $email_results == true ){
					$lp_email++;
					update_user_meta( $user_data->ID, 'lp_email', $lp_email );
					$line = sprintf( __( 'An email has been sent to %s account with instructions on how to reset your lost password!', 'user-registration-aide' ), $user_data->user_login );
					if( $action == 'empty_security_fields' ){
						$line .= '<br/>';
						$line .= __( '<b>Please Fill in Your Security Questions in Your User Profile After You Have Reset Your Lost Password!</b>', 'user-registration-aide' );
					}
					$action = 'email_success';
				}else{
					$line = sprintf( __( 'There was an error attempting to send a password reset email to %s account with instructions on how to reset your lost password, please try again or contact an administrator and ask for help!', 'user-registration-aide' ), $user_data->user_login );
					if( $action == 'empty_security_fields' ){
						$line .= '<br/>';
						$line .= __( '<b>Please Fill in Your Security Questions in Your User Profile After You Have Reset Your Lost Password!</b>', 'user-registration-aide' );
					}
					$action = 'email_error';
				}
				$m_results = array( $line, $nonce, $action, $err, $user_data, $stage );
				return $m_results;
			}
		}else{
			$stage = 'stage_1';
		}
		$m_results = array( $line, $nonce, $action, $err, $user_data, $stage );
		return $m_results;
		
	}
	
	/**
	 * function  lost_xwrd_reset_email_meta
	 * Updates lp_email meta data in user meta table on lost password reset
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params WP_User OBJECT $user, string $new_pass
	 * @returns 
	*/
		
	function lost_xwrd_reset_email_meta( $user, $new_pass ){
		update_user_meta( $user->ID, 'lp_email', 0 );
		return;
	}
		
}