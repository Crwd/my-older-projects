/* 
 @Project: SpruchUniversum
 @Author: INCepted
 @Version: 0.1.2
 @Date: 04 Apr '16
*/

var quotesLoader = $(document).ready(function() {
    var CURRENT_ID = $('[loaderID=show]:eq(0)').textContent;
    var LOAD_REQUEST = true;
    
    $('[loaderID=show]').remove();

    quotesLoader.loadQuotes = function() {
        if(LOAD_REQUEST) {
            LOAD_REQUEST = false;
            $.post('ajax/getquotes.php', {id: CURRENT_ID}, function(res) {
                if(res) {
                    var data = $.parseJSON(res);
                    CURRENT_ID = res.current_id;
                    data.quotes.reverse();
                    LOAD_REQUEST = true;
                    
                    for(i in data.quotes) {
                        $('.lastQuotes > hr').after(data.quotes[i]);
                    }
                }
            });
        }
    }

    setInterval(quotesLoader.loadQuotes, 60000);
});
