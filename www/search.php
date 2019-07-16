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
          <label for="inputEmail4">Email</label>
          <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
        </div>
        <div class="form-group col-md-6">
          <label for="inputPassword4">Password</label>
          <input type="password" class="form-control" id="inputPassword4" placeholder="Password">
        </div>
      </div>
      <div class="form-group">
        <label for="inputAddress">Address</label>
        <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <div class='input-group date' id='datetimepicker7'>
            <input type='text' class="form-control" />
            <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
          </div>
        </div>
        <div class="form-group col-md-6">
          <div class='input-group date' id='datetimepicker7'>
            <input type='text' class="form-control" />
            <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="inputAddress2">Address 2</label>
        <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
      </div>
      <div class="form-row">
        <div class="form-group col-md-4">
          <label for="inputSource">Source</label>
          <select id="inputsource" class="form-control" name="inputSource">
            <option>Sun</option>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="inputObs">Observatory</label>
          <select id="inputObs" class="form-control" name="inputObs">
            <?php
            foreach($observatoryNames as $observatory){
              echo <<<END
            <option>{$observatory}</option>
END;
            }
            ?>
          </select>
        </div>
        <div class="form-group col-md-4">
          <label for="inputFilter">Filter</label>
          <select id="inputFilter" class="form-control" name="inputFilter">
            <option>halpha</option>
            <option>visible</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Search</button>
      </form>
    </div>
  </div>

  <div class="card mt-2 mb-5">
    <div class="card-body">
      <div class="row text-center">
      <?php

      $obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);

      if($_GET["inputSource"] == NULL){
        $res = $obj->getLastImages(10);

        foreach($res as $pic){
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
      }
      else{

        $obsId = ($_GET['inputObs'] == "Helios Observatory")? 1 : 2;    // There is only one observatory, doesnt make sense
        $res = $obj->advanceSearch($_GET["inputSource"], $obsId, $_GET["inputFilter"],10);

        foreach($res as $pic){
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
      }
      ?>
      </div>
    </div>
  </div>
</div>
<!-- /.container -->

<!-- Footer -->
<?php include './assets/partials/footer.php'?>
</body>
</html>
