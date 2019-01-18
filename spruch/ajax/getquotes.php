<?php
if(isset($_POST['id'])) {
    require_once('../classes/Config.php');
    $quoteControl = new Quotes();
    $data = $quoteControl->getNewQuotes($_POST['id']);
    $content = [
        "current_id" => $data[0]['ID'],
        "quotes" => []
    ];
    
    foreach($data as $quote) {
        $date = $quoteControl->getDate($quote['time']);

        array_push($content["quotes"],'<div class="quoteBox col col-md-12">
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
