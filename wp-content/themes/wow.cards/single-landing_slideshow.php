<?php

/*
Template Name: Посадочная страница
*/

# ПОЛУЧИТЬ ТЕКУЩУЮ КАТЕГОРИЮ ДИЗАЙНОВ

# Определить тип дизайнов (slideshow||postcard)
$name_type_post = get_post_type();
$name_type_design = substr($name_type_post, strlen('landing_'));
/*
$name_type_design = 'slideshow';
if (isset($_GET['type']))
$name_type_design = $_GET['type'];
*/
$name_type_design = Designs::verify_type($name_type_design);
$type_design = Designs::get_type_design($name_type_design);

# Определить категорию дизайнов
$terms = get_the_terms(get_the_ID(), 'category_design');
//$terms = get_the_terms(get_the_ID(), 'category_' . $name_type_design);
$id_term = $terms[0]->term_id;


//$id_term = $wp_query->get_queried_object_id();

# /ПОЛУЧИТЬ ТЕКУЩУЮ КАТЕГОРИЮ ДИЗАЙНОВ


# ПОДГОТОВИТЬ HTML-КОД

$_H['h1'] = get_the_title();
//$_H['h1'] = single_term_title('', false);
$meta_h1 = get_term_meta($id_term, 'h1', true);
if ($meta_h1 != '')
	$_H['h1'] = $meta_h1;

$_H['description'] = get_the_content();
//$_H['description'] = term_description();


# ПОЛУЧИТЬ СПИСОК ДИЗАЙНОВ НУЖНОЙ КАТЕГОРИИ
/*
# Вывести шаблон "Выбрать тему открыток"
if ($name_type_design == 'postcard') {
	
	get_template_part( '/template-parts/part-choose-theme-postcard', 'choose-theme-postcard', ['id_term' => $id_term] );
	//include_once __DIR__ . '/template-parts/part-choose-theme-postcard.php';
	get_footer();
	exit;
}
*/
# Получить список дизайнов нужной категории
$designs = design\get_designs($name_type_design, $id_term, false);

# Собрать URL первого дизайна
$slugs_create_order_lang = [
	'ru_RU' => 'create-slideshow-ru',
	'en_US' => 'create-slideshow'
];
$url_first_design = home_url() . '/' . $slugs_create_order_lang[ get_locale() ] . '/?theme=' . $designs[ array_key_first($designs) ]['ID'];

# /ПОЛУЧИТЬ СПИСОК ДИЗАЙНОВ НУЖНОЙ КАТЕГОРИИ


// Заменить шорт-код
$shortcode_design_list = '{DESIGN_LIST}';
if ($_H['description'] == '')  // Вставить шорт-код на пустую страницу автоматически
	$_H['description'] = $shortcode_design_list;
$pos_shortcode_desc = strpos($_H['description'], $shortcode_design_list);
if ($pos_shortcode_desc !== false) {
	if ($name_type_design == 'postcard')
		$_H['description'] = str_replace($shortcode_design_list, design\list_postcards($id_term, 12), $_H['description']);
    else
		$_H['description'] = str_replace($shortcode_design_list, design\slider_designs($designs, 12), $_H['description']);
}


//wp_enqueue_style('comments-css', get_stylesheet_directory_uri() . '/assets/css/comments.css', array(), filemtime( __DIR__ . '/assets/css/comments.css' ), false);
wp_enqueue_style('landing-css', get_stylesheet_directory_uri() . '/assets/css/landing.css', array(), filemtime( __DIR__ . '/assets/css/landing.css' ), false);
if($name_type_design == 'slideshow') {
    wp_enqueue_style('single-video', get_stylesheet_directory_uri() . '/assets/css/single-video.css', array(), filemtime( __DIR__ . '/assets/css/single-video.css' ), false);
    wp_enqueue_script('slider-pagination', get_stylesheet_directory_uri() . '/assets/js/pagination.js', array(), filemtime( __DIR__ . '/assets/js/pagination.js' ), array('jquery'));
}
# HTML

get_header();
/*
# Вывести посадочную страницу открыток
if ($name_type_design == 'postcard') {
	get_template_part('/template-parts/part-choose-theme-postcard', 'choose-theme-postcard', ['id_term' => $id_term]); 	# Вывести шаблон "Выбрать тему открыток"
	get_footer();
}
*/

?>
<!--    <link rel='stylesheet' id='brnhmbx-fonts-css'  href='https://fonts.googleapis.com/css?family=Alice%3A400%2C400italic%2C700%2C700italic%7CComfortaa%3A300%2C400%2C700&#038;subset=latin%2Clatin-ext' type='text/css' media='all' />-->
	<!--<link rel='stylesheet' href='<?php echo get_stylesheet_directory_uri() ?>/assets/css/post_robin_responsive.css?ver=4.7.6' type='text/css' media='all' />-->
