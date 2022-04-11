<?php

// AJAX: Удалить файл

$sid = $_POST['sid'];
session_id($sid); // Применяем сессию с конкретным ID полученным из POST
if (!isset($_SESSION))
	session_start();
//mail('my_works@mail.ru', 'test delete', print_r($_POST,true). "\r\n". print_r($_SESSION, true));
if ( isset( $_SESSION['BX_UPLOADED_CLIPS'][$_SESSION['BX_CLIP_ID']]['media'][$_POST['file_id']] ) )
{
	$url_deleted_file = $_SESSION['BX_UPLOADED_CLIPS'][$_SESSION['BX_CLIP_ID']]['media'][$_POST['file_id']]['src'];
	if ( file_exists( $_SERVER['DOCUMENT_ROOT'] . $url_deleted_file ) ) {
		unlink( $_SERVER['DOCUMENT_ROOT'] . $url_deleted_file ); // удалим файл
	}
	unset( $_SESSION['BX_UPLOADED_CLIPS'][$_SESSION['BX_CLIP_ID']]['media'][$_POST['file_id']] ); // удалим запись о файле в сессии
}