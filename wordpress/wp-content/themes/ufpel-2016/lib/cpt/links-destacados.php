<?php
/* Links destacados */


/**
 * Salva campos personalizados
 *
 */
function links_destacados_save_post( $post_id, $post, $update ) {

	/* Verificações de segurança e permissões */

	if ( ! isset( $_POST['linksdestacados-metabox-nonce'] ) || ! wp_verify_nonce( $_POST['linksdestacados-metabox-nonce'], basename(__FILE__) ) )
		return;

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if ( ! current_user_can( 'edit_post', $post_id ) )
		return;

	/* Salva dados na base */

	if ( isset( $_POST['link'] ) )
		update_post_meta( $post_id, 'link', sanitize_text_field( $_POST['link'] ) );
	else
		delete_post_meta( $post_id, 'link' );

	if ( isset( $_POST['destino'] ) )
		update_post_meta( $post_id, 'destino', sanitize_text_field( $_POST['destino'] ) );
	else
		delete_post_meta( $post_id, 'destino' );
}
add_action( 'save_post_links_destacados', 'links_destacados_save_post', 10, 3 );


/**
 * Adiciona metaboxes
 *
 */
function links_destacados_add_meta_boxes() {

	remove_meta_box( 'postimagediv', 'links_destacados', 'side' );
	add_meta_box( 'postimagediv', 'Imagem do Link', 'post_thumbnail_meta_box', 'links_destacados', 'normal');	// reposiciona metabox da imagem destacada no contexto normal
	add_meta_box( 'linkdestacado-propriedades', 'Propriedades do Link', 'links_destacados_propriedades_meta_box', 'links_destacados', 'normal' );	// adiciona metabox de propriedades

}
//add_action( 'do_meta_boxes', 'links_destacados_altera_metabox_imagem_destacada' );
add_action( 'add_meta_boxes_links_destacados', 'links_destacados_add_meta_boxes' );


/**
 * Personaliza conteúdo da metabox padrão da imagem destacada
 *
 */
function links_destacados_metabox_imagem_destacada( $content, $post_id ) {

	if ( get_post_type( $post_id ) == 'links_destacados' ) {

		$search = array( __( 'Set featured image' ), __( 'Remove featured image') );
		$replace = array( 'Inserir imagem do Link', 'Remover imagem do Link' );

		$content =
			'<p><span class="dashicons dashicons-info"></span> A imagem deve possuir as dimensões de <strong>212 x 130 pixels</strong>.</p>' .
			str_replace( $search, $replace, $content ) .
			'<p><strong>Clique acima para enviar sua imagem.</strong> Para exibir os links no site utilize o Widget <strong>Links Destacados</strong>.</p>';
	}

	return $content;
}
add_filter( 'admin_post_thumbnail_html', 'links_destacados_metabox_imagem_destacada', 10, 2 );


/**
 * Metabox de campos personalizados - função callback
 *
 */
function links_destacados_propriedades_meta_box( $post ) {

//	if (get_post_meta($post->ID, 'link', true) == "")
//		update_post_meta( $post->ID, 'link', sanitize_text_field( 'http://' ) );

	wp_nonce_field( basename(__FILE__), "linksdestacados-metabox-nonce" );

?>
		<p>
			<label for="link" style="width:40px; display:inline-block;"><b>URL:</b></label>
			<input type="text" id="link" name="link" value="<?php echo get_post_meta($post->ID, 'link', true); ?>" size="50" placeholder="não esqueça o http:// no início">
		</p>
		<p>
			<label for="destino" style="width:40px; display:inline-block;"><b>Abrir: </b></label>
			<select id="destino" name="destino">
				<option value=""
				<?php if ( get_post_meta($post->ID, 'destino', true ) == "" ) echo "selected"; ?>
				>Na mesma aba</option>
				<option value="_blank"
				<?php if ( get_post_meta($post->ID, 'destino', true ) == "_blank") echo "selected"; ?>
				>Em uma nova aba</option>
			</select>
		</p>
<?php
}


/**
 * Registra o CPT 'links_destacados' e cria os menus no painel do admin
 *
 */
function links_destacados_init()
{
	register_post_type('links_destacados',
			array(
					'labels' => array(
							'name' => 'Links Destacados',
							'singular_name' =>  'Links Destacados',
							'menu_name' => 'Links Destacados',
							'all_items' => 'Links Destacados',
							'add_new' => 'Adicionar novo',
							'add_new_item' => 'Adicionar novo link destacado',
							'edit_item' => 'Editar link destacado',
							'view_item' => 'Ver link destacado',
							'search_items' => 'Procurar links destacados',
							'not_found' => 'Nenhum link destacado encontrado',
							'not_found_in_trash' => 'Nenhum link destacado encontrado na lixeira'
					),
					'public' => true,
					'has_archive' => true,
					'supports' => array('thumbnail', 'title'), // 'page-attributes'
					'menu_position' => 23,
					'menu_icon' => 'dashicons-images-alt2',
					'show_in_nav_menus' => false
			)
	);

}
add_filter( 'init', 'links_destacados_init' );
