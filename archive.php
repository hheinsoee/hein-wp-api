<?php get_header();

  add_filter( 'get_the_archive_title', function ($title) {    
    if ( is_category() ) {    
            $title = single_cat_title( '', false );    
        } elseif ( is_tag() ) {    
            $title = single_tag_title( '', false );    
        } elseif ( is_author() ) {    
            $title = '<span class="vcard">' . get_the_author() . '</span>' ;    
        } elseif ( is_tax() ) { //for custom post types
            $title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
        } elseif (is_post_type_archive()) {
            $title = post_type_archive_title( '', false );
        }
    return $title;    
  });

if (have_posts()) :
    $data = [];

    while (have_posts()) :
        the_post();
        // $data[] = get_template_part('content', 'thumbnail');
        $data[] = array(
            "id" => get_the_ID(),
            "slug" => $post->post_name,
            "title" => get_the_title(),
            "time" => date("Y-m-d H:i:s", get_post_time('U', true)),
            "excerpt" => html_entity_decode(get_the_excerpt(), ENT_QUOTES, 'UTF-8'),
            "images" => images(),
            "url" => esc_url(get_permalink())
        );
    endwhile;

    echo json_encode(array(
        "title" => get_the_archive_title(),
        "description" => get_the_archive_description(),
        "data" => $data));
else :
endif;
