<?php

include 'lib/conf.php';
include 'lib/CesarDatabase.php';


// Check if already is that rate, if exist, update
$obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);

$obj->addVisitVideo($_POST["id"]);

?>
