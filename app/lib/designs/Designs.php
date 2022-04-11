<?php
// Файл User.php


class Designs
{

	// Свойства слайдшоу по умолчанию
	private static $types_design = [
		'postcard' => [
			'subtitle' => 'для важного события',
			'img' => 'full_HD.png',
			'number_files' => false,
			'min_number_files' => 10,
			'max_number_files' => 30,
			'width_slideshow' => 1920,
			'height_slideshow' => 1080,
			'width_horizontal_frame' => '',
			'height_horizontal_frame' => '',
			'width_vertical_frame' => '',
			'height_vertical_frame' => '',
			'poster' => '',
			'video' => 'https://wow.cards/wp-content/themes/wow.cards/assets/img/video_16_9.mp4'
		],
		'slideshow' => [
			'subtitle' => 'для важного события',
			'img_subtitle' => 'full_HD.png',
			'number_files' => false,
			'min_number_files' => 10,
			'max_number_files' => 30,
			'width_slideshow' => 1920,
			'height_slideshow' => 1080,
			'width_horizontal_frame' => '',
			'height_horizontal_frame' => '',
			'width_vertical_frame' => '',
			'height_vertical_frame' => '',
			'poster' => '',
			'video' => 'https://wow.cards/wp-content/themes/wow.cards/assets/img/video_16_9.mp4',
			'free' => false
		],
		'story' => [
			'subtitle' => 'Видео для моб',
			'img_subtitle' => '1080х1920.png',
			'number_files' => false,
			'min_number_files' => 1,
			'max_number_files' => 3,
			'width_slideshow' => 1080,
			'height_slideshow' => 1920,
				/*
			'width_horizontal_frame' => 1080,
			'height_horizontal_frame' => 1920,
				*/
			'width_vertical_frame' => 1080,
			'height_vertical_frame' => 1920,
			'poster' => '',
			'video' => '//fromfoto.app/wp-content/themes/fromfoto-app/680557_33_story.mp4',
			'free' => true
		]
	];
	private static $designs = [
		/* 2021-07-21 Илья: 1 и 4 убери а то будут приходить заказы, но не делаться
			'type' => 'slideshow',
			'name' => 'slideshow1',
			'width_horizontal_frame' => '1920',
			'height_horizontal_frame' => '1080',
			'width_vertical_frame' => '608',
			'height_vertical_frame' => '1080',
		],
		*/
		2 => [
			'type' => 'slideshow',
			'name' => 'slideshow2',
			/*
			'width_horizontal_frame' => '1000',
			'height_horizontal_frame' => '500',
			'width_vertical_frame' => '500',
			'height_vertical_frame' => '1000',
			*/
			'width_horizontal_frame' => '1920',
			'height_horizontal_frame' => '1080',
			'width_vertical_frame' => '607',
			'height_vertical_frame' => '1080',
            'id_video_youtube' => '8gQHfTa4Wmg'
		],
		3 => [
			'type' => 'slideshow',
			'name' => 'slideshow3',
			'width_horizontal_frame' => '1920',
			'height_horizontal_frame' => '1080',
			'width_vertical_frame' => '608',
			'height_vertical_frame' => '1080',
            'id_video_youtube' => 'R-Y53OC25Aw'
        ],
		/*
		4 => [
			'type' => 'slideshow',
			'name' => 'slideshow4',
			'width_horizontal_frame' => '2300',
			'height_horizontal_frame' => '1294',
			'width_vertical_frame' => '728',
			'height_vertical_frame' => '1294',
		],
		*/
		5 => [
			'type' => 'slideshow',
			'name' => 'slideshow8',
			'width_horizontal_frame' => '1920',
			'height_horizontal_frame' => '1080',
			'width_vertical_frame' => '1080',
			'height_vertical_frame' => '1920',
            'id_video_youtube' => '2eL7swLKPh8'
		],
		6 => [
			'type' => 'slideshow',
			'name' => 'slideshow9',
			'width_horizontal_frame' => '1920',
			'height_horizontal_frame' => '1080',
			'width_vertical_frame' => '608',
			'height_vertical_frame' => '1080',
            'id_video_youtube' => 'KFjpa3ZbuVc'
		],
		7 => [  // Бесплатный!
			'type' => 'slideshow',
			'name' => 'slideshow10',
			'width_horizontal_frame' => '1920',
			'height_horizontal_frame' => '1080',
			'width_vertical_frame' => '608',
			'height_vertical_frame' => '1080',
            'id_video_youtube' => 'iZEahQ9Z4DY',
			'free' => true
		],
		
		//
		8 => [
			'type' => 'story',
			'name' => 'story1',
		],
		9 => [
			'type' => 'story',
			'name' => 'story2',
		],
		/*
		10 => [
			'type' => 'story',
			'name' => 'Story4',
		],
		*/
		11 => [
			'type' => 'story',
			'name' => 'story5',
		],
		12 => [
			'type' => 'story',
			'name' => 'story6',
		],
		13 => [
			'type' => 'story',
			'name' => 'story7',
		],
	];
	
