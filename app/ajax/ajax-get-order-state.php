<?php
getZakazState($_GET['id']);

function getZakazState($id) {
	# Подключить ключевые файлы WP
	require_once ('../../wp-config.php');

	$post_meta   = get_post_meta($id); // Все метаданные записи в виде массива

	if (checkAppendix($post_meta) == true) {
		echo("ready");
	} else {
		echo('none');
	}
}
//Проверить готовность дополнительного видео
function checkAppendix($post_meta) {
	if (empty($post_meta['wpcf-o_video_created'][0])) { //основное видео не готово
		return false;
	}
	if (empty($post_meta['_wpcf_appendix_id'][0])) { //доп видео не установлено
		return true;
	}
	$post_meta_NEW = get_post_meta($post_meta['_wpcf_appendix_id'][0]);
	if (empty($post_meta_NEW['wpcf-o_video_created'][0])) { //доп видео еще не готово
		return false;
	} else { //доп видео создано
		return true;
	}
}
?>