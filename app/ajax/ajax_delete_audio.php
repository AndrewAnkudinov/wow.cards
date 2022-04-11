<?php

// AJAX: Удалить аудио-файл 
/*
// Стартовать сессию
function is_session_started()
{
	if ( php_sapi_name() !== 'cli' ) {
		if ( version_compare(phpversion(), '5.4.0', '>=') ) {
			return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
		} else {
			return session_id() === '' ? FALSE : TRUE;
		}
	}
	return FALSE;
}
if ( is_session_started() === FALSE ) {
	echo 'FALSE';
	$sid = $_POST['sid'];
	session_id($sid); // Применить сессию с конкретным ID полученным из POST
	session_start();
}
*/

$data_input = $_POST;
$data_output = [
	'success' => 0,
	'error' => ''
];
//mail('my_works@mail.ru', 'test delete', print_r($_POST,true). "\r\n". print_r($_SESSION, true));

# Провернить входящие данные
if (
	!isset( $data_input['name_deleted_file'] )
	|| !isset( $data_input['id_product'] )
	|| !isset( $data_input['hash_product'] )
) {
	$data_output['error'] = 'Файл не удален. Входящие данные неполные.';
} else {
	
	# Подключить конфигурацию приложения
	# Подключить класс "Дизайны"
	include_once __DIR__ . '/../app-config.php';
	include_once PATH_APP . '/lib/designs/Designs.php';
	
	$hash = Designs::get_hash_product($data_input['id_product']);
	if ( $data_input['hash_product'] != $hash ) {
		$data_output['error'] = 'Файл не удален. Входящие данные неверные.';
	}
	
}

# Удалить файл
if ($data_output['error'] == '') {
	$url_deleted_file = $_SERVER['DOCUMENT_ROOT'] . '/' . parse_url($_POST['name_deleted_file'], PHP_URL_PATH);
	if ( !file_exists( $url_deleted_file ) ) {
		$data_output['error'] = 'Файл не удален. Удаляемый файл не найден.';
	} {
		if (unlink( $url_deleted_file )) // удалиmь файл
			  $data_output['success'] = 1;
		else  $data_output['error'] = 'Не удалось удалить файл';
	}
	
}

//var_dump($data_output);

header( 'Access-Control-Allow-Origin: *' );
echo json_encode( $data_output );