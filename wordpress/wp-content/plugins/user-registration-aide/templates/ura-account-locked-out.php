<?php 
// page template for user account locked out
if( isset( $_GET['release'] ) ){
	$release = $_GET['release'];
	$release = rtrim( $release, '/' );
	//exit( 'RELEASE: '.$release );
}
//exit( 'MY ACCOUNT LOCKED OUT' );
?>
<div id="account-locked-out">

	<div class="page" id="locked-out">
		
		<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			
			<h2>USER ACCOUNT TEMPORARILY LOCKED OUT:</h2>
		 
			<div class="template-error"><?php _e( 'Your account has been locked for too many failed login attempts!', 'user-registration-aide' ); ?></div>
			<?php if( !empty( $release ) ){ ?>
				<div class="template-message"><?php _e( 'Your account will be unlocked in '.$release.' minutes', 'user-registration-aide' ); ?></div>
			<?php
			}else{
				?>
				<div class="template-message"><?php _e( 'Your account will be unlocked in less than 30 minutes', 'user-registration-aide' ); ?></div>
				<?php
			}
			?>
			
			</form>
			

	</div><!-- .page -->

	</div>
	</div>
</div>