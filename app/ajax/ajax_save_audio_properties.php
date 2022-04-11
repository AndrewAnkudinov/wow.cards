<?php

# WIDGET: Установить email заказа

/*
 * Подключить ключевые файлы WP
 */
require_once (__DIR__ . '/../../wp-config.php');
require_once (__DIR__ . '/../../wp-includes/post.php');
/*
require_once (__DIR__ . '/../../wp-includes/formatting.php');
require_once (__DIR__ . '/../../wp-content/plugins/fromfoto/fromfoto.php');
*/

//var_dump($_POST);

if (!isset($_POST['id_product'], $_POST['url_audio'])) {
	echo 0;
	die;
}
$id_product = intval($_POST['id_product']);
$url_audio = $_POST['url_audio'];
$uri_parts = explode('?', $url_audio, 2);
$my_post = array(
	'ID'           => $id_product,
	'post_excerpt' => $uri_parts[0]
);
wp_update_post( $my_post );

// Обновить данные в БД
if (isset($_POST['second_start_audio']))
	update_post_meta($id_product, 'second_start_audio', $_POST['second_start_audio']);


// РЕНДЕР ВИДЕО С АУДИО

$id_test_product = 100045;

//Получить данные о заказе
$data_product = get_post( $id_product );
$path_audio_file = realpath($_SERVER['DOCUMENT_ROOT'] . $data_product->post_excerpt);
$post_meta = get_post_meta( $id_product );
$second_start_audio = $post_meta['second_start_audio'][0];
if (!isset($second_start_audio))
    $second_start_audio = 0;

if ($id_product == $id_test_product) {
    echo $id_product;
    echo $path_audio_file;
    echo $_SERVER['DOCUMENT_ROOT'] . $data_product->post_excerpt;
    echo realpath($_SERVER['DOCUMENT_ROOT'] . $data_product->post_excerpt);
    var_dump($data_product);
}


if (is_file($path_audio_file))
{
    # Файл успешно получен
    $video_files_paths = [  // Адреса исходных и новых файлов
        realpath(PATH_READY_USERFILES) . '/' . $id_product . FF_SUFFIX . '_preview.mp4' =>
            realpath(PATH_READY_USERFILES) . '/' . $id_product . FF_SUFFIX . '_swapaudio.mp4',
    ];

    $is_create_afade   = false;  // Флаг создания затухания
    if (URL_USERFILES == substr($data_product->post_excerpt, 0, strlen(URL_USERFILES))) {
        $is_create_afade = true;
    }


    $effect_duration = 5; // Длительность эффекта затухания звука
    $cumulative_result = true;
    $rezult            = false;
    $out               = false;
    foreach ($video_files_paths as $video_file_path => $new_video_file_path)
    {
        if (!is_file($video_file_path))
            continue;

        exec( '/usr/bin/ffprobe -v error -select_streams v:0 -show_entries stream=duration -of'
            . ' default=noprint_wrappers=1:nokey=1 ' . $video_file_path . ' 2>&1', $position );

        # Тестовый заказ
        if ($second_start_audio) {

            # Создать ffmpeg-код затухания
            $exec_afade = '';
            if ($is_create_afade) {
                $position = intval($position[0]) - $effect_duration;
                $exec_afade = ", afade=t=out:st=" . $position . ":d=" . $effect_duration;
            }
            /*
                            exec("/usr/bin/ffmpeg -y -i " . $video_file_path . " -itsoffset 10 -i " . $path_audio_file . " -filter_complex '[1:a]apad"
                                . $exec_afade . "[outa]' -map 0:v -map '[outa]'"
                                . " -strict -2 -vcodec copy -shortest " . $new_video_file_path . " 2>&1",
                                $out,
                                $rezult);
                            */


            $exec_code = "/usr/bin/ffmpeg -y -ss " . $second_start_audio . " -t 15 -i \"" . $path_audio_file . "\" -i \"" . $video_file_path . "\""
                . " -map 0:a -map 1:v -vcodec copy -f mp4 -strict -2 \"" . $new_video_file_path . "\" 2>&1";

            /*
            $exec_code = "/usr/bin/ffmpeg -y -ss " . $second_start_audio . " -t 15 -i \"" . $path_audio_file . "\" -i \"" . $video_file_path . "\""
            . " -filter_complex '[0:a]apad"	. $exec_afade . "[outa]'"
            . " -map '[outa]' -map 1:v -vcodec copy -f mp4 -strict -2 \"" . $new_video_file_path . "\" 2>&1";
            */
            var_dump($exec_code);

            exec($exec_code,
                $out,
                $rezult);

            echo "\r\n" . '$out: ';
            echo "\r\n";
            var_dump($out);
            echo "\r\n" . '$rezult: ';
            echo "\r\n";
            var_dump($rezult);

        }

        else
        {

            # Создать ffmpeg-код затухания
            $exec_afade = '';
            if ($is_create_afade) {
                $position = intval($position[0]) - $effect_duration;
                $exec_afade = ", afade=t=out:st=" . $position . ":d=" . $effect_duration;
            }

            exec( "/usr/bin/ffmpeg -y -i " . $video_file_path . " -i " . $path_audio_file
                . " -filter_complex '[1:a]apad"	. $exec_afade . "[outa]'"
                . " -map 0:v -map '[outa]' -strict -2 -vcodec copy -shortest " . $new_video_file_path . " 2>&1",
                $out,
                $rezult );
        }


        if ( $rezult == 0 ) {
            $cumulative_result = $rezult;
            continue;
        }
    }
    /*
    if ( $rezult == 0 ) {
        #Усешный ffmpeg
        echo( 'ready' );
    } else {
        #Ошибка ffmpeg
        echo( 'Результат - ' . $rezult );
        echo( '<pre>' );
        print_r( $out );
        echo( '</pre>' );
    }
    */

} else {
    //echo "Файл не получен!";
}



echo 1;
die;