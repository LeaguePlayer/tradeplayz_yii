<?php
    spl_autoload_register ('autoload');
    function autoload ($className) {
        $fileName = $className . '.php';
        if ( is_file('core/'.$fileName) ) {
            include_once('core/'.$fileName);
        } elseif ( is_file('modules/'.$fileName) ) {
            include_once('modules/'.$fileName);
        } else {
            throw new ApiException("Class $className does not exist");
        }
    }
?>