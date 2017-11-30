<?php get_header();?>
	<div class="limpa"></div>

<?php
global $wp_query;

$is_date_archive = ( isset($wp_query->query['year']) || isset($wp_query->query['monthnum']) || isset($wp_query->query['day']) );

if ( $is_date_archive ) { // Evita erro 404 nos arquivos por data
?>
	<div id="search">
		<div class="wrapper">
			<div id="search_content">
				<div class="limpa"></div>
				<ul id="search_bloco">
					<div class="content_header corFundo">RESULTADO DA BUSCA <span>para
						<?php
						if ( isset($wp_query->query['day']) )
							echo $wp_query->query['day'] . "/";
						if ( isset($wp_query->query['monthnum']) )
							echo $wp_query->query['monthnum'] . "/";
						if ( isset($wp_query->query['year']) )
							echo $wp_query->query['year'];
						?>
						</span>
					</div>
					<li class="search_post">
						NENHUM RESULTADO ENCONTRADO.
					</li>
				</ul>
				<div id="sidebar">
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

<?php

}
else {

?>

	<div id="erro">
		<div class="wrapper">
			<div id="erro_content">
				<div id="erro_img"></div>
				<h1>ERRO 404</h1>
				<h2>A página solicitada não foi encontrada</h2>
				<h3>Você pode ter seguido um link desatualizado ou cometido algum engano ao digitar o endereço.</h3>
			</div>
		</div>
	</div>

<?php

} // if ( $is_date_archive )

?>

<?php get_footer();?>