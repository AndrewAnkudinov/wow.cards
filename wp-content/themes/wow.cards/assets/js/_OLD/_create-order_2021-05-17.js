"use strict";


// TODO: после заливки нового изображения сбиваются blob-изображения ранее обрезанных

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


// СОЗДАТЬ ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ
var vars = {
	FF_root             : '', // Корневая папка сайта
	idOrder             : '',
	configPage          : jQuery('#configPage').data(),
	/*
	$circularBar        : jQuery('#circularBar'),
	$circularBarPercent = jQuery('#circularBarPercent'),
	$progress	  = jQuery('.progress'),
	$progressBar  = jQuery('.progress-bar'),
	*/
	$emailOrder         : jQuery('#emailOrder'),
	btnSubmit           : document.querySelector('#btnSubmit')
};
console.log(vars);


// СОЗДАТЬ ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
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
function scrollToMedia(id) {

	var media = jQuery("#" + id);

	console.log('$(#fotoNVideoContainer>div).scrollTop():', $('#fotoNVideoContainer>div').scrollTop());
	var offsetShadow = 16;
	var offset = media.offset().top - $('#fotoNVideoContainer>div').offset().top - offsetShadow;  // Вычислить разность позиций контейнера для медиа и конкретной превьюшки
	console.log('media.offset().top, $(#fotoNVideoContainer>div).offset().top, offset: ', media.offset().top, $('#fotoNVideoContainer>div').offset().top, offset);
	//$('#fotoNVideoContainer').scrollTop = offset;
	$('#fotoNVideoContainer').animate({scrollTop: offset}, 'slow');

	/*
	var myElement = document.getElementById(id);
	console.log(myElement);
	var topPos = myElement.offsetTop;
	console.log(topPos);
	document.getElementById('fotoNVideoContainer>div').scrollTop = topPos;
	*/
	/*
	console.log(id);
	var posArray = $("#" + id).positionedOffset();
	$('#fotoNVideoContainer>div').scrollTop = posArray[1];
	*/
}

function isEmail(email) {
	if (email.length > 0
		&& (email.match(/.+?\@.+\..{2,}/g) || []).length !== 1) {
		return false;
	} else {
		//console.log('valid');
		return true;
	}
}

function is2words(string) {
	var tagCheckRE = new RegExp("(\\S+)(\\s+)(\\S+)");
	console.log(tagCheckRE + '.test(' + string + '): ' + tagCheckRE.test(string));
	return tagCheckRE.test(string);
}

// Auto Height Textarea
function autoHeight_(element) {
	console.log(element);
	console.log('element.scrollHeight: ', element.scrollHeight);
	return jQuery(element).css({
		'height'    : 'auto',
		'overflow-y': 'hidden'
		//}).height($(element).prop('scrollHeight'));
	}).height(element.scrollHeight);
}

// /ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ



/*
// Показать/скрыть HTML-элементы (в зависимости от того, есть или нет загруженные изображения)
function setDisplay(visible) {
	console.log(visible);

	// Кол-во изображений изменено
	if (visible === 'imageUploaded')
	{

		// Показывать поля для вода текстовых данных, если есть заруженный файл
		// Скрыть форму форму загрузки файлов, если загружено максимальное число
		var tmpImgUpload = jQuery("#fotoNVideoContainer li");
		console.log('tmpImgUpload: ' + tmpImgUpload);
		console.log('tmpImgUpload.length: ' + tmpImgUpload.length);
		console.log('maxImages: ' + vars.maxImages);

		// Если загружен хотя бы 1 медиа-файл
		if (tmpImgUpload.length > 0) {
			jQuery('#stepListMedia').show();
			jQuery('#stepStart').hide();
		} else {
			jQuery('#stepListMedia').hide();
			jQuery('#stepStart').show();
		}

		// Если загружено максимальное кол-во медиа-файлов
		if (tmpImgUpload.length >= vars.maxImages) {
			jQuery('#stepUploading').hide();
		} else {
			jQuery('#stepUploading').show();
		}
	}

	else if (visible === 'load') {		// В момент загрузки файла
		jQuery('#stepUploadingHeader, .btn-upload-media, #stepListMedia').hide();
	}
	else if (visible === 'crop') {		// После загрузки файла Открыть редактор/Скрыть главное тело документа
		//jQuery('#stepListMedia').removeClass('d-none');
		vars.$circularBar.hide();
		//jQuery('.btn-upload-media').toggleClass('btn-light');
		jQuery('#stepUploadingHeader, .btn-upload-media').show();
		jQuery('#stepUploading, #stepListMedia').hide();
	}

	else if (visible === 'cropEnd') {	 // Открыть главное тело документа/Скрыть редактор после обрезки изображения
		jQuery('#stepUploading, #stepListMedia').show();
		console.log('vars.$activeCropImage:');
		console.log(vars.$activeCropImage);
		scrollToId(vars.$activeCropImage.attr('id'));
		//$textOrder.focus();
		//scrollToAnchor('thumbnailAnchor'); 2020-05-12
	}
	/*
	 else if (visible === 'orderCreate') {	 // Открыть главное тело документа/Скрыть редактор после обрезки изображения
	 jQuery('#stepUploading, #stepListMedia').hide();
	 $stepCrop.hide();
	 $textOrder.focus();
	 scrollToAnchor('thumbnailAnchor');
	 }
	 *//*
	else if (visible === 'formSubmit') {	 // После отправки заказа скрыть все, кроме отправки заказа
		jQuery('#stepUploading, #stepListMedia').hide();
	}
	else {								// Конечное состояние документа после обрезки изображения
		jQuery('#stepUploading').hide();
		jQuery('#stepListMedia').show();
	}
}
*/

