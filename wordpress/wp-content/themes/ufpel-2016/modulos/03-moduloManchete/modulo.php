<?php function moduloManchete($id, $opcoes){ 
	$opcoes                 = explode(":", $opcoes);
    $titulo                 = $opcoes[0];
    $idcategoria            = $opcoes[1];
?>
	

	<div id="modulo-manchete<?php echo $id; ?>" class="wrapper modulos">
		<div id="manchete">
				<?php
				    $category_id = get_cat_ID( 'manchete' );
				    $category_link = get_category_link( $category_id );
				?>

				<div class="content_header corFundo"><a href="<?php echo esc_url( $category_link ); ?>"><?php echo $titulo ?></a></div>
				<?php 
					query_posts('showposts=1&cat='.$idcategoria);
					
					if (have_posts())
						while (have_posts()) {
							the_post();
				?>
				<div id="manchete_content" class="corTexto">
					<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
				</div>
				<?php
						}
					wp_reset_query();
				?>
			</div>
	</div>

<?php } ?>