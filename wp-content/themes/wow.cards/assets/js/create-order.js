"use strict";


/*
 TODO: после заливки нового изображения на сервер выполняются функции:
 UploadingFile.onUploadEnd() =>
	 View.setPreview()
	 Loader.onFileLoadEnd() =>
	 	снова UploadingFile.startUpload()
	 	или Loader.stopLoading() =>
	 		cropping.createCrop()

  */


// ЧТО_ТО ТИПА ФРЕЙМВОРКА ДЛЯ СОЗДАНИЯ КЛАССОВ В JAVASCRIPT (ООП)
// ДОКУМЕНТАЦИИ НЕ ОБНАРУЖЕНО :( НО, ПОХОЖЕ, ЧТО ЭТО СДЕЛАЛ https://babeljs.io/
// ВСЕГО СОЗДАНО 4 КЛАССА: View, UploadingFile, ProgressBar, Texts

function dispatch(name) {
	var detail = arguments.length <= 1 || arguments[1] === undefined ? {} : arguments[1];

	document.body.dispatchEvent(new CustomEvent(name, {
		detail: detail
	}));
}

var classCallCheck = function (instance, Constructor) {
	if (!(instance instanceof Constructor)) {
		throw new TypeError("Cannot call a class as a function");
	}
};

var createClass = function () {
	function defineProperties(target, props) {
		for (var i = 0; i < props.length; i++) {
			var descriptor = props[i];
			descriptor.enumerable = descriptor.enumerable || false;
			descriptor.configurable = true;
			if ("value" in descriptor) descriptor.writable = true;
			Object.defineProperty(target, descriptor.key, descriptor);
		}
	}

	return function (Constructor, protoProps, staticProps) {
		if (protoProps) defineProperties(Constructor.prototype, protoProps);
		if (staticProps) defineProperties(Constructor, staticProps);
		return Constructor;
	};
}();

// /ЧТО-ТО ТИПА ФРЕЙМВОРКА ДЛЯ СОЗДАНИЯ КЛАССОВ В JAVASCRIPT (ООП)


// СОЗДАТЬ ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ

function q(id) {
	return document.getElementById(id);
}
/*
jQuery.fn.serializeObject = function() {
	var o = {};
	var a = this.serializeArray();
	jQuery.each(a, function() {
		if (o[this.name]) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};
*/
// scrollToAnchor
function scrollToAnchor(aid) {
	var aTag = jQuery("a[name='" + aid + "']");
	jQuery('html,body').animate({scrollTop: aTag.offset().top}, 'slow');
	//return false;
}
/*
function scrollToId(id) {
	var aTag = jQuery("#" + id);
	jQuery('html,body').animate({scrollTop: aTag.offset().top}, 'slow');
	//return false;
}
*/


// Auto Height Textarea
function autoHeight_(element) {
	console.log(element);
	console.log('element.scrollHeight: ', element.scrollHeight);
	return jQuery(element).css({
		'height'    : 'auto',
		'overflow-y': 'hidden'
		//}).height(jQuery(element).prop('scrollHeight'));
	}).height(element.scrollHeight);
}

// /ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ


// СОЗДАТЬ ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ
var vars =
	{
		FF_root             : '', // Корневая папка сайта
		idOrder             : '',
		idNewDesign         : false,
		configPage          : jQuery('#configPage').data(),
		/*
		$circularBar        : jQuery('#circularBar'),
		$circularBarPercent = jQuery('#circularBarPercent'),
		$progress	  = jQuery('.progress'),
		$progressBar  = jQuery('.progress-bar'),
		*/
		$emailOrder         : jQuery('#emailOrder'),
		btnCompleteUpload   : q('btnCompleteUpload'),
		//btnChooseDesign     : q('btnChooseDesign'),
		btnSubmit           : q('btnSubmit'),
		boxMediaFiles       : q('boxMediaFiles')
	};

console.log(vars);

// Показать/скрыть шаги создания заказа
function showStep(visible) {
	console.log(visible);

	var eventsToSteps = {
		//stepProcessing: 'stepProcessing',
		stepStart: 'stepStart',
		stepProcessing: 'stepListMedia', //
		stepListMedia: 'stepListMedia',
		cropEnd: 'stepListMedia',
		stepChooseDesign: 'stepChooseDesign',
		stepChooseFreeDesign: 'stepChooseFreeDesign',
		stepChooseFreeDesign2: 'stepChooseFreeDesign2',
		stepEmail: 'stepEmail'
	};
	/*
		// wow.cards: Пропустить шаг смены дизайна, если тип дизайна - не слайдшоу
		if (vars.configPage.typeDesign != 'slideshow') {
			eventsToSteps.stepChooseDesign = eventsToSteps.stepEmail;
		}
		//if (!(visible in eventsToSteps))
		//	return;
	*/
	jQuery('#content .step').each( function( index, element ) {
		var $element = jQuery(element);
		if (element.id == eventsToSteps[visible]) {
			$element.show();
		} else {
			$element.hide();
		}
	} );

}

// Добавить/Исправить номера картинок
function addNumbers() {
	var elementsList = vars.boxMediaFiles.querySelectorAll('li .box_thumb_media');
	Array.from(elementsList).forEach(function (item, i, arr) {
		item.querySelector(".imageNumber").innerHTML = (i+1);
		//item.style.opacity = 1;
	});
}


// ===================================

/* var ses_id;
 function set_ses_key(ses) {
 ses_id = ses;
 }
 console.log(ses());*/
//console.log(document.cookie);
var ses_id = getSessId('ses_var');
var ses_ident = getSessId('ses_ident');
if (ses_id === undefined) {

	ses_id = vars.configPage.ses;
	//console.log(ses_id);
}
if (ses_ident === undefined) {
	ses_ident = vars.configPage.uident;
	//console.log(ses_id);
}
//console.log(ses_id);
function getSessId(key) {
	var params = document.cookie.split('; ').map(function (params) {
		return params.split('=')
	});
	for (var j = params.length - 1; j >= 0; j--) {
		if (params[j][0] === key) {
			return params[j][1]
		}
	}
}


// КЛАСС View - ПОКАЗ ЗАГРУЖЕННОГО ФАЙЛА
// Вызывается только в классе UploadingFile и в дальшейшем использует через + объект this.view

