<?php
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
