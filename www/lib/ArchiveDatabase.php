<?php

/**
 * Class ArchiveDatabase
 *
 * Class for accessing the database
 *
 * @author Fran Acien (https://github.com/acien101)
 */


class ArchiveDatabase{

    private $conn;

    /**
     * archiveDatabase constructor.
     */
    public function __construct($host, $user, $pass, $database){
        // Create connection
        $this->conn = new mysqli($host, $user, $pass, $database);
        // Check connection
        if ($this->conn->connect_error) {
            error_log("Could not connect to mysql database", 0);
            die("Connection failed: " . $this->conn->connect_error);
        }

        /* cambiar el conjunto de caracteres a utf8 */
        if (!$this->conn->set_charset("utf8")) {
          printf("Error cargando el conjunto de caracteres utf8: %s\n", $this->conn->error);
          exit();
        }
    }

    public function getLastImages($amount){
      $res = array();

      $sql = "SELECT `id`, `path`, `filename_final`, `filename_original`, `filename_thumb`, `date_obs`, `observatory_id`,
 `filesize_processed`, `date_upload`, `date_updated`, `rate` FROM `archive-images` ORDER BY `date_obs` DESC LIMIT " . $amount;
      if (!$resultado = $this->conn->query($sql)) {
        error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
        exit;
      }
      if ($resultado->num_rows > 0) {
          while($row = $resultado->fetch_assoc()) {

              $obj = new ArchiveImage($row["id"], $row["path"], $row["filename_final"],
                $row["filename_original"], $row["filename_thumb"], $row["date_obs"], $row["filesize_processed"],
                $row["date_updated"], $row["vists"], $row["tags"], $row["date_upload"], $row["rate"]);

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
 `filesize_processed`, `date_upload`, `date_updated`, `rate` FROM `archive-images` " . $filterFeatured .
 "AND `id` IN (SELECT `image_id` FROM `archive-images-metadata`
   WHERE (`metadata_id` = 23 AND `value` = 'False') ) ORDER BY `date_obs` DESC LIMIT " . $amount .
 " OFFSET " . $offset;

      if(DEBUG){ echo "<p>" . $sql . "</p>"; }

      if (!$resultado = $this->conn->query($sql)) {
        error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
        exit;
      }
      if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
          $obj = new ArchiveImage($row["id"], $row["path"], $row["filename_final"],
            $row["filename_original"], $row["filename_thumb"], $row["date_obs"], $row["filesize_processed"],
            $row["date_updated"], $row["vists"], $row["tags"], $row["date_upload"], $row["rate"]);

          $obj->setMetadata($this->getMetadata($row["id"]));  //Adding metedata to obj

          $obj->setObservatory($this->getObservatoryById($row["observatory_id"]));
          array_push($res[0], $obj);
        }
      } else {
        error_log("Ninguna coincidencia", 0);
        return NULL;
      }

      // Then we have to count the results, the same query with a counter and without the limit
      $sql = "SELECT COUNT(*) FROM `archive-images` " . $filterFeatured;

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

