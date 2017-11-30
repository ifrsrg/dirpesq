<?php

/* Redireciona páginas de tipos de posts personalizados para o arquivo da imagem destacada */
if ( in_array( $post->post_type, array('banners','links_destacados') ) ) {
	wp_redirect( wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ), 301 );
	exit;
}

if ( $post->post_type == 'imagem-de-capa' ) {
	$link = get_post_meta( $post->ID, 'imagemdecapa_link', true ) ?: get_post_meta( $post->ID, 'imagemdecapa_externa', true ); // requer PHP >=5.3 - http://php.net/manual/en/language.operators.comparison.php#language.operators.comparison.ternary
	if ( $link ) {
		wp_redirect( $link, 301 );	// redireciona para o link ou imagem externa, se definidos
		exit;
	}
	elseif ( get_post_meta( $post->ID, 'linkar', true ) != '1' ) {	// para manter comportamento antigo de link para o post (removido na versão 2.3)
		wp_redirect( wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ), 301 ); // sem link e sem opção antiga de linkar para post, redireciona para imagem destacada
		exit;
	}
}

get_header();

?>
	<div class="limpa"></div>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="wrapper">
			<div id="single_content">
				<section id="single_post">
					<div class="content_header corFundo">
					<?php

						/* Post padrão */

						if ( $post->post_type == 'post' ) {
							$categories = get_the_category(); // pega categorias do post

							if ( count( $categories ) ) {
								foreach ( $categories as $category ) {
									$children = get_categories( array ( 'parent' => $category->term_id ) );
									if ( count( $children ) == 0 )
										break;			// encontra primeira categoria sem filhos (mais baixa na hierarquia)
								}

								$categories = get_category_parents( $category->term_id, TRUE, ' > ' ); // gera hierarquia de categorias com links
							}
							else
								$categories = "";

							echo $categories;
						}

						/* CPT: wpi_servico */

						elseif ( $post->post_type == 'wpi_servico' ) {

							$opcoes = (array) get_option( 'wpiservico_opcoes' );

							if ( ! empty( $opcoes['pagina-inicial'] ) )
								$linkcatalogo = '<a href="' . get_page_link( $opcoes['pagina-inicial'] ) . '">' . get_the_title( $opcoes['pagina-inicial'] ) . '</a>';
							else
								$linkcatalogo = '<a href="' . get_post_type_archive_link( 'wpi_servico' ) . '">Catálogo de Serviços</a>';

							echo $linkcatalogo;

							$categories = wp_get_post_terms( get_the_ID(), 'wpi_servico_categoria', array() );

							if ( isset( $categories[0] ) )
						 		echo ' > <a href="' . get_term_link( $categories[0]->slug, 'wpi_servico_categoria' ) . '" title="' . sprintf( "Ver todos os serviços de %s", $categories[0]->name ) . '" ' . '>' . $categories[0]->name.'</a> ';
						}

						/* CPT: wpi_pessoa */

/*
						elseif ( $post->post_type == 'wpi_pessoa' ) {

							echo '<a href="' . get_post_type_archive_link( 'wpi_pessoa' ) . '">Pessoas</a>';

							$categories = wp_get_post_terms( get_the_ID(), 'wpi_pessoa_grupo', array() );

							if ( isset( $categories[0] ) )
						 		echo ' > <a href="' . get_term_link( $categories[0]->slug, 'wpi_pessoa_grupo' ) . '" title="' . sprintf( "Ver todos os %s", $categories[0]->name ) . '" ' . '>' . $categories[0]->name.'</a> ';

						}
*/
					?>
					</div>

					<article id="single_post_inside">

						<?php

						if ( $post->post_type == 'wpi_pessoa' ) {
							$thumb = get_the_post_thumbnail( $post->post_id, 'medium' );
							if ( $thumb )
								echo '<div class="wpipessoa-foto">' . $thumb . '</div>';
						}

						the_title( '<h1 class="corTexto">', '</h1>' );

						the_content();

						?>

						<div class="limpa"></div>

						<?php if ( $post->post_type == 'post' ): ?>
							<div id="sign">
								<span class="single_pub_data">Publicado em <?php the_date('d/m/Y'); ?>, em </span>
								<?php
									$categories = get_the_category();
									$separator = ', ';
									$output = '<span class="single_pub_categorias">';
									if($categories){
										foreach($categories as $category) {
											$output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( "Ver todos os posts em %s", $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
										}
									echo trim($output, $separator).".</span> ";
									}

									$output = '';
									$posttags = get_the_tags();
									if ($posttags) {
										foreach($posttags as $tag) {
											$output .= '<a href="'.get_tag_link($tag->term_id).'" title="' . esc_attr( sprintf( "Ver todos os posts com a tag %s", $tag->name ) ) . '">'.$tag->name.'</a>'.$separator;
										}
										echo '<span class="single_pub_tags"> Marcado com as tags ' . trim($output, $separator) . '.</span>';
									}
								?>
							</div>
						<?php endif; ?>

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

<?php get_footer(); ?>