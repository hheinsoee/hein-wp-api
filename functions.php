<?php
// include_once(__DIR__ . "/functions/metabox.php");
include_once(__DIR__ . "/functions/portfolio.php");

remove_filter('the_content','wpautop');
// function abc()
// {
//     include_once('package.php');
// }
// add_shortcode('abc', 'abc');

//image sizing
add_image_size('xs', 70, 70);
add_image_size('s', 200, 200);
add_image_size('m', 400, 400);
add_image_size('l', 800, 800);
add_image_size('xl', 1000, 1000);
function images(){
    return array(
        "xs" => get_the_post_thumbnail_url(null, 'xs'),
        "s" => get_the_post_thumbnail_url(null, 's'),
        "m" => get_the_post_thumbnail_url(null, 'm'),
        "l" => get_the_post_thumbnail_url(null, 'l'),
        "xl" => get_the_post_thumbnail_url(null, 'xl')
    );
}

function hein_theme_support()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'hein_theme_support');


function custom_logo()
{
    $defaults = array(
        'height'               => 400,
        'width'                => 400,
        'flex-height'          => true,
        'flex-width'           => true,
        'header-text'          => array('site-title', 'site-description'),
        'unlink-homepage-logo' => true,
    );

    add_theme_support('custom-logo', $defaults);
}

add_action('after_setup_theme', 'custom_logo');

function logo()
{
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
    return $image[0];
}


// jetpet config 
function jetpackme_allow_my_post_types( $allowed_post_types ) {
    $allowed_post_types[] = 'portfolio';
 
    return $allowed_post_types;
}
add_filter( 'rest_api_allowed_post_types', 'jetpackme_allow_my_post_types' )

function jpina_no_related_posts( $options ) {
    if ( !is_singular( 'post' ) ) {
        $options['enabled'] = false;
    }
    return $options;
}
add_filter( 'jetpack_relatedposts_filter_options', 'jpina_no_related_posts' );

// important အမှားကာကွယ်ရန်
function wpdocs_remove_menus()
{

    // //   remove_menu_page( 'index.php' );                  //Dashboard
    // remove_menu_page('jetpack');                    //Jetpack* 
    // //   remove_menu_page( 'edit.php' );                   //Posts
    // //   remove_menu_page( 'upload.php' );                 //Media
    // //   remove_menu_page( 'edit.php?post_type=page' );    //Pages
    // remove_menu_page('edit-comments.php');          //Comments
    // //   remove_menu_page( 'themes.php' );                 //Appearance
    // remove_menu_page('plugins.php');                //Plugins
    // //   remove_menu_page( 'users.php' );                  //Users
    // remove_menu_page('tools.php');                  //Tools
    // //   remove_menu_page( 'options-general.php' );        //Settings

}
/**
 * Remove Appearance > Themes and Appearance > Theme Editor admin menu items
 */
// function wpdd_remove_menu_items()
// {
//     remove_submenu_page('themes.php', 'themes.php');
//     remove_submenu_page('themes.php', 'theme-editor.php');
// }
// add_action('admin_menu', 'wpdocs_remove_menus');
// add_action('admin_menu', 'wpdd_remove_menu_items', 999);



//my function

function multiexplode ($delimiters,$string) {

    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

