<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>LAMP STACK</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
          integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
          crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
          integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
          crossorigin="anonymous"></script>
  <script src="/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/lib/modal-triggers.js"></script>
</head>
<body>
<?php include './lib/conf.php' ?>
<?php include './lib/CesarDatabase.php' ?>
<?php include './lib/CesarImage.php' ?>
<?php include './lib/CesarMetadata.php' ?>
<?php include './lib/CesarObservatory.php' ?>

<?php
$db = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);

$observatoryNames = $db->getObservatoryNames();
?>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand" href="/">Start Bootstrap</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
            aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="/">Home
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Services</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

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
<footer class="py-5 bg-dark">
  <div class="container">
    <p class="m-0 text-center text-white">Copyright &copy; Your Website 2019</p>
  </div>
  <!-- /.container -->
</footer>
</body>
</html>
