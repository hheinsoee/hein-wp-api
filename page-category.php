<?php get_header();
$appSetting = file_get_contents (get_template_directory_uri().'/json/skills.json');

echo json_encode(
	json_decode($appSetting, TRUE)
);
