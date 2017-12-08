<?php

class JsonFormatter extends Formatter
{
    public function format($data) {
		return json_encode( $data );
    }
}