<!--	<link rel='stylesheet' id='font-awesome-css'  href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' type='text/css' media='all' />-->

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;900&display=swap" rel="stylesheet">
	<div class="col-12 text-left">

		<div class="container-content">

            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">

                    <article class="landing">



<!--                        <div class="container-h1">-->
<!--                            <h1>--><?php //echo $_H['h1'] ?><!--</h1>-->
<!--                        </div>-->



                        <?php
                            echo $_H['description']
                        ?>

                        <?php
                        if ($name_type_design == 'slideshow') {
                            $second_info_block = get_field('second_info_block');
                            $first_create_step = get_field('first_create_step');
                            $second_create_step = get_field('second_create_step');
                            $third_create_step = get_field('third_create_step');

                            $first_download_step = get_field('first_download_step');
                            $second_download_step = get_field('second_download_step');
                            $third_download_step = get_field('third_download_step');
                            $fourth_download_step = get_field('fourth_download_step');

                        echo '
                        <div class="welcome">
                            <div class="welcome__info">
                                <div class="welcome__info-title"><h2>Видеопоздравление ' . get_the_title() . '</h2></div>
                                <div class="welcome__info-description">'; ?> <?php echo the_field('main_description') ?> <? echo '</div>
                                <img class="welcome__unicorn" src="' . get_stylesheet_directory_uri() . '/assets/img/end.png" alt="">
                            </div>
                            <div class="welcome__info">
                                <div class="welcome__info-title">'; ?> <?php echo $second_info_block['info_block_title']; ?> <? echo '</div>
                                <div class="welcome__info-description">'; ?> <?php echo $second_info_block['info_block_description']; ?> <? echo '</div>
                            </div>

                        </div>
                        <div class="steps">
                            <div class="container-fluid">
                                <div class="steps__step">
                                    <div class="steps__step-text">'; ?> <?php echo $first_create_step['step_text']; ?> <? echo '</div>
                                    <div class="steps__step-info">
                                        <div class="steps__step-img"><img src="'; ?> <?php echo $first_create_step['step_img']; ?> <? echo '" alt=""></div>
                                        <div class="steps__step-background"><img src="' . get_stylesheet_directory_uri() . '/assets/img/back_step.png" alt=""></div>
                                        <div class="steps__step-clue">'; ?> <?php echo $first_create_step['step_active']; ?> <? echo '</div>
                                        <div class="steps__step-number"><p>'; ?> <?php echo $first_create_step['step_number']; ?> <? echo '</p></div>
                                    </div>
                                </div>
                                <div class="steps__step">
                                    <div class="steps__step-text">'; ?> <?php echo $second_create_step['step_text']; ?> <? echo '</div>
                                    <div class="steps__step-info">
                                        <div class="steps__step-img"><img src="'; ?> <?php echo $second_create_step['step_img']; ?> <? echo '" alt=""></div>
                                        <div class="steps__step-background"><img src="' . get_stylesheet_directory_uri() . '/assets/img/back_step.png" alt=""></div>
                                        <div class="steps__step-clue">'; ?> <?php echo $second_create_step['step_active']; ?> <? echo '</div>
                                        <div class="steps__step-number"><p>'; ?> <?php echo $second_create_step['step_number']; ?> <? echo '</p></div>
                                    </div>
                                </div>
                                <div class="steps__step">
                                    <div class="steps__step-text">'; ?> <?php echo $third_create_step['step_text']; ?> <? echo '</div>
                                    <div class="steps__step-info">
                                        <div class="steps__step-img"><img src="'; ?> <?php echo $third_create_step['step_img']; ?> <? echo '" alt=""></div>
                                        <div class="steps__step-background"><img src="' . get_stylesheet_directory_uri() . '/assets/img/back_step.png" alt=""></div>
                                        <div class="steps__step-clue">'; ?> <?php echo $third_create_step['step_active']; ?> <? echo '</div>
                                        <div class="steps__step-number"><p>'; ?> <?php echo $third_create_step['step_number']; ?> <? echo '</p></div>
                                    </div>
                                </div>
                                
                                <div class="steps__clue">'; ?> <?php echo the_field('step_after'); ?> <? echo '</div>
                            </div>
                        </div>
                        <div class="welcome">
                            <div class="welcome__info">
                                <div class="welcome__info-title">'; ?> <?php echo the_field('step_download_title'); ?> <? echo '</div>
                            </div>
                        </div>
                        <div class="steps">
                            <div class="container-fluid">
                                <div class="steps__step">
                                    <div class="steps__step-text">'; ?> <?php echo $first_download_step['step_text']; ?> <? echo '</div>
                                    <div class="steps__step-info">
                                        <div class="steps__step-img"><img src="'; ?> <?php echo $first_download_step['step_img']; ?> <? echo '" alt=""></div>
                                        <div class="steps__step-background"><img src="' . get_stylesheet_directory_uri() . '/assets/img/back_step.png" alt=""></div>
                                        <div class="steps__step-clue">'; ?> <?php echo $first_download_step['step_active']; ?> <? echo '</div>
                                        <div class="steps__step-number"><p>'; ?> <?php echo $first_download_step['step_number']; ?> <? echo '</p></div>
                                    </div>
                                </div>
                                <div class="steps__step">
                                    <div class="steps__step-text">'; ?> <?php echo $second_download_step['step_text']; ?> <? echo '</div>
                                    <div class="steps__step-info">
                                        <div class="steps__step-img"><img src="'; ?> <?php echo $second_download_step['step_img']; ?> <? echo '" alt=""></div>
                                        <div class="steps__step-background"><img src="' . get_stylesheet_directory_uri() . '/assets/img/back_step.png" alt=""></div>
                                        <div class="steps__step-clue">'; ?> <?php echo $second_download_step['step_active']; ?> <? echo '</div>
                                        <div class="steps__step-number"><p>'; ?> <?php echo $second_download_step['step_number']; ?> <? echo '</p></div>
                                    </div>
                                </div>
                                <div class="steps__step">
                                    <div class="steps__step-text">'; ?> <?php echo $third_download_step['step_text']; ?> <? echo '</div>
                                    <div class="steps__step-info">
                                        <div class="steps__step-img"><img src="'; ?> <?php echo $third_download_step['step_img']; ?> <? echo '" alt=""></div>
                                        <div class="steps__step-background"><img src="' . get_stylesheet_directory_uri() . '/assets/img/back_step.png" alt=""></div>
                                        <div class="steps__step-clue">'; ?> <?php echo $third_download_step['step_active']; ?> <? echo '</div>
                                        <div class="steps__step-number"><p>'; ?> <?php echo $third_download_step['step_number']; ?> <? echo '</p></div>
                                    </div>
                                </div>
                                <div class="steps__step">
                                    <div class="steps__step-text">'; ?> <?php echo $fourth_download_step['step_text']; ?> <? echo '</div>
                                    <div class="steps__step-info">
                                        <div class="steps__step-img"><img src="'; ?> <?php echo $fourth_download_step['step_img']; ?> <? echo '" alt=""></div>
                                        <div class="steps__step-background"><img src="' . get_stylesheet_directory_uri() . '/assets/img/back_step.png" alt="" ></div>
                                        <div class="steps__step-clue">'; ?> <?php echo $fourth_download_step['step_active']; ?> <? echo '</div>
                                        <div class="steps__step-number"><p>'; ?> <?php echo $fourth_download_step['step_number']; ?> <? echo '</p></div>
                                    </div>
                                </div>
                                <div class="steps__clue">'; ?> <?php echo the_field('download_after'); ?> <? echo '</div>
                            </div>
                        </div>
                        <img class="video_end" src="' . get_stylesheet_directory_uri() . '/assets/img/unicorn3.svg" alt="">
                        ';
                        }
                        ?>

                    </article>

                    <br>
                    <br>
