<?php

/**
 * Class CSDS_URA_ACTIONS
 *
 * @category Class
 * @since 1.4.0.0
 * @updated 1.5.2.0
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class CSDS_URA_ACTIONS
{
	
	/** 
	 * function display_messages
	 * Creates Fields database table
	 * @since 1.5.1.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg, string $class
	 * @returns  
	*/
	
	function display_messages( $msg, $class ){
		$msg1 = '<div class="'.$class.'" style="visibility: hidden"><p></p></div>';
		$msg_wrap = '<div id="message" class="'.$class.'" style="margin-bottom: -25px;"><p>';
		$msg_end = '</p></div>';
		$msg_comp = $msg1.$msg_wrap.$msg.$msg_end;
		echo $msg_comp;
	}
	
	
		
	/** 
	 * function start_wp_wrapper
	 * One action for adding admin page wrappers
	 * @since 1.4.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $tab, array $form, array $h2, array $span, array $nonce
	 * @returns
	*/
	
	function start_wp_wrapper( $msg, $msg1, $tab, $form, $nonce ){
		?>
		<div class="wrap">
		<?php
		if( !empty( $msg ) && empty( $msg1 ) ){
			echo $msg;
		}elseif( !empty( $msg ) && !empty( $msg1 ) ){
			echo $msg;
		}elseif( empty( $msg ) && !empty( $msg1 ) ){
			echo $msg1;
		}
		do_action( 'create_tabs', $tab ); 
		
		
		?>
			<form method="<?php echo $form[0]; ?>" name="<?php echo $form[1]; ?>" id="<?php echo $form[1]; ?>">
				<div class="inside">
				<?php
				wp_nonce_field( $nonce[0], $nonce[1] );
	}
	
	/** 
	 * function start_wp_msg_wrapper
	 * One action for adding admin page wrappers
	 * @since 1.4.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $msg, string $msg1, string $tab, array $form, array $h2, array $span, array $nonce
	 * @returns
	*/
	
	function start_wp_msg_wrapper( $msg, $msg1, $tab, $form, $nonce ){
		//do_action( 'create_msg_tabs', $tab, $msg ); // Line 255 user-registration-aide.php
		//<div id="wpbody">
		echo $msg;
		do_action( 'create_tabs', $tab ); 
		?>
		<div class="wrap">
		
		<?php
		//do_action( 'create_msg_tabs', $tab, $msg, $msg1 ); // Line 255 user-registration-aide.php
		?>
		
		<form method="<?php echo $form[0]; ?>" name="<?php echo $form[1]; ?>" id="<?php echo $form[1]; ?>">
			<div class="inside">
			<?php
			wp_nonce_field( $nonce[0], $nonce[1] );
	}
		
	/** 
	 * function end_wp_wrapper
	 * One action for ending admin page wrappers
	 * @since 1.4.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params
	 * @returns
	*/
	
	function end_wp_wrapper(){
		?>
		<br/>
		<?php
		do_action('show_support');
		?>
		<div class="clear"></div></div> <?php // poststuff ?>
		</form>
		<div class="clear"></div></div> <?php // wrap ?>
		<?php
		
	}
	
	/** 
	 * function start_mini_wp_wrapper
	 * One action for adding admin page mini wrappers
	 * @since 1.4.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params array $span
	 * @returns
	*/
	
	function start_mini_wp_wrapper( $span ){
		/*?>
		<div class="stuffbox">
			<span class="<?php echo $span[0]; ?>"><?php _e( $span[1], $span[2] );?></span>
					<div class="inside">
		<?php*/
	}
	
	/** 
	 * function end_mini_wp_wrapper
	 * One action for ending admin page mini wrappers
	 * @since 1.4.0.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params 
	 * @returns
	*/
	
	function end_mini_wp_wrapper(){
		?>
			<div class="clear"></div></div> <?php // stuffbox ?>
		<div class="clear"></div></div> <?php // inside ?>
		<?php
	}
	
	/** 
	 * function ura_approve_user
	 * Admin approval of user
	 * @since 1.5.1.1
	 * @updated 1.5.2.0
	 * @access public
	 * @uses new_user_approve_approve_user
	 * @params int $user_id
	 * @returns
	*/
	
	public function ura_approve_user( $user_id ) {
		global $wpdb, $wp_hasher;
		
		$user = new WP_User( $user_id );
		$options = get_option('csds_userRegAide_Options');
		$user = new WP_User( $user_id );
		$fields = get_option('csds_userRegAide_registrationFields');
		$login_url = (string) '';
		$url = ( string ) '';
		$page = $options['xwrd_change_name'];
		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );
		$xwrd = ( string ) 'User Entered';
		$msg = ( string ) '';
		$message = ( string ) '';
		$blogname = get_option( 'blogname' );
		
		if( $options['xwrd_change_on_signup'] == 1 ){
			$url = site_url();
			$login_url = $url.'/'.$page.'/?action=new-register';
		}elseif( $options['xwrd_change_on_signup'] == 2 ){
			$login_url = wp_login_url() . "\r\n";
		}
		
		wp_cache_delete( $user->ID, 'users' );
		wp_cache_delete( $user->data->user_login, 'userlogins' );

		
		
		// format the message
		//$message = nua_default_approve_user_message();

		/*
		$message = nua_do_email_tags( $message, array(
			'context' => 'approve_user',
			'user' => $user,
			'user_login' => $user_login,
			'user_email' => $user_email,
		) );
		*/
		
		// send email to user telling of approval
		$message = sprintf( __( 'You have been approved to access %s', 'user-registration-aide' ), $blogname ) . "\r\n\r\n";
		if( in_array( 'Password', $fields ) ){
				$message .= sprintf( __( 'Username: %s', 'user-registration-aide' ), $user_login ) . "\r\n";
				$message .= sprintf( __( 'Password: %s', 'user-registration-aide' ), $xwrd ) . "\r\n";
				$message .= sprintf( __( 'Login URL: %s', 'user-registration-aide' ), $login_url ) ."\r\n\r\n";
		}elseif( !in_array( 'Password', $fields ) ){
			// Generate something random for a password reset key.
			$key = wp_generate_password( 20, false );

			/** This action is documented in wp-login.php */
			do_action( 'retrieve_password_key', $user->user_login, $key );

			// Now insert the key, hashed, into the DB.
			if ( empty( $wp_hasher ) ) {
				require_once ABSPATH . WPINC . '/class-phpass.php';
				$wp_hasher = new PasswordHash( 8, true );
			}
			
			$hashed = time() . ':' . $wp_hasher->HashPassword( $key );
			$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
			$message .= sprintf( __( 'Username: %s', 'user-registration-aide' ), $user->user_login ) . "\r\n\r\n";
			$url = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ) . "\r\n\r\n";
			$message .= sprintf( __( 'To set your password and validate your new user account, visit the following address: %s', 'user-registration-aide'  ), $url );
		}
				 
		$message = apply_filters( 'new_user_approve_approve_user_message', $message, $user );
		$subject = sprintf( __( '[%s] Registration Approved', 'user-registration-aide' ), get_option( 'blogname' ) );
		$subject = apply_filters( 'new_user_approve_approve_user_subject', $subject );

		// send the mail
		$pw_nua = pw_new_user_approve::instance();
		wp_mail( $user_email, $subject, $message, $pw_nua->email_message_headers() );
		unset( $pw_nua );
		// change usermeta tag in database to approved
		update_user_meta( $user->ID, 'pw_user_status', 'approved' );

		do_action( 'new_user_approve_user_approved', $user );
	}
	
	/** 
	 * function ura_register_message
	 * Message for New User Approve to top of Registration form after users successfully registers
	 * @since 1.5.1.1
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $message
	 * @returns string $message
	*/
	
	function ura_register_message( $message ){
		$options = get_option('csds_userRegAide_Options');
		$msg = $options['nua_pre_register_msg'];
		$message =  sprintf( __( '%s', 'user-registration-aide' ), $msg );
		return $message;
		//}
	}
	
	/** 
	 * function ura_success_register_message
	 * Message for New User Approve to top of Registration form after users successfully registers
	 * @since 1.5.1.1
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $messages
	 * @returns string $message
	*/
	
	function ura_success_register_message( $messages ) {
		$options = get_option('csds_userRegAide_Options');
		$msg_1 = $options['nua_post_register_msg_1'];
		$msg_2 = $options['nua_post_register_msg_2'];
		$message =  sprintf( __( '%s', 'user-registration-aide' ), $msg_1 );
		$message .= ' <br/>';
		$message .=  sprintf( __( '%s', 'user-registration-aide' ), $msg_2 );

		//$message = apply_filters( 'new_user_approve_pending_message_default', $message );

		return $message;
	}
	
	/** 
	 * function replace_spaces
	 * Fixes key names for novices
	 * @since 1.5.2.0
	 * @updated 1.5.2.0
	 * @access public
	 * @params string $old
	 * @returns string $new
	*/
	
	function replace_spaces( $old ){
		$new = ( string ) '';
		$new = preg_replace( '/\s+/', '_', $old );
		return $new;
	}
} // end class