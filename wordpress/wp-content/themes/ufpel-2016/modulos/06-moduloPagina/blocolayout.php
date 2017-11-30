<?php

$args = array(
	'sort_order' => 'asc',
	'sort_column' => 'ID',
	'number' => '1'
);
$pages = get_pages( $args );

?>
<style>
.Pagina{
        height: 53px;
        background: url(<?php echo $modulourl; ?>/07.png) no-repeat 0 0;
        position: relative;
}
</style>
<li id="Pagina" class="Pagina" data-type='Pagina' data-title='Página estática' data-options='<?php if ( count( $pages ) ) echo $pages[0]->post_title; ?>:<?php if ( count( $pages ) ) echo $pages[0]->ID; ?>:n'>
    Página estática
    <div id="icon_help2"></div>
    <span id="help">?</span>
</li>