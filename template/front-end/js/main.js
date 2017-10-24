// checking if image is basic
function checkBase()
{
    var optsFlush;

    $.each(opts, function(index, value)
    {
        if(value == 1 && optsFlush != false)
            optsFlush = true;
        else
            optsFlush = false;
    });

    if(optsFlush == true)
        opts = new Object;
}

// getting options images
function getOptionsImages(options)
{
    $.get(base+"rest/", options, function(data)
    {
        $('.col-sm-6 .one-product.ajaxContainer').html(data);
        setTimeout(function(){$('div.loading-images').hide()}, 700);
    });
}

/* изменение изображения при наведении*/
$(document).ready(function()
{
    $(document).on('mouseenter mouseleave', 'img', function(e) {
      var temp = $(this).attr("src");
      $(this).attr("src", $(this).attr("data-alt-src"));
      $(this).attr("data-alt-src", temp);
    });

    /* слайдер превью товара */
    $('.preview-slider').carousel({
        interval: false
    });

    /* слайдер новые поступления */
    $('.new-items-carousel').slick({
      infinite: true,
      dots: false,
      prevArrow: '<button type="button" class="sl-prev"></button>',
      nextArrow: '<button type="button" class="sl-next"></button>',
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            variableWidth: true,
            centerMode: true,
            arrows: false
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });

    /* слайдер часто просматриваемые */
    $('.carousel-looked').slick({
      infinite: true,
      arrows: false,
      dots: false,
      prevArrow: '<button type="button" class="sl-prev"></button>',
      nextArrow: '<button type="button" class="sl-next"></button>',
      speed: 300,
      slidesToShow: 4,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            arrows: true
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            variableWidth: true,
            centerMode: true,
            arrows: false
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });

    $('#filterDress').collapse({
      toggle: false
    });
    $('#filterShoes').collapse({
      toggle: false
    });
    $('#filterBags').collapse({
      toggle: false
    });
    $('#filterAccessory').collapse({
      toggle: false
    });


    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    });


    // Slider Product
      $('.one-product__slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        asNavFor: '.one-product__slider_navigation',
        arrows: true,
        prevArrow: '<button type="button" class="sl-prev"></button>',
        nextArrow: '<button type="button" class="sl-next"></button>',
        dots: true,
        infinite: true,
        focusOnSelect: true,
        fade: true,
        cssEase: 'linear'
      });
      //  one-product-slider
      $('.one-product__slider_navigation').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: '<button type="button" class="sl-bottom"></button>',
        nextArrow: '<button type="button" class="sl-top"></button>',
        dots: false,
        infinite: true,
        asNavFor: '.one-product__slider',
        focusOnSelect: true,
        centerMode: false,
        vertical: true
      });


    /* слайдер Другие товары дизайнера */
    $('.carousel-additional-items').slick({
      infinite: true,
      arrows: false,
      dots: false,
      prevArrow: '<button type="button" class="sl-prev"></button>',
      nextArrow: '<button type="button" class="sl-next"></button>',
      speed: 300,
      slidesToShow: 5,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1199,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 1,
            infinite: true,
          }
        },
         {
          breakpoint: 992,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: false
          }
        },
         {
          breakpoint: 768,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            variableWidth: true,
            centerMode: true,
            arrows: false
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });


    /* слайдер Другие товары дизайнера */
    $('.carousel-you-like').slick({
      infinite: true,
      arrows: false,
      dots: false,
      prevArrow: '<button type="button" class="sl-prev"></button>',
      nextArrow: '<button type="button" class="sl-next"></button>',
      speed: 300,
      slidesToShow: 5,
      slidesToScroll: 1,
      responsive: [
         {
          breakpoint: 1199,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 1,
            infinite: true,
          }
        },
         {
          breakpoint: 992,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            dots: false
          }
        },
         {
          breakpoint: 768,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            variableWidth: true,
            centerMode: true,
            arrows: false
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });

    /* карусель на странице Lookbook */
    $('.lookbook-page-carousel').slick({
          infinite: true,
          dots: false,
          prevArrow: '<button type="button" class="sl-prev"></button>',
          nextArrow: '<button type="button" class="sl-next"></button>',
          speed: 300,
          slidesToShow: 3,
          slidesToScroll: 1,
          responsive: [
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
                dots: false
              }
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
                variableWidth: true,
                centerMode: true,
                arrows: false
              }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
          ]
    });

    /* слайдер страницы статьи */
    $('.article-slider').on('init', function(event, slick){
            $(this).append('<div class="slider-count"><p><span id="current">1</span> / <span id="total">'+slick.slideCount+'</span></p></div>');
        });

      $('.article-slider').slick({
      infinite: true,
      prevArrow: '<button type="button" class="article-prev"></button>',
      nextArrow: '<button type="button" class="article-next"></button>',
      speed: 300,
      slidesToShow: 1,
      slidesToScroll: 1,
    });
    $('.article-slider')
       .on('afterChange', function(event, slick, currentSlide, nextSlide){
      // finally let's do this after changing slides
        $('.slider-count #current').html(currentSlide+1);
    });

    /* sale-slider */
    $('.sale-slider').slick({
      infinite: true,
      arrows: true,
      dots: false,
      prevArrow: '<button type="button" class="sale-prev"></button>',
      nextArrow: '<button type="button" class="sale-next"></button>',
      speed: 300,
      slidesToShow: 3,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        }
        // You can unslick at a given breakpoint now by adding:
        // settings: "unslick"
        // instead of a settings object
      ]
    });

    // delete group dreambox
    $('a.dreambox-del').on('click', function(e)
    {
        var selected = [];

        $("input:checkbox.square-check:checked").each(function() {
            selected.push($(this).val());
        });

        $.ajax({
            type: 'POST',
            data:{dreamDel:selected},
            url: base+"rest/",
            success: function(data)
            {
                window.location = deleteLink;
            }
        });
    });

    // delete one dreambox
    $('.dreambox-product a.btn-del').on('click', function(e)
    {
        var selected = $(this).attr('data-id');

        $.ajax({
            type: 'POST',
            data:{dreamSingleDel:selected},
            url: base+"rest/",
            success: function(data)
            {
                window.location = deleteLink;
            }
        });
    });
});