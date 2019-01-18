<?php 
    $quoteControl = new Quotes();
    if($quoteControl->getTopquote()) {
        return include(Config::PHP_PATH . '/inc/topquote.php');
    }
?>
<div class="welcome row">
    <div class="col col-sm-6 col-sm-offset-3 text-center">
        <span class="title"><?php echo Config::SITE_NAME; ?></span>
        <div class="col col-md-6 col-md-offset-3">
            <p>Willkommen im <?php echo Config::SITE_NAME; ?>! Verfasse & lese die besten
            Sprüche!</p>
        </div>
    </div>
</div>
