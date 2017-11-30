<?php
/* Imagem de Capa */


/**
 * Salva campos personalizados
 *
 */
function imagemdecapa_save_post( $post_id, $post, $update ) {

	/* Verificações de segurança e permissões */

	if ( ! isset( $_POST['imagemdecapa-metabox-nonce'] ) || ! wp_verify_nonce( $_POST['imagemdecapa-metabox-nonce'], basename(__FILE__) ) )
		return;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if ( ! current_user_can( 'edit_post', $post_id ) )
		return;

	/* Salva dados na base */

	if ( isset( $_POST['barra'] ) )
		update_post_meta( $post_id, 'barra', 1 );
	else
		delete_post_meta( $post_id, 'barra' );

	if ( isset( $_POST['imagemdecapa_externa'] ) )
		update_post_meta( $post_id, 'imagemdecapa_externa', $_POST['imagemdecapa_externa'] );
	else
		delete_post_meta( $post_id, 'imagemdecapa_externa' );

	if ( isset( $_POST['imagemdecapa_link'] ) )
		update_post_meta( $post_id, 'imagemdecapa_link', $_POST['imagemdecapa_link'] );
	else
		delete_post_meta( $post_id, 'imagemdecapa_link' );

}
add_action( 'save_post_imagem-de-capa', 'imagemdecapa_save_post', 10, 3 );


/**
 * Adiciona metaboxes
 *
 */
function imagemdecapa_add_meta_boxes() {

	remove_meta_box( 'postimagediv', 'imagem-de-capa', 'side' );												// remove da barra lateral a metabox padrão da imagem destacada
	add_meta_box( 'postimagediv', 'Imagem de Capa', 'post_thumbnail_meta_box', 'imagem-de-capa', 'normal' );	// insere metabox da imagem destacada no contexto normal, com título personalizado
	add_meta_box( 'imagemdecapa-sugeridas', 'Imagens sugeridas', 'imagemdecapa_sugeridas_meta_box', 'imagem-de-capa', 'normal' );	// adiciona metabox de imagens sugeridas
	add_meta_box( 'imagemdecapa-propriedades', 'Propriedades', 'imagemdecapa_propriedades_meta_box', 'imagem-de-capa', 'normal' );	// adiciona metabox de propriedades

}
//add_action( 'do_meta_boxes', 'imagemdecapa_do_meta_boxes' ); // o hook 'do_meta_boxes' é outra alternativa, mas é executado para cada um dos contextos de metabox ('normal', 'advanced' e 'side')
add_action( 'add_meta_boxes_imagem-de-capa', 'imagemdecapa_add_meta_boxes' );


/**
 * Personaliza conteúdo da metabox padrão da imagem destacada
 *
 * http://wordpress.stackexchange.com/questions/158491/is-it-possible-set-a-featured-image-with-external-image-url
 * http://wordpress.stackexchange.com/questions/175790/run-script-after-clicking-set-featured-image-in-media
 * http://hookr.io/filters/admin_post_thumbnail_html/
 *
 */
function imagemdecapa_metabox_imagem_destacada( $content, $post_id ) {

	if ( get_post_type( $post_id ) == 'imagem-de-capa' ) {

		$search = array( __( 'Set featured image' ), __( 'Remove featured image') );
		$replace = array( 'Inserir Imagem de Capa', 'Remover Imagem de Capa' );

		$content =
			'<p><span class="dashicons dashicons-info"></span> Para melhor qualidade, a imagem deve possuir as dimensões de <strong>984 x 220 pixels</strong>. ' .
			'<a style="text-decoration: none" class="tooltip" title="Você pode redimensionar e cortar a imagem, através da opção \'Editar Imagem\', na janela da biblioteca de mídia." href="#">(?)</a></p>' .
			str_replace( $search, $replace, $content ); // conteúdo original "Definir imagem destacada" ou "Remover imagem destacada"
	}

	return $content;
}
add_filter( 'admin_post_thumbnail_html', 'imagemdecapa_metabox_imagem_destacada', 10, 2 );


/**
 * Metaboxes personalizadas - funções callback
 *
 */
