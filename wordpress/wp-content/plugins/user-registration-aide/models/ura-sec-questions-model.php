<?php

/**
 * Class SECURITY_QUESTIONS_MODEL
 *
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class SECURITY_QUESTIONS_MODEL
{
	
	public static $instance;
	
	var $q1 = 'security_question_1';
	var $q2 = 'security_question_2';
	var $q3 = 'security_question_3';
	var $a1 = 'security_answer_1';
	var $a2 = 'security_answer_2';
	var $a3 = 'security_answer_3';
	
	public function __construct() {
		$this->SECURITY_QUESTIONS_MODEL();
	}
	
	function SECURITY_QUESTIONS_MODEL() { //constructor
		
		self::$instance = $this;
		
	}
	
	/**
	 * function security_question_model(
	 * Updates Security Questions Settings
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $msg1
	 * @returns string $msg
	*/
	
	function sec_questions_settings_model( $msg1 ){
		$options = get_option( 'csds_userRegAide_Options' );
		$msg = ( string ) '';
		$result = ( boolean ) false;
		if( isset( $_POST['sec_question_options'] ) ){
			$options['add_security_question'] = $_POST['security_question'];
			$result = update_option( 'csds_userRegAide_Options', $options );
			if( $result == true ){
				$msg = '<div id="message" class="updated"><p>'. __( 'Security Questions Settings Successfully Updated!', 'user-registration-aide' ).'</p></div>'; 
				return $msg;
			}else{
				$msg = '<div id="message" class="error"><p>'. __( 'Security Questions Settings Could Not Be Updated! Please Try Again!', 'user-registration-aide' ).'</p></div>'; 
				return $msg;
			}
		}else{
			return $msg;
		}
	}
	
	/**
	 * function security_questions_array_1
	 * Security Questions array 1
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $questions
	*/
	
	function security_questions_array_1(){
		$questions = array(
			'sq-1'	=>	__( "What was the house number and street name you lived in as a child?", 'user-registration-aide' ),
			'sq-2'	=>	__( "What were the last four digits of your childhood telephone number?", 'user-registration-aide' ),
			'sq-3'	=>	__( "What primary school did you attend?", 'user-registration-aide' ),
			'sq-4'	=>	__( "In what town or city was your first full time job?", 'user-registration-aide' ),
			'sq-5'	=>	__( "In what town or city did you meet your spouse/partner?", 'user-registration-aide' ),
			'sq-6'	=>	__( "What is the middle name of your oldest child?", 'user-registration-aide' ),
			'sq-7'	=>	__( "What are the last five digits of your driver's licence number?", 'user-registration-aide' ),
			'sq-8'	=>	__( "What is your grandmother's (on your mother's side) maiden name?", 'user-registration-aide' ),
			'sq-9'	=>	__( "What is your spouse or partner's mother's maiden name?", 'user-registration-aide' ),
			'sq-10'	=>	__( "In what town or city did your mother and father meet?", 'user-registration-aide' ),
			'sq-11'	=>	__( "What is the name of your first school?", 'user-registration-aide' )
		);
		return $questions;
	}
	
	/**
	 * function security_questions_array_2
	 * Security Questions array 2
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $questions
	*/
	
	function security_questions_array_2(){
		$questions = array(
			'sq-12'	=>	__( "What time of the day were you born? (hh:mm)", 'user-registration-aide' ),
			'sq-13'	=>	__( "What time of the day was your first child born? (hh:mm)", 'user-registration-aide' ),
			'sq-14'	=>	__( "What is the first name of the person you first kissed?", 'user-registration-aide' ),
			'sq-15'	=>	__( "What is the last name of the teacher who gave you your first failing grade?", 'user-registration-aide' ),
			'sq-16'	=>	__( "What is the name of the place your wedding reception was held?", 'user-registration-aide' ),
			'sq-17'	=>	__( "What was the last name of your third grade teacher?", 'user-registration-aide' ),
			'sq-18'	=>	__( "What was the name of the boy/girl you had your second kiss with?",	'user-registration-aide' ),
			'sq-19'	=>	__( "Where were you when you had your first alcoholic drink (or cigarette)?", 'user-registration-aide' ),
			'sq-20'	=>	__( "What was the name of your second dog/cat/goldfish/etc?", 'user-registration-aide' ),
			'sq-21'	=>	__( "Where were you when you had your first kiss?", 'user-registration-aide' ),
			'sq-22'	=>	__( "What street did you grow up on?", 'user-registration-aide' )
		);
		return $questions;
	}
	
	/**
	 * function security_questions_array_3
	 * Security Questions array 3
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $questions
	*/
	
	function security_questions_array_3(){
		$questions = array(
			'sq-23'	=>	__( "When you were young, what did you want to be when you grew up?", 'user-registration-aide' ),
			'sq-24'	=>	__( "Where were you when you first heard about 9/11?", 'user-registration-aide' ),
			'sq-25'	=>	__( "Where were you New Year's 2000?", 'user-registration-aide' ),
			'sq-26'	=>	__( "What's John's (or other friend/family member) middle name?", 'user-registration-aide' ),
			'sq-27'	=>	__( "Who was your childhood hero?", 'user-registration-aide' ),
			'sq-28'	=>	__( "What is the first name of the person who has the middle name of Herbert?", 'user-registration-aide' ),
			'sq-29'	=>	__( "Which phone number do you remember most from your childhood?", 'user-registration-aide' ),
			'sq-30'	=>	__( "What was your favourite place to visit as a child?", 'user-registration-aide' ),
			'sq-31'	=>	__( "What was the make of your first car?", 'user-registration-aide' ),
			'sq-32'	=>	__( "What is your father's middle name?", 'user-registration-aide' ),
			'sq-33'	=>	__( "What is the name of your first grade teacher?", 'user-registration-aide' )
		);
		return $questions;
	}
	
	/**
	 * function security_questions_array_3
	 * Creates array of questions for user profile page updates
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $questions
	*/
	
	function questions_array(){
		$questions = array(
			$this->q1,
			$this->q2,
			$this->q3
		);
		return $questions;
		
	}
	
	/**
	 * function answers_array
	 * Creates array of answers for user profile page updates
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns array $answers
	*/
	
	function answers_array(){
		$answers = array(
			$this->a1,
			$this->a2,
			$this->a3
		);
		return $answers;
		
	}
	
	/**
	 * function missing_security_answers_alert
	 * Determines if user has properly completed security questions and answers
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params int $user_id
	 * @returns array $sq_results boolean $sq_result true if security questions are correctly done, false if not 
	 * string $sa_results string of security questions and answers not completed
	*/
	
	function missing_security_answers_alert( $user_id ){
		$answers = $this->answers_array();
		$questions = $this->questions_array();
		$cnt = ( int ) 0;
		$tcnt = ( int ) 0;
		$result = ( boolean ) false;
		$results = ( string ) '';
		$sa_results = ( string ) '';
		$sq_result = ( boolean ) false;
		foreach( $answers as $index => $answer ){
			$result = get_user_meta( $user_id, $answer, true );
			if( empty( $result ) ){
				if( $cnt == 0 ){
					$results .= $answer;
				}else{
					$results .= ' - - '. $answer;
				}
				$cnt++;
			}
		}
		
		$tcnt = $cnt;
		
		foreach( $questions as $index => $question ){
			$result = get_user_meta( $user_id, $question, true );
			if( empty( $result ) ){
				if( $cnt == 0 ){
					$results .= $question;
				}else{
					$results .= ' - - '. $question;
				}
				$cnt++;
			}
		}
		
		$tcnt = $cnt;
		
		if( $tcnt >= 1 ){
			$sq_results = array( true, $results );
		}else{
			$sq_results = array( false, '' );
		}
		return $sq_results;
	}
	
	/**
	 * function show_security_questions_user_alert
	 * Shows user msg alert is user has not properly completed security questions and answers
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params string $msg1
	 * @returns 
	*/
	
	function show_security_questions_user_alert( $msg1 ){
		global $current_user;
		$user_id = $current_user->ID;
		$link = '<a href="'.admin_url( '/profile.php' ) .'"><b>'. __( 'HERE', 'user-registration-aide' ) .'</b></a>';
		$turn_off =  __( ' - - TURN OFF THIS NOTICE <a href="?ura-sq-alert-msg">HERE!</a>', 'user-registration-aide' );
		$msg = ( string ) sprintf( __( ' You must fill out the security questions %1s to complete your user profile! The following Security Question Fields are Empty!: - ', 'user-registration-aide' ), $link );
		$pre_msg = '<div id="message" class="notice notice-error"><p><strong>';
		$post_msg = '</strong></p></div>';
		if ( !get_user_meta( $user_id, 'ura_sq_alert_notice_ignore' ) ) {
			echo $pre_msg . $msg . $msg1 . $turn_off . $post_msg;
		}
	}	
	
	/**
	 * function user_sq_alert_msg_ignore
	 * Turns of security question alert if questions aren't completed
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function user_sq_alert_msg_ignore(){
		global $current_user;
	
		$user_id = $current_user->ID;
	
		if ( isset( $_GET['ura-sq-alert-msg'])) {
			
			add_user_meta( $user_id, 'ura_sq_alert_notice_ignore', 'true', true );
			
		}
	}
	
	/**
	 * function user_sq_alert_msg
	 * Determines if user has properly completed security questions and answers
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params int $user_id
	 * @returns array $sq_results boolean $sq_result true if security questions are correctly done, false if not 
	 * string $sa_results string of security questions and answers not completed
	*/
	
	function user_sq_alert_msg(){
		global $current_user;
		$current_user = wp_get_current_user();
		
		$options = get_option( 'csds_userRegAide_Options' );
		$sq = $options['add_security_question'];
		if( $sq == 1 ){
			$msgs = array();
			$result = ( boolean ) false;
			$msg = ( string ) '';
			
			if ( $current_user->ID == 0 ) {
				// Not logged in.
			} else {
				$results = apply_filters( 'security_questions_completed', $current_user->ID );
			}
			if( !empty( $results ) ){
				$result = $results[0];
				$msg = $results[1];
			}
			if( $result == true || $result == 1 ){
				do_action( 'show_security_question_alert', $msg );
			}
		}
	}
}?>