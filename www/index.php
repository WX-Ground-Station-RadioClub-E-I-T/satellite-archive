<!DOCTYPE html>
<html>
<head>
    <?php include './assets/partials/head.php' ?>
</head>
<body>
  <!-- Navigation -->
  <?php include './assets/partials/navbar.php'?>

  <!-- Page Content -->
  <div class="container">
    <!-- Page Features -->
    <div class="row text-center main-block">
      <?php
      $obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
      $res = $obj->getLastImages(10);

      foreach($res as $pic){
        echo <<<END
        <div class="col-lg-3 col-md-6">
          <button type="button" class="btn btn-default" data-toggle="modal" data-target="#imageModal" data-image-id="{$pic->getId()}" data-image-src={$pic->getExtSrc()} data-date-obs="{$pic->getDateObs()}"
              data-date-updated="{$pic->getDateUpdated()}" data-observatory="{$pic->getObservatory()->getName()}" data-observatory-lat="{$pic->getMetadata()->getLatitude()}"
              data-observatory-long="{$pic->getMetadata()->getLongitud()}" data-observatory-alt="{$pic->getMetadata()->getAltitude()}" data-telecop="{$pic->getMetadata()->getTelescop()}" data-instrume="{$pic->getMetadata()->getInstrume()}"
              data-exposure="{$pic->getMetadata()->getExposure()}" data-filter="{$pic->getMetadata()->getFilter()}" data-source="{$pic->getMetadata()->getSource()}">
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
      ?>
    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->
  <!-- Footer -->
  <?php include './assets/partials/footer.php'?>

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
                <li class="list-group-item"><b>Filter:</b> <a id="filter"></a> </li>
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
