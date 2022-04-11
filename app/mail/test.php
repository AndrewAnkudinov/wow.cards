<?php


if (isset($_REQUEST['zakaz1']))
      $id_order = (int)$_REQUEST['zakaz1'];
else  $id_order = 24;
//$to       	= 'conscormocra5592@yahoo.com';
//$to       	= 'zukushin1996@gmail.comэ';
$to       	= 'virralitocomu@gmail.com';
//$to       	= 'todogamashi1987@gmail.com';
//$to       	= 'sumpprimsopabera@gmail.com';

//$to       	= 'fromfoto.com@gmail.com';
//$to       	= 'andrew.ankudinov@gmail.com';
//$to       	= 'gnv1302@gmail.com';
//$to       	= 'asdaleze212@gmail.com';
$to1       	= 'wowproduct@mail.ru';
$text = "https://slideshow.photos/";
# Подключить ключевые файлы WP
require_once (__DIR__ . '/../../wp-config.php');
require_once (__DIR__ . '/../../wp-settings.php');
require_once (__DIR__ . '/../../wp-includes/plugin.php');
require_once (__DIR__ . '/../../wp-includes/pluggable.php');
require_once (__DIR__ . '/../../wp-content/plugins/easy-wp-smtp/easy-wp-smtp.php');
require_once (__DIR__ . '/send_mail.php');
//send_mail($id_order, $to, 'example');
send_mail($id_order, $to, 'wait');
//send_mail($id_order, $to, 'ready');
//send_mail($id_order, $to1, 'look');
//wp_mail( $to, 'Confirm your e-mail', $text );
//wp_mail( $to1, 'Confirm your e-mail', $text );
echo 'отправлено';