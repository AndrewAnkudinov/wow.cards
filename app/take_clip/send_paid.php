<?php

# ПРИНЯТЬ СИГНАЛ О ЗАГРУЗКЕ КЛИПА В ВЫСОКОМ КАЧЕСТВЕ

if (!isset($_REQUEST['id']))
	die('Переменная id не существует!');

# Подключить ключевые файлы CMS
require_once( __DIR__ . '/../app-config.php' );
require_once( PATH_ROOT . '/wp-config.php');
require_once( PATH_ROOT . '/wp-includes/post.php' );

define('NAME_PRODUCT', $_REQUEST['id']);
define('ID_PRODUCT', (int)NAME_PRODUCT);

# Получить данные для письма
$to        = get_post_field('post_content', ID_PRODUCT);
/*
$mail_template = get_post( 217 );
$subject   = $mail_template->post_title;
$body      = sprintf(str_replace('%d', '%s', $mail_template->post_content), ID_PRODUCT);
$headers   = array('Content-Type: text/plain; charset=UTF-8');  // text/html
print_r($to);
print_r($subject);
print_r($body);
print_r($headers);
*/

# Заменить аудио
$inAudio = PATH_READY_USERFILES . '/' . NAME_PRODUCT . '_swapaudio.mp4';
if (file_exists($inAudio)) {
	#Если есть замененное аудио
	$OrigVideo = PATH_READY_USERFILES . '/' . NAME_PRODUCT . '.mp4';
	$inVideo   = str_replace(".mp4", "_OLD.mp4", $OrigVideo);
	rename($OrigVideo, $inVideo);
	exec("/usr/bin/ffmpeg -y -i " . $inVideo . " -i " . $inAudio . " -strict -2 -c:v copy -map 0:v:0 -map 1:a:0 -shortest "
		. $OrigVideo . " 2>&1", $out, $rezult);
	echo('Результат - ' . $rezult);
	echo('<pre>');
	print_r($out);
	echo('</pre>');
	echo ('заменён!');
} else {
	echo ('не найден!');
}

require_once (PATH_APP . '/mail/send-mail.php');
send_mail(ID_PRODUCT, $to, 'paid');
echo('Paid send');
exit;