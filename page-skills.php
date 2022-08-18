<?php get_header();
$appSetting = file_get_contents (__DIR__.'/json/skills.json');

echo json_encode(
	json_decode($appSetting, TRUE)
);
