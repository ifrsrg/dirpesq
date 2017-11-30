<?php

function moduloLista($id, $opcoes) {

	$opcoes	= explode(":", $opcoes);
	$titulo	= $opcoes[0];
	$exibir = ( ! empty( $opcoes[1] ) ) ? $opcoes[1] : '';

	global $posts, $post;

?>
	<div id="modulo-lista<?php echo $id; ?>" class="wrapper modulos">

		<div id="modulo_list_content">
			<section id="modulo_list_bloco">
				<ul>

				<div class="content_header corFundo"><?php echo $titulo; ?></div>

				<?php if ( have_posts() ) :
					while ( have_posts() ) : the_post(); ?>

						<li id="post-<?php the_ID(); ?>" <?php post_class("modulo_lista_post"); ?>>
							<article>
								<h1 class="corTexto"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
								<time><?php echo get_the_date(); // 'l, n \d\e F \d\e Y' ?></time>
								<p>
								<?php
									if ( $exibir == 'r' || $exibir == 'i' ) {
										if ( $exibir == 'i' ) {
											$thumb = get_the_post_thumbnail( $post, 'thumbnail' );	// tenta pegar o thumbnail
											if ($thumb) {
								?>
												<div class="search_thumb"><a href="<?php the_permalink(); ?>"><?php echo $thumb; ?></a></div>
								<?php
											}
										}

										the_excerpt();
									}
									else {
										the_content();
									}
								?>
								</p>

								<div class="limpa"></div>
							</article>
						</li>

	            <?php endwhile;
	            	endif; ?>
	        	</ul>

	            <div id="sign" class="sing_search">
	            	<div class="limpa"></div>

					<div id="num_results">
                                            <?php
                                            paginacao_quantidade("Exibindo registros %d a %d de um total de %d.");
                                            ?>
					</div>

					<div id="navigation">
                                            <?php
                                            paginacao(); ?>
					</div>

					<div class="limpa"></div>
	            </div>
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

<?php } ?>