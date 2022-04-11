<?php


# ФУНКЦИИ ТЕМЫ WORDPRESS

# Запустить PHP-сессию
function register_my_session() {
	if( !session_id() ) {
		session_start();
	}
}
add_action('init', 'register_my_session');

add_theme_support( 'post-thumbnails' );


# | ДОБАВИТЬ КОЛОНКУ "ПРЕВЬЮ" В СПИСОК ДИЗАЙНОВ В АДМИНКЕ

// add_filter( 'manage_edit-{ТАКСОНОМИЯ}_columns', 'true_add_columns', 25 );
add_filter( 'manage_edit-slideshow_columns', 'true_add_columns', 25 );
add_filter( 'manage_edit-postcard_columns', 'true_add_columns', 25 );

function true_add_columns( $my_columns ) {

    // наша новая колонка в виде отдельного массива
    $preview = array( 'preview' => 'Превью' );

    // разделяем массив колонок и вставляем новую в нужное место
    $my_columns = array_slice( $my_columns, 0, 1, true ) + $preview + array_slice( $my_columns, 1, NULL, true );

    return $my_columns;

}

add_action( 'admin_head', function() {

    echo '<style>
	#preview{
		width: 58px; /* уменьшить ширину колонки до 58px */
	}
	.postbox-header { /* Заголовки дополнительных полей записей */
		background-color: PaleTurquoise;
	}
	</style>';

} );

add_filter( 'nav_menu_link_attributes', 'change_nav_menu_link_attributes');
function change_nav_menu_link_attributes($atts){
    if (strpos($atts['rel'], "noopener") === false) {
        $atts['rel'] = "noopener";
    }
    return $atts;
}

// add_filter( 'manage_{ТАКСОНОМИЯ}_custom_column', 'true_fill_columns', 25, 3 );
add_filter( 'manage_posts_custom_column', 'true_fill_columns', 25, 3 );

function true_fill_columns( $column_name, $id_term ) {

    switch ( $column_name ) {

        case 'preview': {
						
            /*
            // получаем ID изображения из метаполя
            $image_id = get_term_meta( $id_term, '_preview', true );
            // получаем тег <img> изображения
            $image = wp_get_attachment_image( $image_id, array( 58, 58 ) );
            if( $image ) {
                $out .= $image;
            } else {
                $out .= '<img src="' . get_stylesheet_directory_uri() . '/placeholder.png" width="58" height="58" />';
            }
            */

            
            # Получить slug дизайна по его ID
            //echo
			$slug_design = get_post_field( 'post_name', $id_term);
            //echo
			$post_type = get_post_type($id_term);
	
			$meta_post = get_post_meta( $id_term );
			if (isset($meta_post['name_design'][0])) {
				
				# Расширение файла
				$file_extension = 'jpg';
				//$file_extension = 'png';
				if ('gif' == substr($meta_post['name_design'][0], 0, 3))
					$file_extension = 'gif';
				
				echo '<img src="/app/lib/designs/' . $post_type . '/images/' . ucfirst($meta_post['name_design'][0]) . '_preview.' . $file_extension . '" width="42" height="42" />';
			}

            //$out .= '<img src="/app/lib/designs/postcard/Card1-28_preview.png" width="58" height="58" />';
            break;
        }

    }

    //return $out;

}

# | /ДОБАВИТЬ КОЛОНКУ "ПРЕВЬЮ" В СПИСОК ДИЗАЙНОВ В АДМИНКЕ


# Закрыть страницы от индексирования поисковыми роботами
function noRobots() {
	echo "\t<meta name='robots' content='noindex, nofollow' />\r\n";
}

