<?php
function psa_blog_ajax_pagination( $atts ){
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 5,
            'paged' => 1,
            'post_type' => 'post'
        ), $atts,'psa_pagination_blog'
    );
    $posts_per_page = intval($atts['posts_per_page']);
    $paged = intval($atts['paged']);
    $post_type = sanitize_text_field($atts['post_type']);
    $allpost  = '<div id="result_ajaxp">';
	$allpost .= psa_query_ajax_pagination( $post_type, $posts_per_page , $paged );
    $allpost .= '</div>';

    return $allpost;
}
add_shortcode('psa_pagination_blog', 'psa_blog_ajax_pagination');

function psa_query_ajax_pagination( $post_type = 'post', $posts_per_page = 5, $paged = 1){
	$posts_per_page = get_option('ajp_per_page');
    $args_svl = array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'post_status' => 'publish'
    );
	global $wp_query;
	$attrs = array(
		'posts' => json_encode( $wp_query->query_vars ),
		'posts_per_page' => $posts_per_page,
		'current_pages' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
		'max_pages' => $wp_query->max_num_pages
	);
    $q_svl = new WP_Query( $args_svl );
    $total_records = $q_svl->found_posts;
    $total_pages = ceil($total_records/$posts_per_page);
    if($q_svl->have_posts()):
        $allpost = '<div class="ajax_pagination" posts_per_page="'.$posts_per_page.'" post_type="'.$post_type.'">';
		$fade =0;
		$size = get_option('size_image');
        while($q_svl->have_posts()):$q_svl->the_post();
            $allpost .= '<div class="ajaxp_list_post fadeok'.$fade++.' '.$size.'">';
			if(get_option('show_image') && has_post_thumbnail()){
				$allpost .= '<div class="ajp-images"><a href="'.get_permalink().'">'.get_the_post_thumbnail().'</a></div>';
			}
            $allpost .= '<div class="ajp-content"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a> - <span>'.get_the_date().'</span></div>';
            $allpost .= '</div>';
        endwhile;
        $allpost .= '</div>';
		if(get_option('choice_pagination') == 'piala_pagination'){
			$allpost .= psa_paginate_function( $posts_per_page, $paged, $total_records, $total_pages);
		}else{
			$allpost .= '<div class="load_possts"><a href="#" data-attrs='.json_encode($attrs).'>Load More</a></div>';
		}
        $allpost .='<div class="loading_ajaxp"><div id="circularG"><div id="circularG_1" class="circularG"></div><div id="circularG_2" class="circularG"></div><div id="circularG_3" class="circularG"></div><div id="circularG_4" class="circularG"></div><div id="circularG_5" class="circularG"></div><div id="circularG_6" class="circularG"></div><div id="circularG_7" class="circularG"></div><div id="circularG_8" class="circularG"></div></div></div>';
    endif;wp_reset_query();
    return $allpost;
}