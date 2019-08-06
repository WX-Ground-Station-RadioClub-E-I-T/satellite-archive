<?php
include 'lib/conf.php';
include 'lib/CesarDatabase.php';
include 'lib/CesarImage.php';
include 'lib/CesarMetadata.php' ;
include 'lib/CesarObservatory.php';

$imageId = $_GET["id"];
$obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
$pic = $obj->getImageById($imageId);

header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="file.csv";');

$f = fopen('php://output', 'w');

fputcsv($f, $pic->jsonSerialize());

fclose($f);

?>
