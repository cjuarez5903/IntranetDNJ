(function($) {
    "use strict";
    var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    }

    /*=============================================
    Declarar funciones globales
    =============================================*/

    function parallax() {
        $('.bg--parallax').each(function() {
            var el = $(this),
                xpos = "50%",
                windowHeight = $(window).height();
            if (isMobile.any()) {
                $(this).css('background-attachment', 'scroll');
            } else {
                $(window).scroll(function() {
                    var current = $(window).scrollTop(),
                        top = el.offset().top,
                        height = el.outerHeight();
                    if (top + height < current || top > current + windowHeight) {
                        return;
                    }
                    el.css('backgroundPosition', xpos + " " + Math.round((top - current) * 0.2) + "px");
                });
            }
        });
    }

    function backgroundImage() {
        var databackground = $('[data-background]');
        databackground.each(function() {
            if ($(this).attr('data-background')) {
                var image_path = $(this).attr('data-background');
                $(this).css({
                    'background': 'url(' + image_path + ')'
                });
            }
        });
    }

    function siteToggleAction() {
        var navSidebar = $('.navigation--sidebar'),
            filterSidebar = $('.ps-filter--sidebar');
        $('.menu-toggle-open').on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('active')
            navSidebar.toggleClass('active');
            $('.ps-site-overlay').toggleClass('active');
        });

        $('.ps-toggle--sidebar').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $(this).toggleClass('active');
            $(this).siblings('a').removeClass('active');
            $(url).toggleClass('active');
            $(url).siblings('.ps-panel--sidebar').removeClass('active');
            $('.ps-site-overlay').toggleClass('active');
        });

        $('#filter-sidebar').on('click', function(e) {
            e.preventDefault();
            filterSidebar.addClass('active');
            $('.ps-site-overlay').addClass('active');
        });

        $('.ps-filter--sidebar .ps-filter__header .ps-btn--close').on('click', function(e) {
            e.preventDefault();
            filterSidebar.removeClass('active');
            $('.ps-site-overlay').removeClass('active');
        });

        $('body').on("click", function(e) {
            if ($(e.target).siblings(".ps-panel--sidebar").hasClass('active')) {
                $('.ps-panel--sidebar').removeClass('active');
                $('.ps-site-overlay').removeClass('active');
            }
        });
    }

    function subMenuToggle() {
        $('.menu--mobile .menu-item-has-children > .sub-toggle').on('click', function(e) {
            e.preventDefault();
            var current = $(this).parent('.menu-item-has-children')
            $(this).toggleClass('active');
            current.siblings().find('.sub-toggle').removeClass('active');
            current.children('.sub-menu').slideToggle(350);
            current.siblings().find('.sub-menu').slideUp(350);
            if (current.hasClass('has-mega-menu')) {
                current.children('.mega-menu').slideToggle(350);
                current.siblings('.has-mega-menu').find('.mega-menu').slideUp(350);
            }

        });
        $('.menu--mobile .has-mega-menu .mega-menu__column .sub-toggle').on('click', function(e) {
            e.preventDefault();
            var current = $(this).closest('.mega-menu__column')
            $(this).toggleClass('active');
            current.siblings().find('.sub-toggle').removeClass('active');
            current.children('.mega-menu__list').slideToggle(350);
            current.siblings().find('.mega-menu__list').slideUp(350);
        });
        var listCategories = $('.ps-list--categories');
        if (listCategories.length > 0) {
            $('.ps-list--categories .menu-item-has-children > .sub-toggle').on('click', function(e) {
                e.preventDefault();
                var current = $(this).parent('.menu-item-has-children')
                $(this).toggleClass('active');
                current.siblings().find('.sub-toggle').removeClass('active');
                current.children('.sub-menu').slideToggle(350);
                current.siblings().find('.sub-menu').slideUp(350);
                if (current.hasClass('has-mega-menu')) {
                    current.children('.mega-menu').slideToggle(350);
                    current.siblings('.has-mega-menu').find('.mega-menu').slideUp(350);
                }

            });
        }
    }

    function stickyHeader() {
        var header = $('.header'),
            scrollPosition = 0,
            checkpoint = 50;
        header.each(function() {
            if ($(this).data('sticky') === true) {
                var el = $(this);
                $(window).scroll(function() {

                    var currentPosition = $(this).scrollTop();
                    if (currentPosition > checkpoint) {
                        el.addClass('header--sticky');
                    } else {
                        el.removeClass('header--sticky');
                    }
                });
            }
        })

        var stickyCart = $('#cart-sticky');
        if (stickyCart.length > 0) {
            $(window).scroll(function() {
                var currentPosition = $(this).scrollTop();
                if (currentPosition > checkpoint) {
                    stickyCart.addClass('active');
                } else {
                    stickyCart.removeClass('active');
                }
            });
        }
    }

    function owlCarouselConfig() {
        var target = $('.owl-slider');
        if (target.length > 0) {
            target.each(function() {
                var el = $(this),
                    dataAuto = el.data('owl-auto'),
                    dataLoop = el.data('owl-loop'),
                    dataSpeed = el.data('owl-speed'),
                    dataGap = el.data('owl-gap'),
                    dataNav = el.data('owl-nav'),
                    dataDots = el.data('owl-dots'),
                    dataAnimateIn = (el.data('owl-animate-in')) ? el.data('owl-animate-in') : '',
                    dataAnimateOut = (el.data('owl-animate-out')) ? el.data('owl-animate-out') : '',
                    dataDefaultItem = el.data('owl-item'),
                    dataItemXS = el.data('owl-item-xs'),
                    dataItemSM = el.data('owl-item-sm'),
                    dataItemMD = el.data('owl-item-md'),
                    dataItemLG = el.data('owl-item-lg'),
                    dataItemXL = el.data('owl-item-xl'),
                    dataNavLeft = (el.data('owl-nav-left')) ? el.data('owl-nav-left') : "<i class='icon-chevron-left'></i>",
                    dataNavRight = (el.data('owl-nav-right')) ? el.data('owl-nav-right') : "<i class='icon-chevron-right'></i>",
                    duration = el.data('owl-duration'),
                    datamouseDrag = (el.data('owl-mousedrag') == 'on') ? true : false;
                if (target.children('div, span, a, img, h1, h2, h3, h4, h5, h5').length >= 2) {
                    el.owlCarousel({
                        animateIn: dataAnimateIn,
                        animateOut: dataAnimateOut,
                        margin: dataGap,
                        autoplay: dataAuto,
                        autoplayTimeout: dataSpeed,
                        autoplayHoverPause: true,
                        loop: dataLoop,
                        nav: dataNav,
                        mouseDrag: datamouseDrag,
                        touchDrag: true,
                        autoplaySpeed: duration,
                        navSpeed: duration,
                        dotsSpeed: duration,
                        dragEndSpeed: duration,
                        navText: [dataNavLeft, dataNavRight],
                        dots: dataDots,
                        items: dataDefaultItem,
                        responsive: {
                            0: {
                                items: dataItemXS
                            },
                            480: {
                                items: dataItemSM
                            },
                            768: {
                                items: dataItemMD
                            },
                            992: {
                                items: dataItemLG
                            },
                            1200: {
                                items: dataItemXL
                            },
                            1680: {
                                items: dataDefaultItem
                            }
                        }
                    });
                }

            });
        }
    }

    function masonry($selector) {
        var masonry = $($selector);
        if (masonry.length > 0) {
            if (masonry.hasClass('filter')) {
                masonry.imagesLoaded(function() {
                    masonry.isotope({
                        columnWidth: '.grid-sizer',
                        itemSelector: '.grid-item',
                        isotope: {
                            columnWidth: '.grid-sizer'
                        },
                        filter: "*"
                    });
                });
                var filters = masonry.closest('.masonry-root').find('.ps-masonry-filter > li > a');
                filters.on('click', function(e) {
                    e.preventDefault();
                    var selector = $(this).attr('href');
                    filters.find('a').removeClass('current');
                    $(this).parent('li').addClass('current');
                    $(this).parent('li').siblings('li').removeClass('current');
                    $(this).closest('.masonry-root').find('.ps-masonry').isotope({
                        itemSelector: '.grid-item',
                        isotope: {
                            columnWidth: '.grid-sizer'
                        },
                        filter: selector
                    });
                    return false;
                });
            } else {
                masonry.imagesLoaded(function() {
                    masonry.masonry({
                        columnWidth: '.grid-sizer',
                        itemSelector: '.grid-item'
                    });
                });
            }
        }
    }

    function mapConfig() {
        var map = $('#contact-map');
        if (map.length > 0) {
            map.gmap3({
                address: map.data('address'),
                zoom: map.data('zoom'),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: false
            }).marker(function(map) {
                return {
                    position: map.getCenter(),
                    icon: 'img/marker.png',
                };
            }).infowindow({
                content: map.data('address')
            }).then(function(infowindow) {
                var map = this.get(0);
                var marker = this.get(1);
                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                });
            });
        } else {
            return false;
        }
    }

    function slickConfig() {
        var product = $('.ps-product--detail');
        if (product.length > 0) {
            var primary = product.find('.ps-product__gallery'),
                second = product.find('.ps-product__variants'),
                vertical = product.find('.ps-product__thumbnail').data('vertical');
            primary.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                asNavFor: '.ps-product__variants',
                fade: true,
                dots: false,
                infinite: false,
                arrows: primary.data('arrow'),
                prevArrow: "<a href='#'><i class='fa fa-angle-left'></i></a>",
                nextArrow: "<a href='#'><i class='fa fa-angle-right'></i></a>",
            });
            second.slick({
                slidesToShow: second.data('item'),
                slidesToScroll: 1,
                infinite: false,
                arrows: second.data('arrow'),
                focusOnSelect: true,
                prevArrow: "<a href='#'><i class='fa fa-angle-up'></i></a>",
                nextArrow: "<a href='#'><i class='fa fa-angle-down'></i></a>",
                asNavFor: '.ps-product__gallery',
                vertical: vertical,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            arrows: second.data('arrow'),
                            slidesToShow: 4,
                            vertical: false,
                            prevArrow: "<a href='#'><i class='fa fa-angle-left'></i></a>",
                            nextArrow: "<a href='#'><i class='fa fa-angle-right'></i></a>"
                        }
                    },
                    {
                        breakpoint: 992,
                        settings: {
                            arrows: second.data('arrow'),
                            slidesToShow: 4,
                            vertical: false,
                            prevArrow: "<a href='#'><i class='fa fa-angle-left'></i></a>",
                            nextArrow: "<a href='#'><i class='fa fa-angle-right'></i></a>"
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 3,
                            vertical: false,
                            prevArrow: "<a href='#'><i class='fa fa-angle-left'></i></a>",
                            nextArrow: "<a href='#'><i class='fa fa-angle-right'></i></a>"
                        }
                    },
                ]
            });
        }
    }

    function tabs() {
        $('.ps-tab-list  li > a ').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $(this).closest('li').siblings('li').removeClass('active');
            $(this).closest('li').addClass('active');
            $(target).addClass('active');
            $(target).siblings('.ps-tab').removeClass('active');
        });
        $('.ps-tab-list.owl-slider .owl-item a').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $(this).closest('.owl-item').siblings('.owl-item').removeClass('active');
            $(this).closest('.owl-item').addClass('active');
            $(target).addClass('active');
            $(target).siblings('.ps-tab').removeClass('active');
        });
    }

    function rating() {
        $('select.ps-rating').each(function() {
            var readOnly;
            if ($(this).attr('data-read-only') == 'true') {
                readOnly = true
            } else {
                readOnly = false;
            }
            $(this).barrating({
                theme: 'fontawesome-stars',
                readonly: readOnly,
                emptyValue: '0'
            });
        });
    }

    function productLightbox() {
        var product = $('.ps-product--detail');
        if (product.length > 0) {
            $('.ps-product__gallery').lightGallery({
                selector: '.item a',
                thumbnail: true,
                share: false,
                fullScreen: false,
                autoplay: false,
                autoplayControls: false,
                actualSize: false
            });
            if (product.hasClass('ps-product--sticky')) {
                $('.ps-product__thumbnail').lightGallery({
                    selector: '.item a',
                    thumbnail: true,
                    share: false,
                    fullScreen: false,
                    autoplay: false,
                    autoplayControls: false,
                    actualSize: false
                });
            }
        }
        $('.ps-gallery--image').lightGallery({
            selector: '.ps-gallery__item',
            thumbnail: true,
            share: false,
            fullScreen: false,
            autoplay: false,
            autoplayControls: false,
            actualSize: false
        });
        $('.ps-video').lightGallery({
            thumbnail: false,
            share: false,
            fullScreen: false,
            autoplay: false,
            autoplayControls: false,
            actualSize: false
        });
    }

    function backToTop() {
        var scrollPos = 0;
        var element = $('#back2top');
        $(window).scroll(function() {
            var scrollCur = $(window).scrollTop();
            if (scrollCur > scrollPos) {
                // scroll down
                if (scrollCur > 500) {
                    element.addClass('active');
                } else {
                    element.removeClass('active');
                }
            } else {
                // scroll up
                element.removeClass('active');
            }

            scrollPos = scrollCur;
        });

        element.on('click', function() {
            $('html, body').animate({
                scrollTop: '0px'
            }, 800);
        });
    }

    function filterSlider() {
        var el = $('.ps-slider');
        var min = el.siblings().find('.ps-slider__min');
        var max = el.siblings().find('.ps-slider__max');
        var defaultMinValue = el.data('default-min');
        var defaultMaxValue = el.data('default-max');
        var maxValue = el.data('max');
        var step = el.data('step');
        if (el.length > 0) {
            el.slider({
                min: 0,
                max: maxValue,
                step: step,
                range: true,
                values: [defaultMinValue, defaultMaxValue],
                slide: function(event, ui) {
                    var values = ui.values;
                    min.text('$' + values[0]);
                    max.text('$' + values[1]);
                }
            });
            var values = el.slider("option", "values");
            min.text('$' + values[0]);
            max.text('$' + values[1]);
        } else {
            // return false;
        }
    }

    function modalInit() {
        var modal = $('.ps-modal');
        if (modal.length) {
            if (modal.hasClass('active')) {
                $('body').css('overflow-y', 'hidden');
            }
        }
        modal.find('.ps-modal__close, .ps-btn--close').on('click', function(e) {
            e.preventDefault();
            $(this).closest('.ps-modal').removeClass('active');
        });
        $('.ps-modal-link').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $(target).addClass('active');
            $("body").css('overflow-y', 'hidden');
        });
        $('.ps-modal').on("click", function(event) {
            if (!$(event.target).closest(".ps-modal__container").length) {
                modal.removeClass('active');
                $("body").css('overflow-y', 'auto');
            }
        });
    }

    function searchInit() {
        var searchbox = $('.ps-search');
        $('.ps-search-btn').on('click', function(e) {
            e.preventDefault();
            searchbox.addClass('active');
        });
        searchbox.find('.ps-btn--close').on('click', function(e) {
            e.preventDefault();
            searchbox.removeClass('active');
        });
    }

    function countDown() {
        var time = $(".ps-countdown");
        time.each(function() {
            var el = $(this),
                value = $(this).data('time');
            var countDownDate = new Date(value).getTime();
            var timeout = setInterval(function() {
                var now = new Date().getTime(),
                    distance = countDownDate - now;
                var days = Math.floor(distance / (1000 * 60 * 60 * 24)),
                    hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                    minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
                    seconds = Math.floor((distance % (1000 * 60)) / 1000);
                el.find('.days').html(days);
                el.find('.hours').html(hours);
                el.find('.minutes').html(minutes);
                el.find('.seconds').html(seconds);
                if (distance < 0) {
                    clearInterval(timeout);
                    el.closest('.ps-section').hide();
                }
            }, 1000);
        });
    }

    function productFilterToggle() {
        $('.ps-filter__trigger').on('click', function(e) {
            e.preventDefault();
            var el = $(this);
            el.find('.ps-filter__icon').toggleClass('active');
            el.closest('.ps-filter').find('.ps-filter__content').slideToggle();
        });
        if ($('.ps-sidebar--home').length > 0) {
            $('.ps-sidebar--home > .ps-sidebar__header > a').on('click', function(e) {
                e.preventDefault();
                $(this).closest('.ps-sidebar--home').children('.ps-sidebar__content').slideToggle();
            })
        }
    }

    function mainSlider() {
        var homeBanner = $('.ps-carousel--animate');
        homeBanner.slick({
            autoplay: true,
            speed: 1000,
            lazyLoad: 'progressive',
            arrows: false,
            fade: true,
            dots: true,
            prevArrow: "<i class='slider-prev ba-back'></i>",
            nextArrow: "<i class='slider-next ba-next'></i>"
        });
    }

    function subscribePopup() {
        var subscribe = $('#subscribe'),
            time = subscribe.data('time');
        setTimeout(function() {
            if (subscribe.length > 0) {
                subscribe.addClass('active');
                $('body').css('overflow', 'hidden');
            }
        }, time);
        $('.ps-popup__close').on('click', function(e) {
            e.preventDefault();
            $(this).closest('.ps-popup').removeClass('active');
            $('body').css('overflow', 'auto');
        });
        $('#subscribe').on("click", function(event) {
            if (!$(event.target).closest(".ps-popup__content").length) {
                subscribe.removeClass('active');
                $("body").css('overflow-y', 'auto');
            }
        });
    }

    function stickySidebar() {
        var sticky = $('.ps-product--sticky'),
            stickySidebar, checkPoint = 992,
            windowWidth = $(window).innerWidth();
        if (sticky.length > 0) {
            stickySidebar = new StickySidebar('.ps-product__sticky .ps-product__info', {
                topSpacing: 20,
                bottomSpacing: 20,
                containerSelector: '.ps-product__sticky',
            });
            if ($('.sticky-2').length > 0) {
                var stickySidebar2 = new StickySidebar('.ps-product__sticky .sticky-2', {
                    topSpacing: 20,
                    bottomSpacing: 20,
                    containerSelector: '.ps-product__sticky',
                });
            }
            if (checkPoint > windowWidth) {
                stickySidebar.destroy();
                stickySidebar2.destroy();
            }
        } else {
            return false;
        }
    }

    function accordion() {
        var accordion = $('.ps-accordion');
        accordion.find('.ps-accordion__content').hide();
        $('.ps-accordion.active').find('.ps-accordion__content').show();
        accordion.find('.ps-accordion__header').on('click', function(e) {
            e.preventDefault();
            if ($(this).closest('.ps-accordion').hasClass('active')) {
                $(this).closest('.ps-accordion').removeClass('active');
                $(this).closest('.ps-accordion').find('.ps-accordion__content').slideUp(350);

            } else {
                $(this).closest('.ps-accordion').addClass('active');
                $(this).closest('.ps-accordion').find('.ps-accordion__content').slideDown(350);
                $(this).closest('.ps-accordion').siblings('.ps-accordion').find('.ps-accordion__content').slideUp();
            }
            $(this).closest('.ps-accordion').siblings('.ps-accordion').removeClass('active');
            $(this).closest('.ps-accordion').siblings('.ps-accordion').find('.ps-accordion__content').slideUp();
        });
    }

    function progressBar() {
        var progress = $('.ps-progress');
        progress.each(function(e) {
            var value = $(this).data('value');
            $(this).find('span').css({
                width: value + "%"
            })
        });
    }

    function customScrollbar() {
        $('.ps-custom-scrollbar').each(function() {
            var height = $(this).data('height');
            $(this).slimScroll({
                height: height + 'px',
                alwaysVisible: true,
                color: '#000000',
                size: '6px',
                railVisible: true,
            });
        })
    }

    function select2Cofig() {
        $('select.ps-select').select2({
            placeholder: $(this).data('placeholder'),
            minimumResultsForSearch: -1
        });

        $('.select2').select2();
    }

    function carouselNavigation() {
        var prevBtn = $('.ps-carousel__prev'),
            nextBtn = $('.ps-carousel__next');
        prevBtn.on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $(target).trigger('prev.owl.carousel', [1000]);
        });
        nextBtn.on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $(target).trigger('next.owl.carousel', [1000]);
        });
    }

    function dateTimePicker() {
        $('.ps-datepicker').datepicker();
    }

    /*=============================================
    Función de paginación
    =============================================*/

    function pagination(){

        var target = $('.pagination');

        if(target.length > 0){

            target.each(function() {

                var el = $(this),
                    totalPages = el.data("total-pages"),
                    currentPage = el.data("current-page"),
                    urlPage = el.data("url-page");

                el.twbsPagination({

                    totalPages: totalPages,
                    startPage: currentPage,
                    visiblePages: 3,
                    first: '<i class="fas fa-angle-double-left"></i>',
                    last: '<i class="fas fa-angle-double-right"></i>',
                    prev: '<i class="fas fa-angle-left"></i>',
                    next: '<i class="fas fa-angle-right"></i>'

                }).on("page", function(evt, page){

                    if(urlPage.includes("&",1)){

                        urlPage = urlPage.replace("&"+currentPage, "&"+page);
                        window.location = urlPage+"#showcase";

                    }else{

                        window.location = urlPage+"&"+page+"#showcase";
                    }    

                })

            })

        }

    }

    /*=============================================
    Función Preload
    =============================================*/

    function preload(){

        var preloadFalse = $(".preloadFalse");
        var preloadTrue = $(".preloadTrue");

        if(preloadFalse.length > 0){

            preloadFalse.each(function(i){

                var el = $(this);

                $(el).ready(function(){

                    $(preloadTrue[i]).remove();
                    $(el).css({"opacity":1,"height":"auto"})

                })

            })

        }
    }

    /*=============================================
    Validación Bootstrap 4
    =============================================*/

    function validateBS4(){

        (function() {
          'use strict';
          window.addEventListener('load', function() {
            // Get the forms we want to add validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
              form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                  event.preventDefault();
                  event.stopPropagation();
                }
                form.classList.add('was-validated');
              }, false);
            });
          }, false);
        })();
   
    }

    /*=============================================
    Capturar email login 
    =============================================*/
    function rememberLogin(){

        if(localStorage.getItem("emailRem") != null){

            $('[name="loginEmail"]').val(localStorage.getItem("emailRem"));

        }

        if(localStorage.getItem("checkRem") != null && localStorage.getItem("checkRem")){

            $('#remember-me').attr("checked", true);
            
        }

    }

    /*=============================================
    Datatable
    =============================================*/
    function dataTable(){

       /*=============================================
        Datatable Lado Cliente
        =============================================*/

        var targetClient = $('.dt-responsive.dt-client');

        if(targetClient.length > 0){

            $(targetClient).DataTable({

                "order":[]

            });

        }

        /*=============================================
        Datatable Lado Servidor para productos
        =============================================*/

        var targetServerProducts = $('.dt-responsive.dt-server-products');

        if(targetServerProducts.length > 0){

            $(targetServerProducts).DataTable({

                "processing":true,
                "serverSide": true,
                "ajax":{
                    "url": $("#path").val()+"ajax/data-products.php?idStore="+$("#idStore").val(),
                    "type": "POST"
                },
                "columns": [

                    { "data": "id_product" },
                    { "data": "actions", "orderable": false  },
                    { "data": "feedback", "orderable": false },
                    { "data": "state", "orderable": false },
                    { "data": "image_product", "orderable": false  },
                    { "data": "name_product" },
                    { "data": "name_category" },
                    { "data": "name_subcategory" },
                    { "data": "price_product" },
                    { "data": "shipping_product" },
                    { "data": "stock_product" },
                    { "data": "delivery_time_product" },
                    { "data": "offer_product", "orderable": false   } ,
                     { "data": "summary_product", "orderable": false   },
                    { "data": "specifications_product", "orderable": false   },
                    { "data": "details_product", "orderable": false   },
                    { "data": "description_product", "orderable": false   },
                    { "data": "gallery_product", "orderable": false   },
                    { "data": "top_banner_product", "orderable": false   },
                    { "data": "default_banner_product", "orderable": false   },
                    { "data": "horizontal_slider_product", "orderable": false   },
                    { "data": "vertical_slider_product", "orderable": false   },
                    { "data": "video_product", "orderable": false   },
                    { "data": "tags_product", "orderable": false   },
                    { "data": "views_product"  },
                    { "data": "sales_product" },
                    { "data": "reviews_product", "orderable": false },
                    { "data": "date_created_product" }    
                ]

            });

        }

         /*=============================================
        Datatable Lado Servidor para órdenes
        =============================================*/

        var targetServerOrders = $('.dt-responsive.dt-server-orders');

        if(targetServerOrders.length > 0){

            $(targetServerOrders).DataTable({

                "processing":true,
                "serverSide": true,
                "ajax":{
                    "url": $("#path").val()+"ajax/data-orders.php?idStore="+$("#idStore").val()+"&token="+localStorage.getItem("token_user"),
                    "type": "POST"
                },
                "columns": [

                    { "data": "id_order" },
                    { "data": "status_order"},
                    { "data": "displayname_user"},
                    { "data": "email_order" },
                    { "data": "country_order" },
                    { "data": "city_order" },
                    { "data": "address_order", "orderable": false  },
                    { "data": "phone_order", "orderable": false  },
                    { "data": "name_product" },
                    { "data": "quantity_order" },
                    { "data": "details_order", "orderable": false  },
                    { "data": "price_order" },
                    { "data": "process_order", "orderable": false  },
                    { "data": "date_created_order" }    
              
                ]

            });

        }

        /*=============================================
        Datatable Lado Servidor para disputas
        =============================================*/

        var targetServerDisputes = $('.dt-responsive.dt-server-disputes');

        if(targetServerDisputes.length > 0){

            $(targetServerDisputes).DataTable({

                "processing":true,
                "serverSide": true,
                "ajax":{
                    "url": $("#path").val()+"ajax/data-disputes.php?idStore="+$("#idStore").val()+"&token="+localStorage.getItem("token_user"),
                    "type": "POST"
                },
                "columns": [

                    { "data": "id_dispute" },
                    { "data": "id_order_dispute"},
                    { "data": "displayname_user"},
                    { "data": "email_user" },
                    { "data": "content_dispute", "orderable": false },
                    { "data": "answer_dispute", "orderable": false },
                    { "data": "date_answer_dispute" },   
                    { "data": "date_created_dispute" }    
              
                ]

            });

        }

         /*=============================================
        Datatable Lado Servidor para mensajes
        =============================================*/

        var targetServerMessages = $('.dt-responsive.dt-server-messages')

        if(targetServerMessages.length > 0){

            $(targetServerMessages).DataTable({

                "processing":true,
                "serverSide": true,
                "ajax":{
                    "url": $("#path").val()+"ajax/data-messages.php?idStore="+$("#idStore").val()+"&token="+localStorage.getItem("token_user"),
                    "type": "POST"
                },
                "columns": [

                    { "data": "id_message" },
                    { "data": "name_product"},
                    { "data": "displayname_user"},
                    { "data": "email_user" },
                    { "data": "content_message", "orderable": false },
                    { "data": "answer_message", "orderable": false },
                    { "data": "date_answer_message" },   
                    { "data": "date_created_message" }    
              
                ]

            });

        }


    }


    /*=============================================
    Summernote
    =============================================*/

    function summer(){

        var target = $('.summernote');

        if(target.length > 0){

            $(target).summernote({

                placeholder:'',
                tabsize: 2,
                height: 400,
                toolbar:[
                    ['misc', ['codeview', 'undo', 'redo']],
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['style', 'ul', 'ol', 'paragraph', 'height']],
                    ['insert', ['link','picture', 'hr']]
                ]

            });

        }

    }

    /*=============================================
    Editar contenido de Summernote
    =============================================*/

    function editSummer(){

        var target = $('.editSummernote');

        if(target.length > 0){

            target.each(function(i){

                var el = $(this);

                $(el).ready(function(){

                    var settings = {
                   
                      "url": $("#urlApi").val()+"products?linkTo=id_product&equalTo="+$(el).attr("idProduct")+"&select=description_product",
                      "method": "GET",
                      "timeout": 0,
                    };

                    /*=============================================
                    Cuando la petición de AJAX devuelve coincidencia
                    =============================================*/

                    $.ajax(settings).done(function (response) {
                      
                        if(response.status == 200){

                            $(el).summernote('pasteHTML', response.results[0].description_product);

                        }

                    });

                })


            })

        }

    }

    /*=============================================
    Share
    =============================================*/

    function share(){

        var target = $('.social-share');

        if(target.length > 0){

            $(target).shapeShare();

        }

    }

    /*=============================================
    Ejecutar funciones globales
    =============================================*/
    
    $(function() {
        backgroundImage();
        owlCarouselConfig();
        siteToggleAction();
        subMenuToggle();
        masonry('.ps-masonry');
        productFilterToggle();
        tabs();
        slickConfig();     
        productLightbox();        
        rating();
        backToTop();
        stickyHeader();
        filterSlider();
        mapConfig();
        modalInit();
        searchInit();
        countDown();
        mainSlider();
        parallax();
        stickySidebar();
        accordion();
        progressBar();
        customScrollbar();
        select2Cofig();
        carouselNavigation();
        dateTimePicker();
        $('[data-toggle="tooltip"]').tooltip();
        pagination();
        preload();
        validateBS4();
        rememberLogin();
        dataTable();
        summer();
        editSummer();
        share();
    });

    $(window).on('load', function() {
        $('body').addClass('loaded');
        // subscribePopup();
    });

    $.scrollUp({
        scrollText:'',
        scrollSpeed: 1000
    })

})(jQuery);


