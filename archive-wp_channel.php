<?php get_header();
if (isset($_GET['channel_categories'])) {
    $terms = get_terms([
        'taxonomy' => 'channel_categories',
        'hide_empty' => false,
    ]);
    echo json_encode($terms);
    exit;
};
if (isset($_GET['latest'])) {
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

    if (have_posts()) :
        $data = [];

        while (have_posts()) :
            the_post();

            if (get_post_meta(get_the_id(), 'wp_channel', true) || get_the_tags(get_the_ID())) {
                $p = get_post_meta(get_the_id(), 'wp_channel', true);
            };
            // $data[] = get_template_part('content', 'thumbnail');
            $data[] = array_merge(
                $p,
                array(
                    "id" => get_the_ID(),
                    "slug" => $post->post_name,
                    "title" => get_the_title(),
                    "time" => date("Y-m-d H:i:s", get_post_time('U', true)),
                    "excerpt" => html_entity_decode(get_the_excerpt(), ENT_QUOTES, 'UTF-8'),
                    "images" => images(),
                    "url" => esc_url(get_permalink()),
                    "channel_categories" => wp_get_post_terms(get_the_ID(), "channel_categories"),
                    "tag" => wp_get_post_terms(get_the_ID(), "post_tag"),
                )
            );
        endwhile;

        echo json_encode(array(
            "title" => get_the_archive_title(),
            "description" => get_the_archive_description(),
            "data" => $data
        ));
    else :
    endif;
    exit;
}

$terms = get_terms([
    'taxonomy' => 'channel_categories',
    'hide_empty' => true,
]);
$theData = [];
foreach ($terms as $term) {
    $posts = getPostsByTerms($term->term_id);
    $theData[] = array(
        "term_id" => $term->term_id,
        "name" => $term->name,
        "channels"=> $posts
    );
}
echo json_encode($theData);
function getPostsByTerms($termsid){
    $query = new WP_Query(array(
        'post_type' => 'wp_channel',      // name of post type.   
        'posts_per_page' => -1, 
        'tax_query' => array(
            array(
                'taxonomy' => 'channel_categories',   // taxonomy name
                'field' => 'term_id',           // term_id, slug or name
                'terms' => $termsid,                  // term id, term slug or term name
            )
        )
    ));
    while ($query->have_posts()) :
        $query->the_post();
        if (get_post_meta(get_the_id(), 'wp_channel', true) || get_the_tags(get_the_id())) {
            $channel_meta = get_post_meta(get_the_id(), 'wp_channel', true);
        };
        // $data[] = get_template_part('content', 'thumbnail');
        $data[] = array_merge(
            $channel_meta,
            array(
                "id" => get_the_id(),
                "slug" => get_post_field('post_name', get_the_ID()),
                "title" => get_the_title(),
                "url" => esc_url(get_permalink()),
                "excerpt" => html_entity_decode(get_the_excerpt(), ENT_QUOTES, 'UTF-8'),
                "images" => images(),
                // "channel_categories" => wp_get_post_terms(get_the_id(), "channel_categories"),
                // "tag" => wp_get_post_terms(get_the_id(), "post_tag"),
            )
        );
    endwhile;
    return $data;
}
exit;