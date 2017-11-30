<?php get_header();?>
	<div class="limpa"></div>
	<?php $counter = 0; ?>
	<div id="search">
		<div class="wrapper">
			<div id="search_content">
				<div class="limpa"></div>
				<ul id="search_bloco">

					<div class="content_header corFundo">RESULTADO DA BUSCA <span>por "<?php the_search_query() ?>"</span></div>

					<?php if ( have_posts() ) :
						while ( have_posts() ) : the_post(); ?>

							<li class="search_post">

								<h1 class="corTexto"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>

<?php
								$thumb = get_the_post_thumbnail( $post, 'thumbnail' );	// tenta pegar o thumbnail
								if ( ! $thumb )
									$thumb = catch_that_image();	// se não tem thumbnail, tenta pegar primeira imagem do post

								if ( $thumb ) {
?>
										<div class="search_thumb"><a href="<?php the_permalink() ?>"><?php echo $thumb; ?></a></div>
<?php
								}

								echo '<p>';
								if ( $post->post_type == 'post' )	// Exibe data apenas para posts comuns (exclui CPTs)
									echo '<strong>' . get_the_date('d/m/Y') . ' • </strong>';

								echo wpe_excerpt('wpe_excerptlength_search') . '</p>';

								$counter++;
								$output = '';
								$separator = ', ';
								$posttags = get_the_tags();
								if ($posttags) {
									foreach($posttags as $tag) {
										$output .= '<a href="'.get_tag_link($tag->term_id).'" title="' . esc_attr( sprintf( __( "Ver todos os posts com a tag %s" ), $tag->name ) ) . '">'.$tag->name.'</a>'.$separator;
									}
									echo "<h2>Tags: ".trim($output, $separator).".</h2>";
								}
?>

								<div class="limpa"></div>
							</li>

					<?php endwhile; ?>

					<div id="sign" class="sing_search">
						<div class="limpa"></div>
						<div id="num_results">
													<?php
													paginacao_quantidade();
													?>
						</div>

						<div id="navigation">
													<?php
													paginacao();
													?>
						</div>

						<div class="limpa"></div>
					</div>

					<?php
						else : 	// if (have_posts()) ?>
							<li class="search_post">
								NENHUM RESULTADO ENCONTRADO.
							</li>
					<?php endif; ?>


				</ul>


				<div id="sidebar">
					<button class="sidebar-toggle corFundo"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
					<ul>
						<?php
							dynamic_sidebar('sidebar-1');
						?>
					</ul>
				</div>
				<div class="limpa"></div>
			</div>
		</div>
	</div>

<?php get_footer();?>