// ФУНКЦИЯ: Показать/скрыть HTML-элементы, зависимые от кол-ва загруженных медиа-файлов
function showMinMax(count) {
	jQuery('#content .show-min-max').each( function( index, element ) {
		var $element = jQuery(element);
		var min = parseInt($element.attr('data-show-min'));
		var max = parseInt($element.attr('data-show-max'));
		console.log(count, $element, min, max);
		if (count >= min && (count <= max || isNaN(max))) {
			console.log('1');
			$element.removeClass('d-none');
		} else {
			console.log('2');
			$element.addClass('d-none');
		}
	} );
}

// Показать/скрыть шаги создания заказа
function setDisplay(visible) {
	console.log(visible);


	var stepEvents = {
		imageUploaded: ''
	};


	jQuery('#content .step').each( function( index, element ) {
		var $element = jQuery(element);
		if (element.id == visible) {
			$element.removeClass('d-none');
		} else {
			$element.addClass('d-none');
		}
	} );

	if (1 == 2) {


	// Кол-во изображений изменено
	if (visible === 'imageUploaded')
	{

		// Показывать поля для вода текстовых данных, если есть заруженный файл
		// Скрыть форму форму загрузки файлов, если загружено максимальное число
		var tmpImgUpload = jQuery("#fotoNVideoContainer li");
		console.log(tmpImgUpload.length);
		showMinMax(tmpImgUpload.length);
		// Если загружен хотя бы 1 медиа-файл
		if (tmpImgUpload.length > 0) {
			jQuery('#stepListMedia').show();
			jQuery('#stepStart').hide();
		} else {
			jQuery('#stepListMedia').hide();
			jQuery('#stepStart').show();
		}
		if (tmpImgUpload.length >= vars.configPage.minNumberFiles) {
			$(vars.btnSubmit).show();
		}
		else {
			$(vars.btnSubmit).hide();
		}

		// Если загружено максимальное кол-во медиа-файлов
		if (tmpImgUpload.length >= vars.maxNumberFiles) {
			jQuery('#stepUploading').hide();
		} else {
			jQuery('#stepUploading').show();
		}
	}

	if (visible === true) {		// Начальное состояние документа до загрузки файла
		jQuery('#stepUploading').show();
		jQuery('#stepListMedia').hide();
	}
	/*
	else if (visible === 'load') {		// В момент загрузки файла
		jQuery('.btn-upload-media, #stepListMedia').hide();
	}
	*/
	else if (visible === 'crop') {		// После загрузки файла /*Открыть редактор/Скрыть главное тело документа*/
		//jQuery('#stepListMedia').removeClass('d-none');
		//vars.$circularBar.hide();
		//jQuery('.btn-upload-media').toggleClass('btn-light');
		jQuery('.btn-upload-media').show();
		jQuery('#stepUploading, #stepListMedia, h1').hide();
	}
	else if (visible === 'cropHidden') {  // Скрытно открыть и закрыть редактор (сохранить данные обрезки)/Скрыть главное тело документа после загрузки файла
		//vars.$circularBar.hide();

		//jQuery('.btn-upload-media').toggleClass('btn-light');
		jQuery('.btn-upload-media').show();
		jQuery('#stepUploading, #stepListMedia').show();
		//$textOrder.focus();
		//scrollToAnchor('thumbnailAnchor'); 2020-05-12
	}
	else if (visible === 'cropEnd') {	 // Открыть главное тело документа/Скрыть редактор после обрезки изображения
		jQuery('#stepUploading, #stepListMedia, h1').show();
		jQuery('#stepCrop').hide();
		console.log('cropping.$croppedMedia:', cropping.$croppedMedia);
		//scrollToId(cropping.$croppedMedia.attr('id')); 2020-12-25 Вместо него теперь scrollToMedia
		//$textOrder.focus();
		//scrollToAnchor('thumbnailAnchor'); 2020-05-12
	}
	/*
	 else if (visible === 'orderCreate') {	 // Открыть главное тело документа/Скрыть редактор после обрезки изображения
	 jQuery('#stepUploading, #stepListMedia').hide();
	 $boxCrop.hide();
	 $textOrder.focus();
	 scrollToAnchor('thumbnailAnchor');
	 }
	 */
	else if (visible === 'formSubmit') {	 // После отправки заказа скрыть все, кроме отправки заказа
		jQuery('#stepUploading, #substepListMedia').hide();
	}
	else {								// Конечное состояние документа после обрезки изображения
		jQuery('#stepUploading').hide();
		jQuery('#stepListMedia').show();
	}

	}
}

// Добавить/Исправить номера картинок
function addNumbers() {
	var elementsList = document.querySelectorAll('#fotoNVideoContainer li .box_thumb_media');
	Array.from(elementsList).forEach(function (item, i, arr) {
		item.querySelector(".imageNumber").innerHTML = (i+1);
		//item.style.opacity = 1;
	});
}


/* DOCUMENT READY */

