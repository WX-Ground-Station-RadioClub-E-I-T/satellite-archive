<!DOCTYPE html>
<html>
<head>
  <?php include 'assets/partials/head.php' ?>
</head>
<body>

  <?php
  $db = new ArchiveDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);

  $StationsNames = $db->getStationsNames();
  ?>

  <!-- Navigation -->
  <?php include 'assets/partials/navbar.php'?>

  <?php

  $pg = ($_GET["pg"] == NULL)? 1: $_GET["pg"];

  $obj = new ArchiveDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
  $dateOrd = ($_GET["dateOrd"] == NULL)? "desc": $_GET["dateOrd"];    // Current oder
  $res = $obj->simpleSearch($_GET["query"], $dateOrd, 12, 12 * ($pg - 1));
  $count = $res[1];   // Advance search return an array, with the number of results in #1 and the data on #0
  $data = $res[0];

  ?>

  <!-- Page Content -->
  <div class="container  mt-3">
    <nav aria-label="breadcrumb">
      <div class="breadcrumb sub-text">
        Looking for <i>"<?php echo $_GET["query"]?>" </i>
      </div>
    </nav>

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

        <div class="float-left sub-text">
          Obtained <?php echo number_format($count); ?> results
        </div>

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
        <li class="page-item <?php if($pg == 1){ echo "disabled"; } ?>">
          <a class="page-link" href="<?php echo $prevPg ?>" tabindex="-1" <?php if($pg == 0){ echo "aria-disabled=\"true\""; } ?>>Previous</a>
        </li>

        <?php

        // With count put the number above

        $pgCounter = ($pg < 6)? 1: $pg - 5;
        $maxCounter = ceil($count/12);

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
          if($pgCounter > $pg + 5){
            echo <<<END
            <li class="page-item"><a class="page-link">...</a></li>
END;
            echo <<<END
            <li class="page-item"><a class="page-link" href="{$lastlink}">{$maxCounter}</a></li>
END;
            break;
          }

          if($pg == $pgCounter){
            echo <<<END
            <li class="page-item active"><a class="page-link" href="{$link}">{$pgCounter}</a></li>
END;
          } elseif ($pgCounter == $pg - 5) {
            echo <<<END
            <li class="page-item"><a class="page-link" href="{$firstlink}">1</a></li>
END;
            echo <<<END
            <li class="page-item"><a class="page-link">...</a></li>
END;
          } else {
            echo <<<END
            <li class="page-item"><a class="page-link" href="{$link}">{$pgCounter}</a></li>
END;
          }


        }



        ?>

        <li class="page-item <?php if($pg == $maxCounter){ echo "disabled"; } ?>">
          <a class="page-link" href="<?php echo $nextPg ?>">Next</a>
        </li>
      </ul>
    </nav>
  <!-- /.container -->
  </div>

  <!-- Footer -->
  <?php include 'assets/partials/footer.php'?>

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
