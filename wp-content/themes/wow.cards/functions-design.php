<?php

namespace design;


function isMobileDevice() {
	return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

# Получить URL с хешем страницы продукта
function get_url_product($id_product) {
	$hash = \Designs::get_hash_product($id_product);
	return home_url() . '/product/' . $id_product . '/?' . http_build_query(['hash' => $hash]);
}

# Получить список заказов юзера из кук
function get_ids_order_cookie() {
    $ids_orders = [];
    if (isset($_COOKIE['ids_orders']))
        $ids_orders = unserialize($_COOKIE['ids_orders']);
    //var_dump($ids_orders);
    return $ids_orders;
}
/*
# Получить список дизайнов
function get_designs() {
	require_once PATH_APP . '/lib/lib-designs.php';
	return get_design_properties();
}
*/

# Получить видео дизайна
function get_video_design( $id_design ) {
	$meta_post = get_post_meta( $id_design );	// Получить meta-данные дизайна
	$video_meta = false;
	if (!isMobileDevice() && isset($meta_post['video_pc'][0]))
		$video_meta = $meta_post['video_pc'][0];
	elseif (isset($meta_post['video_mobile'][0]))
		$video_meta = $meta_post['video_mobile'][0];
	if ($video_meta)
		$video_meta = wp_get_attachment_url($video_meta);
	return $video_meta;
}

# Объединить данные дизайна из WP-записи и отдельно хранящиеся данные из мета БД WP и
# - мета-данные WP-записи;
# - программные свойства дизайна из файлов /app/lib/designs, привязанные через name_design
# - видео
function merge_design_properties($data_db) // Вход - Id или slug дизайна
{
    # Добавить мета-данные WP-записи
    //var_dump($data_db);
    $meta_post = get_post_meta( $data_db->ID );
    foreach ($meta_post as $key_meta => $item_meta) {
        $data_db->{$key_meta} = $item_meta[0];
    }
    //var_dump($data_db);

	# Получить программное имя дизайна
	if ($data_db->post_type != 'story')  // У сторис нет категорий, поэтому имя дизайна хранится прямо в имени WP-записи)
		$name_design = $data_db->name_design;
	else  $name_design = $data_db->post_name;
	
	# Получить данные дизайна из файлов
	if ($data_db->post_type == 'postcard') { // Открытки хранятся особыми образом
		$data_file = \Designs::get_postcard($name_design); // $data_db->post_name
		
		# Превьюшки открыток
		$thumbnail_design = get_the_post_thumbnail_url( $data_db->ID );
		if ($thumbnail_design)
			$data_file['preview'] = $thumbnail_design;
		else
			$data_file['preview'] = URL_APP . '/lib/designs/postcard/images/' . $data_file['preview'];
	}
	else
		$data_file = \Designs::get_design($name_design);  // $data_db->post_name

	# Для администраторов сделать дизайны бесплатными
	if( current_user_can( 'administrator' ) ) { // only if administrator
		$data_file['free'] = true;
	}

	# Получить ссылку на видео-превью дизайна
	$video_design = get_video_design( $data_db->ID );
	if ($video_design)
		$data_db->video = $video_design;
	
	return (array) $data_db + $data_file;
}

# Вернуть дизайн
function get_design($id_design) // Вход - Id WP-записи дизайна
{
	$args = array('p' => $id_design, 'post_type' => 'any');
	$query = new \WP_Query($args);
    //var_dump($query);
	$custom_design = $query->posts[0];

	return merge_design_properties($custom_design);
}

# Переформатировать заказ под другой дизайн
function recrop_photo($width, $height, $width_frame, $height_frame, $x_frame, $y_frame, $aspect_ratio_new_frame) {

    // Вычислить площадь фрейма
    $new_frame = array();  // Массив характеристик нового фрема
    $new_frame['area'] = $width_frame * $height_frame;

    // Получить размеры предварительного фрейма
    $new_frame['width'] = sqrt($new_frame['area'] * $aspect_ratio_new_frame);
    $new_frame['height'] = $new_frame['width'] / $aspect_ratio_new_frame;

    // Получить координаты предварительного фрейма
    $center_x = $x_frame + $width_frame / 2; // Вычислить середину фрейма
    $center_y = $y_frame + $height_frame / 2;
    $new_frame['x'] = $center_x - $new_frame['width'] / 2;
    $new_frame['x2'] = $center_x + $new_frame['width'] / 2;
    $new_frame['y'] = $center_y - $new_frame['height'] / 2;
    $new_frame['y2'] = $center_y + $new_frame['height'] / 2;


    // ПОЛУЧИТЬ КООРДИНАТЫ ФИНАЛЬНОГО ФРЕЙМА, ЕСЛИ ПРЕДВАРИТЕЛЬНЫЙ НЕ УКЛАДЫВАЕТСЯ В ФОТО

    // Получить величины превышения фрейма над размерами фото
    $new_frame['overflow_x'] = abs(min(0,
        $new_frame['x'],
        $width - $new_frame['x2']
    ));
    $new_frame['overflow_y'] = abs(min(0,
        $new_frame['y'],
        $height - $new_frame['y2']
    ));

    // Получить коэффициенты соотношения предварительного и финального фрейма
    $new_frame['ratio_x'] = 1;
    if ($new_frame['overflow_x'] > 0) {
        $new_frame['ratio_x'] = $new_frame['width'] / ($new_frame['width'] - $new_frame['overflow_x'] * 2);
    }
    $new_frame['ratio_y'] = 1;
    if ($new_frame['overflow_y'] > 0) {
        $new_frame['ratio_y'] = $new_frame['height'] / ($new_frame['height'] - $new_frame['overflow_y'] * 2);
    }
    $new_frame['ratio'] = max($new_frame['ratio_x'], $new_frame['ratio_y']);

    // Получить координаты финального фрема
    if ($new_frame['ratio'] != 1)
    {
        $new_frame['width'] /= $new_frame['ratio'];
        $new_frame['height'] /= $new_frame['ratio'];
        $new_frame['width'] = round($new_frame['width'], 12);  // Округлить, чтобы отбросить неточность оперций над дробными числами https://floating-point-gui.de/
        $new_frame['height'] = round($new_frame['height'], 12);
        $new_frame['x'] = round($center_x - $new_frame['width'] / 2, 12);
        $new_frame['y'] = round($center_y - $new_frame['height'] / 2, 12);
    }

    return $new_frame;
}


function change_sources_design($sources_order, $data_new_design)
{

    if (!$data_new_design['is_need_cropping'] || !isset($sources_order[0]['x']))
        return $sources_order;

    //var_dump($sources_order);
    // Получить новое разрешение фрейма
    //$data_new_design = get_design($id_new_design);
    $new_resolution = ['width' => 2000, 'height' => 1000];
    $aspects_ratio = [
        'default' => false,
        'horizontal' => false,
        'vertical' => false
    ];
    if (!empty($data_new_design['width_slideshow']) && !empty($data_new_design['height_slideshow'])) {
        $aspects_ratio['default'] = $data_new_design['width_slideshow'] / $data_new_design['height_slideshow'];
    }
    if (!empty($data_new_design['width_horizontal_frame']) && !empty($data_new_design['height_horizontal_frame'])) {
        $aspects_ratio['horizontal'] = $data_new_design['width_horizontal_frame'] / $data_new_design['height_horizontal_frame'];
    }
    if (!empty($data_new_design['width_vertical_frame']) && !empty($data_new_design['height_vertical_frame'])) {
        $aspects_ratio['vertical'] = $data_new_design['width_vertical_frame'] / $data_new_design['height_vertical_frame'];
    }

    foreach ($sources_order as $k => $data_crop) {

        # Вычислить стороны фрейма с учетом поворота
        $width = $data_crop['naturalWidth'];
        $height = $data_crop['naturalHeight'];
        if (($data_crop['rotate'] + 90) % 180 == 0) {
            $width = $data_crop['naturalHeight'];
            $height = $data_crop['naturalWidth'];
        }

        $aspect_ratio = $aspects_ratio['default'];
        if ($data_crop['width'] > $data_crop['height']) {
            $aspect_ratio = $aspects_ratio['horizontal'];
        } else {
            $aspect_ratio = $aspects_ratio['vertical'];
        }

        //var_dump($width, $height, $aspect_ratio);
        $data_recrop = recrop_photo(
            $width,
            $height,
            $data_crop['width'],
            $data_crop['height'],
            $data_crop['x'],
            $data_crop['y'],
            $aspect_ratio
        );
        //var_dump($data_recrop);
        $new_data_crop = [
            'width'  => $data_recrop['width'],
            'height' => $data_recrop['height'],
            'x'      => $data_recrop['x'],
            'y'      => $data_recrop['y']
        ];
        $sources_order[$k] = array_merge($data_crop, $new_data_crop);
    }
    return $sources_order;
}

function box_postcard($design) {
	$class_scc_overlay_upload = '';
	if (!isset($design['photo'])) {
		$class_scc_overlay_upload = 'd-none';
	}
	// <a href="' . home_url() . '/postcard-ru/?theme=' . $design['name'] . '">
	$box_postcard = '
<div class="box-card">
	<div class="card rounded-lg border-0">
		<a href="' . home_url() . '/postcard/' . $design['post_name'] . '/">
			<img class="card-img" src="' . $design['preview'] . '" alt="Card image">
		</a>
		<div class="card-img-overlay ' . $class_scc_overlay_upload . '">
			<h5 class="card-title mt-3 text-white text-center">Загрузить фото <span class="material-icons">file_upload</span></h5>
		</div>
		<div class="row card-buttons">
			<div class="col text-center"><a href="' . home_url() . '/postcard/' . $design['post_name'] . '/" class="btn btn-primary px-1">Отправить</a></div>'
			//<div class="col-lg-6"><a href="' . $design['preview'] . '" class="btn btn-sm btn-outline-dark px-1 w-100">Заменить текст</a></div>
		. '</div>
	</div>
</div>';
	return $box_postcard;
}

# Видео-плееры дизайнов
function player_server($type_design, $url_media = false)
{
	if ($type_design == 'slideshow')
		$class_css_aspect = '16by9';
	else
		$class_css_aspect = '9by16';
	if (!$url_media)
		$url_media = 'https://wow.cards/wp-content/uploads/2021/09/Story1.mp4';
    return '
<div class="embed-responsive embed-responsive-' . $class_css_aspect . '">
	<video id="video' . md5($url_media) . '" loading="lazy" class="embed-responsive-item video-js" crossorigin playsinline loop autoplay muted controls
		data-setup=\'{"userActions":{"doubleClick":false},"controlBar":{"fullscreenToggle":false}}\'>
		<source src="' . $url_media . '" type="video/mp4">
		Your browser does not support the video tag.
	</video>
</div>';  // class="video-js" data-setup="{}" || plyr  || {controlBar:{fullscreenToggle:false}}
}

function player_youtube($id_video) {
    return '<div class="video-container">
    <iframe name="ff_home_iframe" src="https://www.youtube.com/embed/' . $id_video
        . '?version=3&autoplay=1&mute=1&controls=1&loop=1&showinfo=0&rel=0&loop=1&playlist=' . $id_video
        . '" width="340" height="193" allowfullscreen="allowfullscreen"></iframe>'
        . '</div>';
}
function player_design($type_design, $url_video) {
    //return player_youtube($id_video);
    return player_server($type_design, $url_video);
}

function player_slideshow($url_video = false) {
	//return player_youtube($id_video);
	return player_design('slideshow', $url_video);
}

function player_story($url_video) {
	//return player_youtube($id_video);
	return player_design('story', $url_video);
}
/*
# Получить видео-превью дизайна
function get_video_design($id_category_design) {
	
}
*/
# Получить дизайны по категории

function get_designs($type_design = 'slideshow', $id_category_design = false, $show_per_page = 12)
{

    $args = array(
        'posts_per_page' => $show_per_page, // количество постов на странице
        'post_type' => $type_design,
		'orderby' => array( 'menu_order' => 'ASC' ),
		//'orderby' => 'menu_order',
		//'sort_column' => 'menu_order',
    );

    // Искать внутри категорий для всех типов дизайнов кроме сторис
    if ($id_category_design && $type_design != 'story')
    {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'category_design',
				//'taxonomy' => 'category_' . $type_design,
				'field' => 'term_id',
				'terms' => $id_category_design
			)
		);
	}

    $query = new \WP_Query( $args );

    $posts = $query->posts;
    /*
	var_dump($args);
	var_dump($query);
	var_dump($posts);
    */
	
    // Вернуть дизайны по умолчанию, если текущий спиток пустой
    if (!$posts)
    	return get_designs($type_design, get_id_default_category_design(), $show_per_page);

    $designs = [];
    //$designs_data = Designs::get_designs($type_design);
	
	# Добавить в свойства дизайна отдельно хранящиеся данные:
    # - мета-данные WP-записи;
	# - программные свойства дизайна привязанные через name_design
	# - видео
	# - превью
    foreach ($posts as $post) {
		$designs[] = merge_design_properties($post);
    }

    //var_dump($designs); die;
    return $designs;
}

