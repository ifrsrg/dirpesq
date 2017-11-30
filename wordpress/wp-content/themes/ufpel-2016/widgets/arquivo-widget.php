<?php

add_action( 'widgets_init', 'arquivoinst_widget' );

function arquivoinst_widget() {
  register_widget( 'arquivoinst_widget' );
}

class arquivoinst_widget extends WP_Widget {

  function __construct() {
	$widget_ops = array( 'classname' => 'arquivoinst', 'description' => 'Arquivo de posts do site agrupado por ano, e mensal para o ano corrente.' );

	parent::__construct( 'arquivoinst-widget', 'Arquivos Anuais (UFPel)', $widget_ops );
  }

  function widget( $args, $instance ) {
	extract( $args );

	//Our variables from the widget settings.
	$title = apply_filters('widget_title', $instance['title'] );


	echo $before_widget;

	// Display the widget title
	echo $before_title;
	  echo $instance["title"];
	echo $after_title;
?>
  <style type="text/css">
	#arquivo{
		width: 100%;
		margin-bottom: 10px;
		padding: 0;
	}

	#arquivo div{
		padding: 0;
	}

		#arquivo_list{
			font-weight: bold;
			color: #ccc;
			padding: 0 15px !important;
			width: 100%;
			height: 252px;
			position: relative;
			webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}

		#arquivo_list a {
			color: #666;
		}
		#arquivo_list a:hover {
			color: #0cf;
		}

		#arquivo_atual{
			float: left;
			height: 100%;
		}

			#arquivo_atual ul{
				list-style: none;
				display:table;
				height:100%;
				padding: 10px 0;
				box-sizing: border-box;
			}

			#arquivo_atual ul li{
				display:table-row;
				line-height: 18px;
				text-transform: uppercase;
			}

			.arqme{
				float: left;
			}

			.arqan{
				float: right;
				margin-left: 5px;
			}

		#arquivo_anteriores{
			float: right;
			height: 100%;
			padding-top: 10px !important;
			text-align: right;
			box-sizing: border-box;
			overflow: hidden;
		}

			#arquivo_anteriores ul{
				display:table;
				height:164px;
			}

			#arquivo_anteriores ul li{
				 display:table-row;
			}
  </style>
  <div id="arquivo">
	<div id="arquivo_list">
	  <div id="arquivo_atual">
		<ul>
		<?php
		  $meses = array('','jan','fev','mar','abr','mai','jun','jul','ago','set','out','nov','dez');
		  $anoatual = date("Y");
		  $mesatual = date("n");
		  for ($i = 12; $i > 0; $i--) {
			if ($i > $mesatual)
			  echo '<li><div class="arq"><div class="arqme">'.$meses[$i].'</div> <div class="arqan">'.$anoatual.'</div></div></li>';
			else
			  echo '<li><a href="'.get_bloginfo('url').'/'.$anoatual.'/'.sprintf("%02d",$i).'"><div class="arq"><div class="arqme">'.$meses[$i].'</div> <div class="arqan">'.$anoatual.'</div></div></a></li>';
		  }
		?>
		</ul>
	  </div>
	  <div id="arquivo_anteriores">
		<ul>
		<?php

			$oldestyear = date('Y');
			$args = array('orderby'=>'date','order'=>'ASC','posts_per_page'=>1,'ignore_sticky_posts'=>1);
			$oldestpost = get_posts($args);

			if (!empty($oldestpost)){
				$oldestyear = mysql2date('Y',$oldestpost[0]->post_date);
			}

			for ($i = $anoatual-1; $i >= $oldestyear; $i--) {
				echo '<li>';
				echo '<a href="'.get_bloginfo('url').'/'.$i.'">';
				echo $i.'</a></li>';
			}
		?>
		</ul>
	  </div>
	</div>
  </div>

<?php
	/* CODIGO WIDGET!!! */

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
	$defaults = array( 'title' => __('Arquivo', 'arquivoinst'));
	$instance = wp_parse_args( (array) $instance, $defaults ); ?>

	<?php //Widget Title: Text Input.?>
	<p>
	  <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Titulo:', 'arquivoinst'); ?></label>
	  <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />
	</p>

  <?php
  }
}

?>