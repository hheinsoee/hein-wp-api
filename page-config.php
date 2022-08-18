<?php get_header();
$appSetting = file_get_contents (__DIR__.'/json/appSetting.json');
$skills = file_get_contents (__DIR__.'/json/skills.json');

echo json_encode(
	array_merge(
		array(
			"name" => get_bloginfo('name'),
			"description" => get_bloginfo('description'),
			"logo"=>logo(),
			
		),
		json_decode($appSetting, TRUE),
		array("skills"=>json_decode($skills, TRUE))
	)
);
