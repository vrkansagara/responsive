<?php

include_once  __DIR__.'/compress.php';

add_action( 'init', 'create_post_type' );
function create_post_type() {
    register_post_type( 'beer',
        array(
            'labels' => array(
                'name' => __( 'Story' ),
                'singular_name' => __( 'Story' )
            ),
            'public' => true,
            'hierarchical' => false,
            'show_in_admin_bar' => true,
            'can_export' => true,
            'menu_position' => 2,
			'menu_icon' => 2,
            'has_archive' => true,
			'rewrite' => 'story',
			'capability_type'     => 'page',
            'taxonomies'          => array( 'category','tag' ),
            'supports' => array(
                'title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'thumbnail', 'custom-fields','post-formats'
            )
        )
    );
	flush_rewrite_rules();
}
function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}
function setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
// Remove issues with prefetching adding extra views
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);









// This code alwayse be the last in function.php file.
/**
 * Modify final html output before sending to the browser for rendering process.
 * @param $buffer
 * @return mixed
 */
function callback($buffer) {
    $buffer = getCompressedOutPut($buffer);
    // modify buffer here, and then return the updated code
    return $buffer;
}

function buffer_start() { ob_start("callback"); }
function buffer_end() { ob_end_flush(); }
add_action('wp_loaded', 'buffer_start');
add_action('shutdown', 'buffer_end');