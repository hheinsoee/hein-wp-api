<?php

function create_discog_taxonomies()
{
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name' => _x('languages', 'taxonomy general name'),
        'singular_name' => _x('Language', 'taxonomy singular name'),
        'search_items' =>  __('Search languages'),
        'popular_items' => __('Popular languages'),
        'all_items' => __('All languages'),
        'parent_item' => __('Parent Language'),
        'parent_item_colon' => __('Parent Language:'),
        'edit_item' => __('Edit Language'),
        'update_item' => __('Update Language'),
        'add_new_item' => __('Add New Language'),
        'new_item_name' => __('New Language Name'),
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'languages'),
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_rest' => true
    );

    /* register_taxonomy() to register taxonomy
*/
    register_taxonomy('languages', 'portfolio', $args);
}
add_action('init', 'create_discog_taxonomies', 0);


function portfolio_post()
{

    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x('portfolio', 'Post Type General Name', 'twentytwenty'),
        'singular_name'       => _x('portfolio', 'Post Type Singular Name', 'twentytwenty'),
        'menu_name'           => __('portfolio', 'twentytwenty'),
        'parent_item_colon'   => __('Parent portfolio', 'twentytwenty'),
        'all_items'           => __('portfolio အားလုံး', 'twentytwenty'),
        'view_item'           => __('portfolio ကိုကြည့်ရန်', 'twentytwenty'),
        'add_new_item'        => __('portfolioသစ်ထည့်ရန်', 'twentytwenty'),
        'add_new'             => __('အသစ်ထပ်ထည့်ရန်', 'twentytwenty'),
        'edit_item'           => __('portfolio အချက်အလက်ပြင်ရန်', 'twentytwenty'),
        'update_item'         => __('ပြင်ဆင်ပြီး', 'twentytwenty'),
        'search_items'        => __('ရှိသော portfolioများ ရှာရန်', 'twentytwenty'),
        'not_found'           => __('မတွေ့ရှိပါ', 'twentytwenty'),
        'not_found_in_trash'  => __('ဘာမှမရှိ', 'twentytwenty'),
    );

    // Set other options for Custom Post Type

    $args = array(
        'label'               => __('portfolioများ', 'twentytwenty'),
        'description'         => __('portfolio အချက်အလက်', 'twentytwenty'),
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'portfolio', 'with_front' => false),
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

        'taxonomies'          => array( 'post_tag' ),
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
    register_post_type('portfolio', $args);
    flush_rewrite_rules();
}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/

add_action('init', 'portfolio_post');


/////////////////////////////////////////////////////////////////////////

function add_portfolio()
{
    add_meta_box(
        'wpa-portfolio',
        'portfolio Imformation',
        'portfolioInput',
        'portfolio', //portfolios, post, page
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
add_action('admin_menu', 'add_portfolio');


add_action('wp_insert_post', 'change_slug');
function change_slug($post_id)
{

    // Making sure this runs only when a 'portfolio' post type is created
    $slug = 'portfolio';
    if ($slug != $_POST['post_type']) {
        return;
    }


    wp_update_post(array(
        'ID' => $post_id,
        'post_name' => $post_id // slug
    ));
}


function portfolioInput()
{
    global $post;
    wp_nonce_field('portfolio', 'portfolio_nonce');


    $meta = get_post_meta($post->ID, 'portfolio', true);

    $color = @isset($meta['color']) ? @$meta['color'] : '';
    $description = @isset($meta['description']) ? @$meta['description'] : '';
    $demo = @isset($meta['demo_link']) ? @$meta['demo_link'] : '';
    $profile = @isset($meta['profile_link']) ? @$meta['profile_link'] : '';
    $github_link = @isset($meta['github_link']) ? @$meta['github_link'] : '';

?>
    Color
    <input placeholder="type" type="color" name="portfolio[color]" value="<?php echo $color; ?>" />
   <br />
    Demo Link
    <textarea placeholder="Demo Link" type="text" class="widefat" name="portfolio[demo_link]"><?php echo $demo; ?></textarea>
    Profile Link
    <textarea placeholder="Profile Link" type="text" class="widefat" name="portfolio[profile_link]"><?php echo $profile; ?></textarea>
    Description
    <textarea placeholder="Download Desctiption" class="widefat" name="portfolio[description]"><?php echo $description; ?></textarea>
    Github
    <textarea placeholder="github_link" class="widefat" name="portfolio[github_link]"><?php echo $github_link; ?></textarea>
    
<?php

}

add_action('save_post', 'portfolio_save');
function portfolio_save($post_id)
{
    global $custom_meta_fields;
    if (
        !isset($_POST['portfolio_nonce']) ||
        !wp_verify_nonce($_POST['portfolio_nonce'], 'portfolio')
    )
        return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    update_post_meta(
        $post_id,
        'portfolio',
        $_POST['portfolio']
    );
}

function my_enter_title_here($title, $post)
{
    if ('portfolio' == $post->post_type) {
        $title = 'Name';
    }
    return $title;
}
add_filter('enter_title_here', 'my_enter_title_here', 10, 2);

function custom_columns($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => 'အမည်',
        'languages' => 'languages',
        'tags' => 'tags',
        'featured_image' => 'Image'
    );
    return $columns;
}

add_filter('manage_portfolio_posts_columns', 'custom_columns');

function custom_columns_data($column, $post_id)
{
    switch ($column) {
        case 'featured_image':
            the_post_thumbnail('xs');
            break;
        case 'languages':
            echo get_post_meta($post_id, 'portfolio', true)['languages'];
            break;
        case 'tags':
            echo get_post_meta($post_id, 'portfolio', true)['tags'];
            break;
    }
}
add_action('manage_portfolio_posts_custom_column', 'custom_columns_data', 10, 2);
?>