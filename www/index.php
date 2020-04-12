<!DOCTYPE html>
<html>
<head>
    <?php include 'assets/partials/head.php' ?>
</head>
<body>
  <?php

  $pg = ($_GET["pg"] == NULL)? 1: $_GET["pg"];

  $obj = new ArchiveDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
  $res = $obj->getImages(False, 12, 12 * ($pg - 1));
  $count = $res[1];   // Advance search return an array, with the number of results in #1 and the data on #0
  $data = $res[0];

  $picsCounter = $obj->getObservationCount();
  ?>


  <!-- Navigation -->
  <?php include 'assets/partials/navbar.php'?>
  <div class="jumbotron jumbotron-fluid my-jumbotron">
    <div class="container">
      <div class="float-right" style="width: 18rem;">
        <div class="alert stats" role="alert" data-toggle="tooltip" data-placement="top" title="<?php echo JUMBO_IM_TOOLTIP; ?>">

          <?php
          $length = count($picsCounter);
          $total = ($length == 1)?$picsCounter[0][1]:0;
          if($length > 1){
            for($i = 0; $i < $length; $i++){
              echo "<b>" . $picsCounter[$i][0] . "</b>: " . $picsCounter[$i][1] . " images <br>";
              $total += $picsCounter[$i][1];
            }
          }
          echo "<b>" . JUMBO_IM . "</b>: " . $total . "<br>";
          ?>
        </div>
      </div>



      <h1 class="display-4">Archive Viewer</h1>
      <p class="lead"><?php echo JUMBOTRON; ?></p>
    </div>
  </div>

  <!-- Page Content -->
  <div class="container mt-3">
    <!-- Page Features -->
    <div class="row text-center main-block">

      <?php
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
          echo <<<END
          <div class="col-lg-3 col-md-6">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#imageModal" data-title="{$modalTitle}" data-image-id="{$pic->getId()}" data-image-src={$pic->getExtSrc()} data-date-obs="{$pic->getDateObs()}"
                data-date-updated="{$pic->getDateUpdated()}" data-station="{$pic->getStation()->getName()}" data-station-lat="{$pic->getStation()->getLatitude()}"
                data-station-long="{$pic->getStation()->getLongitude()}" data-station-ele="{$pic->getStation()->getElevation()}" data-radio="{$pic->getMetadata()->getRadio()}"
                data-satellite="{$pic->getMetadata()->getSatellite()}" data-rate="{$formatedRate}" data-rate-text="{$rateText}">
            <div class="card" style="width: 15rem;">
              <img class="card-img-top" src="{$pic->getExtSrc()}" alt="Card image cap">
              <div class="card-body">
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
    <!-- /.row -->

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
