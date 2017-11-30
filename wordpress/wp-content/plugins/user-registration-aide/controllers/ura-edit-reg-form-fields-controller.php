<?php

/**
 * Class URA_EDIT_REGISTRATION_FIELDS_CONTROLLER
 *
 * @category Class
 * @since 1.5.2.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_EDIT_REGISTRATION_FIELDS_CONTROLLER
{
	
	/** 
	 * function update_registration_fields_settings_controller
	 * Updates selected fields for dashboard widget on URA admin page
	 * @since 1.5.1.4
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns 
	*/
	
	function update_registration_fields_settings_controller(){
		do_action( 'reg_form_fields_view' );
	}
} // end class