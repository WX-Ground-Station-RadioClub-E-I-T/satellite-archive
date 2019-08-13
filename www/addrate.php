<?php

include 'lib/conf.php';
include 'lib/CesarDatabase.php';


// Check if already is that rate, if exist, update
$obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
$exist = $obj->existRate($_POST["id"], $_SERVER['REMOTE_ADDR']);

if($exist){   // If exist, update the current rate
  $obj->updateRate($_POST["id"], $_POST["value"], $_SERVER['REMOTE_ADDR']);
}
else{       // If not exist, insert a rate
  $obj->insertRate($_POST["id"], $_POST["value"], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
}

// Update the average rate at `cesar-archive-images-rates`
$obj->updateAvrRate($_POST["id"]);

?>
