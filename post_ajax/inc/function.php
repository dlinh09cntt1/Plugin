<?php
/*Hàm phân trang*/
function psa_paginate_function($item_per_page, $current_page, $total_records, $total_pages)
{
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination">';

        $right_links = $current_page + 3;
        $previous = $current_page - 3; //previous link
        $next = $current_page + 1; //next link
        $first_link = true; //boolean var to decide our first link

        if($current_page > 1){
            $previous_link = ($previous==0)?1:$previous;
            $pagination .= '<li class="first"><a href="#" data-page="1" title="First">&laquo;</a></li>'; //first link
            $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>'; //previous link
            for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                if($i > 0){
                    $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
                }
            }
            $first_link = false; //set first link to false
        }

        if($first_link){ //if current active page is first link
            $pagination .= '<li class="first active">'.$current_page.'</li>';
        }elseif($current_page == $total_pages){ //if it's the last active link
            $pagination .= '<li class="last active">'.$current_page.'</li>';
        }else{ //regular current link
            $pagination .= '<li class="active">'.$current_page.'</li>';
        }

        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){
            $next_link = ($i > $total_pages)? $total_pages : $i;
            $pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">&gt;</a></li>'; //next link
            $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
        }

        $pagination .= '</ul>';
    }
    return $pagination; //return pagination links
}
/*Xử lý Ajax*/
add_action( 'wp_ajax_LoadPostPagination', 'LoadPostPagination_init' );
add_action( 'wp_ajax_nopriv_LoadPostPagination', 'LoadPostPagination_init' );
function LoadPostPagination_init() {
    $posts_per_page = intval($_POST['posts_per_page']);
    $paged = intval($_POST['data_page']);
    $post_type = sanitize_text_field($_POST['post_type']);
    $allpost = psa_query_ajax_pagination( $post_type, $posts_per_page , $paged );
    echo $allpost;
    exit;
}
/*Ajax Next-Prev*/
function kbnt_ajax_pagination() {
    $query_vars = json_decode( stripslashes( $_POST['query_vars'] ), true );
    $query_vars['paged'] = $_POST['page'];
    $posts = new WP_Query( $query_vars );
    $GLOBALS['wp_query'] = $posts;
	$postn = 0;
    if( ! $posts->have_posts() ) {
        get_template_part( 'content', 'none' );
    }
    else {
        while ( $posts->have_posts() ) {
            $posts->the_post();
			include( locate_template( 'content-posts.php' ) );
        }
    }
	//kbnt_pagination();
	echo '<div class="col-xs-24" id="pagination">';
		echo '<div id="pagination">';
		echo '<div class="next-posts">'.next_post_link('%link', 'Newer entry &gt;').'</div>';
		echo '<div class="prev-posts">'.previous_post_link('%link', '&lt; Older entry').'</div>';
		echo '</div>';
	echo '</div>';
    die();
}
add_action( 'wp_ajax_nopriv_ajax_pagination', 'kbnt_ajax_pagination' );
add_action( 'wp_ajax_ajax_pagination', 'kbnt_ajax_pagination' );
/*Ajax Load More*/
function piala_loadMores_init(){
	$args = json_decode( stripslashes( $_POST['attrs'] ), true );
	$args['paged'] = $_POST['page'] + 1;
	$args['post_status'] = 'publish';
	$args['posts_per_page'] = intval($_POST['posts_per_page']);
	query_posts( $args );
	if( have_posts() ) :
		while( have_posts() ): the_post();?>
			<div class="ajax_pagination">
				<?php
				$fade =0;
				$size = get_option('size_image');?>
				<div class="ajaxp_list_post fadeok<?php echo esc_attr($fade++);?> <?php echo esc_attr($size);?>">
				<?php
				if(get_option('show_image') && has_post_thumbnail()){?>
					<div class="ajp-images"><a href="<?php the_permalink();?>"><?php the_post_thumbnail();?></a></div>
				<?php }?>
					<div class="ajp-content"><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a> - <span><?php echo esc_attr(get_the_date());?></span></div>
				</div>
			</div>
			<?php
		endwhile;
	endif;
	die();
}
add_action('wp_ajax_piala_loadMores','piala_loadMores_init');
add_action('wp_ajax_nopriv_piala_loadMores','piala_loadMores_init');