<!--                    <div class="container-h1 text-left">-->
<!--                        <h1>Автор</h1>-->
<!---->
<!--                        <div class="d-flex">-->
<!--                            <div class="mr-3">-->
<!--                                <img src="--><?php //echo get_stylesheet_directory_uri() ?><!--/assets/img/autor.jpg" class="rounded-circle border" alt=""></div>-->
<!--                            <div class="align-self-center text-danger"><big>Поспелова Анастасия</big></div>-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    <div id="lastArticles" style="margin-left: -60px; border-left: 1px solid #4F0074; padding-left: 60px;">-->
<!--                        <div class="container-h1 text-center">-->
<!--                            <div class="h1">Последние статьи</div>-->
<!--							<div class="robin-sep"></div>-->
<!--                        </div>-->
<!--						-->
<!--                        --><?php //foreach([1, 2] as $v) { ?>
<!--                            <article>-->
<!--                                <div class="row">-->
<!--                                    <div class="col-5 col-sm-12 col-md-5">-->
<!--                                        <img class="rounded shadow w-100" src="--><?php //echo get_stylesheet_directory_uri() ?><!--/assets/img/3.png" alt="">-->
<!--                                    </div>-->
<!--                                    <div class="col-7 col-sm-12 col-md-7">-->
<!--                                        <h4><strong>Название статьи</strong></h4>-->
<!--                                        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod...</div>-->
<!--                                        <div class="mt-3">-->
<!--                                            <a href="#" class="text-danger">Перейти</a>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </article>-->
<!--                        --><?php //} ?>
<!---->
<!--                    </div>-->
                    
                    <div class="author">
                        <div class="author__container _container container-fluid">
                            <div class="author__info">
                                <div class="author__info-img"><img loading="lazy" width="190px" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/author.png" alt=""></div>
                                <div class="author__info-name">
                                    <h2>Автор</h2>
                                    <p>Имя автора</p>
                                </div>
                            </div>
                            <div class="authon__posts">
                                <h2 class="author__posts-title">Последние статьи</h2>
                                <div class="posts">
                                    <div class="posts__post">
                                        <div class="posts__post-img"><img loading="lazy" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/post.png" alt=""></div>
                                        <div class="posts__post-info">
                                            <div class="posts__post-info_title">Название статьи</div>
                                            <div class="posts__post-info_text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </div>
                                            <a href="#" class="posts__post-info_button">Перейти <i class="fas fa-arrow-right"></i></a>
                                        </div>

                                    </div>
                                    <div class="posts__post">
                                        <div class="posts__post-img"><img loading="lazy" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/post.png" alt=""></div>
                                        <div class="posts__post-info">
                                            <div class="posts__post-info_title">Название статьи</div>
                                            <div class="posts__post-info_text">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </div>
                                            <a href="#" class="posts__post-info_button">Перейти <i class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
					<!--noindex-->
					<div class="text-center">
						<div class="widget-item-inner">
							<style type="text/css">
								
								.robin-sep {
									width: 60px;
									height: 6px;
									background-color: #ff6f60;
									border-radius: 6px;
									margin: 16px auto 20px;
								}
								.social-widget-outer {
									display: inline-block;
								}
								.widget-item-inner ul {
									padding-left: 0;
									margin: 0;
								}
								.social-widget li {
									float: left;
									margin-right: 20px;
									margin-bottom: 20px;
									list-style: none;
								}
								.social-widget li a {
									display: block;
									text-align: center;
									line-height: 48px;
									border-radius: 50%;
									height: 44px;
									width: 44px;
								}
								.social-widget li i {
									font-size: 20px;
								}
								a.sw-4283036.social-widget-button,
								a.sw-4283036.social-widget-button:visited { background-color: #ff6f60; color: #FFF; -webkit-transition: all 0.3s ease-out; transition: all 0.3s ease-out; }
								a.sw-4283036.social-widget-button:hover { background-color: #FFF; color: #ff6f60; }

							</style>

<!--							<div class="side_header brnhmbx-font-1">-->
<!--								<span class="robin-icon-asterisk mr10">*</span>-->
<!--								<span class="h1">--><?php //_e('Social', 'fromfoto'); ?><!--</span>-->
<!--								<span class="robin-icon-asterisk ml10">*</span>-->
<!--							</div>-->
<!--							<div class="robin-sep"></div>-->
<!--							<div class="t-a-c">-->
<!--								<div class="social-widget-outer">-->
<!--									<ul class="social-widget clearfix">-->
<!--										<li><a class="sw-4283036 social-widget-button clearfix" rel="nofollow" href="https://www.facebook.com/PhotoVideosSlideshow/" target="_blank"><i class="fa fa-facebook"></i></a></li>-->
<!--										<li><a class="sw-4283036 social-widget-button clearfix" rel="nofollow" href="https://twitter.com/slideshow_photo" target="_blank"><i class="fa fa-twitter"></i></a></li>-->
<!--										<li><a class="sw-4283036 social-widget-button clearfix" rel="nofollow" href="https://www.instagram.com/slideshow.photos/" target="_blank"><i class="fa fa-instagram"></i></a></li>-->
<!--										--><?php ///* <li><a class="sw-4283036 social-widget-button clearfix" rel="nofollow" href="#" target="_blank"><i class="fa fa-pinterest-p"></i></a></li> */ ?>
<!--										<li><a class="sw-4283036 social-widget-button clearfix" rel="nofollow" href="https://plus.google.com/111850356154127930897" target="_blank"><i class="fa fa-google-plus"></i></a></li>-->
<!--									</ul>-->
<!--								</div>-->
<!--							</div>-->
                            <div class="footer">
                                <div class="footer__container _container">
                                    <div class="footer__body">
                                        <div class="footer__img"><img loading="lazy" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/un_fotter.png" alt=""></div>
                                        <div class="footer__links">
                                            <h4>давай дружить</h4>
                                            <a href="#"><img loading="lazy" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/insta.png" alt=""></a>
                                            <a href="#"><img loading="lazy" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/vk.png" alt=""></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</div>
					</div>
					<!--/noindex-->
					
					<?php if (1 == 2) { ?>
					<!-- Comments -->
					
					<br />
					<br />
                    <?php comments_template(); /* '/template-parts/comments.php' */ ?>


                    <div id="comments" class="container-h1 text-left">

                        <div class="h1">Комментарии</div>

                        <div class="rounded border">
                            <div class="d-flex mb-2">
                                <div class="mr-3">
                                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/user.png" class="rounded-circle" alt=""></div>
                                <div class="align-self-center"><strong>Поспелова Анастасия</strong></div>
                            </div>
                            <div>
                                Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
                        </div>
                        <div class="rounded border">
                            <div class="d-flex mb-2">
                                <div class="mr-3">
                                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/user.png" class="rounded-circle" alt=""></div>
                                <div class="align-self-center"><strong>Поспелова Анастасия</strong></div>
                            </div>
                            <div>
                                Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
                        </div>

						<div class="h1">Написать комментарий</div>

                        <div class="rounded border">
                            <div class="d-flex mb-2">
                                <div class="mr-3">
                                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/user.png" class="rounded-circle" alt=""></div>
                                <div class="align-self-center"><strong>Поспелова Анастасия</strong></div>
                            </div>
                            <textarea style="width: 100%" rows="5">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</textarea>
                        </div>

                        <button class="btn btn-outline-dark">Оставить комментарий</button>

                    </div>
                    <!-- Comments -->
					<?php } ?>
                </main><!-- #main -->
            </div>


		</div>

	</div>
	<div class="col-12" style="/*padding-left: 120px; margin-top: 120px;*/">


	</div>
    <script>

        let html,
            old_html,
            load = 16,
            welcome_title = $('.welcome-screen__info-title').text(),
            welcome_description = $('.welcome-screen__info-description').text(),
            cards_title = $('.best__subtitle').html(),
            howCreate_title = $('.how-to__block-title').first().html(),
            howCreate_description = $('.how-to__block-description').first().html(),
            howDownload_title = $('.how-to__block-title').last().html(),
            howDownload_description = $('.how-to__block-description').last().html();


        function load_cards() {
            (load += 16),
                $.ajax({
                    url: "https://wow.cards/wp-admin/admin-ajax.php",
                    method: "GET",
                    dataType: "json",
                    data: { action: "cards", loaded: load, id_term: "<?php echo $id_term ?>" },
                    success: function (a) {
                        "OK" == a.status && ($(".landing").html(a.html), console.log(a.count), "all" == a.count && $(".more-button").css({ display: "none" }));
                        $('.welcome-screen__info-title').text(welcome_title);
                        $('.welcome-screen__info-description').text(welcome_description);
                        $('.best__subtitle').html(cards_title);
                        $('.how-to__block-title').first().html(howCreate_title);
                        $('.how-to__block-description').first().html(howCreate_description);
                        $('.how-to__block-title').last().html(howDownload_title);
                        $('.how-to__block-description').last().html(howDownload_description);
                    },
                });
        }

        let target_mouse = $('.figure_video');
        setInterval(function () {
            if (window.matchMedia("(max-width: 1280px)").matches) {
                if ( '<?php echo $name_type_design ?>' == 'slideshow') {
                    $('.carousel-control-next').attr('style' , 'opacity: 0!important');
                    $('.carousel-control-prev').attr('style' , 'opacity: 0!important');
                }
            }
        }, 5000);
        if (window.matchMedia("(max-width: 1280px)").matches) {
            target_mouse.mousemove(function () {
                if ('<?php echo $name_type_design ?>' == 'slideshow') {
                    $('.carousel-control-next').attr('style', 'opacity: 0.6!important');
                    $('.carousel-control-prev').attr('style', 'opacity: 0.6!important');
                }
            });
        };
        $('#carouselChooseSlideshow').on('slide.bs.carousel', () => {
            $('.carousel-item.active').find('video')[0].pause();
        });
        $('#carouselChooseSlideshow').on('slid.bs.carousel', () => {
            $('.carousel-item.active').find('video')[0].play();
        });


    </script>





<?php get_footer(); ?>