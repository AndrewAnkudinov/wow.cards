<?

# ФАЙЛ КОНФИГУРАЦИЙ ДЛЯ ПРОГРАММНЫХ ВКЛЮЧЕНИЙ (ТИПА AJAX-файлов) САЙТА

@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '150' );

define('FF_SUFFIX', '_wo'); // Суффикс заказа  // _en
define('SECRET_KEY', 'fromfoto'); // Серктеное слово для шифрованного хэша (например, /app/get_video.php выдача видео с защитой от скачивания)

$dirname_app = 'app';
$dirname_userfiles = 'USERFILES';
$dirname_tmp_userfiles = 'tmp';
$dirname_ready_userfiles = 'ready';
$dirname_backup_userfiles = 'backup';
//$dirname_audio_userfiles = 'audio';

define('URL_ROOT_WO_SLASH', '');  // URL корня сайта относительно домена без закрывающего слеша (слеш и имя подпапки, если есть, можно добавлять к $_SERVER['DOCUMENT_ROOT'])
define('URL_ROOT', URL_ROOT_WO_SLASH ? URL_ROOT_WO_SLASH : '/');  // URL корня сайта относительно домена (слеш, имя подпапки, если есть)
define('PATH_ROOT', $_SERVER['DOCUMENT_ROOT'] . URL_ROOT_WO_SLASH);  // URL корня сайта относительно домена (слеш и имя подпапки, если есть)
define('URL_APP', URL_ROOT_WO_SLASH . '/' . $dirname_app); // Абсолютный путь к папке app
define('PATH_APP', $_SERVER['DOCUMENT_ROOT'] . URL_APP); // URL к папке app
define('URL_USERFILES', URL_APP . '/' . $dirname_userfiles);
define('URL_TMP_USERFILES', URL_USERFILES . '/' . $dirname_tmp_userfiles);
define('PATH_TMP_USERFILES', $_SERVER['DOCUMENT_ROOT'] . URL_TMP_USERFILES);
define('URL_READY_USERFILES', URL_USERFILES . '/' . $dirname_ready_userfiles);
define('PATH_READY_USERFILES', $_SERVER['DOCUMENT_ROOT'] . URL_READY_USERFILES);
define('URL_BACKUP_USERFILES', URL_USERFILES . '/' . $dirname_backup_userfiles);
define('PATH_BACKUP_USERFILES', $_SERVER['DOCUMENT_ROOT'] . URL_BACKUP_USERFILES);
define('URL_AUDIO_USERFILES', URL_READY_USERFILES);
define('PATH_AUDIO_USERFILES', PATH_READY_USERFILES);
//define('URL_AUDIO_USERFILES', URL_USERFILES . $dirname_audio_userfiles);
//define('PATH_AUDIO_USERFILES', $_SERVER['DOCUMENT_ROOT'] . URL_AUDIO_USERFILES);

define('PATH_ORDERFILES', '/home/admin/zak/wow.cards');
//define('DEFAULT_UPLOAD_ZAKAZ_PATH', PATH_ORDERFILES);  // Старое длинное название папки с исходными файлами заказа, хорошо бы от него отказаться
//define('DEFAULT_UPLOAD_TMP_PATH', PATH_TMP_USERFILES);
//define('DEFAULT_UPLOAD_TMP_DIR', URL_TMP_USERFILES);

define('PRODUCT_PRICE', 149);  // Цена продукта
define('URL_PAYPAL', 'https://www.sandbox.paypal.com/cgi-bin/websc');  // Sandbox - 'https://www.sandbox.paypal.com/cgi-bin/websc', Online - 'https://www.paypal.com/cgi-bin/websc'
define('EMAIL_PAYPAL', 'iljanew2@yandex.ru'); // Email получателя платежа(на него зарегестрирован paypal аккаунт)