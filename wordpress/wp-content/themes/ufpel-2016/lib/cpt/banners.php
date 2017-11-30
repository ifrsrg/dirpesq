<?php
/* Banners */


/**
 * Salva campos personalizados
 *
 */
function banners_save_post( $post_id, $post, $update ) {

	/* Verificações de segurança e permissões */

	if ( ! isset( $_POST['banners-metabox-nonce'] ) || ! wp_verify_nonce( $_POST['banners-metabox-nonce'], basename(__FILE__) ) )
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
add_action( 'save_post_banners', 'banners_save_post', 10, 3 );


/**
 * Adiciona metaboxes
 *
 */
function banners_add_meta_boxes() {

	remove_meta_box( 'postimagediv', 'banners', 'side' );
	add_meta_box( 'postimagediv', 'Imagem do Banner', 'post_thumbnail_meta_box', 'banners', 'normal' );	// reposiciona metabox da imagem destacada no contexto normal
	add_meta_box( 'banner-propriedades', 'Propriedades do Link', 'banners_propriedades_meta_box', 'banners', 'normal' );	// adiciona metabox de propriedades

}
//add_action( 'do_meta_boxes', 'banners_add_meta_boxes' );
add_action( 'add_meta_boxes_banners', 'banners_add_meta_boxes' );


/**
 * Personaliza conteúdo da metabox padrão da imagem destacada
 *
 */
function banners_metabox_imagem_destacada( $content, $post_id ) {

	if ( get_post_type( $post_id ) == 'banners' ) {

		$search = array( __( 'Set featured image' ), __( 'Remove featured image') );
		$replace = array( 'Inserir imagem do Banner', 'Remover imagem do Banner' );

		$content =
			'<p><span class="dashicons dashicons-info"></span> A imagem deve possuir as dimensões de <strong>192 x 132 pixels</strong>.</p>' .
			str_replace( $search, $replace, $content ) .
			'<p><strong>Clique acima para enviar sua imagem.</strong> Confira as imagens disponíveis para download em nosso <a href="https://docs.ufpel.edu.br/index.php/s/hZcRF4jnXt9DqHs" target="_blank">repositório de Banners</a>.</p>' .
			'<p><span class="dashicons dashicons-info"></span> Selecione os banners que serão exibidos no site através do menu <a href="themes.php?page=theme-settings&tab=layout">Aparência - Opções do Tema</a>, na aba <strong>Banners</strong>.</p>';
	}

	return $content;
}
add_filter( 'admin_post_thumbnail_html', 'banners_metabox_imagem_destacada', 10, 2 );


/**
 * Metabox de campos personalizados - função callback
 *
 */
function banners_propriedades_meta_box( $post ) {

//	if (get_post_meta($post->ID, 'link', true) == "")
//		update_post_meta( $post->ID, 'link', sanitize_text_field( 'http://' ) );

	wp_nonce_field( basename(__FILE__), "banners-metabox-nonce" );

?>

		<p>
			<label for="link" style="width:40px; display:inline-block;">
				<strong>URL:</strong>
			</label>
			<input type="text" id="link" name="link" value="<?php echo get_post_meta($post->ID, 'link', true); ?>" size="50" placeholder="não esqueça o http:// no início">
		</p>
		<p>
			<label for="destino" style="width:40px; display:inline-block;">
				<strong>Abrir:</strong>
			</label>
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
 * Registra o CPT 'banners' e cria os menus no painel do admin
 *
 */
function banners_init() {

	register_post_type('banners',
		array(
			'labels' => array(
				'name' => 'Banners',
				'singular_name' =>  'Banner',
				'menu_name' => 'Banners',
				'all_items' => 'Banners',
				'add_new' => 'Adicionar novo',
				'add_new_item' => 'Adicionar novo banner',
				'edit_item' => 'Editar banner',
				'view_item' => 'Ver banner',
				'search_items' => 'Procurar banners',
				'not_found' => 'Nenhum banner encontrado',
				'not_found_in_trash' => 'Nenhum banner encontrado na lixeira'
			),
		'public' => true,
		'has_archive' => true,
		'supports' => array('thumbnail', 'title'),
		'exclude_from_search' => true,
		'menu_position' => 22,
		'menu_icon' => 'dashicons-slides',
		'show_in_nav_menus' => false
		)
	);

}
add_filter( 'init', 'banners_init' );
