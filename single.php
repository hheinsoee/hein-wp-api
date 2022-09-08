<?php
get_header();

echo json_encode(array(
	"id" => get_the_ID(),
	"slug" => $post->post_name,
    "time" => date("Y-m-d H:i:s", get_post_time('U', true)),
    "title"=>get_the_title(),
    "content"=>apply_filters('the_content', $post->post_content),
    "excerpt" => html_entity_decode(get_the_excerpt(), ENT_QUOTES, 'UTF-8'),
    "images" => images(),
    "category" => wp_get_post_terms(get_the_ID(), "category"),
    "tag" => wp_get_post_terms(get_the_ID(), "post_tag"),
));
