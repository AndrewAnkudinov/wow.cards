// CROP


// СОЗДАТЬ ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ
var cropper = false;  // Обрезчик

// СОЗДАТЬ NAMESPACE CROPPING ЧЕРЕЗ ФУКНКЦИЮ
var	cropping = function() {
//var	cropping = {
	//console.log(cropper);

	// СОЗДАТЬ ПЕРЕМЕННЫЕ ОБРЕЗКИ
	var
		$boxCrop = jQuery('#stepCrop'),
		$croppedMedia = false, // Редактируемое изображение
		fileType = false, // Тип редактируемого файла image/video
		imageCrop = document.getElementById('imageCrop'),  // Изображение редактора (холст)
		$videoCrop = $boxCrop.find('video'), // Видео редактора
		isHiddenCropping = false, // Флаг скрытой обрезки

		resolutionsFrames = {}, // Массив разрешений фреймов
		isNeedCropping = false, // Флаг необходимости обрезки
		isSelectionResolutionsFrames = false
		//isInit = false  // Флаг происходящего процесса обрезки
	;

	// Наполнить массив разрешений фреймов
	// Установить необходимость обрезки
	// Установить необходимость выбора разрешения обрезки
	var configCrop = jQuery('#configCrop').data();
	if (
		configCrop.widthHorizontalFrame != ''//undefined
		&& configCrop.heightHorizontalFrame != ''//undefined
	) {
		resolutionsFrames[configCrop.widthHorizontalFrame/configCrop.heightHorizontalFrame] = {
			'width': configCrop.widthHorizontalFrame,
			'height': configCrop.heightHorizontalFrame
		}
	}
	if (
		configCrop.widthVerticalFrame != ''//undefined
		&& configCrop.heightVerticalFrame != ''//undefined
	) {
		resolutionsFrames[configCrop.widthVerticalFrame/configCrop.heightVerticalFrame] = {
			'width': configCrop.widthVerticalFrame,
			'height': configCrop.heightVerticalFrame
		}
	}
	if (!jQuery.isEmptyObject(resolutionsFrames)) {
		isNeedCropping = true;
	}
	if (Object.keys(resolutionsFrames).length > 1)
		isSelectionResolutionsFrames = true;
	console.log(resolutionsFrames);
	// /СОЗДАТЬ ПЕРЕМЕННЫЕ ОБРЕЗКИ


	function getIdMedia($croppedMedia) {
		var idMedia = $croppedMedia.attr('id');
		console.log('idMedia = ', idMedia);
		return idMedia;
	}
	function getTextareaMedia($croppedMedia) {
		return $croppedMedia.closest('li').find('textarea')[0];
	}

	//
	// Трансформировать видео-превьюшку согласно данным обрезки
	function transformThumbVideo($img) {
		//var $img = jQuery(img);
		console.log($img);
		console.log($img.get(0));
		var dataCrop = JSON.parse($img.attr('data-crop'));
		var dataCropCanvas = JSON.parse($img.attr('data-crop-canvas'));
		console.log(dataCrop);

		var sizeMedia = {
			width: dataCropCanvas.naturalWidth,
			height: dataCropCanvas.naturalHeight
		};

		console.log(sizeMedia);
		/*
		var sizeMedia = {
			width: $img.attr('data-natural-width'),
			height: $img.attr('data-natural-height')
		};
		*/

		// Процент отступа видео от границ превью, относительно размеров превью
		var percentIndent = {
			x: dataCrop.x / dataCrop.width,
			y: dataCrop.y / dataCrop.height
		};

		// Процент высоты и ширины изображения
		var
			scaleX = sizeMedia.width / dataCrop.width,
			scaleY = sizeMedia.height / dataCrop.height;
		var cssThumb = {
			width: scaleX * 100 + '%',
			height: scaleY * 100 + '%'
		};
		/*
		var scale = Math.max(
			sizeMedia.width / dataCrop.width,
			sizeMedia.height / dataCrop.height
		);
		var cssThumb = {
			width: scale * 100 + '%',
			height: scale * 100 + '%'
		};
		*/

		cssThumb.left = (- percentIndent.x * 100) + '%';
		cssThumb.top = (- percentIndent.y * 100) + '%';

		/*
		var percentIndent = {
			x: dataCrop.x / sizeMedia.width,
			y: dataCrop.y / sizeMedia.height
		};

		var sizeThumb = {
			width: 80,
			//height: $img.height()
		};
		sizeThumb.height = dataCrop.height / dataCrop.width * sizeThumb.width;
		/*
		var scaleX = sizeMedia.width / dataCrop.width;
		var scaleY = sizeMedia.height / dataCrop.height;
		var scaleFactor = Math.max(scaleX, scaleY);
		*//*

		var cssThumb = {
			width: sizeThumb.width / dataCrop.width * sizeMedia.width,
			height: sizeThumb.height / dataCrop.height * sizeMedia.height
		};
		cssThumb.left = 0 - cssThumb.width * percentIndent.x;
		cssThumb.top = 0 - cssThumb.height * percentIndent.y;
*/
		console.log('dataCrop, sizeMedia, percentIndent, scaleX, scaleY, cssThumb: ',
			dataCrop, sizeMedia, percentIndent, scaleX, scaleY, cssThumb);

		var $video = $img.closest('.box_thumb_media').find('video');
		$video.css(cssThumb);
	}

	// Повернуть изображение на 90 градусов, вписав его полностью в холст
	function rotateImage(degree) {

		// Повернуть изображение, вписав его полностью в холст
		var contData = cropper.getContainerData();
		if (isSelectionResolutionsFrames) {
			cropper.setCropBoxData({  // Центрировать контур для правильного поворота
				width: 2, height: 2, top: (contData.height/ 2) - 1, left: (contData.width / 2) - 1
			});
		}
		
		cropper.rotate(degree);  // Повернуть на указанный угол относительно текущего угла

		// Вписать изображение в холст
		var canvData = cropper.getCanvasData();
		var newWidth = canvData.width * (contData.height / canvData.height);

		if (newWidth >= contData.width) {
			var newHeight = canvData.height * (contData.width / canvData.width);
			var newCanvData = {
				height: newHeight,
				width: contData.width,
				top: (contData.height - newHeight) / 2,
				left: 0
			};
		} else {
			var newCanvData = {
				height: contData.height,
				width: newWidth,
				top: 0,
				left: (contData.width - newWidth) / 2
			};
		}

		cropper.setCanvasData(newCanvData);
//		cropper.setCropBoxData(newCanvData);

	}

	function scrollToMedia(id) {

		var media = jQuery("#" + id);

		console.log('$(#boxMediaFiles>div).scrollTop():', $('#boxMediaFiles>div').scrollTop());
		var offsetShadow = 16;
		var offset = media.offset().top - $('#boxMediaFiles>div').offset().top - offsetShadow;  // Вычислить разность позиций контейнера для медиа и конкретной превьюшки
		console.log('media.offset().top, $(#boxMediaFiles>div).offset().top, offset: ', media.offset().top, $('#boxMediaFiles>div').offset().top, offset);
		//$('#boxMediaFiles').scrollTop = offset;
		$('#boxMediaFiles').animate({scrollTop: offset}, 'slow');

		/*
		var myElement = document.getElementById(id);
		console.log(myElement);
		var topPos = myElement.offsetTop;
		console.log(topPos);
		document.getElementById('boxMediaFiles>div').scrollTop = topPos;
		*/
		/*
		console.log(id);
		var posArray = $("#" + id).positionedOffset();
		$('#boxMediaFiles>div').scrollTop = posArray[1];
		*/
	}

	// Закрыть редактор
	function closeEditor() {
		//if (isHiddenCropping) {
			showStep('cropEnd');
		//}
		if (isNeedCropping == 1) {
			cropper.destroy();
			//console.log('cropper: ', cropper);
		}
		$croppedMedia = false;
		console.log('$croppedMedia: ', $croppedMedia);
	}

	// Записать данные обрезки изображения в атрибут HTML-элемента медиа-файла
	function writeDataCropToAttrMedia() {
		console.log($croppedMedia);
		//console.log(cropper);
		var dataCrop = cropper.getData();
		var dataCropCanvas = cropper.getCanvasData();
		console.log(dataCrop);
		console.log(dataCropCanvas);
		$croppedMedia.attr('data-crop', JSON.stringify(dataCrop));
		$croppedMedia.attr('data-crop-canvas', JSON.stringify(dataCropCanvas));

		// Добавить размеры фрейма в аттрибут обрезываемого изображения 2021-07-07
		var aspectRatioFrame = findСlosestAspectRatio(dataCropCanvas.naturalWidth, dataCropCanvas.naturalHeight);
		var resolutionFrame = resolutionsFrames[aspectRatioFrame];
		console.log(resolutionFrame);
		$croppedMedia.attr('data-resolution-frame', JSON.stringify(resolutionFrame));  // encodeURIComponent(JSON.stringify(resolutionFrame))

		/*
		// Вычислить и копировать ориентацию фрейма в HTML-аттрибут data исходного медиа-файла
		var canvasData = cropper.getCanvasData();

		var orientationFrame = false;
		if (canvasData.naturalWidth > canvasData.naturalHeight) {
			orientationFrame = 'horizontal';
		} else {
			orientationFrame = 'vertical';
		}
		if (orientationFrame != undefined) {
			$croppedMedia.attr('data-orientation-frame', orientationFrame);
		}
		*/
	}

	// Завершить обрезку
	function complete() {

		$boxCrop.hide();
		console.log('cropping.complete');
		/* 2021-12-17 Слишком нагружает консоль
		console.log('$croppedMedia.get(0): ', $croppedMedia.get(0));
		*/
		//var initialAvatarURL;

		if (isNeedCropping == 1) {
			var canvas;
			//console.log(cropper);
			writeDataCropToAttrMedia();  // Записать данные обрезки в HTML-аттрибуты обрезываемого изображения

			canvas = cropper.getCroppedCanvas({  // TODO: откуда взяты размеры?
				width : 300,
				height: 200,
				maxWidth : 300,
				maxHeight: 200,
				imageSmoothingEnabled: true,  // Повысить качество превьюшек
				imageSmoothingQuality: 'high'  // Повысить качество превьюшек
			});

			$croppedMedia.attr('src', canvas.toDataURL());  // Вставить обрезанное blob-изображение в HTML-объект с class='image'

			// Изменить превьюшку видео
			if (fileType == 'video') {
				transformThumbVideo($croppedMedia);
			}

		}

		// Копировать секунду начала видео в HTML-аттрибут data медиа-файла
		if (fileType == 'video') {
			$croppedMedia.attr('data-second', jQuery('#rangeMediaSegment').val());
		}

		//sliderCropping.cloneBoxMediaToSlider($croppedMedia);
		console.log('$croppedMedia = ', $croppedMedia, new Date());
		$videoCrop.attr('src', '#false');

		// Копировать подпись из редактора в список медиа-файлов
		var textarea = getTextareaMedia($croppedMedia);
		textarea.value = jQuery('#stepCrop textarea').val();

		var idMedia = getIdMedia($croppedMedia);
		closeEditor();
		autoHeight_(textarea);  // TODO: пересчитать высоты всех textarea после showStep('stepListMedia');
		scrollToMedia(idMedia);

		// Обрезать следующее загруженное, но необрезанное изображение (если редактор скрыт)
		if (isHiddenCropping) {
			var img = vars.boxMediaFiles.querySelector('img[data-type=image]:not([data-crop-canvas])'); // Найти следующее необрезанное изображение
			console.log(img);
			if (img === null) {
				showStep('stepListMedia');
				console.log('removeClass processing');
				jQuery('#stepListMedia').removeClass('processing');
			}
			else {
				cropping.createCrop(img);
			}
		}

	}

	// Синхронизировать координаты обрезаемого изображения-заглушки и видео
	function syncCoordsImgNVideo() {
		var $img = $boxCrop.find('.cropper-canvas');
		//console.log($img);
		//console.log($videoCrop);
		$videoCrop.attr("style", $img.attr("style"));
	}

	// Найти и вернуть соотношение сторон контура из предустановленных, ближайшее к заданным высоте и ширине
	function findСlosestAspectRatio(width, height) {
		var aspectRatio = 0;
		var similarityRatio = 0; // Коэффициент схожести соотношений сторон изображения и контура
		var maxSimilarityRatio = 0; // Коэффициент схожести соотношений сторон изображения и контура
		var aspectRatioImage = width/height;

		for (var key in resolutionsFrames) {
			//resolutionsFrames.forEach(function (key) {
			similarityRatio = aspectRatioImage / key;
			console.log('similarityRatio: ', similarityRatio);
			if (similarityRatio > 1)  // Привести коэффициент к отношению большего числа к меньшему
				similarityRatio = 1/similarityRatio;
			console.log('similarityRatio: ', similarityRatio);
			if (similarityRatio > maxSimilarityRatio) {
				maxSimilarityRatio = similarityRatio;
				aspectRatio = key;
			}
		}
		//});

		/*
		var aspectRatio = configCrop.widthSlideshow / configCrop.heightSlideshow;
		if (
			/*orientationFrame == undefined
			&&*//* configCrop.widthHorizontalFrame != undefined
			&& configCrop.heightHorizontalFrame != undefined
			&& configCrop.widthVerticalFrame != undefined
			&& configCrop.heightVerticalFrame != undefined
		) {
			console.log('width, height: ', width, height);
			if (width > height) {
				aspectRatio = configCrop.widthHorizontalFrame / configCrop.heightHorizontalFrame;
			} else {
				aspectRatio = configCrop.widthVerticalFrame / configCrop.heightVerticalFrame;
			}
		}
		*/

		return aspectRatio;
	}

	// Создать обрезчик
	function createCrop(image, options) {

		console.log('$croppedMedia: ', $croppedMedia);
/*
		// Отложить иницилазацию редактора, если он занят
		if ($croppedMedia) {
			console.log('!!!');
			var createCropTimeout = setTimeout(function () {
				createCrop(image, options);
			}, 80);
			return false;
		}
*/
		//console.log(cropper);
		console.log(cropping.imageCrop);
		console.log(jQuery(cropping.imageCrop));

		if (cropper != false) {
			cropper.destroy();  // Разрушить прошлое редактирование на всякий случай
		}
		//console.log(cropper);

		$croppedMedia = jQuery(image);
		//console.log('$croppedMedia = ' + $croppedMedia + new Date());

		console.log(image);
		console.log(imageCrop);

		imageCrop.src = image.getAttribute('data-src');

		// Особености редактирования видео
		fileType = $croppedMedia.attr('data-type');
		if (fileType == 'video') {
			$boxCrop
				.removeClass('image')
				.addClass('video');
			$videoCrop
				.attr('src', image.getAttribute('data-file'))
				.attr('data-second', $croppedMedia.attr('data-second'));  // Передать выбранную ранее секунду медиа-сегмента
			$videoCrop[0].load();
		}
		else
			$boxCrop
				.removeClass('video')
				.addClass('image');

		// Не обрезать файл если не требуется
		if (isNeedCropping == 0) {
			closeEditor();
			return;
		}

		// Установить флаг скрытой обрезки обрезаемого изображения
		if ($boxCrop.is(":hidden"))
			isHiddenCropping = true;
		else
			isHiddenCropping = false;


		// Получить данные прошлой обрезки изображения
		var
			attrData = image.getAttribute('data-crop')  // Данные контура
			//attrCanvasData = image.getAttribute('data-crop-canvas') // Данные изображения
		;
		if (attrData != null) { // data attribute doesn't exist
			attrData = JSON.parse(attrData);
			console.log(attrData);
		}
		/* На wow.cards данные холста не нужны (изображение всегда всписываем в холст сами)
		if (attrCanvasData != null) { // data attribute doesn't exist
			attrCanvasData = JSON.parse(attrCanvasData);
			console.log(attrCanvasData);
		}
		*/

		// Создать первичное соотношение сторон
		var aspectRatio = 16/9;
		if (attrData) {
			aspectRatio = attrData.width/attrData.height;
		}

		var optionsCrop = {
			dragMode                : 'none',  // Определите режим перетаскивания обрезки.
			aspectRatio             : aspectRatio, // 9 / 16
			autoCropArea            : 1,
			movable                 : false,

				// Зумы (4 шт.)
				scalable: false, // Включите масштабирование изображения.
				zoomable: false,  // Включите, чтобы увеличить изображение.
				zoomOnTouch: false,
				zoomOnWheel: false,

			restore                 : true,  // Восстановить обрезанную область после изменения размера окна
			guides                  : false, // Показать пунктирные линии над рамкой обрезки
			center                  : false,  // Показать центральный индикатор над рамкой кадрирования
			highlight               : false,
			cropBoxMovable          : true,
			cropBoxResizable        : true,
			toggleDragModeOnDblclick: false,
			//checkOrientation        : false, // Обнаруживать переернутые jpeg-файлы (на 2021-05-21 опция не работает в последних версиях Chrome и Firefox 77)

			viewMode                : 2,

			/*
			dragMode                : 'move',
			aspectRatio             : aspectRatio,  // 9 / 16
			autoCropArea            : 1,
			restore                 : false,
			guides                  : true,
			center                  : false,
			highlight               : false,
			cropBoxMovable          : false,
			cropBoxResizable        : false,
			toggleDragModeOnDblclick: false,
			viewMode                : 1,
			*/

			//data: attrData,
			//data: {"x":153.27019614361703,"y":103.36622132646275,"width":184.640625,"height":328.25,"rotate":0,"scaleX":1,"scaleY":1},
			//ready: function() { cropper.setCanvasData(dataCropCanvas); }
			//ready: function() { cropper.setCanvasData({"left":100,"top":16.46875}) }
			ready                   : function () {

				// Уточнить ориентацию контура
				// СОЗДАТЬ РАМКУ ЕСЛИ ИЗОБРАЖЕНИЕ РАНЕЕ НЕ ОБРЕЗАЛОСЬ
				if (!attrData)
				{
					console.log($croppedMedia);

					// Поменять пропорцию контура по умолчанию на пропорцию для вертикальных или горизонтальных фреймов
					var canvasData = cropper.getCanvasData();
					var aspectRatio = findСlosestAspectRatio(canvasData.naturalWidth, canvasData.naturalHeight);
					cropper.setAspectRatio(aspectRatio);
					console.log('aspectRatio:', aspectRatio);
					console.log('optionsCrop.aspectRatio:', optionsCrop.aspectRatio);
					console.log('optionsCrop:', optionsCrop);
				}


				//console.log('attrData, attrCanvasData: ', attrData, attrCanvasData);
				// Повернуть изображение на угол предыдущей обрезки
				else
				{
					// Вычислить расхождение углов поворота предыдущей и текущей обрезок (вдруг старый глючный браузер)
					var cropData = cropper.getData();
					var differenceAngle = attrData.rotate - cropData.rotate;
					if (differenceAngle != 0)
						rotateImage(differenceAngle);
					var aspectRatio = findСlosestAspectRatio(attrData.width, attrData.height);
					cropper.setAspectRatio(aspectRatio);
					//cropper.setCanvasData(attrCanvasData);  // setCanvasData конфликтует с setData, запущенным одновременно
					cropper.setData(attrData);
				}

				console.log($boxCrop);
				syncCoordsImgNVideo(); // Синхронизировать координаты обрезаемого изображения-заглушки и видео

				// Завершить авто-обрезку (если редактор скрыт)
				if (isHiddenCropping) {
					complete();
				}

			},
			crop                 : function () {
				syncCoordsImgNVideo(); // Синхронизировать координаты обрезаемого изображения-заглушки и видео
			}
			/*
			cropend                 : function () {
				$croppedMedia = false;
				console.log('$croppedMedia = ' + $croppedMedia + new Date());
			}
			*/

		};
		if (options != undefined)
			optionsCrop = Object.assign(optionsCrop, options);

		/* 2021-12-17 Слишком нагружает консоль
		console.log('image: ', image);
		*/
		console.log('image.naturalWidth: ', image.naturalWidth);
		console.log('image.naturalHeight: ', image.naturalHeight);
		console.log('imageCrop: ', imageCrop);
		console.log('imageCrop.naturalWidth: ', imageCrop.naturalWidth);
		console.log('imageCrop.naturalHeight: ', imageCrop.naturalHeight);


		imageCrop.addEventListener('cropend', function () {
			console.log('cropend!!!');
			complete();
		});

		cropper = new Cropper(image);

		cropper = new Cropper(imageCrop, optionsCrop);
		//console.log('cropper: ', cropper);
		console.log(imageCrop);

		// Копировать подпись к медиа-файлу из списка в редактор
		var textarea = getTextareaMedia($croppedMedia);
		var text = textarea.value;
		jQuery('#stepCrop textarea').val(text);

	}

	// Открыть обрезчик
	function openCropper(image) {
		//$croppedMedia = jQuery(image);
		//sliderCropping.setActiveSilde(image);
		imageCrop.src = '';  // Очистить изображение, иначе мелькает на милисекунду
		imageCrop.src = image.getAttribute('data-src');
		showStep('stepCrop');
		$boxCrop.show();
		createCrop(image);

	}

	// CROPPING: СОБЫТИЯ, ПРИ КОТОРЫХ ОТКРЫВАЕТСЯ МОДАЛЬНОЕ ОКНО РЕДАКТОРА ОБРЕЗКИ ИЗОБРАЖЕНИЯ
	// Клик по миниатюре
	jQuery('#boxMediaFiles ul').on('click', '.box_thumb_media', function () {

		// Вывести модальное окно если происходит загрузка файлов
		if (jQuery('#stepListMedia').hasClass('processing')) {
			jQuery('#modalProcessing').modal('show');
			return;
		}

		var media = this.querySelector('img');
		if (
			isNeedCropping == 1
			|| media.getAttribute('data-type') == 'video'
		) {
			openCropper(media);
		}
	});

	// Повернуть изображение на 90 градусов, с сохранением рамки
	jQuery('#rotateLeft').click(function () {

		// Получить даннные контура обрезки до поворота
		var cropData = cropper.getData();

		rotateImage(90);

		if (!isSelectionResolutionsFrames)
			return;

		// Повернуть контур, сохранив позицию
		var aspectRatio = cropData.height/cropData.width;
		console.log('aspectRatio: ', aspectRatio, 1/aspectRatio);
		var canvData = cropper.getCanvasData();
		var newCropBoxData = {
			width: cropData.height,
			height: cropData.width,
			x: canvData.naturalWidth - cropData.y - cropData.height,
			y: cropData.x,
			rotate: cropData.rotate + 90,
			scaleX: 1,
			scaleY: 1
		};
		cropper.setAspectRatio(aspectRatio);
		cropper.setData(newCropBoxData);

	});

	// Удалить медиа-файл
	jQuery('#deleteMedia').on('click', function () {
		var $btnDelete = $croppedMedia.closest('li').find('button');
		$btnDelete.click();
		closeEditor();
	});

	// Выйти из редактора без изменений
	jQuery('#closeEditor').click(function () {
		closeEditor();
	});

	// Сохранить изменения медиа-файла и закрыть редактор по клику на кнопку
	jQuery('#btnCrop, #btnCrop2').click(function () {
		setTimeout(function () {
			complete();
		}, 800)
	});

	// Если нажал энтер - то значит, была нажата кнопа "готово"
	jQuery('#stepCrop textarea').keyup(function(e) {
		var code = e.keyCode ? e.keyCode : e.which;
		if (code == 13) {  // Enter keycode
			jQuery('#btnCrop').click();
		}
	});

	// Return public data and functions
	return {

		// Data
		imageCrop: imageCrop,
		//cropper: cropper,
		//$croppedMedia: $croppedMedia, // Редактируемое изображение

		// Functions
		createCrop: createCrop

	}

}();