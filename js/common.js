jQuery(document).ready(function($) {

    setTimeout(function() {
      $("html").addClass("loaded");
      setTimeout(function() {
          $('.wig-loader').remove();
      }, 1000);

    }, 500);

    $('body').on('click', '.toggle-header', function(){
        $('.site-header').toggleClass('open closed');
        $('body').toggleClass('menu-open menu-closed');
        if(!$('body').hasClass('menu-open')) {
            $('.title-area, .nav-primary').hide();
        } else {
            setTimeout(function(){
                $('.title-area, .nav-primary').fadeIn();
            }, 300);
        }

    });

    $('body').on('click', '.menu-item-has-children > a', function(){
        $(this).parents('.menu-item-has-children').toggleClass('open').children('.sub-menu').slideToggle();

    });

    var mySwiper = new Swiper('.swiper-container', {
        speed: 400,
        spaceBetween: 100,
        autoHeight: false,
        slidesPerView: 1,
        autoplay: {
            delay: 20000,
        },
        pagination: {
           el: '.swiper-pagination',
           type: 'bullets',
           clickable: true,
        },
    });

    setTimeout(function() {

        var scrollbar = document.querySelector('.scrollbar');
        SimpleScrollbar.initEl(scrollbar);

    }, 1000);

});
