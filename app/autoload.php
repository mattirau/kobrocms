<?php

function kobroAutoload($className) {
    $appFolder = ROOT . '/app/';
    
    $directories = array(
        "controllers",
        "modules",
        "templates",
        "classes"
    );
    
    foreach ($directories as $directory) {

        $classFilename = $appFolder . "/" . $directory . "/" . $className . ".php";
        
        if (file_exists($classFilename)) {
            require_once($classFilename);
        }
    }
}

?>