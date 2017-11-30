<?php
	function moduloImagemdest($id, $opcoes){
		$opcoes                 = explode(":", $opcoes);
		$quantidade             = $opcoes[0]; // Quantidade de imagens configuradas nas opções do tema
		$ordem_de_exibicao      = $opcoes[1]; // Por data (date) ou aletória (rand)
		$exibir_seletor         = $opcoes[2]; // s ou n
		$tempo_entre_transicoes = $opcoes[3]*1000;
		$efeito_de_transicao    = $opcoes[4]; // Mover pra esquerda (e), mover pra direita (d) ou fade (f)
?>

		<div id="modulo-imagemdest<?php echo $id; ?>" class="wrapper modulos moduloimgdest">
			<div id="imgDest" data-jcarousel="true">
				<ul>
				<?php
				$args = array(
							'posts_per_page' => $quantidade,
							'orderby' => $ordem_de_exibicao,
							'post_type' => 'imagem-de-capa',
						);
				$query = new WP_Query( $args );

				if ($query->have_posts()) {

					while ($query->have_posts()) {

						$query->the_post();

						$imagemdecapa_externa = get_post_meta( get_the_ID(), 'imagemdecapa_externa', true );

						$link = get_post_meta( get_the_ID(), 'imagemdecapa_link', true );

						if ( ! $link && get_post_meta( get_the_ID(), 'linkar', true ) == '1' )	// compatibilidade com comportamento antigo de link para post (removido na versão 2.3)
							$link = get_the_permalink();

						echo '<li>';

						if ( $link )
							echo '<a href="' . $link . '">';

						if ( $imagemdecapa_externa )
							echo '<img src="' . $imagemdecapa_externa . '">';
						else
							the_post_thumbnail('large');

						if ( get_post_meta( get_the_ID(), 'barra', true ) )
							echo '<div class="imgDestcontent">' . get_the_title() . '</div>';

						if ( $link )
							echo '</a>';

						echo '</li>';
					}
				}
				else if ( current_user_can( 'edit_posts' ) ) {
						echo '<li><div style="width:984px; height:220px; padding:80px 20px; box-sizing:border-box; font-size:18px; color:#666; text-align: center;">' .
							 'Adicione imagens a este bloco acessando <strong>Imagem de Capa - Adicionar nova</strong> no painel administrativo<br>' .
							 '<span style="font-size:14px;">Você pode configurar as opções de exibição, ou remover este módulo, em <strong>Aparência - Opções do Tema</strong><br>' .
							 '<em>(Esta mensagem não é visível aos visitantes do site)</em></span>' .
							 '</div></li>';
				}

				echo '</ul>';

				if ($exibir_seletor == "s" && $query->post_count > 1 ) {
					echo '<div id="imgdest-seletor" class="imgDest-pagination" data-jcarouselpagination="true"></div>';
				}

				wp_reset_postdata();
				?>
			</div>
		</div>
		<script>

			jQuery(document).ready(function($) {

				$('#imgDest').jcarousel({
					wrap: 'circular',
					<?php if ($efeito_de_transicao == 'f'){ ?>
						animation: {
							duration: 0
						},
					<?php } ?>
					visible: 1
				})
				.jcarouselAutoscroll({
					interval: <?php echo $tempo_entre_transicoes ?>,
					<?php if ($efeito_de_transicao == 'e'){ ?>
					  target: '-=1',
					<?php } ?>
					autostart: true
				})
				.hover(function() {
					$(this).jcarouselAutoscroll('stop');
				}, function() {
					$(this).jcarouselAutoscroll('start');
				})
				.jcarouselSwipe();

				<?php if ($efeito_de_transicao == 'f'){ ?>
					$('#imgDest').on('jcarousel:animate', function (event, carousel) {
//                        console.log(carousel._element.context);
						$(carousel._element.context).find('li').hide().fadeIn();
					});
				<?php } ?>

		$('.imgDest-pagination')
			.on('jcarouselpagination:active', 'a', function() {
				$(this).addClass('imgdest-select-active');
			})
			.on('jcarouselpagination:inactive', 'a', function() {
				$(this).removeClass('imgdest-select-active');
			})
			.jcarouselPagination({
				'item': function(page, carouselItems) {
					return '<a class="imgdest-select" href="#' + page + '"></a>';
				}
			});

		});
		</script>
<?php } ?>