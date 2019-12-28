<?php

include 'lib/conf.php';
include 'lib/ArchiveDatabase.php';
include 'lib/ArchiveImage.php';
include 'lib/ArchiveMetadata.php' ;
include 'lib/ArchiveObservatory.php';

$imageId = $_GET["id"];
$obj = new ArchiveDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
$pic = $obj->getImageById($imageId);

header('Content-disposition: attachment; filename=file.json');
header('Content-type: application/json');

echo json_encode($pic);
