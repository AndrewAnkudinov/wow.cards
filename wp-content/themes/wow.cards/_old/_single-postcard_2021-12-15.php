<?php

/*
Template Name: Создать открытку
*/

$card = Designs::get_postcard($_GET['theme']);
//var_dump($card);

define('IS_GIF', false);
$tag_postcard = 'canvas';
$js_postcard = 'cards.js';
if (IS_GIF) {
    $tag_postcard =  'img';
    $js_postcard = 'gif.js';
}
define('TAG_POSTCARD', $tag_postcard);

# Подключить скрипты
wp_enqueue_style( 'constructor-cards-css', get_stylesheet_directory_uri() . '/assets/css/constructor-cards.css',
    array('style-css'), filemtime( __DIR__ . '/assets/css/constructor-cards.css' ) );

wp_enqueue_script('cards-js', get_stylesheet_directory_uri() . '/assets/js/' . $js_postcard,
    array(), filemtime( __DIR__ . '/assets/js/' . $js_postcard ), false);


# Удаленные из шаблона свойства открытки:
# - title


# HTML
get_header('choose-theme');

?>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Raleway:400,600,700|Lato:400,700">

<div class="col-md-6 content">
    <div class="constructor-block">
        <div class="constructor" data-card='<?php echo(json_encode($card)) ?>'>
            <div class="idle">
                <div class="lds-css ng-scope">
                    <div class="lds-double-ring"><div></div><div></div></div>
                </div>
            </div>

            <div class="photo-slot">
                <div class="photo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 27.974"><g fill="#DCDCDD"><path d="M13.872 8.692c-1.882 0-3.402 1.512-3.402 3.385s1.52 3.385 3.402 3.385c1.886 0 3.406-1.512 3.406-3.385s-1.521-3.385-3.406-3.385z"></path><path d="M20.286 22.315v-4.317h4.683V13.342h2.674V6.871c0-1.889-1.555-3.435-3.456-3.435H22.46C22.46 1.529 20.904 0 19.005 0H8.638a3.434 3.434 0 0 0-3.455 3.436H3.454C1.555 3.436 0 4.983 0 6.871v12.025c0 1.89 1.555 3.437 3.454 3.437h16.832v-.018zM6.911 12.129c0-3.831 3.11-6.923 6.961-6.923 3.853 0 6.965 3.092 6.965 6.923s-3.112 6.921-6.965 6.921c-3.851 0-6.961-3.09-6.961-6.921z"></path><path d="M30.317 19.967v-4.656h-3.368V19.967h-4.683v3.351H26.949v4.656h3.368v-4.656H35v-3.351h-4.683z"></path></g></svg>
                    Добавить фото
                </div>
                <canvas class="user-image hide"></canvas>
            </div>

            <?php if (IS_GIF) { ?>
                <img class="front">
            <?php } else { ?>
                <canvas class="front"></canvas>
            <?php } ?>

            <div class="file-selector"><input type="file" accept="image/*" style="display: none;"></div>
            <div class="delete hide">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15.01 19"><path d="M11.996 12.5v3.723a1.48 1.48 0 0 1-.012.24c-.061.363-.284.592-.531.553-.246-.039-.433-.343-.434-.718-.002-1.034 0-2.066 0-3.1V8.85c-.002-.081 0-.161.005-.242.031-.366.249-.636.501-.625.242.01.452.295.469.65.009.187.003.377.003.564l-.001 3.303zm-3.985.04v3.689c.002.074-.002.147-.01.219-.056.356-.286.598-.536.568-.245-.03-.451-.324-.455-.683-.007-.555-.002-1.109-.002-1.664V8.854c-.003-.087 0-.173.009-.26.052-.358.277-.601.53-.579.252.024.46.328.463.695.005.668.003 1.337.003 2.007l-.002 1.823zm-4.009-1.12v4.773c.002.093-.003.186-.016.278-.062.354-.28.578-.52.546-.24-.032-.434-.329-.44-.686-.006-.321-.003-.642-.003-.963v-2.847-3.77c0-.441.236-.762.527-.734.254.026.448.335.45.736.003.889.002 1.777.002 2.667zM13.979 6H1.035c-.005.11-.015.221-.015.323v3.142c0 2.283-.005 4.567.002 6.849a2.658 2.658 0 0 0 1.999 2.589c.142.038.286.066.428.097h8.12a.845.845 0 0 1 .121-.037 2.658 2.658 0 0 0 2.307-2.629c.006-3.346.006-6.696 0-10.048.001-.09-.011-.176-.018-.286zM15.009 4c.003-.756-.304-.999-1-1h-3c-.063.213 0-2 0-2-.001-.551-.599-1-1.108-1H5.117c-.509 0-1.108.449-1.108 1 0 0 .062 2.211 0 2h-3c-.521 0-.913.458-1 1-.021.133 0-.135 0 0 0 .757.407.993 1.105.993h12.874c.524 0 .92-.37.996-.927.013-.123.03.057.025-.066z"></path></svg>
            </div>

            <?php if (IS_GIF) { ?>
                <img class="decor hide">
            <?php } else { ?>
                <canvas class="decor hide"></canvas>
            <?php } ?>

            <div class="text-slot">
                <div class="text-area">
                    <textarea class="text-content"></textarea>
                </div>
            </div>
            <div class="one-line">Поздравляю</div>
            <textarea class="multi-line"></textarea>
            <!---- Второй текст -->
            <div class="two text-slot hide">
                <div class="two text-area">
                    <textarea class="two text-content"></textarea>
                </div>
            </div>
            <div class="two one-line">Поздравляю</div>
            <textarea class="two multi-line"></textarea>
        </div>
        <div class="zoom-box hide">
            <div class="zoom-tools">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.012 20" id="zoom_out"><path fill="#7F7F7F" d="M0 3.998v12.004h16.012V3.998H0zm14.701 10.75h-13.4V5.327h13.4v9.421zm-.71-1.309c-.217-3.073-2.108-3.332-2.739-3.332-.867-.099-1.931.555-2.404 1.427-1.005 1.706-1.695 1.191-1.931 1.191-1.301-.635-1.616-.694-2.443-.694-1.634-.018-2.301 1.535-2.436 1.903h11.953v-.495zm-8.729-2.856c1.11 0 2.01-.906 2.01-2.023s-.9-2.023-2.01-2.023c-1.11 0-2.01.906-2.01 2.023s.9 2.023 2.01 2.023z"></path></svg>
                <div class="slider">
                    <div class="slider-corner">
                        <div class="slider-tooltip">100%</div>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 27 20" id="zoom_in"><path fill="#7F7F7F" d="M0 0v20h27V0H0zm24.789 17.911H2.194V2.215h22.595v15.696zm-1.196-2.182c-.365-5.12-3.555-5.551-4.619-5.551-1.463-.165-3.256.925-4.054 2.378-1.694 2.842-2.858 1.984-3.256 1.984-2.193-1.058-2.725-1.157-4.119-1.157-2.754-.03-3.88 2.557-4.108 3.171h20.156v-.825zM8.874 10.971c1.871 0 3.389-1.51 3.389-3.37a3.38 3.38 0 0 0-3.389-3.37c-1.872 0-3.39 1.509-3.39 3.37a3.38 3.38 0 0 0 3.39 3.37z"></path></svg>
            </div>
        </div>
        <button class="download">Скачать открытку</button>
    </div>

    <?php if (IS_GIF) { ?>
        <img id="out" class="hide">
    <?php } else { ?>
        <canvas id="out" class="hide"></canvas>
    <?php } ?>

</div>

    <div class="col-md-6 d-flex justify-content-center align-items-center flex-column constructor-desc">
        <div style="position:relative; display: inline-block;">
            Нажмите на текст, чтобы <span class="text-danger">заменить</span>
            <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/arrow_constructor.svg"
                 style="position: absolute; right: 110%; max-width: calc((100vw / 2 - 250px) / 2); z-index: 100;" alt="">
        </div>
        <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/unicorns/unicorn_constructor.svg" style="max-width:100%;" alt="">
    </div>

<script>
    jQuery('.constructor-desc div, .constructor-desc img')
        .hover(function(){
            $('.constructor-block textarea').css(
                {
                    'transition': '.5s',
                    'transform': 'scale(1.3)'
                });
        }, function () {
            $('.constructor-block textarea').css('transform', 'scale(1)');
        });
</script>
<?php get_footer(); ?>