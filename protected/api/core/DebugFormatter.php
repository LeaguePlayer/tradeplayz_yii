<?php

class DebugFormatter extends Formatter
{
    public function format($data)
    {
        ob_start();
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        $result = ob_get_clean();
        return $result;
    }
}