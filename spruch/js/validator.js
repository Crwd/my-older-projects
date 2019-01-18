/* 
 @Project: SpruchUniversum
 @Author: INCepted
 @Version: 0.1.2
 @Date: 04 Apr '16
*/

$(document).ready(function() {
    $('[data-validator]').data('valid', false);
    
    var DATA_VALIDATIONS = {
        "username":  {
            "max": 25,
            "min": 3
        },
        "text": {
            "max": 100,
            "min": 5
        }
    };
        
    var ERRORS = [];

    function checkSubmit(form) {
        var valid = true;
        $(form + " [data-validator]").each(function(key, val) {
            if(!$(val).data('valid')) {
                valid =  false;
            }
        });

      return valid;
    }

    function getQuotebox(name, quote, time, likes) {
        var element = '<div class="quoteBox col col-md-12"><div class="row quoteHeader"><span class="col col-md-12"><span class="headline"><b>'  + name + '</b> &middot; ' + time +  '</span></span></div> <div class="row quoteContent"><p class="col col-md-12">' + quote + '</p></div><div class="row quoteStats"><p class="col col-md-12"><span class="likeIcon glyphicon glyphicon-thumbs-up"></span> <span class="likeCount">' + likes + '</span></p></div></div>';
        return element;
    }
    
    var AJAX_LOADING = false;
    
    $('button.postButton').click(function() {
       if(checkSubmit('form.postQuote')) {
           if(!AJAX_LOADING) {
                AJAX_LOADING = true;
                $.ajax({
                    type: "POST",
                    url: "ajax/create.php",
                    cache: false,
                    beforeSend: function() {
                        $('.ajaxLoader').show();
                        $('form.postQuote').hide();
                        $('.ajaxLoader').prependTo('.postContainer');
                    },
                    data: {
                        name: $('form.postQuote .username').val(), 
                        content: $('form.postQuote .quoteInput').val()
                    },
                    complete: function() {
                        AJAX_LOADING = false;
                        $('.ajaxLoader').hide();
                        $('form.postQuote').show();
                    },

                    success: function(data) {
                        quotesLoader.loadQuotes();
                        var data = $.parseJSON(data);
                        if(data.status === "success") {
                            var box = $(getQuotebox(data.name, data.quote, data.time, 0));
                            $(box).hide().insertAfter('.lastQuotes hr').fadeIn(500);
                        }
                    }
                });
            }
       }
    });
        
    $('[data-validator]').on('input', function() {
        ERRORS = [];
        
        var tooltip_position = { my: 'left center', at: 'right+10 center' };
        tooltip_position.collision = 'none';
        
        $(this).tooltip();
        $(this).tooltip('disable');

        if($.inArray($(this).attr('data-validator'), DATA_VALIDATIONS)) {
            var input = $(this).val().length;
            var key = $(this).attr('data-validator');

            if(input < DATA_VALIDATIONS[key]["min"]) {
                ERRORS.push('Mindestens ' + DATA_VALIDATIONS[key]["min"] + ' Zeichen');
            }

            if(input > DATA_VALIDATIONS[key]["max"]) {
                ERRORS.push('Maximal ' + DATA_VALIDATIONS[key]["max"] + ' Zeichen');
            }

            if($(ERRORS).size()) {
                with($(this)) {
                    removeClass('colorSucces');
                    siblings('span').removeClass('bgSuccess');
                    
                    data('tooltip', false);
                    data('bs.tooltip', false);
                    attr('title', ERRORS[0]);
                    prop('title', ERRORS[0]);
                    attr('data-original-title', ERRORS[0]);
                    
                    tooltip({
                        disabled: false,
                        position: tooltip_position,
                        tooltipClass: "right",
                        title: ERRORS[0],
                        content: ERRORS[0]
                    }); 
                    
                    addClass('colorError', 200);
                    siblings('span').addClass('bgError', 200);
                    data("valid", false);
                }
            } else {
                with($(this)) {
                    removeClass('colorError');
                    siblings('span').removeClass('bgError');
                    addClass('colorSuccess', 200);
                    siblings('span').addClass('bgSuccess', 200);
                    data("valid", true);
                }
            }
        }
    });
});