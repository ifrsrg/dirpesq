<?php get_header();?>
	<div class="limpa"></div>

	<div id="single">
		<div class="wrapper">
			<div id="single_content">
				<section id="single_post">
					<div class="content_header corFundo"><?php the_title() ?></div>
					<article id="single_post_inside">
							<p>
								<?php the_content(); ?>
							</p>

							<div class="limpa"></div>
					</article>
				</section>

				<section id="sidebar">
					<button class="sidebar-toggle corFundo"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
					<ul>
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-1') ) : ?>
						<?php endif; ?>
					</ul>
				</section>

				<div class="limpa"></div>
			</div>
		</div>
	</div>

<?php get_footer();?>