<?php
class ufpelImagemLink_widget extends WP_Widget {

	protected $widget_slug = 'imagemcomlink'; // usar o mesmo nome da pasta do widget

	protected $widget_defaults = array( 'title' => '',
						   				'link'  => 'http://',
						   				'target'=> '_self'
								 );

	function __construct() {
		parent::__construct(
			$this->get_widget_slug().'-ufpel',	// ID do widget
			'Imagem com link (UFPel)',	// Nome do widget (back-end)
			array( 				// Argumentos
				'classname' => $this->get_widget_slug().'-widget',	// Classe CSS
				'description' => 'Exibe uma imagem, com link opcional.', // Descrição (back-end)
				 )
		);

		// Enfileira estilos e scripts do admin (back-end)
//		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Enfileira estilos e scripts para o site (front-end)
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
//		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );
	}

	public function get_widget_slug() {
		return $this->widget_slug;
	}

	public function get_widget_defaults($item = null) {
		if ($item)
			return $this->widget_defaults[$item];
		else
			return $this->widget_defaults;
	}

	public function register_admin_scripts($hook) {
		if ($hook == "widgets.php") {
			 if(function_exists('wp_enqueue_media')) {
				wp_enqueue_media();
			}
			 // http://stackoverflow.com/questions/15277549/wp-enqueue-media-and-custom-post-types
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');

			wp_enqueue_script( $this->get_widget_slug().'-admin-script', get_template_directory_uri().'/widgets/'.$this->get_widget_slug().'/admin.js', array('jquery', 'media-editor') );
		}
	}

	public function register_widget_styles() {
		wp_enqueue_style( $this->get_widget_slug().'-widget-styles', get_template_directory_uri().'/widgets/'.$this->get_widget_slug().'/widget.css' );
	}

/*
  function imagenswidget_widget() {
	$widget_ops = array( 'classname' => $this->get_widget_slug(), 'description' => 'Crie conteudo com editor de texto.' );

	$this->WP_Widget( $this->get_widget_slug(), 'Imagens Widget', $widget_ops, $control_ops );
  }
*/

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

		if ($instance['title'] != "")
			echo $before_title . $instance['title'] . $after_title;
?>

		<div class="imagemcomlink-conteudo">

<?php
			if ($instance['link'] != "" && $instance['link'] != "http://") {
				echo "<a href='".$instance['link']."'";
				echo " target='".$instance['target']."' />";
			}

			echo "<img src='".$instance['image']."' />";

			if ($instance['link'] != "" && $instance['link'] != "http://")
				echo "</a>";
?>

		</div>

<?php
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
		  <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Titulo:', 'imagenswidget'); ?></label>
		  <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e('Link:', 'imagenswidget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $instance['link']; ?>" style="width:95%;" />
			<select id="destino" name="<?php echo $this->get_field_name( 'target' ); ?>">
				<option value="_self"
				<?php if ($instance['target'] == "_self")      { echo "selected"; } ?>
				>Abrir na mesma janela</option>
				<option value="_blank"
				<?php if ($instance['target'] == "_blank") { echo "selected"; } ?>
				>Abrir em nova janela</option>
			</select>
<?php
			$rand = rand(1, 9999999);
?>
			<div id="imagem_anexo_<?=$rand?>">
				<div>
<?php
				if ( ! empty( $instance['image'] ) ) {
					echo "<img src=\"".$instance['image']."\" alt=\"Imagem do widget\" />\n";
				}
?>
				</div>
				<input type="hidden" name="<?=$this->get_field_name( 'image' )?>" value="<?=$instance['image']?>" />
			</div>

		</p>
		<p>
<?php
			if ( ! empty( $instance['image'] ) )
				$input_imagem_widget = "Alterar imagem";
			else
				$input_imagem_widget = "Selecionar imagem";
?>
			<input type="button" id="<?=$rand?>" class="button button-primary upload_image_button" value="<?=$input_imagem_widget?>" />
		</p>

<?php
	} // function form


	/**
	 *  Limpa variáveis antes de salvar
	 */

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

} // class ufpelImagemLink_widget


add_action('widgets_init',
	create_function('', 'return register_widget("ufpelImagemLink_widget");')
);
?>