var View = function () {
	function View(_ref) {
		console.log(_ref);
		var type = _ref.type;
		var name = _ref.name;
		var ind = _ref.ind;
		var idRestoredFile = _ref.idRestoredFile;  // 2021-05-12 Для загрузки файлов из библиотеки
		classCallCheck(this, View);

		var slashInd = type.indexOf('/');
		this.type = slashInd !== -1 ? type.substring(0, slashInd) : type;
		this.name = name;
		this.ind = ind;
		this.idRestoredFile = idRestoredFile;  // 2021-05-12 Для загрузки файлов из библиотеки

		this.render();
	}

	// Создать HTML-элементы загруженного изображения
	createClass(View, [{

		// Создать болванку превьюшки в процессе закачки файла
		// Функция всегда вызывается при создании нового объекта типа View, как автозагрузчик
		key  : 'render',
		value: function render() {
			console.log(this);

			var li = document.createElement('li');
			li.className = 'col-6 col-md-4 col-xl-3 d-flex align-items-center justify-content-center flex-column loading';
			li.title = this.name;

			var imageCaptionInput = document.createElement('textarea');
			//var imageCaptionInput = document.createElement('input');
			//imageCaptionInput.type = 'text';
			imageCaptionInput.name = 'image_captions[]';
			imageCaptionInput.setAttribute('maxlength', 40);
			imageCaptionInput.setAttribute('rows', 1);
			imageCaptionInput.setAttribute('data-ind', this.ind);
			if (this.idRestoredFile !== undefined) {  // 2021-05-12 Для загрузки файлов из библиотеки
				imageCaptionInput.value = vars.configPage.oldOrder.files[this.idRestoredFile].text;
			}
			li.appendChild(imageCaptionInput);

			// create boxThumbMedia
			var boxThumbMedia = document.createElement('div');
			boxThumbMedia.className = 'box_thumb_media shadow rounded';

			var img = document.createElement('img');
			img.id = 'image' + this.ind;
			if (this.idRestoredFile !== undefined) {  // 2021-05-12 Для загрузки файлов из библиотеки

				// Привести значения обрезки к числам
				var dataCrop = {};
				jQuery.each(vars.configPage.oldOrder.files[this.idRestoredFile].dataCrop, function(index, value) {
					dataCrop[index] = parseFloat(value);
				});

				img.setAttribute('data-crop', JSON.stringify(dataCrop));
				//img.setAttribute('data-crop', '{"x":869.8765347752311,"y":451.261024501732,"width":64.39811393843038,"height":36.22393909036709,"rotate":0,"scaleX":1,"scaleY":1}');
				//img.setAttribute('data-crop-canvas', '{"left":-2812.475400560688,"top":-1505.0481573679117,"width":5072.612328040934,"height":3381.741552027289,"naturalWidth":1500,"naturalHeight":1000}');
				//console.log('Restored data-crop:', JSON.stringify(dataCrop));
				//console.log('Restored data-crop:', '{"x":869.8765347752311,"y":451.261024501732,"width":64.39811393843038,"height":36.22393909036709,"rotate":0,"scaleX":1,"scaleY":1}');
			}
			boxThumbMedia.appendChild(img);

			boxThumbMedia.insertAdjacentHTML("beforeend",
				'<div class="icons"><span class="material-icons">content_cut</span><span style="font-size: 24px; margin: 0 20px;">T+</span></div>' +
				'<div class="imageNumber"></div>' +
				'<div class="progress-holder"><div class="fileuploader-progressbar"><div class="bar"></div></div></div>');
			li.appendChild(boxThumbMedia);
			// /create boxThumbMedia
			//console.log('li:', li);

			var delBtn = document.createElement('button');
			delBtn.type = 'button';
			delBtn.className = 'del_media';
			delBtn.setAttribute('data-ind', this.ind);
			li.appendChild(delBtn);

			li.addEventListener('dragover', addNumbers);
			this.els = {  // Ключевые HTML-элементы этой превьшки
				el    : li,
				delBtn: delBtn,
				imageCaptionInput: imageCaptionInput
			};
			console.log('li, this.els:', li, this.els);
			return this;
		}
	}, {
		// Вырезать заглушку под размер видео
		key  : 'createBlobStub',
		value: function createBlobStub(width, height) {
			var canvas = document.createElement("canvas");
			canvas.width = width;
			canvas.height = height;
			return canvas.toDataURL();
		}
	}, {

		// View.setPreview()
		// Заполнить превьюшку медиа-файла пользователя после его загрузки на сервер
		// Запускается только из UploadingFile.onUploadEnd()
		key  : 'setPreview',
		value: function setPreview(wError, src, width, height) {

			var _this = this;

			function createBlobStub(width, height) {
				var canvas = document.createElement("canvas");
				canvas.width = width;
				canvas.height = height;
				return canvas.toDataURL();
			}

			console.log('setPreview');
			/*width = 300;
			height = 300;*/
			console.log(wError, src, width, height);
			var el = this.els.el;
			var mediaImg = el.querySelector('img');
			var mediaImgSrc = src;

			el.classList.remove('loading');
			if (wError === true) {
				el.classList.add('wError');
				return;
			}
			el.querySelector('.progress-holder').style.display = 'none';

			// Создать служебные аттрибуты залитого изображения
			jQuery(mediaImg).attr({
				'data-type'          : this.type,
				'data-natural-width' : width,  // img.naturalWidth не работает, если элемент скрыт, поэтому получаем размеры посредством PHP
				'data-natural-height': height,
				'data-file': src
			});

			/* 2021-12-17 Слишком нагружает консоль
			console.log(mediaImg);
			console.log(mediaImg.src);
			*/

			if (mediaImg.src) {  // Предотвратить повторное заполнение превью TODO: а нельзя ли раньше прервать повтор?
				return;
			}

			var attrImg = {};

			// Особенности видео
			if (this.type == 'video') {

				// Создать видео-превьюшку (пустое изображение, которые будет обрезаться вместо видео)
				var mediaVideo = document.createElement('video');
				var boxThumbMedia = el.querySelector('.box_thumb_media');
				boxThumbMedia.insertBefore(mediaVideo, mediaImg);

				// Выполнить после загрузки видео в браузер
				mediaVideo.addEventListener("loadedmetadata", function (e) {
					console.log(mediaVideo);
					console.log("width:", mediaVideo.videoWidth);
					console.log("height:", mediaVideo.videoHeight);
					console.log(this);
					var width = mediaVideo.videoWidth;
					var height = mediaVideo.videoHeight;
					mediaImgSrc = createBlobStub(width, height);
					console.log(mediaImgSrc);
					attrImg['data-second'] = 0;
					console.log(mediaVideo);




					attrImg['data-src'] = mediaImgSrc;  // Сохранить ссылку на необрезанное изображение
					console.log(attrImg);

					jQuery(mediaImg).attr(attrImg);

					// Обрезать медиа-файл
					mediaImg.onload = _this.createCrop(mediaImg);

					// Вставить изображение в HTML-элемент медиа-фигуры в последнюю очередь
					mediaImg.src = mediaImgSrc;

				});

				mediaVideo.src = src;
				console.log(mediaVideo);
/*
				mediaVideo.onload = function () {
					var width = mediaVideo.videoWidth;
					var height = mediaVideo.videoHeight;
					mediaImgSrc = this.createBlobStub(width, height);
					attrImg['data-second'] = 0;
					console.log(mediaVideo);
				};
*/

			} else {

				attrImg['data-src'] = mediaImgSrc;  // Сохранить ссылку на необрезанное изображение
				console.log(attrImg);

				jQuery(mediaImg).attr(attrImg);

				// Обрезать медиа-файл
				mediaImg.onload = this.createCrop(mediaImg);

				/*function () {
					console.log(this);
					//var img = vars.boxMediaFiles.querySelector('img:not([data-crop-canvas])');
					mediaFile.onload = null;
					cropping.createCrop(mediaImg);  // TODO: подвисает скроллинг
				};*/

				// Вставить изображение в HTML-элемент медиа-фигуры в последнюю очередь
				mediaImg.src = mediaImgSrc;

			}

		}
	}, {

		// Запустить скрытую обрезку всех необрезанных изображений
		key  : 'createCrop',
		value: function createCrop(img) {
			/* 2021-12-17 Слишком нагружает консоль
			console.log(img);
			*/
			img.onload = null;
			/*
			var width = img.naturalWidth;
			var height = img.naturalHeight;
			img.setAttribute('data-natural-width', width);
			img.setAttribute('data-natural-height', height);
			*/
			cropping.createCrop(img);  // TODO: подвисает скроллинг
		}
	}, {

		// Вставить болванку превьшки в HTML-список превьшек
		// Вызывается только из Loader.pushFilesForDownload()
		key  : 'append',
		value: function append(parent) {
			console.log('this.els, parent: ', this.els, parent);
			this.els.parent = parent;
			this.els.parent.appendChild(this.els.el);
		}
	}, {
		key  : 'destroy',
		value: function destroy() {
			this.els.parent.removeChild(this.els.el);
			for (var j = this.els.length - 1; j >= 0; j--) {
				this.els[j] = null;
			}
			this.els = null;
		}
	}]);
	return View;
}();
console.log(View);

