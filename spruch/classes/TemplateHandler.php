<?php
final class TemplateHandler {
    public $templates = [];
    public $current;
    
    public function __construct($data) {
        if(is_array($data)) {
            $this->templates = $data;
        }
        
        $default = [
            "name" => "home",
            "file" => "home.php"
        ];
          
        $this->templates["/"] = $default;
        
        $this->current = $this->templates["/"];
        
        if(isset($_GET['site'])) {
            $valid = false;
            $site = $_GET['site'];
            
            foreach($this->templates as $tpl) {
                if(array_search($site, $tpl, true)) {
                    $valid = true;
                    $this->current = $tpl;
                }
            }
            
            if(!$valid) {
                header('Location:' . Config::PATH);
            }
        }
    }
     
    public function display() {
        $file = $this->current['file'];
        return (Config::TEMPLATE_PATH . "/" . $file);
    }
    
    public function add($tpl, $name) {
        if(is_array($tpl)) {
            $this->templates[$name] = $tpl;
        }
    }
    
    public function remove($name) {
        if(isset($this->templates[$name])) {
            unset($this->templates[$name]);
        }
    } 
}

