<?php

// Отправка HTML5 письма
function send_mail($id_product, $email, $type) {

	# Подключить ключевые файлы WP
	$path_root = __DIR__ . '/../..';
	require_once ($path_root . '/wp-config.php');
	require_once (get_stylesheet_directory() . '/functions.php');
	require_once ($path_root . '/wp-includes/l10n.php');
	require_once ($path_root . '/wp-includes/pluggable.php');
	require_once ($path_root . '/wp-content/plugins/easy-wp-smtp/easy-wp-smtp.php');

	if ($type == 'wait') {
		$template = 'mail_wait.html';
		$title_mail = 'About your order';
		//$title_mail = __('Confirm your order to create a slideshow!', 'fromfoto');
	}
	if ($type == 'test') {
		$template = 'mail_test.html';
		$title_mail = __('Confirm your e-mail!', 'fromfoto');
	}
	if ($type == 'example') {
		$template = 'mail_example.html';
		$title_mail = __('Confirm your e-mail!', 'fromfoto');
	}
	if ($type == 'ready'/* 2020-10-30 || $type == 'look'*/) {
		$template = 'mail_ready.html';
		$title_mail = 'Your story ready!';
		//$title_mail = __('Your video is ready!', 'fromfoto');
	}
	if ($type == 'sorry') {
		$template = 'mail_sorry.html';
		$title_mail = __('Sorry, we can\'t help', 'fromfoto') . ' :(';
	}
	if ($type == 'paid') {
		$template = 'mail_paid.html';
		$title_mail = __('Your video!', 'fromfoto');
	}
	if ($type == 'buy') {
		$template = 'mail_buy.html';
		$title_mail = __('Your slideshow!', 'fromfoto');
	}
	if ($type == 'speed') {
		$template = 'mail_speed.html';
		$title_mail = __('Your slideshow!', 'fromfoto');
	}
	
	// Тело письма
	$file_body_mail = __DIR__ . '/' . get_locale() . '/' . $template; # Путь к файлу с телом письма
	if ( !is_file( $file_body_mail) )
		$file_body_mail = __DIR__ . '/' . $template;
	$body_mail = file_get_contents($file_body_mail);
	
	// Сделать замены в теле письма
	$body_mail = str_replace(
		['{ID_PRODUCT}', '{EMAIL}', '{SITE_URL}', '{URL_PRODUCT}'],
		[$id_product, $email, home_url(), design\get_url_product($id_product)],
		$body_mail
	);

	/*
	//Замена много язычного текста
	$texts = array(
		"Confirm your order to create a slideshow!",
		"The order will be processed as soon as you click",
		"CONFIRM ORDER",
		"Sincerely, Slideshow.Photos!",
		"P.S. forget about the letter if you haven't make an order :)",
		"Your slideshow!",
		"Your video is processed!",
		"Perfect, You did it! Your order will be processed in the professional program of our designer, it will take some time (up to 11 hours).",
		"Need faster? Follow the link below and click \"speed up video creation\". Just to remind You, creation of a slideshow is FREE in our service. If You like the video - You can take your slideshow (on a paid basis)",
		"To see when it will be ready, or to",
		"ACCELERATE the CREATION of a SLIDESHOW",
		"Read feedbacks",
		"or leave yours",
		"We answer questions",
		"in our group on Facebook",
		"P.S. Do not respond to this letter.",
		"Your video is ready!",
		"We tried our best and made a great slideshow!",
		"You can view and download it here:",
		"VIEW SLIDESHOW",
		"P.P.S. If Your video is SOCIAL - email us (showing the link to the slideshow) and we will do a gift for you!",
		"Your video!",
		"And here is Your video is in good quality and without our logo :)",
		"Follow the link, click the right mouse button and select \"Save video as...\"<br>Download:",
		"Have a nice viewing!"
	);
	usort($texts, function($a, $b) {
		return strlen($b) - strlen($a);
	});
	foreach ($texts as &$value) {
		$body_mail = str_replace($value, __($value, 'fromfoto'), $body_mail);
	}
	*/
	
	$headers_mail = array(
		'Content-Type: text/html; charset=UTF-8',
		'List-Id: wow.cards',
		'X-Postmaster-Msgtype: ' . $type
	);
	
	wp_mail( $email, $title_mail, $body_mail, $headers_mail );
}