// CLASS VIEW - ПОКАЗ ЗАГРУЖЕННОГО ФАЙЛА


// CLASS UPLOADING FILE (ЗАГРУЗКА ФАЙЛА)
// this - объект с данными загружаемого файла
// Автоматом добавляет в this экземпляр класса View

var UploadingFile = function () {
	function UploadingFile(_ref) {
		var _ref$file = _ref.file;
		var file = _ref$file === undefined ? {} : _ref$file;
		var ind = _ref.ind;
		var type = _ref.type;
		var name = _ref.name;
		var src = _ref.src;
		var id = _ref.id;

		// 2021-05-12 Для загрузки файлов из библиотеки
		var uploadType = _ref.uploadType;
		var filesize = _ref.filesize;
		var idRestoredFile = _ref.idRestoredFile;

		classCallCheck(this, UploadingFile);

		this.ind = ind;
		this.file = file;
		this.start = 0;
		this.xhr = null;
		this.count = 0;
		this.id = id !== undefined ? id : null;
		this.wError = false;
		this.size = file.size;

		// 2021-05-12 Для загрузки файлов из библиотеки
		this.uploadType = uploadType;
		if (uploadType !== undefined) {
			this.size = filesize;
			this.idRestoredFile = idRestoredFile;
		}

		this.type = type || file.type;
		this.name = name || file.name.replace(/ /g, '_');
		this.view = new View({  // Создать экземпляр класса View (вызовется функция View.render())
			type   : this.type,
			name   : this.name,
			ind    : this.ind,
			idRestoredFile: this.idRestoredFile // 2021-05-12 Для загрузки файлов из библиотеки
		});

		console.log('UploadingFile, this: ', this);
	}

	createClass(UploadingFile, [
		{
			key  : 'getChunk',
			value: function getChunk() {
				return this.file.slice(this.start, this.next(), this.type);
			}
		}, {
			key  : 'next',
			value: function next() {
				var start = this.start;
				var fSize = this.size;
				var CHUNK_SIZE = 524288 * 8;  // Размер загружаемых фрагментов файла
				var newStart = start + CHUNK_SIZE > fSize ? fSize : start + CHUNK_SIZE;
				this.currChunkSize = newStart - this.start;
				this.start = newStart;
				return newStart;
			}
		}, {
			key  : 'startUpload',  // Начать загрузку очередного файла пользователя
			value: function startUpload() {
				console.log('startUpload: ', this);
				this.loadChunk();
			}
		}, {

			// UploadingFile.loadChunk()
			// Грузить кусок файла, вызывается из startUpload() и onLoad()
			key  : 'loadChunk',
			value: function loadChunk() {
				/*
				function collectFD(file) {
					console.log('file: ', file);
					var fd = new FormData();  // Создать новую HTML-форму
					fd.append('clip_id', vars.configPage.idInitialDesign);
					fd.append('name', file.name);
					fd.append('size', file.size);
					fd.append('sid', ses_id);
					fd.append('uident', ses_ident);

					// 2021-05-21 Для загрузки файлов из библиотеки
					fd.append('upload_type', file.uploadType);
					fd.append('id_restored_file', file.idRestoredFile);

					if (file.id !== null) {
						fd.append('file_id', file.id);
					}

					var currentChunk = file.getChunk();
					fd.append('chunk', currentChunk);
					return fd;
				}
				*/

				console.log('loadChunk: ', this);

				// Пропустить уже закаченный файл или закончить загрузку файла, если размер закаченной части файла совпадает с его полным размером
				if (this.start === this.size) {
					this.delXhr();
					this.onUploadEnd();
					return;
				}

				//this.fd = this.collectFD(this); // Собрать данные о загружаемом файле для обработки AJAX-запросом
				this.collectFD();
				console.log(this);
				console.log(this.fd.name);

				this.delXhr();

				var URL_CREATE = vars.FF_root + '/app/ajax/ajax-upload.php';
				this.xhr = new XMLHttpRequest();  // Сделать HTTP-запросы к серверу без перезагрузки страницы. Документация: https://learn.javascript.ru/xmlhttprequest
				this.xhr.open("POST", URL_CREATE, true);
				this.xhr.onload = this.onLoad.bind(this); // Обработать ответ
				this.xhr.onerror = this.onError.bind(this);
				this.xhr.upload.onprogress = this.onProgress.bind(this);
				this.xhr.send(this.fd);  // Отправить данные
			}
		}, {
			key  : 'collectFD',  // Собрать данные о файле для его отправки на закачку
			value: function collectFD() {
				var file = this;
				console.log('file: ', file);
				var fd = new FormData();  // Создать новую HTML-форму
				fd.append('clip_id', vars.configPage.idInitialDesign);  // TODO: Зачем загрузчику файла знать о idDesign? По моему незачем.
				fd.append('name', file.name);
				fd.append('size', file.size);
				fd.append('sid', ses_id);
				fd.append('uident', ses_ident);

				// 2021-05-21 Для загрузки файлов из библиотеки
				fd.append('upload_type', file.uploadType);
				fd.append('id_restored_file', file.idRestoredFile);

				if (file.id !== null) {
					fd.append('file_id', file.id);
				}

				var currentChunk = file.getChunk();
				fd.append('chunk', currentChunk);
				this.fd = fd;
				//return fd;
			}
		}, /* {
			key  : 'collectFD',  // Собрать данные о файле для его отправки на закачку
			value: function collectFD(file) {
				console.log('file: ', file);
				var fd = new FormData();  // Создать новую HTML-форму
				fd.append('clip_id', vars.configPage.idInitialDesign);  // TODO: Зачем загрузчику файла знать о idDesign? По моему незачем.
				fd.append('name', file.name);
				fd.append('size', file.size);
				fd.append('sid', ses_id);
				fd.append('uident', ses_ident);

				// 2021-05-21 Для загрузки файлов из библиотеки
				fd.append('upload_type', file.uploadType);
				fd.append('id_restored_file', file.idRestoredFile);

				if (file.id !== null) {
					fd.append('file_id', file.id);
				}

				var currentChunk = file.getChunk();
				fd.append('chunk', currentChunk);
				this.fd = fd;
				//return fd;
			}
		}, {
				key  : 'collectFD',  // Собрать коллекцию данных о файле для его отправки на закачку
				value: function collectFD() {
					this.fd = new FormData();  // Создать новую HTML-форму
					this.fd.append('clip_id', vars.configPage.idInitialDesign);
					this.fd.append('name', this.name);
					this.fd.append('size', this.size);
					this.fd.append('sid', ses_id);
					this.fd.append('uident', ses_ident);

					// 2021-05-21 Для загрузки файлов из библиотеки
					this.fd.append('upload_type', this.uploadType);
					this.fd.append('id_restored_file', this.idRestoredFile);

					if (this.id !== null) {
						this.fd.append('file_id', this.id);
					}

					this.currentChunk = this.getChunk();
					this.fd.append('chunk', this.currentChunk);
				}
			},*/ {
			key  : 'delXhr',
			value: function delXhr() {
				if (this.xhr !== null) {
					this.xhr.onload = null;
					this.xhr.onerror = null;
				}
				this.xhr = null;
			}
		}, {
			key  : 'onLoad',  // Вызывается после загрузки каждой части (куска) загружаемого файла
			value: function onLoad(ev) {
				console.log('ev: ', ev);
				this.clear();

				if (ev.target.status !== 200) {
					this.onError();
					return;
				}

				var chunkSize = this.currChunkSize;

				var resp = JSON.parse(ev.target.responseText);  // JSON ответ процедуры загрузки файла
				console.log('JSON.parse(ev.target.responseText): ', resp);
				// if ( this.count === 0 ) {
				this.id = resp.file_id ? resp.file_id : this.id;
				// this.count++
				// }

				this.src = resp.src !== undefined ? resp.src : null;
				this.width = resp.width !== undefined ? resp.width : null;
				this.height = resp.height !== undefined ? resp.height : null;
				this.loadChunk();

				dispatch('chunk:loaded', chunkSize);
			}
		}, {
			//Прогресс выгрузки файла
			key  : 'onProgress',
			value: function onProgress(ev) {
				var done = this.start;
				var total = this.size;
				var present = Math.floor(done / total * 100);
				//console.log(present);
				this.view.els.el.querySelector('.bar').style.width = present + '%';
			}
		}, {
			key  : 'onError',
			value: function onError() {
				this.clear();
				this.wError = true;
				this.onUploadEnd();
			}
		}, {
			key  : 'clear',
			value: function clear() {
				this.delXhr();
				this.currentChunk = null;
				this.fd = null;
			}
		}, {

			// UploadingFile.onUploadEnd()
			// Выполнить после загрузки файла на сервер
			// Первая из функций, вызывающихся после загрузки файла на сервер
			// Вызывается из loadChunk() или onError() (успешной или не успешной загрузки)
			key  : 'onUploadEnd',
			value: function onUploadEnd() {

				//showMinMax();

				console.log('onUploadEnd: ', this);
				var wError = this.wError;
				this.clear();
				this.file = null;

				// !!! DEL
				console.log(this);
				this.view.setPreview(  // Использует метод класса View
					wError,
					this.src,
					this.width,
					this.height
				);

				dispatch('file:loadend', JSON.stringify({
					wError : wError,
					ind    : this.ind
				}));
			}
		}, {
			key  : 'delete',
			value: function _delete() {
				console.log('UploadingFile delete');
				var _this = this;
				var URL_DELETE = vars.FF_root + '/app/ajax/ajax-delete-uploaded.php';
				fetch(URL_DELETE, {
					method : 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body   : 'file_id=' + this.id + '&sid=' + ses_id + '&uident=' + ses_ident
				}).then(function () {
					_this.destroy();

					dispatch('file:deleted');

				});
				//cropping.clear(); 2021-06-05 Зачем очищать редактор резки при удалении файла?
			}
		}, {
			key  : 'destroy',
			value: function destroy() {
				console.log('UploadingFile destroy');
				this.view.destroy();
				this.view = null;
				var ind = this.coll.indexOf(this);
				this.coll[ind] = null;
				this.coll.splice(ind, 1);
				delete this.coll;
			}
		}
	]);
	return UploadingFile;
}();

