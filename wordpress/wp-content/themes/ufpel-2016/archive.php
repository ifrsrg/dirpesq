<?php
$opcoes = (array) get_option( 'wpiservico_opcoes' );

// Se está nos arquivos do wpi_servico e foi configurada uma página personalizada, redireciona
if ( is_post_type_archive( 'wpi_servico' ) && ! empty( $opcoes['pagina-inicial'] ) )
	wp_redirect( get_page_link( $opcoes['pagina-inicial'] ));

get_header();
?>
	<div class="limpa"></div>
	<div id="search">
		<div class="wrapper">
			<div id="search_content">
				<div class="limpa"></div>
				<ul id="search_bloco">
					<?php
					if (is_date()){ ?>
						<div class="content_header corFundo">RESULTADO DA BUSCA <span>para
							<?php if ( is_day() ) :
								echo ' "'.get_the_date().'"</span>';
							elseif ( is_month() ) :
								echo ' "'.get_the_date('F').' DE '.get_the_date('Y').'"</span>';
							elseif ( is_year() ) :
								echo ' "'.get_the_date('Y').'"</span>';
							endif;  ?>
						</div>
					<?php
					} else if (is_tag()){ ?>
						<div class="content_header corFundo">RESULTADO DA BUSCA <span>pela tag "<?php single_tag_title(); ?>"</span></div>
					<?php
					} else if (is_category()){ ?>
						<div class="content_header corFundo">RESULTADO DA BUSCA <span>pela categoria "<?php single_cat_title(); ?>"</span></div>
					<?php
					} else if ( is_post_type_archive( 'wpi_servico' ) || is_tax( 'wpi_servico_categoria' ) ) {

						/* Cabeçalho wpi_servico (CPT) e wpi_servico_categoria (Taxonomia) */

						if ( ! empty( $opcoes['pagina-inicial'] ) )
							$linkcatalogo = '<a href="' . get_page_link( $opcoes['pagina-inicial'] ) . '">' . get_the_title( $opcoes['pagina-inicial'] ) . '</a>';
						else
							$linkcatalogo = '<a href="' . get_post_type_archive_link( 'wpi_servico' ) . '">Catálogo de Serviços</a>';

					?>
						<div class="content_header corFundo">
							<?php
							echo $linkcatalogo;
							$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
							if ( $term )
								echo ' > ' . $term->name;
							?>
						</div>

						<div class="wpiservico-nav">
							Categoria:
							<select onchange="window.location.href=this.options[this.selectedIndex].value;">
								<option value=""></option>
								<?php
								$categorias = get_terms( 'wpi_servico_categoria', array( 'hide_empty' => false ) );

								foreach ( $categorias as $categoria ) {
									echo '<option value="' . get_term_link( $categoria->slug, 'wpi_servico_categoria' ) . '"';
									if ( isset( $term->slug ) && ( $term->slug == $categoria->slug ) )
										echo ' selected';
									echo '>' . $categoria->name . '</option>';
								}
								?>
							</select>
						</div>
					<?php
					}
					else {	// Se caiu aqui provavelmente é arquivo de post ou taxonomia personalizados - exibe o título padrão do WP
					?>
						<div class="content_header corFundo">
							<?php the_archive_title(); ?>
						</div>
					<?php
					}
					?>

					<?php
						if ( is_post_type_archive( 'wpi_servico' ) || is_tax( 'wpi_servico_categoria' ) ) {

							/* Arquivos do CPT wpi_servico e Taxonomia wpi_servico_categoria */

							if ( have_posts() ) {
								while ( have_posts() ) {
									the_post();
					?>
									<li class="wpiservico-post">

										<h1 class="corTexto"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>

										<?php
											$thumb = get_the_post_thumbnail( $post, 'thumbnail' );	// tenta pegar o thumbnail
											if ($thumb) {
												$classe = "";
												echo '<div class="wpiservico-thumb"><a href="' . get_the_permalink() . '">' . $thumb . '</a></div>';
											}
											else
												$classe = "wpiservico-nothumb";
										?>

										<div class="wpiservico-resumo <?php echo $classe; ?>"><?php echo get_post_meta( get_the_ID(), 'wpiservico_descricao', true); ?></div>

										<div class="limpa"></div>
									</li>

					<?php
								} // while
							} // if ( have_posts() )
							else {
					?>
								<li class="search_post">
									<p>Nenhum serviço encontrado.</p>
								</li>
					<?php
							}
						} // if ( is_post_type_archive( 'wpi_servico' ) || is_tax( 'wpi_servico_categoria' ) )

						else {

							/* Arquivos de posts padrão */

							if ( have_posts() ) {
	//							$count_posts = wp_count_posts();
								$counter = 0;

								while ( have_posts() ) {
									the_post();
					?>
									<li class="search_post">

										<h1 class="corTexto"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>

										<?php
											$thumb = get_the_post_thumbnail( $post, 'thumbnail' );	// tenta pegar o thumbnail
											if ( ! $thumb )
												$thumb = catch_that_image();	// se não tem thumbnail, tenta pegar primeira imagem do post

											if ($thumb) {
										?>
												<div class="search_thumb"><a href="<?php the_permalink() ?>"><?php echo $thumb; ?></a></div>
										<?php
											}
										?>

										<p>
										<?php
											if ( $post->post_type == 'post' )	// Exibe data apenas para posts comuns (exclui CPTs)
										 		echo "<strong>".get_the_date('d/m/Y')." • </strong>";

										 	echo wpe_excerpt('wpe_excerptlength_search');
										?>
										</p>

										<?php
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

						<?php } // while
							} // if ( have_posts() )
							else { ?>
								<li class="search_post">
									NENHUM RESULTADO ENCONTRADO.
								</li>
					<?php
							} // else
						} // else
					?>

					<div id="sign" class="sing_search">
						<div class="limpa"></div>

						<?php if ( is_post_type_archive( 'wpi_servico' ) || is_tax( 'wpi_servico_categoria' ) ): ?>

							<div id="num_results">
								<?php echo $wp_query->found_posts . " serviços listados.";	?>
							</div>

						<?php else: ?>
							<div id="num_results">
								<?php paginacao_quantidade(); ?>
							</div>

							<div id="navigation">
								<?php paginacao(); ?>
							</div>
						<?php endif; ?>

						<div class="limpa"></div>
					</div>

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