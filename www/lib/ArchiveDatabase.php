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

      $sql = "SELECT `ID`, `PATH`, `FILEKEY`, `DATE_OBS`, `STATION_ID`, `DATE_UPDATED`, `DATE_UPLOAD`, `VISITS`, `TAGS`,
                `FEATURED`, `RATE` FROM `archive-images` ORDER BY `date_obs` DESC LIMIT " . $amount;
      if (!$resultado = $this->conn->query($sql)) {
        error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
        exit;
      }
      if ($resultado->num_rows > 0) {
          while($row = $resultado->fetch_assoc()) {

              $obj = new ArchiveImage($row["ID"], $row["PATH"], $row["FILEKEY"], $row["DATE_OBS"], $row["STATION_ID"],
                  $row["DATE_UPDATED"], $row["DATE_UPLOAD"], $row["VISITS"], $row["TAGS"], $row["FEATURED"], $row["RATE"]);

              $obj->setMetadata($this->getMetadata($row["ID"]));  //Adding metedata to obj

              $obj->setStation($this->getStationById($row["STATION_ID"]));
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

      $filterFeatured = ($onlyFeatured)? "WHERE `FEATURED` = true ": "";

      $sql = "SELECT `ID`, `PATH`, `FILEKEY`, `DATE_OBS`, `STATION_ID`, `DATE_UPDATED`, `DATE_UPLOAD`, `VISITS`,
                `TAGS`, `FEATURED`, `RATE` FROM `archive-images` " . $filterFeatured .
                " ORDER BY `DATE_OBS` DESC LIMIT " . $amount .
                " OFFSET " . $offset;

      if(DEBUG){ echo "<p>" . $sql . "</p>"; }

      if (!$resultado = $this->conn->query($sql)) {
        error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
        exit;
      }
      if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
          $obj = new ArchiveImage($row["ID"], $row["PATH"], $row["FILEKEY"], $row["DATE_OBS"], $row["STATION_ID"],
            $row["DATE_UPDATED"], $row["DATE_UPLOAD"], $row["VISITS"], $row["TAGS"], $row["FEATURED"], $row["RATE"]);

          $obj->setMetadata($this->getMetadata($row["ID"]));  //Adding metedata to obj

          $obj->setStation($this->getStationById($row["STATION_ID"]));
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
    private function getImageByDateobs($dateobs, $satellite, $featured){
      $res = NULL;

      $sql_base = "SELECT `ID`, `PATH`, `FILEKEY`, `DATE_OBS`, `STATION_ID`, `DATE_UPDATED`, `DATE_UPLOAD`, `VISITS`,
                `TAGS`, `FEATURED`, `RATE` FROM `archive-images` WHERE `DATE_OBS` LIKE '" . $dateobs . "%'";


      if($featured){
        $sql = $sql_base . " AND `FEATURED` = 1";
      }

      if(DEBUG){ echo "<p>" . $sql . "</p>"; }

      if (!$resultado = $this->conn->query($sql)) {
        error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
        exit;
      }
      if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
          // Get only the picture with the filter and the source specified
          $meta = $this->getMetadata($row["ID"]);
          if($meta->getSatellite() == $satellite){
            $obj = new ArchiveImage($row["ID"], $row["PATH"], $row["FILEKEY"], $row["DATE_OBS"], $row["STATION_ID"],
              $row["DATE_UPDATED"], $row["DATE_UPLOAD"], $row["VISITS"], $row["TAGS"], $row["FEATURED"], $row["RATE"]);

              $res->setMetadata($meta);  //Adding metedata to obj

              $res->setObservatory($this->getStationById($row["STATION_ID"]));
          }
        }
      } else {
        error_log("Ninguna coincidencia", 0);

        // If there is no result with featured, try without, and if not, return null
        if($featured){
          return $this->getImageByDateobs($dateobs, $satellite, 0);
        } else {
          return null;
        }

      }

      return $res;
    }

    public function &getMetadata($imageId){
      $res = new ArchiveMetadata();

      $sql = "SELECT `IMAGE_ID`, `METADATA_ID`, `VALUE` FROM `archive-images-metadata` WHERE `IMAGE_ID` = " . $imageId;

      if(DEBUG){ echo "<p>" . $sql . "</p>"; }

      if (!$resultado = $this->conn->query($sql)) {
        error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
        exit;
      }
      if ($resultado->num_rows > 0) {
        while($row = $resultado->fetch_assoc()) {
          $res->setById($row["METADATA_ID"], $row["VALUE"]);
        }
      } else {
        error_log("Ninguna coincidencia", 0);
        exit;
      }

      return $res;
    }

  public function &getStationById($stationId){
    $res = NULL;

    $sql = " SELECT `ID`, `NAME`, `SHORTDESCRIPTION`, `LATITUDE`, `LONGITUDE`, `ELEVATION`, `SECTIONURL`, `DATECREATED`,
            `DATEUPDATED` FROM `archive-stations` WHERE `id` = " . $stationId;
    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        $res = new ArchiveStation($row["ID"], $row["NAME"], $row["SHORTDESCRIPTION"], $row["LATITUDE"], $row["LONGITUDE"],
                                $row["ELEVATION"], $row["SECTIONURL"], $row["DATECREATED"], $row["DATECREATED"]);
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      exit;
    }

    return $res;
  }

  public function &getImageById($id){
    $res = NULL;

    $sql = "SELECT `ID`, `PATH`, `FILEKEY`, `DATE_OBS`, `STATION_ID`, `DATE_UPDATED`, `DATE_UPLOAD`, `VISITS`, `TAGS`,
            `FEATURED`, `RATE` FROM `archive-images` WHERE `ID` = " . $id;
    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        $res = new ArchiveImage($row["ID"], $row["PATH"], $row["FILEKEY"], $row["DATE_OBS"], $row["STATION_ID"],
          $row["DATE_UPDATED"], $row["DATE_UPLOAD"], $row["VISITS"], $row["TAGS"], $row["FEATURED"], $row["RATE"]);

        $res->setMetadata($this->getMetadata($row["ID"]));  //Adding metedata to obj

        $res->setStation($this->getStationById($row["STATION_ID"]));
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      exit;
    }

    return $res;
  }

  public function getStationsNames(){
    $res = array();

    $sql = "SELECT `NAME` FROM `archive-stations` ORDER BY `NAME`;";
    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        array_push($res, $row['NAME']);
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      exit;
    }

    return $res;
  }

  public function advanceSearch($query, $satellite, $stationId, $since, $until, $onlyFeatured, $order, $amount, $offset){      // Return the requested image ID's
    $res = [array(), 0];    // First its the results, and the second its the number of results that satifies
    $since = str_replace('/', '-', $since);    // Format date, SRC: https://bit.ly/2YRdKAw
    $until = str_replace('/', '-', $until);

    // Ensure that if some parameters are NULL, there is not in the query
    // Creating queries like these are difficult to read, but easy to control it parameters are NULL, consider changing this in the future
    $sqlMeta = "`ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` ";
    $sqlMetaSourceBase = "`IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 1 AND `VALUE` = '" . ucfirst($satellite ). "')) ";
    $sqlMeta .= ($satellite != NULL) ? "WHERE " . $sqlMetaSourceBase . ")": ")";

    if($query){
      $sqlMetaQuery = "`ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 2 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 3 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 4 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 5 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 6 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 7 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 14 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 15 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 16 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 1 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMetaQuery .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 17 AND `VALUE` LIKE '%" . $query . "%')))) ";
    }

    if(DEBUG){ echo "<p>" . $sqlMeta . "</p>"; }
    if(DEBUG){ echo "<p>" . $sqlMetaQuery . "</p>"; }

    $sqlSel = "SELECT `ID`, `PATH`, `FILEKEY`, `DATE_OBS`, `STATION_ID`, `DATE_UPDATED`, `DATE_UPLOAD`, `VISITS`,
    `TAGS`, `FEATURED`, `RATE` FROM `archive-images` ";
    $sqlCount = "SELECT COUNT(*) FROM `archive-images`";
    $sqlPar = ($onlyFeatured)? "WHERE `FEATURED` = true ":"";
    $sqlPar .= ($stationId) ? ($onlyFeatured)? "AND `STATION_ID` = " . $stationId . " " : "WHERE `STATION_ID` = " . $stationId . " " : "";
    $sqlPar .= ($onlyFeatured || $stationId)? "AND " . $sqlMeta . " " : "WHERE " . $sqlMeta . " ";


    // Then filter by date
    if($since == NULL && $until == NULL){
      $sqlDate = "";
    } elseif ($since == NULL && $until != NULL) {
      $sqlDate = "AND `DATE_OBS` < '" . date( 'Y-m-d H:i:s', strtotime($until)) ."' ";
    } elseif ($since != NULL && $until == NULL) {
      $sqlDate = "AND `DATE_OBS` > '" . date( 'Y-m-d H:i:s', strtotime($since)) . "' ";
    } else {
      $sqlDate = "AND `DATE_OBS` BETWEEN '" . date( 'Y-m-d H:i:s', strtotime($since)) . "'
      AND '" .  date( 'Y-m-d H:i:s', strtotime($until)) . "' ";
    }

    $sqlPar .= $sqlDate;

    if($query){
      $sqlPar .= "AND (";
      $sqlPar .= $sqlMetaQuery;
      $sqlPar .= "OR ";
      $sqlPar .= "(`DATE_OBS` LIKE '%" . date( 'Y-m-d', strtotime($query)) . "%' OR `DATE_OBS` LIKE '%" . $query . "%')";
      $sqlPar .= ") ";
    }

    // Ordenation modes can be {"0" -> date desc, "1" -> date asc, "2" -> rate desc, "3" rate asc}
    switch($order){
      case 0:
        $sqlOrd = "ORDER BY `DATE_OBS` DESC ";
        break;
      case 1:
        $sqlOrd = "ORDER BY `DATE_OBS` ASC ";
        break;
      case 2:
        $sqlOrd = "ORDER BY `RATE` DESC ";
        break;
      case 3:
        $sqlOrd = "ORDER BY `RATE` ASC ";
        break;
      default:
        $sqlOrd = "ORDER BY `DATE_OBS` DESC ";
    }

    $sql = $sqlSel . $sqlPar . $sqlOrd;

    $sql .= ($amount != NULL) ? "LIMIT " . $amount . " " : "LIMIT 12 ";
    $sql .= ($offset != NULL) ? "OFFSET " . $offset : "";

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {

        $obj = new ArchiveImage($row["ID"], $row["PATH"], $row["FILEKEY"], $row["DATE_OBS"], $row["STATION_ID"],
          $row["DATE_UPDATED"], $row["DATE_UPLOAD"], $row["VISITS"], $row["TAGS"], $row["FEATURED"], $row["RATE"]);

        $obj->setMetadata($this->getMetadata($row["ID"]));  //Adding metedata to obj

        $obj->setStation($this->getStationById($row["STATION_ID"]));
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
    $sqlMeta = "SELECT `IMAGE_ID` FROM `archive-images-metadata` ";
    if($query){
      $sqlMeta .= " WHERE `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 2 AND `VALUE` LIKE '%" . $query . "%'))";
      $sqlMeta .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 3 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMeta .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 4 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMeta .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 5 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMeta .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 6 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMeta .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 7 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMeta .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 14 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMeta .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 15 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMeta .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 16 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMeta .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 17 AND `VALUE` LIKE '%" . $query . "%')) ";
      $sqlMeta .= "OR `IMAGE_ID` IN (SELECT `IMAGE_ID` FROM `archive-images-metadata` WHERE (`METADATA_ID` = 1 AND `VALUE` LIKE '%" . $query . "%')) ";
    }

    if(DEBUG){ echo "<p>" . $sqlMeta . "</p>"; }

    $sqlSel = "SELECT `ID`, `PATH`, `FILEKEY`, `DATE_OBS`, `STATION_ID`, `DATE_UPDATED`, `DATE_UPLOAD`, `VISITS`,
                `TAGS`, `FEATURED`, `RATE` FROM `archive-images` ";
    $sqlWhe = "WHERE `ID` IN (" . $sqlMeta . ") ";

    // In this case we have to format the date as the webpage
    $sqlDate = "OR `DATE_OBS` LIKE '%" . date( 'Y-m-d', strtotime($query)) . "%' ";
    $sqlDate = "OR `DATE_OBS` LIKE '%" . $query . "%' ";
    $sqlOrd = ($order != NULL) ? (($order == "asc")? "ORDER BY `DATE_OBS` ASC ": "ORDER BY `DATE_OBS` DESC "): "";
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

        $obj = new ArchiveImage($row["ID"], $row["PATH"], $row["FILEKEY"], $row["DATE_OBS"], $row["STATION_ID"],
          $row["DATE_UPDATED"], $row["DATE_UPLOAD"], $row["VISITS"], $row["TAGS"], $row["FEATURED"], $row["RATE"]);

        $obj->setMetadata($this->getMetadata($row["ID"]));  //Adding metedata to obj

        $obj->setStation($this->getStationById($row["STATION_ID"]));
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
    $sql = "SELECT DISTINCT `VALUE` FROM `archive-images-metadata` WHERE `METADATA_ID` = 1";

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
      error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
      exit;
    }

    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        array_push($res, array(0 => $row['VALUE'], 1 => -1));
      }
    } else {
      error_log("Ninguna coincidencia", 0);
      exit;
    }

    // Now we have to put the amount of pictures in every source
    $length = count($res);
    for($i = 0; $i < $length; $i++){
      $sql = "SELECT COUNT(*) FROM `archive-images-metadata` WHERE `VALUE` = \"" . $res[$i][0] . "\";";

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

    $sql = "SELECT 1 FROM `archive-images-rates` WHERE `IMAGE_ID` = " . $imageid . " AND `IP` = \"" . $ip . "\" LIMIT 1";

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }


    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
         exit;
    }
    if ($resultado->num_rows > 0) {
      $res = true;
    }

    return $res;
  }

  public function insertRate($imageid, $rate, $ip, $browser){
    $sql = "INSERT INTO `archive-images-rates` (`IMAGE_ID`, `RATE`, `IP`, `BROWSER`) VALUES (" . $imageid .
        ", \"" . $rate . "\", \"" . $ip . "\" , \"" . $browser .
        "\")";

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
         exit;
    }
  }

  // Update an alredy set rate at `archive-images-rates`
  public function updateRate($imageid, $rate, $ip){
    $sql = "UPDATE `archive-images-rates` SET `RATE` = " . $rate . " WHERE `IMAGE_ID` = " . $imageid . " AND `IP` = \"" . $ip . "\"";

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
         exit;
    }
  }

  // Update the average rate at `archive-images-rates`
  public function updateAvrRate($imageid){
    $avr = -1;

    $sql = "SELECT AVG(`RATE`) FROM `archive-images-rates` WHERE `IMAGE_ID` = " . $imageid;

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
         exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        $avr = $row["AVG(`RATE`)"];
      }
    }

    $sql = "UPDATE `archive-images` SET `RATE`=" . $avr . " WHERE `ID` = " . $imageid;

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
         exit;
    }

    return $avr;
  }

  // Get Avr Rate from `archive-images-rates`
  public function getAvrRate($imageid){
    $res = -1;

    $sql = "SELECT `RATE` FROM `archive-images` WHERE `ID` = " . $imageid;

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
         exit;
    }
    if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
        $res = $row["RATE"];
      }
    }

    return $res;
  }

  // Update the visits of a observation, giving his ID
  public function addVisitObs($imageId){
    $sql = "UPDATE `archive-images` SET `VISITS` = `VISITS` + 1 WHERE `ID` = " . $imageId;

    if(DEBUG){ echo "<p>" . $sql . "</p>"; }

    if (!$resultado = $this->conn->query($sql)) {
         error_log("Could not connect to mysql database. Errno:" . $this->conn->errno, 0);
         return false;
         exit;
    }

    return true;
  }
}
