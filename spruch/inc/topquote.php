<?php
    // $quoteControl defined in welcome.php (Quotes class)
    $data = $quoteControl->getTopquote();
?>
<div class="quote-of-day row">
    <div class="col col-sm-6 col-sm-offset-3 text-center">
        <span class="title">Spruch des Tages:</span>
        <div class="quoteDay col col-md-6 col-md-offset-3">
            <p><?php echo $data['content']; ?></p>
            <span>~ <?php echo $data['author'] . ' <span style="font-size:0.7em; ">(' . $quoteControl->getDate($data['time']) . ')</span>'; ?></span>
        </div>
    </div>
</div>
