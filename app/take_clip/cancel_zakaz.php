<?php

# ОБРАБОТАТЬ СИГНАЛ ОБ ОТМЕНЕ ЗАКАЗА

# Подключить ключевые файлы CMS
require_once( __DIR__ . '/../app-config.php' );
require_once( PATH_ROOT . '/wp-config.php');
require_once( PATH_ROOT . '/wp-includes/post.php' );

$order_id  = (int)$_REQUEST['zakaz'];

# Обновить данные в БД
$my_post                = array();
$my_post['ID']          = $order_id;
$my_post['post_status'] = 'draft';
wp_update_post($my_post);

# Отправить письмо

$to        = get_post_field('post_content', $order_id);
$mail_template = get_post( 215 );
$subject   = $mail_template->post_title;
$body      = sprintf( $mail_template->post_content , $order_id);
$headers   = array('Content-Type: text/plain; charset=UTF-8');  // text/html
//require_once ('../wp-includes/pluggable.php');
//require_once ('../wp-content/plugins/easy-wp-smtp/easy-wp-smtp.php');

//Если текущий заказ дополнительный
$appendixZakaz = get_post_meta($order_id - 1, '_wpcf_appendix_id', true);
if ($appendixZakaz != $order_id) {
	//wp_mail( $to, $subject, $body, $headers );
	//require_once ('../e-mail/send_mail.php');
	//send_mail($order_id, $to, 'sorry');
}
//mail($to, $subject, $body);

echo('Removed');
die;