<?php

function channel_cat_taxonomies()
{
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name' => _x('channel categories', 'taxonomy general name'),
        'singular_name' => _x('Categorie', 'taxonomy singular name'),
        'search_items' =>  __('Search channel categories'),
        'popular_items' => __('Popular channel categories'),
        'all_items' => __('All channel categories'),
        'parent_item' => __('Parent Categorie'),
        'parent_item_colon' => __('Parent Categorie:'),
        'edit_item' => __('Edit Categorie'),
        'update_item' => __('Update Categorie'),
        'add_new_item' => __('Add New Categorie'),
        'new_item_name' => __('New Categorie Name'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'channel_categories'),
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_rest' => true
    );

    /* register_taxonomy() to register taxonomy
*/
    register_taxonomy('channel_categories', 'wp_channel', $args);
}
add_action('init', 'channel_cat_taxonomies', 0);


function wp_channel_post()
{

    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x('wp_channel', 'Post Type General Name', 'twentytwenty'),
        'singular_name'       => _x('wp_channel', 'Post Type Singular Name', 'twentytwenty'),
        'menu_name'           => __('wp_channel', 'twentytwenty'),
        'parent_item_colon'   => __('Parent wp_channel', 'twentytwenty'),
        'all_items'           => __('wp_channel အားလုံး', 'twentytwenty'),
        'view_item'           => __('wp_channel ကိုကြည့်ရန်', 'twentytwenty'),
        'add_new_item'        => __('wp_channelသစ်ထည့်ရန်', 'twentytwenty'),
        'add_new'             => __('အသစ်ထပ်ထည့်ရန်', 'twentytwenty'),
        'edit_item'           => __('wp_channel အချက်အလက်ပြင်ရန်', 'twentytwenty'),
        'update_item'         => __('ပြင်ဆင်ပြီး', 'twentytwenty'),
        'search_items'        => __('ရှိသော wp_channelများ ရှာရန်', 'twentytwenty'),
        'not_found'           => __('မတွေ့ရှိပါ', 'twentytwenty'),
        'not_found_in_trash'  => __('ဘာမှမရှိ', 'twentytwenty'),
    );

    // Set other options for Custom Post Type

    $args = array(
        'label'               => __('wp_channelများ', 'twentytwenty'),
        'description'         => __('', 'twentytwenty'),
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'wp_channel', 'with_front' => false),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => true,
        'menu_os'       => 10,

        // Features this CPT supports in Post Editor
        'supports'           =>  array(
            'title',
            'editor',
            // 'author',
            // 'post-formats',
            'thumbnail',
            'excerpt',
            // 'comments',
            // 'revisions',
            // 'custom-fields',
        ),

        'taxonomies'          => array('post_tag'),
        'menu_icon'            => 'dashicons-editor-code',

        // You can associate this CPT with a taxonomy or custom taxonomy.
        // 'taxonomies'          => array( 'post_tag', 'category' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'can_export'          => true,
        'exclude_from_search' => true,

        // important
        'show_in_rest'        => true

    );

    // Registering your Custom Post Type
    register_post_type('wp_channel', $args);
    flush_rewrite_rules();
}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/

add_action('init', 'wp_channel_post');


/////////////////////////////////////////////////////////////////////////

function add_wp_channel()
{
    add_meta_box(
        'wpa-wp_channel',
        'wp_channel Imformation',
        'wp_channelInput',
        'wp_channel', //wp_channels, post, page
        'advanced',
        'default',
        null
    );

    //   add_meta_box(
    //       string $id,
    //       string $title,
    //       callable $callback,
    //       string|array|WP_Screen $screen = null,
    //       string $context = 'advanced',
    //       string $priority = 'default',
    //       array $callback_args = null
    //       )

}
add_action('admin_menu', 'add_wp_channel');


add_action('wp_insert_post', 'channel_change_slug');
function channel_change_slug($post_id)
{

    // Making sure this runs only when a 'wp_channel' post type is created
    $slug = 'wp_channel';
    if ($slug != $_POST['post_type']) {
        return;
    }


    wp_update_post(array(
        'ID' => $post_id,
        'post_name' => $post_id // slug
    ));
}


function wp_channelInput()
{
    global $post;
    wp_nonce_field('wp_channel', 'wp_channel_nonce');
    $meta = get_post_meta($post->ID, 'wp_channel', true);

    $color = @isset($meta['color']) ? @$meta['color'] : '';
    $channel_link = @isset($meta['channel_link']) ? @$meta['channel_link'] : '';

?>
    Color
    <input placeholder="type" type="color" name="wp_channel[color]" value="<?php echo $color; ?>" />
    <br />
    channel link
    <textarea placeholder="channel link" type="text" class="widefat" name="wp_channel[channel_link]"><?php echo $channel_link; ?></textarea>

<?php

}

add_action('save_post', 'wp_channel_save');
function wp_channel_save($post_id)
{
    global $custom_meta_fields;
    if (
        !isset($_POST['wp_channel_nonce']) ||
        !wp_verify_nonce($_POST['wp_channel_nonce'], 'wp_channel')
    )
        return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    update_post_meta(
        $post_id,
        'wp_channel',
        $_POST['wp_channel']
    );
}

function channel_title_here($title, $post)
{
    if ('wp_channel' == $post->post_type) {
        $title = 'Channel Name';
    }
    return $title;
}
add_filter('enter_title_here', 'channel_title_here', 10, 2);

function channel_custom_columns($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'အမည်',
        'channel_categories' => 'channel categories',
        'tags' => 'tags',
        'featured_image' => 'Image'
    );
    return $columns;
}

add_filter('manage_wp_channel_posts_columns', 'channel_custom_columns');

function channel_custom_columns_data($column, $post_id)
{
    switch ($column) {
        case 'featured_image':
            the_post_thumbnail('xs');
            break;
        case 'channel_categories':
            echo get_post_meta($post_id, 'wp_channel', true)['channel_categories'];
            break;
        case 'tags':
            echo get_post_meta($post_id, 'wp_channel', true)['tags'];
            break;
    }
}
add_action('manage_wp_channel_posts_custom_column', 'channel_custom_columns_data', 10, 2);
?>