<?php

/**
 * Class SECURITY_QUESTIONS_CONTROLLER
 *
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class SECURITY_QUESTIONS_CONTROLLER
{
	
	public static $instance;
	
	var $q1 = 'security_question_1';
	var $q2 = 'security_question_2';
	var $q3 = 'security_question_3';
	var $a1 = 'security_answer_1';
	var $a2 = 'security_answer_2';
	var $a3 = 'security_answer_3';
	
	public function __construct() {
		$this->SECURITY_QUESTIONS_CONTROLLER();
	}
	
	function SECURITY_QUESTIONS_CONTROLLER() { //constructor
		
		self::$instance = $this;
		
	}
	
	/**	
	 * function sec_questions_settings_controller
	 * Controller for security question settings
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @return
	*/
	
	function sec_questions_settings_controller() {
		global $current_user;
		$current_user = wp_get_current_user();
		$results = array();
		if( !current_user_can( 'manage_options', $current_user->ID ) ){	
			wp_die( __( 'You do not have permissions to modify this plugins settings, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			do_action( 'sq_settings_view' );
		}
	}
	
	
	/**	
	 * function sec_questions_rf_controller
	 * Controller for security question settings
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @return
	*/
	
	function sec_questions_rf_controller() {
		$results = array();
		$results = apply_filters( 'sq_rf_model', $results );
		do_action( 'sq_rf_view', $results );
	}
	
	
	/**	
	 * function sec_questions_profile_controller
	 * Controller for security question settings
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params 
	 * @return
	*/
	
	function sec_questions_profile_controller() {
		global $current_user;
		$current_user = wp_get_current_user();
		$results = array();
		if( !current_user_can( 'edit_users', $current_user->ID ) ){	
			wp_die( __( 'You do not have permissions to edit this user, sorry, check with site administrator to resolve this issue please!', 'user-registration-aide' ) );
		}else{
			$results = apply_filters( 'sq_profile_model', $results );
			do_action( 'sq_profiles_view', $results );
		}
	}
	
}