/*=============================================
Función para ordenar productos
=============================================*/

function sortProduct(event){
    
    var url = event.target.value.split("+")[0];
    var sort = event.target.value.split("+")[1];
    var endUrl = url.split("&")[0];
    window.location = endUrl+"&1&"+sort+"#showcase";

}


/*=============================================
Función para crear Cookies en JS
=============================================*/

function setCookie(name, value, exp){

    var now = new Date();

    now.setTime(now.getTime() + (exp*24*60*60*1000));

    var expDate = "expires="+now.toUTCString();

    document.cookie = name + "=" +value+"; "+expDate;

}

/*=============================================
Función para almacenar en cookie la tabulación de la vitrina
=============================================*/

$(document).on("click",".ps-tab-list li", function(){

    setCookie("tab", $(this).attr("type"), 1);

})

/*=============================================
Función para buscar productos
=============================================*/

$(document).on("click", ".btnSearch", function(){

    var path = $(this).attr("path");
    var search = $(this).parent().children(".inputSearch").val().toLowerCase();
    var match = /^[a-z0-9ñÑáéíóú ]*$/;

    if(match.test(search)){

        var searchTest = search.replace(/[ ]/g, "_");
        searchTest = searchTest.replace(/[ñ]/g, "n");
        searchTest = searchTest.replace(/[á]/g, "a");
        searchTest = searchTest.replace(/[é]/g, "e");
        searchTest = searchTest.replace(/[í]/g, "i");
        searchTest = searchTest.replace(/[ó]/g, "o");
        searchTest = searchTest.replace(/[ú]/g, "u");
        
        window.location = path+searchTest;

    }else{

       $(this).parent().children(".inputSearch").val("");
    
    }

})

