<?php

/* Widget Manchete  */


class ufpelManchete_widget extends WP_Widget {

	protected $widget_slug = 'manchete';	// usar o mesmo nome da pasta do widget

	protected $widget_defaults = array(		// valores default para o front-end
									'title' 	=> 'Manchete',
									'tipo'		=> 'post',
									'cat_id'	=> '',
									'post_id'	=> '',
									'page_id'	=> ''
								 );

	/**
	 *  Construtor e funções de inicialização
	 */

	function __construct() {
		parent::__construct(
				$this->get_widget_slug().'-ufpel',	// ID do widget
				'Manchete (UFPel)',	// nome do widget
				array( 					// argumentos
					'classname' => $this->get_widget_slug().'-widget', // nome da classe CSS
					'description' => 'Exibe o título, com link, de uma página do site ou do post mais recente da categoria selecionada.',
					 )
		);

		// Enfileira estilos e scripts do admin (back-end)
//		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
//		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Enfileira estilos e scripts para o site (front-end)
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
//		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

	} // construct()


	public function get_widget_slug() {
		return $this->widget_slug;
	}

	public function get_widget_defaults($item = null) {
		if ($item)
			return $this->widget_defaults[$item];
		else
			return $this->widget_defaults;
	}

	public function register_admin_styles() {
		wp_enqueue_style( $this->get_widget_slug().'-admin-styles', get_template_directory_uri().'/widgets/'.$this->get_widget_slug().'/admin.css' );
	}
	public function register_admin_scripts() {
		wp_enqueue_script( $this->get_widget_slug().'-admin-script', get_template_directory_uri().'/widgets/'.$this->get_widget_slug().'/admin.js' );
	}
	public function register_widget_styles() {
		wp_enqueue_style( $this->get_widget_slug().'-widget-styles', get_template_directory_uri().'/widgets/'.$this->get_widget_slug().'/widget.css' );
	}
	public function register_widget_scripts() {
		wp_enqueue_script( $this->get_widget_slug().'-widget-script', get_template_directory_uri().'/widgets/'.$this->get_widget_slug().'/widget.js' );
	}



	/**
	 *  Front-end
	 */

	public function widget( $args, $instance ) {

		extract( $args ); // gera variáveis $before_widget, $before_title, etc..
		$instance = wp_parse_args( (array) $instance, $this->get_widget_defaults() );	// aplica defaults
		$instance['title'] = apply_filters( 'widget_title', $instance['title'] );	// http://codex.wordpress.org/Function_Reference/apply_filters

		echo $before_widget;
//		echo $before_title . $instance['title'] . $after_title;


		// código específico do widget -- início

		if ($instance['title'])	// se título em branco, não exibe barra
			echo $before_title . $instance['title'] . $after_title;

		$args = "";
		if ( $instance['tipo'] == 'page' && ! empty( $instance['page_id'] ) )
			$args = array( 'posts_per_page' => 1, 'page_id' => $instance['page_id']);
		elseif ( $instance['tipo'] == 'post' && ! empty( $instance['cat_id'] ) )
			$args = array( 'posts_per_page' => 1, 'cat' => $instance['cat_id']);
		elseif ( $instance['tipo'] == 'single' && ! empty( $instance['post_id'] ) )
			$args = array( 'p' => $instance['post_id'] );

		if ($args) {
			$query = new WP_Query($args);

			if ($query->have_posts()) {
				$query->the_post();
?>

			<div class="manchete-conteudo">
				<a href="<?php the_permalink() ?>" class="corTexto"><?php the_title(); ?></a>
			</div>

<?php
			}

			wp_reset_postdata();

		}

		// código específico do widget -- fim


		echo $after_widget;

	} // function widget


	/**
	 *  Back-end
	 */

	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->get_widget_defaults() );
?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Título:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />
		</p>

		<p>
			Exibir:<br />
			<input type="radio" id="<?php echo $this->get_field_id( 'tipo' ).'-post'; ?>" name="<?php echo $this->get_field_name( 'tipo' ); ?>" value="post" <?php if ($instance['tipo'] == "post") echo "checked"; ?> /> Último post de:
<?php
			$args = array( 'echo' => 0, 'selected' => $instance['cat_id'], 'name' => $this->get_field_name('cat_id'), 'id' => $this->get_field_name('cat_id') );
			$select = wp_dropdown_categories( $args );
			$select = preg_replace("#<select([^>]*)>#", "<select$1 onchange=\"document.getElementById('".$this->get_field_id( 'tipo' ).'-post'."').checked = 'checked';\">", $select);
			echo $select;
?>
		<p>
			<input type="radio" id="<?php echo $this->get_field_id( 'tipo' ).'-page'; ?>" name="<?php echo $this->get_field_name( 'tipo' ); ?>" value="page" <?php if ($instance['tipo'] == "page") echo "checked"; ?> /> Página:
<?php
			$args = array( 'echo' => 0, 'selected' => $instance['page_id'], 'name' => $this->get_field_name('page_id'), 'id' => $this->get_field_name('page_id') );
			$select = wp_dropdown_pages( $args );
			$select = preg_replace("#<select([^>]*)>#", "<select$1 onchange=\"document.getElementById('".$this->get_field_id( 'tipo' ).'-page'."').checked = 'checked';\">", $select);
			echo $select;
?>
		</p>

		<p>
			<input type="radio" id="<?php echo $this->get_field_id( 'tipo' ).'-single'; ?>" name="<?php echo $this->get_field_name( 'tipo' ); ?>" value="single" <?php if ($instance['tipo'] == "single") echo "checked"; ?> /> ID do Post:
			<input type="text" id="<?php echo $this->get_field_id( 'post_id' ).'-single'; ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>" value="<?php echo $instance['post_id']; ?>" onfocus="document.getElementById('<?php echo $this->get_field_id( 'tipo' ).'-single'; ?>').checked = 'checked';" />
		</p>

<?php
	} // function form


	/**
	 *  Limpa variáveis antes de salvar
	 */

	public function update( $instance, $old_instance ) {

		$instance['title'] = strip_tags( $instance['title'] );

		return $instance;
	}

} // class ufpelManchete_widget


add_action('widgets_init',
     create_function('', 'return register_widget("ufpelManchete_widget");')
);

?>