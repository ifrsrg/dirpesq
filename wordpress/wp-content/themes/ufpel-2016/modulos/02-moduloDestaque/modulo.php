<?php

function moduloDestaque($id, $opcoes) {

	$opcoes                 = explode(":", $opcoes);
	$titulo                 = $opcoes[0];
	$idcategoria            = $opcoes[1];

	global $inst_options;

?>

	<div id="modulo-destaque<?php echo $id; ?>" class="wrapper modulos">
		<div id="destaques">
			<div class="modulo-destaque-pagination"></div>
			<?php
				$category_link = get_category_link( $idcategoria );
			?>
			<div class="content_header corFundo"><a href="<?php echo esc_url( $category_link ); ?>"><?php echo $titulo; ?></a></div>
			<div class="carousel_destaques">
				<div id="destaques_content">
					<ul class="lista_dest">
						<?php
						$quantidade = 6;
						$the_query = new WP_Query('showposts='.$quantidade.'&cat='.$idcategoria);
						$c = 0;

						if ($the_query->have_posts())
							while ($the_query->have_posts()) {
								$the_query->the_post();

								if ($c%2 == 0) {
						?>
									<li class="item_dest">
										<div id="destaques_list">
											<ul>
						<?php
								}
						?>

								<li class="dest_bloco">
									<div class="dest_bloco_thumb">
										<a href="<?php the_permalink() ?>">
										<?php
										//	the_post_thumbnail("medium");
											$thumb = get_the_post_thumbnail( $the_query->post, 'medium' );
											if ( empty( $thumb ) )
												$thumb = '<img src="' . $inst_options['wpinst_ogimg'] . '">';
											echo $thumb;
										?>
										</a>
									</div>
									<div class="dest_bloco_titulo">
										<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
									</div>
									<div class="dest_bloco_resumo">
										<?php echo wpe_excerpt('wpe_excerptlength_dest'); ?>
									</div>
								</li>

						<?php
								if ( ($c%2 != 0) || ($c == $the_query->found_posts-1) ) {
						?>
											</ul>
										</div>
									</li> <!-- item_dest -->

						<?php
								}

								$c++;
							} // end while

						?>
					</ul>
				</div>
				<!--div id="carousel_dest_left"><a href="" class="prev-navigation-dest"><img src="<?php bloginfo('template_url');?>/imagens/icones/arrowgrayright.png"></a></div-->
				<!--div id="carousel_dest_right"><a href="" class="next-navigation-dest"><img src="<?php bloginfo('template_url');?>/imagens/icones/arrowgrayleft.png"></a></div-->
			</div>
		</div>
	</div>

	<script>
		jQuery(document).ready(function($) {
			$('#destaques_content').jcarousel({
				'wrap': 'circular',
			}).jcarouselAutoscroll({
				'interval': 10000,
			}).hover(function() {
				$(this).jcarouselAutoscroll('stop');
			}, function() {
				$(this).jcarouselAutoscroll('start');
			}).jcarouselSwipe();

			$('.modulo-destaque-pagination').jcarouselPagination({
			    'item': function(page, carouselItems) {
			        return '<a href="#' + page + '"><img src="'+templateUrl+'/imagens/icones/bt_off.png"></a>';
			    }
			}).on('jcarouselpagination:active', 'a', function() {
			    $(this).find('img').attr('src',templateUrl+'/imagens/icones/bt_on.png');
			})
			.on('jcarouselpagination:inactive', 'a', function() {
			    $(this).find('img').attr('src',templateUrl+'/imagens/icones/bt_off.png');
			});

			$('.modulo-destaque-pagination').find("a").find("img").first().attr('src',templateUrl+'/imagens/icones/bt_on.png')

			$('.dest_bloco_titulo').hover(function() {
				$(this).addClass('corBorda');
				$(this).find('a').addClass('corTexto');
			},function() {
				$(this).removeClass('corBorda');
				$(this).find('a').removeClass('corTexto');
			});
		});
	</script>
<?php } ?>