/*=============================================
Función para buscar con la tecla ENTER
=============================================*/

var inputSearch = $(".inputSearch");
var btnSearch = $(".btnSearch");

for(let i = 0; i < inputSearch.length; i++){

    $(inputSearch[i]).keyup(function(event) {
        
        event.preventDefault();

        if(event.keyCode == 13 && $(inputSearch[i]).val() != ""){

            var path =  $(btnSearch[i]).attr("path");
            var search = $(this).val().toLowerCase();
            var match = /^[a-z0-9ñÑáéíóú ]*$/;

            if(match.test(search)){

                var searchTest = search.replace(/[ ]/g, "_");
                searchTest = searchTest.replace(/[ñ]/g, "n");
                searchTest = searchTest.replace(/[á]/g, "a");
                searchTest = searchTest.replace(/[é]/g, "e");
                searchTest = searchTest.replace(/[í]/g, "i");
                searchTest = searchTest.replace(/[ó]/g, "o");
                searchTest = searchTest.replace(/[ú]/g, "u");
                
                window.location = path+searchTest;

            }else{

               $(this).val("");
            
            }


        }


    })

}

/*=============================================
Función para cambiar la cantidad
=============================================*/

function changeQuantity(quantity, move, stock, index){

    var number = 1;

    if(Number(quantity) > stock-1){

        quantity = stock-1;

    }

    if(move == "up"){

        number = Number(quantity)+1;
    
    }

    if(move == "down" && Number(quantity) > 1){

        number = Number(quantity)-1;
    }

    $("#quant"+index).val(number);

    $("[quantitySC]").attr("quantitySC", number);

    totalP(index);
 
}

/*=============================================
Validar correo repetido
=============================================*/

function validateDataRepeat(event, type){


    if(type == "email"){

        var table = "users";
        var linkTo = "email_user";
        var select = "email_user,method_user";


    }

    if(type == "store"){

        var table = "stores";
        var linkTo = "name_store";
        var select = "name_store";


    }

    if(type == "product"){

        var table = "products";
        var linkTo = "name_product";
        var select = "name_product";

    }

    var settings = {
      "url": $("#urlApi").val()+table+"?equalTo="+event.target.value+"&linkTo="+linkTo+"&select="+select,
      "method": "GET",
      "timeout": 0,
    };


    /*=============================================
    Cuando la petición de AJAX devuelve error
    =============================================*/

    $.ajax(settings).error(function (response) {

        if(response.responseJSON.status == 404){
            
            if(type == "email"){

                validateJS(event, "email");

            }

            if(type == "store"){

                validateJS(event, "text&number");
                createUrl(event,"urlStore");

            }

            if(type == "product"){

                validateJS(event, "text&number");
                createUrl(event,"urlProduct");

            }
        }

    })

    /*=============================================
    Cuando la petición de AJAX devuelve coincidencia
    =============================================*/

    $.ajax(settings).done(function (response) {
      
        if(response.status == 200){

            $(event.target).parent().addClass("was-validated");

            if(type == "email"){
                $(event.target).parent().children(".invalid-feedback").html("The email already exists in the database and was registered with the method "+response.results[0].method_user);
            }

            if(type == "store" || type == "product"){
                $(event.target).parent().children(".invalid-feedback").html("The name "+type+" already exists in the database and was registered");
            }

            event.target.value = "";

            return;

        }

    });

}


