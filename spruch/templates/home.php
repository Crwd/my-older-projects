<script src="js/quotesLoader.js"></script>
<script src="js/loadmore.js"></script>
<script src="js/validator.js"></script>

<div class="container-fluid">
    <div class="row">
        <div class="hidden-xs headerContainer">
            <div class="header col col-sm-12"></div>
        
            <div class="headerOverlay col col-sm-12 vcenter">
                <?php include_once(Config::PHP_PATH . '/inc/welcome.php'); ?>
            </div>
        </div>
    </div>
</div>

<div class="clearfix container">
    <div class="row postContainer">  
        <div class="col col-md-4 col-md-offset-4 text-center">
            <form class="postQuote">
                  <div class="input-group underline">
                    <span class="input-group-addon" id="basic-addon1">Name</span>
                    <input data-placement="top" data-validator="username" type="text" class="username form-control" placeholder="">
                  </div>
                
                  <div class="input-group">
                    <span class="input-group-addon" id="basic-addon1">Spruch</span>
                    <textarea data-validator="text" sizable="false" class="quoteInput form-control" placeholder=""></textarea>
                  </div>
                
                <button type="button" class="postButton form-control btn btn-success">Senden</button>
            </form>
        </div> <!-- [END: div - col-md-12] -->
    </div> <!-- [END: div - row] -->
    
    <div class="row">
        <div class="lastQuotes col col-md-4 col-md-offset-4">
            <hr>
            <?php include_once(Config::PHP_PATH . '/inc/quotes.php'); ?>
            <div class='col col-md-12 ajaxLoader' style='display:none;'>
                <div class="row">
                    <div class="col col-md-6 col-md-offset-3">
                        <div class="uil-facebook-css">
                            <div class="loadingBars">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div><!-- [END: div - row] -->
    <div class="row">
        <div class="col col-md-4 col-md-offset-4">
            <button data-role="loader" class="btn btn-default loadmore">Mehr laden</button>
        </div>
    </div>
</div> <!-- [END: div - container] -->