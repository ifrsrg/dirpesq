<?php

/**
 * Class URA_TEMPLATE_MODEL
 *
 * @category Class
 * @since 1.5.3.8
 * @updated 1.5.3.8
 * @access public
 * @author Brian Novotny
 * @website http://creative-software-design-solutions.com
*/

class URA_TEMPLATE_MODEL
{
	
	/**
	 * function template_model_page_header
	 * Handles displaying top for my templates 
	 * @since 1.5.3.8
	 * @updated 1.5.3.8
	 * @access public
	 * @params
	 * @returns array $results of messages and errors for form to display on reload
	*/
	
	function template_model_page_header( $results ){
		$fields = ( string ) '';
		$theme = wp_get_theme();
		//wp_die( 'THEME: '.$theme );
		switch( $theme ){
			case 'Accelerate':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					?>
					<div id="primary">
						<div id="content" class="clearfix">
					<?php
				}
				break;
			case 'Athena':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					<div id="primary" class="content-area">
						<main id="main" class="site-main athena-page" role="main">
							<div class="row">
    
							<?php get_sidebar('left'); ?>
    
								<div class="col-sm-<?php echo athena_main_width(); ?>">
									<div class="entry-content">

					<?php
				}
				break;
			case 'ColorMag':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					<?php do_action( 'colormag_before_body_content' ); ?>