/*=============================================
Función para validar formulario
=============================================*/

function validateJS(event, type){

    /*=============================================
    Validamos texto
    =============================================*/

    if(type == "text"){

        var pattern = /^[A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/;

        if(!pattern.test(event.target.value)){

            $(event.target).parent().addClass("was-validated");

            $(event.target).parent().children(".invalid-feedback").html("Do not use numbers or special characters");

            event.target.value = "";

            return;

        }

    }

    /*=============================================
    Validamos texto y números
    =============================================*/

    if(type == "text&number"){

        var pattern = /^[0-9A-Za-zñÑáéíóúÁÉÍÓÚ ]{1,}$/;

        if(!pattern.test(event.target.value)){

            $(event.target).parent().addClass("was-validated");

            $(event.target).parent().children(".invalid-feedback").html("Do not use special characters");

            event.target.value = "";

            return;

        }

    }

    /*=============================================
    Validamos email
    =============================================*/

    if(type == "email"){

        var pattern = /^[^0-9][.a-zA-Z0-9_]+([.][.a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/;

        if(!pattern.test(event.target.value)){

            $(event.target).parent().addClass("was-validated");

            $(event.target).parent().children(".invalid-feedback").html("The email is misspelled");

            event.target.value = "";

            return;

        }

    }

    /*=============================================
    Validamos contraseña
    =============================================*/

    if(type == "password"){

        var pattern = /^[#\\=\\$\\;\\*\\_\\?\\¿\\!\\¡\\:\\.\\,\\0-9a-zA-Z]{1,}$/;

        if(!pattern.test(event.target.value)){

            $(event.target).parent().addClass("was-validated");

            $(event.target).parent().children(".invalid-feedback").html("The password is misspelled");

            event.target.value = "";

            return;

        }

    }

    /*=============================================
    Validamos teléfono
    =============================================*/

    if(type == "phone"){

        var pattern = /^[-\\(\\)\\0-9 ]{1,}$/;

        if(!pattern.test(event.target.value)){

            $(event.target).parent().addClass("was-validated");

            $(event.target).parent().children(".invalid-feedback").html("The phone is misspelled");

            event.target.value = "";

            return;

        }

    }

    /*=============================================
    Validamos párrafos
    =============================================*/

    if(type == "paragraphs"){

        var pattern = /^[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}$/;

        if(!pattern.test(event.target.value)){

            $(event.target).parent().addClass("was-validated");

            $(event.target).parent().children(".invalid-feedback").html("The input is misspelled");

            event.target.value = "";

            return;

        }

    }

    /*=============================================
    Validamos número
    =============================================*/

    if(type == "numbers"){

        var pattern = /^[.\\,\\0-9]{1,}$/;

        if(!pattern.test(event.target.value)){

            $(event.target).parent().addClass("was-validated");

            $(event.target).parent().children(".invalid-feedback").html("The input is misspelled");

            event.target.value = "";

            return;

        }

    }

}


/*=============================================
Validamos imagen
=============================================*/

function validateImageJS(event, input){

    var image = event.target.files[0];

    /*=============================================
    Validamos el formato
    =============================================*/

    if(image["type"] !== "image/jpeg" && image["type"] !== "image/png"){

        fncSweetAlert("error", "The image must be in JPG or PNG format", null)

        return;
    }

    /*=============================================
    Validamos el tamaño
    =============================================*/

    else if(image["size"] > 2000000){

        fncSweetAlert("error", "Image must not weigh more than 2MB", null)

        return;

    }

    /*=============================================
    Mostramos la imagen temporal
    =============================================*/

    else{

        var data = new FileReader();
        data.readAsDataURL(image);

        $(data).on("load", function(event){

            var path = event.target.result; 

            $("."+input).attr("src", path);    

        })

    }

}

/*=============================================
Función para recordar email en el login
=============================================*/

function remember(event){

    if(event.target.checked){

        localStorage.setItem("emailRem", $('[name="loginEmail"]').val());
        localStorage.setItem("checkRem", true);

    }else{

        localStorage.removeItem("emailRem");
        localStorage.removeItem("checkRem");

    }

}


/*=============================================
Función para agregar productos a la lista de deseos
=============================================*/

function addWishlist(urlProduct, urlApi){
    
    /*=============================================
    Validamos que el usuario esté autenticado
    =============================================*/

    if(localStorage.getItem("token_user") != null){

        var token = localStorage.getItem("token_user");

        /*=============================================
        Revisar que el token coincida con la base de datos
        =============================================*/

        var settings = {
          "url": urlApi+"users?equalTo="+token+"&linkTo=token_user&select=id_user,wishlist_user",
          "method": "GET",
          "timeout": 0,
        };

        /*=============================================
        Cuando la petición de AJAX devuelve error
        =============================================*/

        $.ajax(settings).error(function (response) {
    
            if(response.responseJSON.status == 404){

                fncNotie(3,"The user token is not correct.");

                return;
            }

        })

        /*=============================================
        Cuando la petición de AJAX devuelve el resultado correcto
        =============================================*/

        $.ajax(settings).done(function (response) {
          
            if(response.status == 200){

                var id = response.results[0].id_user;
                var wishlist = JSON.parse(response.results[0].wishlist_user);

                var noRepeat = 0;

                /*=============================================
                Preguntamos si existe un producto en la lista de deseos
                =============================================*/

                if(wishlist != null && wishlist.length > 0){

                    wishlist.forEach(list=>{
                        
                        if(list == urlProduct){
                            
                            noRepeat --;
                           
                        }else{

                            noRepeat ++;
                        }

                    })

                    /*=============================================
                    Preguntamos si no ha agregado este producto a la lista de deseos anteriormente
                    =============================================*/ 

                    if(wishlist.length != noRepeat){

                        fncNotie(2,"It already exists on your wishlist");

                    }else{

                        wishlist.push(urlProduct);

                        /*=============================================
                        Cuando no exista lista de deseos inicialmente
                        =============================================*/

                        var settings = {
                            "url": urlApi+"users?id="+id+"&nameId=id_user&token="+token,
                            "method": "PUT",
                            "timeout": 0,
                            "headers": {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            "data": {
                                "wishlist_user": JSON.stringify(wishlist)
                            }
                        };

                        /*=============================================
                        Cuando la petición de AJAX devuelve el resultado correcto
                        =============================================*/

                        $.ajax(settings).done(function (response) {

                            if(response.status == 200){

                                var totalWishlist = Number($(".totalWishlist").html());

                                $(".totalWishlist").html(totalWishlist + 1);

                                fncNotie(1,"Product added to wishlist");

                            }

                        })

                    }

                }else{
 
                    /*=============================================
                    Cuando no exista lista de deseos inicialmente
                    =============================================*/

                    var settings = {
                        "url": urlApi+"users?id="+id+"&nameId=id_user&token="+token,
                        "method": "PUT",
                        "timeout": 0,
                        "headers": {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        "data": {
                            "wishlist_user": '["'+urlProduct+'"]'
                        }
                    };

                    /*=============================================
                    Cuando la petición de AJAX devuelve el resultado correcto
                    =============================================*/

                    $.ajax(settings).done(function (response) {

                        if(response.status == 200){

                            var totalWishlist = Number($(".totalWishlist").html());

                            $(".totalWishlist").html(totalWishlist + 1);

                            fncNotie(1,"Product added to wishlist");

                        }

                    })

                }

            }

        });

    }else{

        fncNotie(3,"The user must be logged in.");
    }

}

/*=============================================
Función para agregar dos productos a la lista de deseos
=============================================*/

function addWishlist2(urlProduct1, urlProduct2, urlApi){
    
    addWishlist(urlProduct1, urlApi);

    setTimeout(function(){

         addWishlist(urlProduct2, urlApi);

    },1000)

}

/*=============================================
Función para remover productos de la lista de deseos
=============================================*/

function removeWishlist(urlProduct, urlApi, urlDomain){
   
    fncSweetAlert("confirm","Are you sure to delete this item?","").then(resp=>{

        if(resp){

            /*=============================================
            Revisar que el token coincida con la base de datos
            =============================================*/ 

            var settings = {
            "url": urlApi+"users?linkTo=token_user&equalTo="+localStorage.getItem("token_user")+"&select=id_user,wishlist_user",
            "method": "GET",
            "timeout": 0,
            }; 

            $.ajax(settings).done(function (response) {   

                if(response.status == 200){

                    var id = response.results[0].id_user;
                    var wishlist = JSON.parse(response.results[0].wishlist_user);

                    wishlist.forEach((list, index)=>{

                        if(list == urlProduct){

                            wishlist.splice(index, 1); 
                            
                        }

                    })

                    var settings = {
                        "url": urlApi+"users?id="+id+"&nameId=id_user&token="+localStorage.getItem("token_user"),
                        "method": "PUT",
                        "timeout": 0,
                        "headers": {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        "data": {
                            "wishlist_user": JSON.stringify(wishlist)
                        }
                    };

                    $.ajax(settings).done(function (response) {

                        if(response.status == 200){

                            var totalWishlist = Number($(".totalWishlist").html());

                            $(".totalWishlist").html(totalWishlist - 1);

                            fncSweetAlert(
                                "success",
                                "Product delete to wishlist",
                                urlDomain+"account&wishlist"
                            )
                        }

                    })


                }

            })

        }
    
    })

}

/*=============================================
Función para adicionar productos al carrito de compras
=============================================*/

function addShoppingCart(urlProduct, urlApi, currentUrl, tag){
    
    /*=============================================
    Traer información del producto
    =============================================*/

    var select = "stock_product,specifications_product";

    var settings = {
        "url": urlApi+"products?linkTo=url_product&equalTo="+urlProduct+"&select="+select,
        "method": "GET",
        "timeout": 0
    }

    $.ajax(settings).done(function (response) {

        if(response.status == 200){
            
            /*=============================================
            Preguntamos primero que el producto tenga stock
            =============================================*/

            if(response.results[0].stock_product == 0){

                fncSweetAlert(
                    "error",
                    "Out of Stock",
                    ""
                )

                return;

            }

            /*=============================================
            Validamos existencia de detalles
            =============================================*/

            if(tag.getAttribute("detailsSC") != ""){

                var details = tag.getAttribute("detailsSC");
            
            }else{

                var details = "";
            }

            /*=============================================
            Validamos existencia de cantidad
            =============================================*/

            if(tag.getAttribute("quantitySC") != ""){

                var quantity = tag.getAttribute("quantitySC");
            
            }else{

                var quantity = 1;
            }

            /*=============================================
            Preguntamos si detalles viene vacío
            =============================================*/

            if(details == ""){

                if(response.results[0].specifications_product != null){
                    
                    var detailsProduct = JSON.parse(response.results[0].specifications_product);

                    details = '[{';

                    for(const i in detailsProduct){
                        
                       var property = Object.keys(detailsProduct[i]).toString();
                       
                       details += '"'+property+'":"'+detailsProduct[i][property][0]+'",'

                    }

                    details = details.slice(0, -1);

                    details += '}]';        

                }

            }else{

                var newDetail = JSON.parse(details);  
       
                if(response.results[0].specifications_product != null){
                    
                    var detailsProduct = JSON.parse(response.results[0].specifications_product);

                    details = '[{';

                    for(const i in detailsProduct){
                        
                       var property = Object.keys(detailsProduct[i]).toString();
                       
                       details += '"'+property+'":"'+detailsProduct[i][property][0]+'",'

                    }

                    details = details.slice(0, -1);

                    details += '}]';        
                    
                }

                for(const i in JSON.parse(details)[0]){         

                    if(newDetail[0][i] == undefined){
                        
                        Object.assign(newDetail[0], {[i]:JSON.parse(details)[0][i]})

                    }

                }

                details = JSON.stringify(newDetail);
                        
            }

            /*=============================================
            Preguntar si la Cookie ya existe
            =============================================*/

            var myCookies = document.cookie;
            var listCookies = myCookies.split(";");
            var count = 0;
            
            for(const i in listCookies){

                list = listCookies[i].search("listSC");
                
                /*=============================================
                Si list es superior a -1 es porque encontró la cookie
                =============================================*/

                if(list > -1){

                    count--

                    var arrayListSC = JSON.parse(listCookies[i].split("=")[1]);

                }else{

                    count++
                }

            }


            /*=============================================
            Trabajamos sobre la cookie que ya existe
            =============================================*/

            if(count != listCookies.length){

                if(arrayListSC != undefined){

                    /*=============================================
                    Preguntar si el producto se repite
                    =============================================*/

                    var count = 0;
                    var index = null;

                    for(const i in arrayListSC){
                        
                        if(arrayListSC[i].product == urlProduct &&
                           arrayListSC[i].details == details.toString()){

                            count--
                            index = i;

                        }else{

                            count++
                        }

                    }

                    if(count == arrayListSC.length){

                        arrayListSC.push({

                            "product": urlProduct,
                            "details": details,
                            "quantity": quantity

                        })
                    
                    }else{

                        arrayListSC[index].quantity += quantity;

                    }

                    /*=============================================
                    Creamos la cookie
                    =============================================*/

                    setCookie("listSC", JSON.stringify(arrayListSC), 1); 

                    fncSweetAlert(
                        "success", 
                        "Product added to Shopping Cart",
                        currentUrl
                    )  

                }

            }else{

                /*=============================================
                Creamos la cookie
                =============================================*/

                var arrayListSC = [];

                arrayListSC.push({

                    "product": urlProduct,
                    "details": details,
                    "quantity": quantity
                
                }) 

                setCookie("listSC", JSON.stringify(arrayListSC), 1);  

                fncSweetAlert(
                    "success", 
                    "Product added to Shopping Cart",
                    currentUrl
                ) 

            }

        }

    })

}

/*=============================================
Función para remover productos del carrito de compras
=============================================*/

function removeSC(urlProduct, currentUrl){

    fncSweetAlert("confirm","Are you sure to delete this item?","").then(resp=>{

        if(resp){

            /*=============================================
            Preguntar si la Cookie ya existe
            =============================================*/

            var myCookies = document.cookie;
            var listCookies = myCookies.split(";");
            var count = 0;
            
            for(const i in listCookies){

                list = listCookies[i].search("listSC");
                
                /*=============================================
                Si list es superior a -1 es porque encontró la cookie
                =============================================*/

                if(list > -1){

                    count--

                    var arrayListSC = JSON.parse(listCookies[i].split("=")[1]);

                }else{

                    count++
                }

            }


            /*=============================================
            Trabajamos sobre la cookie que ya existe
            =============================================*/

            if(count != listCookies.length){

                if(arrayListSC != undefined){

                    arrayListSC.forEach((list, index)=>{

                        if(list.product == urlProduct){

                            arrayListSC.splice(index, 1); 

                        }    

                    })

                    setCookie("listSC", JSON.stringify(arrayListSC), 1);  

                    fncSweetAlert(
                        "success", 
                        "Product removed from shopping cart",
                        currentUrl
                    ) 

                }

            }    
        }

    })

}

/*=============================================
Seleccionar detalles al producto
=============================================*/

$(document).on("click", ".details", function(){

    var details = $(this).attr("detailType");
    var value = $(this).attr("detailValue");

    var detailsLength = $(".details."+details);
    
    for(var i = 0; i < detailsLength.length; i++){

        $(detailsLength[i]).css({"border":"1px solid #bbb"});
    }

    $(this).css({"border":"3px solid #bbb"});

    /*=============================================
    Preguntar si el usuario ha agregado detalles
    =============================================*/

    if($("[detailsSC]").attr("detailsSC") != ""){

       var detailsSC = JSON.parse($("[detailsSC]").attr("detailsSC"));

       for(const i in detailsSC){

            detailsSC[i][details] = value;

            $("[detailsSC]").attr("detailsSC", JSON.stringify(detailsSC))

       }


    }else{

        $("[detailsSC]").attr("detailsSC", '[{\"'+details+'\":\"'+value+'\"}]')

    }

})

/*=============================================
Función para agregar dos productos al carrito de compras
=============================================*/

function addShoppingCart2(urlProduct1, urlProduct2, urlApi, currentUrl, tag){
    
    addShoppingCart(urlProduct1, urlApi, currentUrl, tag);

    setTimeout(function(){

        addShoppingCart(urlProduct2, urlApi, currentUrl, tag);

    },1000)

}

/*=============================================
Definir el subtotal y total del carrito de compras
=============================================*/ 

var price = $(".ps-product__price span");
var shipping = $(".shipping span");
var quantity = $(".quantity input");
var subtotal = $(".subtotal");
var totalPrice = $(".totalPrice span");
var listSC = $(".listSC");

function totalP(index){

    var total = 0;

    var arrayListSC = [];

    if(price.length > 0){

        price.each(function(i) {

            /*=============================================
            Calculando el precio de envío luego de cambiar cantidad
            =============================================*/ 

            if(index != null){

                $(shipping[index]).html(Number($(shipping[index]).attr("currentShipping"))*Number($(quantity[index]).val())); 

            }else{

                /*=============================================
                Calculando el precio de envío inicial
                =============================================*/ 

                $(shipping[i]).html(Number($(shipping[i]).attr("currentShipping"))*Number($(quantity[i]).val())); 
            
            } 

            /*=============================================
            Calculando los subtotales
            =============================================*/ 

            let sub = (Number($(price[i]).html())*Number($(quantity[i]).val()))+Number($(shipping[i]).html()); 
            total += sub;   

            $(subtotal[i]).html(sub.toFixed(2));

            /*=============================================
            Definir lo que actualizaremos en la Cookie
            =============================================*/ 

            arrayListSC.push({

                "product": $(listSC[i]).attr("url"),
                "details": $(listSC[i]).attr("details"),
                "quantity": $(quantity[i]).val()

            })

        })

        /*=============================================
        Calculando el total
        =============================================*/ 

        $(totalPrice).html(total.toFixed(2));

        /*=============================================
        Actualizando la cookie del carrito de compras
        =============================================*/ 

        setCookie("listSC", JSON.stringify(arrayListSC), 1); 

    }

}

totalP(null);

/*=============================================
Agregar codigo telefónico
=============================================*/ 

function changeCountry(event){
    
    $(".dialCode").html(event.target.value.split("_")[1]);

}

/*=============================================
Capturamos el método de pago
=============================================*/

var methodPaid = $('[name="payment-method"]').val(); 

function changeMethodPaid(event){
    
    methodPaid = event.target.value;
   
   
}


/*=============================================
Capturar el total de la transacción
=============================================*/ 

var total = $(".totalOrder").attr("total");

/*=============================================
Función para procesar el checkout
=============================================*/ 

function checkout(){

    var forms = document.getElementsByClassName('needs-validation');

    var validation = Array.prototype.filter.call(forms, function(form) { 

        if (form.checkValidity()){
            
            return [""];
        }

    })

    if(validation.length > 0){      
       
       /*=============================================
        Pasarela de pago Paypal
        =============================================*/ 

        if(methodPaid == "paypal"){

            /*=============================================
            Abrimos ventana modal para incorporar el botón de pago de Paypal
            =============================================*/ 

            fncSweetAlert("html", `<div id="paypal-button-container"></div>`, null);

            /*=============================================
            Declaramos función de paypal
            =============================================*/ 

            paypal.Buttons({

                createOrder: function(data, actions) {
                    // This function sets up the details of the transaction, including the amount and line item details.
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: total
                            }
                        }]
                    });
                },

                onApprove: function(data, actions) {
                    
                    // This function captures the funds from the transaction.
                    return actions.order.capture().then(function(details) {
                        
                        if(details.status == "COMPLETED"){

                            //Generar la orden en la Base de datos

                            newOrder("paypal", "pending", details.id, total);

                        }

                        return false;
                       
                    });

                },

                onCancel: function (data) {

                    fncSweetAlert("error", `The transaction has been canceled`, null);

                    return false;

                },

                onError: function (err) {
                    
                    fncSweetAlert("error", `An error occurred while making the transaction`, null);

                    return false;

                }


            }).render('#paypal-button-container');

        }

         /*=============================================
        Pasarela de pago Payu
        =============================================*/ 

        if(methodPaid == "payu"){

            newOrder("payu", "test", null, total);

        }

        /*=============================================
        Pasarela de pago MP
        =============================================*/ 

        if(methodPaid == "mercado-pago"){

            var newTotal = 0;

            /*=============================================
            COnvertir a moneda local para Mercado Pago
            =============================================*/ 
            // https://free.currconv.com/api/v7/currencies?apiKey=[YOUR API KEY]

            var settings = {
              "url": "https://free.currconv.com/api/v7/convert?q=USD_COP&compact=ultra&apiKey=cf2b1e499a7e50da66db",
              "method": "GET",
              "timeout": 0
            };

            $.ajax(settings).error(function (response) {

                if(response.status == 400){

                    fncSweetAlert("error", `Error converting local currency`, null);

                    return;
                }
           
            })

            $.ajax(settings).done(function (response) {

                newTotal = Math.round(response["USD_COP"]*total);

                const mercadopago = new MercadoPago('YOUR API KEY');

                var formMP = `

                <img src="img/payment-method/mp_logo.png" style="width:200px" class="pb-3" />

                <form id="form-checkout" >

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-credit-card"></i></span>
                        </div>
                        <input class="form-control" name="cardNumber" id="form-checkout__cardNumber" />
                    </div>

                    <div class="form-row">
                        
                        <div class="col">

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">MM</span>
                                </div>
                                <input class="form-control" name="cardExpirationMonth" id="form-checkout__cardExpirationMonth" />
                            </div>

                        </div>

                        <div class="col">

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">AAAA</span>
                                </div>
                                 <input class="form-control" name="cardExpirationYear" id="form-checkout__cardExpirationYear" />
                            </div>

                        </div>

                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-user"></i></span>
                        </div>
                        <input class="form-control" name="cardholderName" id="form-checkout__cardholderName"/>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-envelope"></i></span>
                        </div>
                        <input class="form-control" name="cardholderEmail" id="form-checkout__cardholderEmail"/>
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">CVV/CVC</span>
                        </div>
                        <input class="form-control" name="securityCode" id="form-checkout__securityCode" />
                    </div>


                    <div class="input-group mb-3">
                        
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-university"></i></span>
                        </div>

                        <select class="form-control" name="issuer" id="form-checkout__issuer"></select>

                    </div>

                    <select class="form-control mb-3" name="identificationType" id="form-checkout__identificationType"></select>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        </div>

                        <input class="form-control" name="identificationNumber" id="form-checkout__identificationNumber"/>

                    </div>

                    <select class="form-control mb-3" name="installments" id="form-checkout__installments"></select>

                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="form-checkout__submit">Pagar</button>

                    <div class="input-group mb-3">
                        <progress value="0" class="mt-3 w-100 progress-bar">Cargando...</progress>
                    </div>

                </form>`;

                fncSweetAlert("html", formMP, null);

                 const cardForm = mercadopago.cardForm({
                  amount: newTotal.toString(),
                  autoMount: true,
                  form: {
                    id: "form-checkout",
                    cardholderName: {
                      id: "form-checkout__cardholderName",
                      placeholder: "Titular de la tarjeta",
                    },
                    cardholderEmail: {
                      id: "form-checkout__cardholderEmail",
                      placeholder: "E-mail",
                    },
                    cardNumber: {
                      id: "form-checkout__cardNumber",
                      placeholder: "Número de la tarjeta",
                    },
                    cardExpirationMonth: {
                      id: "form-checkout__cardExpirationMonth",
                      placeholder: "Mes",
                    },
                    cardExpirationYear: {
                      id: "form-checkout__cardExpirationYear",
                      placeholder: "Año",
                    },
                    securityCode: {
                      id: "form-checkout__securityCode",
                      placeholder: "Código de seguridad",
                    },
                    installments: {
                      id: "form-checkout__installments",
                      placeholder: "Cuotas",
                    },
                    identificationType: {
                      id: "form-checkout__identificationType",
                      placeholder: "Tipo de documento",
                    },
                    identificationNumber: {
                      id: "form-checkout__identificationNumber",
                      placeholder: "Número de documento",
                    },
                    issuer: {
                      id: "form-checkout__issuer",
                      placeholder: "Banco emisor",
                    },
                  },
                  callbacks: {
                    onFormMounted: error => {
                      if (error) return console.warn("Form Mounted handling error: ", error);
                      console.log("Form mounted");
                    },
                    onSubmit: event => {
                      event.preventDefault();

                      const {
                        paymentMethodId: payment_method_id,
                        issuerId: issuer_id,
                        cardholderEmail: email,
                        amount,
                        token,
                        installments,
                        identificationNumber,
                        identificationType,
                      } = cardForm.getCardFormData();

                      var response = {

                          token,
                          issuer_id,
                          payment_method_id,
                          transaction_amount: Number(amount),
                          installments: Number(installments),
                          identificationType,
                          identificationNumber
    
                      };

                      newOrder("mercado-pago", "test", null, response);
  
                    },
                    onFetching: (resource) => {
                      console.log("Fetching resource: ", resource);

                      // Animate progress bar
                      const progressBar = document.querySelector(".progress-bar");
                      progressBar.removeAttribute("value");

                      return () => {
                        progressBar.setAttribute("value", "0");
                      };
                    },
                  },
                });

            })


        }

        return false;

    }else{

        return false;

    }
  
}

/*=============================================
Dar formato a fecha
=============================================*/

function formatDate(date){

    var day = date.getDate();
    var month = date.getMonth()+1;
    var year = date.getFullYear();

    return year + '-' + month + '-' + day;

}

/*=============================================
Crear la orden
=============================================*/

function newOrder(methodPaid, statusPaid, idPayment, total){

    /*=============================================
    Capturar el id de la tienda
    =============================================*/

    var idStoreClass = $(".idStore");
    var idStore = [];
    
    idStoreClass.each(i=>{

        idStore.push($(idStoreClass[i]).val());     

    })

    /*=============================================
    Capturar ela url de la tienda
    =============================================*/

    var urlStoreClass = $(".urlStore");
    var urlStore = [];
    
    urlStoreClass.each(i=>{

        urlStore.push($(urlStoreClass[i]).val());     

    })

    /*=============================================
    Capturar el id del usuario
    =============================================*/

    var idUser = $("#idUser").val();

    /*=============================================
    Capturar el id del producto
    =============================================*/

    var idProductClass = $(".idProduct");
    var idProduct = [];
    
    idProductClass.each(i=>{

        idProduct.push($(idProductClass[i]).val());     

    })

    /*=============================================
    Capturar los detalles de la orden
    =============================================*/

    var detailsOrderClass = $(".detailsOrder");
    var detailsOrder = [];
    
    detailsOrderClass.each(i=>{

        detailsOrder.push($(detailsOrderClass[i]).html().replace(/\s+/gi,''));

    })

    /*=============================================
    Capturar la cantidad de la orden
    =============================================*/

    var quantityOrderClass = $(".quantityOrder");
    var quantityOrder = [];

    quantityOrderClass.each(i=>{

        quantityOrder.push($(quantityOrderClass[i]).html());
       
                
    })

    /*=============================================
    Capturar el precio de la orden
    =============================================*/

    var priceOrderClass = $(".priceOrder");
    var priceOrder = [];
    
    priceOrderClass.each(i=>{

        priceOrder.push($(priceOrderClass[i]).html());
                
    })

    /*=============================================
    Capturar el stock del producto
    =============================================*/

    var salesProductClass = $(".salesProduct");
    var salesProduct = [];
    
    salesProductClass.each(i=>{

        salesProduct.push($(salesProductClass[i]).val());
                
    })

    /*=============================================
    Capturar las ventas del producto
    =============================================*/

    var stockProductClass = $(".stockProduct");
    var stockProduct = [];
    
    stockProductClass.each(i=>{

        stockProduct.push($(stockProductClass[i]).val());
                
    })

    /*=============================================
    Capturar información del usuario
    =============================================*/

    var emailOrder = $("#emailOrder").val();
    var countryOrder = $("#countryOrder").val().split("_")[0];
    var cityOrder = $("#cityOrder").val();
    var phoneOrder = $("#countryOrder").val().split("_")[1]+"_"+$("#phoneOrder").val();
    var addressOrder = $("#addressOrder").val();;
    var infoOrder = $("#infoOrder").val();

    /*=============================================
    Capturar tiempo de entrega del producto
    =============================================*/

    var deliveryTimeClass = $(".deliveryTime");
    var deliveryTime = [];

    deliveryTimeClass.each(i=>{

        deliveryTime.push($(deliveryTimeClass[i]).val());
                
    })

    /*=============================================
    Preguntar si la Cookie de cupones existe
    =============================================*/

    var myCookies = document.cookie;
    var listCookies = myCookies.split(";");
    var arrayCouponsMP = [];

    for(const f in listCookies){

        list = listCookies[f].search("couponsMP");

        /*=============================================
        Si list es superior a -1 es porque encontró la cookie
        =============================================*/

        if(list > -1){

            var couponsMP = listCookies[f].split("=")[1];
            arrayCouponsMP = JSON.parse(decodeURIComponent(couponsMP));
        }
          
    }

    /*=============================================
    Preguntar si el usuario desea guardar su dirección
    =============================================*/

    var saveAddress = $("#create-account")[0].checked;

    if(saveAddress){

        var settings = {
            "url": $("#urlApi").val()+"users?id="+idUser+"&nameId=id_user&token="+localStorage.getItem("token_user"),
            "method": "PUT",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            "data": {
                "country_user": countryOrder,
                "city_user": cityOrder,
                "phone_user": phoneOrder,
                "address_user": addressOrder
            }
        };

        $.ajax(settings).done(function (response){})

    }

    /*=============================================
     Crear la descripción de la compra
    =============================================*/
    var nameProduct = $(".nameProduct");
    var description = "";

    nameProduct.each(i=>{

        description += $(nameProduct[i]).html() +" x "+quantityOrder[i]+", "
                
    })

    description = description.slice(0, -2);
    
    /*=============================================
    Variable para avisar cuando finaliza el foreach
    =============================================*/

    var forEachEnd = 0;

     /*=============================================
    Variables para almacenar los id de las órdenes y de las ventas
    =============================================*/

    var idOrder = [];
    var idSale = [];

    /*=============================================
    Recorremos los Id de productos para generar la orden
    =============================================*/

    idProduct.forEach((value, i)=>{

        /*=============================================
        Aumentar venta del producto, disminuir stock del producto
        =============================================*/

        if(methodPaid == "paypal"){

            var settings = {
                "url": $("#urlApi").val()+"products?id="+value+"&nameId=id_product&token="+localStorage.getItem("token_user"),
                "method": "PUT",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                "data": {
                    "sales_product": Number(salesProduct[i])+Number(quantityOrder[i]),
                    "stock_product": Number(stockProduct[i])-Number(quantityOrder[i])
                }
            };

            $.ajax(settings).done(function (response){})

        }
        
        /*=============================================
        Generar el tiempo de entrega de cada producto
        =============================================*/

        var moment = Math.ceil(Number(deliveryTime[i])/2);
        
        var sentDate = new Date();
        sentDate.setDate(sentDate.getDate()+moment);

        var deliveredDate = new Date();
        deliveredDate.setDate(deliveredDate.getDate()+Number(deliveryTime[i]));

        /*=============================================
        Crear el proceso de entrega de la orden
        =============================================*/

        var processOrder = [
       
            {
                "stage":"reviewed",
                "status":"ok",
                "comment":"We have received your order, we start delivery process",
                "date":formatDate(new Date())
            },

            {
                "stage":"sent",
                "status":"pending",
                "comment":"",
                "date":formatDate(sentDate)
            },

            {
                "stage":"delivered",
                "status":"pending",
                "comment":"",
                "date":formatDate(deliveredDate)
            }
        ]

        /*=============================================
        Crear la orden en base de datos
        =============================================*/

        var settings = {
            "url": $("#urlApi").val()+"orders?token="+localStorage.getItem("token_user"),
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            "data": {
                "id_store_order": idStore[i],
                "id_user_order": idUser,
                "id_product_order": value,
                "details_order": detailsOrder[i],
                "quantity_order": quantityOrder[i],
                "price_order": priceOrder[i],
                "email_order": emailOrder,
                "country_order": countryOrder,
                "city_order": cityOrder,
                "phone_order": phoneOrder,
                "address_order": addressOrder,
                "notes_order": infoOrder,
                "process_order": JSON.stringify(processOrder),
                "status_order": statusPaid,
                "date_created_order": formatDate(new Date())

            }
        };

        /*=============================================
        Cuando la petición de AJAX devuelve el resultado correcto
        =============================================*/

        $.ajax(settings).done(function (response) {
            
            if(response.status == 200){

                idOrder.push(response.results.lastId);
             
                /*=============================================
                Crear comisión
                =============================================*/

                var unitPrice = 0;
                var commissionPrice = 0;
                var count = 0;
                
                if(arrayCouponsMP.length > 0){

                    arrayCouponsMP.forEach(value=>{
                            
                        if(value == urlStore[i]){

                            count--;
                    
                        }else{

                            count++;                         

                        }
                    
                    })

                }

                if(arrayCouponsMP.length == count){

                    /*=============================================
                    Crear comisión orgánica
                    =============================================*/
               
                    unitPrice = (Number(priceOrder[i])*0.75).toFixed(2);  
                    commissionPrice = (Number(priceOrder[i])*0.25).toFixed(2);
                    
                }else{

                    /*=============================================
                    Crear comisión por cupón
                    =============================================*/

                    unitPrice = (Number(priceOrder[i])*0.95).toFixed(2);
                    commissionPrice = (Number(priceOrder[i])*0.05).toFixed(2);
                }

                /*=============================================
                Crear la venta en base de datos
                =============================================*/

                var settings = {
                    "url": $("#urlApi").val()+"sales?token="+localStorage.getItem("token_user"),
                    "method": "POST",
                    "timeout": 0,
                    "headers": {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    "data": {
                        "id_order_sale": response.results.lastId,
                        "unit_price_sale": unitPrice,
                        "commission_sale": commissionPrice,
                        "payment_method_sale": methodPaid,
                        "id_payment_sale": idPayment,
                        "status_sale": statusPaid,
                        "date_created_sale": formatDate(new Date())

                    }
                };

                $.ajax(settings).done(function (response) {

                    if(response.status == 200){

                        idSale.push(response.results.lastId);

                        forEachEnd++

                        /*=============================================
                         Avisar Cuando finaliza la creación de las órdenes y ventas
                        =============================================*/

                        if(forEachEnd == idProduct.length){

                            /*=============================================
                            Cuando finaliza la creación de las órdenes y ventas con Paypal
                            =============================================*/

                            if(methodPaid == "paypal"){

                                document.cookie = "listSC=; max-age=0";

                                fncSweetAlert("success", "The purchase has been executed successfully", $("#url").val()+"account&my-shopping");

                                return;

                            }

                            /*=============================================
                            Cuando finaliza la creación de las órdenes y ventas con Payu
                            =============================================*/

                            if(methodPaid == "payu"){

                                /*=============================================
                                Variables de Payu
                                =============================================*/

                                var action = "https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/";
                                var merchantId = 508029;
                                var accountId = 512321;
                                var referenceCode = Math.ceil(Math.random()*1000000);
                                var apiKey = "4Vj8eK4rloUd272L48hsrarnUA";
                                var signature = hex_md5(apiKey+"~"+merchantId+"~"+referenceCode+"~"+total+"~USD");
                                var test = 1;
                                var url = $("#url").val()+"checkout";

                                /*=============================================
                                Formulario de Payu
                                =============================================*/

                                var formPayu = `
                                    <img src="img/payment-method/payu_logo.png" style="width:100px" />

                                    <form method="post" action="`+action+`">
                                      <input name="merchantId"    type="hidden"  value="`+merchantId+`"   >
                                      <input name="accountId"     type="hidden"  value="`+accountId+`" >
                                      <input name="description"   type="hidden"  value="`+description+`"  >
                                      <input name="referenceCode" type="hidden"  value="`+referenceCode+`" >
                                      <input name="amount"        type="hidden"  value="`+total+`"   >
                                      <input name="tax"           type="hidden"  value="0"  >
                                      <input name="taxReturnBase" type="hidden"  value="0" >
                                      <input name="currency"      type="hidden"  value="USD" >
                                      <input name="signature"     type="hidden"  value="`+signature+`"  >
                                      <input name="test"          type="hidden"  value="`+test+`" >
                                      <input name="buyerEmail"    type="hidden"  value="`+emailOrder+`" >
                                      <input name="responseUrl"    type="hidden"  value="`+url+`" >
                                      <input name="confirmationUrl"    type="hidden"  value="`+url+`" >
                                      <input name="Submit" class="ps-btn p-0 px-5" type="submit" value="Next">
                                    </form>`;

                                fncSweetAlert("html", formPayu, null);

                                /*=============================================
                                Listado de tarjetas de crédito
                                =============================================*/ 

                                //https://www.mercadopago.com.co/developers/es/guides/payments/web-tokenize-checkout/testing/

                                /*=============================================
                                Crear cookies para modificar la base de datos luego del pago con Payu o MP
                                =============================================*/

                                setCookie("idProduct", JSON.stringify(idProduct), 1);
                                setCookie("quantityOrder", JSON.stringify(quantityOrder), 1);
                                setCookie("idOrder", JSON.stringify(idOrder), 1);
                                setCookie("idSale", JSON.stringify(idSale), 1);
                                
                            }

                            /*=============================================
                            Cuando finaliza la creación de las órdenes y ventas con Payu
                            =============================================*/

                            if(methodPaid == "mercado-pago"){

                                total["description"] = description;
                                total["email"] = emailOrder;

                                setCookie("idProduct", JSON.stringify(idProduct), 1);
                                setCookie("quantityOrder", JSON.stringify(quantityOrder), 1);
                                setCookie("idOrder", JSON.stringify(idOrder), 1);
                                setCookie("idSale", JSON.stringify(idSale), 1);
                                setCookie("mp",JSON.stringify(total), 1);

                                window.location = $("#url").val()+"checkout";

                            }


                        }

                    }
                    
                  

                })

            }

        })

    })

}

/*=============================================
Mover el scroll hasta terminos y condiciones
=============================================*/

function goTerms(){

    $("html, body").animate({

        scrollTop: $("#tabContent").offset().top-50

    })

}

/*=============================================
Aceptar términos y condiciones para nueva tienda
=============================================*/

function  changeAccept(event){
   
    if(event.target.checked){

        $("#createStore").tab("show");
        $(".btnCreateStore").removeClass("disabled");

        /*=============================================
        Mover el scroll hasta la creación de la tienda
        =============================================*/

        $("html, body").animate({

            scrollTop: $("#createStore").offset().top-100

        })

    }else{

        $("#createStore").removeClass("active");
        $(".btnCreateStore").addClass("disabled");

    }

}

/*=============================================
Función para crear Url's
=============================================*/

function createUrl(event, input){

    var value = event.target.value;

    value = value.toLowerCase();
    value = value.replace(/[ ]/g, "-");
    value = value.replace(/[á]/g, "a");
    value = value.replace(/[é]/g, "e");
    value = value.replace(/[í]/g, "i");
    value = value.replace(/[ó]/g, "o");
    value = value.replace(/[ú]/g, "u");
    value = value.replace(/[ñ]/g, "n");

    $('[name="'+input+'"]').val(value);

}


/*=============================================
Función para validar el formulario de la tienda y continuar con la creación del producto
=============================================*/

function validateFormStore(){

    /*=============================================
    Validar que la tienda esté correctamente creada
    =============================================*/

    var formStore = $(".formStore");
    var error = 0;

    formStore.each(i =>{

        if($(formStore[i]).val() == "" ||  $(formStore[i]).val() == undefined){

            error++

            $(formStore[i]).parent().addClass("was-validated");

        }

    })

    if(error > 0){

        fncNotie(3,"There are fields in the store creation that are not correct");

        return;

    }

    /*=============================================
    Habilitar el módulo de producto
    =============================================*/

    $("#createProduct").tab("show");
    $(".btnCreateProduct").removeClass("disabled");

    /*=============================================
    Mover el scroll hasta la creación del producto
    =============================================*/

    $("html, body").animate({

        scrollTop: $("#createProduct").offset().top-100

    })

}

/*=============================================
Traer subcategorias de acuerdo a la categoría
=============================================*/

function changeCategory(event){

    $(".subCategoryProduct").show();
    
    var idCategory = event.target.value.split("_")[0];

    var settings = {
      "url": $("#urlApi").val()+"subcategories?equalTo="+idCategory+"&linkTo=id_category_subcategory&select=id_subcategory,name_subcategory,title_list_subcategory",
      "method": "GET",
      "timeout": 0,
    };

    $.ajax(settings).done(function (response) {

        var optSubCategory = $(".optSubCategory");

        optSubCategory.each(i=>{

            $(optSubCategory[i]).remove();
        })

        response.results.forEach(item =>{

            $('[name="subCategoryProduct"]').append(`<option class="optSubCategory" value="`+item.id_subcategory+`_`+item.title_list_subcategory+`">`+item.name_subcategory+`</option>`)

        })

    })

}

/*=============================================
Adicionar Entradas al formulario de productos 
=============================================*/

function addInput(elem, type){

    var inputs = $("."+type);
    
    if(inputs.length < 5){

        /*=============================================
        Adicionar entrada de resumen del producto
        =============================================*/

        if(type == "inputSummary"){ 

            $(elem).before(`

                <div class="form-group__content input-group mb-3 inputSummary">
                 
                    <div class="input-group-append">
                        <span class="input-group-text">
                             <button type="button" class="btn btn-danger"  onclick="removeInput(`+inputs.length+`,'inputSummary')">&times;</button>
                        </span>
                    </div>

                    <input
                    class="form-control" 
                    type="text"
                    name="summaryProduct_`+inputs.length+`"
                    pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                    onchange="validateJS(event,'paragraphs')"
                    required>

                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>

                </div>

            `)


        } 

        /*=============================================
        Adicionar entrada de detalle del producto
        =============================================*/

        if(type == "inputDetails"){

            $(elem).before(`

                <div class="row mb-3 inputDetails">

                    <!--=====================================
                    Entrada para el título del detalle
                    ======================================--> 

                    <div class="col-12 col-lg-6 form-group__content input-group">
                         
                        <div class="input-group-append">
                            <span class="input-group-text">
                                 <button type="button" class="btn btn-danger" onclick="removeInput(`+inputs.length+`,'inputDetails')">&times;</button>
                            </span>
                        </div>

                        <div class="input-group-append">
                            <span class="input-group-text">
                                Title:
                            </span>
                        </div>

                        <input
                        class="form-control" 
                        type="text"
                        name="detailsTitleProduct_`+inputs.length+`"
                        pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                        onchange="validateJS(event,'paragraphs')"
                        required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Entrada para el valor del detalle
                    ======================================--> 

                    <div class="col-12 col-lg-6 form-group__content input-group">

                        <div class="input-group-append">
                            <span class="input-group-text">
                                 Value:
                            </span>
                        </div>

                        <input
                        class="form-control" 
                        type="text"
                        name="detailsValueProduct_`+inputs.length+`"
                        pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                        onchange="validateJS(event,'paragraphs')"
                        required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                </div>

            `)
        }

        /*=============================================
        Adicionar entrada de especificación técnica del producto
        =============================================*/

        if(type == "inputSpecifications"){

            $(elem).before(`

                <div class="row mb-3 inputSpecifications">

                    <!--=====================================
                    Entrada para el título del detalle
                    ======================================--> 

                    <div class="col-12 col-lg-6 form-group__content input-group">
                         
                        <div class="input-group-append">
                            <span class="input-group-text">
                                 <button type="button" class="btn btn-danger" onclick="removeInput(`+inputs.length+`,'inputSpecifications')">&times;</button>
                            </span>
                        </div>

                        <div class="input-group-append">
                            <span class="input-group-text">
                                Type:
                            </span>
                        </div>

                        <input
                        class="form-control" 
                        type="text"
                        name="specificationsTypeProduct_`+inputs.length+`"
                        pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                        onchange="validateJS(event,'paragraphs')"
                        required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                    <!--=====================================
                    Entrada para el valor del detalle
                    ======================================--> 

                    <div class="col-12 col-lg-6 form-group__content input-group">

                        <input
                        class="form-control tags-input" 
                        data-role="tagsinput"
                        type="text"
                        name="specificationsValuesProduct_`+inputs.length+`"
                        placeholder="Type And Press Enter"
                        pattern='[-\\(\\)\\=\\%\\&\\$\\;\\_\\*\\"\\#\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]{1,}'
                        onchange="validateJS(event,'paragraphs')"
                        required>

                        <div class="valid-feedback">Valid.</div>
                        <div class="invalid-feedback">Please fill out this field.</div>

                    </div>

                </div>

            `)

            fncTagInput();
        }


        $('[name="'+type+'"]').val(inputs.length+1);

    }else{

        fncNotie(2,"Maximum 5 entries allowed");

        return;

    }

}

/*=============================================
Remover entradas al formulario de productos 
=============================================*/

function removeInput(index, type){

    var inputs = $("."+type);

    if(inputs.length > 1){

        inputs.each(i=>{

            if(i == index){

                $(inputs[i]).remove();
            }

        })

         $('[name="'+type+'"]').val(inputs.length-1);

    }else{

        fncNotie(3,"At least one entry must exist");

        return;
    }

}

/*=============================================
Tags Input
=============================================*/

function fncTagInput(){

    var target = $('.tags-input');

    if(target.length > 0){  
    
        $(target).tagsinput();
    }

}

fncTagInput();

/*=============================================
DropZone
=============================================*/

Dropzone.autoDiscover = false;

var arrayFiles = [];

 var countArrayFiles = 0;   

$(".dropzone").dropzone({

    url: "/",
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg, image/png",
    maxFilesize: 2,
    maxFiles: 10,
    init: function(){

        this.on("addedfile", function(file){

            countArrayFiles++;

            setTimeout(function(){

                arrayFiles.push({

                    "file":file.dataURL,
                    "type":file.type,
                    "width":file.width,
                    "height":file.height

                })

                $("[name='galleryProduct']").val(JSON.stringify(arrayFiles));

            },100*countArrayFiles);

        })

        this.on("removedfile", function(file){
            
            console.log("file", file);

            countArrayFiles++;

            setTimeout(function(){

                var index = arrayFiles.indexOf({

                    "file":file.dataURL,
                    "type":file.type,
                    "width":file.width,
                    "height":file.height

                })

                arrayFiles.splice(index, 1);

                $("[name='galleryProduct']").val(JSON.stringify(arrayFiles));

            },100*countArrayFiles);

        })

        myDropzone = this;

        $(".saveBtn").click(function(){

            if (arrayFiles.length >= 1) {

                myDropzone.processQueue();

            }else {
                fncSweetAlert("error", "The gallery cannot be empty", null)
            }

        })

    }

})

/*=============================================
Edición de Galería
=============================================*/

if($("[name='galleryProductOld']").length > 0 && $("[name='galleryProductOld']").val() != ""){

    var arrayFilesOld = JSON.parse($("[name='galleryProductOld']").val());

}

var arrayFilesDelete = Array();

function removeGallery(elem){

    $(elem).parent().remove();

    var index = arrayFilesOld.indexOf($(elem).attr("remove"));  
    
    arrayFilesOld.splice(index, 1);
    
    console.log("arrayFilesOld", arrayFilesOld);

    $("[name='galleryProductOld']").val(JSON.stringify(arrayFilesOld));

    arrayFilesDelete.push($(elem).attr("remove"));

    $("[name='deleteGalleryProduct']").val(JSON.stringify(arrayFilesDelete));

}


/*=============================================
Elegir tipo de oferta
=============================================*/

function changeOffer(type){

    if(type.target.value == "Discount"){

        $(".typeOffer").html("Percent %:");

    }

     if(type.target.value == "Fixed"){

        $(".typeOffer").html("Price $:");

    }

}

/*=============================================
Cambiar estado del producto
=============================================*/

function changeState(event, idProduct, idView){
    
    if(event.target.checked){

        var state = "show";

    }else{

      var state = "hidden";  
    
    }

    var token = localStorage.getItem("token_user");

     var settings = {
        "url": $("#urlApi").val()+"products?id="+idProduct+"&nameId=id_product&token="+token,
        "method": "PUT",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        "data": {
            "state_product": state
        }
    };

    /*=============================================
    Cuando la petición de AJAX devuelve el resultado correcto
    =============================================*/

    $.ajax(settings).done(function (response) {
       
        if(response.status == 200){}

    })

}


/*=============================================
Función para remover productos
=============================================*/

function removeProduct(idProduct){


    fncSweetAlert("confirm","Are you sure to delete this item?","").then(resp=>{

        if(resp){

            var data = new FormData();
            data.append("idProduct", idProduct);

            $.ajax({

                url: $("#path").val()+"ajax/delete-products.php",
                method: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (respose){
      
                    var settings = {
                        "url": $("#urlApi").val()+"products?id="+idProduct+"&nameId=id_product&token="+localStorage.getItem("token_user"),
                        "method": "DELETE",
                        "timeout": 0,
                        "headers": {
                            "Content-Type": "application/x-www-form-urlencoded"
                        }
                    };

                  $.ajax(settings).done(function (response) {

                        if(response.status == 200){

                            fncSweetAlert(
                                "success",
                                "The product has been deleted",
                                $("#path").val()+"account&my-store"
                            )
                        }

                    })

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + " " + errorThrown);
                }


            })

        }

    })

}

/*=============================================
Función para actualizar la orden
=============================================*/

$(document).on("click", ".nextProcess", function(){

     /*=============================================
    Limpiamos la ventana modal
    =============================================*/

    $(".orderBody").html("");

    /*=============================================
    Capturamos ID de la Orden, el nombre y email del cliente, el producto y su proceso
    =============================================*/

    var idStore = $(this).attr("idStore");
    var idOrder = $(this).attr("idOrder");
    var clientOrder =  $(this).attr("clientOrder");
    var emailOrder =  $(this).attr("emailOrder");
    var productOrder =  $(this).attr("productOrder");
    var processOrder = JSON.parse(atob($(this).attr("processOrder")));

    /*=============================================
    Nombramos la ventan modal con el id de la orden
    =============================================*/

    $(".modal-title span").html("Order N. "+idOrder);

    /*=============================================
    Quitamos la opción de llenar el campo de recibido si no se ha enviado el producto
    =============================================*/

    if(processOrder[1].status == "pending"){

        processOrder.splice(2,1); 
    }

    /*=============================================
    Información dinámica que aparecerá en la ventana modal
    =============================================*/

    processOrder.forEach((value,index)=>{

        let date = "";
        let status = "";
        let comment = "";

        if(value.status == "ok"){

            date = `<div class="col-10">
                        
                        <input type="date" class="form-control" value="`+value.date+`" readonly>

                    </div>`;

            status = `<div class="col-10 mt-3">

                        <div class="text-uppercase">`+value.status+`</div>

                    </div>`;

            comment = `<div class="col-10">   
                        <textarea class="form-control" readonly>`+value.comment+`</textarea>
                      </div>`;

        }else{

            date = `<div class="col-10">
                        
                        <input type="date" class="form-control" name="date" value="`+value.date+`" required>

                    </div>`;

            status = `<div class="col-10 mt-3">

                <input type="hidden" name="idStore" value="`+idStore+`">
                <input type="hidden" name="stage" value="`+value.stage+`">
                <input type="hidden" name="processOrder" value="`+$(this).attr("processOrder")+`">
                <input type="hidden" name="idOrder" value="`+idOrder+`">
                <input type="hidden" name="clientOrder" value="`+clientOrder+`">
                <input type="hidden" name="emailOrder" value="`+emailOrder+`">
                <input type="hidden" name="productOrder" value="`+productOrder+`">

                <div class="custom-control custom-radio custom-control-inline">

                     <input        
                        id="status-pending" 
                        type="radio" 
                        class="custom-control-input" 
                        value="pending" 
                        name="status" 
                        checked>

                        <label class="custom-control-label" for="status-pending">Pending</label>

                </div>

                <div class="custom-control custom-radio custom-control-inline">

                     <input        
                        id="status-ok" 
                        type="radio" 
                        class="custom-control-input" 
                        value="ok" 
                        name="status" 
                        >

                        <label class="custom-control-label" for="status-ok">Ok</label>

                </div>

            </div>`;

            comment = `<div class="col-10">   
                        <textarea class="form-control" name="comment" required>`+value.comment+`</textarea>
                      </div>`;

        }
        
        $(".orderBody").append(`

            <div class="card-header text-uppercase">`+value.stage+`</div>

            <div class="card-body">

                <!--=====================================
                Bloque Fecha
                ======================================-->

                <div class="form-row">

                    <div class="col-2 text-right">

                        <label class="p-3 lead">Date:</label>

                    </div>

                    `+date+`

                </div>

                <!--=====================================
                Bloque Status
                ======================================-->

                <div class="form-row">
                    
                    <div class="col-2 text-right">
                        <label class="p-3 lead">Status:</label>
                    </div>

                    `+status+`

                </div>

                <!--=====================================
                Bloque Comentarios
                ======================================-->

                <div class="form-row">

                    <div class="col-2 text-right">
                        <label class="p-3 lead">Comment:</label>
                    </div>

                    `+comment+`

                </div>

            </div>

        `);


    })

    /*=============================================
    Aparecemos la ventana Modal
    =============================================*/

    $("#nextProcess").modal()

})

/*=============================================
Función para crear una disputa
=============================================*/

$(document).on("click",".openDispute",function(){


    $("[name='idOrder']").val($(this).attr("idOrder"));
    $("[name='idUser']").val($(this).attr("idUser"));
    $("[name='idStore']").val($(this).attr("idStore"));
    $("[name='emailStore']").val($(this).attr("emailStore"));
    $("[name='nameStore']").val($(this).attr("nameStore"));

     /*=============================================
    Aparecemos la ventana Modal
    =============================================*/

    $("#newDispute").modal()


})

/*=============================================
Función para responder disputa
=============================================*/

$(document).on("click", ".answerDispute", function(){

    $("[name='idDispute']").val($(this).attr("idDispute"));
    $("[name='clientDispute']").val($(this).attr("clientDispute"));
    $("[name='emailDispute']").val($(this).attr("emailDispute"));

     /*=============================================
    Aparecemos la ventana Modal
    =============================================*/

    $("#answerDispute").modal()


})

/*=============================================
Función para responder mensaje
=============================================*/

$(document).on("click", ".answerMessage", function(){

    $("[name='idMessage']").val($(this).attr("idMessage"));
    $("[name='clientMessage']").val($(this).attr("clientMessage"));
    $("[name='emailMessage']").val($(this).attr("emailMessage"));
    $("[name='urlProduct']").val($(this).attr("urlProduct"));

     /*=============================================
    Aparecemos la ventana Modal
    =============================================*/

    $("#answerMessage").modal()

})

/*=============================================
Función para calificar un producto
=============================================*/

$(document).on("click", ".newReview", function(){

    $("[name='idProduct']").val($(this).attr("idProduct"));
    $("[name='idUser']").val($(this).attr("idUser"));

    /*=============================================
    Aparecemos la ventana Modal
    =============================================*/

    $("#newReview").modal()
})


/*=============================================
Función para cambiar de idioma
=============================================*/

function changeLang(lang){

    localStorage.setItem("yt-widget",`{"lang":"${lang}","active":true}`);

    window.open(window.location.href, '_top');

}




