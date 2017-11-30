<?php function moduloPagina($id, $opcoes) {
	$opcoes   = explode(":", $opcoes);
    $titulo   = $opcoes[0];
    $idpagina = $opcoes[1];
    $sidebar  = $opcoes[2];
?>

	<div id="modulo-pagina<?php echo $id; ?>">
		<div class="wrapper">
			<div id="single_content">
				<section id="single_post" <?php if ( $sidebar == 'n' ) echo 'style="width:100%"'; ?>>
					<?php
						$querypagina = new WP_Query( array( 'page_id' => $idpagina ) );
						if ( $querypagina->have_posts() ) {
							$querypagina->the_post();
					?>
					<?php if ( $titulo ): ?>
					<div class="content_header corFundo"><?php echo $titulo ?></div>
					<?php endif; ?>
					<article id="single_post_inside">
							<p>
								<?php the_content(); ?>
							</p>

							<div class="limpa"></div>
					</article>

					<?php
						}

						wp_reset_postdata();
					?>
				</section>

<?php if ( $sidebar != 'n' ): ?>
				<section id="sidebar">
					<ul>
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-1') ) : ?>
						<?php endif; ?>
					</ul>
				</section>
<?php endif; ?>

				<div class="limpa"></div>
			</div>
		</div>
	</div>


<?php } ?>