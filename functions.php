<?php

if ( ! defined('DDVASH_CHILD_USES_LESS') ) define('DDVASH_CHILD_USES_LESS', false);

function __ddvash_after_theme_setup() {
    require_once(dirname(__FILE__).'/lib/wp-less/bootstrap-for-theme.php');
    WPLessPlugin::getInstance()->registerHooks();
}

if ( ! function_exists('ddvash_after_theme_setup') ):
function ddvash_after_theme_setup() {

    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );
    
    // This theme uses post thumbnails
    add_theme_support( 'post-thumbnails' );
    
    // This theme allows users to set a custom background
    add_custom_background();
    
    register_nav_menus( array(
        'primary' => __( 'Primary Navigation', 'ddvash' ),
    ) );
    
    add_option('ddvash_layout_global_width', '900px');
    add_option('ddvash_layout_sidebar_location', 'right');
    add_option('ddvash_layout_sidebar_width', '200px');
    
    add_option('ddvash_layout_header_image_show', false);
    add_option('ddvash_layout_header_image_width', (int)get_option('ddvash_layout_global_width'));
    add_option('ddvash_layout_header_image_height', '100');
    
    if ( get_option('ddvash_layout_header_image_show') ) {
        add_custom_image_header( '', 'twentyten_admin_header_style' );
    }

    define( 'HEADER_IMAGE_WIDTH', apply_filters( 'ddvash_header_image_width', get_option('ddvash_layout_header_image_width') ) );
    define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'ddvash_header_image_height', get_option('ddvash_layout_header_image_height') ) );
    
    set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );
    define( 'NO_HEADER_TEXT', true );
    
    if ( ! is_admin() ) {
        if ( DDVASH_CHILD_USES_LESS ) {
            wp_enqueue_style('ddvash_less', get_bloginfo('stylesheet_directory').'/style.less');
        } else {
            wp_enqueue_style('ddvash_less', get_bloginfo('template_directory').'/style.less');
        }
    }
    
}
endif;

add_action( 'after_setup_theme', '__ddvash_after_theme_setup' );
add_action( 'after_setup_theme', 'ddvash_after_theme_setup' );

function ddvash_widgets_init() {

    register_sidebar( array(
        'name' => __( 'Primary Widget Area', 'ddvash' ),
        'id' => 'primary-widget-area',
        'description' => __( 'The primary widget area', 'ddvash' ),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
    
    register_sidebar( array(
        'name' => __( 'Secondary Widget Area', 'ddvash' ),
        'id' => 'secondary-widget-area',
        'description' => __( 'The secondary widget area', 'ddvash' ),
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

}

add_action( 'widgets_init', 'ddvash_widgets_init' );

function ddvash_admin_menu() {
    
    if ( isset($_GET['page']) and $_GET['page'] == 'ddvash' ) {
        if ( $_POST['rebuild-css'] ) {
            WPLessPlugin::getInstance()->processStylesheets(true);
        }
        
    }
        
    add_menu_page(__('ddVash Theme','ddvash'), __('ddVash Theme','ddvash'), 'manage_options', 'ddvash', 'ddvash_admin_home_page' );
    add_submenu_page('ddvash', __('ddVash Theme - About','ddvash'), __('About','ddvash'), 'manage_options', 'ddvash', 'ddvash_admin_home_page');
    add_submenu_page('ddvash', __('ddVash Theme - Layout','ddvash'), __('Layout','ddvash'), 'manage_options', 'ddvash-layout', 'ddvash_admin_layout_page');

    register_setting( 'ddvash-layout-group', 'ddvash_layout_global_width' );
    register_setting( 'ddvash-layout-group', 'ddvash_layout_header_image_width' );
    register_setting( 'ddvash-layout-group', 'ddvash_layout_header_image_height' );
    register_setting( 'ddvash-layout-group', 'ddvash_layout_header_image_show' );
    register_setting( 'ddvash-layout-group', 'ddvash_layout_sidebar_location' );
    register_setting( 'ddvash-layout-group', 'ddvash_layout_sidebar_width' );

}

add_action('admin_menu', 'ddvash_admin_menu');

function ddvash_wp_head_action() {
    
    $global_width = get_option('ddvash_layout_global_width');
    $sidebar_width = get_option('ddvash_layout_sidebar_location') == 'none' ?
        0 : get_option('ddvash_layout_sidebar_width');
        
    $content_width = preg_match('/%/', $global_width) ?
        null : ((int)$global_width - (int)$sidebar_width) . 'px';

        
    if ( defined('DDVASH_NO_SIDEBAR_PAGE_TEMPLATE') ) {
        $sidebar_width = null;
    }
?>
<style type="text/css">
#shell-container { width: <?php echo $global_width; ?>; }
<?php if ( $content_width ): ?>
#content img { max-width: <?php echo $content_width; ?>; }
<?php endif; ?>
<?php if ( $sidebar_width ): ?>
#content-container { margin-right: -<?php echo $sidebar_width; ?>; }
#content { margin-right: <?php echo $sidebar_width; ?>; }
#sidebar-container { width: <?php echo $sidebar_width; ?>; }
<?php endif; ?>
</style>
<?php
}
add_action('wp_head', 'ddvash_wp_head_action');

function ddvash_admin_home_page() {
    get_template_part('admin-templates/home');
}

function ddvash_admin_layout_page() {
    get_template_part('admin-templates/layout');
}

function ddvash_body_class_filter($classes, $class = null) {
    
    switch(get_option('ddvash_layout_sidebar_location')) {
        case 'right':
            $classes[] = ' sidebar-right ';
            break;
        case 'left':
            $classes[] = ' sidebar-left ';
            break;
        case 'none':
            $classes[] = ' sidebar-none ';
            break;
    }
    
    $classes[] = $class;
    
    return $classes;
    
}

add_filter('body_class', 'ddvash_body_class_filter');

if ( ! function_exists( 'ddvash_posted_on' ) ) :
function ddvash_posted_on() {
    printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'ddvash' ),
        'meta-prep meta-prep-author',
        sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
            get_permalink(),
            esc_attr( get_the_time() ),
            get_the_date()
        ),
        sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
            get_author_posts_url( get_the_author_meta( 'ID' ) ),
            sprintf( esc_attr__( 'View all posts by %s', 'ddvash' ), get_the_author() ),
            get_the_author()
        )
    );
}
endif;

if ( ! function_exists( 'ddvash_comment' ) ) :
function ddvash_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
                case '' :
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                <div id="comment-<?php comment_ID(); ?>">
                <div class="comment-author vcard">
                        <?php echo get_avatar( $comment, 40 ); ?>
                        <?php printf( __( '%s <span class="says">says:</span>', 'ddvash' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
                </div><!-- .comment-author .vcard -->
                <?php if ( $comment->comment_approved == '0' ) : ?>
                        <em><?php _e( 'Your comment is awaiting moderation.', 'ddvash' ); ?></em>
                        <br />
                <?php endif; ?>

                <div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                        <?php
                                /* translators: 1: date, 2: time */
                                printf( __( '%1$s at %2$s', 'ddvash' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'ddvash' ), ' ' );
                        ?>
                </div><!-- .comment-meta .commentmetadata -->

                <div class="comment-body"><?php comment_text(); ?></div>

                <div class="reply">
                        <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                </div><!-- .reply -->
        </div><!-- #comment-##  -->

        <?php
                        break;
                case 'pingback'  :
                case 'trackback' :
        ?>
        <li class="post pingback">
                <p><?php _e( 'Pingback:', 'ddvash' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'ddvash'), ' ' ); ?></p>
        <?php
                        break;
        endswitch;
}
endif;

?>