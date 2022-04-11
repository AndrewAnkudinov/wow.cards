<?php
function reSendAction() {
	# Подключить ключевые файлы WP
	require_once (__DIR__ . '/../../wp-config.php');
	require_once (__DIR__ . '/../../wp-includes/post.php');
	require_once (__DIR__ . '/../../wp-content/plugins/fromfoto/fromfoto.php');
	require_once (__DIR__ . '/send_mail.php');

	//*** ЧЕРЕЗ СУТКИ, ЕСЛИ НЕ СМОТРЕЛ ВИДЕО ***
	$args = array(
		'numberposts' => 30,
		'orderby'     => 'date',
		'order'       => 'DESC',
		'post_type'   => 'product',
		'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
		'date_query' => array(
			'after' => date('d-m-Y', strtotime('18.08.2017')) 
		),
		'meta_query' => array(
			array(
				'key' => 'wpcf-o_video_created',	//Видео создано
				'compare' => 'EXISTS'
			),
			array(
				'relation' => 'OR',					//Видео не просмотрено
				array(
					'key' => 'wpcf-o_video_showed',
					'compare' => '<',
					'value' => 1
				),
				array(
					'key' => 'wpcf-o_video_showed',
					'compare' => 'NOT EXISTS'
				),
			),
			array(
				'key' => 'wpcf-o_video_created',	//Дата создания видео больше 24 часов назад
				'compare' => '<',
				'value' => time() - (24 * 60 * 60)
			),
			array(
				'key' => 'wpcf-o_re_send_look',		//Небыло отправлено уведомление "не смотрел"
				'compare' => 'NOT EXISTS'
			)
		)
	);
	$products = get_posts($args);
	//Отправить уведомление заказам
	$done = '';
	foreach ($products as $zakaz) {
		//Игнорировать дополнительные заказы
		$appendixZakaz = get_post_meta($zakaz->ID - 1, '_wpcf_appendix_id', true);
		if ($appendixZakaz != $zakaz->ID) {
			$ID       = $zakaz->ID;
			$to       = $zakaz->post_content;
			send_mail($ID, $to, 'look');
			update_post_meta((int)$ID, 'wpcf-o_re_send_look', time());	//Установить статус  - уведомление "не смотрел" отправлено
			echo ($ID . '</br>');
			if ($done != '') $done = $done . ',';
			$done = $done . $ID;
		}
	}
	if ($done != '') {
		@file_put_contents(DEFAULT_UPLOAD_ZAKAZ_PATH . 're_send_LOOK.txt', date('d-m-Y h:i:s', time()) . ' ==> ' . $done . "\r\n", FILE_APPEND); #Отчёт;
	}
	/*
	//*** ЧЕРЕЗ СУТОКИ, ЕСЛИ НЕ КУПИЛ ВИДЕО ***
	$args = array(
		'numberposts' => 30,
		'orderby'     => 'date',
		'order'       => 'DESC',
		'post_type'   => 'product',
		'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
		'date_query' => array(
			'after' => date('d-m-Y', strtotime('20.08.2017')) 
		),
		'meta_query' => array(
			array(
				'key' => 'wpcf-o_video_created',	//Видео создано
				'compare' => 'EXISTS'
			),
			array(
				'key' => 'wpcf-o_video_showed',		//Дата просмотра видео больше 24 часов назад
				'compare' => '<',
				'value' => time() - (24 * 60 * 60)
			),
			array(
				'key' => 'wpcf-o_pay',				//Видео не куплено
				'compare' => 'NOT EXISTS'
			),
			array(
				'key' => 'wpcf-o_re_send_buy',	//Небыло отправлено уведомление "не купил"
				'compare' => 'NOT EXISTS'
			)
		)
	);
	$products = get_posts($args);
	//Отправить уведомление заказам
	$done = '';
	foreach ($products as $zakaz) {
		//Игнорировать дополнительные заказы
		$appendixZakaz = get_post_meta($zakaz->ID - 1, '_wpcf_appendix_id', true);
		if ($appendixZakaz != $zakaz->ID) {
			$ID       = $zakaz->ID;
			$to       = $zakaz->post_content;
			send_mail($ID, $to, 'buy');
			update_post_meta((int)$ID, 'wpcf-o_re_send_buy', time());	//Установить статус  - уведомление "не купил" отправлено
			echo ($ID . '</br>');
			if ($done != '') $done = $done . ',';
			$done = $done . $ID;
		}
	}
	if ($done != '') {
		@file_put_contents(DEFAULT_UPLOAD_ZAKAZ_PATH . 're_send_BUY.txt', date('d-m-Y h:i:s', time()) . ' ==> ' . $done . "\r\n", FILE_APPEND); #Отчёт;
	} */
}
?>