<?php
$tour['dttm_begin'] = date('Y-m-d H:i');
$a = strtotime("+1 minute ".$tour['dttm_begin']);
echo date("Y-m-d H:i",$a);