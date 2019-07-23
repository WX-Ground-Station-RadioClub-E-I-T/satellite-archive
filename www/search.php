<!DOCTYPE html>
<html>
<head>
  <?php include './assets/partials/head.php' ?>
</head>
<body>

  <?php
  $db = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);

  $observatoryNames = $db->getObservatoryNames();
  ?>

  <!-- Navigation -->
  <?php include './assets/partials/navbar.php'?>

  <!-- Page Content -->
  <div class="container">
    <div class="card main-block">
      <div class="card-body">
        <form action="search.php" method="get">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="inputSource">Since</label>
              <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                   <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker7" name="inputSince"/>
                   <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                       <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                   </div>
               </div>
            </div>
            <div class="form-group col-md-6">
              <label for="inputSource">Until</label>
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
              <label for="inputSource">Source</label>
              <select id="inputsource" class="form-control" name="inputSource">
                <option value="" <?php if($_GET["inputSource"] == NULL) echo "selected" ?>>Whatever</option>
                <option <?php if($_GET["inputSource"] == "Sun") echo "selected" ?>>Sun</option>
              </select>
            </div>
            <div class="form-group col-md-4">
              <label for="inputObs">Observatory</label>
              <select id="inputObs" class="form-control" name="inputObs">
                <option value="">Whatever</option>
                <?php
                foreach($observatoryNames as $observatory){
                  if($observatory == $_GET["inputObs"]){
                    echo "<option selected>" . $observatory . "</option>";
                  } else{
                    echo "<option>" . $observatory . "</option>";
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group col-md-4">
              <label for="inputFilter">Filter</label>
              <select id="inputFilter" class="form-control" name="inputFilter">
                <option value="" <?php if($_GET["inputFilter"] == NULL) echo "selected" ?>>Whatever</option>
                <option <?php if($_GET["inputFilter"] == "halpha") echo "selected" ?>>halpha</option>
                <option <?php if($_GET["inputFilter"] == "visible") echo "selected" ?>>visible</option>
              </select>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
      </div>
    </div>

    <div class="card mt-2 mb-5">
      <div class="card-body">

        <?php

        // Current oder
        $dateOrd = ($_GET["dateOrd"] == NULL)? "desc": $_GET["dateOrd"];

        // Order By Date desc Button
        $query = $_GET;
        $query['dateOrd'] = "desc";
        $dateDesc = "?" . http_build_query($query);

        // Order by Date asc button
        $query = $_GET;
        $query['dateOrd'] = "asc";
        $dateAsc = "?" . http_build_query($query);
        ?>

        <div class="float-right">
          <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Order by Date <?php echo ($dateOrd == "desc")? "DESC" : "ASC";?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="<?php echo $dateDesc; ?>">Date DESC</a>
              <a class="dropdown-item" href="<?php echo $dateAsc; ?>">Date ASC</a>
            </div>
          </div>
        </div><br><br>

        <script>
        $('.dropdown-toggle').dropdown()
        </script>

        <div class="row text-center">

          <?php

          $pg = ($_GET["pg"] == NULL)? 0: $_GET["pg"];

          $obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
          $obsId = ($_GET['inputObs'] == "Helios Observatory")? 1 : 1;    // There is only one observatory, doesnt make sense
          $res = $obj->advanceSearch($_GET["inputSource"], $obsId, $_GET["inputFilter"], $_GET["inputSince"], $_GET["inputUntil"], $dateOrd ,12, 12 * $pg);
          $count = $res[1];   // Advance search return an array, with the number of results in #1 and the data on #0
          $data = $res[0];
          if($res != NULL){
            foreach($data as $pic){
              echo <<<END
              <div class="col-lg-3 col-md-6">
              <button type="button" class="btn btn-default" data-toggle="modal" data-target="#imageModal" data-image-id="{$pic->getId()}" data-image-src={$pic->getExtSrc()} data-date-obs="{$pic->getDateObs()}"
              data-date-updated="{$pic->getDateUpdated()}" data-observatory="{$pic->getObservatory()->getName()}" data-observatory-lat="{$pic->getMetadata()->getLatitude()}"
              data-observatory-long="{$pic->getMetadata()->getLongitud()}" data-observatory-alt="{$pic->getMetadata()->getAltitude()}" data-telecop="{$pic->getMetadata()->getTelescop()}" data-instrume="{$pic->getMetadata()->getInstrume()}"
              data-exposure="{$pic->getMetadata()->getExposure()}">
              <div class="image">
              <img class="card-img-top" src=" {$pic->getExtSrc()} " alt="">
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

    // Next Button
    $query = $_GET;
    $query['pg'] = $pg + 1;
    $nextPg = "?" . http_build_query($query);


    // Previous Button
    $query = $_GET;
    $query['pg'] = $pg - 1;
    $prevPg = "?" . http_build_query($query);
    ?>

    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item <? if($pg == 0){ echo "disabled"; } ?>">
          <a class="page-link" href="<?php echo $prevPg ?>" tabindex="-1" <? if($pg == 0){ echo "aria-disabled=\"true\""; } ?>>Previous</a>
        </li>

        <?php

        // With count put the number above

        $pgCounter = ($pg < 5)? 0: $pg - 5;
        $maxCounter = ceil($count/12) - 1;

        for(; $pgCounter <= $maxCounter ; $pgCounter++){
          $query = $_GET;
          $query['pg'] = $pgCounter;
          $link = "?" . http_build_query($query);

          //If there are a lot of results then put a button and exit
          if($pgCounter > $pg + 5){
            echo <<<END
            <li class="page-item"><a class="page-link" href="{$link}">...</a></li>
END;
            break;
          }

          if($pg == $pgCounter){
            echo <<<END
            <li class="page-item active"><a class="page-link" href="{$link}">{$pgCounter}</a></li>
END;
          } else {
            echo <<<END
            <li class="page-item"><a class="page-link" href="{$link}">{$pgCounter}</a></li>
END;
          }


        }

        ?>

        <li class="page-item <? if($pg == $maxCounter){ echo "disabled"; } ?>">
          <a class="page-link" href="<?php echo $nextPg ?>">Next</a>
        </li>
      </ul>
    </nav>
  <!-- /.container -->
  </div>

  <!-- Footer -->
  <?php include './assets/partials/footer.php'?>

  <!-- Datepicker scripts -->
  <script type="text/javascript">
      $(function () {
          $('#datetimepicker7').datetimepicker({
              <?php if($_GET["inputSince"] != NULL){ echo "defaultDate: moment('" . $_GET["inputSince"]. "', 'DD/MM/YYYY')"; } ?>

          });
          $('#datetimepicker8').datetimepicker({
              useCurrent: false,
            <?php if($_GET["inputUntil"] != NULL){ echo "defaultDate: moment('" . $_GET["inputUntil"]. "', 'DD/MM/YYYY')"; } ?>
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
            </div>
            <div class="col-sm">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><b>Date uploaded: </b> <a id="date-uploaded"></a></li>
                <li class="list-group-item"><b>Telecope:</b> <a id="telescope"></a> </li>
                <li class="list-group-item"><b>Camera:</b> <a id="instrume"></a></li>
                <li class="list-group-item"><b>Time exposure:</b> <a id="exposure"></a></li>
                <li class="list-group-item"><b>Latitude:</b> <a id="lat"></a></li>
                <li class="list-group-item"><b>Longitude:</b> <a id="long"></a></li>
                <li class="list-group-item"><b>Altitude:</b> <a id="alt"></a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <a href="#" class="btn btn-primary" tabindex="-1" role="button" aria-disabled="true">More info</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
