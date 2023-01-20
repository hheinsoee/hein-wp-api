<?php
get_header();
if (get_post_meta(get_the_id(), 'wp_channel', true) || get_the_tags(get_the_ID())) {
	$p = get_post_meta(get_the_id(), 'wp_channel', true);
};

echo json_encode(
	array_merge(
		$p,
		array(
			"id" => get_the_ID(),
			"slug" => $post->post_name,
			"title" => get_the_title(),
			"time" => date("Y-m-d H:i:s", get_post_time('U', true)),
			"content" => apply_filters('the_content', get_the_content()),
			"images" => images(),
			"languages" => wp_get_post_terms(get_the_ID(), "languages"),
			"tag" => wp_get_post_terms(get_the_ID(), "post_tag"),
		)

	)
);