	private static function collect_settings_design($design, $id_design)
	{
		$design += self::$types_design[ $design['type'] ];
		$design['id'] = $id_design;
		return $design;
	}
	
	// Проверить имя типа дизайна и вернуть имя по умолчанию, если указанного не существует
	public static function verify_type($type_design)
	{
		$verified_type_design = array_key_first(self::$types_design);
		foreach (self::$types_design as $key => $design) {
			if ($key == $type_design) {
				$verified_type_design = $key;
				break;
			}
		}
		return $verified_type_design;
	}
	/*
	public function __construct()
	{
		foreach (self::$designs as $id => $design) {
			var_dump($design);
			$design += $this->settings['slideshow'];
			$design['id'] = $id;
			self::$designs[$id] = $design;
		}
	}
	*/
	
	# Вернуть тип дизайнов
	public static function get_type_design($id_type_design)
	{
		if ( !isset( self::$types_design[$id_type_design] ) )
			die ('Типа дизайна с именем "' . $id_type_design . '" не существует');
		$type_design = self::$types_design[$id_type_design];
		return $type_design;
	}

	# Получить дизайны указанного типа
	public static function get_designs($type_design)
	{
		$designs = [];
		foreach (self::$designs as $key => $design) {
			if ($design['type'] != $type_design) {
				continue;
			}
			$designs[$key] = self::collect_settings_design($design, $key);
		}

		return $designs;
	}

	# Получить открытки
    public static function get_postcards($category_postcards)
    {
        $postcards = json_decode(file_get_contents(PATH_APP . '/lib/designs/postcard/cards.json'), true);
        return $postcards;
    }

	# Вернуть один дизайн
	public static function get_design($id_design)
	{
		# Id дизайна передан в виде его имени
		if (!is_int($id_design)) {
			foreach (self::$designs as $id => $design) {
				if ($design['name'] == $id_design) {
					$id_design = $id;
					break;
				}
			}
		}
		
		if ( !isset( self::$designs[$id_design] ) )
			die ('Дизайна с именем "' . $id_design . '" не существует');
		$design = self::$designs[$id_design];
		$design = self::collect_settings_design($design, $id_design);
		return $design;
	}

    # Вернуть одну открытку
    public static function get_postcard($id_postcard)
    {
        $postcards = self::get_postcards(false);
		$postcard = $postcards[(array_search($id_postcard, array_column($postcards, 'name')))];
		$postcard['preview'] = str_replace('.png', '.jpg', $postcard['preview']);
        return $postcard;
        //return $postcards[(array_search($id_postcard, array_column($postcards, 'name')))];
    }

    /*
    # Вернуть один дизайн
    public function _get_design($id_design)
    {
        # Id дизайна пепредан в виде его имени
        if (!is_int($id_design)) {
            foreach ($this->designs as $id => $design) {
                if ($design['name'] == $id_design) {
                    $id_design = $id;
                    break;
                }
            }
        }

        if ( !isset( $this->designs[$id_design] ) )
            die ('Дизайна с именем "' . $id_design . '"" не существует');
        $design = $this->designs[$id_design];
        return $design;
    }
    */
	
	# Получить хэш страницы продукта
	public static function get_hash_product($id_product) {
		return substr(md5($id_product . 'product'), 0, 8);
	}
	
	# Вычислить цену продукта
	public static function calc_price_product($quantity_frames) {
		return 750 + 50 * $quantity_frames;
	}
	
	# Получить долю цены
	public static function get_part_price_product(
		$price,
		$part // 1 - Цена предоплаты, 2 - Финальная оплата
	) {
		$percentage_prepayment = .1;
		if ($part == 1) {
			return $price * $percentage_prepayment;
		} else {
			return $price * (1 - $percentage_prepayment);
		}
	}
	
	
}

?>