var jqueryPlugins = {
    init: function () {
        this.foundation();
        this.magnificPopup();
        this.fitVids();
    },

    // Read more: https://github.com/zurb/foundation
    foundation: function () {
        $(document).foundation();
    },

    // Read more: https://github.com/dimsemenov/Magnific-Popup
    magnificPopup: function () {
        $('.popup').each(function () {
            $(this).magnificPopup({
                delegate: 'a',
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        });
    },

    // Read more: https://github.com/davatron5000/FitVids.js
    fitVids: function () {
        $('article').fitVids();
    }
};

var tweaks = {
    init: function () {
        this.topBar();

        if (!this.isMobile()) {
            this.backToTop();
            this.heroParallax();
        }
    },

    // Check screen size
    isMobile: function () {
        return (matchMedia(Foundation.media_queries.small).matches || matchMedia(Foundation.media_queries.medium).matches) && !matchMedia(Foundation.media_queries.large).matches;
    },

    // Back to top button
    backToTop: function () {
        var $button = $('#back-top');

        // Add HTML to the page, if it isn't already there.
        if ($button.length === 0) {
            $('body').append('<a id="back-top" class="animated" href="#"><i class="fa fa-caret-up"></i></a>');
            $button = $('#back-top');
        }

        if ($(document).scrollTop() >= 200) {
            $button.stop().show(0, function () {
                $(this).removeClass('fadeOutUp').addClass('fadeInDown');
            });
        } else {
            $button.stop().removeClass('fadeInDown').addClass('fadeOutUp');
        }
    },

    // Parallax functionality for homepage hero
    heroParallax: function () {
        var $hero = $('.hero');
        var $msg = $('.hero-message');

        // Don't run if not needed
        if ($hero.length === 0) return;

        // Set background image position
        var top = ($(window).scrollTop() / 3) - ($('.top-bar').height() / 2);
        $hero.css('backgroundPosition', 'center ' + top + 'px');

        // Add initial fade in
        $msg.addClass('fadeInDown animated');

        // Add classes on scroll up or down
        if ($(document).scrollTop() >= 250) {
            $msg.removeClass('fadeInUp fadeInDown').addClass('fadeOutDown');
        } else if ($(document).scrollTop() < 250 && $msg.hasClass('fadeOutDown')) {
            $msg.removeClass('fadeOutDown').addClass('fadeInUp');
        }
    },

    goToTop: function () {
        $('html, body').animate({
            scrollTop: 0
        }, 600);
    },

    topBar: function () {
        $(document).on('click.fndtn.topbar', '[data-topbar]', function () {
            $(this).find('.title.back h5 a').unwrap();
        });
    },

    showPopupImageOverlay: function ($el) {
        $el.before('<div class="popup-overlay"><i class="fa fa-search-plus"></i></div>');

        var $overlay = $('.popup-overlay');
        $overlay.stop(true, true).fadeIn(300);

        var overlayHeight = $el.height();
        var $icon = $overlay.find('i');

        $overlay.css('fontSize', overlayHeight * 0.2);
        $icon.css('top', (overlayHeight / 2) - ($icon.height() / 2));
        $icon.css('left', ($el.width() / 2) - ($icon.width() / 2));
    },

    hidePopupImageOverlay: function () {
        $('.popup-overlay').stop(true, true).fadeOut(150, function () {
            $(this).remove();
        });
    }
};

(function ($) {
    $(document).ready(function () {
        jqueryPlugins.init();
        tweaks.init();

        $(document).on('scroll', function () {
            tweaks.init();
        });

        // Handle the #back-top button click
        $(document).on('click', '#back-top', function (e) {
            e.preventDefault();

            tweaks.goToTop();
        });

        // Popup image overlay
        if (!tweaks.isMobile()) {
            $(document).on('mouseenter', '.popup a img', function () {
                tweaks.showPopupImageOverlay($(this));
            });
            $(document).on('mouseleave', '.popup a ', function () {
                tweaks.hidePopupImageOverlay();
            });
        }

        // Contact form
        $('[data-abide="ajax"]').on('valid.fndtn.abide', function () {
            var $form = $(this);

            $form.addClass('bounceOut animated');
            $form.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $form.hide(0, function () {
                    $('.spinner-wrapper').show();

                    $.ajax({
                        type: $form.attr('type'),
                        url: $form.attr('action'),
                        data: $form.serialize()
                    })
                        .done(function (result) {
                            $('.spinner-wrapper').hide();

                            if (result.responseText !== undefined) {
                                var response = JSON.parse(result.responseText);

                                if (response.status !== undefined && response.status === 'success') {
                                    $('.result-success').show();
                                    return;
                                }
                            }

                            $('.result-error').show();
                        })
                        .error(function () {
                            $('.result-error').show();
                        });
                });
            });
        });
    });
})(jQuery);
