<!DOCTYPE html>
<html>
<head>
  <?php include 'assets/partials/head.php' ?>

  <script>
  $('.dropdown-toggle').dropdown()
  </script>
</head>
<body>

  <?php
  $db = new ArchiveDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);

  $stationsNames = $db->getStationsNames();
  ?>

  <!-- Navigation -->
  <?php include 'assets/partials/navbar.php'?>

  <?php

  $pg = ($_GET["pg"] == NULL)? 1: $_GET["pg"];

  $obj = new ArchiveDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
  $stationId = ($_GET['inputStation'] == "EA4RCT VHF/UHF Satellite Station")? 1 : 1;    // There is only one groundstation, doesnt make sense

  // Ordenation modes can be {"0" -> date desc, "1" -> date asc, "2" -> rate desc, "3" rate asc}
  $ord = ($_GET["ord"] == NULL)? "0": $_GET["ord"];    // Current oder

  $discardDark = ($_GET["inputDark"] == "on")? True: False;
  $onlyFeatured = ($_GET["inputFeat"] == "on")? True: False;
  $res = $obj->advanceSearch($_GET["inputQuery"], $_GET["inputSatellite"], $stationId, $_GET["inputSince"], $_GET["inputUntil"], $onlyFeatured, $ord ,12, 12 * ($pg - 1));
  $count = $res[1];   // Advance search return an array, with the number of results in #1 and the data on #0
  $data = $res[0];

  $picsCounter = $obj->getObservationCount();
  ?>

  <!-- Page Content -->
  <div class="container  mt-3">
    <div class="card main-block">
      <div class="card-body">
        <form action="search.php" method="get">
          <div class="form-group">
            <label for="inputQuery"><?php echo SEARCH_KEYWORDS; ?></label>
            <input type="text" class="form-control" id="inputQuery" name="inputQuery" placeholder="<?php echo SEARCH; ?>" value="<?php if($_GET["inputQuery"]) echo $_GET["inputQuery"];  ?>">
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputSince"><?php echo SEARCH_SINCE; ?></label>
              <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                   <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker7" name="inputSince"/>
                   <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                       <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                   </div>
               </div>
            </div>
            <div class="form-group col-md-6">
              <label for="inputUntil"><?php echo SEARCH_UNTIL; ?></label>
              <div class="input-group date" id="datetimepicker8" data-target-input="nearest">
                   <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker8" name="inputUntil"/>
                   <div class="input-group-append" data-target="#datetimepicker8" data-toggle="datetimepicker">
                       <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                   </div>
               </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label for="inputSatellite"><?php echo SEARCH_SATELLITE;?></label>
              <select id="inputsatellite" class="form-control" name="inputSatellite">
                <option value=""><?php echo SEARCH_WHATEVER; ?></option>
                <?php
                foreach($picsCounter as $sat){
                  if(strpos($_GET["inputSatellite"], $sat[0]) !== false){
                    echo "<option selected>" . $sat[0]  . "</option>";
                  } else{
                    echo "<option>" . $sat[0] . "</option>";
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-4">
              <label for="inputStation"><?php echo SEARCH_STATION;?></label>
              <select id="inputStation" class="form-control" name="inputStation">
                <option value=""><?php echo SEARCH_WHATEVER; ?></option>
                <?php
                foreach($stationsNames as $station){
                  if($station == $_GET["inputStation"]){
                    echo "<option selected>" . $station . "</option>";
                  } else{
                    echo "<option>" . $station . "</option>";
                  }
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="form-check form-check-inline">
              <input type="checkbox" class="form-check-input" id="inputFeat" name="inputFeat" <?php if($_GET["inputFeat"] == "on") echo "checked" ?>>
              <label class="form-check-label" for="exampleCheck1" ><?php echo SEARCH_ONLY_FEAT;?></label>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary"><?php echo SEARCH_SEARCH;?></button>
          </div>
        </form>
      </div>
    </div>

    <div class="card mt-2 mb-5">
      <div class="card-body">
        <div class="row">

        <?php

        // Order By Date desc Button
        $query = $_GET;
        $query['ord'] = "0";
        $dateDesc = "?" . http_build_query($query);

        // Order by Date asc button
        $query = $_GET;
        $query['ord'] = "1";
        $dateAsc = "?" . http_build_query($query);

        // Order by Rate desc button
        $query = $_GET;
        $query['ord'] = "2";
        $rateDesc = "?" . http_build_query($query);

        // Order by Rate asc button
        $query = $_GET;
        $query['ord'] = "3";
        $rateAsc = "?" . http_build_query($query);
        ?>

        <div class="col-md-3 mr-md-auto sub-text">
          <?php echo SEARCH_OBTAINED . " " . number_format($count) . " " . SEARCH_RESULTS; ?>
        </div>

        <?php

        if($count > 0){
          switch($ord){
            case 0:
              $ordText = SEARCH_ORDERDATEDESC;
              break;
            case 1:
              $ordText = SEARCH_ORDERDATEASC;
              break;
            case 2:
              $ordText = SEARCH_ORDERRATEDESC;
              break;
            case 3:
              $ordText = SEARCH_ORDERRATEASC;
              break;
          }

          echo "<div class=\"col-md-3 ml-md-auto\">
            <div class=\"dropdown\">
              <button class=\"btn btn-secondary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                $ordText
              </button>
              <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                <a class=\"dropdown-item\" href=\"{$dateDesc}\">" . SEARCH_DATEDESC . "</a>
                <a class=\"dropdown-item\" href=\"{$dateAsc}\">" . SEARCH_DATEASC . "</a>
                <a class=\"dropdown-item\" href=\"{$rateDesc}\">" . SEARCH_RATEDESC . "</a>
                <a class=\"dropdown-item\" href=\"{$rateAsc}\">" . SEARCH_RATEASC . "</a>
              </div>
            </div>
          </div>";
        }

        ?>

        <br><br>

      </div>


        <div class="row text-center">
          <?php
          $localDate = new DateTime();
          if($res != NULL){
            foreach($data as $pic){
              $avrRate = $pic->getRate();
              $formatedRate = ($avrRate != "")?number_format($avrRate, 1): "";
              $rateText = MODAL_RATE;
              if(substr ( $_SERVER [ "HTTP_ACCEPT_LANGUAGE" ], 0 , 2 ) == "es"){
                $modalTitle = MODAL_TITLE1 . " " . $pic->getMetadata()->getSatellite() . " " . MODAL_TITLE2 . " " . $pic->getStation()->getName();
              } else {
                $modalTitle = $pic->getMetadata()->getSatellite() . " " . MODAL_TITLE . " " . $pic->getStation()->getName();
              }

              $interval = $localDate->diff($pic->getDateObsDatetime());
              $delta =($interval->format('%d') > 0)? $interval->format('%d') . " days ":"";
              $delta .=($interval->format('%h') > 0)? $interval->format('%h') . " hours ":"";
              $delta .=($interval->format('%i') > 0)? $interval->format('%i') . " minutes ":"";
              echo <<<END
              <div class="col-lg-3 col-md-6">
              <button type="button" class="btn btn-default" data-toggle="modal" data-target="#imageModal" data-title="{$modalTitle}" data-image-id="{$pic->getId()}" data-image-src={$pic->getExtSrc()} data-date-obs="{$pic->getDateObs()}"
                  data-date-updated="{$pic->getDateUpdated()}" data-station="{$pic->getStation()->getName()}" data-station-lat="{$pic->getStation()->getLatitude()}"
                  data-station-long="{$pic->getStation()->getLongitude()}" data-station-ele="{$pic->getStation()->getElevation()}" data-radio="{$pic->getMetadata()->getRadio()}"
                  data-satellite="{$pic->getMetadata()->getSatellite()}" data-rate="{$formatedRate}" data-rate-text="{$rateText}">
                <div class="card" style="width: 15rem;">
                  <img class="card-img-top" src="{$pic->getExtSrc()}" alt="Card image cap">
                  <div class="card-body" data-toggle="tooltip" data-placement="top" title="{$delta} ago">
                    <p class="card-text"> {$pic->getMetadata()->getSatellite()} - {$pic->getDateObs()}</p>
                  </div>
                </div>
              </button>
              </div>
END;
            }
          } else {  // There is no coincidences
            echo "There is no coincidences";
          }
          ?>
        </div>
      </div>
    </div>

    <?php   // Pagination scripts

    $displayPagination = ceil($count/12) > 1;

    if($displayPagination){

      $pgCounterPerPage = ($ismobile)? 5: 1;    // Var on head for detect mobile

      // With count put the number above
      $pgCounter = ($pg < $pgCounterPerPage + 1)? 1: $pg - $pgCounterPerPage;
      $maxCounter = ceil($count/12);

      // Next Button
      $query = $_GET;
      $query['pg'] = $pg + 1;
      $nextPg = "?" . http_build_query($query);


      // Previous Button
      $query = $_GET;
      $query['pg'] = $pg - 1;
      $prevPg = "?" . http_build_query($query);

      $previousDisabled = ($pg == 1)? "disabled":"";
      $nextDisabled = ($pg == $maxCounter)? "disabled":"";

      echo "<nav aria-label=\"Page navigation example\">
        <ul class=\"pagination justify-content-center\">
          <li class=\"page-item {$previousDisabled}\">
            <a class=\"page-link\" href=\"{$prevPg}\" tabindex=\"-1\">" . PAGINATION_PREV . "</a>
          </li>";

      // Last index button link
      $query = $_GET;
      $query['pg'] = $maxCounter;
      $lastlink = "?" . http_build_query($query);

      // First index button link
      $query = $_GET;
      $query['pg'] = 1;
      $firstlink = "?" . http_build_query($query);


      for(; $pgCounter <= $maxCounter ; $pgCounter++){
        $query = $_GET;
        $query['pg'] = $pgCounter;
        $link = "?" . http_build_query($query);


        //If there are a lot of results then put a button and exit
        if($pgCounter > $pg + $pgCounterPerPage){
          echo <<<END
          <li class="page-item"><a class="page-link">...</a></li>
END;
          echo <<<END
          <li class="page-item"><a class="page-link" href="{$lastlink}">{$maxCounter}</a></li>
END;
          break;
        }

        if($pgCounter == $pg - $pgCounterPerPage){
          echo <<<END
          <li class="page-item"><a class="page-link" href="{$firstlink}">1</a></li>
END;
          echo <<<END
          <li class="page-item"><a class="page-link">...</a></li>
END;
        } elseif ($pg == $pgCounter) {
          echo <<<END
          <li class="page-item active"><a class="page-link" href="{$link}">{$pgCounter}</a></li>
END;
        } elseif($pgCounter != $pg + $pgCounterPerPage){
          echo <<<END
          <li class="page-item"><a class="page-link" href="{$link}">{$pgCounter}</a></li>
END;
        }
      }

      echo "<li class=\"page-item {$nextDisabled}\">
          <a class=\"page-link\" href=\"{$nextPg}\">" . PAGINATION_NEXT . "</a>
        </li>
      </ul>
    </nav>";
    }
    ?>
  <!-- /.container -->
  </div>

  <!-- Footer -->
  <?php include 'assets/partials/footer.php'?>

  <!-- Datepicker scripts -->
  <script type="text/javascript">
      $(function () {
          $('#datetimepicker7').datetimepicker({
              <?php if($_GET["inputSince"] != NULL){ echo "defaultDate: moment('" . $_GET["inputSince"]. "', 'DD/MM/YYYY')"; } else { echo "defaultDate: moment(\"01/01/2017\", 'DD/MM/YYYY')"; } ?>

          });
          $('#datetimepicker8').datetimepicker({
              useCurrent: false,
            <?php if($_GET["inputUntil"] != NULL){ echo "defaultDate: moment('" . $_GET["inputUntil"]. "', 'DD/MM/YYYY')"; } else { echo "defaultDate: moment()";} ?>
          });
          $("#datetimepicker7").on("change.datetimepicker", function (e) {
              $('#datetimepicker8').datetimepicker('minDate', e.date);
          });
          $("#datetimepicker8").on("change.datetimepicker", function (e) {
              $('#datetimepicker7').datetimepicker('maxDate', e.date);
          });
      });
  </script>

  <!-- Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md">
              <img class="card-img-top" src="#" alt="">
              <div class="rate-item" id="ratecont">
                <div class="rate"></div>
              </div>
            </div>
            <div class="col-sm">
              <ul class="list-group list-group-flush"  id="properties">
                <li class="list-group-item"><b><?php echo MODAL_DATE;?>: </b> <a id="date-uploaded"></a></li>
                <li class="list-group-item"><b><?php echo MODAL_RADIO;?>:</b> <a id="radio"></a> </li>
                <li class="list-group-item"><b><?php echo MODAL_LAT;?>:</b> <a id="lat"></a></li>
                <li class="list-group-item"><b><?php echo MODAL_LONG;?>:</b> <a id="long"></a></li>
                <li class="list-group-item"><b><?php echo MODAL_ALT;?>:</b> <a id="ele"></a></li>
                <li class="list-group-item" id="rateitem"><b><?php echo MODAL_RATE;?>:</b> <a id="ratetext"></a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo MODAL_CLOSE; ?></button>
          <a href="#" class="btn btn-primary" tabindex="-1" role="button" aria-disabled="true"><?php echo MODAL_MOREINFO; ?></a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