    /*
    Get image by date obs. If $featured is true get the featured picture of the day, if not, the first.
    */
    private function getImageByDateobs($dateobs, $filter, $source, $featured){
      $res = NULL;

      $sql_base = "SELECT `id`, `path`, `filename_final`, `filename_original`, `filename_thumb`, `date_obs`, `observatory_id`,
   `filesize_processed`, `date_upload`, `date_updated`, `rate` FROM `archive-images` WHERE `date_obs` LIKE '" . $dateobs . "%'";


      if($featured){
        $sql = $sql_base . " AND `featured` = 1";
      } else {
        $sql = $sql_base . $sql_limit;
      }

      if(DEBUG){ echo "<p>" . $sql . "</p>"; }

      if (!$resultado = $this->conn->query($sql)) {
        error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
        exit;
      }
      if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
          // Get only the picture with the filter and the source specified
          $meta = $this->getMetadata($row["id"]);
          if($meta->getFilter() == $filter && $meta->getSource() == $source){
              $res = new ArchiveImage($row["id"], $row["path"], $row["filename_final"],
                $row["filename_original"], $row["filename_thumb"], $row["date_obs"], $row["filesize_processed"],
                $row["date_updated"], $row["vists"], $row["tags"], $row["date_upload"], $row["rate"]);

              $res->setMetadata($meta);  //Adding metedata to obj

              $res->setObservatory($this->getObservatoryById($row["observatory_id"]));
          }
        }
      } else {
        error_log("Ninguna coincidencia", 0);

        // If there is no result with featured, try without, and if not, return null
        if($featured){
          return $this->getImageByDateobs($dateobs, $filter, $source, 0);
        } else {
          return null;
        }

      }

      return $res;
    }

    public function &getMetadata($imageId){
      $res = new ArchiveMetadata();

      $sql = "SELECT `metadata_id`, `value` FROM `archive-images-metadata` WHERE `image_id` = " . $imageId;

      if(DEBUG){ echo "<p>" . $sql . "</p>"; }

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
            `loginuserupdate` FROM `archive-observatories` WHERE `id` = " . $observatoryId;
    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        $res = new ArchiveObservatory($row["id"], $row["name"], $row["shortdescription"], $row["sectionurl"],
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
 `filesize_processed`, `date_upload`, `date_updated`, `rate` FROM `archive-images` WHERE `id` = " . $id;
    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        $res = new ArchiveImage($row["id"], $row["path"], $row["filename_final"],
          $row["filename_original"], $row["filename_thumb"], $row["date_obs"], $row["filesize_processed"],
          $row["date_updated"], $row["vists"], $row["tags"], $row["date_upload"], $row["rate"]);

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

    $sql = "SELECT `name` FROM `archive-observatories` ORDER BY `name`;";
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

    $sql = "SELECT `name` FROM `archive-observatories` ORDER BY `name`;";
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


  public function advanceSearch($query, $source, $obsId, $filter, $since, $until, $discardDark, $onlyFeatured, $order, $amount, $offset){      // Return the requested image ID's
    $res = [array(), 0];    // First its the results, and the second its the number of results that satifies
    $since = str_replace('/', '-', $since);    // Format date, SRC: https://bit.ly/2YRdKAw
    $until = str_replace('/', '-', $until);

    // Ensure that if some parameters are NULL, there is not in the query
    // Creating queries like these are difficult to read, but easy to control it parameters are NULL, consider changing this in the future
    $sqlMeta = "`id` IN (SELECT `image_id` FROM `archive-images-metadata` ";
    $sqlMeta .= ($discardDark)? "WHERE (`metadata_id` = 23 AND `value` = 'False') ":"" ;
    $sqlMetaSourceBase = "`image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 34 AND `value` = '" . ucfirst($source ). "')) ";
    $sqlMeta .= ($source != NULL) ? (($discardDark) ? "AND " . $sqlMetaSourceBase :  "WHERE " . $sqlMetaSourceBase) : "";
    $sqlMetaFilterBase = "`image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 28 AND `value` =  '" . strtolower($filter) . "')) ";
    $sqlMeta .= ($filter != NULL) ? (($source != NULL || $discardDark) ? "AND " . $sqlMetaFilterBase . ")" :  "WHERE " . $sqlMetaFilterBase . ")") : ")";

    if($query){
      $sqlMetaQuery .= "`id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 7 AND `value` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 9 AND `value` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 10 AND `value` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 11 AND `value` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 12 AND `value` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 13 AND `value` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 28 AND `value` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 34 AND `value` LIKE '%" . $query . "%')))) ";
    }

    if(DEBUG){ echo "<p>" . $sqlMeta . "</p>"; }
    if(DEBUG){ echo "<p>" . $sqlMetaQuery . "</p>"; }

    $sqlSel = "SELECT `id`, `path`, `filename_final`, `filename_original`,
      `filename_thumb`, `date_obs`, `observatory_id`, `filesize_processed`,
      `date_upload`, `date_updated`, `rate` FROM `archive-images` ";
    $sqlCount = "SELECT COUNT(*) FROM `archive-images`";
    $sqlPar = ($onlyFeatured)? "WHERE `featured` = true ":"";
    $sqlPar .= ($obsId) ? ($onlyFeatured)? "AND `observatory_id` = " . $obsId . " " : "WHERE `observatory_id` = " . $obsId . " " : "";
    $sqlPar .= ($onlyFeatured || $obsId)? "AND " . $sqlMeta . " " : "WHERE " . $sqlMeta . " ";


    // Then filter by date
    if($since == NULL && $until == NULL){
      $sqlDate = "";
    } elseif ($since == NULL && $until != NULL) {
      $sqlDate = "AND `date_obs` < '" . date( 'Y-m-d H:i:s', strtotime($until)) ."' ";
    } elseif ($since != NULL && $until == NULL) {
      $sqlDate = "AND `date_obs` > '" . date( 'Y-m-d H:i:s', strtotime($since)) . "' ";
    } else {
      $sqlDate = "AND `date_obs` BETWEEN '" . date( 'Y-m-d H:i:s', strtotime($since)) . "'
      AND '" .  date( 'Y-m-d H:i:s', strtotime($until)) . "' ";
    }

    $sqlPar .= $sqlDate;

    if($query){
      $sqlPar .= "AND (";
      $sqlPar .= $sqlMetaQuery;
      $sqlPar .= "OR ";
      $sqlPar .= "(`date_obs` LIKE '%" . date( 'Y-m-d', strtotime($query)) . "%' OR `date_obs` LIKE '%" . $query . "%')";
      $sqlPar .= ") ";
    }

    // Ordenation modes can be {"0" -> date desc, "1" -> date asc, "2" -> rate desc, "3" rate asc}
    switch($order){
      case 0:
        $sqlOrd = "ORDER BY `date_obs` DESC ";
        break;
      case 1:
        $sqlOrd = "ORDER BY `date_obs` ASC ";
        break;
      case 2:
        $sqlOrd = "ORDER BY `rate` DESC ";
        break;
      case 3:
        $sqlOrd = "ORDER BY `rate` ASC ";
        break;
      default:
        $sqlOrd = "ORDER BY `date_obs` DESC ";
    }

    $sql = $sqlSel . $sqlPar . $sqlOrd;

    $sql .= ($amount != NULL) ? "LIMIT " . $amount . " " : "LIMIT 12";
    $sql .= ($offset != NULL) ? "OFFSET " . $offset : "";

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {

        $obj = new ArchiveImage($row["id"], $row["path"], $row["filename_final"],
          $row["filename_original"], $row["filename_thumb"], $row["date_obs"], $row["filesize_processed"],
          $row["date_updated"], $row["vists"], $row["tags"], $row["date_upload"], $row["rate"]);

        $obj->setMetadata($this->getMetadata($row["id"]));  //Adding metedata to obj

        $obj->setObservatory($this->getObservatoryById($row["observatory_id"]));
        array_push($res[0], $obj);
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      return NULL;
    }

    $sql = $sqlCount . $sqlPar;

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


  public function simpleSearch($query, $order, $amount, $offset){
    $res = [array(), 0];    // First its the results, and the second its the number of results that satifies

    // Ensure that if some parameters are NULL, there is not in the query
    // Creating queries like these are difficult to read, but easy to control it parameters are NULL, consider changing this in the future
    $sqlMeta = "SELECT `image_id` FROM `archive-images-metadata` ";
    if($query){
      $sqlMeta .= " WHERE `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 7 AND `value` LIKE '%" . $query . "%'))";
      $sqlMeta .= " OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 9 AND `value` LIKE '%" . $query . "%'))";
      $sqlMeta .= " OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 10 AND `value` LIKE '%" . $query . "%'))";
      $sqlMeta .= " OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 11 AND `value` LIKE '%" . $query . "%'))";
      $sqlMeta .= " OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 12 AND `value` LIKE '%" . $query . "%'))";
      $sqlMeta .= " OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 13 AND `value` LIKE '%" . $query . "%'))";
      $sqlMeta .= " OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 28 AND `value` LIKE '%" . $query . "%'))";
      $sqlMeta .= " OR `image_id` IN (SELECT `image_id` FROM `archive-images-metadata` WHERE (`metadata_id` = 34 AND `value` LIKE '%" . $query . "%'))";
    }

    if(DEBUG){ echo "<p>" . $sqlMeta . "</p>"; }

    $sqlSel = "SELECT `id`, `path`, `filename_final`, `filename_original`,
      `filename_thumb`, `date_obs`, `observatory_id`, `filesize_processed`,
      `date_upload`, `date_updated`, `rate` FROM `archive-images` ";
    $sqlWhe = "WHERE `id` IN (" . $sqlMeta . ") ";

    // In this case we have to format the date as the webpage
    $sqlDate = "OR `date_obs` LIKE '%" . date( 'Y-m-d', strtotime($query)) . "%' ";
    $sqlDate = "OR `date_obs` LIKE '%" . $query . "%' ";
    $sqlOrd = ($order != NULL) ? (($order == "asc")? "ORDER BY `date_obs` ASC ": "ORDER BY `date_obs` DESC "): "";
    $sqlLimit = ($amount != NULL) ? "LIMIT " . $amount . " " : "LIMIT 12";
    $sqlOffset = ($offset != NULL) ? "OFFSET " . $offset : "";

    $sql = $sqlSel . $sqlWhe . $sqlDate . $sqlOrd . $sqlLimit . $sqlOffset;

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {

        $obj = new ArchiveImage($row["id"], $row["path"], $row["filename_final"],
          $row["filename_original"], $row["filename_thumb"], $row["date_obs"], $row["filesize_processed"],
          $row["date_updated"], $row["vists"], $row["tags"], $row["date_upload"], $row["rate"]);

        $obj->setMetadata($this->getMetadata($row["id"]));  //Adding metedata to obj

        $obj->setObservatory($this->getObservatoryById($row["observatory_id"]));
        array_push($res[0], $obj);
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      return NULL;
    }

    $sqlSel = "SELECT COUNT(*) FROM `archive-images` ";
    // Then we have to count the results, the same query with a counter and without the limit
    $sql = $sqlSel . $sqlWhe . $sqlDate;

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

  public function getObservationCount(){    // Get amount of pictures of every source
    $res = array();

    // First get sources names
    $sql = "SELECT DISTINCT `value` FROM `archive-images-metadata` WHERE `metadata_id` = 34";

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }

    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        array_push($res, array(0 => $row['value'], 1 => -1));
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      exit;
    }

    // Now we have to put the amount of pictures in every source
    $length = count($res);
    for($i = 0; $i < $length; $i++){
      $sql = "SELECT COUNT(*) FROM `archive-images-metadata` WHERE `value` = \"" . $res[$i][0] . "\";";

      if(DEBUG){ echo "<p>" . $sql . "</p>"; }

      if (!$resultado = $this->conn->query($sql)) {
        error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
        exit;
      }

      if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
          $res[$i][1] = $row["COUNT(*)"];
        }
      } else {
        error_log("Ninguna coincidencia", 0);
        exit;
      }
    }


    return $res;
  }

  // Return if there is a rate with the given ip and image_id
  public function existRate($imageid, $ip){
    $res = false;

    $sql = "SELECT 1 FROM `archive-images-rates` WHERE `image_id` = " . $imageid . " AND `ip` = \"" . $ip . "\" LIMIT 1";

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }


    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $conn->errno, 0);
         exit;
    }
    if ($resultado->num_rows > 0) {
      $res = true;
    }

    return $res;
  }

  public function insertRate($imageid, $rate, $ip, $browser){
    $sql = "INSERT INTO `archive-images-rates` (`image_id`, `rate`, `ip`, `browser`) VALUES (" . $imageid .
        ", \"" . $rate . "\", \"" . $ip . "\" , \"" . $browser .
        "\")";

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $conn->errno, 0);
         exit;
    }
  }

  // Update an alredy set rate at `archive-images-rates`
  public function updateRate($imageid, $rate, $ip){
    $sql = "UPDATE `archive-images-rates` SET `rate` = " . $rate . " WHERE `image_id` = " . $imageid . " AND `ip` = \"" . $ip . "\"";

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $conn->errno, 0);
         exit;
    }
  }

  // Update the average rate at `archive-images-rates`
  public function updateAvrRate($imageid){
    $avr = -1;

    $sql = "SELECT AVG(`rate`) FROM `archive-images-rates` WHERE `image_id` = " . $imageid;

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $conn->errno, 0);
         exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        $avr = $row["AVG(`rate`)"];
      }
    }

    $sql = "UPDATE `archive-images` SET `rate`=" . $avr . " WHERE `id` = " . $imageid;

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $conn->errno, 0);
         exit;
    }

    return $avr;
  }

  // Get Avr Rate from `archive-images-rates`
  public function getAvrRate($imageid){
    $res = -1;

    $sql = "SELECT `rate` FROM `archive-images` WHERE `id` = " . $imageid;

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $conn->errno, 0);
         exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        $res = $row["rate"];
      }
    }

    return $res;
  }

  // Update the visits of a observation, giving his ID
  public function addVisitObs($imageId){
    $sql = "UPDATE `archive-images` SET `visits` = `visits` + 1 WHERE `id` = " . $imageId;

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $conn->errno, 0);
         return false;
         exit;
    }

    return true;
  }
}
