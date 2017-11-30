<?php

// REGISTRA ÁREAS DE WIDGETS
if ( function_exists('register_sidebar') ) {
  register_sidebar(array(
	  'id' => 'sidebar-1',
	  'name' => 'Barra lateral',
	  'description'   => 'Widgets nesta área aparecerão na barra lateral do site',
	  'class'         => '',
	  'before_widget' => '<li id="%1$s" class="widget %2$s">',
	  'after_widget' => '</li>',
	  'before_title'  => '<h2 class="content_header corFundo">',
	  'after_title'   => '</h2>'
	));
	register_sidebar(array(
	  'id' => 'links2',
	  'name' => 'Rodapé central',
	  'description'   => 'Widgets nesta área aparecerão na coluna central do rodapé',
	  'before_widget' => '',
	  'after_widget' => '',
	  'before_title'  => '<h2 class="corTexto">',
	  'after_title'   => '</h2>'
	));
	register_sidebar(array(
	  'id' => 'links3',
	  'name' => 'Rodapé direita',
	  'description'   => 'Widgets nesta área aparecerão na coluna direita do rodapé',
	  'before_widget' => '',
	  'after_widget' => '',
	  'before_title'  => '<h2 class="corTexto">',
	  'after_title'   => '</h2>'
	));
}

?>
