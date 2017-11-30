<?php

function moduloVitrine($id, $opcoes) {

	$opcoes                 = explode(":", $opcoes);
	$titulo                 = $opcoes[0];
	$idcategoria            = is_numeric( $opcoes[1] ) ? $opcoes[1] : '';
	$quantidade				= is_numeric( $opcoes[2] ) ? $opcoes[2] : 10;
	$delay					= is_numeric( $opcoes[3] ) ? $opcoes[3] : 2000;
	$classetitulo			= ( $opcoes[4] ) ? "vitrine_bloco_" . $opcoes[4] : '';
	$classeresumo			= ( $opcoes[5] ) ? "vitrine_bloco_" . $opcoes[5] : '';
	$orderby				= $opcoes[6];
	$ignoresticky			= ( $opcoes[7] == 's' ); // booleano
	// $opcoes[8] indica exibição nas páginas internas (tratado no header.php)

	global $inst_options;
?>


	<div id="modulo-vitrine<?php echo $id; ?>" class="wrapper modulos modulo-vitrine">
		<div id="vitrine">
			<?php
				$category_link = ( $idcategoria ) ? get_category_link( $idcategoria ) : '';
			?>
			<?php if ( $titulo ): ?>
				<div class="content_header corFundo">
				<?php
					if ( $category_link )
						echo '<a href="' . esc_url( $category_link ) . '">' . $titulo . '</a>';
					else
						echo $titulo;
				?>
				</div>
			<?php endif; ?>
			<div class="carousel_vitrine">
				<div id="vitrine_content">
					<ul class="lista_vitrine">
						<?php
						$the_query = new WP_Query( array( 'posts_per_page' => $quantidade, 'cat' => $idcategoria, 'orderby' => $orderby, 'ignore_sticky_posts' => $ignoresticky ) );

						if ($the_query->have_posts())
							while ($the_query->have_posts()) {
								$the_query->the_post();

								$thumb = get_the_post_thumbnail( $the_query->post->ID, "medium" );	// tenta pegar o thumbnail
								if ( ! $thumb ) {
									$thumb = catch_that_image();	// se não tem thumbnail, tenta pegar primeira imagem do post
								}
								if ( ! $thumb ) {
									$thumb = '<img src="' . $inst_options['wpinst_ogimg'] . '">';
								}

						?>
								<li class="item_vitrine">
									<div class="vitrine_bloco">

									<?php if ( $thumb ): ?>
										<a href="<?php the_permalink() ?>"><?php echo $thumb; ?></a>
									<?php endif; ?>

										<div class="vitrine_bloco_titulo <?php echo $classetitulo; ?>">
											<a href="<?php the_permalink() ?>">
												<?php the_title(); ?>
												<span class="vitrine_bloco_resumo <?php echo $classeresumo; ?>"><?php the_excerpt(); ?></span>
											</a>
										</div>
									</div>
								</li>

						<?php
							} // end while

						wp_reset_postdata();

						?>
					</ul>
				</div>
				<div id="vitrine_anterior"><a href="" class="prev-navigation-vitrine"><img src="<?php bloginfo('template_url');?>/imagens/icones/arrowgrayright.png"></a></div>
				<div id="vitrine_proximo"><a href="" class="next-navigation-vitrine"><img src="<?php bloginfo('template_url');?>/imagens/icones/arrowgrayleft.png"></a></div>
			</div>
		</div>
	</div>

	<script>
		jQuery(document).ready(function($) {
			$('#vitrine_content').jcarousel({
				'wrap': 'circular',
			}).jcarouselAutoscroll({
				'interval': <?php echo $delay; ?>,
			}).hover(function() {
				$(this).jcarouselAutoscroll('stop');
			}, function() {
				$(this).jcarouselAutoscroll('start');
			}).jcarouselSwipe();

			$('.prev-navigation-vitrine').click(function() {
			    $('#vitrine_content').jcarousel('scroll', '-=1');
			    return false;
			});

			$('.next-navigation-vitrine').click(function() {
    			$('#vitrine_content').jcarousel('scroll', '+=1');
    			return false;
			});

		});
	</script>
<?php } ?>