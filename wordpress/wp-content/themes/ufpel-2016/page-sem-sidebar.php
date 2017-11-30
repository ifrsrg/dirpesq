<?php /* template name: Sem barra lateral */ ?>
<?php get_header();?>
	<div class="limpa"></div>

	<div id="single">
		<div class="wrapper">
			<div id="single_content">
				<section id="single_post" style="width:100%">
					<?php
						if ( have_posts() ) :
							while ( have_posts() ) : the_post(); 
						?>
					<div class="content_header corFundo"><?php the_title() ?></div>
					<article id="single_post_inside">
							<p>
								<?php the_content(); ?>
							</p>

							<div class="limpa"></div>
							

						<?php 	
							endwhile; 
							wp_reset_postdata(); 
						endif;
						?>
					</article>
				</section>
				
				<div class="limpa"></div>
			</div>
		</div>
	</div>
	
<?php get_footer();?>