// /CLASS UPLOADING FILE


// CLASS PROGRESSBAR

var ProgressBar = function () {
	function ProgressBar(el) {
		classCallCheck(this, ProgressBar);

		this.el = el;
		this.findEls();
		this.attrs = {
			speed    : 0,   // Скорость загрузки
			time     : 0,    // Время загрузки
			procent  : 0, // Процент загружен
			loaded   : 0,  // ??? Это св-во при второй и далее загрузке меньшего файла после большего.
			totalSize: 0  // Размер закачиваемого файла
		};
		this.timer = null;
		this.el.style.display = 'none';
		//vars.$circularBar.hide(); Не надо
	}

	createClass(ProgressBar, [{
		key  : 'findEls',
		value: function findEls() {
			var _this = this;

			var q = function q(sel) {
				return _this.el.querySelector(sel);
			};
			this.els = {
				speed    : q('.speed'),
				time     : q('.time'),
				procent  : q('.procent'),
				loaded   : q('.loaded'),
				totalSize: q('.totalSize'),
				barLine  : q('.bar .loaded')
			};
		}
	}, {
		key  : 'calcSpeed',
		value: function calcSpeed() {
			var perSecond = this.attrs.loaded / this.attrs.time;
			perSecond = this.humanizeSize(perSecond);
			perSecond += '/s';
			this.els.speed.textContent = perSecond;
		}
	}, {
		key  : 'calcProcents',
		value: function calcProcents() {

			// Weezy: Остановить искусственную анимацию прогресс-бара
			//console.log(circularBarInterval);
			//clearInterval(circularBarInterval);

			if (this.attrs.loaded === 0) {
				this.els.procent.textContent = 0;
				return;
			}
			var pr = this.attrs.loaded / this.attrs.totalSize * 100;
			pr = pr.toFixed(0);
			this.els.procent.textContent = pr;
			//vars.$circularBar.attr('data-value', pr);
			//$circularBarPercent.text(pr);
			//circularBarSet();  // Weezy: Запустить естественную анимацию прогресс-бара
			this.els.barLine.style.width = pr + '%';
		}
	}, {
		key  : 'startTimer',
		value: function startTimer() {

			/*
			 circularBarInterval = setInterval(function () {  // Запустить искусственную анимацию прогресс-бара
			 circularBarHandmade();
			 }, 500);
			 */

			var _this2 = this;

			//vars.$circularBar.show();
			//this.el.style.display = 'block';

			if (this.timer !== null) return;
			this.timer = setInterval(function () {
				var seconds = _this2.attrs.time;
				_this2.els.time.textContent = _this2.parseSeconds(seconds);
				_this2.attrs.time++;
				_this2.calcSpeed();
			}, 1000);
		}
	}, {
		key  : 'stopTimer',
		value: function stopTimer() {
			window.clearInterval(this.timer);
			this.timer = null;
		}
	}, {
		key  : 'humanizeSize',
		value: function humanizeSize(bytes) {
			var SIZES = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
			bytes = parseInt(bytes);
			if (isNaN(bytes)) return '';
			if (bytes === 0) return '0 ' + SIZES[1];
			var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
			if (i === 0) return bytes + ' ' + SIZES[i];
			var result = (bytes / Math.pow(1024, i)).toFixed(1);

			result += ' ' + SIZES[i];

			return result;
		}
	}, {
		key  : 'parseSeconds',
		value: function parseSeconds(seconds) {

			function toStr(num) {
				return num < 10 ? '0' + num : '' + num;
			}

			seconds = parseInt(seconds, 10);
			var mm = Math.floor(seconds / 60);
			var ss = seconds - mm * 60;
			var dates = {
				mm: mm,
				ss: ss
			};

			Object.keys(dates).forEach(function (key) {
				dates[key] = toStr(dates[key]);
			});

			return dates.mm + ':' + dates.ss;
		}
	}, {
		key  : 'reset',  // Сбросить значения прогресс-бара
		value: function reset() {
			setTimeout(function () {
				//console.log(this.attrs, this.attrs.loaded);
				this.stopTimer();
				this.el.style.display = 'none';
				this.attrs.loaded = 0;
				this.attrs.totalSize = 0;
			}.bind(this), 100);  // Таймуат, чтобы успел обнулиться loaded - размер загрузки порции файла
		}
	}, {
		key: 'loaded',  // Загрузить порцию файла
		get: function get() {
			return this.attrs.loaded;
		},
		set: function set(size) {
			this.attrs.loaded = size;
			this.els.loaded.textContent = this.humanizeSize(size);
			this.calcProcents();
		}
	}, {
		key: 'totalSize',  // Получить полный вес загружаемого файла
		get: function get() {
			return this.attrs.totalSize;
		},
		set: function set(size) {
			this.attrs.totalSize = size;
			this.els.totalSize.textContent = this.humanizeSize(size);
			this.calcProcents();
		}
	}]);
	return ProgressBar;
}();

