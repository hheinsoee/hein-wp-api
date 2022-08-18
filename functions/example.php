<?php
function profile_post()
{

    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x('ဝန်ထမ်း', 'Post Type General Name', 'twentytwenty'),
        'singular_name'       => _x('ဝန်ထမ်း', 'Post Type Singular Name', 'twentytwenty'),
        'menu_name'           => __('ဝန်ထမ်းများ', 'twentytwenty'),
        'parent_item_colon'   => __('Parent Profile', 'twentytwenty'),
        'all_items'           => __('ဝန်ထမ်း အားလုံး', 'twentytwenty'),
        'view_item'           => __('ဤဝန်ထမ်းကိုကြည့်ရန်', 'twentytwenty'),
        'add_new_item'        => __('ဝန်ထမ်းသစ်ထည့်ရန်', 'twentytwenty'),
        'add_new'             => __('အသစ်ထပ်ထည့်ရန်', 'twentytwenty'),
        'edit_item'           => __('ကိုယ်ရေးအကျည်းပြင်ရန်', 'twentytwenty'),
        'update_item'         => __('ပြင်ဆင်ပြီး', 'twentytwenty'),
        'search_items'        => __('ရှိသော ဝန်ထမ်းများ ရှာရန်', 'twentytwenty'),
        'not_found'           => __('မတွေ့ရှိပါ', 'twentytwenty'),
        'not_found_in_trash'  => __('ဘာမှမရှိ', 'twentytwenty'),
    );

    // Set other options for Custom Post Type

    $args = array(
        'label'               => __('ဝန်ထမ်းများ', 'twentytwenty'),
        'description'         => __('ဝန်ထမ်း အချက်အလက်', 'twentytwenty'),
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'p', 'slug' => 'profile'),
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'menu_position'       => 10,

        // Features this CPT supports in Post Editor
        'supports'           =>  array(
            'title',
            // 'editor',
            // 'author',
            // 'post-formats',
            'thumbnail',
            // 'excerpt',
            // 'comments',
            // 'revisions',
            // 'custom-fields',
        ),

        'menu_icon'            => 'dashicons-admin-users',

        // You can associate this CPT with a taxonomy or custom taxonomy.
        // 'taxonomies'          => array( 'post_tag', 'category' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'can_export'          => true,
        'exclude_from_search' => false,

        // important
        'show_in_rest'        => false

    );

    // Registering your Custom Post Type
    register_post_type('profile', $args);
    flush_rewrite_rules();
}

/* Hook into the 'init' action so that the function
* Containing our post type registration is not
* unnecessarily executed.
*/

add_action('init', 'profile_post');



/////////////////////////////////////////////////////////////////////////

function add_profile()
{
    add_meta_box(
        'wpa-profile',
        'Profile Imformation',
        'profileInput',
        'profile', //Profiles, post, page
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
add_action('admin_menu', 'add_profile');


function profileInput()
{
    global $post;
    wp_nonce_field('profile', 'profile_nonce');


    $meta = get_post_meta($post->ID, 'profile', true);

    $position = @isset($meta['position']) ? @$meta['position'] : '';
    $nrc = @isset($meta['nrc']) ? @$meta['nrc'] : '';
    $id = @isset($meta['id']) ? @$meta['id'] : '';
    $expire = @isset($meta['expire']) ? @$meta['expire'] : '';

?>
    Position
    <input placeholder="Web Developer" type="text" class="widefat" name="profile[position]" value="<?php echo $position; ?>" />
    NRC
    <input placeholder="၁၁/စတန(နိုင်)၀၀၀၀၀၀" type="text" class="widefat" name="profile[nrc]" value="<?php echo $nrc; ?>" />
    ID
    <input placeholder="000" max="999" min="1" type="number" class="widefat" name="profile[id]" value="<?php echo $id; ?>" />
    Valid Date
    <input placeholder="Web Developer" type="date" class="widefat" name="profile[expire]" value="<?php echo $expire; ?>" />

<?php

}

add_action('save_post', 'profile_save');
function profile_save($post_id)
{
    global $custom_meta_fields;
    if (
        !isset($_POST['profile_nonce']) ||
        !wp_verify_nonce($_POST['profile_nonce'], 'profile')
    )
        return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    update_post_meta(
        $post_id,
        'profile',
        $_POST['profile']
    );
}

function my_enter_title_here($title, $post)
{
    if ('profile' == $post->post_type) {
        $title = 'Name';
    }
    return $title;
}
add_filter('enter_title_here', 'my_enter_title_here', 10, 2);

function custom_columns($columns)
{
    $columns = array(
        'cb' => '<input type="checkbox" />',
        // 'featured_image' => 'Image',
        'title' => 'အမည်',
        'nrc' => 'မှတ်ပုံတင်အမှတ်',
        'p_id' => 'နံပါတ်',
        'position' => 'ရာထူး',
        'expire' => 'သက်တမ်း',
    );
    return $columns;
}

add_filter('manage_profile_posts_columns', 'custom_columns');

function custom_columns_data($column, $post_id)
{
    switch ($column) {
        case 'featured_image':
            the_post_thumbnail('tiny');
            break;
        case 'p_id':
            echo get_post_meta($post_id, 'profile', true)['id'];
            break;
        case 'nrc':
            echo get_post_meta($post_id, 'profile', true)['nrc'];
            break;
        case 'position':
            echo get_post_meta($post_id, 'profile', true)['position'];
            break;
        case 'expire':
            echo get_post_meta($post_id, 'profile', true)['expire'];
            break;
    }
}
add_action('manage_profile_posts_custom_column', 'custom_columns_data', 10, 2);
?>