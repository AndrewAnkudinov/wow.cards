<?php
	//очистить записи папки TEMP старше 30 минут
	$files = array_diff(scandir('TEMP/'), array('.', '..'));
	foreach ($files as $file){
		$filetime = filemtime(realpath('TEMP/') . '/' . $file);
		if ($filetime < time() - 30*60){
			unlink(realpath('TEMP/') . '/' . $file);
		}
	}

	//Получить картинку для обратного возврата
	$uniq = uniqid();
	if (!move_uploaded_file($_FILES['photo']['tmp_name'], 'TEMP/' . $uniq . '-photo.png')) exit('error upload file');
	if (!move_uploaded_file($_FILES['text']['tmp_name'], 'TEMP/' . $uniq . '-text.png')) exit('error upload file');
	$frames = $_POST["frames"];
	$decor = $_POST["decor"];
	$logo = $_POST["logo"];

	$_SERVER['HTTP_HOST'] == 'localhost' ? define('FFMPEG', __DIR__ . '/Utiles/ffmpeg.exe') : define('FFMPEG', __DIR__ . '/Utiles/ffmpeg1');
	if ($decor == 'undefined'){
		$ffmpeg = FFMPEG . ' -y -i TEMP/' . $uniq . '-photo.png -i TEMP/' . $uniq . '-text.png -i logo/' . $logo . ' -framerate 10 -i "images/' . $frames . '/Render_%5d.png" -filter_complex "[0][3]overlay[PHOTO]; [PHOTO][1]overlay[TEXT]; [TEXT][2]overlay[out]; [out]split[o1][o2]; [o1]palettegen[p]; [o2]fifo[o3]; [o3][p]paletteuse" TEMP/' .$uniq . '.gif';
	}else{
		$ffmpeg = FFMPEG . ' -y -i TEMP/' . $uniq . '-photo.png -i TEMP/' . $uniq . '-text.png -framerate 10 -i "images/' . $frames . '/Render_%5d.png" -framerate 10 -i "images/' . $decor . '/Render_%5d.png" -i logo/' . $logo . ' -filter_complex "[0][2]overlay[PHOTO]; [PHOTO][1]overlay[TEXT]; [TEXT][3]overlay[DECOR]; [DECOR][4]overlay[out]; [out]split[o1][o2]; [o1]palettegen[p]; [o2]fifo[o3]; [o3][p]paletteuse" TEMP/' .$uniq . '.gif';
	}
	exec($ffmpeg . ' 2>&1', $out, $rezult);
	if ($rezult != 0) exit('error step1 = ' . print_r($out));
	echo('TEMP/' .$uniq . '.gif');
?>