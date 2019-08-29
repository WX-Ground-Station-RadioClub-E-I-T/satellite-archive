<!DOCTYPE html>
<html>
<head>
    <?php include 'assets/partials/head.php' ?>
</head>
<body>
  <?php

  $pg = ($_GET["pg"] == NULL)? 1: $_GET["pg"];

  $obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
  $res = $obj->getImages(True, 12, 12 * ($pg - 1));
  $count = $res[1];   // Advance search return an array, with the number of results in #1 and the data on #0
  $data = $res[0];

  $picsCounter = $obj->getObservationCount();
  ?>


  <!-- Navigation -->
  <?php include 'assets/partials/navbar.php'?>
  <div class="jumbotron jumbotron-fluid my-jumbotron">
    <div class="container">



      <div class="float-right" style="width: 18rem;">
        <div class="alert stats" role="alert">

          <?php
          $length = count($picsCounter);
          $total = ($length == 1)?$picsCounter[0][1]:0;
          if($length > 1){
            for($i = 0; $i < $length; $i++){
              echo "<b>" . $picsCounter[$i][0] . "</b>: " . $picsCounter[$i][1] . " images <br>";
              $total += $picsCounter[$i][1];
            }
          }
          echo "<b>Total images</b>: " . $total . "<br>";
          ?>
        </div>
      </div>



      <h1 class="display-4">CESAR Archive Viewer</h1>
      <p class="lead">CESAR Educational Initiative sky observations database</p>
    </div>
  </div>

  <!-- Page Content -->
  <div class="container">
    <!-- Page Features -->
    <div class="row text-center main-block">
      <?php
      if($res != NULL){
        foreach($data as $pic){
          $avrRate = $pic->getRate();
          $formatedRate = ($avrRate != "")?number_format($avrRate, 1): "";
          echo <<<END
          <div class="col-lg-3 col-md-6">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#imageModal" data-image-id="{$pic->getId()}" data-image-src={$pic->getExtSrc()} data-date-obs="{$pic->getDateObs()}"
                data-date-updated="{$pic->getDateUpdated()}" data-observatory="{$pic->getObservatory()->getName()}" data-observatory-lat="{$pic->getMetadata()->getLatitude()}"
                data-observatory-long="{$pic->getMetadata()->getLongitud()}" data-observatory-alt="{$pic->getMetadata()->getAltitude()}" data-telecop="{$pic->getMetadata()->getTelescop()}" data-instrume="{$pic->getMetadata()->getInstrume()}"
                data-exposure="{$pic->getMetadata()->getExposure()}" data-filter="{$pic->getMetadata()->getFilter()}" data-source="{$pic->getMetadata()->getSource()}" data-rate="{$formatedRate}">
            <div class="card" style="width: 15rem;">
              <img class="card-img-top" src="{$pic->getExtSrc()}" alt="Card image cap">
              <div class="card-body">
                <p class="card-text"> {$pic->getMetadata()->getSource()} - {$pic->getDateObs()}</p>
              </div>
            </div>
          </button>
          </div>
END;
        }
      }

      ?>
    </div>
    <!-- /.row -->

    <?php   // Pagination scripts

    $displayPagination = ceil($count/12) > 1;

    if($displayPagination){
      // With count put the number above
      $pgCounter = ($pg < 6)? 1: $pg - 5;
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
      echo <<<END
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <li class="page-item {$previousDisabled}">
            <a class="page-link" href="{$prevPg}" tabindex="-1">Previous</a>
          </li>
END;

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

      echo <<<END
        <li class="page-item {$nextDisabled}">
          <a class="page-link" href="{$nextPg}">Next</a>
        </li>
      </ul>
    </nav>
END;
    }
    ?>
  </div>
  <!-- /.container -->
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
                <li class="list-group-item"><b>Date uploaded: </b> <a id="date-uploaded"></a></li>
                <li class="list-group-item"><b>Telecope:</b> <a id="telescope"></a> </li>
                <li class="list-group-item"><b>Filter:</b> <a id="filter"></a> </li>
                <li class="list-group-item"><b>Camera:</b> <a id="instrume"></a></li>
                <li class="list-group-item"><b>Time exposure:</b> <a id="exposure"></a></li>
                <li class="list-group-item"><b>Latitude:</b> <a id="lat"></a></li>
                <li class="list-group-item"><b>Longitude:</b> <a id="long"></a></li>
                <li class="list-group-item"><b>Altitude:</b> <a id="alt"></a></li>
                <li class="list-group-item" id="rateitem"><b>Rate:</b> <a id="ratetext"></a></li>
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