					<div id="primary">
						<div id="content" class="clearfix">
						<?php
				}
				break;
			case 'Customizr':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					do_action( '__before_main_wrapper' ); ##hook of the header with get_header ?>
					<div id="main-wrapper" class="<?php echo implode(' ', apply_filters( 'tc_main_wrapper_classes' , array('container') ) ) ?>">

						<?php do_action( '__before_main_container' ); ##hook of the featured page (priority 10) and breadcrumb (priority 20)...and whatever you need! ?>

						<div class="container" role="main">
							<div class="<?php echo implode(' ', apply_filters( 'tc_column_content_wrapper_classes' , array('row' ,'column-content-wrapper') ) ) ?>">

								<?php do_action( '__before_article_container' ); ##hook of left sidebar?>

									<div id="content" class="<?php echo implode(' ', apply_filters( 'tc_article_container_class' , array( CZR_utils::czr_fn_get_layout(  CZR_utils::czr_fn_id() , 'class' ) , 'article-container' ) ) ) ?>">

										<?php do_action( '__before_loop' );##hooks the header of the list of post : archive, search...
				}
				break;
			case 'Eleganto':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					get_template_part( 'template-part', 'topnav' );

					get_template_part( 'template-part', 'head' );

					if ( get_theme_mod( 'breadcrumbs-check', 1 ) != 0 ) {
						eleganto_breadcrumb();
					}?>
					<!-- start content container -->
					<div class="row container rsrc-content">    
						<?php //left sidebar ?>    
						<?php get_sidebar( 'left' ); ?>    
						<article class="col-md-<?php eleganto_main_content_width(); ?> rsrc-main">      
						<div <?php post_class( 'rsrc-post-content' ); ?>>                            
					<header>                              
						<h1 class="entry-title page-header">
							<?php the_title(); ?>
						</h1> 
						<time class="posted-on published" datetime="<?php the_time( 'Y-m-d' ); ?>"></time>                                                        
					</header>                            
					<div class="entry-content">                              
					<?php
				}
				break;
			case 'evolve':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					
					?>
					<div id="primary" class="<?php evolve_layout_class($type = 1); ?>">
						<div class="entry-content article">
					<?php
				}
				break;
			case 'Hueman':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					<section class="content">

						<?php hu_get_template_part('parts/page-title'); ?>

						<div class="pad group">
							<article <?php post_class('group'); ?>>

							<?php hu_get_template_part('parts/page-image'); ?>

								<div class="entry themeform">
								<?php
				}
				break;
			case 'Responsive':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					<div id="content" class="<?php echo esc_attr( implode( ' ', responsive_get_content_classes() ) ); ?>" role="main">
					<?php responsive_entry_before(); ?>
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php responsive_entry_top(); ?>
					<?php
				}
				break;
			case 'Spacious':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					do_action( 'spacious_before_body_content' ); ?>
					<div id="primary">
						<div id="content" class="clearfix">
						<?php
				}
				break;
			case 'Sydney':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					<div id="primary" class="content-area col-md-9">
						<main id="main" class="post-wrap" role="main">
						<?php
				}
				break;
			case 'Twenty Fifteen':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					<div id="primary" class="content-area">
						<main id="main" class="site-main" role="main">
						<?php
				}
				break;
			case 'Twenty Seventeen':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					<div class="clear"></div>
					<div class="wrap">
						<div id="primary" class="content-area">
							<main id="main" class="site-main" role="main">
								<table id="2017_ura_template_table" class="ura_template_table">
									<tr>
										<td valign="top" id="2017_ura_template_primary" class="2017_ura_main_tab">
											<?php
				}
				break;
			case 'Twenty Sixteen':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					<div id="primary" class="content-area">
						<main id="main" class="site-main" role="main">
						<?php
				}
				break;
			case 'Vantage':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					<div id="primary" class="content-area">
						<div id="content" class="site-content" role="main">
					<?php
				}
				break;
			case 'Virtue':
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					<table class="virtue_template_table">
					<tr>
					<td class="virtue_primary_td">
					<?php
				}
				break;
			default:
				$fields = apply_filters( 'page_template_header_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					?>
					<div id="ura_page" class="container">
						<div id="primary" class="content-area">
							<table id="ura_template_table" class="ura_template_table">
								<tr>
									<td id="ura_template_primary" class="ura_main_tab">
										<?php
				}
				break;
		}
	}
		
	/**
	 * function template_model_page_sidebar
	 * Handles displaying sidebar for my templates 
	 * @since 1.5.3.8
	 * @updated 1.5.3.8
	 * @access public
	 * @params
	 * @returns array $results of messages and errors for form to display on reload
	*/
	
	function template_model_page_sidebar( $results ){
		$theme = wp_get_theme();
		$fields = ( string ) '';
		switch( $theme ){
			case 'Accelerate':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
						</div><!-- #content -->
					</div><!-- #primary -->
					<?php accelerate_sidebar_select();
				}
				break;
			case 'Athena':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
								</div><!-- .entry-content -->
							<footer class="entry-footer"></footer><!-- .entry-footer -->
							</article><!-- #post-## -->
						</div>

						<?php get_sidebar(); ?>
					</div>
					<?php
				}
				break;
			case 'ColorMag':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
							</div><!-- #content -->
						</div><!-- #primary -->

					<?php colormag_sidebar_select(); 
				}
				break;
			case 'Customizr':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
								do_action( '__after_loop' );##hook of the comments and the posts navigation with priorities 10 and 20 ?>

								</div><!--.article-container -->

						   <?php do_action( '__after_article_container' ); ##hook of left sidebar ?>

						</div><!--.row -->
					</div><!-- .container role: main -->

					<?php do_action( '__after_main_container' );

				}
				break;
			case 'Eleganto':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
										</div>                               
									                
									</div>        
								      
							
						</article>    
						<?php //get the right sidebar ?>    
						<?php get_sidebar( 'right' ); ?>
					</div>
					<!-- end content container -->
					<?php
					get_template_part( 'template-part', 'footernav' );
				}
				break;
			case 'evolve':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
							<div class="clearfix"></div>
						</div><!--END .entry-content .article-->
					</div> <!--END .hentry-->
					
					<?php
					get_sidebar();
				}
				break;
			case 'Hueman':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
									<div class="clear"></div>
								</div><!--/.entry-->

							</article>
						</div><!--/.pad-->
	
					</section><!--/.content-->
					<?php
					get_sidebar();
				}
				break;
			case 'Responsive':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					responsive_entry_bottom(); ?>
					</div><!-- end of #post-<?php the_ID(); ?> -->
					<?php responsive_entry_after(); ?>
					</div><!-- end of #content -->
					<?php get_sidebar();
				}
				break;
			case 'Spacious':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
						</div><!-- #content -->
					</div><!-- #primary -->
					<?php 
					spacious_sidebar_select(); 
					do_action( 'spacious_after_body_content' );
				}
				break;
			case 'Sydney':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
						</main><!-- #main -->
					</div><!-- #primary -->
					<?php get_sidebar();
				}
				break;
			case 'Twenty Fifteen':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
						</main><!-- .site-main -->
					</div><!-- .content-area -->
					<?php
				}
				break;
			case 'Twenty Seventeen':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					<div class="clear"></div>
					</td>
					<td id="2017_ura_template_sidebar" class="2017_ura_sidebar_tab">
						<?php 
						
						get_sidebar();
						
						?>
					</td>
					<?php
				}
				break;
			case 'Twenty Sixteen':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
						</main><!-- .site-main -->
						<?php get_sidebar( 'content-bottom' ); ?>
					</div><!-- .content-area -->
					<?php get_sidebar();
				}
				break;
			case 'Vantage':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
						</div><!-- #content .site-content -->
					</div><!-- #primary .content-area -->
					<?php get_sidebar();
				}
				break;
			case 'Virtue':
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					</td>
					<td class="virtue_sidebar_td">
					<?php
					dynamic_sidebar( kadence_sidebar_id() );
				}
				break;
			default:
				$fields = apply_filters( 'page_template_sidebar_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_sidebar' ){
							get_sidebar();
						}else{
							echo $value;
						}
					}
				}else{
					?>
					</td>
					<td id="ura_template_sidebar" class="ura_sidebar_tab">
					<?php 
					get_sidebar();
					?>
					</td>
					<?php
				}
				break;
		}
	}
	
	/**
	 * function template_model_page_footer
	 * Handles displaying closing tags for my templates 
	 * @since 1.5.3.8
	 * @updated 1.5.3.8
	 * @access public
	 * @params
	 * @returns array $results of messages and errors for form to display on reload
	*/
	
	function template_model_page_footer( $results ){
		$fields = ( string ) '';
		$theme = wp_get_theme();
		switch( $theme ){
			case 'Accelerate':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
						do_action( 'accelerate_after_body_content' );
					get_footer();
				}
				break;
			case 'Athena':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					?>								
						</main><!-- #main -->
					</div><!-- #primary -->
					<?php 
					get_footer();
				}
				break;
			case 'ColorMag':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					do_action( 'colormag_after_body_content' );
					get_footer();
				}
				break;
			case 'Customizr':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					?>
					</div><!-- //#main-wrapper -->

					<?php do_action( '__after_main_wrapper' );##hook of the footer with get_get_footer
				}
				break;
			case 'Eleganto':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					get_footer();
				}
				break;
			case 'evolve':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					get_footer();
				}
				break;
			case 'Hueman':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					get_footer();
				}
				break;
			case 'Responsive':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					get_footer();
				}
				break;
			case 'Spacious':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					get_footer();
				}
				break;
			case 'Sydney':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					get_footer();
				}
				break;
			case 'Twenty Fifteen':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
				}else{
					get_footer();
				}
				break;
			case 'Twenty Seventeen':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
				}else{
									?>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<?php
					get_footer();
				}
				break;
			case 'Twenty Sixteen':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
				}else{
					get_footer();
				}
				break;
			case 'Virtue':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					get_footer();
				}
				break;
			case 'Virtue':
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
						?>
						</td></tr></table>
						<?php
						do_action('kadence_page_footer');
					?>
					</div><!-- /.main -->
					<?php
				}
				break;
			default:
				$fields = apply_filters( 'page_template_footer_filter', $fields );
				if( !empty( $fields ) ){
					//echo implode( " ", $fields );
					$fields = explode( ',', $fields );
					foreach( $fields as $index => $value ){
						if( $value == 'get_footer' ){
							get_footer();
						}else{
							echo $value;
						}
					}
					//echo 'Filtered Fields';
				}else{
					?>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					<?php
					get_footer();
				}
				break;
		}
	}
	
	/**
	 * function template_model_filter_top
	 * Handles displaying closing tags for my templates 
	 * @since 1.5.3.8
	 * @updated 1.5.3.8
	 * @access public
	 * @params
	 * @returns array $results of messages and errors for form to display on reload
	*/
	
	function template_model_filter_top( $results ){
		// sample, use string below as example to add your own style on the page use only get_sidebar to activate the get_sidebar on the page.
		//$results = '<div class="clear"></div>,</td>,<td id="2017_ura_template_sidebar" class="2017_ura_sidebar_tab">,</td>';
		$results = '<div class="container">,<div class="row enigma_blog_wrapper">,<div class="col-md-8">';
		return $results;
	}
	
	/**
	 * function template_model_filter_sidebar
	 * Handles displaying closing tags for my templates 
	 * @since 1.5.3.8
	 * @updated 1.5.3.8
	 * @access public
	 * @params
	 * @returns array $results of messages and errors for form to display on reload
	*/
	
	function template_model_filter_sidebar( $results ){
		// sample, use string below as example to add your own style, use only get_sidebar to activate the get_sidebar on the page.
		//$results = '<div class="clear"></div>,</td>,<td id="2017_ura_template_sidebar" class="2017_ura_sidebar_tab">,get_sidebar,</td>';
		$results = '</div>,get_sidebar,</div>,</div>';
		return $results;
	}
	
	/**
	 * function template_model_filter_footer
	 * Handles displaying closing tags for my templates 
	 * @since 1.5.3.8
	 * @updated 1.5.3.8
	 * @access public
	 * @params
	 * @returns array $results of messages and errors for form to display on reload
	*/
	
	function template_model_filter_footer( $results ){
		// sample, use string below as example to add your own style, use only get_footer to activate the get_footer on the page.
		//$results = '<div class="clear"></div>,</td>,<td id="2017_ura_template_sidebar" class="2017_ura_sidebar_tab">,get_footer,</td>';
		$results = 'get_footer';
		return $results;
	}
}

