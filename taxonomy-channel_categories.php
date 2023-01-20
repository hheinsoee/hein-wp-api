<?php
get_header();

$channel_categories = get_queried_object();
$query = new WP_Query(array(
    'post_type' => 'wp_channel',      // name of post type.   
    'posts_per_page' => -1, 
    'tax_query' => array(
        array(
            'taxonomy' => 'channel_categories',   // taxonomy name
            'field' => 'term_id',           // term_id, slug or name
            'terms' => $channel_categories->term_id,                  // term id, term slug or term name
        )
    )
));

add_filter('get_the_archive_title', function ($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_tax()) { //for custom post types
        $title = sprintf(__('%1$s'), single_term_title('', false));
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    }
    return $title;
});

while ($query->have_posts()) :
    $query->the_post();
    if (get_post_meta($post->ID, 'wp_channel', true) || get_the_tags($post->ID)) {
        $channel_meta = get_post_meta($post->ID, 'wp_channel', true);
    };
    // $data[] = get_template_part('content', 'thumbnail');
    $data[] = array_merge(
        $channel_meta,
        array(
            "id" => $post->ID,
            "slug" => $post->post_name,
            "title" => get_the_title(),
            "excerpt" => html_entity_decode(get_the_excerpt(), ENT_QUOTES, 'UTF-8'),
            "images" => images(),
            // "channel_categories" => wp_get_post_terms($post->ID, "channel_categories"),
            // "tag" => wp_get_post_terms($post->ID, "post_tag"),
        )
    );
endwhile;
echo json_encode(array(
    "title" => get_the_archive_title(),
    "description" => get_the_archive_description(),
    "term_id"=>$channel_categories->term_id,
    "data" => $data
));