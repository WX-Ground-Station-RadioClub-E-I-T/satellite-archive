<?php

include 'lib/conf.php';
include 'lib/CesarDatabase.php';
include 'lib/CesarImage.php';
include 'lib/CesarMetadata.php' ;
include 'lib/CesarObservatory.php';

$imageId = $_GET["id"];
$obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
$pic = $obj->getImageById($imageId);

header('Content-disposition: attachment; filename=file.json');
header('Content-type: application/json');

echo json_encode($pic);
