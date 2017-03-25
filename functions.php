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




function disable_wp_emojicons() {

  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );


function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}



// function for inserting Google Analytics into the wp_head
add_action('wp_footer', 'ga');
function ga() {
   if ( !is_user_logged_in() ) { // not for logged in users
?>
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-62451902-2', 'auto');
  ga('send', 'pageview');

</script>
<?php
   }
}