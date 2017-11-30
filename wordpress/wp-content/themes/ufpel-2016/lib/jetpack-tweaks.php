<?php

/**
 * Ajustes de serviços do Jetpack
 *
 */

/**
 * Desabilita metadados Open Graph do Jetpack, para evitar conflito com as tags fornecidas pelo Tema
 */
add_filter( 'jetpack_enable_open_graph', '__return_false' );


/**
 * Remove os botões de compartilhamento do 'excerpt' quando não for a query principal
 * (evita inserção dos botões no resumo do módulo Vitrine, por exemplo)
 *
 * https://jetpack.com/2013/06/10/moving-sharing-icons/
 *
 */
function ufpel_remove_jetpack_sharing( $query) {
    if ( ! $query->is_main_query() )
        remove_filter( 'the_excerpt', 'sharing_display', 19 );
}
add_action( 'pre_get_posts', 'ufpel_remove_jetpack_sharing' );

?>