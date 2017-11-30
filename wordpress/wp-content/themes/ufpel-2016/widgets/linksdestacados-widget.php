<?php

add_action( 'widgets_init', 'linksdestacados_widget' );

function linksdestacados_widget() {
	register_widget( 'linksdestacados_widget' );
}

class linksdestacados_widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'linksdestacados', 'description' => 'Exibe os Links Destacados do site' );

		parent::__construct( 'linksdestacados-widget', 'Links Destacados (UFPel)', $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

//		$title = apply_filters('widget_title', $instance['title'] );

		echo $before_widget;

		echo $before_title;
		echo $instance['title'];
		echo $after_title;
?>
		<script>
			jQuery(document).ready(function($) {

				var ldPosAtual = 0;

				var ldQuantBanners = $("#ldQuantBanners").val();
				var ldTamanhoTotal = ldQuantBanners*215;

				ldSlideAtual = 0;

				var ldDelayStep = <?php echo $instance["slide_espera"]*1000; ?>;

				function slidesteps(){
					ldPosAtual -= 215;

					if (ldSlideAtual == ldQuantBanners) {
						ldPosAtual = 0;
						ldSlideAtual = 1;
						$("#linksdestacados_imgs").css("left", "0");
						ldPosAtual -= 215;
						$("#linksdestacados_imgs").animate({left:ldPosAtual+'px'});
					}
					else
					{
						ldSlideAtual++;
						$("#linksdestacados_imgs").animate({left:ldPosAtual+'px'});
					}
				}

				var ldTimer = setInterval(slidesteps, ldDelayStep);

				$('#linksdestacados').mouseover(function(){
					clearInterval(ldTimer);
				}).mouseout(function(){
					slidesteps();
					ldTimer = setInterval(slidesteps, ldDelayStep);
				});

				$("#linksdestacados_imgs").css("width", (ldTamanhoTotal*2)+"px");

			});

		</script>

	<div id="linksdestacados" style="height: 130px; width: 100%; overflow: hidden;position:relative; padding:0">
		 <div id="linksdestacados_imgs" style="height: 130px;display: inline;position:absolute; background-color: blank; width: 9000px; padding:0; overflow: hidden;">
		  <?php
				$args = array(
				   'post_type' => 'links_destacados',
				);

				$the_query = new WP_Query( $args );

				if ( $the_query->have_posts() ) {
					$c=0;
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						echo "<a href='".get_post_meta($the_query->post->ID, 'link', true)."'
						target='".get_post_meta($the_query->post->ID, 'destino', true)."'>
						<img src='".wp_get_attachment_url(get_post_thumbnail_id($the_query->post->ID))."'></a>";
						if ($c==0) {
							$primeira_imagem = "<a href='".get_post_meta($the_query->post->ID, 'link', true)."'
							target='".get_post_meta($the_query->post->ID, 'destino', true)."'>
							<img src='".wp_get_attachment_url(get_post_thumbnail_id($the_query->post->ID))."'></a>";
						}
						$c++;
					}
				}
				echo $primeira_imagem;
				wp_reset_postdata();
				?>
				<input type="hidden" id="ldQuantBanners" value="<?php echo $c; ?>">
		</div>
	</div>

<?php

		echo $after_widget;

	} // function widget()


	// Update the widget

	function update( $new_instance, $old_instance ) {

		return $new_instance;
	}


	// Backend

	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('Links Destacados', 'slider'), 'slide_vel' => '10', 'slide_modo' => 'etapas', 'slide_espera' => '3');
		$instance = wp_parse_args( (array) $instance, $defaults );
?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Titulo:', 'linksdestacados'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />
		</p>

<?php /*
		<p>
			<label for="<?php echo $this->get_field_id( 'slide_modo' ); ?>">Modo de rotação:</label><br />
			<input type="radio" name="<?php echo $this->get_field_name( 'slide_modo' ); ?>" value="continuo" <?php if ($instance['slide_modo'] == "continuo") echo "checked"; ?> /> Contínuo &nbsp;
			<input type="radio" name="<?php echo $this->get_field_name( 'slide_modo' ); ?>" value="etapas" <?php if ($instance['slide_modo'] == "etapas") echo "checked"; ?> /> Em etapas
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'slide_vel' ); ?>">Intervalo de deslize (modo contínuo):</label><br />
			<input type="number" id="<?php echo $this->get_field_id( 'slide_vel' ); ?>" name="<?php echo $this->get_field_name( 'slide_vel' ); ?>" value="<?php echo $instance['slide_vel']; ?>" style="width:23%;" /> milissegundos
		</p>
*/ ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'slide_espera' ); ?>">Espera entre cada rotação:</label><br />
			<input type="number" id="<?php echo $this->get_field_id( 'slide_espera' ); ?>" name="<?php echo $this->get_field_name( 'slide_espera' ); ?>" value="<?php echo $instance['slide_espera']; ?>" style="width:23%;" /> segundos
		</p>

<?php
	} // function form()

} // class linksdestacados_widget

?>