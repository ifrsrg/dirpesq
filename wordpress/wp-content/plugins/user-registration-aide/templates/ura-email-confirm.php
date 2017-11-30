<?php 

// Email confirmation page for email verification
$ma = new URA_MEMBERS_ACTIONS();
$msg = ( string ) '';
$user_email = ( string ) '';
$login = ( string ) '';
if( isset( $_POST['activate_key'] ) ){
	$key = $_POST['key'];
	$msg = $ma->verify_email_account( $key );
}

if( isset( $_GET['action'] ) && $_GET['action'] == 'verify-email' ){
	if( isset( $_GET['key'] ) ){
		$key = $_GET['key']; 
		$key = rtrim( $key, '/' );
	}else{
		
	}
	if( isset( $_GET['user_email'] ) ){
		$user_email = $_GET['user_email'];
		$user_email = rtrim( $user_email, '/' );
	}
	if( !empty( $key ) ){
		$msg = $ma->verify_email_account( $key );
	}
	
}

?>
<div class="ura-email-confirm" id="email-confirm">

	<div class="page" id="activate-page">
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<?php
		if( $msg === null ){
			$new_line = '%0D%0A';
			$email = get_option( 'admin_email' );
			
			if( !empty( $user_email ) ){
				$user = get_user_by( 'email', $user_email );
			}
			if( !empty( $user ) ){
				$login = $user->user_login;
				$id = $user->ID;
				$verified = get_user_meta( $id, 'email_verification', true );
			}
			$blogname = get_option( 'blogname' );
			$msg = sprintf( __( 'Please provide a valid activation key below or <br/>Contact an Administrator <a href="mailto:%1s?subject=Email Activation Key&body=My Email Activation Key for Your Site %2s Is Not Working, Could you Please Send Me Another Valid Email Activation Key to This Email Address Please? %3s KEY: %4s %5s EMAIL: %6s %7s LOGIN: %8s "><b>Here</b></a>', 'user-registration-aide' ), $email, $blogname, $new_line, $key, $new_line, $user_email, $new_line, $login );
			$theme = wp_get_theme();
			if( empty( $verified ) || $verified == 'unverified' ){
		?>
			
				<h2 style="text-align:center; background-color: #D9A2A2;"><?php _e( 'EMAIL CONFIRMATION FAILED PAGE:', 'user-registration-aide' ); ?></h2>
			 
				<div style="text-align:center; background-color: #D9A2A2;"><?php _e( 'Could not validate key!', 'user-registration-aide' ); ?></div>
				<div style="text-align:center; background-color: #D9A2A2;"><?php echo $msg; ?>

				<form action="" method="get" class="standard-form" id="activation-form">
				
				<label for="key"><?php _e( 'Activation Key:', 'user-registration-aide' ); ?></label>
				<input style="width: 200px;" type="text" name="key" id="key" value="" />

				<p class="submit">
				<input type="submit" name="activate_key" value="<?php esc_attr_e( 'Activate', 'user-registration-aide' ); ?>" />
				</p>

				</form>
				</div>
				
				<?php
			}elseif( !empty( $verified ) && $verified = 'verified' ){
				?>
				<h2 style="text-align:center; background-color: #D9A2A2;"><?php _e( 'EMAIL CONFIRMATION PAGE:', 'user-registration-aide' ); ?></h2>
			 
				<div style="text-align:center; background-color: #D9A2A2;"><?php _e( 'This Email has already been confirmed!!', 'user-registration-aide' ); ?></div>
				<?php
			}
		}else{ 
			?>
			<h2 style="text-align:center;"><?php _e( 'EMAIL CONFIRMATION PAGE:', 'user-registration-aide' ); ?></h2>
			<div style="text-align:center;">
			<?php echo $msg; ?>
			</div>
			<?php	
		} 
		
		?>
		<div style="text-align:center;"><a href="<?php echo site_url(); ?>">Home</a></div>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
	</div><!-- .page -->

</div>