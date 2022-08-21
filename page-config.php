<?php get_header();
$appSetting = file_get_contents(__DIR__ . '/json/appSetting.json');
$skills = file_get_contents(__DIR__ . '/json/skills.json');

$args = array(
	'post_type' => 'portfolio',
	'order'    => 'ASC'
);
$recent_works = [];
$the_query = new WP_Query($args);
if ($the_query->have_posts()) :
	while ($the_query->have_posts()) :
		$the_query->the_post();
		// content goes here
		$recent_works[] = array(
			"id" => get_the_ID(),
			"slug" => $post->post_name,
			"title" => get_the_title(),
			"excerpt" => html_entity_decode(get_the_excerpt(), ENT_QUOTES, 'UTF-8'),
			"time" => date("Y-m-d H:i:s", get_post_time('U', true)),
			"images" => images(),
			"languages" => wp_get_post_terms(get_the_ID(), "languages"),
			"tag" => wp_get_post_terms(get_the_ID(), "post_tag"),
		);
	endwhile;
	wp_reset_postdata();
else :
endif;


echo json_encode(
	array_merge(
		array(
			"name" => get_bloginfo('name'),
			"description" => get_bloginfo('description'),
			"logo" => logo(),

		),
		json_decode($appSetting, TRUE),
		array("recent_works" => $recent_works),
		array("skills" => json_decode($skills, TRUE))
	)
);