function get_postcards($id_category_design = false, $postcards_to_show) {
    return get_designs('postcard', $id_category_design, $postcards_to_show);
}
/*
function get_slideshows($id_category_design = false) {
    return get_designs('slideshow', $id_category_design);
}
function get_stories($id_category_design = false) {
    return get_designs('story', $id_category_design);
}
*/
function slider_designs(
    array $designs//, $show
    //int $id_category_design,
    //array $conf = []
) {
	
    /*
    # Настройки слайдера по умолчанию
    $conf_default = [
        'id' => ''
    ];
    $conf += $conf_default;
    */
	
    # Тип дизайна

	$type_design     = $designs[0]['post_type'];
    $html = '';


    
    # Создать HTML слайдов
    $carousel_items = [];
    if ($type_design == 'postcard') { // Открытки выводить по три в ряд
		$rows = array_chunk($designs, 1);
		//var_dump($rows);
		foreach ($rows as $row) {
			$carousel_item = '<div class="row list-postcards">';
			foreach ($row as $design) {
				$carousel_item .=  '<div class="col">' . box_postcard($design) . '</div>';  // col-sm-6 col-md-6
			}
			$carousel_item .= '</div>';
			$carousel_items[] = $carousel_item;
		}
	}

	else
		foreach ($designs as $design) {
			$carousel_items[] = '
				<figure class="figure_video">
					<div>
						' . player_design($type_design, $design['video']) . /* player_design($design['id_video_youtube']) .*/ '
						<!--<div class="text-center font-italic">' . $design['name'] . '</div>-->
					</div>
				</figure>';
		}


	
	$num_design = 0;
	foreach ($carousel_items as $carousel_item) {
		//var_dump($design);
		$active = '';
		if ($num_design == 0) { $active = ' active'; }
		$html .= '
			<div class="carousel-item' . $active . '"
				 data-url="' . home_url() . '/' . $type_design . '/' . $designs[$num_design]['post_name'] . '"
				 data-id-design="' . $designs[$num_design]['ID'] . '"
				 >
				' . $carousel_item . '
			</div>';
		$num_design++;
	}

	
	# Создать HTML контейнера слайдера
	
    # Собрать URL первого дизайна
    $url_first_design = home_url() . '/' . $type_design . '/' . $designs[0]['post_name'];
	
    $id_carousel     = 'carouselChoose' . ucfirst($type_design);
    $html = '
    <div id="' . $id_carousel . '" data-ride="carousel" data-interval="false"
         class="carousel slide mx-auto type_design_' . $type_design . '">
         <ol class="carousel-indicators">
    <li data-target="#' . $id_carousel . '" data-slide-to="0" class="active"></li>
    <li data-target="#' . $id_carousel . '" data-slide-to="1"></li>
    <li data-target="#' . $id_carousel . '" data-slide-to="2"></li>
    <li data-target="#' . $id_carousel . '" data-slide-to="3"></li>
    <li data-target="#' . $id_carousel . '" data-slide-to="4"></li>
  </ol>
        <div class="carousel-inner container_video">
' . $html . '
        </div>
        <a class="carousel-control-prev d-flex" href="#' . $id_carousel . '" type="button" data-target="#' . $id_carousel . '" data-slide="prev">
            <!--<span class="carousel-control-prev-icon" aria-hidden="true"></span>-->
            <span class="material-icons md-18">arrow_back_ios</span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next d-flex" href="#' . $id_carousel . '" type="button" data-target="#' . $id_carousel . '" data-slide="next">
            <!--<span class="carousel-control-next-icon" aria-hidden="true"></span>-->
            <span class="material-icons md-18">arrow_forward_ios</span>
            <span class="sr-only">Next</span>
        </a>

    </div>';

    if ($type_design != 'postcard') {
        $html.= '<div class="d-flex my-3 justify-content-center box-link-design">
            <a id="link' . ucfirst($type_design) . '" class="btn btn-danger my-1 bubbly-button link_design"
               href="' . $url_first_design . '">создать видео</a>
        </div>';
    }


    return $html;
}


