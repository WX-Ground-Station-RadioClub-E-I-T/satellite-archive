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

  <script>
          $(function () {
          $('[data-toggle="tooltip"]').tooltip()
  });
  </script>

  <script defer src="https://use.fontawesome.com/releases/v5.9.0/js/all.js"></script>


</head>
<body>
<?php include './lib/conf.php' ?>
<?php include './lib/CesarDatabase.php' ?>
<?php include './lib/CesarImage.php' ?>
<?php include './lib/CesarMetadata.php' ?>
<?php include './lib/CesarObservatory.php' ?>

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

  <?php
  $imageId = $_GET["id"];
  $obj = new CesarDatabase(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DATABASE);
  $pic = $obj->getImageById($imageId);
  ?>

  <!-- Page Features -->
  <div class="row main-block">


    <div class="col-6">
      <img class="card-img-top"
           src="<?php echo $pic->getExtSrc(); ?>"
           alt="">

      <div class="card bg-secondary icons-sub-logo">
        <div class="card-header">

          <a href="/test.php?id=<?php echo $pic->getId(); ?>" data-placement="top" title="Name of the data acqusition telescope"><i class="fas fa-file-csv icon" style="color:#00a9e0" data-toggle="tooltip" data-placement="top" title="CSV file output"></i></a>

          <a href="/generatejson.php?id=<?php echo $pic->getId(); ?>"><i class="far fa-file-code icon" style="color:#00a9e0" data-toggle="tooltip" data-placement="top" title="JSON file ouput"></i></a>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="col-sm">
        <ul class="list-group list-group-flush">
          <li class="list-group-item list-group-item-primary"><b class="float-left">Observatory Name:</b> <a class="float-right" id="alt"><?php echo $pic->getObservatory()->getName(); ?></a></li>
          <li class="list-group-item list-group-item-primary"><b class="float-left">Short description:</b> <a class="float-right" id="alt"><?php echo $pic->getObservatory()->getShortDescription(); ?></a></li>
          <li class="list-group-item list-group-item-primary"><b class="float-left">Since:</b> <a class="float-right" id="alt"><?php echo $pic->getObservatory()->getDateCreated(); ?></a></li>
          <li class="list-group-item list-group-item-primary" data-toggle="tooltip" data-placement="top" title="Organisation responsible for data"><b class="float-left">Organization</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getOrigin(); ?></a></li>
          <li class="list-group-item list-group-item-success"><b class="float-left">Date observation:</b> <a class="float-right" id="alt"><?php echo $pic->getDateObs(); ?></a></li>
          <li class="list-group-item list-group-item-success"><b class="float-left">Date updated:</b> <a class="float-right" id="alt"><?php echo $pic->getDateUpdated(); ?></a></li>
          <li class="list-group-item list-group-item-success"><b class="float-left">Date uploaded:</b> <a class="float-right" id="alt"><?php echo $pic->getDateUpload(); ?></a></li>
          <li class="list-group-item list-group-item-dark"><b class="float-left">Filesize:</b> <a class="float-right" id="alt"><?php echo $pic->getFilesizeProcessed(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Observatory latitude"><b class="float-left">Latitude</b> <a class="float-right" id="alt" align="right"><?php echo $pic->getMetadata()->getLatitude(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Observatory longitude"><b class="float-left">Longitude</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getLongitud(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Observatory altitude"><b class="float-left">Altitude</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getAltitude(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Telescope right ascension"><b class="float-left">TEL-RA</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelRa(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Telescope declination"><b class="float-left">TEL-DEC</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelDec(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Telescope azimuth"><b class="float-left">TEL-AZ</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelAz(); ?></a></li>
          <li class="list-group-item list-group-item-info" data-toggle="tooltip" data-placement="top" title="Telescope altitude"><b class="float-left">TEL-ALT</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelAlt(); ?></a></li>
          <li class="list-group-item" data-toggle="tooltip" data-placement="top" title="Name of the data acqusition telescope"><b class="float-left">Telescop</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getTelescop(); ?></a></li>
          <li class="list-group-item" data-toggle="tooltip" data-placement="top" title="Name of the data acqusition instrument"><b class="float-left">Instrument</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getInstrume(); ?></a></li>
          <li class="list-group-item" data-toggle="tooltip" data-placement="top" title="Exposure length in milliseconds"><b class="float-left">Time exposure</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getExposure() ." ms"; ?></a></li>
          <li class="list-group-item" data-toggle="tooltip" data-placement="top" title="Path of the processing script"><b class="float-left">Script</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getScript(); ?></a></li>
          <li class="list-group-item" data-toggle="tooltip" data-placement="top" title=""><b class="float-left">Mount name</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getMntName(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Local Sidereal Time"><b class="float-left">LST</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getLst(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Position of the center of the Sun, e.g.(horizontal, vertical), in pixels relative to the top left corner"><b class="float-left">Sun XY-PX</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getSunXyPx(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Bits per pixel component"><b class="float-left">BITPIX</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getBitpix(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Number of Axis, i.e. dimensions, should be 2 or 3"><b class="float-left">NAXIS</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getNaxis(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Width in pixels"><b class="float-left">NAXIS1</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getNaxis1(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Height in pixels"><b class="float-left">NAXIS2</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getNaxis2(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Processing info and other relevant stuff"><b class="float-left">History</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getHistory(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Telescope flip: 0 = EAST , 1 = WEST"><b class="float-left">Mount flip</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getMntFlip(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Image is dark"><b class="float-left">Black</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getBlack(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Diameter of the Sun in pixels"><b class="float-left">EPH-SUN-DIAM-PX</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getEphSunDiamPx(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Shape of the original (BMP) image array"><b class="float-left">ORIGINAL-SHAPE</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getOriginalShape(); ?></a></li>
          <li class="list-group-item list-group-item-secondary" data-toggle="tooltip" data-placement="top" title="Color adjustment gamma"><b class="float-left">COLOR-GAMMA</b> <a class="float-right" id="alt"><?php echo $pic->getMetadata()->getColorGamma(); ?></a></li>
        </ul>
      </div>
    </div>
  </div>
  <!-- /.row -->
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
