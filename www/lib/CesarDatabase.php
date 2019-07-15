<?php

/**
 * Class CesarDatabase
 *
 * Class for accessing the database
 *
 * @author Fran Acien (https://github.com/acien101)
 */


class CesarDatabase{

    private $conn;

    /**
     * cesarDatabase constructor.
     */
    public function __construct($host, $user, $pass, $database){
        // Create connection
        $this->conn = new mysqli($host, $user, $pass, $database);
        // Check connection
        if ($this->conn->connect_error) {
            error_log("Could not connect to mysql database", 0);
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getLastImages($amount){
      $res = array();

      $sql = "SELECT `id`, `path`, `filename_final`, `filename_original`, `filename_thumb`, `date_obs`, `observatory_id`,
 `filesize_processed`, `date_upload`, `date_updated` FROM `cesar-archive-images` ORDER BY `date_obs` DESC LIMIT " . $amount;
      if (!$resultado = $this->conn->query($sql)) {
        error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
        exit;
      }
      if ($resultado->num_rows > 0) {
          while($row = $resultado->fetch_assoc()) {

              $obj = new CesarImage($row["id"], $row["path"], $row["filename_final"],
                $row["filename_original"], $row["filename_thumb"], $row["date_obs"], $row["filesize_processed"],
                $row["date_updated"], $row["vists"], $row["tags"], $row["date_upload"]);

              $obj->setMetadata($this->getMetadata($row["id"]));  //Adding metedata to obj

              $obj->setObservatory($this->getObservatoryById($row["observatory_id"]));
              array_push($res, $obj);
          }
      } else {
        error_log("Ninguna coincidencia", 0);
        exit;
      }

      return $res;

    }

    public function &getMetadata($imageId){
      $res = new CesarMetadata();

      $sql = "SELECT `metadata_id`, `value` FROM `cesar-archive-images-metadata` WHERE `image_id` = " . $imageId;
      if (!$resultado = $this->conn->query($sql)) {
        error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
        exit;
      }
      if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
          $res ->setById($row["metadata_id"], $row["value"]);
        }
      } else {
        error_log("Ninguna coincidencia", 0);
        exit;
      }

      return $res;
    }

  public function &getObservatoryById($observatoryId){
    $res = NULL;

    $sql = " SELECT `id`, `name`, `shortdescription`, `sectionurl`, `datecreated`, `dateupdated`, `iduserupdate`, 
            `loginuserupdate` FROM `cesar-archive-observatories` WHERE `id` = " . $observatoryId;
    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        $res = new CesarObservatory($row["id"], $row["name"], $row["shortdescription"], $row["sectionurl"],
          $row["datecreated"], $row["dateupdated"], $row["iduserupdate"], $row["loginuserupdate"]);
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      exit;
    }

    return $res;
  }

  public function &getImageById($id){
    $res = NULL;

    $sql = "SELECT `id`, `path`, `filename_final`, `filename_original`, `filename_thumb`, `date_obs`, `observatory_id`,
 `filesize_processed`, `date_upload`, `date_updated` FROM `cesar-archive-images` WHERE `id` = " . $id;
    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        $res = new CesarImage($row["id"], $row["path"], $row["filename_final"],
          $row["filename_original"], $row["filename_thumb"], $row["date_obs"], $row["filesize_processed"],
          $row["date_updated"], $row["vists"], $row["tags"], $row["date_upload"]);

        $res->setMetadata($this->getMetadata($row["id"]));  //Adding metedata to obj

        $res->setObservatory($this->getObservatoryById($row["observatory_id"]));
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      exit;
    }

    return $res;
  }

  public function getObservatoryNames(){
    $res = array();

    $sql = "SELECT `name` FROM `cesar-archive-observatories` ORDER BY `name`;";
    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        array_push($res, $row['name']);
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      exit;
    }

    return $res;
  }

  public function getFiltersNames(){
    $res = array();

    $sql = "SELECT `name` FROM `cesar-archive-observatories` ORDER BY `name`;";
    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        array_push($res, $row['name']);
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      exit;
    }

    return $res;
  }

  public function advanceSearch($source, $obsId, $filter, $amount){      // Return the requested image ID's
    $res = array();

    // First, filter by metadata
    $ids = array();
    $sql = "SELECT `image_id` FROM `cesar-archive-images-metadata` WHERE (`metadata_id` = 34 AND `value` = '" . ucfirst($source ). "') AND `image_id` IN (SELECT `image_id` FROM `cesar-archive-images-metadata` WHERE
 (`metadata_id` = 28 AND `value` =  '" . strtolower($filter) . "')) LIMIT " . $amount;
    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        array_push($ids, $row["image_id"]);
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      return NULL;
    }

    // Then filter by observatory
    $sql = "SELECT `id`, `path`, `filename_final`, `filename_original`, `filename_thumb`, `date_obs`, `observatory_id`,
 `filesize_processed`, `date_upload`, `date_updated` FROM `cesar-archive-images` WHERE `id` IN (" . implode(',', $ids) . ") AND `observatory_id` = " . $obsId . " ORDER BY `date_obs` DESC LIMIT " . $amount;
    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {

        $obj = new CesarImage($row["id"], $row["path"], $row["filename_final"],
          $row["filename_original"], $row["filename_thumb"], $row["date_obs"], $row["filesize_processed"],
          $row["date_updated"], $row["vists"], $row["tags"], $row["date_upload"]);

        $obj->setMetadata($this->getMetadata($row["id"]));  //Adding metedata to obj

        $obj->setObservatory($this->getObservatoryById($row["observatory_id"]));
        array_push($res, $obj);
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      return NULL;
    }

    return $res;
  }

}