// Получить ID категории по умолчанию
function get_id_default_category_design() {
	return 20;
}

function list_postcards(
	$id_category_design = false,
	$show_postcards = 12
) {

	$postcards = get_postcards($id_category_design, $show_postcards);
	$html = '';
	foreach ($postcards as $key => $design)
	{
		if ($key == 4) {
//			$html .= '
////		<div class="col-5 text-center my-auto">
////			<img src="' . get_stylesheet_directory_uri() . '/assets/img/unicorns.png" alt=""
////				 style="max-width: 63%;">
////		</div>
////		<div class="col-7 text-center my-auto">
////			В открытки с таким значком
////			<span class="btn btn-icon bg-warning rounded-circle mx-1 shadow-sm">
////			<span class="material-icons">file_upload</span>
////			</span>
////			можно загружать <span class="text-danger text-uppercase">СВОИ</span> фото.
////			<br />В каждой открытке можно заменить текст. Нажмите <button class="btn btn-sm btn-outline-dark px-1">Заменить текст</button>
////			<div class="text-danger">Дарите радость :)</div>
////		</div>';
            $html .= '
</div></div>
<div class="how-to col-sm-12">
			<div class="how-to__container _container">
				<div class="how-to__block">
				
					<div class="how-to__block-title title">' . get_field('how_to_create_title') . '</div>
					<br>
					<div class="how-to__block-description">' . get_field('how_to_create_description') . '</div>
				</div>
				<div class="how-to__block">
					<div class="how-to__block-title title">' . get_field('how_to_download_title') . '</div>
					<br>
					<div class="how-to__block-description">' . get_field('how_to_download_description') . '</div>
				</div>
			</div>
			
        <img class="how-to__background" src="' . get_stylesheet_directory_uri() . '/assets/img/how.svg" alt="">
		</div>
		<div class="container-fluid"><div class="row d-flex justify-content-center">';

		} elseif ($key == 8) {
            $html .= '
</div></div>
<div class="help col-sm-12">
			<div class="help__container _container">
				<div class="help__body d-flex align-items-center justify-content-center">
					<img src="' . get_stylesheet_directory_uri() . '/assets/img/unicorn3.svg" alt="на этой картинке единорог" class="help__img">
					<div class="help__text">
						<p>В открытки с таким значком <span class="material-icons">file_upload</span> можно загружать <span>СВОИ</span> фото.</p>
						<p>В каждой открытке можно заменить текст. </p>
						<p><span>Дарите радость :)</span></p>
					</div>
					<img src="' . get_stylesheet_directory_uri() . '/assets/img/unicorn4.svg" alt="на этой картинке единорог" class="help__img">
				</div>
			</div>
			 <img class="help__background" src="' . get_stylesheet_directory_uri() . '/assets/img/help.svg" alt="">
		</div>
		<div class="container-fluid"><div class="row d-flex justify-content-center">';

        } elseif ($key == 0) {
            $html .= '<div id="start-list" class="welcome-screen">
                            <div class="welcome-screen__container _container">
                                <div class="welcome-screen__body">
                                    <div class="welcome-screen__info">
                                        <h1 class="welcome-screen__info-title title">' . get_the_title() . '</h1>
                                        <div class="welcome-screen__info-description">' . get_field('main_description') . '</div>
                                    </div>
                                    <a class="first-item-unic" href="#end"><img src="' . get_stylesheet_directory_uri() . '/assets/img/unicorn2.svg" alt="на этой картинке единорог" class="welcome-screen__unicorn"></a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="container-fluid"><div class="row d-flex justify-content-center">
                        ';
            $html .= '<div  class="col-sm-12"><div class="best__subtitle">' . get_field('box_title') . '</div></div>';

        }


		$html .= '
        
		<div class="col-sm-6 col-md-4 col-xl-3">
			' . box_postcard($design) . '
		</div>';




	}

    $html .= '<div class="col-sm-12 text-center"><button onclick="load_cards();" style=" margin: 50px 0;" class="more-button btn btn-primary px-1">Ещё больше открыток</button></div>';
	$html = '

	<div class="row d-flex justify-content-center list-postcards">' . $html . '
		<div class="col-12 text-center">
			<!--<div class="h1" style="margin: 160px 0 50px;">К сожалению, открыток <span class="text-danger">больше нет</span>!</div>-->
			<a href="#start-list"><img id="end" class="end" style="margin-top: 15px" src="' . get_stylesheet_directory_uri() . '/assets/img/end.svg" alt=""></a>
		</div>
	</div>';

	return $html;

}
