<?php
function carrega_scripts_backend( $hook ) {

	global $post;

	/* Edição de posts personalizados */

	if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
		if ( $post->post_type == 'imagem-de-capa' ) {
			wp_enqueue_script( 'imagemdecapa-sugestoes', get_template_directory_uri().'/js/imagemdecapa-sugestoes.js', array('jquery') );
			wp_enqueue_style ( 'imagemdecapa-backend', get_template_directory_uri().'/css/imagemdecapa-backend.css' );
		}

		if ( in_array( $post->post_type, array( 'imagem-de-capa', 'banners', 'links_destacados' ) ) ) {
			wp_enqueue_script( 'tooltip-backend', get_template_directory_uri().'/js/tooltip-backend.js', array( 'jquery-ui-tooltip' ) );
			wp_enqueue_style ( 'tooltip-backend', get_template_directory_uri().'/css/tooltip-backend.css' );
		}
	}

	/* Opções do Tema */

	if ( $hook == 'appearance_page_theme-settings' ) {

		wp_enqueue_script( 'jquery-ui-draggable' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'banners-editor', get_template_directory_uri().'/js/banners-editor.js', array('jquery-ui-draggable', 'jquery-ui-sortable') );
		wp_enqueue_script( 'layout-editor', get_template_directory_uri().'/js/layout-editor.js', array('jquery-ui-draggable', 'jquery-ui-sortable') );

		wp_enqueue_style ( 'themeoptions', get_template_directory_uri().'/css/themeoptions.css' );

		global $inst_options;

?>
		<script type="text/javascript">
			var arrayIds = '<?php echo $inst_options["wpinst_modulos"]; ?>';
			var optionlist = ""; var arrayCat = []; var arrayWidgets; var ids = 0;
			var configuracao_modificada = false;

			<?php
	/*
				$categorias = get_terms( 'category', array( 'hide_empty' => 0 ) );
				foreach ( $categorias as $cat ) {
			?>
			arrayCat.push( "<?php echo $cat->term_id ?>-<?php echo $cat->name ?>" ); optionlist += "<option value='<?php echo $cat->term_id ?>'><?php echo $cat->name ?></option>";
			<?php
				} // end foreach
	*/
			?>

			window.onbeforeunload = function (e) {
				if (!configuracao_modificada)
					return;
				else
					return "ATENÇÃO! Suas modificações ainda não foram salvas.\nPara salvar, clique em 'Permanecer nesta página' e depois no botão Salvar Opções.";
			}
		</script>

<?php
	} // if ( $hook == 'appearance_page_theme-settings' )

} // end function carrega_scripts_backend

add_action('admin_enqueue_scripts', 'carrega_scripts_backend');

?>