// /CLASS PROGRESSBAR


// NAMESPACE "LOADER"
// Общие функции и объект photoNVideo, управляющие загрузкой файлов

var Loader = {
	init: function init() {
		console.log('Loader init');
		this.addLis();
		this.makeSortable();
	},

	photoNVideo: {  // Свойства загружаемого контента (фото и видео, музыка)
		isLoading: false,  // Индикатор процесса загрузки файла
		maxLength: vars.configPage.maxNumberFiles,
		startInd : 1,
		els      : {
			btn     : document.getElementsByClassName('btn-upload-media'),
			btnRestore  : q('btnRestore'),  // 2021-05-12 Для загрузки файлов из библиотеки
			inp     : q('inputUploadMedia'),
			list    : vars.boxMediaFiles.querySelector('ul'),
			counterMedia : q('counterMedia')
		},
		items    : [],  // Коллекция медиа-файлов
		progress : new ProgressBar(q('fotoUploadProgress'))
	},

	// Добавить прослушивание событий для кнопкок загрузки файлов
	addLis: function addLis() {
		console.log('Loader addLis this:', this);
		var _this = this;
		var photoNVideo = _this.photoNVideo;

		// Обработать клики по кнопкам загрузки, отправить список файлов в функции pushFilesForDownload() и startDownload()

		// Нажать программно на скрытое поле выбора файлов input type=file после клика на видимые красивые кнопки
		for (var i=0; i < photoNVideo.els.btn.length; i++) {
			photoNVideo.els.btn[i].onclick = function(){
				photoNVideo.els.inp.click();
				$(photoNVideo.els.btnRestore).hide(); // TODO
			}
		};

		// Запустить загрузку файлов после изменения скрытого поля выбора файлов input type=file
		photoNVideo.els.inp.onchange = function (ev) {
			_this.pushFilesForDownload(ev.target.files === undefined ? ev.target.value : ev.target.files); // Подготовить файлы юзера для загрузки
			if (photoNVideo.pushedCount !== 0) {  // Начать загрузку файлов юзера
				_this.startDownload();
			}
		};

		// Запустить загрузку файлов из библиотеки файлов на сервере
		photoNVideo.els.btnRestore.onclick = function () {

			jQuery(photoNVideo.els.btnRestore).hide();

			var objectUploadFiles = {
				uploadType: 'lib',
				sizes: {}
			};
			console.log(objectUploadFiles);
			jQuery.each(vars.configPage.oldOrder.files, function(index, element) {
				console.log(this);
				//var type = 'image/png';
				var serverFile = new File([], this.src, {type: this.type, size: this.size});  // size нельзя установить вручную, приходится передавать это значение отдельно

				// `await` can only be used in an async body, but showing it here for simplicity.
				//var serverFile = getFileFromUrl('https://wow.cards' + this.src, 'example.jpg');

				objectUploadFiles[index] = serverFile;
				objectUploadFiles.sizes[index] = this.size;
				console.log(objectUploadFiles[index]);
				//objectUploadFiles[index][size] = this.size;
				//console.log(objectUploadFiles[index]);
			});
			console.log(objectUploadFiles);
			objectUploadFiles['length'] = vars.configPage.oldOrder.files.length;
			console.log(objectUploadFiles);
			_this.pushFilesForDownload(objectUploadFiles);
			if (photoNVideo.pushedCount !== 0) {
				_this.startDownload();
			}
		};

		// Загрузить файлы пользователя при перетаскивании?
		document.querySelector('.uploader').addEventListener("drop", function (e) {
			e.stopPropagation();
			e.preventDefault();
			_this.pushFilesForDownload(e.dataTransfer.files);
			if (photoNVideo.pushedCount !== 0) {
				_this.startDownload();
			}
		});

		// Прослушивать события
		document.body.addEventListener('file:loadend', this.onFileLoadEnd.bind(this));  // Загрузка файла на сервер завершена
		document.body.addEventListener('file:deleted', this.onFileDelete.bind(this));  // Удаление файла
		document.body.addEventListener('chunk:loaded', this.onChunkLoad.bind(this));  // Кусок загружен

		photoNVideo.els.list.onclick = this.onListClick.bind(this);  // Клик по изображению TODO: сюда же повесить обрезку

	},

	// Собрать данные о загружаемых файлах в photoNVideo.items, создать пока пустые превьшки
	// после нажатия на загрузку файлов
	pushFilesForDownload: function pushFilesForDownload(files) {
		console.log(files);
		console.log(files[0]);
		var photoNVideo = this.photoNVideo;
		var pushedCount = 0;

		for (var j = 0; j < files.length; j++)
		{

			if (photoNVideo.maxLength === photoNVideo.items.length) /* Ограничить число загрузок */
				continue;

			pushedCount++;

			var f = files[j];
			if (f.size < 108664326) {

				// Подготовить аттрибуты пользовательского файла для загрузки
				var attrs = {
					file    : f,
					ind     : photoNVideo.startInd,
					name    : f.name,
					type    : f.type,
					src     : f.src,
					id      : f.file_id
				};

				// 2021-05-21 Для загрузки файлов из библиотеки
				// Добавить аттрибуты восстанавливаемого файла
				if (files.uploadType == 'lib') {
					attrs.idRestoredFile = j;
					attrs.uploadType = files.uploadType; // 2021-05-21 Модифкатор вида загрузки: lib, url
					attrs.filesize = files.sizes[j];
				}

				console.log(attrs);
				console.log(photoNVideo.els.list);

				var newFile = new UploadingFile(attrs);  // Вызывает метод View.render() помимо прочего
				console.log('newFile: ', newFile);
				photoNVideo.items.push(newFile);
				newFile.view.append(photoNVideo.els.list);  // Вставить болванку превьшки в HTML-список превьшек


				if (/*1 == 2 &&*/ files.uploadType != 'lib' && typeof URL.createObjectURL === 'function') {
					// Эмулировать загрузку файла для локальных файлов, загруженных в браузер, а не на сервер (кроме IE)
					newFile.view.setPreview(  // Использует метод класса View
						false, // error
						URL.createObjectURL(f),
						null, // width
						null // height
					);
				}

				newFile.coll = photoNVideo.items;
			}
			photoNVideo.startInd++;
		}

		var totalSize = 0;
		photoNVideo.items.forEach(function (file) {
			totalSize += file.size;

		});
		photoNVideo.progress.totalSize = totalSize;
		photoNVideo.pushedCount = pushedCount;

		this.checkLength();  // Проверить количество пригодных ресурсов (создать оповещения, поменять CSS-стили) после подготовки изображения к загрузке // 2019-12-25 Зачем именно здесь? Рано же ещё.
	},


	// Начать загрузку файлов пользователя
	startDownload       : function startDownload() {
		showStep('stepProcessing');
		jQuery('#stepListMedia').addClass('processing');
		var photoNVideo = this.photoNVideo;
		console.log('startDownload, photoNVideo: ', photoNVideo);
		if (photoNVideo.isLoading === true)
			return;

		if (photoNVideo.items[0] !== undefined) {
			console.log('photoNVideo.items[0]: ', photoNVideo.items[0]);
			photoNVideo.isLoading = true;

			// Отправить на загрузку первый файл из коллектции (даже если он уже закачен, проверка в loadChunk())
			// TODO: нельзя ли оптимизировать и сразу отправлять только незакаченные файлы?
			photoNVideo.items[0].startUpload();
			photoNVideo.progress.startTimer();
			//showStep('load');
		}
	},

	// Loader.onFileLoadEnd()
	// Выполнить после окончания загрузки одного из файлов пользователя на сервер:
	// - Начать загрузку следующего файла;
	// - Или оформить окончание загрузки всех файлов.
	// Вызывается в ответ на событие file:loadend (метод UploadingFile.onUploadEnd())
	onFileLoadEnd       : function onFileLoadEnd(ev) {

		// !!! DEL
		console.log(ev);

		var _JSON$parse = JSON.parse(ev.detail);
		var ind = _JSON$parse.ind;

		var photoNVideo = this.photoNVideo;

		var file = this.findByInd(ind);
		var indInArr = photoNVideo.items.indexOf(file);

		var isLast = indInArr === photoNVideo.items.length - 1;

		if (isLast === false) {
			console.log('onFileLoadEnd isLast, indInArr, photoNVideo.items:', indInArr, photoNVideo.items);
			photoNVideo.items[indInArr + 1].startUpload();  // Загрузить следующий файл
		} else {
			this.stopLoading();  // Завершить загрузку файлов
		}
		this.checkLength();

		//console.log(photoNVideo);
		console.log(photoNVideo.progress);
		photoNVideo.progress.reset();  // weezy
		console.log(photoNVideo.progress);
		//showStep(false);
	},
	onFileDelete        : function () {
		this.checkLength();
		//showStep(true);  // weezy
	},

	// Поменять свойства HTML-элементов, зависимых от кол-ва загруженных медиа-файлов
	checkLength         : function () {

		// Вывести кол-во загруженных медиа-файлов
		// Удалить не медиа-файлы
		var photoNVideo = this.photoNVideo;
		var length = 0;
		for (var i = 0; i < photoNVideo.items.length; i++) {
			var curItem = photoNVideo.items[i];
			console.log('curItem: ', curItem);
			if (
				curItem.type.indexOf('image') > -1
				|| curItem.type.indexOf('video') > -1
				&& curItem.src !== null
				&& curItem.id !== null
			) {
				length++;
			} else {
				if (curItem.file == null) {
					//this.findByInd(curItem.ind).delete();  // TODO: 2021-06-19 удаляется меда-файл, вставленный в браузер минуя сервер
				}
			}
		}
		var needPhotos = vars.configPage.minNumberFiles - length;
		if (needPhotos > 0) {
			console.log(photoNVideo.els.counterMedia);
			photoNVideo.els.counterMedia.innerHTML = needPhotos;
		}

		addNumbers();

		/* 2020-04-13 Видимость элементов в зависимости от кол-во фото теперь управлется функцией showMinMax() */
		// ФУНКЦИЯ: Показать/скрыть HTML-элементы, зависимые от кол-ва загруженных медиа-файлов
		//function showMinMax() {
		var count = vars.boxMediaFiles.querySelectorAll('li').length;
		jQuery('#content .show-min-max').each( function( index, element ) {
			var $element = jQuery(element);
			var min = parseInt($element.attr('data-show-min'));
			var max = parseInt($element.attr('data-show-max'));
			//console.log(count, $element, min, max);
			if (count >= min && (count <= max || isNaN(max))) {
				$element.removeClass('d-none');
			} else {
				$element.addClass('d-none');
			}
		} );
		//}
		//showMinMax();

		if (count == 0)
			showStep('stepStart');

	},

	onChunkLoad         : function onChunkLoad(ev) {
		this.photoNVideo.progress.loaded += ev.detail; // ev.detail is chunkSize
	},
	onListClick         : function onListClick(ev) {
		var trg = ev.target;
		if (!trg.classList.contains('del_media')) return;

		var ind = parseInt(trg.getAttribute('data-ind'));
		if (isNaN(ind)) return;

		var file = this.findByInd(ind);
		if (file === null) return;

		file.delete();
	},

	// Найти медиа-файл по его индексу в коллекции
	findByInd           : function findByInd(ind) {
		var file = null;
		var items = this.photoNVideo.items;
		for (var j = items.length - 1; j >= 0; j--) {
			if (items[j].ind === ind) {
				file = items[j];
				break;
			}
		}
		return file;
	},

	// Loader.stopLoading()
	// Закончить загрузку порции файлов пользователя TODO: Возможно, лучше сюда переместить обрезку фото
	// Вызвается только из Loader.onFileLoadEnd()
	stopLoading         : function stopLoading() {
		console.log('stopLoading');
		var photoNVideo = this.photoNVideo;
		//showStep('stepListMedia');
		photoNVideo.isLoading = false;
		photoNVideo.progress.stopTimer();
		photoNVideo.progress.el.style.display = 'none';
		this.resetInput(photoNVideo.els.inp);
		/*
		// weezy.app: Запустить обрезку всех загруженных файлов пользователя, не содержащих данные об обрезке
		if (vars.configPage.isNeedCropping == 0) {
			return;
		}*/
		//console.log(jQuery('#boxMediaFiles img'));

		// Запустить скрытую обрезку всех необрезанных изображений
		//var img = vars.boxMediaFiles.querySelector('img:not([data-crop-canvas])');
		//cropping.createCrop(img);  // TODO: подвисает скроллинг
		//showStep('stepListMedia');
		//jQuery('#stepListMedia').removeClass('processing');
		/*jQuery('#stepListMedia').removeClass('processing');*/
	},

	resetInput: function (inp) {
		var form = document.createElement('form');
		var par = inp.parentElement;
		par.insertBefore(form, inp);
		form.appendChild(inp);
		form.reset();
		par.insertBefore(inp, form);
		par.removeChild(form);
		form = null;
	},


	getData     : function () {
		var mediaList = this.photoNVideo.els.list;
		var media = this.photoNVideo;

		if (media.isLoading !== false)
			return null;

		var mediaData = media.items.map(function (file) {
			return {
				id  : file.id,
				ind : findInd(file.view.els.el),
				type: file.type
			};
		});
		return {
			media: mediaData
		};

		function findInd(el) {
			var list = mediaList.children;
			var length = list.length;
			for (var i = 0; i < length; i++) {
				if (list[i] === el) return i;
			}
		}
	},
	makeSortable: function makeSortable() {
		var el = this.photoNVideo.els.list;
		new Sortable(el, {
			draggable: 'li'
		});
	}
};

