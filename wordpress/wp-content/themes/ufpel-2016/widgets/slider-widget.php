<?php

add_action( 'widgets_init', 'slider_widget' );


function slider_widget() {
  register_widget( 'Slider_widget' );
}

class Slider_widget extends WP_Widget {

  function __construct() {
	$widget_ops = array( 'classname' => 'slider', 'description' => 'Galeria rotativa com os últimos 4 posts da categoria selecionada.' );

	parent::__construct( 'slider-widget', 'Destaques por categoria (UFPel)', $widget_ops );
  }

  function widget( $args, $instance ) {
	extract( $args );

	global $inst_options;

	if( !$instance["title"] ) {
	  $category_info = get_category($instance["cat"]);
	  $instance["title"] = $category_info->name;
	}

	//Our variables from the widget settings.
	$title = apply_filters('widget_title', $instance['title'] );

	// Get array of post info.
	$cat_posts = new WP_Query(
	  "showposts=4".
	  "&cat=" . $instance["cat"] // .
//	  "&orderby=" . $sort_by .
//	  "&order=" . $sort_order
	);

	echo $before_widget;

	// Display the widget title
	echo $before_title;
	if( $instance["title_link"] )
	  echo '<a href="' . get_category_link($instance["cat"]) . '">' . $instance["title"] . '</a>';
	else
	  echo $instance["title"];
	echo $after_title;

?>
	<style>
	  .Slider_widget{position: relative; height: 280px; padding: 0 !important; background-color: transparent !important;}
	  .Slider_widget div, .Slider_widget ul {padding: 0; background-color: transparent;}

		.widget_slider_selector{position: absolute; top: -29px; left: 129px; height: 20px; width: 78px;}
			.widget_slider_selector ul{padding: 2px 0 !important; box-sizing: border-box;}
			.widget_slider_selector ul li{float: left; margin: 0 2px !important; padding: 8px 7px; font-size: 155%; font-weight: bold; color: #CCCCCC; text-align: center; cursor: pointer; background: url("<?php bloginfo('template_url'); ?>/imagens/icones/bt_off.png") no-repeat; width: 1px;}
		.widget_slider_selected{transition:.1s ease-out; -webkit-transition:.1s ease-out; color: #FFFFFF !important; background: url("<?php bloginfo('template_url'); ?>/imagens/icones/bt_on.png") no-repeat !important;}
		.widget_slider_panel{position: absolute; visibility: hidden;}
		.widget_slider_content{position: absolute; top: 151px; width: 212px;}
			.widget_slider_thumb{position: absolute; top: 0; left: 0px; overflow: hidden; height: 159px; width: 212px;}
				.widget_slider_thumb img{width: 212px; height: auto;}
			.widget_slider_titulo{position: absolute; padding-top: 2px; border-bottom: 5px solid #ccc; transition: .3s ease-out;padding-bottom: 6px; margin: 10px 0; font-size: 135%; font-weight: bold; color: #666666; max-height: 53px; overflow: hidden;}
			.widget_slider_titulo a{color: #666666;}
/*			.widget_slider_titulo a:hover{color: #00CCFF;} */
			.widget_slider_titulo_box {height: 8px;}
			.widget_slider_titulo:hover { /*color: #00CCFF;*/ border-bottom-width: 8px; border-bottom-style: solid; /* #00CCFF */;transition: .3s ease-out;}
			.widget_slider_resumo{position:: absolute; padding-top: 57px; vertical-align: bottom; color: #333333;text-align: justify;font-size: 10px;}


	</style>

	<div class="Slider_widget">
	  <div class="widget_slider_selector">
		<ul>
		  <?php
			for($i=0; $i < 4; $i++) {
			  echo '<li data-widget_slider_panel="widget_slider_panel_'.($i+1).'"';
			  if ($i == 0)
				echo ' class="widget_slider_selected"';
			  echo "></li>\n";
			}
		  ?>
		</ul>
	  </div>
<?php
	$i = 0;
	while ( $cat_posts->have_posts() ){
	  $cat_posts->the_post();
	?>

	<div id="widget_slider_panel_<?php echo $i+1; ?>" class="widget_slider_panel">
	  <div class="widget_slider_thumb">
		<a href="<?php the_permalink() ?>">
		<?php
			//the_post_thumbnail("medium");
			$thumb = get_the_post_thumbnail( $cat_posts->post, 'medium' );
			if ( empty( $thumb ) )
				$thumb = '<img src="' . $inst_options['wpinst_ogimg'] . '">';
			echo $thumb;
		?>
		</a>
	  </div>

	  <div class="widget_slider_content">
		  <div style="position:relative">
			<div class="widget_slider_titulo_box">
			  <div class="widget_slider_titulo">
				<a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
			  </div>
		</div>

			<div class="widget_slider_resumo">
			  <?php echo wpe_excerpt('wpe_excerptlength_normal'); ?>
			</div>
		  </div>
	  </div>
	</div>


	<?php
	$i = $i + 1;
	}
	wp_reset_query();
?>
	</div>

	<script>

	jQuery(document).ready(function($) {

		function trocar() {
	    	a = $('.widget_slider_selector').find(".widget_slider_selected");
			if( a.next().length != 0 ){
				a = a.next();
			}else{
				a = $('.widget_slider_selector li').first();
			}
			a.click();
	    }

		$('#widget_slider_panel_1').css('visibility', 'visible');

		$('.widget_slider_selector ul li').click(function() {
			if (!$(this).hasClass('widget_slider_selected')) {

				teste = "#"+($(this).data('widget_slider_panel'));
				$('.widget_slider_selected').removeClass('widget_slider_selected');
				$(this).addClass('widget_slider_selected');
				$('.Slider_widget').find(teste).css('display', 'none');
				$('.Slider_widget').find(teste).css('visibility', 'visible');
				$('.widget_slider_panel').fadeOut();

				$('.Slider_widget').find(teste).fadeIn();

				if ($(teste+" .widget_slider_titulo").text().length>85) {
					$('.widget_slider_resumo').animate({'padding-top': 74});
				}
				else {
					$('.widget_slider_resumo').animate({'padding-top': 57});
				}
			}
		});

	    var myTimer = setInterval(trocar, 5000);

		$('.Slider_widget').mouseover(function(){
		    clearInterval(myTimer);
		 }).mouseout(function(){
		    myTimer = setInterval(trocar, 5000);
		 });

		teste = "#"+($('.widget_slider_selector ul li').first().data('widget_slider_panel'));
		if ($(teste+" .widget_slider_titulo").text().length>85) {
			$('.widget_slider_resumo').animate({'padding-top': 74});
		}
		else {
			$('.widget_slider_resumo').animate({'padding-top': 57});
		}


        $('.widget_slider_titulo').hover(function() {
        	$(this).addClass('corBorda');
    		$(this).find('a').addClass('corTexto');
    	},function() {
        	$(this).removeClass('corBorda');
    		$(this).find('a').removeClass('corTexto');
    	});

	});

	</script>

<?php
	echo $after_widget;
  }

  //Update the widget

  function update( $new_instance, $old_instance ) {
	$instance = $old_instance;

	//Strip tags from title and name to remove HTML
	//$instance['title'] = strip_tags( $new_instance['title'] );

	return $new_instance;
  }


  function form( $instance ) {

	//Set up some default widget settings.
	$defaults = array( 'title' => 'Destaques', 'title_link' => true, 'cat' => '' );
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	<p>
	  <label for="<?php echo $this->get_field_id( 'title' ); ?>">Título:</label>
	  <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />
	</p>

	<p>
	  <label for="<?php echo $this->get_field_id("title_link"); ?>">
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("title_link"); ?>" name="<?php echo $this->get_field_name("title_link"); ?>"<?php checked( (bool) $instance["title_link"], true ); ?> />
		Usar link para a categoria no título?
	  </label>
	</p>

	<p>
	  <label>
		Categoria:
		<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("cat"), 'selected' => $instance["cat"], 'orderby' => 'name' ) ); ?>
	  </label>
	</p>

  <?php
  }
}

?>