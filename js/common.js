jQuery(document).ready(function($) {

    setTimeout(function() {
      $("body").addClass("loaded");
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

});