// /NAMESPACE "LOADER"


// NAMESPACE "Sender"

var Sender = {

	getEmail: function () {

		function isEmail(email) {
			if (email.length > 0
				&& (email.match(/.+?\@.+\..{2,}/g) || []).length !== 1) {
				return false;
			} else {
				return true;
			}
		}

		var inp = document.querySelector('#emailOrder');
		var val = inp.value;

		var res = {
			valid: true,
			val: val
		};

		if (val === '' || !isEmail(val)) {
			res.valid = false;
			inp.classList.add('hasError');
			var elemError = document.createElement('div');
			elemError.classList.add('errortext');
			elemError.innerHTML = 'Add your e-mail';
			var parentDiv = vars.btnSubmit.parentNode;  // Weezy
			//var parentDiv = inp.parentNode;
			console.log(parentDiv);
			parentDiv.insertBefore(elemError, vars.btnSubmit);
			inp.onfocus = function () {
				inp.classList.remove('hasError');
				var parentDiv = vars.btnSubmit.parentNode;  // Weezy
				//var parentDiv = inp.parentNode;
				parentDiv.removeChild(elemError);
				inp.onfocus = null;
			};
		}

		return res;
	},

	collectData: function (isNeedEmail) {  // Собрать данные для отправки заказа

		// Добавить данные из data-аттрибутов медиа-файлов в заказ
		var dataMediaFiles = [];
		jQuery('#boxMediaFiles li img').each(function () {  // TODO: можно заменить jQuery на нативный JS
			var fileData = jQuery(this).data();

			// Не передавать на обработку некоторые data-аргументы медиа-файла
			delete fileData.src; // 2021-06-21 Передать BLOB-объект
			//delete fileData.height;  // 2021-02-05 Размеры медиа-файла нужны для пере-обрезки
			//delete fileData.width;
			delete fileData.cropCanvas;
			console.log(fileData);
			dataMediaFiles.push(fileData);
		});

		/*
		 jQuery('#boxMediaFiles li img').each(function () {

		 // Добавить ориентацию фрейма в данные
		 var $image = jQuery(this);
		 var dataCropImage = $image.data('crop');
		 dataCropImage.frame_orientation = $image.data('frame_orientation');
		 dataCropImage.file = $image.data('file');

		 // Добавить информацию о видео
		 if ($image.data('type') == 'video') {
		 dataCropImage.second = $image.data('second');
		 }
		 formDataCrop.push(dataCropImage);
		 });
		 */
		/* 2021-07-08
		 var resolutionData = {
		 "slideshow" : {
		 'width' : vars.configPage.widthSlideshow,
		 'height': vars.configPage.heightSlideshow
		 },
		 "frame_orientations": {
		 "horizontal": {
		 "width": vars.configPage.widthHorizontalFrame,
		 "height": vars.configPage.heightHorizontalFrame
		 },
		 "vertical": {
		 "width": vars.configPage.widthVerticalFrame,
		 "height": vars.configPage.heightVerticalFrame
		 }
		 }
		 };
		 */

		var dataOrder = {
			sid: ses_id,
			uident: ses_ident,
			id_order: vars.idOrder,
			id_initial_design: vars.configPage.idInitialDesign,
			id_design: vars.idNewDesign,
			code_language: vars.configPage.codeLanguage,
			//loader     : Loader,
			formData: jQuery('form').serializeArray(),
			//formData   : jQuery('form').serializeObject(),
			//canvas_data: formDataCrop,
			media_files: dataMediaFiles,
			//resolution : resolutionData,
			isFull: true  // Флаг полных (валидных) данных
		};

		if (isNeedEmail !== false) {
			var email = this.getEmail();
			dataOrder.email = email.val;
			if (email.valid === false) {
				dataOrder.isFull = false;
			}
		}

		var files = Loader.getData();
		if (files === null) {
			dataOrder.isFull = false;
		} else {
			dataOrder.media = JSON.stringify(files.media);
			dataOrder.items = JSON.stringify(Loader.photoNVideo.items, function (key, value) {
				if (key == 'coll' || key == 'view') {  // TODO: Разобраться, что за coll и view?
					return undefined;
				}
				return value;
			});
		}

		return dataOrder;
	},

	// ОТПРАВИТЬ ЗАКАЗ (ФУНКЦИЯ sendClipData())
	// 2021-08-12 function sendClipData() {  // TODO: 2010-06-18 Нужны ли тут аргументы (Loader)? Вроде нет
	sendClipData: function () {

		// Очистить историю ошибок
		var parentDiv = vars.btnSubmit.parentNode;
		Array.from(parentDiv.querySelectorAll('.errortext')).forEach(function (item, i, arr) {
			parentDiv.removeChild(item);
		});

		var ajaxBody = Sender.collectData();
		console.log(ajaxBody);
		console.log('sendClipData, ajaxBody:', ajaxBody);
		if (ajaxBody.isFull !== true)
			return;
		delete ajaxBody.isFull;

		// Отправить собранные данные скрипту сохранения заказа
		//yaCounter25315490.reachGoal('zakaz-video');

		// Анимировать кнопку
		setTimeout(function () {
			jQuery('.btn-arrow-left, .btn-arrow-right').hide();
			vars.btnSubmit.setAttribute("disabled", "disabled");
			vars.btnSubmit.value = 'processing your order ...';
			vars.btnSubmit.classList.add('loading');
			vars.btnSubmit.classList.remove('btn-outline-light');
			vars.btnSubmit.classList.remove('btn-light');
			vars.btnSubmit.classList.remove('btn');
			vars.btnSubmit.textContent = 'processing your order ...';
			showStep('formSubmit');  // Wezzy
		}, 800);
		//return false;

		var URL_POST = vars.FF_root + '/app/ajax/ajax-create-order.php';
		fetch(URL_POST, {
			method: 'POST',
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			body: jQuery.param(ajaxBody)
		}).then(function (resp) {
			console.log(resp);
			return resp.json();
		}).then(function (resp) {

			// Выполнить после успешного создания заказа
			console.log(resp);
			//dataLayer.push({'event': 'zakaz'}); Deleted Andrew Ankudinov, вызывало ошибку посла переезда на slideshow-online.com, предположительно это обработка данных для Google Analytics посредством Google Tag Manager
			// yaCounter25315490.reachGoal('zakaz-video'); Deleted Andrew Ankudinov

			if (resp.url_order !== undefined) {  // resp.id_order
				//jQuery('#configPage').attr('data-id-order', resp.id_order);
				//vars.idOrder = resp.id_order; // 2022-01-07 Зачем? Все равно уходим со страницы
				window.location = url_order;
				//window.location = vars.FF_root + '/product/' + resp.id_order;
			}

		});
	},
	// /ОТПРАВИТЬ ЗАКАЗ (ФУНКЦИЯ sendClipData())
	/*
		sendPrepayment: function () {

			var ajaxBody = Sender.collectData(false);
			if (ajaxBody.isFull !== true)
				return;
			delete ajaxBody.isFull;

			fetch(
				vars.FF_root + '/app/ajax/ajax-prepayment.php',
				{
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: jQuery.param(ajaxBody)
				}
			).
			then(function (resp) {
				console.log(resp);
				return resp.json();
			}).then(function (resp) {

				// Выполнить после успешного создания заказа
				console.log(resp);
				//dataLayer.push({'event': 'zakaz'}); Deleted Andrew Ankudinov, вызывало ошибку посла переезда на slideshow-online.com, предположительно это обработка данных для Google Analytics посредством Google Tag Manager
				// yaCounter25315490.reachGoal('zakaz-video'); Deleted Andrew Ankudinov
				if (resp.id_order !== undefined) {
					//jQuery('#configPage').attr('data-id-order', resp.id_order);
					vars.idOrder = resp.id_order;
					window.location = vars.FF_root + '/product/' + resp.id_order;
				}

			});
		}*/
}

