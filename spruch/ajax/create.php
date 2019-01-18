<?php
sleep(1);
if(isset($_POST['name'], $_POST['content'])) {
    require_once('../classes/Config.php');
    $quoteControl = new Quotes();
    
    $name = trim(htmlentities($_POST['name']));
    $content = trim(htmlentities($_POST['content']));
    $time = time();
    
    $data = [
        "status" => "failed",
        "name" => $name,
        "quote" => $content,
        "time" => $time
    ];
    
    if(strlen($name) >= 3 && strlen($name) <= 25) {
         if(strlen($content) >= 5 && strlen($content) <= 100) {
             $quoteControl->addQuote($data);
             $data["status"] = "success";
             $data["time"] = $quoteControl->getDate($time);
         }
    }
    
    echo json_encode($data);
}