jQuery(document).ready(function ($) {


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


	// КЛАСС View - ПРОСМОТРОВЩИК ЗАГРУЖЕННЫХ ФАЙЛОВ

	var View = function () {
		function View(_ref) {
			console.log(_ref);
			var type = _ref.type;
			var name = _ref.name;
			var ind = _ref.ind;
			var partKey = _ref.partKey;
			var idRestoredFile = _ref.idRestoredFile;  // 2021-05-12 Для загрузки файлов из библиотеки
			classCallCheck(this, View);

			var slashInd = type.indexOf('/');
			this.type = slashInd !== -1 ? type.substring(0, slashInd) : type;
			this.name = name;
			this.ind = ind;
			this.partKey = partKey;
			this.idRestoredFile = idRestoredFile;  // 2021-05-12 Для загрузки файлов из библиотеки

			this.render();
		}

		// Создать HTML-элементы загруженного изображения
		createClass(View, [{

			// Создать болванку превьюшки в процессе закачки файла
			// Функция вызывается при создании нового объекта типа View, как автозагрузчик
			key  : 'render',
			value: function render() {
				console.log(this);

				var li = document.createElement('li');
				li.className = 'col-6 col-md-4 col-xl-3 d-flex align-items-center justify-content-center flex-column loading';
				li.title = this.name;

				var imageCaptionInput = document.createElement('textarea');
				//var imageCaptionInput = document.createElement('input');
				//imageCaptionInput.type = 'text';
				jQuery(imageCaptionInput).attr('maxlength', 40);
				jQuery(imageCaptionInput).attr('rows', 1);
				imageCaptionInput.name = 'image_captions[]';
				imageCaptionInput.dataset.ind = this.ind;
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
					$.each(vars.configPage.oldOrder.files[this.idRestoredFile].dataCrop, function(index, value) {
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
				console.log('li:', li);

				var delBtn = document.createElement('button');
				delBtn.type = 'button';
				delBtn.className = 'del_media';
				delBtn.dataset.ind = this.ind;
				li.appendChild(delBtn);

				li.addEventListener('dragover', addNumbers);  // Не нужно на weezy.app, только 1 фото/видео, 2019-07-26
				this.els = {
					el    : li,
					delBtn: delBtn,
					imageCaptionInput: imageCaptionInput
				};
				console.log('li:', li);
				return this;
			}
		}, {

			// Создать превьюшку медиа-файла пользователя после его загрузки
			key  : 'setPreview',
			value: function setPreview(wError, src, width, height) {
				console.log('setPreview');
				console.log(src);
				var el = this.els.el;
				el.classList.remove('loading');

				if (wError === true) {
					el.classList.add('wError');
					return;
				}

				//el.style.overflow = 'hidden';
				//el.style.backgroundImage = 'none';

				//el.style.backgroundImage = 'url(' + src + ')';
				var tempImg = el.querySelector('img');
				console.log(tempImg);
				tempImg.dataset.type = this.type;
				tempImg.onload = function () {  // Выполнить после загрузки файла

				};

				if (jQuery(tempImg).attr('data-natural-width') == undefined) {  // Предотвратить повторное создание превью

					// Создать служебные аттрибуты залитого изображения
					var attrImg = {
						'data-natural-width': width,
						'data-natural-height': height,
						'data-file': src
					};
					if (this.type == 'image') {
						tempImg.src = src;
					}
					else if (this.type == 'video') {
						var blobStub = cropping.createBlobStub(width, height);
						tempImg.src = blobStub;
						attrImg['data-second'] = 0;

						// Создать видео-превьюшку
						var video = document.createElement('video');
						video.src = src;
						var boxThumbMedia = el.querySelector('.box_thumb_media');
						boxThumbMedia.insertBefore(video, tempImg);

					}
					attrImg['data-src'] = tempImg.src;  // Сохранить изображение до резки
					console.log(attrImg);
					jQuery(tempImg).attr(attrImg);
					el.querySelector('.progress-holder').style.display = 'none';

				}

			}
		}, {
			key  : 'append',
			value: function append(parent) {
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
	// CLASS VIEW - ПРОСМОТР ЗАГРУЖЕННЫХ ФАЙЛОВ


	// CLASS UPLOADING FILE (ЗАГРУЗКА ФАЙЛА)
	// this - объект с данными загружаемого файла

	var UploadingFile = function () {
		function UploadingFile(_ref) {
			var _ref$file = _ref.file;
			var file = _ref$file === undefined ? {} : _ref$file;
			var partKey = _ref.partKey;
			var ind = _ref.ind;
			var type = _ref.type;
			var name = _ref.name;
			var notAFile = _ref.notAFile;
			var src = _ref.src;
			var id = _ref.id;

			// 2021-05-12 Для загрузки файлов из библиотеки
			var uploadType = _ref.uploadType;
			var filesize = _ref.filesize;
			var idRestoredFile = _ref.idRestoredFile;

			classCallCheck(this, UploadingFile);

			this.ind = ind;
			this.partKey = partKey;
			this.file = file;
			this.start = 0;
			this.xhr = null;
			this.count = 0;
			this.id = id !== undefined ? id : null;
			this.wError = false;
			this.notAFile = notAFile;
			this.size = file.size;

			// 2021-05-12 Для загрузки файлов из библиотеки
			this.uploadType = uploadType;
			if (uploadType !== undefined) {
				this.size = filesize;
				this.idRestoredFile = idRestoredFile;
			}

			this.type = type || file.type;
			this.name = name || file.name.replace(/ /g, '_');
			this.view = new View({  // Вызывается функция View.render()
				type   : this.type,
				name   : this.name,
				ind    : this.ind,
				partKey: this.partKey,
				idRestoredFile: this.idRestoredFile // 2021-05-12 Для загрузки файлов из библиотеки
			});

			console.log('UploadingFile, this: ', this);
			if (notAFile === true) {
				this.src = src;
				this.view.setPreview(this.wError, this.src, this.width, this.height);
			}
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
					var CHUNK_SIZE = 524288 * 4;  // Размер загружаемых фрагментов файла
					var newStart = start + CHUNK_SIZE > fSize ? fSize : start + CHUNK_SIZE;
					this.currChunkSize = newStart - this.start;
					this.start = newStart;
					return newStart;
				}
			}, {
				key  : 'startUpload',  // Начать загрузку очередного файла пользователя
				value: function startUpload() {
					console.log('startUpload: ', this);
					if (this.notAFile === true) { // Прервать загрузку файла
						this.onUploadEnd();
						return;
					}

					this.loadChunk();
				}
			}, {
				key  : 'loadChunk',
				value: function loadChunk() {
					console.log('loadChunk: ', this);

					// Закончить загрузку файла, если размер закаченной части файла совпадает с полным размером файла
					if (this.start === this.size) {
						this.delXhr();
						this.onUploadEnd();
						return;
					}

					this.collectFD();// Собрать данные о загружаемом файле для обработки AJAX-запросом
					console.log(this);
					console.log(this.fd.name);
					this.delXhr();

					var URL_CREATE = vars.FF_root + '/app/ajax/ajax-upload.php';
					this.xhr = new XMLHttpRequest();  // Сделать HTTP-запросы к серверу без перезагрузки страницы. Документация: https://learn.javascript.ru/xmlhttprequest
					this.xhr.open("POST", URL_CREATE, true);
					this.xhr.onload = this.onLoad.bind(this);  // Обработать ответ
					this.xhr.onerror = this.onError.bind(this);
					this.xhr.upload.onprogress = this.onProgress.bind(this);
					this.xhr.send(this.fd);  // Отправить данные

				}
			}, {
				key  : 'onLoad',
				value: function onLoad(ev) {
					this.clear();

					if (ev.target.status !== 200) {
						this.onError();
						return;
					}

					var chunkSize = this.currChunkSize;

					var resp = JSON.parse(ev.target.responseText);  // JSON ответ процедуры загрузки файла

					// if ( this.count === 0 ) {
					this.id = resp.file_id ? resp.file_id : this.id;
					// this.count++
					// }

					this.src = resp.src !== undefined ? resp.src : null;
					this.width = resp.width !== undefined ? resp.width : null;
					this.height = resp.height !== undefined ? resp.height : null;
					this.loadChunk();

					dispatch('chunk:loaded', JSON.stringify({
						chunkSize: chunkSize,
						partKey  : this.partKey
					}));
				}
			}, {
				//Прогресс выгрузки файла
				key  : 'onProgress',
				value: function onProgress(ev) {
					var done = this.start;
					var total = this.size;
					var present = Math.floor(done / total * 100);
					//console.log(present);
					if (this.type != 'audio/mp3' && this.type != 'audio/wav') {
						this.view.els.el.querySelector('.bar').style.width = present + '%';
					}
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
				key  : 'collectFD',
				value: function collectFD() {
					this.fd = new FormData();  // Создать новую HTML-форму
					this.fd.append('clip_id', vars.configPage.idDesign);
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
			}, {
				key  : 'delXhr',
				value: function delXhr() {
					if (this.xhr !== null) {
						this.xhr.onload = null;
						this.xhr.onerror = null;
					}
					this.xhr = null;
				}
			},

			{
				// Закончить загрузку файла (успешную или нет)
				key  : 'onUploadEnd',
				value: function onUploadEnd() {

					setDisplay('imageUploaded');

					console.log('onUploadEnd: ', this);
					var wError = this.wError;
					this.clear();
					this.file = null;

					// !!! DEL
					console.log(this);
					this.view.setPreview(
						wError,
						this.src,
						this.width,
						this.height
					);

					dispatch('file:loadend', JSON.stringify({
						wError : wError,
						partKey: this.partKey,
						ind    : this.ind
					}));
				}
			},

			{
				key  : 'delete',
				value: function _delete() {
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
					cropping.clear();
				}
			}, {
				key  : 'destroy',
				value: function destroy() {
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

	var SIZES = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
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

				function toStr(num) {
					return num < 10 ? '0' + num : '' + num;
				}
			}
		}, {
			key  : 'reset',  // Сбросить значения прогресс-бара
			value: function reset() {
				setTimeout(function () {
					/*
					 console.log(this);
					 console.log(this.attrs);
					 console.log(this.attrs.loaded);
					 */
					this.stopTimer();
					this.el.style.display = 'none';
					this.attrs.loaded = 0;
					this.attrs.totalSize = 0;
					/*
					 console.log(this);
					 console.log(this.attrs);
					 console.log(this.attrs.loaded);
					 */
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

	var Loader = {
		init: function init() {
			console.log('Loader init');
			this.parts.fotoNvideo.maxLength = vars.configPage.maxNumberFiles;

			var media = arguments.length <= 0 || arguments[0] === undefined ? [] : arguments[0];

			this.addLis();
			this.makeSortable();
			this.setRestored(media);
		},

		get keys() {
			return Object.keys(this.parts);
		},

		parts: {
			fotoNvideo: {
				loading  : false,
				maxLength: vars.configPage.maxNumberFiles,
				startInd : 1,
				els      : {
					btn     : document.getElementsByClassName('btn-upload-media'),
					restoreBtn  : q('restoreBtn'),  // 2021-05-12 Для загрузки файлов из библиотеки
					inp     : q('inputUploadMedia'),
					list    : document.querySelector('#fotoNVideoContainer ul'),
					counter : q('counterMedia'),
					messCounter : q('messCounterMedia')
				},
				items    : [],
				progress : new ProgressBar(q('fotoUploadProgress'))
			}
		},

		// Добавить прослушивание событий для кнопкок загрузки файлов
		addLis: function addLis() {
			console.log('Loader addLis this:', this);
			var _this = this;

			// Обработать клики по кнопкам загрузки, отправить список файлов в функции pushFilesForDownload() и startDownload()
			this.keys.forEach(function (key) {
				var part = _this.parts[key];
				for (var i=0; i < part.els.btn.length; i++) {

					// Нажать программно на скрытое поле выбора файлов input type=file после клика на видимую красивую кнопку
					part.els.btn[i].onclick = function(){
						part.els.inp.click();
						jQuery(part.els.restoreBtn).hide(); // TODO
					}

				};
				/*
				part.els.btn.onclick = function () {
					console.log('.btn-upload-media click');
					part.els.inp.click();
				};
				*/

				// Запустить загрузку файлов после изменения скрытого поля выбора файлов input type=file
				part.els.inp.onchange = function (ev) {
					_this.pushFilesForDownload(key, ev.target.files === undefined ? ev.target.value : ev.target.files);
					if (part.pushedCount !== 0) {
						_this.startDownload(key);
					}
				};

				// Запустить загрузку файлов из библиотеки
				part.els.restoreBtn.onclick = function () {

					jQuery(part.els.restoreBtn).hide();

					var objectUploadFiles = {
						uploadType: 'lib',
						sizes: {}
					};
					console.log(objectUploadFiles);
					$.each(vars.configPage.oldOrder.files, function(index, element) {
						console.log(this);
						var serverFile = new File([], this.src, {type: 'image/png', size: this.size});

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
					_this.pushFilesForDownload(key, objectUploadFiles);
					if (part.pushedCount !== 0) {
						_this.startDownload(key);
					}
					/*
					console.log(_this);
					console.log(part.els.restoreBtn);
					var
						filesize = jQuery(part.els.restoreBtn).attr('data-filesize'),
						src = jQuery(part.els.restoreBtn).attr('data-src');
					console.log(filesize, src);
					var serverFile = new File([], src, {type: 'image/png'});
					var objectUploadFiles = {
						0: serverFile,
						length: 1,
						uploadType: 'lib',
						filesize: filesize
					};
					console.log(objectUploadFiles);
					_this.pushFilesForDownload(key, objectUploadFiles);
					if (part.pushedCount !== 0) {
						_this.startDownload(key);
					}
					*/
				}

			});

			// Прослушивать события
			document.body.addEventListener('file:loadend', this.onFileLoadEnd.bind(this));  // Загрузка файла завершена
			document.body.addEventListener('file:deleted', this.onFileDelete.bind(this));  // Удаление файла
			document.body.addEventListener('chunk:loaded', this.onChunkLoad.bind(this));  // Кусок загружен

			this.parts.fotoNvideo.els.list.onclick = this.onListClick.bind(this);

			// Загрузить файлы пользователя при перетаскивании?
			document.querySelector('.uploader').addEventListener("drop", function (e) {
				var key = 'fotoNvideo';
				var part = _this.parts[key];
				e.stopPropagation();
				e.preventDefault();
				_this.pushFilesForDownload(key, e.dataTransfer.files);
				if (part.pushedCount !== 0) {
					_this.startDownload(key);
				}
			});

		},

		// Начать загрузку файлов пользователя
		startDownload       : function startDownload(key) {  // key - fotoNvideo or mus
			jQuery('#stepListMedia').addClass('processing');
			var part = this.parts[key];
			console.log('startDownload, part: ', part);
			if (part.loading === true)
				return;

			if (part.items[0] !== undefined) {
				console.log('part.items[0]: ', part.items[0]);
				part.loading = true;
				part.items[0].startUpload();
				part.progress.startTimer();
				//setDisplay('load');
			}
		},

		// Нажать на загрузку файлов
		pushFilesForDownload: function pushFilesForDownload(key, files) {
			console.log(files);
			console.log(files[0]);
			var part = this.parts[key];
			var pushedCount = 0;

			for (var j = 0; j < files.length; j++) {

				if (part.maxLength === part.items.length) /* Ограничить число загрузок */
					continue;

				if (part.items.length == 33) continue;
				pushedCount++;

				var f = files[j];
				if (f.size < 108664326) {

					// Подготовить аттрибуты пользовательского файла для загрузки
					var attrs = {
						file    : f,
						partKey : key,
						ind     : part.startInd,
						name    : f.name,
						notAFile: f.notAFile,
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
					console.log(part.els.list);

					var newFile = new UploadingFile(attrs);  // Вызывается функция render?
					part.items.push(newFile);
					newFile.view.append(part.els.list);
					newFile.coll = part.items;
				}
				part.startInd++;
			}

			var totalSize = 0;
			part.items.forEach(function (file) {
				totalSize += file.size;
			});
			part.progress.totalSize = totalSize;
			part.pushedCount = pushedCount;

			this.checkLength();  // Совершить действия (создать оповещения, CSS-стили) после загрузки изображения // 2019-12-25 Зачем именно здесь? Рано же ещё.
		},
		setRestored         : function setRestored(fotoNvideo) {
			var _this2 = this;

			[fotoNvideo].forEach(function (files, ind) {

				files.forEach(function (file) {
					file.name = file.name || '';
					file.notAFile = true;
					file.type = file.type || 'image';
				});

				_this2.pushFilesForDownload('fotoNvideo', files);
			});
		},

		// Конец загрузки одного из файлов пользователя
		onFileLoadEnd       : function onFileLoadEnd(ev) {
			var _JSON$parse = JSON.parse(ev.detail);

			var partKey = _JSON$parse.partKey;
			var ind = _JSON$parse.ind;


			var part = this.parts[partKey];

			var file = this.findByInd(ind, partKey);
			var indInArr = part.items.indexOf(file);

			var isLast = indInArr === part.items.length - 1;

			if (isLast === false) {
				console.log('onFileLoadEnd isLast, indInArr, part.items:', indInArr, part.items);
				part.items[indInArr + 1].startUpload();  // Загрузить следующий файл
			} else {
				this.stopLoading(part);  // Завершить загрузку файлов
			}
			if (partKey === 'fotoNvideo') {
				this.checkLength();
			}
			//console.log(part);
			console.log(part.progress);
			part.progress.reset();  // weezy
			console.log(part.progress);
			//setDisplay(false);
		},
		onFileDelete        : function () {
			this.checkLength();
			setDisplay('imageUploaded');
			//setDisplay(true);  // weezy
		},
		checkLength         : function () {
			var part = this.parts.fotoNvideo;
			//Проверить количество пригодных ресурсов
			var length = 0;
			for (var i = 0; i < part.items.length; i++) {
				var curItem = part.items[i];
				if (curItem.type.indexOf('image') > -1 || curItem.type.indexOf('video') > -1) {
					if (curItem.src !== null && curItem.id !== null) {
						length++;
					} else {
						if (curItem.file == null) {
							this.findByInd(curItem.ind, 'fotoNvideo').delete();
						}
					}
				} else {
					if (curItem.file == null) {
						this.findByInd(curItem.ind, 'fotoNvideo').delete();
					}
				}
			}
			var needed = part.maxLength;
			var remainPhotos = needed - length;
			var needPhotos = vars.configPage.minNumberFiles - length

			/* 2020-04-13 Видимость элементов в зависимости от кол-во фото теперь упраялется функцией showMinMax()
			// Есть загруженные фото
			if (remainPhotos < 1) { // Загружено максимальное кол-во фото
				//document.querySelector('.btn-upload-media').classList.add('d-none');
				part.isFull = true;
			} else {
				part.isFull = false;
			}
			*/

			if (needPhotos > 0) {
				jQuery(part.els.messCounter).show();
				console.log(part.els.counter);
				part.els.counter.innerHTML = needPhotos;
			}
			else {
				jQuery(part.els.messCounter).hide();
			}

			addNumbers();
		},

		onChunkLoad         : function onChunkLoad(ev) {
			var _JSON$parse2 = JSON.parse(ev.detail);
			var chunkSize = _JSON$parse2.chunkSize;
			var partKey = _JSON$parse2.partKey;
			var part = this.parts[partKey];
			part.progress.loaded += chunkSize;
		},
		onListClick         : function onListClick(ev) {
			var trg = ev.target;
			if (!trg.classList.contains('del_media')) return;

			var ind = parseInt(trg.dataset.ind);
			if (isNaN(ind)) return;

			var file = this.findByInd(ind, 'fotoNvideo');
			if (file === null) return;

			file.delete();
		},
		findByInd           : function findByInd(ind, partKey) {
			var file = null;
			var items = this.parts[partKey].items;
			for (var j = items.length - 1; j >= 0; j--) {
				if (items[j].ind === ind) {
					file = items[j];
					break;
				}
			}
			return file;
		},

		// Закончить загрузку порции файлов пользователя TODO: Возможно, лучше сюда переместить обрезку фото
		stopLoading         : function stopLoading(part) {
			console.log('stopLoading');
			setDisplay('cropHidden');
			part.loading = false;
			part.progress.stopTimer();
			part.progress.el.style.display = 'none';
			this.resetInput(part.els.inp);

			// weezy.app: Запустить обрезку всех загруженных файлов пользователя, не содержащих данные об обрезке
			if (vars.configPage.isNeedCropping == 0) {
				return;
			}
			console.log(jQuery('#fotoNVideoContainer img'));
			jQuery('#fotoNVideoContainer img').each(function (index, tempImg) {

				//console.log( 'index : ' + index );
				//console.log( tempImg );
				//console.log(jQuery(tempImg).attr('data-crop'));
				if (!jQuery(tempImg).attr('data-crop-сanvas')) {

					// Открыть редактор резки с появлением превьюшки загруженного изображения
					//jQuery('#modalCrop').modal('show');
					//setDisplay(false);
					var cropCreateTimeout = setInterval(function () {
						console.log(tempImg);
						console.log('cropping.$croppedMedia: ', cropping.$croppedMedia);
						if (!cropping.$croppedMedia) {
							console.log(cropper);
							console.log(cropping.imageCrop);
							console.log(jQuery(cropping.imageCrop));
							jQuery(cropping.imageCrop).cropper('destroy');
							console.log(cropper);
							cropping.createCrop(tempImg);
							clearInterval(cropCreateTimeout);
						}
					}, 100);
				}

			}).promise().done( function(){ jQuery('#stepListMedia').removeClass('processing'); } );
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
			var mediaList = this.parts.fotoNvideo.els.list;
			var media = this.parts.fotoNvideo;

			if (media.loading !== false)
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
			var el = this.parts.fotoNvideo.els.list;
			new Sortable(el, {
				draggable: 'li'
			});
		}
	};

	// /LOADER


	function q(id) {
		return document.getElementById(id);
	}


	// CLASS TEXTS

	var Texts = function () {
		function Texts(wrp) {
			classCallCheck(this, Texts);

			this.wrp = wrp;
			this.findEls();
		}

		createClass(Texts, [{
			key  : 'findEls',
			value: function findEls() {
				/* Weezy 2020-04-20 Текстовыe поля теперь создаем на лету
				 var _this = this;

				 var els = window.A.toArr(this.wrp.querySelectorAll('li'));

				 els = els.map(function(el) {
				 var seconds = el.dataset.seconds;
				 var inp = el.querySelector('textarea');  // Weezy input[type=text]
				 //var inp = el.querySelector('input[type=text]');
				 inp.addEventListener('blur', _this.onInputBlur.bind(_this));

				 return {
				 // el,
				 seconds: seconds,
				 inp: inp,
				 val: function val(txt) {
				 if (txt !== undefined) {
				 this.inp.value = txt;
				 }
				 return this.inp.value;
				 },
				 validate: function validate() {
				 var val = this.val();
				 // var req = 1;
				 var req = this.inp.getAttribute('data-req');

				 // console.log(this);

				 // Проверить заполненность текстов перед отправкой
				 if (req == '1' && (val === '' || !is2words(val)))
				 {
				 var erName = this.inp.getAttribute('data-name');
				 var elemError = document.createElement('div');
				 elemError.classList.add('errortext');
				 if (val === '') {
				 elemError.innerHTML = 'add text for story';  // weezy
				 }
				 else {
				 elemError.innerHTML = 'add one word';
				 }
				 //elemError.innerHTML = 'Add your ' + erName;
				 var parentDiv = vars.btnSubmit.parentNode;
				 parentDiv.insertBefore(elemError, vars.btnSubmit);
				 this.addErrLis(elemError);
				 this.valid = false;
				 return false;
				 } else {
				 this.valid = true;
				 return true;
				 }
				 },
				 addErrLis: function addErrLis(elemError) {
				 this.inp.classList.add('hasError');
				 this.inp.onfocus = function(ev) {
				 var parentDiv = vars.btnSubmit.parentNode;
				 parentDiv.removeChild(elemError);
				 ev.target.classList.remove('hasError');
				 ev.target.onfocus = null;
				 };
				 }
				 };
				 });

				 this.els = els;
				 */
			}
		}, {
			key  : 'getData',
			value: function getData() {
				var validAll = true;
				var data = this.els.map(function (el) {
					var isValid = el.validate();
					if (isValid === false)
						validAll = false;

					return {
						seconds: el.seconds,
						text   : el.val()
					};
				});

				return {
					valid: validAll,
					texts: data
				};
			}
		}, {
			key  : 'setData',
			value: function setData() {
				var data = arguments.length <= 0 || arguments[0] === undefined ? [] : arguments[0];

				var els = this.els;
				data.forEach(function (txt) {
					for (var j = els.length - 1; j >= 0; j--) {
						if (txt.seconds === els[j].seconds) {
							els[j].val(txt.text);
							break;
						}
					}
				});
			}
		}, {
			key  : 'onInputBlur',
			value: function onInputBlur(ev) {
				///////////////// this.saveTxt();
				// let trg = ev.target
				// if ( trg.type === 'text' && trg.tagName.toLowerCase() === 'input' ) {
				//	 for ( let j = this.els.length - 1; j >= 0; j-- ) {
				//		 let txt = this.els[ j ]
				//		 if ( txt.inp === trg ) {
				//			 this.saveTxt( txt )
				//		 }
				//	 }
				// }
			}
		}, {
			key  : 'saveTxt',
			value: function saveTxt(txt) {
				var data = ['id_design=', vars.configPage.idDesign];
				data.push('texts=' + JSON.stringify(jQuery(form).serializeArray()));
				//data.push('texts=' + JSON.stringify(this.getData().texts));
				// data.push( `seconds=${txt.seconds}`, `text=${txt.val()}` )

				var URL_TXT_SAVE = vars.FF_root + '/app/ajax/ajax-save-text.php';
				fetch(URL_TXT_SAVE, {
					method : 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body   : data.join('&') + '&sid=' + ses_id + '&uident=' + ses_ident
				});
			}
		}]);
		return Texts;
	}();

	// /CLASS TEXTS


	// ОТПРАВИТЬ ЗАКАЗ

	function sendClipData(Loader, texts) {

		var data = collectData();
		console.log('sendClipData', data);
		if (data.isFull !== true)
			return;

		delete data.isFull;

		sendData(data);

		function collectData() {

			var result = {
				isFull: true
			};

			var email = getEmail();
			result.email = email.val;
			if (email.valid === false) {
				result.isFull = false;
			}

			/* 2020-04-22 Weezy, текстовые поля теперь другие
			var txt = texts.getData();
			//result.texts = JSON.stringify(txt.texts).replace(/&/g, '%26');
			result.texts = encodeURIComponent(JSON.stringify(txt.texts));
			if (txt.valid === false) {
				result.isFull = false;
			}
			*/
			var files = Loader.getData();
			if (files === null) {
				result.isFull = false;
			} else {
				result.media = JSON.stringify(files.media);
				result.items = JSON.stringify(Loader.parts.fotoNvideo.items, function (key, value) {
					if (key == 'coll' || key == 'view') {  // TODO: Разобраться, что за coll и view?
						return undefined;
					}
					return value;
				});
			}

			return result;
		}

		// Отправить собранные данные скрипту сохранения заказа
		function sendData(data) {
			//yaCounter25315490.reachGoal('zakaz-video');
			setTimeout(function () {
				jQuery('.btn-arrow-left, .btn-arrow-right').hide();
				vars.btnSubmit.setAttribute("disabled", "disabled");
				vars.btnSubmit.value = 'processing your order ...';
				vars.btnSubmit.classList.add('loading');
				vars.btnSubmit.classList.remove('btn-outline-light');
				vars.btnSubmit.classList.remove('btn-light');
				vars.btnSubmit.classList.remove('btn');
				vars.btnSubmit.textContent = 'processing your order ...';
				setDisplay('formSubmit');  // Wezzy
			}, 800);
			//return false;


			// Добавить данные из data-аттрибутов медиа-файлов в заказ
			var dataMediaFiles = [];
			jQuery('#fotoNVideoContainer li img').each(function () {
				var fileData = jQuery(this).data();

				// Не передавать на обработку некоторые data-аргументы медиа-файла
				delete fileData.src;
				//delete fileData.height;  // 2021-02-05 Размеры медиа-файла нужны для пере-обрезки
				//delete fileData.width;
				delete fileData.cropCanvas;
				console.log(fileData);
				dataMediaFiles.push(fileData);
			});

			/*
			jQuery('#fotoNVideoContainer li img').each(function () {

				// Добавить ориентиацию фрейма в данные
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


			var ajaxBody = {
				sid        : ses_id,
				uident     : ses_ident,
				id_order   : vars.idOrder,
				less_photos: document.querySelector('#fotoNVideoContainer').dataset.less_photos,
				id_design  : vars.configPage.idDesign,
				//loader     : Loader,
				formData   : jQuery('form').serializeArray(),
				//formData   : jQuery('form').serializeObject(),
				//canvas_data: formDataCrop,
				media_files : dataMediaFiles,
				resolution : resolutionData,
				is_need_cropping: vars.configPage.isNeedCropping
			};

			Object.keys(data).forEach(function (key) {
				ajaxBody[key] = data[key];
			});

			console.log(ajaxBody);

			var URL_POST = vars.FF_root + '/app/ajax/ajax-create-order.php';
			fetch(URL_POST, {
				method : 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body   : jQuery.param(ajaxBody)
			}).then(function (resp) {
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
		}

		function getEmail() {
			var inp = document.querySelector('#emailOrder');
			var val = inp.value;

			var res = {
				valid: true,
				val  : val
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
		}
	}

	// ОТПРАВИТЬ ЗАКАЗ

	setPageStyles();

	var texts = void 0;

	function init() {

		texts = new Texts(document.getElementById('textsList'));

		addSendListener();

		// Создать объекты Loader, texts
		Loader.init();
		texts.setData();

		/*
		restoreOldSession().then(function (restored) {
			restored = restored || {};
			Loader.init(restored.media, restored.audio);
			texts.setData(restored.texts);
		});
		*/

	}
	init();

	function addSendListener() {
		vars.btnSubmit.onclick = function () {
			//Очистить историю ошибок
			var parentDiv = vars.btnSubmit.parentNode;
			Array.from(parentDiv.querySelectorAll('.errortext')).forEach(function (item, i, arr) {
				parentDiv.removeChild(item);
			});
			sendClipData(Loader, texts);
			//return false;
		};
	}

	function setPageStyles() {
		var doc = document.documentElement;
		doc.style.overflow = 'visible';
		doc.style.overflowX = 'hidden';
		doc.style.height = 'auto';
		document.body.style.height = 'auto';
	}

	// ===================================


	//circularBarSet();
	//circularBarRotate(vars.$circularBar.find('.progress-right .progress-bar'), '45');


	// CHANGE TEXTAREA
	//console.log($('#textOrder')[0]);
	//autoHeight_($('#textOrder')[0]);
	//$('#textOrder').
	$('#boxTextareaCropping textarea')
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


	$('#fotoNVideoContainer')
		.on('change input paste keyup propertychange', 'textarea', function () {
			autoHeight_(this);
		});

	// /CHANGE TEXTAREA

	// Показать/скрыть элементы, зависимые от кол-ва загруженных медиа-файлов
	showMinMax();

	// Загрузить старый заказ
	if (vars.configPage.oldOrder) {
		console.log(vars.configPage.oldOrder);
		console.log(View);
		/*
		var view2 = new View({
			type   : this.type,
			name   : this.name,
			ind    : this.ind,
			partKey: this.partKey
		});
		console.log(view2);
				*/
		$.each(vars.configPage.oldOrder.files, function() {

			console.log(this);			/*
			View.setPreview(false, this.src, this.width, this.height);
			Loader.stopLoading;
			*/
		});
	}

});

// /DOCUMENT READY


// Когда поля текста и почты заполнены - измени заливку кнопки
function highlightBtnSubmit() {
	var
		valueTextOrder = jQuery('#textOrder').val(),
		valueEmailOrder = vars.$emailOrder.val(),
		$btnSubmit = jQuery(vars.btnSubmit);
	if (
		is2words(valueTextOrder)
		&& valueEmailOrder != ''
		&& isEmail(valueEmailOrder)
	) {
		$btnSubmit.children().removeClass('d-none');
	} else {
		$btnSubmit.children().addClass('d-none');
	}
}
jQuery('#stepListMedia').on('input propertychange', '#textOrder, #emailOrder', highlightBtnSubmit);
//$textOrder.on('click', function() { scrollToAnchor('emailOrder'); });
vars.$emailOrder.on('click', function () {
	scrollToAnchor('btnSubmit');
});