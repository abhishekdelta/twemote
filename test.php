<?php
require_once(__DIR__.'/TwemoteManager.php');
/*
$obj = new TwemoteManager('#browse cd /var/www/workspace/');
$obj->run();

$obj = new TwemoteManager('#browse cd ./comet');
$obj->run();
*/
$obj = new TwemoteManager('#browse line config.ini');
$obj->run();


?>
