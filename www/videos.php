<!DOCTYPE html>
<html>
<head>
    <?php include 'assets/partials/head.php' ?>
</head>
<body>
  <!-- Navigation -->
  <?php include 'assets/partials/navbar.php'?>

  <?php

  $pg = ($_GET["pg"] == NULL)? 1: $_GET["pg"];

  $obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
  $res = $obj->getVideos(12, 12 * ($pg - 1));

  $count = $res[1];   // Advance search return an array, with the number of results in #1 and the data on #0
  $data = $res[0];
  ?>

  <!-- Page Content -->
  <div class="container">
    <h1>Videos</h1>
    <!-- Page Features -->
    <div class="row text-center main-block">
      <?php
      if($res != NULL){
        foreach($data as $video){
          $avrRate = $video->getRate();
          $formatedRate = ($avrRate != "")?number_format($avrRate, 1): "";
          $rateText = MODAL_RATE;
          echo <<<END
          <div class="col-lg-3 col-md-6">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#videoModal" data-title="CHANGEME" data-video-id="{$video->getId()}" data-path={$video->getExtSrc()} data-date-created="{$video->getDatecreated()}"
                data-date="CHANGEME" data-filter="{$video->getFilter()}" data-source="{$video->getSource()}" data-numimages="{$video->getNumimages()}" data-duration="{$video->getDuration()}" data-rate-text="{$rateText}" data-rate="{$formatedRate}" data-visits="{$rateText}">
            <div class="card" style="width: 15rem;">
              <img class="card-img-top" src="http://cesar.esa.int/sun_monitor/archive/helios/visible/2018/201803/20180316/image_hel_visible_20180316T103051_processed_thumbnail.jpg" alt="Card image cap">
              <div class="card-body">
                <p class="card-text"> {$video->getSource()} - {$video->getDatecreated()}</p>
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

  </div>
  <!-- /.container -->
  <!-- Footer -->
  <?php include 'assets/partials/footer.php'?>

  <!-- Modal -->
  <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

              <div class="rate-item" id="ratecont">
                <div class="rate"></div>
              </div>

              <div class="video-item" id="card-video-item">
                  <div class="video-here">
                  </div>
              </div>
            </div>
            <div class="col-sm">
              <ul class="list-group list-group-flush"  id="properties">
                <li class="list-group-item"><b><?php echo "CHANGEME DATE";?>: </b> <a id="date"></a></li>
                <li class="list-group-item"><b><?php echo "CHANGEME DATECREATED";?>: </b> <a id="datecreated"></a></li>
                <li class="list-group-item"><b><?php echo "DELETEME PATH";?>: </b> <a id="path"></a></li>
                <li class="list-group-item"><b><?php echo "CHANGEME numimages";?>: </b> <a id="numimages"></a></li>
                <li class="list-group-item"><b><?php echo "CHANGEME duration";?>: </b> <a id="duration"></a></li>
                <li class="list-group-item"><b><?php echo "CHANGEME source";?>: </b> <a id="source"></a></li>
                <li class="list-group-item"><b><?php echo "CHANGEME filter";?>: </b> <a id="filter"></a></li>
                <li class="list-group-item"><b><?php echo "CHANGEME visits";?>: </b> <a id="visits"></a></li>
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
