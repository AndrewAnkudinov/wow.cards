let select = 0;
let max_pag = document.getElementsByClassName('pagination__item').length;

$('.carousel-control-next').click(function() {
    if(select == (max_pag - 1)) {
        $('.pagination__item-' + select).removeClass('active');
        select = -1;
    }
    console.log(max_pag);
    console.log(select);
    $('.pagination__item-' + select).removeClass('active');
    $('.pagination__item-' + (select + 1)).addClass('active');
    ++select;
    
});

$('.carousel-control-prev').click(function() {
    if( select == 0) {
        $('.pagination__item-' + select).removeClass('active');
        select = 5;
        $('.pagination__item-' + (select)).addClass('active');
    }
    $('.pagination__item-' + select).removeClass('active');
    $('.pagination__item-' + (select - 1)).addClass('active');
    --select;
});