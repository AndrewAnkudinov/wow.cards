<?

# Загрузить файл

error_reporting(E_ERROR);
include(__DIR__ . '/../app_config.php');

$sid = $_POST['sid'];
if ($sid) {
	session_id($sid);
} // Применяем сессию с конкретным ID полученным из POST

//if ($_POST['sid'])
//mail('my_works@mail.ru', 'weeze upload POST', print_r($_POST, true));

if (!isset($_SESSION))
	session_start();

// если идентификатор пользователя отсутствует, назначим ему
if (!isset($_SESSION['BX_USER_IDENT'])) {
	if ($_POST['uident'])
		$_SESSION['BX_USER_IDENT'] = $_POST['uident'];
	else
		$_SESSION['BX_USER_IDENT'] = uniqid();

	//  mail('my_works@mail.ru','lost ident',print_r($_POST,true).print_r($_SESSION, true));
}
//mail('my_works@mail.ru','lost ident',print_r($_POST,true));
if (!isset($_SESSION['BX_CLIP_ID'])) {
	$_SESSION['BX_CLIP_ID'] = $_POST['clip_id'];
}

$sesTmp = $_SESSION;

//  require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if ($_POST) {
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	// Каталог в который будет загружаться файл
	//echo "\r\n" . '$uploadDir = ' .
		$uploadDir = PATH_TMP_USERFILES . '/' . $sesTmp['BX_USER_IDENT'];//.'/'.$_POST['clip_id'] . '/'
	//создадим корневую папку юзера, если её нет
	if (!is_dir($uploadDir)) {
		mkdir($uploadDir, 0755);
	}
	//создадим папку проекта, если её нет
	if (!is_dir($uploadDir . '/' . $_POST['clip_id'])) {
		@mkdir($uploadDir . '/' . $_POST['clip_id'], 0755);
	}
	$uploadDir = $uploadDir . '/' . $_POST['clip_id'];
	$filename = $_POST['name'];
	$f = fopen($uploadDir . '/' . $filename, "a");
	fputs($f, file_get_contents($_FILES['chunk']['tmp_name']));
	fclose($f);

	if (filesize($uploadDir . '/' . $filename) == $_POST['size'])
	{
		$filenameTmp = md5(microtime() . rand(0, 9999));
		$ext = getExtension($filename);
		$filenameNew = $filenameTmp . '.' . $ext;
		rename(
			$uploadDir . '/' . $filename,
			$uploadDir . '/' . $filenameNew
		);
		//unlink($uploadDir.$filename);

		$return = array(
			'src' => URL_TMP_USERFILES . '/' . $sesTmp['BX_USER_IDENT'] . '/' . $_POST['clip_id'] . '/' . $filenameNew,
			'file_id' => $filenameTmp
		);

		$arFileType = explode('/', finfo_file($finfo, $_SERVER['DOCUMENT_ROOT'] . $return['src']));
		$type = 'audio';
		if ($arFileType[0] == 'image' || $arFileType[0] == 'video') {
			$type = 'media';
		}

		$_SESSION['BX_UPLOADED_CLIPS'][$_POST['clip_id']][$type][$filenameTmp] = array(
			'src' => $return['src'],
			'file_id' => $return['file_id'],
			'ext' => getExtension($filename),
			'name' => $filename,
			'type' => ($arFileType[0] == "application") ? "audio" : $arFileType[0],
		);
	} else {
		$return = array('file_id' => md5($filename), 'file_size' => $_POST['size']);
	}
	if (!file_exists(PATH_TMP_USERFILES . '/' . $sesTmp['BX_USER_IDENT'] . '/' . $_POST['clip_id'] . '/flag.txt')) {
		@file_put_contents(PATH_TMP_USERFILES . '/' . $sesTmp['BX_USER_IDENT'] . '/' . $_POST['clip_id'] . '/flag.txt', '1');
	}
	//var_dump($_POST);
	//var_dump($_SESSION);
	header('Access-Control-Allow-Origin: *');
	echo json_encode($return);
}

function getExtension($filename)
{
	return substr(strrchr($filename, '.'), 1);
}