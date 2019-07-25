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

    public function getImages($onlyFeatured ,$amount, $offset){
      $res = [array(), 0];    // First its the results, and the second its the number of results that satifies

      $filterFeatured = ($onlyFeatured)? "WHERE `featured` = true ": "";

      $sql = "SELECT `id`, `path`, `filename_final`, `filename_original`, `filename_thumb`, `date_obs`, `observatory_id`,
 `filesize_processed`, `date_upload`, `date_updated` FROM `cesar-archive-images` " . $filterFeatured .
 "AND `id` IN (SELECT `image_id` FROM `cesar-archive-images-metadata`
   WHERE (`metadata_id` = 23 AND `value` = 'False') ) ORDER BY `date_obs` DESC LIMIT " . $amount .
 " OFFSET " . $offset;

      if(DEBUG){ echo "<p>" . $sql . "</p>"; }

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
          array_push($res[0], $obj);
        }
      } else {
        error_log("Ninguna coincidencia", 0);
        return NULL;
      }

      // Then we have to count the results, the same query with a counter and without the limit
      $sql = "SELECT COUNT(*) FROM `cesar-archive-images`";

      if(DEBUG){ echo "<p>" . $sql . "</p>"; }

      if (!$resultado = $this->conn->query($sql)) {
        error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
        exit;
      }
      if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
          $res[1] = $row["COUNT(*)"];
        }
      } else {
        error_log("Ninguna coincidencia", 0);
        return NULL;
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

  public function advanceSearch($source, $obsId, $filter, $since, $until, $discardDark, $onlyFeatured, $order, $amount, $offset){      // Return the requested image ID's
    $res = [array(), 0];    // First its the results, and the second its the number of results that satifies
    $since = str_replace('/', '-', $since);    // Format date, SRC: https://bit.ly/2YRdKAw
    $until = str_replace('/', '-', $until);

    // Ensure that if some parameters are NULL, there is not in the query
    // Creating queries like these are difficult to read, but easy to control it parameters are NULL, consider changing this in the future
    $sqlMeta = "SELECT `image_id` FROM `cesar-archive-images-metadata` ";
    $sqlMeta .= ($discardDark)? "WHERE (`metadata_id` = 23 AND `value` = 'False') ":"" ;
    $sqlMetaSourceBase = "`image_id` IN (SELECT `image_id` FROM `cesar-archive-images-metadata` WHERE (`metadata_id` = 34 AND `value` = '" . ucfirst($source ). "')) ";
    $sqlMeta .= ($source != NULL) ? (($discardDark) ? "AND " . $sqlMetaSourceBase :  "WHERE " . $sqlMetaSourceBase) : "";
    $sqlMetaFilterBase = "`image_id` IN (SELECT `image_id` FROM `cesar-archive-images-metadata` WHERE (`metadata_id` = 28 AND `value` =  '" . strtolower($filter) . "')) ";
    $sqlMeta .= ($filter != NULL) ? (($source != NULL || $discardDark) ? "AND " . $sqlMetaFilterBase :  "WHERE " . $sqlMetaFilterBase) : "";

    if(DEBUG){ echo "<p>" . $sqlMeta . "</p>"; }

    $sqlSel = "SELECT `id`, `path`, `filename_final`, `filename_original`,
      `filename_thumb`, `date_obs`, `observatory_id`, `filesize_processed`,
      `date_upload`, `date_updated` FROM `cesar-archive-images` ";
    $filterFeatured = ($onlyFeatured)? "WHERE `featured` = true ": "";
    $sqlWheBase = "`id` IN (" . $sqlMeta . ") ";
    $sqlWhe = ($onlyFeatured)? "AND " . $sqlWheBase : "WHERE " . $sqlWheBase ;
    $sqlObs = ($obsId != NULL) ? "AND `observatory_id` = " . $obsId . " ": "";

    // Then filter by observatory
    if($since == NULL && $until == NULL){
      $sqlDate = "";
    } elseif ($since == NULL && $until != NULL) {
      $sqlDate = "AND `date_obs` < '" . date( 'Y-m-d H:i:s', strtotime($until)) ."' ";
    } elseif ($since != NULL && $until == NULL) {
      $sqlDate = "AND `date_obs` > '" . date( 'Y-m-d H:i:s', strtotime($since)) . "' ";
    } else {
      $sqlDate = "AND `date_obs` BETWEEN '" . date( 'Y-m-d H:i:s', strtotime($since)) .
      "' AND '" .  date( 'Y-m-d H:i:s', strtotime($until)) . "' ";
    }

    $sqlOrd = ($order != NULL) ? (($order == "asc")? "ORDER BY `date_obs` ASC ": "ORDER BY `date_obs` DESC "): "";
    $sqlLimit = ($amount != NULL) ? "LIMIT " . $amount . " " : "LIMIT 12";
    $sqlOffset = ($offset != NULL) ? "OFFSET " . $offset : "";

    $sql = $sqlSel . $filterFeatured . $sqlWhe . $sqlObs . $sqlDate . $sqlOrd . $sqlLimit . $sqlOffset;

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

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
        array_push($res[0], $obj);
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      return NULL;
    }

    $sqlSel = "SELECT COUNT(*) FROM `cesar-archive-images` ";
    // Then we have to count the results, the same query with a counter and without the limit
    $sql = $sqlSel . $filterFeatured . $sqlWhe . $sqlObs . $sqlDate;

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        $res[1] = $row["COUNT(*)"];
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      return NULL;
    }

    return $res;
  }

}