// Функция оплаты
this.payCloudPayments = function (amount) {
	console.log(amount);
	var widget = new cp.CloudPayments({language: vars.configPage.cloudpaymentsCodeLanguage});
	widget.pay('auth', // или 'charge'
		{ //options
			publicId: vars.configPage.cloudpaymentsPublicId, //id из личного кабинета
			description: 'Предоплата заказа', //назначение
			amount: parseInt(amount), //сумма
			currency: 'RUB', //валюта
			//accountId: 'user@example.com', //идентификатор плательщика (необязательно)
			//invoiceId: '', //номер заказа  (необязательно)
			skin: "mini" //дизайн виджета (необязательно)
		},
		{
			onSuccess: function (options) { // success
				//действие при успешной оплате
				showStep('stepEmail');
			},
			onFail: function (reason, options) { // fail
				//действие при неуспешной оплате
			},
			onComplete: function (paymentResult, options) { //Вызывается как только виджет получает от api.cloudpayments ответ с результатом транзакции.
				//например вызов вашей аналитики Facebook Pixel
			}
		}
	)
};

// ФУНКЦИЯ: Инициализация

function init() {

	// Запустить инициализацию загрузчика
	Loader.init();

	// ФУНКЦИЯ: Создать слушателя событий
	function createEventListener() {

		// Нажать программно на скрытое поле загрузки старого заказа
		if (vars.configPage.oldOrder != '') {
			$('#btnRestore').click();
		}

		// Перейти к следующему шагу
		vars.btnCompleteUpload.onclick = function () {
			// wow.cards: Пропустить шаг смены дизайна, если тип дизайна - не слайдшоу
			if (vars.configPage.typeDesign == 'slideshow') {
				if (vars.configPage.freeDesign) {
					showStep('stepChooseFreeDesign');
				} else {

					// Сформировать цену предоплаты заказа
					var ajaxBody = {
						'quantity_frames': Loader.photoNVideo.items.length
					};
					fetch(
						vars.FF_root + '/app/ajax/ajax-build-price-prepayment-order.php',
						{
							method: 'POST',
							headers: {
								'Content-Type': 'application/x-www-form-urlencoded'
							},
							body: jQuery.param(ajaxBody)
						}
					)
						.then(function (response) {
							return response.json();
						})
						.then(function (data) {
							console.log(data);
							$('#prepayment #amount').text(data);
							showStep('stepChooseDesign');
						});
				}
			}
			else {
				showStep('stepEmail');
			}
		};

		jQuery('.btn-choose-design')
			.on("click", function (e) {
				showStep('stepEmail');
			});
		/* 2021-11-08 теперь несколько кнопок смены дизайна
		vars.btnChooseDesign.onclick = function () {
			showStep('stepEmail');
		};
		*/
		vars.btnSubmit.onclick = function () {
			Sender.sendClipData();
			//return false;
		};

		// Кнопки смены дизайна
		/*
		q('showStepChooseFreeDesign').onclick = function () {
			//alert('stepChooseFreeDesign');
			vars.idNewDesign = vars.configPage.idFreeDesign;
			showStep('stepChooseFreeDesign');
		};
		*/
		q('showStepChooseFreeDesign2').onclick = function () {
			//alert('stepChooseFreeDesign');
			vars.idNewDesign = vars.configPage.idFreeDesign;
			showStep('stepChooseFreeDesign2');
		};
		q('showStepChooseDesign').onclick = function () {
			vars.idNewDesign = false;
			showStep('stepChooseDesign');
		};
		q('showStepChooseDesign2').onclick = function () {
			vars.idNewDesign = false;
			showStep('stepChooseDesign');
		};

		// Оплатить предоплату
		q('btnPayPrepayment').onclick = function () {
			payCloudPayments($('#prepayment #amount').text());
		};


		// CHANGE TEXTAREA
		//console.log(jQuery('#textOrder')[0]);
		//autoHeight_(jQuery('#textOrder')[0]);
		//jQuery('#textOrder').
		jQuery('#boxTextareaCropping textarea')
			.on("keypress", function (e) {
				if ((e.keyCode == 10 || e.keyCode == 13)) {
					e.preventDefault();
					scrollToAnchor('btnSubmit');
					vars.$emailOrder.focus();
				}
			})
			.on('change input paste keyup propertychange', function () {
				if (this.getAttribute('maxLength') == this.value.length)
					jQuery('#messMaxLength').show();
				else
					jQuery('#messMaxLength').hide();
			});

		jQuery('#boxMediaFiles')
			.on('change input paste keyup propertychange', 'textarea', function () {
				autoHeight_(this);
			});

		// /CHANGE TEXTAREA

		/* 2021-11-08 Убрали карусель */ /* 2021-11-22 Вернули карусель */
		// Действия при листании карусели
		jQuery('#carouselChooseSlideshow').on('slide.bs.carousel', function (ev) {
			vars.idNewDesign = jQuery(ev.relatedTarget).attr('data-id-design');  // TODO: бесплатный дизайн. А если полистали и передумали?
			console.log(vars.idNewDesign);
		});

	}

	createEventListener();

	//circularBarSet();
	//circularBarRotate(vars.$circularBar.find('.progress-right .progress-bar'), '45');
}


