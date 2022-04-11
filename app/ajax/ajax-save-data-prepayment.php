<?php

// СОХРАНИТЬ ДАННЫЕ ЗАКАЗА ПЕРЕД ПРЕОПЛАТОЙ (И ПЕРЕЗАГРУЗКОЙ СТРАНИЦЫ)

if (!$_POST) {
    die('$_POST array is empty');
}
//var_dump($_POST);

# Подключить ключевые файлы CMS
include_once(__DIR__ . '/../app-config.php');


$reSendId = $_POST['id_order'];

# Начать сессию
$sid = $_POST['sid'];
session_id($sid); // Применить сессию с конкретным ID полученным из POST
if (!isset($_SESSION))
    session_start();

//Подключить ключевые файлы WP
require_once(PATH_ROOT . '/wp-config.php');
require_once(PATH_ROOT . '/wp-includes/post.php');

//mail('my_works@mail.ru', 'test crop', print_r($_POST, true) . "\r\n" . print_r($_SESSION, true)); //die;
define('ID_DESIGN', $_POST['id_design']);
define('PATH_TMP_PRODUCT', PATH_TMP_USERFILES . '/' . $sessionTmp['BX_USER_IDENT'] . '/' . ID_DESIGN);

// Защитить заказ от повторной отправки
if (!file_exists(PATH_TMP_PRODUCT . '/' . 'flag.txt')) { // eсли файла-флага нет, значит уже удалили, когда оформляли заказ
    die(0);
}

// Записать данные заказа в файл в папке заказа
file_put_contents(PATH_TMP_PRODUCT . '/data_prepayment.txt', json_encode($_POST));

// Вывести выходящие данные
exit(1);