function imagemdecapa_sugeridas_meta_box( $post ) {

	$imagens = array(
		'por-do-sol.jpg'	=> 'Pôr do Sol no Campus Anglo da UFPel',
		'livros.jpg'		=> 'Novo espaço da Livraria UFPel',
		'discos.jpg'		=> 'Discoteca L. C. Vinholes – Centro de Artes / UFPel',
		'grande-hotel.jpg'	=> 'Grande Hotel – Pelotas, RS',
		'calourada.jpg'		=> 'Calourada 2015/1 – Campus Anglo da UFPel',
		'chorei.jpg'		=> 'Dia do Choro – Mercado Público de Pelotas'
	);

	wp_nonce_field( basename(__FILE__), "imagemdecapa-metabox-nonce" );

?>
		<p><strong>Clique acima para enviar sua imagem, ou selecione uma de nossas sugestões:</strong></p>

		<div id="imagemdecapa-sugestoes">

<?php
			foreach ( $imagens as $imagem => $descricao ) {
				$url = get_bloginfo('template_url') . '/imagens/capas/' . $imagem;
				echo '<img class="tooltip imagemdecapa-sugestao' . ( get_post_meta( $post->ID, 'imagemdecapa_externa', true ) == $url ? ' imagemdecapa-sugestao-selecionada' : '' ) . '" src="' . $url . '" title="' . $descricao . '">';
			}
?>
			<br><span class="description">Imagens: <a href="http://ccs2.ufpel.edu.br/wp/tag/imagem-da-semana/" target="_blank">Katia Helena Dias – CCS / UFPel</a></span>
		</div>
<?php
}

function imagemdecapa_propriedades_meta_box( $post ) {

?>
		<p>
			<input type="checkbox" name="barra"
			<?php if (get_post_meta($post->ID, 'barra', true) == 1) { echo "checked"; } ?>>
			<label for="barra">Exibir título sobre a imagem <a style="text-decoration: none" class="tooltip" title="Marque esta opção para exibir o título sobre a imagem.<br><br><img src='<?php bloginfo('template_url');?>/imagens/ajuda_imagem_de_capa.jpg'>" href="#">(?)</a></label>
		</p>

		<p>
			<label for="imagemdecapa_link">
				<strong>Link (opcional):</strong>
			</label>
			<input type="text" name="imagemdecapa_link" value="<?php echo get_post_meta( $post->ID, 'imagemdecapa_link', true ); ?>" size="50" placeholder="URL completo (não esqueça o http://)">
			<a style="text-decoration: none" class="tooltip" title="Se desejar que a imagem funcione como link, informe o endereço (URL) neste campo. Deixe em branco para remover o link." href="#">(?)</a>
		</p>

		<input type="hidden" name="imagemdecapa_externa" id="imagemdecapa_externa" value="<?php echo get_post_meta( $post->ID, 'imagemdecapa_externa', true ); ?>">

<?php
}


/**
 * Registra o CPT 'imagem-de-capa' e cria os menus no painel do admin
 *
 */
function imagemdecapa_init()
{
	register_post_type('imagem-de-capa',
			array(
					'labels' => array(
							'name' => 'Imagem de Capa',
							'singular_name' =>  'Imagem de Capa',
							'menu_name' => 'Imagem de Capa',
							'all_items' => 'Imagens de Capa',
							'add_new' => 'Adicionar nova',
							'add_new_item' => 'Adicionar nova imagem de capa',
							'edit_item' => 'Editar imagem de capa',
							'view_item' => 'Ver imagem de capa',
							'search_items' => 'Procurar imagem de capa',
							'not_found' => 'Nenhuma imagem de capa encontrada',
							'not_found_in_trash' => 'Nenhuma imagem de capa encontrada na lixeira'
					),
					'public' => true,
					'has_archive' => true,
					'supports' => array('thumbnail', 'title'), // 'editor'
					'exclude_from_search' => true,
					'menu_position' => 21,
					'menu_icon' => 'dashicons-format-image',
					'show_in_nav_menus' => false
			)
	);

}
add_filter( 'init', 'imagemdecapa_init' );
