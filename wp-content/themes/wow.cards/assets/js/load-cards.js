
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
                data: { action: "cards", loaded: load },
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