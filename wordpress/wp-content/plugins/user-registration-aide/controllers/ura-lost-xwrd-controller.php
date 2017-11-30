<?php

/**
 * Class LOST_PASSWORD_CONTROLLER
 *
 * @category Class
 * @since 1.5.3.0
 * @updated 1.5.3.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/


class LOST_PASSWORD_CONTROLLER
{
	
	/**
	 * function update_password_control
	 * Handles password update page input and errors and views
	 * @since 1.5.3.0
	 * @updated 1.5.3.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function lost_xwrd_control(){
		$results = array();
		$results = apply_filters( 'xwrd_lost_model', $results );
		do_action( 'xwrd_lost_view', $results );
	}
}