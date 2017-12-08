<?php
error_reporting(E_ALL);
@session_start();

require(realpath(__DIR__ . "/../incs/pre.inc"));
require(realpath(__DIR__ . "/../incs/class_user.inc"));
require(realpath(__DIR__ . "/../incs/class_page.inc"));

db_init();

mysql_query("SET NAMES 'utf8'");

include('autoload.php');

$apiEngine = new ApiEngine();
$apiEngine->call();
