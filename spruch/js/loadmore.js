/* 
 @Project: SpruchUniversum
 @Author: INCepted
 @Version: 0.1.2
 @Date: 04 Apr '16
*/

$(document).ready(function() {
    var AJAX_LOADING = false;
    $('.loadmore[data-role=loader]').click(function() {
        var LOADED_ITEMS = $('.lastQuotes > .quoteBox').length;

        if(!AJAX_LOADING) {
            AJAX_LOADING = true;
            $.ajax({
                url: 'ajax/loadmore.php',
                cache: false,
                beforeSend: function() {
                    $('.ajaxLoader').show();
                    $('.ajaxLoader').appendTo('.lastQuotes');
                },
                complete: function(xhr) {
                    $('.ajaxLoader').hide();
                    AJAX_LOADING = false;
                    var data = $.parseJSON(xhr.responseText);

                    for(i in data) {
                        $('.lastQuotes > .quoteBox:last').after(data[i]);
                    }
                },
                type: 'POST',
                data: {items: LOADED_ITEMS}
            });
        }
    });
});
