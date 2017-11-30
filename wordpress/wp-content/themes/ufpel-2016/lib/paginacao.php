<?php
function paginacao($end_size = 2, $mid_size = 3, $prev_text = "<", $nex_text = ">") {
    global $wp_query;
    
    $big = 99999999;
    echo paginate_links(array(
    'base' => str_replace($big, '%#%', get_pagenum_link($big)),
    'format' => '?paged=%#%',
    'total' => $wp_query->max_num_pages,
    'current' => max(1, get_query_var('paged')),
    'show_all' => false,
    'end_size' => $end_size,
    'mid_size' => $mid_size,
    'prev_next' => true,
    'prev_text' => $prev_text,
    'next_text' => $nex_text,
    'type' => 'list'
    ));
}

function paginacao_quantidade($formato = "Exibindo resultado(s) %d a %d de %d encontrado(s).")
{
    global $wp_query;
 
    $postspag = get_option('posts_per_page');

    $numreg   = $wp_query->post_count;
    $paged    = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $totalreg = $wp_query->found_posts;
    
    if ($numreg < $postspag)
        $inicial = (($postspag * $paged)+1)-$postspag;
    else
        $inicial = (($numreg * $paged)+1)-$postspag;
    $final   = ($inicial + $numreg)-1;
       
    printf($formato, $inicial, $final, $totalreg);
}

?>
