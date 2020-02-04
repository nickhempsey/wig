//var $ = $.noConflict(true);

AOS.init();

var input, output;
jQuery(document).ready(function($) {

    setTimeout(function() {
      $("body").addClass("loaded");
    }, 500);

    function headerScrolled() {
        if ($(window).scrollTop() >= 330) {
          $('.site-header').addClass('scrolled');
          $('body').addClass('scrolled');
         }
         else {
          $('.site-header').removeClass('scrolled');
          $('body').removeClass('scrolled');
         }
    }

    headerScrolled();

    $(window).scroll(function(){
      headerScrolled();
    });

    $('#reviewModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var author = button.data('author');
      var review = button.data('review');
      var biline = button.data('biline');
      var modal = $(this);
      modal.find('.review-author').text(author);
      modal.find('.review-entry').html(review);
      modal.find('.review-biline').html(biline);
    });

    $('body').on('click', '.toggleMenu', function(){
        $('.main-menu').toggleClass('open');
        $('body').toggleClass('menu-open');
        $('.intercom-launcher-frame').toggleClass('d-none');
        setTimeout(function(){
            $('.main-menu').toggleClass('split-line-loaded');
        },500);
    });

    $('body').on('click', '.toggleSearch', function(){
        $('.search-header').toggleClass('open');
        $('body').toggleClass('search-open');
        setTimeout(function(){
            $('.search-header').toggleClass('split-line-loaded');
        },500);
    });


    $('body').on('click', '[data-menu-page]', function(){
        var pageId = $(this).attr('data-menu-page');
        $('.main-menu').addClass('open');
        $('body').addClass('menu-open');
        $('[data-page]').removeClass('d-block').addClass('d-none');
        $('[data-page="'+pageId+'"]').addClass('d-block');
        $('.intercom-launcher-frame').addClass('d-none');
        setTimeout(function(){
            $('.main-menu').addClass('split-line-loaded');
        },500);
    });


    var swiperCarousel = new Swiper ('.swiper-container.swiper-carousel', {
        loop: false,
        spaceBetween: 30,
        slidesPerView: 1,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
        breakpoints: {
            600: {
              slidesPerView: 1,
              spaceBetween: 20,
            },
            700: {
              slidesPerView: 2,
              spaceBetween: 30,
            },
            1023: {
              slidesPerView: 3,
              spaceBetween: 30,
            },
        }

    });

    var swiperCarouselSmall = new Swiper ('.swiper-container.swiper-carousel-small', {
        loop: false,
        spaceBetween: 50,
        slidesPerView: 3,
        autoplay: {
            delay: 3500,
          },
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
          dynamicBullets: true,
          dynamicMainBullets: 1,
        },
        breakpoints: {
            600: {
              slidesPerView: 3,
              spaceBetween: 20,
            },
            700: {
              slidesPerView: 4,
              spaceBetween: 30,
            },
            1023: {
              slidesPerView: 5,
              spaceBetween: 30,
            },
        }

    });

    var scrollbar = document.querySelector('.scrollbar');
    SimpleScrollbar.initEl(scrollbar);


    $('body').on('keyup', '#input_1_9', function() {
        var val = $(this).val();
        $('#input_1_6_3').val(val);
    });

    $('body').on('change', '#input_1_3', function() {
        var selected = $(this).children('option:selected').val();
        $('#input_1_20 option[value="'+selected+'"]').attr('selected','selected');
        console.log(selected);
    });

    $('body').on('change', '#input_1_62', function() {
        var selected = $(this).children('option:selected').val();
        $('#input_1_73 option[value="'+selected+'"]').attr('selected','selected');
        console.log(selected);
    });

    $('body').on('change', '#input_1_63', function() {
        var selected = $(this).children('option:selected').val();
        $('#input_1_74 option[value="'+selected+'"]').attr('selected','selected');
        console.log(selected);
    });

    $('body').on('change', '#input_1_64', function() {
        var selected = $(this).children('option:selected').val();
        $('#input_1_76 option[value="'+selected+'"]').attr('selected','selected');
        console.log(selected);
    });

    $(document).on('click', '#serviceList .menu__link', function(){
        var sl = $(this).data('sl');

        $('#serviceList .service-listings').addClass('not-selected').removeClass('selected');
        $('#serviceList .menu__link').removeClass('selected');

        $('#serviceList .service-listings.sl-'+sl+', #serviceList .menu__link.sl-'+sl).addClass('selected').removeClass('not-selected');
    });
});