/* DOCUMENT READY */

jQuery(document).ready(function ($) {

	// setPageStyles(); 2021-06-11
	init();

	// ===================================

	/*
	function def() {
		var d = jQuery.Deferred();
		setTimeout(function(){
			d.resolve();
		},1000);
		return d;
	}
	def().done(function(){
		console.log('test');
	});
	*/

});

// /DOCUMENT READY

/*
{
    "name"
:
    "card1-1", "title"
:
    "Card 1-1", "preview"
:
    "Card1-1_preview.png", "template"
:
    "Card1-1_template.png", "width"
:
    640, "height"
:
    640, "txt"
:
    {
        "fontName"
    :
        "AdleryPro", "fontFile"
    :
        "AdleryPro.otf", "default"
    :
        "\u0421 \u0434\u043d\u0435\u043c \u0440\u043e\u0436\u0434\u0435\u043d\u0438\u044f", "color"
    :
        "#514e51", "fontSize"
    :
        63, "lineHeight"
    :
        100, "X"
    :
        61, "Y"
    :
        441, "width"
    :
        520, "height"
    :
        98, "marginTop"
    :
        -8, "paddingTop"
    :
        8, "lines"
    :
        1, "letterSpace"
    :
        1.2, "upperCase"
    :
        false, "rotate"
    :
        0, "topConstant"
    :
        18, "leftConstant"
    :
        -1, "drawletterSpace"
    :
        1
    }
}
	*/