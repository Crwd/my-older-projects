<?php
sleep(1);
if(isset($_POST['items'])) {
    require_once('../classes/Config.php');
    $quoteControl = new Quotes();
    $data = $quoteControl->loadMore($_POST['items'], 5);
    $content = [];
    
    foreach($data as $quote) {
        $date = $quoteControl->getDate($quote['time']);

        array_push($content,'<div class="quoteBox col col-md-12">
            <div class="row quoteHeader">
                <span class="col col-md-12">
                    <span class="headline"><b>' . $quote['author'] . '</b> &middot; ' . $date . '</span>
                </span>
            </div>

            <div class="row quoteContent">
                <p class="col col-md-12">' . $quote['content'] . '</p>
            </div>

            <div class="row quoteStats">
                <p class="col col-md-12"><span class="likeIcon glyphicon glyphicon-thumbs-up"></span> <span class="likeCount">' . $quote['likes'] . '</span></p>
            </div>
        </div>');
    }
    
    echo json_encode($content);
}