function fromfoto_enqueue_head_scripts()
{
	
	# Update jquery version
	function replace_core_jquery_version() {
		wp_deregister_script( 'jquery' );
		// Change the URL if you want to load a local copy of jQuery from your own server.
		wp_register_script( 'jquery', get_stylesheet_directory_uri() . '/assets/js/lib/jquery-3.5.1.min.js', array(), '3.5.1' );
		//wp_register_script( 'jquery', "https://code.jquery.com/jquery-3.4.1.min.js", array(), '3.1.1' );
	}
	add_action( 'wp_enqueue_scripts', 'replace_core_jquery_version' );
	
	# Обновить версию файла style.css
	$themecsspath = get_stylesheet_directory() . '/style.css';
	$style_ver = filemtime($themecsspath);
    wp_enqueue_style('style-css', get_stylesheet_uri(), array('bootstrap-css'), $style_ver);
	
	# Включить js- и css-файлы
    //wp_enqueue_script( 'video-js', 'https://vjs.zencdn.net/7.14.3/video.min.js', array('jquery'), '7.14.3' );
    //wp_enqueue_script( 'plyr-js', get_stylesheet_directory_uri() . '/assets/js/lib/plyr.min.js', array('jquery'), '3.6.8' );
    //wp_enqueue_style( 'plyr-css', get_stylesheet_directory_uri() . '/assets/js/lib/plyr.css', false);
    //wp_enqueue_style('fonts.googleapis-style', 'https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@800&family=MuseoModerno:wght@400;700&display=swap', false);


	wp_enqueue_script("jquery");
	//wp_enqueue_script( 'video-js', get_stylesheet_directory_uri() . '/assets/js/lib/video.min.js', array('jquery'), '7.14.3', true );
	wp_enqueue_script( 'popper-min-js', get_stylesheet_directory_uri() . '/assets/js/lib/popper.min.js', array('jquery'), '1.16.1', true );
	wp_enqueue_script( 'bootstrap-js', get_stylesheet_directory_uri() . '/assets/js/lib/bootstrap.min.js', array('popper-min-js'), '4.5.2', true );
    wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css', false, true);
	wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), filemtime( __DIR__ . '/assets/js/custom.js' ), true);
	wp_enqueue_style('fonts.googleapis-style', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&family=PT+Sans:wght@400;700&family=PT+Serif:wght@400;700&display=swap', false, true);



	if (is_front_page() || is_home()) {  // ... только для главной страницы
		//wp_enqueue_style('home-style', get_stylesheet_directory_uri() . '/assets/css/home.css', array('style-css'), filemtime( __DIR__ . '/assets/css/home.css' ));
		//wp_enqueue_script( 'home-js', get_stylesheet_directory_uri() . '/assets/js/home.js', array('jquery'), filemtime( __DIR__ . '/assets/js/home.js' ) );
	} else {
		
	}
	
	/*
	# Добавить глобальные js-переменные в файл custom.js
	wp_register_script(
		'custom-js',
		get_stylesheet_directory_uri() . '/assets/js/custom.js',
		array( 'jquery' ), '1.0.1', true
	);
	wp_localize_script(
		'custom-js',
		'globalObject',
		array(
			'homeUrl' => esc_url( home_url() ),
			'stylesheetDirectoryUri' => esc_url( get_stylesheet_directory_uri() )
		)
	);
	wp_enqueue_script( 'custom-js' );
	*/
	
}

if (!is_admin()) {
	add_filter('wp_enqueue_scripts', 'fromfoto_enqueue_head_scripts', 1);
}

# Восстановить пункт "Меню" в админке (почему-то пропал)
add_theme_support('menus');

# Создать массив $_H для вывода HTML-кода
$_H = [];

# Подключить конфигурацию приложения
# Подключить класс "Дизайны"
# Подключить функции пространства имен "design"
include_once ABSPATH . 'app/app-config.php';
include_once PATH_APP . '/lib/designs/Designs.php';
include_once __DIR__ . '/functions-design.php';

# Скрыть поле родительской категории в админке
add_action( 'admin_head-term.php', 'wpse_58799_hide_parent_category' );
//add_action( 'admin_head-edit-tags.php', 'wpse_58799_remove_parent_category' );

function wpse_58799_hide_parent_category()
{
    if ( 'category' == $_GET['taxonomy'] )
        return;

    $parent = 'parent()';

    //if ( isset( $_GET['action'] ) )
    if ( isset($_GET['tag_ID']) )
        $parent = 'parent().parent()';



    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($)
        {
            $('label[for=parent]').<?php echo $parent; ?>.remove();
        });
    </script>
    <?php
}




# Вывод комментариев
function mytheme_comment( $comment, $args, $depth ) {
	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}
	?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-item-outer">
	<?php endif; ?>
	<div class="comment-item clearfix">
		<?php if ( $args['avatar_size'] != 0 ) { ?>
			<div class="comment-author-img"><?php echo get_avatar( $comment, $args['avatar_size'] ); ?></div>
		<?php } ?>
		<div class="comment-content clearfix">
			<div class="comment-info">
				<div class="comment-info-inner">
					<div class="table-cell-middle">

						<div class="comment-date brnhmbx-font-3 fs12"><a
									href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"><?php
								/* translators: 1: date, 2: time */
								printf( __( '%1$s at %2$s' ), get_comment_date(), get_comment_time() ); ?></a></div>
						<div class="comment-author-name brnhmbx-font-1 fw700 fs16"><?php echo get_comment_author_link() ?></div>
						
						<?php if ( $comment->comment_approved == '0' ) : ?>
							<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
							<br/>
						<?php endif; ?>

						<div class="comment-meta commentmetadata"><?php edit_comment_link( __( '(Edit)' ), '  ', '' ); ?></div>

					</div>
				</div>
			</div>

			<div class="comment-reply-edit clearfix">

				<div class="btnReply fs16 brnhmbx-font-1 fw700">
					<?php comment_reply_link( array_merge( $args, array(
						'add_below' => $add_below,
						'depth'     => $depth,
						'max_depth' => $args['max_depth']
					) ) ); ?>
				</div>

			</div>

		</div>
	</div>

	<div class="comment-text clearfix comment-text-w-a">
		<div class="brnhmbx-font-2 fs14">
			<?php comment_text(); ?>
		</div>
	</div>
	
	
	<?php if ( 'div' != $args['style'] ) : ?>
		</div>
	<?php endif; ?>
	<?php
}


?>



