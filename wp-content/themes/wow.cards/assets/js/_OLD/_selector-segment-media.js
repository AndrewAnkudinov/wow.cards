// МЕДИА УТИЛИТЫ

var	selectorSegmentMedia = function() {

	var	$boxEditorMedia = jQuery('#stepCrop');
	var mediaUser = {
		player: $boxEditorMedia.find('video').get(0), //document.querySelector('#audioUser'),
		$bar: jQuery('#barMediaUser'),
		isPressedBar: false,
		segment: {
			duration: 20,  // Длительность медиа-отрезка для сторис в секундах
			firstSecond: 0, // Первая секунда выделенного отрека пользовательского аудио
			$range: jQuery('#rangeMediaSegment'),
			printFirstSecond: document.getElementById('printFirstSecond')
		}
	};
	mediaUser.segment.lastSecond = mediaUser.segment.duration; // Последняя секунда выделенного отрека пользовательского аудио

	// Переключить иконки плей/стоп
	function toggleIconPlay(icon)	{
		var $videoIconElement = $boxEditorMedia.find('.play-button i');
		if (icon == 'play') {
			$videoIconElement.removeClass('fa-stop fa-pause').addClass('fa-play');
		}
		else if (icon == 'stop') {
			$videoIconElement.removeClass('fa-play fa-pause').addClass('fa-stop');
		}
		else if (icon == 'pause') {
			$videoIconElement.removeClass('fa-play fa-stop').addClass('fa-pause');
		}
	}

	// ЗАМЕНИТЬ АУДИО НА ПОЛЬЗОВАТЕЛЬСКОЕ

	/* mediaUser.player
	// Получить плеер пользовательского видео
	function getPlayerMediaUser() {
		return $boxEditorMedia.find('video').get(0);
	}

	// Получить полоску пользовательского видео
	function getBarMediaUser() {
		return jQuery('#barUserVideo');
	}
	*/

	// Воспроизвести пользовательское видео
	function playMediaUser() {
		mediaUser.player.play();
		toggleIconPlay('pause');
	}

	// Поставить на паузу пользовательское аудио
	function pauseMediaUser() {
		mediaUser.player.pause();
		toggleIconPlay('play');
	}

	// Дополнить число меньше, чем 10, нулем спереди
	function paddingNumberZeros(number) {
		if (number < 10)
			number = '0' + number;
		return number;
	}

	// Форматировать время
	function formatTime(time) {
		var secondsInHour = 60;
		var minuts = Math.trunc(time / secondsInHour);
		var seconds = Math.trunc(time % secondsInHour);
		var formattedTime = paddingNumberZeros(seconds);
		return minuts + ':' + paddingNumberZeros(seconds);
	}

	// Показать текущее время пользовательского аудио
	function showCurrentTimeMedia(time) {
		jQuery('#currentTimeUserMedia').text(formatTime(time));
	}

	/*
	// Показать полное время пользовательского аудио
	function formatTotalTimeMedia(durationMedia) {
		if (durationMedia > 0) {
			console.log('formatTime(durationMedia): ', formatTime(durationMedia));
			return formatTime(durationMedia);
		}
		else {
			return '__:__';
		}
	}
	/* 2020-10-20 Вместо этого теперь formatTotalTimeMedia()
	// Показать полное время пользовательского аудио
	function showTotalTimeUserMedia() {
		var player = mediaUser.player;
		var intervalCurrentTimeUserVideo = setInterval(function () {
			var playerDuration = Number(mediaUser.player.duration);
			console.log(playerDuration);
			if (playerDuration > 0) {
				mediaUser.duration = playerDuration;
				console.log('formatTime(mediaUser.duration): ' + formatTime(mediaUser.duration));
				jQuery('#totalTimeUserMedia').text(formatTime(mediaUser.duration));
				clearInterval(intervalCurrentTimeUserVideo);
			}
			else {
				mediaUser.duration = false;
				jQuery('#totalTimeUserMedia').text('__:__');
			}
		}, 1000);
	}
	*/

	// Настроить input type="range" под пользовательский аудио-файл
	function adjustInputRange(durationUserAudio) {

		var maxInputRange = Math.max(0, durationUserAudio - mediaUser.segment.duration);
		var percentWidthScrollRange = mediaUser.segment.duration/durationUserAudio * 100;
		var classScrollRange = 'scrollRange' + Math.round(percentWidthScrollRange);
		console.log(
			'mediaUser.segment.duration, maxInputRange, percentWidthScrollRange, classScrollRange: ',
			mediaUser.segment.duration, maxInputRange, percentWidthScrollRange, classScrollRange
		);

		// Создать CSS-класс бегунка исходя из длительности аудио
		$("<style type='text/css'> #rangeMediaSegment." + classScrollRange + "::-webkit-slider-thumb"
			+ " { width: " + percentWidthScrollRange + "%;} </style>").appendTo("head");
		mediaUser.segment.$range
			.attr('max', maxInputRange)
			.attr('class', classScrollRange)
		;

		/*
		// Создать изображение аудио-волны
		wavesurfer = WaveSurfer.create({
			container: '#waveform',
			waveColor: '#515253',
			progressColor: 'transparent'
		});
		wavesurfer.load(mediaUser.player.src);
		*/

		// Изменить поле текущего времени
		mediaUser.$bar.attr('max', durationUserAudio);
		mediaUser.segment.printFirstSecond.setAttribute('max', maxInputRange);

		/*
		var lengthDocumentStyleSheets = document.styleSheets[1].rules.length;
		console.log(lengthDocumentStyleSheets);
		for (var j = 0; j < lengthDocumentStyleSheets; j++) {
			var rule = document.styleSheets[1].rules[j];
			console.log(rule);
			if (rule.cssText.match("webkit-slider-thumb")) {
				console.log(rule);
				rule.style.width = '100px';
			}
		}
		*/
	}

	// Поменять аудио отрезок
	function highlightMedia() {

		// Выделить цветом отрезок аудио
		var
			secondsVideoTotal = Number(mediaUser.segment.$range.attr('max')) + mediaUser.segment.duration,
			secondsVideoCurrent = Number(mediaUser.segment.$range.val()),
			secondsSliderThumbCss = {
				'left' : secondsVideoCurrent / secondsVideoTotal * 100 + '%',
				'right': (secondsVideoTotal - (secondsVideoCurrent + mediaUser.segment.duration)) / secondsVideoTotal * 100 + '%'
			};
		console.log(secondsVideoTotal);
		console.log(secondsVideoCurrent);
		jQuery('#highlightVideoSegment').css(secondsSliderThumbCss);

		// Поменять начальную и конечную секунды выделенного отрезка пользовательского аудио
		mediaUser.segment.firstSecond = secondsVideoCurrent;
		mediaUser.segment.lastSecond = secondsVideoCurrent + mediaUser.segment.duration;
		console.log('mediaUser.segment.firstSecond: ' + mediaUser.segment.firstSecond);
		console.log('mediaUser.segment.lastSecond: ' + mediaUser.segment.lastSecond);
		//document.getElementById('printFirstSecond').innerHTML = secondsVideoCurrent;
		mediaUser.segment.printFirstSecond.value = secondsVideoCurrent;
	}

	// Иинициализировать редактор пользовательского медиа-файла
	function initEditorMediaUser() {
		var durationMediaUser = mediaUser.player.duration;
		console.log('durationMediaUser: ', durationMediaUser);

		// TODO: Выставить начальное время медиа-файла
		mediaUser.segment.firstSecond = mediaUser.player.getAttribute('data-second');
		mediaUser.player.currentTime = mediaUser.segment.firstSecond;
		console.log('mediaUser.segment.firstSecond: ', mediaUser.segment.firstSecond);

		adjustInputRange(durationMediaUser);
		highlightMedia();
		showCurrentTimeMedia(0);
		//showTotalTimeUserMedia();

		// Показать полное время пользовательского медиа-файла
		var formattedDurationUserMedia = '__:__';
		if (durationMediaUser > 0) {
			console.log('formatTime(durationMediaUser): ', formatTime(durationMediaUser));
			formattedDurationUserMedia = formatTime(durationMediaUser);
		}
		jQuery('#totalTimeUserMedia').text(formattedDurationUserMedia);
		
		jQuery('#userVideoFigure').show().click();
		//$boxEditorMedia.modal('show');  // TODO: не рисутеся гистограмма, если окно скрыто
		mediaUser.$bar.attr({'max': durationMediaUser});
	}

	// Обработать смену медиа-отрезка
	mediaUser.segment.$range.on('change, input', function () {
		highlightMedia();
		console.log(jQuery(this).val());
	});

	// Обработать события пользовательского аудио
	jQuery(mediaUser.player)

		// Обновлять текущее время пользовательского аудио при его воспроизведении
		.on('timeupdate', function () {

			var currentTime = Math.round( $(this).get(0).currentTime );

			// Начать воспроизведение с начала выделенного аудио-отрезка
			// если текущее время достигло его конца
			if (
				currentTime == mediaUser.segment.lastSecond
				&& !mediaUser.isPressedBar
			) {
				resetTimeSegmentMediaUser();
				return false;
			}

			console.log('currentTime: ', currentTime);
			showCurrentTimeMedia(currentTime);
			if (!mediaUser.isPressedBar)
				mediaUser.$bar.val(currentTime);
		})

		// ... окончание воспроизведения
		.on('ended', function () {
			beginPlaySegmentUserVideo();
			this.play();
		})

		// ... после загрузки аудио-файла в браузер
		.on('loadedmetadata', function () {
			//runAfterUploadUserVideo();
			initEditorMediaUser();
		})
	;

	// Перевести время пользовательского аудио-файла на начало выбранного отрезка
	function resetTimeSegmentMediaUser() {
		//mediaUser.$bar.val(mediaUser.segment.firstSecond);

		console.log('mediaUser.segment.firstSecond: ', mediaUser.segment.firstSecond);
		mediaUser.player.currentTime = mediaUser.segment.firstSecond;
	}

	// Поменять текущее время пользовательского медиа-файла
	// ...после смены значения полоски
	// ...после смены значения поля input
	mediaUser.$bar
		.on('change', function () {
			var playTime = Math.round( $(this).get(0).value );
			console.log(playTime);
			mediaUser.player.currentTime = playTime;
		})

		.on('mousedown', function () {
			mediaUser.isPressedBar = true;
		})

		.on('mouseup', function () {
			mediaUser.isPressedBar = false;
		})
	;
	jQuery(mediaUser.segment.printFirstSecond)
		.on('change', function () {
			var playTime = Math.round( $(this).get(0).value );
			console.log(playTime);
			mediaUser.segment.$range.val(playTime);
		});

	// События медиа-редактора
	$boxEditorMedia

		// Ставить пользовательский медиа-файл на паузу при закрытии редактора (модального окна)
		.on('hide.bs.modal', function (e) {
			pauseMediaUser();
		})

		// Плей/пауза после клика по кнопке Play/Pause
		.find('.play-button')
		.on('click', '.fa-play', function () {
			playMediaUser();
		})
		.on('click', '.fa-pause', function () {
			pauseMediaUser();
		})
		.end()

		// Поменять уровень громкости пользовательского аудио
		.find('#volumeUserMedia').on('change, input', function () {
		console.log(jQuery(this).val());
		mediaUser.player.volume = jQuery(this).val() / 100;
	})
	;

	// Return public data and functions
	/*
	return {

		// Data
		//cropper: cropper,
		//cropping.$activeCropImage: cropping.$activeCropImage, // Редактируемое изображение

		// Functions
		initEditorMediaUser: initEditorMediaUser

	};
	*/

	// /ЗАМЕНИТЬ АУДИО НА ПОЛЬЗОВАТЕЛЬСКОЕ
};
selectorSegmentMedia();