<?php

include './lib/conf.php';
include './lib/CesarDatabase.php';
include './lib/CesarImage.php';
include './lib/CesarMetadata.php';
include './lib/CesarObservatory.php';

$conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, "cesar-helios-observation");
// Check connection
if ($conn->connect_error) {
    error_log("Could not connect to mysql database", 0);
    die("Connection failed: " . $conn->connect_error);
}

$resAlpha = array();   // Array con los path de las fotos destacadas ALPHA
$resVisible = array();   // Array con los path de las fotos destacadas Visible

$sql = "SELECT `bestalpha`, `bestvisible` FROM `cesar-helios-observation` WHERE `bestvisible` != \"\"";

if (!$resultado = $conn->query($sql)) {
  error_log("Could not connect to mysql database. Errno:" . $conn->errno, 0);
  exit;
}
if ($resultado->num_rows > 0) {
  while($row = $resultado->fetch_assoc()) {
    array_push($resAlpha, $row["bestalpha"]);
    array_push($resVisible, $row["bestvisible"]);
  }
} else {
  error_log("Ninguna coincidencia", 0);
  echo "Ninguna coincidencia";
  exit;
}

/*
foreach($resAlpha as $i){
  echo $i . "<br>";
}

foreach($resVisible as $i){
  echo $i . "<br>";
}
*/

$conn = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
// Check connection
if ($conn->connect_error) {
    error_log("Could not connect to mysql database", 0);
    die("Connection failed: " . $conn->connect_error);
}

foreach ($resVisible as $thumb) {
  $sql = "UPDATE `cesar-archive-images` SET `featured` = true WHERE `filename_thumb`
   = \"" . $thumb . "\"";

   if (!$resultado = $conn->query($sql)) {
     error_log("Could not connect to mysql database. Errno:" . $conn->errno, 0);
     exit;
   }
}


foreach ($resAlpha as $thumb) {
  $sql = "UPDATE `cesar-archive-images` SET `featured` = true WHERE `filename_thumb`
   = \"" . $thumb . "\"";

   if (!$resultado = $conn->query($sql)) {
     error_log("Could not connect to mysql database. Errno:" . $conn->errno, 0);
     exit;
   }
}

?>
